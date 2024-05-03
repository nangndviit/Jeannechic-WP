<?php

// Register the script
wp_register_script( 'hfcm_pro_showboxes', plugins_url( 'js/nnr-hfcm-pro-showboxes.js', dirname( __FILE__ ) ), array( 'jquery' ), NNR_HFCM_PRO::$nnr_hfcm_pro_version );

// prepare ID (for AJAX)
if ( !isset( $id ) ) {
    $id = -1;
}

// Localize the script with new data
$translation_array = array(
    'header'         => __( 'Header', '99robots-header-footer-code-manager-pro' ),
    'body_open'      => __( 'Body Open', '99robots-header-footer-code-manager-pro' ),
    'before_content' => __( 'Before Content', '99robots-header-footer-code-manager-pro' ),
    'after_content'  => __( 'After Content', '99robots-header-footer-code-manager-pro' ),
    'footer'         => __( 'Footer', '99robots-header-footer-code-manager-pro' ),
    'id'             => absint( $id ),
    'security'       => wp_create_nonce( 'hfcm-pro-get-posts' ),
);
wp_localize_script( 'hfcm_pro_showboxes', 'hfcm_pro_localize', $translation_array );

// Enqueued script with localized data.
wp_enqueue_script( 'hfcm_pro_showboxes' );
?>

<div class="wrap">
    <h1>
        <?php echo $update ? esc_html__( 'Edit Snippet', '99robots-header-footer-code-manager-pro' ) : esc_html__( 'Add New Snippet', '99robots-header-footer-code-manager-pro' ) ?>
        <?php if ( $update ) : ?>
            <a href="<?php echo admin_url( 'admin.php?page=hfcm-pro-create' ) ?>" class="page-title-action">
                <?php esc_html_e( 'Add New Snippet', '99robots-header-footer-code-manager-pro' ) ?>
            </a>
        <?php endif; ?>
    </h1>
    <?php
    if ( !empty( $_GET['message'] ) ) :
        if ( 1 === $_GET['message'] ) :
            ?>
            <div class="updated">
                <p><?php esc_html_e( 'Script updated', '99robots-header-footer-code-manager-pro' ); ?></p>
            </div>
            <a href="<?php echo admin_url( 'admin.php?page=hfcm-pro-list' ) ?>">&laquo; <?php esc_html_e( 'Back to list', '99robots-header-footer-code-manager-pro' ); ?></a>
        <?php elseif ( 6 === $_GET['message'] ) : ?>
            <div class="updated">
                <p><?php esc_html_e( 'Script Added Successfully', '99robots-header-footer-code-manager-pro' ); ?></p>
            </div>
            <a href="<?php echo admin_url( 'admin.php?page=hfcm-pro-list' ) ?>">&laquo; <?php esc_html_e( 'Back to list', '99robots-header-footer-code-manager-pro' ); ?></a>
        <?php
        endif;
    endif;

    $hfmc_pro_temp_error = get_transient( 'hfcm_pro_snippet_error' );
    if ( !empty( $hfmc_pro_temp_error ) || !empty( $nnr_snippet_error ) ) :
        $nnr_default_display_message = "The snippet was deactivated as it resulted in following error:";
        if ( !empty( $hfmc_pro_temp_error ) ) :
            $nnr_default_display_message = "We couldn't save the snippet as it resulted in following error:";
            $nnr_snippet_error           = $hfmc_pro_temp_error;
        endif;
        ?>
        <div class="error">
            <p>
                <?php echo esc_html( $nnr_default_display_message ); ?>
                <b><?php echo esc_html( $nnr_snippet_error ); ?></b>
            </p>
        </div>
        <?php
        delete_transient( 'hfcm_pro_snippet_error' );
    endif;

    if ( $update ) :
        $hfcm_pro_form_action = admin_url( 'admin.php?page=hfcm-pro-request-handler&id=' . absint( $id ) );
    else :
        $hfcm_pro_form_action = admin_url( 'admin.php?page=hfcm-pro-request-handler' );
    endif;
    ?>
    <?php
    if ( $nnr_snippet_type == 'php' && in_array( $location, [ 'header', 'footer' ] ) ) {
        ?>
        <p class="notice notice-warning nnr-padding-10">
            <?php
            _e( 'Note: We have changed the way php snippets are executed to support a broader range of php snippets.  PHP Snippets will no longer specify a location (header, footer, etc).  For snippets created prior to v1.0.11 that relied on HFCM to specify the location (header, footer) - these will need to be converted to a new format. For more information - click <a href="https://draftpress.com/docs/header-footer-code-manager-pro/#elementor-toc__heading-anchor-22" target="_blank">here</a>. Also, please note that once you edit and save a legacy snippet, the location will be changed to execute everywhere.', '99robots-header-footer-code-manager-pro' );
            ?>
        </p>
        <?php
    }
    ?>
    <form method="post" action="<?php echo $hfcm_pro_form_action ?>">
        <?php
        if ( $update ) :
            wp_nonce_field( 'update-snippet_' . absint( $id ) );
        else :
            wp_nonce_field( 'create-snippet' );
        endif;
        ?>
        <input type="hidden" id="nnr_hfcm_all_tags" value="<?php echo esc_attr( $nnr_hfcm_pro_all_tags ); ?>"/>
        <table class="wp-list-table widefat fixed hfcm-pro-form-width form-table">
            <tr>
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Snippet Name', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td><input type="text" name="data[name]" value="<?php echo esc_attr( $name ); ?>"
                           class="hfcm-pro-field-width"/>
                </td>
            </tr>
            <?php
            $nnr_hfcm_pro_snippet_type_array = array(
                'html' => esc_html__( 'HTML', '99robots-header-footer-code-manager-pro' ),
                'css'  => esc_html__( 'CSS', '99robots-header-footer-code-manager-pro' ),
                'js'   => esc_html__( 'Javascript', '99robots-header-footer-code-manager-pro' ),
                'php'  => esc_html__( 'PHP', '99robots-header-footer-code-manager-pro' )
            ); ?>
            <tr id="snippet_type">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Snippet Type', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[snippet_type]" id="nnr-snippet-type-select">
                        <?php
                        foreach ( $nnr_hfcm_pro_snippet_type_array as $nnr_key => $nnr_item ) {
                            if ( $nnr_key === $nnr_snippet_type ) {
                                echo "<option value='" . esc_attr( $nnr_key ) . "' selected>" . esc_html( $nnr_item ) . "</option>";
                            } else {
                                echo "<option value='" . esc_attr( $nnr_key ) . "'>" . esc_html( $nnr_item ) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
            $nnr_hfcm_pro_display_array = array(
                'All'            => esc_html__( 'Site Wide', '99robots-header-footer-code-manager-pro' ),
                's_posts'        => esc_html__( 'Specific Posts', '99robots-header-footer-code-manager-pro' ),
                's_pages'        => esc_html__( 'Specific Pages', '99robots-header-footer-code-manager-pro' ),
                's_categories'   => esc_html__( 'Specific Categories (Archive & Posts)', '99robots-header-footer-code-manager-pro' ),
                's_custom_posts' => esc_html__( 'Specific Post Types (Archive & Posts)', '99robots-header-footer-code-manager-pro' ),
                's_tags'         => esc_html__( 'Specific Tags (Archive & Posts)', '99robots-header-footer-code-manager-pro' ),
                's_is_home'      => esc_html__( 'Home Page', '99robots-header-footer-code-manager-pro' ),
                's_is_search'    => esc_html__( 'Search Page', '99robots-header-footer-code-manager-pro' ),
                's_is_archive'   => esc_html__( 'Archive Page', '99robots-header-footer-code-manager-pro' ),
                'latest_posts'   => esc_html__( 'Latest Posts', '99robots-header-footer-code-manager-pro' ),
                'manual'         => esc_html__( 'Shortcode Only', '99robots-header-footer-code-manager-pro' ),
                'admin'          => esc_html__( 'Admin (WP Backend)', '99robots-header-footer-code-manager-pro' ),
            ); ?>
            <tr>
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Site Display', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[display_on]" onchange="hfcm_pro_showotherboxes(this.value);">
                        <?php
                        foreach ( $nnr_hfcm_pro_display_array as $dkey => $statusv ) {
                            if ( $display_on === $dkey ) {
                                printf( '<option value="%1$s" selected="selected">%2$s</option>', $dkey, $statusv );
                            } else {
                                printf( '<option value="%1$s">%2$s</option>', $dkey, $statusv );
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
            $nnr_hfcm_pro_pages                      = get_pages(
                array(
                    'post_status' => array( 'publish', 'private' )
                )
            );
            $nnr_hfcm_pro_exclude_pages_style        = (in_array(
                $display_on, [ 'admin',
                               's_pages' ]
            )) ? 'display:none;' : '';
            $nnr_hfcm_pro_exclude_posts_style        = (in_array(
                $display_on, [ 'admin',
                               's_posts' ]
            )) ? 'display:none;' : '';
            $nnr_hfcm_pro_exclude_categories_style   = (in_array(
                $display_on, [ 'admin',
                               's_categories' ]
            )) ? 'display:none;' : '';
            $nnr_hfcm_pro_exclude_tags_style         = (in_array(
                $display_on, [ 'admin',
                               's_tags' ]
            )) ? 'display:none;' : '';
            $nnr_hfcm_pro_exclude_custom_posts_style = (in_array(
                $display_on, [ 'admin',
                               's_custom_posts' ]
            )) ? 'display:none;' : '';
            $nnr_hfcm_pro_exclude_lp_count_style     = (in_array(
                $display_on, [ 'admin',
                               'latest_posts' ]
            )) ? 'display:none;' : '';
            $nnr_hfcm_pro_exclude_manual_style       = (in_array(
                $display_on, [ 'admin',
                               'manual' ]
            )) ? 'display:none;' : '';
            ?>
            <tr id="ex_pages"
                style="<?php echo esc_attr( $nnr_hfcm_pro_exclude_pages_style . $nnr_hfcm_pro_exclude_posts_style . $nnr_hfcm_pro_exclude_tags_style . $nnr_hfcm_pro_exclude_custom_posts_style . $nnr_hfcm_pro_exclude_categories_style . $nnr_hfcm_pro_exclude_lp_count_style . $nnr_hfcm_pro_exclude_manual_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Exclude Pages', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[ex_pages][]" multiple>
                        <?php
                        foreach ( $nnr_hfcm_pro_pages as $pdata ) {
                            if ( in_array( $pdata->ID, $ex_pages ) ) {
                                printf( '<option value="%1$s" selected="selected">%2$s</option>', $pdata->ID, $pdata->post_title );
                            } else {
                                printf( '<option value="%1$s">%2$s</option>', $pdata->ID, $pdata->post_title );
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="ex_posts"
                style="<?php echo esc_attr( $nnr_hfcm_pro_exclude_pages_style . $nnr_hfcm_pro_exclude_posts_style . $nnr_hfcm_pro_exclude_tags_style . $nnr_hfcm_pro_exclude_custom_posts_style . $nnr_hfcm_pro_exclude_categories_style . $nnr_hfcm_pro_exclude_lp_count_style . $nnr_hfcm_pro_exclude_manual_style ); ?>">
                <th class="hfcm-pro-th-width">
                    <?php esc_html_e( 'Exclude Posts', '99robots-header-footer-code-manager-pro' ); ?>
                </th>
                <td>
                    <select class="nnr-wraptext" name="data[ex_posts][]" multiple>
                        <option disabled></option>
                    </select> <img id="loader"
                                   src="<?php echo plugins_url( 'images/ajax-loader.gif', dirname( __FILE__ ) ); ?>">
                </td>
            </tr>
            <?php
            $nnr_hfcm_pro_pages       = get_pages(
                array(
                    'post_status' => array( 'publish', 'private' )
                )
            );
            $nnr_hfcm_pro_pages_style = ('s_pages' === $display_on) ? '' : 'display:none;';
            ?>
            <tr id="s_pages" style="<?php echo esc_attr( $nnr_hfcm_pro_pages_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Page List', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[s_pages][]" multiple>
                        <?php
                        foreach ( $nnr_hfcm_pro_pages as $pdata ) {
                            if ( in_array( $pdata->ID, $s_pages ) ) {
                                printf( '<option value="%1$s" selected="selected">%2$s</option>', esc_attr( $pdata->ID ), esc_attr( $pdata->post_title ) );
                            } else {
                                printf( '<option value="%1$s">%2$s</option>', esc_attr( $pdata->ID ), esc_attr( $pdata->post_title ) );
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php $nnr_hfcm_pro_posts_style = 's_posts' === $display_on ? '' : 'display:none;'; ?>
            <tr id="s_posts" style="<?php echo esc_attr( $nnr_hfcm_pro_posts_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Post List', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select class="nnr-wraptext" name="data[s_posts][]" multiple>
                        <option disabled>...</option>
                    </select>
                </td>
            </tr>
            <?php
            $nnr_hfcm_pro_categories = NNR_HFCM_PRO::hfcm_pro_get_categories();
            $nnr_hfcm_pro_tags       = NNR_HFCM_PRO::hfcm_pro_get_tags();

            $nnr_hfcm_pro_categories_style   = 's_categories' === $display_on ? '' : 'display:none;';
            $nnr_hfcm_pro_tags_style         = 's_tags' === $display_on ? '' : 'display:none;';
            $nnr_hfcm_pro_custom_posts_style = 's_custom_posts' === $display_on ? '' : 'display:none;';
            $nnr_hfcm_pro_lpcount_style      = 'latest_posts' === $display_on ? '' : 'display:none;';
            $nnr_hfcm_pro_location_style     = 'manual' === $display_on ? 'display:none;' : '';

            // Get all names of Post Types
            $args = array(
                'public' => true,
            );

            $output   = 'names';
            $operator = 'and';

            $nnr_hfcm_pro_custom_post_types = get_post_types( $args, $output, $operator );
            $nnr_hfcm_pro_post_types        = array( 'post' );
            foreach ( $nnr_hfcm_pro_custom_post_types as $cpdata ) {
                $nnr_hfcm_pro_post_types[] = $cpdata;
            }
            ?>
            <tr id="s_categories" style="<?php echo esc_attr( $nnr_hfcm_pro_categories_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Category List', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[s_categories][]" multiple>
                        <?php
                        foreach ( $nnr_hfcm_pro_categories as $nnr_key_cat => $nnr_item_cat ) {
                            foreach ( $nnr_item_cat['terms'] as $nnr_item_cat_key => $nnr_item_cat_term ) {
                                if ( in_array( $nnr_item_cat_term->term_id, $s_categories ) ) {
                                    echo "<option value='" . esc_attr( $nnr_item_cat_term->term_id ) . "' selected>" . esc_html( $nnr_item_cat['name'] ) . " - " . esc_html( $nnr_item_cat_term->name ) . "</option>";
                                } else {
                                    echo "<option value='" . esc_attr( $nnr_item_cat_term->term_id ) . "'>" . esc_html( $nnr_item_cat['name'] ) . " - " . esc_html( $nnr_item_cat_term->name ) . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="s_tags" style="<?php echo esc_attr( $nnr_hfcm_pro_tags_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Tags List', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[s_tags][]" multiple>
                        <?php
                        foreach ( $nnr_hfcm_pro_tags as $nnr_key_cat => $nnr_item_tag ) {
                            foreach ( $nnr_item_tag['terms'] as $nnr_item_tag_key => $nnr_item_tag_term ) {
                                if ( in_array( $nnr_item_tag_term->term_id, $s_tags ) ) {
                                    echo "<option value='" . esc_attr( $nnr_item_tag_term->term_id ) . "' selected>" . esc_html( $nnr_item_tag['name'] ) . " - " . esc_attr( $nnr_item_tag_term->name ) . "</option>";
                                } else {
                                    echo "<option value='" . esc_attr( $nnr_item_tag_term->term_id ) . "'>" . esc_html( $nnr_item_tag['name'] ) . " - " . esc_attr( $nnr_item_tag_term->name ) . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="c_posttype" style="<?php echo esc_attr( $nnr_hfcm_pro_custom_posts_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Post Types', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[s_custom_posts][]" multiple>
                        <?php
                        foreach ( $nnr_hfcm_pro_custom_post_types as $cpkey => $cpdata ) {
                            if ( in_array( $cpkey, $s_custom_posts ) ) {
                                echo "<option value='" . esc_attr( $cpkey ) . "' selected>" . esc_html( $cpdata ) . "</option>";
                            } else {
                                echo "<option value='" . esc_attr( $cpkey ) . "'>" . esc_html( $cpdata ) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="lp_count" style="<?php echo esc_attr( $nnr_hfcm_pro_lpcount_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Post Count', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[lp_count]">
                        <?php
                        for ( $i = 1; $i <= 20; $i++ ) {
                            if ( $i == $lp_count ) {
                                echo "<option value='" . esc_attr( $i ) . "' selected>" . esc_html( $i ) . "</option>";
                            } else {
                                echo "<option value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
            $nnr_hfcm_pro_locations = array(
                'header'         => 'Header',
                'body_open'      => 'After Body Open Tag',
                'before_content' => 'Before Content',
                'after_content'  => 'After Content',
                'footer'         => 'Footer'
            );
            ?>
            <tr id="locationtr" style="<?php echo esc_attr( $nnr_hfcm_pro_location_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Location', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[location]" id="data_location">
                        <?php
                        foreach ( $nnr_hfcm_pro_locations as $lkey => $statusv ) {
                            if ( $location == $lkey ) {
                                echo "<option value='" . esc_attr( $lkey ) . "' selected='selected'>" . esc_html( $statusv ) . '</option>';
                            } else {
                                echo "<option value='" . esc_attr( $lkey ) . "'>" . esc_html( $statusv ) . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <p>
                        <b><?php _e( "Note", '99robots-header-footer-code-manager-pro' ); ?></b>: <?php _e( "Not all locations (such as before content and body tags) exist on all page/post types. The location will only appear as an option if the appropriate hook exists on the page.", '99robots-header-footer-code-manager-pro' ); ?>
                        .</p>
                </td>
            </tr>
            <?php $nnr_hfcm_pro_device_type_array = array(
                'both'    => __( 'Show on All Devices', '99robots-header-footer-code-manager-pro' ),
                'desktop' => __( 'Only Desktop', '99robots-header-footer-code-manager-pro' ),
                'mobile'  => __( 'Only Mobile Devices', '99robots-header-footer-code-manager-pro' )
            ) ?>
            <?php
            $nnr_hfcm_pro_display_to_array = array(
                'all'           => __( 'All', '99robots-header-footer-code-manager-pro' ),
                'logged-in'     => __( 'Logged In Users', '99robots-header-footer-code-manager-pro' ),
                'non-logged-in' => __( 'Non Logged In Users', '99robots-header-footer-code-manager-pro' )
            );
            ?>
            <?php $nnr_hfcm_pro_status_array   = array(
                'active'   => __( 'Active', '99robots-header-footer-code-manager-pro' ),
                'inactive' => __( 'Inactive', '99robots-header-footer-code-manager-pro' )
            );
            $nnr_hfcm_pro_device_display_style = '';
            $nnr_hfcm_pro_display_to_style     = '';
            if ( in_array( $display_on, [ 'admin' ] ) ) {
                $nnr_hfcm_pro_display_to_style = 'display:none;';
            }
            ?>
            <tr id="nnr-device-display" style="<?php echo esc_attr( $nnr_hfcm_pro_device_display_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Device Display', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[device_type]">
                        <?php
                        foreach ( $nnr_hfcm_pro_device_type_array as $smkey => $typev ) {
                            if ( $device_type === $smkey ) {
                                echo "<option value='" . esc_attr( $smkey ) . "' selected='selected'>" . esc_html( $typev ) . '</option>';
                            } else {
                                echo "<option value='" . esc_attr( $smkey ) . "'>" . esc_html( $typev ) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="nnr-display-to" style="<?php echo esc_attr( $nnr_hfcm_pro_display_to_style ); ?>">
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Display To', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[display_to]">
                        <?php
                        foreach ( $nnr_hfcm_pro_display_to_array as $smkey => $typev ) {
                            if ( $display_to === $smkey ) {
                                echo "<option value='" . esc_attr( $smkey ) . "' selected='selected'>" . esc_html( $typev ) . '</option>';
                            } else {
                                echo "<option value='" . esc_attr( $smkey ) . "'>" . esc_html( $typev ) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Status', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td>
                    <select name="data[status]">
                        <?php
                        foreach ( $nnr_hfcm_pro_status_array as $skey => $statusv ) {
                            if ( $status === $skey ) {
                                echo "<option value='" . esc_attr( $skey ) . "' selected='selected'>" . esc_html( $statusv ) . '</option>';
                            } else {
                                echo "<option value='" . esc_attr( $skey ) . "'>" . esc_html( $statusv ) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Priority', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td><input type="number" min="0" max="100" name="data[priority]"
                           value="<?php echo esc_attr( $nnr_snippet_priority ); ?>"
                           class="hfcm-pro-field-width"/>
                    <p>
                        <b><?php _e( "Note", '99robots-header-footer-code-manager-pro' ); ?></b>: <?php _e( "In case of equal priority, latest snippet will render first.", '99robots-header-footer-code-manager-pro' ); ?>
                    </p>
                    <p>
                        <b><?php _e( "Note", '99robots-header-footer-code-manager-pro' ); ?></b>: <?php _e( "Lower priority snippet will render first, higher priority snippet will render last.", '99robots-header-footer-code-manager-pro' ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th class="hfcm-pro-th-width"><?php esc_html_e( 'Tags', '99robots-header-footer-code-manager-pro' ); ?></th>
                <td class="nnr-hfcm-pro-tag-box">
                    <input type="text" autocomplete="off" name="data[tags]"
                           class="hfcm-pro-field-width nnr-hfcm-pro-tags"
                           value="<?php echo esc_attr( $nnr_hfcm_pro_snippet_tags ); ?>">
                    <p>
                        <b><?php _e( "Note", '99robots-header-footer-code-manager-pro' ); ?></b>: <?php _e( "Please enter tags as comma separated", '99robots-header-footer-code-manager-pro' ); ?>
                    </p>
                </td>
            </tr>
            <?php if ( $update ) : ?>
                <tr>
                    <th class="hfcm-pro-th-width"><?php esc_html_e( 'Shortcode', '99robots-header-footer-code-manager-pro' ); ?></th>
                    <td>
                        <p>
                            [hfcm id="<?php echo esc_html( $id ); ?>"]

                            <a data-shortcode='[hfcm id="<?php echo esc_html( $id ); ?>"]' href="javascript:void(0);"
                               class="nnr-btn-click-to-copy nnr-hfcm-pro-btn-copy-inline" id="hfcm_pro_copy_shortcode">
                                <?php esc_html_e( 'Copy', '99robots-header-footer-code-manager' ); ?>
                            </a>
                        </p>

                    </td>
                </tr>
                <tr>
                    <th class="hfcm-pro-th-width"><?php esc_html_e( 'Changelog', '99robots-header-footer-code-manager-pro' ); ?></th>
                    <td>
                        <p>
                            <?php esc_html_e( 'Snippet created by', '99robots-header-footer-code-manager-pro' ); ?>
                            <b><?php echo esc_html( $createdby ); ?></b> <?php echo _e( 'on', '99robots-header-footer-code-manager-pro' ) . ' '; ?> <?php echo date_i18n( get_option( 'date_format' ), strtotime( $createdon ) ) . ' ' . __( 'at', '99robots-header-footer-code-manager-pro' ) . ' ' . date_i18n( get_option( 'time_format' ), strtotime( $createdon ) ) ?>
                            <br/>
                            <?php if ( !empty( $lastmodifiedby ) ) : ?>
                                <?php esc_html_e( 'Last edited by', '99robots-header-footer-code-manager-pro' ); ?>
                                <b><?php echo esc_html( $lastmodifiedby ); ?></b> <?php echo _e( 'on', '99robots-header-footer-code-manager-pro' ) . ' '; ?><?php echo date_i18n( get_option( 'date_format' ), strtotime( $lastrevisiondate ) ) . ' ' . __( 'at', '99robots-header-footer-code-manager-pro' ) . ' ' . date_i18n( get_option( 'time_format' ), strtotime( $lastrevisiondate ) ) ?>
                            <?php endif; ?>
                        </p>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
        <div class="nnr-mt-20">
            <h1><?php esc_html_e( 'Snippet', '99robots-header-footer-code-manager-pro' ); ?>
                / <?php esc_html_e( 'Code', '99robots-header-footer-code-manager-pro' ) ?></h1>
            <p class="notice notice-warning nnr-display-none" id="php-snippet-warning">
                <?php _e( "Warning: You’re adding a PHP snippet to the site. Please make sure the code you’re adding is absolutely correct. Adding bad or improperly written code may result in site crashes causing you to be locked out of your site. Do this at your own risk.", "99robots-header-footer-code-manager-pro" ); ?>
            </p>
            <?php
            if ( $nnr_snippet_type == 'php' && in_array( $location, [ 'header', 'footer' ] ) ) {
                ?>
                <p class="notice notice-warning nnr-padding-10">
                    <?php
                    _e( 'Note: We have changed the way php snippets are executed to support a broader range of php snippets.  PHP Snippets will no longer specify a location (header, footer, etc).  For snippets created prior to v1.0.11 that relied on HFCM to specify the location (header, footer) - these will need to be converted to a new format. For more information - click <a href="https://draftpress.com/docs/header-footer-code-manager-pro/#elementor-toc__heading-anchor-22" target="_blank">here</a>. Also, please note that once you edit and save a legacy snippet, the location will be changed to execute everywhere.', '99robots-header-footer-code-manager-pro' );
                    ?>
                </p>
                <?php
            }
            ?>
            <div class="nnr-mt-20 nnr-hfcm-pro-codeeditor-box">
                    <textarea name="data[snippet]" aria-describedby="nnr-newcontent-description"
                              id="nnr_hfcm_pro_newcontent"
                              rows="20"><?php echo html_entity_decode( $snippet ); ?></textarea>
                <div class="wp-core-ui">
                    <input type="submit"
                           name="<?php echo $update ? 'update' : 'insert'; ?>"
                           value="<?php echo $update ? esc_html__( 'Update', '99robots-header-footer-code-manager-pro' ) : esc_html__( 'Save', '99robots-header-footer-code-manager-pro' ) ?>"
                           class="button button-primary button-large nnr-btnsave">
                    <?php if ( $update ) :
                        $delete_nonce = wp_create_nonce( 'hfcm_pro_delete_snippet' );
                        ?>
                        <a onclick="return nnr_hfcm_pro_confirm_delete_snippet();"
                           href="<?php echo esc_url( admin_url( 'admin.php?page=hfcm-pro-list&action=delete&_wpnonce=' . $delete_nonce . '&snippet=' . $id ) ); ?>"
                           class="button button-secondary button-large nnr-btndelete"><?php esc_html_e( 'Delete', '99robots-header-footer-code-manager-pro' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>
