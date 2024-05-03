<?php
/*
 	Plugin Name:       Buy Now Button for WooCommerce
 	Description:       Customers expect a fast and seamless shopping experience. Give shoppers the easiest way to make a purchase. The Buy Now Button for WooCommerce will help achieve this goal.
    Version:           1.0.1
    Author:            Ido Navarro
    Requires at least: 5.0
    Author URI:        https://wpnavarro.com/
    License:           GPL-2.0+ 
    License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
    Text Domain:       sbw-wc
    Domain Path:       /languages/
    WC tested up to: 6.4.2
 */




// If this file is called directly, abort.
if (!defined('WPINC')){
	die;
}

define( 'SBWWC_URL', plugins_url( '/', __FILE__ ) );

/**
 * Currently plugin version.
 */

define( 'BUY_NOW_BUTTON_FOR_WC_VERSION', '1.0.0' );


/**
 * Enqueue the plugin's styles.
 */

function sbw_wc_styles_connector()
{
    wp_register_style( 'sbw-wc-plugin-styles', SBWWC_URL . '/public/css/buy-now-button-for-woocommerce.css' );
    wp_enqueue_style( 'sbw-wc-plugin-styles' );
}

add_action( 'wp_enqueue_scripts', 'sbw_wc_styles_connector' );


/**
 * Main Plugin's Functions
*/

function sbw_wc_add_buy_now_button_single()
{
	global $product;
    printf( '<button id="sbw_wc-adding-button" type="submit" name="sbw-wc-buy-now" value="%d" class="single_add_to_cart_button buy_now_button button alt">%s</button>', $product->get_ID(), esc_html__( 'Buy Now', 'sbw-wc' ) );
}

add_action( 'woocommerce_after_add_to_cart_button', 'sbw_wc_add_buy_now_button_single' );

/*** Add buy now button to product archive page ***/

function sbw_wc_add_buy_now_button_archive()
{
	global $product;
	if ( ! $product->is_type( 'simple' ) ) {
	  return false;
	}
    printf( '<a id="sbw_wc-adding-button-archive" href="%s?buy-now=%s" data-quantity="1" class="button product_type_simple add_to_cart_button  buy_now_button" data-product_id="%s" rel="nofollow">%s</a>', wc_get_checkout_url(), $product->get_ID(), $product->get_ID(), esc_html__( 'Buy Now', 'sbw-wc' ) );
}

add_action( 'woocommerce_after_shop_loop_item', 'sbw_wc_add_buy_now_button_archive', 20 );

/*** Handle for click on buy now ***/

function sbw_wc_handle_buy_now()
{
	if ( !isset( $_REQUEST['sbw-wc-buy-now'] ) )
	{
		return false;
	}

	WC()->cart->empty_cart();

	$product_id = absint( $_REQUEST['sbw-wc-buy-now'] );
    $quantity = absint( $_REQUEST['quantity'] );

	if ( isset( $_REQUEST['variation_id'] ) ) {

		$variation_id = absint( $_REQUEST['variation_id'] );
		WC()->cart->add_to_cart( $product_id, 1, $variation_id );

	}else{
        WC()->cart->add_to_cart( $product_id, $quantity );
	}

	wp_safe_redirect( wc_get_checkout_url() );
	exit;
}

add_action( 'wp_loaded', 'sbw_wc_handle_buy_now' );

