<?php
namespace AIOSEO\Plugin\Addon\Redirects\Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use AIOSEO\Plugin\Common\Traits;

/**
 * Class that holds all internal options for AIOSEO.
 *
 * @since 1.0.0
 */
class InternalOptions {
	use Traits\Options;

	/**
	 * All the default options.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $defaults = [
		// phpcs:disable WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound
		'internal' => [
			'lastActiveVersion' => [ 'type' => 'string', 'default' => '0.0' ]
		]
		// phpcs:enable WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound
	];

	/**
	 * Holds a list of all the possible deprecated options.
	 *
	 * @since 1.2.5
	 *
	 * @var array
	 */
	protected $allDeprecatedOptions = [];

	/**
	 * The Construct method.
	 *
	 * @since 1.0.0
	 *
	 * @param string $optionsName An array of options.
	 */
	public function __construct( $optionsName = 'aioseo_redirect_options_internal' ) {
		$this->optionsName = $optionsName;

		$this->init();

		add_action( 'shutdown', [ $this, 'save' ] );
	}

	/**
	 * Initializes the options.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init() {
		// Options from the DB.
		$dbOptions = json_decode( get_option( $this->optionsName ), true );
		if ( empty( $dbOptions ) ) {
			$dbOptions = [];
		}

		// Refactor options.
		$this->defaultsMerged = array_replace_recursive( $this->defaults, $this->defaultsMerged );

		$options = array_replace_recursive(
			$this->defaultsMerged,
			$this->addValueToValuesArray( $this->defaultsMerged, $dbOptions )
		);

		aioseo()->core->optionsCache->setOptions( $this->optionsName, apply_filters( 'aioseo_get_redirect_options_internal', $options ) );
	}

	/**
	 * Get all the deprecated options.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function getAllDeprecatedOptions() {
		return $this->allDeprecatedOptions;
	}

	/**
	 * Sanitizes, then saves the options to the database.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $options An array of options to sanitize, then save.
	 * @return void
	 */
	public function sanitizeAndSave( $options ) {
		if ( ! is_array( $options ) ) {
			return;
		}

		// First, recursively replace the new options into the cached state.
		// It's important we use the helper method since we want to replace populated arrays with empty ones if needed (when a setting was cleared out).
		$cachedOptions = aioseo()->core->optionsCache->getOptions( $this->optionsName );
		$dbOptions     = aioseo()->helpers->arrayReplaceRecursive(
			$cachedOptions,
			$this->addValueToValuesArray( $cachedOptions, $options, [], true )
		);

		// Now, we must also intersect both arrays to delete any individual keys that were unset.
		// We must do this because, while arrayReplaceRecursive will update the values for keys or empty them out,
		// it will keys that aren't present in the replacement array unaffected in the target array.
		$dbOptions = aioseo()->helpers->arrayIntersectRecursive(
			$dbOptions,
			$this->addValueToValuesArray( $cachedOptions, $options, [], true ),
			'value'
		);

		// Update the cache state.
		aioseo()->core->optionsCache->setOptions( $this->optionsName, $dbOptions );

		// Finally, save the new values to the DB.
		$this->save( true );
	}
}