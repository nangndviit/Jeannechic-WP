<?php
/**
 * Created by PhpStorm.
 * User: tsaricam
 * Date: 24/02/2023
 * Time: 10:19
 *
 * @since 1.13.0
 */

namespace WPCCrawler\Objects\Filtering\Commands\ActionCommands\PostPage;

use WPCCrawler\Exceptions\MethodNotExistException;
use WPCCrawler\Objects\Enums\ValueType;
use WPCCrawler\Objects\Filtering\Commands\ActionCommands\Base\AbstractActionCommand;
use WPCCrawler\Objects\Filtering\Commands\Enums\CommandShortCodeName;
use WPCCrawler\Objects\Filtering\Commands\Views\ViewDefinition;
use WPCCrawler\Objects\Filtering\Commands\Views\ViewDefinitionList;
use WPCCrawler\Objects\Filtering\Enums\CommandKey;
use WPCCrawler\Objects\Filtering\Enums\InputName;
use WPCCrawler\Objects\Informing\Informer;
use WPCCrawler\Objects\Transformation\Base\AbstractTransformationService;
use WPCCrawler\Objects\Transformation\Interfaces\Transformable;
use WPCCrawler\Objects\Value\ValueExtractor;
use WPCCrawler\Objects\Value\ValueSetter;
use WPCCrawler\Objects\Views\Enums\ViewVariableName;
use WPCCrawler\Objects\Views\Select\SelectPostTransformableFieldsWithLabel;
use WPCCrawler\Objects\Views\ShortCodeButtonsWithLabelForTemplateCmd;
use WPCCrawler\Objects\Views\TextAreaWithLabel;

class FieldTemplate extends AbstractActionCommand {

    /** @var string[]|null */
    private $fields = null;

    /** @var string|null */
    private $template = null;

    public function getKey(): string {
        return CommandKey::FIELD_TEMPLATE;
    }

    public function getName(): string {
        return _wpcc('Field template');
    }

    public function getInputDataTypes(): array {
        return [ValueType::T_POST_PAGE];
    }

    protected function isOutputTypeSameAsInputType(): bool {
        return true;
    }

    public function doesNeedSubjectValue(): bool {
        return false;
    }

    protected function isTestable(): bool {
        return false;
    }

    protected function createViews(): ?ViewDefinitionList {
        return (new ViewDefinitionList())
            // Add the transformable fields
            ->add((new ViewDefinition(SelectPostTransformableFieldsWithLabel::class))
                ->setVariable(ViewVariableName::TITLE, _wpcc('Fields'))
                ->setVariable(ViewVariableName::INFO,  _wpcc('Select the fields that will be changed by using the
                    template.'))
                ->setVariable(ViewVariableName::NAME,  InputName::TRANSFORMABLE_FIELDS)
            )

            // Add the short code buttons
            ->add((new ViewDefinition(ShortCodeButtonsWithLabelForTemplateCmd::class))
                ->setVariable(ViewVariableName::TITLE, _wpcc('Short codes'))
                ->setVariable(ViewVariableName::INFO,  _wpcc("Short codes that can be used in the template. You 
                    can hover over the short codes to see what they do. You can click to the short code buttons to copy 
                    the short codes. Then, you can paste the short codes into the template to include them. They will be 
                    replaced with their actual values."))
            )
            // Add the template
            ->add((new ViewDefinition(TextareaWithLabel::class))
                ->setVariable(ViewVariableName::TITLE, _wpcc('Template'))
                ->setVariable(ViewVariableName::INFO,  _wpcc("Enter your template. What you define here will be 
                    used as the new value of the specified fields. The original value will be replaced with this
                    template."))
                ->setVariable(ViewVariableName::NAME, InputName::TEMPLATE)
                ->setVariable(ViewVariableName::ROWS, 4)
                ->setVariable(ViewVariableName::PLACEHOLDER, _wpcc('New value of each item of each selected field...'))
            );
    }

    protected function onExecute($key, $subjectValue) {
        $logger = $this->getLogger();

        if (!$this->getTransformableFields()) {
            if ($logger) {
                $logger->addMessage(_wpcc('The template could not be applied, because there are not any selected fields.'));
            }
            return;
        }

        if ($this->getTemplateOption() === '') {
            if ($logger) {
                $logger->addMessage(_wpcc('The template could not be applied, because there is no template.'));
            }
            return;
        }

        $provider = $this->getProvider();
        if (!$provider) {
            if ($logger) {
                $logger->addMessage(_wpcc('The template could not be applied, because there is no dependency provider.'));
            }
            return;
        }

        // Transform the post data
        foreach($provider->getDataSourceMap() as $identifier => $data) {
            if (!($data instanceof Transformable)) {
                continue;
            }

            $this->transform(
                $data,
                $this->getTransformableFieldsForIdentifier($identifier)
            );
        }

    }

    /**
     * Transform specific fields of a transformable by applying the template
     *
     * @param Transformable $data   The data to be transformed
     * @param string[]      $fields The fields of the data, without an identifier
     * @since 1.13.0
     */
    protected function transform(Transformable $data, array $fields): void {
        // If there are no fields, stop.
        if (!$fields) {
            return;
        }

        $map = [];
        foreach ($fields as $field) {
            $map[$field] = '';
        }

        // Get the texts to transform
        $values = null;
        try {
            $values = (new ValueExtractor())->fillAndFlatten($data, $map);
        } catch (MethodNotExistException $e) {
            Informer::addError($e->getMessage())->setException($e)->addAsLog();
        }

        // If there is nothing to transform, stop.
        if (!$values) {
            return;
        }

        // Transform the values
        $transformedValues = $this->onTransformValues($values);

        // Put the transformed values to their original places
        $setter = new ValueSetter();
        try {
            $setter->set($data, $transformedValues);
        } catch (MethodNotExistException $e) {
            Informer::addError($e->getMessage())->setException($e)->addAsLog();
        }
    }

    /**
     * @param array $values The values that should be transformed
     * @return array The transformed values
     * @since 1.13.0
     */
    protected function onTransformValues(array $values): array {
        $logger = $this->getLogger();
        return array_map(function($value) use ($logger) {
            // If the value is not scalar, return it as-is. This will probably never be the case.
            if (!is_scalar($value)) {
                return $value;
            }

            if ($logger) {
                $logger->addSubjectItem((string) $value);
            }

            $applier = $this->createShortCodeApplier([CommandShortCodeName::ITEM => $value]);
            $result = $applier->apply($this->getTemplateOption());

            if ($logger) {
                $logger->addModifiedSubjectItem($result);
            }

            return $result;
        }, $values);
    }

    /**
     * @param string $identifier Prefix that is used to specify the type of transformable that the field belongs to.
     * @return string[] The fields that has the given identifier, with the identifier removed.
     * @since 1.13.0
     */
    protected function getTransformableFieldsForIdentifier(string $identifier): array {
        return AbstractTransformationService::getTransformableFieldsFromSelect(
            $this->getTransformableFields(),
            $identifier
        );
    }

    /*
     * OPTION GETTERS
     */

    /**
     * @return string[] The transformable fields specified in the options
     * @since 1.13.0
     */
    protected function getTransformableFields(): array {
        if ($this->fields === null) {
            $fields = $this->getArrayOption(InputName::TRANSFORMABLE_FIELDS) ?? [];
            $this->fields = array_filter($fields, function($field) {
                return is_string($field);
            });
        }

        return $this->fields;
    }

    /**
     * @return string The template option's value. If the template option is not defined, an empty string is returned.
     * @since 1.13.0
     */
    protected function getTemplateOption(): string {
        if ($this->template === null) {
            $template = $this->getStringOption(InputName::TEMPLATE);
            $this->template = $template === null
                ? ''
                : $template;
        }

        return $this->template;
    }

}