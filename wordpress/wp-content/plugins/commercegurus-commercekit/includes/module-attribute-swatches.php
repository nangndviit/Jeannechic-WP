<?php
/**
 *
 * Attribute swatches module
 *
 * @package CommerceKit
 * @subpackage Shoptimizer
 */

/**
 * Get product attributes swatches admin tab.
 *
 * @param string $tabs admin product tabs.
 */
function commercegurus_get_attribute_swatches_tab( $tabs ) {
	$tabs['commercekit_swatches'] = array(
		'label'    => esc_html__( 'Attribute Swatches', 'commercegurus-commercekit' ),
		'target'   => 'cgkit_attr_swatches',
		'class'    => array( 'commercekit-attributes-swatches', 'show_if_variable' ),
		'priority' => 62,
	);
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'commercegurus_get_attribute_swatches_tab' );

/**
 * Get product attributes swatches admin panel.
 */
function commercegurus_get_attribute_swatches_panel() {
	global $post;
	$product_id = $post->ID;
	$product_id = intval( $product_id );
	$product    = wc_get_product_object( 'variable', $product_id );
	$attributes = commercegurus_attribute_swatches_load_attributes( $product );

	$attribute_swatches = get_post_meta( $product_id, 'commercekit_attribute_swatches', true );
	require_once dirname( __FILE__ ) . '/templates/admin-attribute-swatches.php';
}
add_filter( 'woocommerce_product_data_panels', 'commercegurus_get_attribute_swatches_panel' );

/**
 * Add admin CSS and JS scripts
 */
function commercegurus_attribute_swatches_admin_scripts() {
	$screen = get_current_screen();
	if ( 'product' === $screen->post_type && 'post' === $screen->base ) {
		wp_enqueue_style( 'commercekit-admin-attribute-swatches-style', CKIT_URI . 'assets/css/admin-attribute-swatches.css', array(), CGKIT_CSS_JS_VER );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'commercekit-admin-attribute-swatches-script', CKIT_URI . 'assets/js/admin-attribute-swatches.js', array( 'wp-color-picker' ), CGKIT_CSS_JS_VER, true );
	}
}
add_action( 'admin_enqueue_scripts', 'commercegurus_attribute_swatches_admin_scripts' );

/**
 * Save product attributes gallery
 *
 * @param string $post_id post ID.
 * @param string $post post.
 */
function commercegurus_save_product_attribute_swatches( $post_id, $post ) {
	if ( 'product' !== $post->post_type ) {
		return;
	}
	$cgkit_swatches_nonce = isset( $_POST['cgkit_swatches_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['cgkit_swatches_nonce'] ) ) : '';
	if ( $cgkit_swatches_nonce && wp_verify_nonce( $cgkit_swatches_nonce, 'cgkit_swatches_nonce' ) ) {
		if ( $post_id ) {
			$attribute_swatches = isset( $_POST['commercekit_attribute_swatches'] ) ? map_deep( wp_unslash( $_POST['commercekit_attribute_swatches'] ), 'sanitize_textarea_field' ) : array();
			if ( ! isset( $attribute_swatches['enable_loop'] ) ) {
				$attribute_swatches['enable_loop'] = 0;
			}
			if ( ! isset( $attribute_swatches['enable_product'] ) ) {
				$attribute_swatches['enable_product'] = 0;
			}
			update_post_meta( $post_id, 'commercekit_attribute_swatches', $attribute_swatches );
		}
	}
}
add_action( 'woocommerce_process_product_meta', 'commercegurus_save_product_attribute_swatches', 10, 2 );

/**
 * Get ajax product gallery
 */
function commercegurus_get_ajax_attribute_swatches() {
	$ajax           = array();
	$ajax['status'] = 0;
	$ajax['html']   = '';

	$product_id = isset( $_GET['product_id'] ) ? (int) $_GET['product_id'] : 0; // phpcs:ignore
	if ( $product_id ) {
		ob_start();
		$product_id   = intval( $product_id );
		$product      = wc_get_product_object( 'variable', $product_id );
		$attributes   = commercegurus_attribute_swatches_load_attributes( $product );
		$without_wrap = true;

		$attribute_swatches = get_post_meta( $product_id, 'commercekit_attribute_swatches', true );
		require_once dirname( __FILE__ ) . '/templates/admin-attribute-swatches.php';

		$ajax['status'] = 1;
		$ajax['html']   = ob_get_contents();
		ob_clean();
	}

	wp_send_json( $ajax );
}
add_action( 'wp_ajax_commercekit_get_ajax_attribute_swatches', 'commercegurus_get_ajax_attribute_swatches' );

/**
 * Update ajax product gallery
 */
function commercegurus_update_ajax_attribute_swatches() {
	$ajax           = array();
	$ajax['status'] = 0;
	$ajax['html']   = '';

	$product_id = isset( $_GET['product_id'] ) ? (int) $_GET['product_id'] : 0; // phpcs:ignore
	if ( $product_id ) {
		$post = get_post( $product_id );
		commercegurus_save_product_attribute_swatches( $product_id, $post );
		$ajax['status'] = 1;
	}

	wp_send_json( $ajax );
}
add_action( 'wp_ajax_commercekit_update_ajax_attribute_swatches', 'commercegurus_update_ajax_attribute_swatches' );

/**
 * Add attribute swatches to product loop
 */
function commercekit_as_product_loop() {
	global $product;
	if ( ! $product || ( method_exists( $product, 'is_type' ) && ! $product->is_type( 'variable' ) ) ) {
		return;
	}
	$options       = get_option( 'commercekit', array() );
	$as_active_plp = isset( $options['attribute_swatches_plp'] ) && 1 === (int) $options['attribute_swatches_plp'] ? true : false;
	if ( ! $as_active_plp ) {
		return;
	}

	$product_id = $product ? $product->get_id() : 0;
	if ( ! $product_id ) {
		return;
	}

	$as_swatches = get_post_meta( $product_id, 'commercekit_attribute_swatches', true );
	if ( isset( $as_swatches['enable_product'] ) && 0 === (int) $as_swatches['enable_product'] ) {
		return;
	}

	$out_of_stock = get_post_meta( $product_id, '_stock_status', true );
	if ( 'outofstock' === $out_of_stock ) {
		return;
	}

	$enable_loop = ( isset( $as_swatches['enable_loop'] ) && 1 === (int) $as_swatches['enable_loop'] ) || ! isset( $as_swatches['enable_loop'] ) ? true : false;
	if ( ! $enable_loop ) {
		return;
	}
	wp_enqueue_script( 'wc-add-to-cart-variation' );

	if ( defined( 'COMMERCEKIT_SWATCHES_AJAX' ) && true === COMMERCEKIT_SWATCHES_AJAX ) {
		$cache_key     = 'cgkit_swatch_loop_form_' . $product_id;
		$swatches_html = get_transient( $cache_key );
		if ( ! isset( $_GET['cgkit-as-nocache'] ) && false !== $swatches_html ) { // phpcs:ignore
			echo apply_filters( 'cgkit_loop_swatches_ajax', $swatches_html, $product ); // phpcs:ignore
			return;
		}
		$swatches_html = commercekit_as_build_product_swatch_cache( $product, true, 'via PLP page' );
		echo apply_filters( 'cgkit_loop_swatches_ajax', $swatches_html, $product ); // phpcs:ignore
	} else {
		$cache_key3    = 'cgkit_swatch_loop_full_' . $product_id;
		$swatches_html = get_transient( $cache_key3 );
		if ( ! isset( $_GET['cgkit-as-nocache'] ) && false !== $swatches_html ) { // phpcs:ignore
			echo apply_filters( 'cgkit_loop_swatches', $swatches_html, $product ); // phpcs:ignore
			return;
		}
		$swatches_html = commercekit_as_build_product_swatch_cache( $product, true, 'via PLP page' );
		echo apply_filters( 'cgkit_loop_swatches', $swatches_html, $product ); // phpcs:ignore
	}
}
add_action( 'woocommerce_after_shop_loop_item', 'commercekit_as_product_loop', 10 );

/**
 * Get ajax products variations
 */
function commercegurus_get_ajax_as_variations() {
	$commercekit_nonce  = isset( $_POST['commercekit_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['commercekit_nonce'] ) ) : '';
	$verify_nonce       = wp_verify_nonce( $commercekit_nonce, 'commercekit_nonce' );
	$product_ids        = isset( $_POST['product_ids'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['product_ids'] ) ) ) : '';
	$ajax               = array();
	$ajax['status']     = 1;
	$ajax['variations'] = array();
	$ajax['images']     = array();
	$product_ids        = explode( ',', $product_ids );
	$product_ids        = array_unique( $product_ids );
	if ( count( $product_ids ) ) {
		foreach ( $product_ids as $product_id ) {
			$cache_key2    = 'cgkit_swatch_loop_form_data_' . $product_id;
			$swatches_html = get_transient( $cache_key2 );
			if ( false !== $swatches_html ) {
				$swatches_data = json_decode( $swatches_html, true );
				if ( isset( $swatches_data['variations'] ) && 'cgkit_cache' === $swatches_data['variations'] ) {
					$variations_json = get_transient( 'cgkit_swatch_loop_form_variations_' . $product_id );
					if ( false !== $variations_json ) {
						$ajax['variations'][ $product_id ] = $variations_json;
					} else {
						$_product = wc_get_product( $product_id );
						if ( $_product ) {
							$ajax['variations'][ $product_id ] = wp_json_encode( commercekit_get_available_variations( $_product ) );
						} else {
							$ajax['variations'][ $product_id ] = '';
						}
					}
				} else {
					$ajax['variations'][ $product_id ] = isset( $swatches_data['variations'] ) ? wp_json_encode( $swatches_data['variations'] ) : '';
				}
				$ajax['images'][ $product_id ] = isset( $swatches_data['images'] ) ? wp_json_encode( $swatches_data['images'] ) : '';
			} else {
				$ajax['variations'][ $product_id ] = '';
				$ajax['images'][ $product_id ]     = '';
			}
		}
	}
	wp_send_json( $ajax );
}
add_action( 'wp_ajax_commercekit_get_ajax_as_variations', 'commercegurus_get_ajax_as_variations' );
add_action( 'wp_ajax_nopriv_commercekit_get_ajax_as_variations', 'commercegurus_get_ajax_as_variations' );

/**
 * Ajax add to cart.
 */
function commercegurus_ajax_as_add_to_cart() {
	$ajax            = array();
	$ajax['status']  = 0;
	$ajax['notices'] = '';
	$ajax['message'] = esc_html__( 'Error on adding to cart.', 'commercegurus-commercekit' );

	$nonce        = wp_verify_nonce( 'commercekit_nonce', 'commercekit_nonce' );
	$product_id   = isset( $_POST['product_id'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : 0;
	$variation_id = isset( $_POST['variation_id'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['variation_id'] ) ) : 0;
	$variations   = isset( $_POST['variations'] ) ? $_POST['variations'] : array(); // phpcs:ignore
	if ( $product_id && $variation_id ) {
		if ( WC()->cart->add_to_cart( $product_id, 1, $variation_id, $variations ) ) {
			$ajax['status']  = 1;
			$ajax['message'] = esc_html__( 'Sucessfully added to cart.', 'commercegurus-commercekit' );

			ob_start();
			woocommerce_mini_cart();
			$mini_cart = ob_get_clean();

			$ajax['fragments'] = apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
				)
			);
			$ajax['cart_hash'] = WC()->cart->get_cart_hash();
		} else {
			ob_start();
			wc_print_notices();
			$notices = ob_get_clean();

			$ajax['notices'] = $notices;
		}
	}

	wp_send_json( $ajax );
}
add_action( 'wp_ajax_commercekit_ajax_as_add_to_cart', 'commercegurus_ajax_as_add_to_cart' );
add_action( 'wp_ajax_nopriv_commercekit_ajax_as_add_to_cart', 'commercegurus_ajax_as_add_to_cart' );

/**
 * Attribute swatches loop add to cart link.
 *
 * @param string $html    link html.
 * @param string $product product object.
 */
function commercegurus_as_loop_add_to_cart_link( $html, $product ) {
	$options       = get_option( 'commercekit', array() );
	$as_active_plp = isset( $options['attribute_swatches_plp'] ) && 1 === (int) $options['attribute_swatches_plp'] ? true : false;
	if ( ! $as_active_plp ) {
		return $html;
	}
	$hide_button = true;
	if ( $hide_button && $product && ( method_exists( $product, 'is_type' ) && $product->is_type( 'variable' ) ) ) {
		$product_id   = $product ? $product->get_id() : 0;
		$out_of_stock = get_post_meta( $product_id, '_stock_status', true );
		if ( 'outofstock' === $out_of_stock ) {
			return $html;
		}
		$as_swatches = get_post_meta( $product_id, 'commercekit_attribute_swatches', true );
		if ( isset( $as_swatches['enable_product'] ) && 0 === (int) $as_swatches['enable_product'] ) {
			return $html;
		}
		$enable_loop = ( isset( $as_swatches['enable_loop'] ) && 1 === (int) $as_swatches['enable_loop'] ) || ! isset( $as_swatches['enable_loop'] ) ? true : false;
		if ( ! $enable_loop ) {
			return $html;
		}
		$as_activate_atc  = isset( $options['as_activate_atc'] ) && 1 === (int) $options['as_activate_atc'] ? true : false;
		$single_attribute = false;

		$variation_attributes = $product->get_variation_attributes();
		if ( is_array( $variation_attributes ) && 1 === count( $variation_attributes ) ) {
			$single_attribute = true;
		}

		if ( ! $as_activate_atc && $single_attribute ) {
			$as_swatch_link = isset( $options['as_swatch_link'] ) && ! empty( $options['as_swatch_link'] ) ? $options['as_swatch_link'] : commercekit_get_default_settings( 'as_swatch_link' );
			if ( 'product' === $as_swatch_link ) {
				return '';
			} else {
				return '<div class="cgkit-as-single-atc-wrap">' . $html . '</div>'; // phpcs:ignore
			}
		}
		if ( $as_activate_atc && $single_attribute ) {
			return '<div class="cgkit-as-single-atc-wrap"><a href="' . esc_url( $product->add_to_cart_url() ) . '" class="button cgkit-as-single-atc">' . esc_html__( 'Add to cart', 'commercegurus-commercekit' ) . '</a><input type="hidden" class="cgkit-as-single-atc-clk" value="0" /></div>'; // phpcs:ignore
		}

		return '';
	}

	return $html;
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'commercegurus_as_loop_add_to_cart_link', 99, 2 );

/**
 * Product gallery options
 *
 * @param string $options module options.
 */
function commercekit_get_as_options( $options ) {
	$commercekit_as = array();

	$commercekit_as['as_activate_atc'] = isset( $options['as_activate_atc'] ) && 1 === (int) $options['as_activate_atc'] ? 1 : 0;
	$commercekit_as['cgkit_attr_gal']  = isset( $options['pdp_attributes_gallery'] ) && 1 === (int) $options['pdp_attributes_gallery'] ? 1 : 0;
	$commercekit_as['as_swatch_link']  = isset( $options['as_activate_atc'] ) && 1 === (int) $options['as_activate_atc'] ? 0 : ( ! isset( $options['as_swatch_link'] ) || 'variation' === $options['as_swatch_link'] ? 1 : 0 );

	$commercekit_as['as_enable_tooltips'] = ( ( isset( $options['as_enable_tooltips'] ) && 1 === (int) $options['as_enable_tooltips'] ) || ! isset( $options['as_enable_tooltips'] ) ) ? 1 : 0;

	$swatches_ajax = 0;
	if ( defined( 'COMMERCEKIT_SWATCHES_AJAX' ) && true === COMMERCEKIT_SWATCHES_AJAX ) {
		$swatches_ajax = 1;
	}
	$commercekit_as['swatches_ajax'] = $swatches_ajax;
	return $commercekit_as;
}

/**
 * Product loop class
 *
 * @param array  $classes array of classes.
 * @param string $product product object.
 */
function commercegurus_as_loop_class( $classes, $product ) {
	$options     = get_option( 'commercekit', array() );
	$disable_atc = isset( $options['as_activate_atc'] ) && 1 === (int) $options['as_activate_atc'] ? false : true;
	if ( $product && ( method_exists( $product, 'is_type' ) && $product->is_type( 'variable' ) ) ) {
		$as_active_plp = isset( $options['attribute_swatches_plp'] ) && 1 === (int) $options['attribute_swatches_plp'] ? true : false;
		if ( ! $as_active_plp ) {
			return $classes;
		}
		$hide_button   = true;
		$enable_facade = true;
		if ( $hide_button ) {
			$can_hide     = true;
			$product_id   = $product ? $product->get_id() : 0;
			$out_of_stock = get_post_meta( $product_id, '_stock_status', true );
			if ( 'outofstock' === $out_of_stock ) {
				$can_hide      = false;
				$enable_facade = false;
			}
			$as_swatches = get_post_meta( $product_id, 'commercekit_attribute_swatches', true );
			if ( isset( $as_swatches['enable_product'] ) && 0 === (int) $as_swatches['enable_product'] ) {
				return $classes;
			}
			$enable_loop = ( isset( $as_swatches['enable_loop'] ) && 1 === (int) $as_swatches['enable_loop'] ) || ! isset( $as_swatches['enable_loop'] ) ? true : false;
			if ( ! $enable_loop ) {
				$can_hide      = false;
				$enable_facade = false;
			}
			if ( $can_hide ) {
				$classes[] = 'ckit-hide-cta';
			}
		}
		$classes[] = 'cgkit-swatch-hover';
		if ( $disable_atc ) {
			$classes[] = 'cgkit-disable-atc';
		}
		if ( isset( $options['as_disable_facade'] ) && 1 === (int) $options['as_disable_facade'] ) {
			$enable_facade = false;
		}
		if ( $enable_facade ) {
			$classes[] = 'cgkit-swatch-loading';
		}
	}

	return $classes;
}
add_filter( 'woocommerce_post_class', 'commercegurus_as_loop_class', 10, 2 );

/**
 * Remove shoptimizer gallery image.
 */
function commercegurus_as_remove_shoptimizer_gallery_image() {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'shoptimizer_gallery_image', 10 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'commercegurus_as_add_shoptimizer_gallery_image', 10 );
}
add_action( 'init', 'commercegurus_as_remove_shoptimizer_gallery_image' );

/**
 * Add shoptimizer gallery image.
 */
function commercegurus_as_add_shoptimizer_gallery_image() {
	global $product;
	if ( $product && method_exists( $product, 'is_type' ) && $product->is_type( 'variable' ) ) {
		$options       = get_option( 'commercekit', array() );
		$product_id    = $product ? $product->get_id() : 0;
		$cache_key2    = 'cgkit_swatch_loop_form_data_' . $product_id;
		$swatches_html = get_transient( $cache_key2 );
		$as_swatches   = get_post_meta( $product_id, 'commercekit_attribute_swatches', true );
		$show_swatches = isset( $as_swatches['enable_product'] ) && 0 === (int) $as_swatches['enable_product'] ? false : true;
		$enable_loop   = isset( $as_swatches['enable_loop'] ) && 0 === (int) $as_swatches['enable_loop'] ? false : true;
		$as_active_plp = isset( $options['attribute_swatches_plp'] ) && 1 === (int) $options['attribute_swatches_plp'] ? true : false;
		$out_of_stock  = get_post_meta( $product_id, '_stock_status', true );
		if ( 'outofstock' === $out_of_stock || ! $as_active_plp ) {
			$enable_loop = false;
		}
		$attributes_gallery = isset( $options['pdp_attributes_gallery'] ) && 1 === (int) $options['pdp_attributes_gallery'] ? true : false;
		if ( $attributes_gallery ) {
			$enable_plp_gallery  = false;
			$cgkit_image_gallery = get_post_meta( $product_id, 'commercekit_image_gallery', true );
			if ( is_array( $cgkit_image_gallery ) && count( $cgkit_image_gallery ) ) {
				foreach ( $cgkit_image_gallery as $slug => $image_gallery ) {
					if ( 'global_gallery' === $slug ) {
						continue;
					}
					$images = explode( ',', trim( $image_gallery ) );
					if ( isset( $images[0] ) && ! empty( $images[0] ) ) {
						$enable_plp_gallery = true;
						break;
					}
				}
			}
			if ( ! $enable_plp_gallery ) {
				$attributes_gallery = false;
			}
		}
		if ( $show_swatches && $enable_loop && $attributes_gallery && false !== $swatches_html ) {
			$swatches_data = json_decode( $swatches_html, true );

			$images = isset( $swatches_data['images'] ) ? $swatches_data['images'] : array();
			if ( is_array( $images ) && count( $images ) ) {
				return;
			}
		}
	}
	if ( function_exists( 'shoptimizer_gallery_image' ) ) {
		shoptimizer_gallery_image();
	}
}

/**
 * Display attribute swatches style on header
 */
function commercekit_as_loading_styles() {
	$options = get_option( 'commercekit', array() );
	if ( isset( $options['as_disable_facade'] ) && 1 === (int) $options['as_disable_facade'] ) {
		return;
	}
	?>
<style type="text/css">
@keyframes cgkit-loading { 0% { background-position: 100% 50%; } 100% { background-position: 0 50%; } }
body ul.products li.product.cgkit-swatch-loading .woocommerce-image__wrapper,
body ul.products li.product.cgkit-swatch-loading .woocommerce-card__header > * { color: transparent !important; background: linear-gradient(100deg, #ececec 30%, #f5f5f5 50%, #ececec 70%); border-radius: 5px; background-size: 400%; animation: cgkit-loading 1.2s ease-in-out infinite; }
body ul.products li.product.cgkit-swatch-loading .woocommerce-image__wrapper > *,
body ul.products li.product.cgkit-swatch-loading .woocommerce-card__header > * > * { visibility: hidden; }
ul.products li.product.cgkit-swatch-loading .woocommerce-card__header .product__categories,
ul.products li.product.cgkit-swatch-loading .woocommerce-card__header .woocommerce-loop-product__title,
ul.products li.product.cgkit-swatch-loading .woocommerce-card__header .price { display: table; width: auto;  }
ul.products li.product.cgkit-swatch-loading .woocommerce-card__header .star-rating:before { visibility: hidden; }
</style>
	<?php
}
add_action( 'wp_head', 'commercekit_as_loading_styles', 5 );
