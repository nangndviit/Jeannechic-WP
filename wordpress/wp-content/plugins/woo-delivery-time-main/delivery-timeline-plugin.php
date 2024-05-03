<?php
/*
Plugin Name: Woo Delivery Timeline
Description: Plugin to display a delivery timeline on WooCommerce product pages.
Version: 1.7.1
Author: CuongPham
Author URI: https://cuongwp.com
License: GPLv2 or later
Text Domain: delivery-timeline-plugin
*/

// Enqueue necessary scripts and styles
function delivery_timeline_enqueue_scripts() {
    if (is_admin()) {
        wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), '4.0.13');
        wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
        wp_enqueue_style('delivery-timeline-plugin-style', plugins_url('/css/delivery-timeline-admin.css', __FILE__));
    } else {
        wp_enqueue_style('delivery-timeline-plugin-style', plugins_url('/css/delivery-timeline.css', __FILE__));
        wp_enqueue_script('popper', plugins_url('/js/popper.min.js', __FILE__), array(), false, true);
        wp_enqueue_script('tippy', plugins_url('/js/tippy-bundle.umd.min.js', __FILE__), array(), false, true);
        wp_enqueue_script('delivery-timeline-plugin-script', plugins_url('/js/delivery-timeline.js', __FILE__), array(), false, true);
    }
}
add_action('wp_enqueue_scripts', 'delivery_timeline_enqueue_scripts');
add_action('admin_enqueue_scripts', 'delivery_timeline_enqueue_scripts');


// Add custom menu page for plugin options
function delivery_timeline_add_options_page() {
    add_submenu_page(
        'woocommerce',
        'Woo Delivery Timeline',
        'Woo Delivery Timeline',
        'manage_options',
        'woo-delivery-timeline',
        'delivery_timeline_plugin_options_page'
    );
}
add_action( 'admin_menu', 'delivery_timeline_add_options_page' );

// Render the plugin options page
function delivery_timeline_plugin_options_page() {
    ?>
<div class="wrap">
    <h1>Delivery Timeline Settings</h1>
    <section class="delivery-timeline-plugin-section">
        <form action="options.php" method="post">
            <?php settings_fields( 'delivery-timeline-plugin-settings-group' ); ?>
            <p><?php _e("Note: Nếu nội dung cài đặt timeline trùng nhau, hệ thống sẽ áp dụng timeline đầu tiên.", "woo-delivery-timeline") ?></p>
            <?php do_settings_sections( 'delivery-timeline-plugin' ); ?>
            <?php submit_button(); ?>
        </form>
    </section>
    <p>Plugin này <b>MIỄN PHÍ</b>, cấm các hình thức mua bán</p>
    <p>Bạn có thể ủng hộ mình một ly cafe bằng cách chuyển vào MOMO số điện thoại <a href="https://me.momo.vn/cuongwp"
            target="_blank">0794652822</a>.</p>
    <p>Nếu bạn cần hỗ trợ hoặc thiết kế website thì add Zalo mình nhé :)</p>

</div>
<?php
}

// Register the plugin settings
function delivery_timeline_plugin_settings() {
    register_setting( 'delivery-timeline-plugin-settings-group', 'delivery_timeline_options', array(
        'sanitize_callback' => 'delivery_timeline_options_validate',
        'default'           => delivery_timeline_options_default()
    ) );

    add_settings_section(
        'delivery_timeline_plugin_general_section',
        '',
        '',
        'delivery-timeline-plugin'
    );

    add_settings_field(
        'delivery_timeline_timelines',
        '',
        'delivery_timeline_timelines_callback',
        'delivery-timeline-plugin',
        'delivery_timeline_plugin_general_section'
    );

    // Add the display option field
    add_settings_field(
        'delivery_timeline_display_option',
        'Display Option',
        'delivery_timeline_display_option_callback',
        'delivery-timeline-plugin',
        'delivery_timeline_plugin_general_section'
    );

    
    if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {
        add_settings_error( 'delivery-timeline-plugin-notices', 'settings_saved', 'Options saved.', 'updated' );
    }

    settings_errors( 'delivery-timeline-plugin-notices' );
}
add_action( 'admin_init', 'delivery_timeline_plugin_settings' );

// Default values for plugin options
function delivery_timeline_options_default() {
    return array(
        'display_option' => 'auto',
        'timelines' => array(
            array(
                'categories'      => array(),
                'start_days'      => 0,
                'shipping_from'   => 5,
                'shipping_to'     => 7,
                'delivery_from'   => 10,
                'delivery_to'     => 14,
                'start_description'    => __('After you place your order, We will take 7-10 days to prepare it for shipment.', 'woo-delivery-timeline'),
                'shipping_description' => __('We put your order in the mail.', 'woo-delivery-timeline'),
                'delivery_description' => __('Estimated to arrive at your doorstep', 'woo-delivery-timeline')
            )
        )
    );
}

// Callback for rendering the timelines repeater field
function delivery_timeline_timelines_callback() {
    $options = get_option( 'delivery_timeline_options' );
    $default_options = delivery_timeline_options_default(); // Fetch default options

    // Set default values for new timeline
    $default_start_day = 0;
    $default_start_description = isset( $default_options['timelines'][0]['start_description'] ) ? $default_options['timelines'][0]['start_description'] : '';
    $default_shipping_description = isset( $default_options['timelines'][0]['shipping_description'] ) ? $default_options['timelines'][0]['shipping_description'] : '';
    $default_delivery_description = isset( $default_options['timelines'][0]['delivery_description'] ) ? $default_options['timelines'][0]['delivery_description'] : '';

    $categories = get_categories( array( 'taxonomy' => 'product_cat' ) );
    ?>
    <div class="delivery-timeline-timelines-wrapper">
        <?php
            if ( isset( $options['timelines'] ) && is_array( $options['timelines'] ) ) {
                foreach ( $options['timelines'] as $index => $timeline ) {
                    ?>
        <div class="delivery-timeline-timeline-row">
            <h3>Timeline <?php echo $index + 1; ?></h3>
            <div class="delivery-timeline-row-content">
                <div class="inputs categories-inputs">
                    <div class="input">
                        <label for="delivery_timeline_categories_<?php echo $index; ?>">Select Product Categories</label>
                        <select name="delivery_timeline_options[timelines][<?php echo $index; ?>][categories][]"
                            id="delivery_timeline_categories_<?php echo $index; ?>"
                            class="delivery-timeline-timeline-categories" multiple="multiple">
                            <?php
                                foreach ( $categories as $category ) {
                                    $selected = ( isset( $timeline['categories'] ) && in_array( $category->term_id, $timeline['categories'] ) ) ? 'selected="selected"' : '';
                                    echo '<option value="' . $category->term_id . '" ' . $selected . '>' . $category->name . '</option>';
                                }
                                ?>
                        </select>
                    </div>
                </div>

                <div class="inputs start-inputs">
                    <div class="input">
                        <label for="delivery_timeline_start_days_<?php echo $index; ?>">Start(days)</label>
                        <input type="number" name="delivery_timeline_options[timelines][<?php echo $index; ?>][start_days]"
                            id="delivery_timeline_start_days_<?php echo $index; ?>"
                            value="<?php echo esc_attr( $timeline['start_days'] ); ?>" min="0" required>
                    </div>
                    <div class="input w-full">
                        <label for="delivery_timeline_start_description_<?php echo $index; ?>">Start Description</label>
                        <textarea name="delivery_timeline_options[timelines][<?php echo $index; ?>][start_description]"
                            id="delivery_timeline_start_description_<?php echo $index; ?>"><?php echo esc_textarea( $timeline['start_description'] ); ?></textarea>
                    </div>
                </div>

                <div class="inputs shipping-input">
                    <div class="input">
                        <label for="delivery_timeline_shipping_from_<?php echo $index; ?>">Shipping From(days)</label>
                        <input type="number"
                            name="delivery_timeline_options[timelines][<?php echo $index; ?>][shipping_from]"
                            id="delivery_timeline_shipping_from_<?php echo $index; ?>"
                            value="<?php echo esc_attr( $timeline['shipping_from'] ); ?>" min="0" required>
                    </div>

                    <div class="input">
                        <label for="delivery_timeline_shipping_to_<?php echo $index; ?>">Shipping To(days)</label>
                        <input type="number" name="delivery_timeline_options[timelines][<?php echo $index; ?>][shipping_to]"
                            id="delivery_timeline_shipping_to_<?php echo $index; ?>"
                            value="<?php echo esc_attr( $timeline['shipping_to'] ); ?>" min="0" required>
                    </div>
                    <div class="input w-full">
                        <label for="delivery_timeline_shipping_description_<?php echo $index; ?>">Shipping
                            Description</label>
                        <textarea name="delivery_timeline_options[timelines][<?php echo $index; ?>][shipping_description]"
                            id="delivery_timeline_shipping_description_<?php echo $index; ?>"><?php echo esc_textarea( $timeline['shipping_description'] ); ?></textarea>
                    </div>
                </div>

                <div class="inputs">
                    <div class="input">
                        <label for="delivery_timeline_delivery_from_<?php echo $index; ?>">Delivery From(days)</label>
                        <input type="number"
                            name="delivery_timeline_options[timelines][<?php echo $index; ?>][delivery_from]"
                            id="delivery_timeline_delivery_from_<?php echo $index; ?>"
                            value="<?php echo esc_attr( $timeline['delivery_from'] ); ?>" min="0" required>
                    </div>

                    <div class="input">
                        <label for="delivery_timeline_delivery_to_<?php echo $index; ?>">Delivery To(days)</label>
                        <input type="number" name="delivery_timeline_options[timelines][<?php echo $index; ?>][delivery_to]"
                            id="delivery_timeline_delivery_to_<?php echo $index; ?>"
                            value="<?php echo esc_attr( $timeline['delivery_to'] ); ?>" min="0" required>
                    </div>
                    <div class="input w-full">
                        <label for="delivery_timeline_delivery_description_<?php echo $index; ?>">Delivery
                            Description</label>
                        <textarea name="delivery_timeline_options[timelines][<?php echo $index; ?>][delivery_description]"
                            id="delivery_timeline_delivery_description_<?php echo $index; ?>"><?php echo esc_textarea( $timeline['delivery_description'] ); ?></textarea>
                    </div>
                </div>

                <button
                    class="delivery-timeline-remove-timeline button button-secondary"><?php esc_html_e( 'Remove Timeline', 'delivery-timeline' ); ?></button>
            </div>
        </div>
        <?php
                }
            }
            ?>
    </div>
    <button
        class="delivery-timeline-add-timeline button button-primary"><?php esc_html_e( 'Add New Timeline', 'delivery-timeline' ); ?></button>
    <script>
    jQuery(document).ready(function($) {
        let timelineCount = <?php echo isset( $options['timelines'] ) ? count( $options['timelines'] ) : 0; ?>;

        $('.delivery-timeline-add-timeline').on('click', function(e) {
            e.preventDefault();
            let html = `
                        <div class="delivery-timeline-timeline-row">
                            <h3>Timeline ${timelineCount}</h3>
                            <div class="delivery-timeline-row-content">
                                <div class="inputs categories-inputs">
                                    <div class="input w-full">
                                        <label for="delivery_timeline_categories_${timelineCount}">Select Product Categories</label>
                                        <select name="delivery_timeline_options[timelines][${timelineCount}][categories][]" id="delivery_timeline_categories_${timelineCount}" class="delivery-timeline-timeline-categories" multiple="multiple">
                                            <?php
                                                $term_ids = wp_list_pluck($categories, 'term_id');
                                                foreach ($term_ids as $term_id) {
                                                    echo '<option value="' . $term_id . '">' . get_term($term_id)->name . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="inputs">
                                    <div class="input">
                                        <label for="delivery_timeline_start_days_${timelineCount}">Start(days)</label>
                                        <input type="number" name="delivery_timeline_options[timelines][${timelineCount}][start_days]" id="delivery_timeline_start_days_${timelineCount}" min="0" required value="0">
                                    </div>
                                    <div class="input w-full">
                                        <label for="delivery_timeline_start_description_${timelineCount}">Start Description</label>
                                        <textarea name="delivery_timeline_options[timelines][${timelineCount}][start_description]"
                                            id="delivery_timeline_start_description_${timelineCount}"><?php echo esc_textarea( isset($timeline['start_description']) ? $timeline['start_description'] : $default_start_description ); ?></textarea>
                                    </div>
                                </div>

                                <div class="inputs">
                                    <div class="input">
                                        <label for="delivery_timeline_shipping_from_${timelineCount}">Shipping From(days)</label>
                                        <input type="number" name="delivery_timeline_options[timelines][${timelineCount}][shipping_from]" id="delivery_timeline_shipping_from_${timelineCount}" min="0" required>
                                    </div>
                                    <div class="input">
                                        <label for="delivery_timeline_shipping_to_${timelineCount}">Shipping To(days)</label>
                                        <input type="number" name="delivery_timeline_options[timelines][${timelineCount}][shipping_to]" id="delivery_timeline_shipping_to_${timelineCount}" min="0" required>
                                    </div>
                                    <div class="input w-full">
                                        <label for="delivery_timeline_shipping_description_${timelineCount}">Shipping Description</label>
                                        <textarea name="delivery_timeline_options[timelines][${timelineCount}][shipping_description]"
                                            id="delivery_timeline_shipping_description_${timelineCount}"><?php echo esc_textarea( isset($timeline['shipping_description']) ? $timeline['shipping_description'] : $default_shipping_description ); ?></textarea>
                                    </div>
                                </div>

                                <div class="inputs">
                                    <div class="input">
                                        <label for="delivery_timeline_delivery_from_${timelineCount}">Delivery From(days)</label>
                                        <input type="number" name="delivery_timeline_options[timelines][${timelineCount}][delivery_from]" id="delivery_timeline_delivery_from_${timelineCount}" min="0" required>
                                    </div>

                                    <div class="input">
                                        <label for="delivery_timeline_delivery_to_${timelineCount}">Delivery To(days)</label>
                                        <input type="number" name="delivery_timeline_options[timelines][${timelineCount}][delivery_to]" id="delivery_timeline_delivery_to_${timelineCount}" min="0" required>
                                    </div>
                                    <div class="input w-full">
                                        <label for="delivery_timeline_delivery_description_${timelineCount}">Delivery Description</label>
                                        <textarea name="delivery_timeline_options[timelines][${timelineCount}][delivery_description]"
                                            id="delivery_timeline_delivery_description_${timelineCount}"><?php echo esc_textarea( isset($timeline['delivery_description']) ? $timeline['delivery_description'] : $default_delivery_description ); ?></textarea>
                                    </div>
                                </div>

                                <button class="delivery-timeline-remove-timeline button button-secondary"><?php esc_html_e( 'Remove Timeline', 'delivery-timeline' ); ?></button>
                            </div>
                        </div>
                    `;

            $('.delivery-timeline-timelines-wrapper').append(html);
            $('.delivery-timeline-timeline-categories').select2();
            timelineCount++;
        });

        $(document).on('click', '.delivery-timeline-remove-timeline', function(e) {
            e.preventDefault();
            $(this).closest('.delivery-timeline-timeline-row').remove();
        });

        $('.delivery-timeline-timeline-categories').select2();
    });
    </script>
<?php
}

function delivery_timeline_display_option_callback() {
    $options = get_option( 'delivery_timeline_options' );
    $display_option = isset( $options['display_option'] ) ? $options['display_option'] : 'auto';
    echo '<input type="radio" name="delivery_timeline_options[display_option]" value="auto" ' . checked( $display_option, 'auto', false ) . '> Auto Display';
    echo '<br>';
    echo '<input type="radio" name="delivery_timeline_options[display_option]" value="shortcode" ' . checked( $display_option, 'shortcode', false ) . '> Use Shortcode <mark>[woo-delivery-timeline]</mark>';
}

// Validate and sanitize plugin options
function delivery_timeline_options_validate( $input ) {
    $valid_input = array();
    // Validate and sanitize the display option
    if ( isset( $input['display_option'] ) ) {
        $display_option = sanitize_text_field( $input['display_option'] );
        if ( in_array( $display_option, array( 'auto', 'shortcode' ) ) ) {
            $valid_input['display_option'] = $display_option;
        }
    }
    if ( isset( $input['timelines'] ) && is_array( $input['timelines'] ) ) {
        foreach ( $input['timelines'] as $timeline ) {
            $valid_timeline = array();
            
            if ( isset( $timeline['categories'] ) && is_array( $timeline['categories'] ) ) {
                $valid_timeline['categories'] = array_map( 'absint', $timeline['categories'] );
            } else {
                $valid_timeline['categories'] = array();
            }
            
            $valid_timeline['start_days'] = sanitize_text_field( $timeline['start_days'] );
            $valid_timeline['shipping_from'] = sanitize_text_field( $timeline['shipping_from'] );
            $valid_timeline['shipping_to'] = sanitize_text_field( $timeline['shipping_to'] );
            $valid_timeline['delivery_from'] = sanitize_text_field( $timeline['delivery_from'] );
            $valid_timeline['delivery_to'] = sanitize_text_field( $timeline['delivery_to'] );
            $valid_timeline['start_description'] = sanitize_textarea_field( $timeline['start_description'] );
            $valid_timeline['shipping_description'] = sanitize_textarea_field( $timeline['shipping_description'] );
            $valid_timeline['delivery_description'] = sanitize_textarea_field( $timeline['delivery_description'] );
            
            $valid_input['timelines'][] = $valid_timeline;
        }
    }
    
    return $valid_input;
}


// Render the fulfillment timeline on the product page
function delivery_timeline_render_fulfillment_timeline() {
    global $product;
    
    $options = get_option( 'delivery_timeline_options' );
    $display_option = isset( $options['display_option'] ) ? $options['display_option'] : 'auto';
    if ( $display_option === 'shortcode' ) {
        return ''; // Display option is set to shortcode, return empty string
    }
    if ( isset( $options['timelines'] ) && is_array( $options['timelines'] ) ) {
        $product_categories = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'ids' ) );
        
        $matching_timelines = array(); // Store matching timelines
        
        foreach ( $options['timelines'] as $timeline ) {
            $timeline_categories = $timeline['categories'];
            
            if ( empty( $timeline_categories ) ) {
                // Timeline does not apply to specific categories, add it to the matching timelines
                $matching_timelines[] = $timeline;
            } elseif ( ! empty( array_intersect( $product_categories, $timeline_categories ) ) ) {
                // Timeline applies to specific categories and matches the product's categories
                $matching_timelines[] = $timeline;
            }
        }
        
        if ( ! empty( $matching_timelines ) ) {
            // Retrieve the details of the first matching timeline
            $timeline = $matching_timelines[0];
            
            $start_days = $timeline['start_days'];
            $shipping_from = $timeline['shipping_from'];
            $shipping_to = $timeline['shipping_to'];
            $delivery_from = $timeline['delivery_from'];
            $delivery_to = $timeline['delivery_to'];
            $start_description = $timeline['start_description'];
            $shipping_description = $timeline['shipping_description'];
            $delivery_description = $timeline['delivery_description'];
            
            $dt = date("Y-m-d");
            $date_start = date( 'M d', strtotime( "$dt + $start_days day" ) );
            $date_ship = date( 'M d', strtotime( "$dt + $shipping_from day" ) ) . ' - ' . date( 'M d', strtotime( "$dt + $shipping_to day" ) );
            $date_delivered = date( 'M d', strtotime( "$dt + $delivery_from day" ) ) . ' - ' . date( 'M d', strtotime( "$dt + $delivery_to day" ) );
            ob_start();
            include( plugin_dir_path( __FILE__ ) . 'templates/fulfillment-timeline.php' );
            echo ob_get_clean();
        }
    }
}

add_action( 'woocommerce_single_product_summary', 'delivery_timeline_render_fulfillment_timeline', 35 );

function delivery_timeline_render_fulfillment_timeline_shortcode(){
    global $product;
    $options = get_option( 'delivery_timeline_options' );
    $display_option = isset( $options['display_option'] ) ? $options['display_option'] : 'auto';

    if ( $display_option !== 'shortcode' ) {
        return ''; // Display option is not set to shortcode, return empty string
    }
    if ( isset( $options['timelines'] ) && is_array( $options['timelines'] ) ) {
        $product_categories = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'ids' ) );
        
        $matching_timelines = array(); // Store matching timelines
        
        foreach ( $options['timelines'] as $timeline ) {
            $timeline_categories = $timeline['categories'];
            
            if ( empty( $timeline_categories ) ) {
                // Timeline does not apply to specific categories, add it to the matching timelines
                $matching_timelines[] = $timeline;
            } elseif ( ! empty( array_intersect( $product_categories, $timeline_categories ) ) ) {
                // Timeline applies to specific categories and matches the product's categories
                $matching_timelines[] = $timeline;
            }
        }
        
        if ( ! empty( $matching_timelines ) ) {
            // Retrieve the details of the first matching timeline
            $timeline = $matching_timelines[0];
            
            $start_days = $timeline['start_days'];
            $shipping_from = $timeline['shipping_from'];
            $shipping_to = $timeline['shipping_to'];
            $delivery_from = $timeline['delivery_from'];
            $delivery_to = $timeline['delivery_to'];
            $start_description = $timeline['start_description'];
            $shipping_description = $timeline['shipping_description'];
            $delivery_description = $timeline['delivery_description'];
            
            $dt = date("Y-m-d");
            $date_start = date( 'M d', strtotime( "$dt + $start_days day" ) );
            $date_ship = date( 'M d', strtotime( "$dt + $shipping_from day" ) ) . ' - ' . date( 'M d', strtotime( "$dt + $shipping_to day" ) );
            $date_delivered = date( 'M d', strtotime( "$dt + $delivery_from day" ) ) . ' - ' . date( 'M d', strtotime( "$dt + $delivery_to day" ) );
            ob_start();
            include( plugin_dir_path( __FILE__ ) . 'templates/fulfillment-timeline.php' );
            echo ob_get_clean();
        }
    }
}

add_shortcode( 'woo-delivery-timeline', 'delivery_timeline_render_fulfillment_timeline_shortcode' );