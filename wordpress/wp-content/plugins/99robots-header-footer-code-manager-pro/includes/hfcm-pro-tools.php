<?php

// Register the script
wp_register_script('hfcm_showboxes', plugins_url('js/nnr-hfcm-pro-showboxes.js', dirname(__FILE__)), array('jquery'), NNR_HFCM_PRO::$nnr_hfcm_pro_version);


// Localize the script with new data
$translation_array = array(
    'header'         => __('Header', '99robots-header-footer-code-manager-pro'),
    'before_content' => __('Before Content', '99robots-header-footer-code-manager-pro'),
    'after_content'  => __('After Content', '99robots-header-footer-code-manager-pro'),
    'footer'         => __('Footer', '99robots-header-footer-code-manager-pro'),
    'security' => wp_create_nonce('hfcm-get-posts'),
);
wp_localize_script('hfcm_showboxes', 'hfcm_localize', $translation_array);

// Enqueued script with localized data.
wp_enqueue_script('hfcm_showboxes');
?>

<div class="wrap">
    <h1>
        <?php _e('Tools', '99robots-header-footer-code-manager-pro'); ?>
    </h1>
    <div class="hfcm-pro-meta-box-wrap hfcm-pro-grid">
        <div id="normal-sortables" class="meta-box-sortables">
            <div id="hfcm-pro-admin-tool-export" class="postbox ">
                <div class="postbox-header">
                    <h2 class="hndle">
                        <?php _e('Export Snippets', '99robots-header-footer-code-manager-pro'); ?>
                    </h2>
                </div>
                <div class="inside">
                    <form method="post">
                        <p>
                            <?php _e(
                                'Select the snippets you would like to export and then select your export method. Use the
                            download button to export to a .json file which you can then import to another HFCM
                            installation', '99robots-header-footer-code-manager-pro'
                            ); ?>.
                        </p>
                        <div class="hfcm-notice notice-warning">
                            <p><?php _e('NOTE: Import/Export Functionality is only intended to operate within the same website.  Using the export/import to move snippets from one website to a different site, may result in inconsistent behavior, particularly if you have specific elements as criteria such as pages, posts, categories, or tags.', '99robots-header-footer-code-manager-pro'); ?></p>
                        </div>
                        <div class="hfcm-pro-fields">
                            <div class="hfcm-pro-field hfcm-pro-field-checkbox" data-name="keys" data-type="checkbox">
                                <div class="hfcm-pro-label">
                                    <label for="keys">
                                        <?php _e('Select Snippets', '99robots-header-footer-code-manager-pro'); ?>
                                    </label>
                                </div>
                                <div class="hfcm-pro-input">
                                    <input type="hidden" name="keys">
                                    <ul class="hfcm-pro-checkbox-list hfcm-pro-bl">
                                        <?php if (!empty($nnr_hfcm_pro_snippets)) {
                                            foreach ($nnr_hfcm_pro_snippets as $nnr_key => $nnr_hfcm_pro_snippet) {
                                                ?>
                                                <li>
                                                    <label>
                                                        <input type="checkbox"
                                                               id="keys-snippet_<?php echo esc_attr($nnr_hfcm_pro_snippet->script_id); ?>"
                                                               name="nnr_hfcm_pro_snippet[]"
                                                               value="snippet_<?php echo esc_attr($nnr_hfcm_pro_snippet->script_id); ?>"> <?php echo esc_html($nnr_hfcm_pro_snippet->name); ?>
                                                    </label>
                                                </li>
                                                <?php
                                            }
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <p class="hfcm-pro-submit">
                            <button type="submit" name="action" class="button button-primary" value="download">
                                <?php _e('Export File', '99robots-header-footer-code-manager-pro'); ?>
                            </button>
                        </p>
                        <?php wp_nonce_field('hfcm-pro-nonce'); ?>
                    </form>
                </div>
            </div>
            <div id="hfcm-pro-admin-tool-import" class="postbox ">
                <div class="postbox-header">
                    <h2 class="hndle">
                        <?php _e('Import Snippets', '99robots-header-footer-code-manager-pro'); ?>
                    </h2>
                </div>
                <div class="inside">
                    <form method="post" enctype="multipart/form-data">
                        <p>
                            <?php _e(
                                'Select the HFCM JSON file you would like to import. When you click the import button below,
                            HFCM will import the field groups.', '99robots-header-footer-code-manager-pro'
                            ); ?>
                        </p>
                        <div class="hfcm-pro-fields">
                            <div class="hfcm-pro-field hfcm-pro-field-file" data-name="nnr_hfcm_pro_import_file"
                                 data-type="file">
                                <div class="hfcm-pro-label">
                                    <label for="nnr_hfcm_pro_import_file">
                                        <?php _e('Select File', '99robots-header-footer-code-manager-pro'); ?>
                                    </label>
                                </div>
                                <div class="hfcm-pro-input">
                                    <div class="hfcm-pro-file-uploader" data-library="all" data-mime_types=""
                                         data-uploader="basic">
                                        <div class="hide-if-value">
                                            <label class="hfcm-pro-basic-uploader">
                                                <input type="file" name="nnr_hfcm_pro_import_file"
                                                       id="nnr_hfcm_pro_import_file">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="hfcm-pro-submit">
                            <input type="submit" class="button button-primary" value="<?php _e('Import', '99robots-header-footer-code-manager-pro'); ?>">
                        </p>
                        <?php wp_nonce_field('hfcm-pro-nonce'); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
