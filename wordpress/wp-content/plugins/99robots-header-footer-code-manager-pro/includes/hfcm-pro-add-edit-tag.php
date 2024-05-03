<?php
// Register the script
wp_register_script('hfcm_pro_showboxes', plugins_url('js/nnr-hfcm-pro-showboxes.js', dirname(__FILE__)), array( 'jquery' ), NNR_HFCM_PRO::$nnr_hfcm_pro_version);

// prepare ID (for AJAX)
if (!isset($id) ) {
    $id = -1;
}

// Localize the script with new data
$translation_array = array(
    'header'         => __('Header', '99robots-header-footer-code-manager-pro'),
    'body_open'      => __('Body Open', '99robots-header-footer-code-manager-pro'),
    'before_content' => __('Before Content', '99robots-header-footer-code-manager-pro'),
    'after_content'  => __('After Content', '99robots-header-footer-code-manager-pro'),
    'footer'         => __('Footer', '99robots-header-footer-code-manager-pro'),
    'id'             => absint($id),
    'security'       => wp_create_nonce('hfcm-pro-get-posts'),
);
wp_localize_script('hfcm_pro_showboxes', 'hfcm_pro_localize', $translation_array);

// Enqueued script with localized data.
wp_enqueue_script('hfcm_pro_showboxes');
?>

<div class="wrap">
    <h1>
        <?php echo $update ? esc_html__('Edit Tag', '99robots-header-footer-code-manager-pro') : esc_html__('Add New Tag', '99robots-header-footer-code-manager-pro') ?>
        <?php if ($update ) : ?>
            <a href="<?php echo admin_url('admin.php?page=hfcm-pro-tag-create') ?>" class="page-title-action">
                <?php esc_html_e('Add New Tag', '99robots-header-footer-code-manager-pro') ?>
            </a>
        <?php endif; ?>
    </h1>
    <?php
    if (!empty($_GET['message']) ) :
        if (1 === $_GET['message'] ) :
            ?>
            <div class="updated">
                <p><?php esc_html_e('Script updated', '99robots-header-footer-code-manager-pro'); ?></p>
            </div>
            <a href="<?php echo admin_url('admin.php?page=hfcm-pro-list') ?>">&laquo; <?php esc_html_e('Back to list', '99robots-header-footer-code-manager-pro'); ?></a>
        <?php elseif (6 === $_GET['message'] ) : ?>
            <div class="updated">
                <p><?php esc_html_e('Script Added Successfully', '99robots-header-footer-code-manager-pro'); ?></p>
            </div>
            <a href="<?php echo admin_url('admin.php?page=hfcm-pro-tags-list') ?>">&laquo; <?php esc_html_e('Back to list', '99robots-header-footer-code-manager-pro'); ?></a>
            <?php
        endif;
    endif;


    if ($update ) :
        $hfcm_pro_form_action = admin_url('admin.php?page=hfcm-pro-tag-request-handler&id=' . absint($id));
    else :
        $hfcm_pro_form_action = admin_url('admin.php?page=hfcm-pro-tag-request-handler');
    endif;
    ?>
    <form method="post" action="<?php echo $hfcm_pro_form_action ?>">
        <?php
        if ($update ) :
            wp_nonce_field('update-tag_' . absint($id));
        else :
            wp_nonce_field('create-tag');
        endif;
        ?>
        <table class="wp-list-table widefat fixed hfcm-pro-form-width form-table">
            <tr>
                <th class="hfcm-pro-th-width"><?php esc_html_e('Tag Name', '99robots-header-footer-code-manager-pro'); ?></th>
                <td><input type="text" name="data[tag]" value="<?php echo esc_attr($tag); ?>"
                           class="hfcm-pro-field-width"/>
                </td>
            </tr>
        </table>
        <div class="wrap">
            <div class="wrap">
                <div class="wp-core-ui">
                    <input type="submit"
                           name="<?php echo $update ? 'update' : 'insert'; ?>"
                           value="<?php echo $update ? esc_html__('Update', '99robots-header-footer-code-manager-pro') : esc_html__('Save', '99robots-header-footer-code-manager-pro') ?>"
                           class="button button-primary button-large nnr-btnsave">
                    <?php if ($update ) :
                        $delete_nonce = wp_create_nonce('hfcm_pro_delete_tag');
                        ?>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=hfcm-pro-tags-list&action=delete&_wpnonce=' . $delete_nonce . '&tag=' . $id)); ?>"
                           class="button button-secondary button-large nnr-btndelete"><?php esc_html_e('Delete', '99robots-header-footer-code-manager-pro'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>
