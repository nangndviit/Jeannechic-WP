<?php
/**
 *
 * Free Shipping Notification
 *
 * @package CommerceKit
 * @subpackage Shoptimizer
 */

/**
 * Add cart page.
 */
function commercekit_fsn_add_cart_page() {
	$options = get_option( 'commercekit', array() );

	$enable_fsn_cart_page = isset( $options['fsn_cart_page'] ) && 1 === (int) $options['fsn_cart_page'] ? true : false;
	if ( ! $enable_fsn_cart_page ) {
		return;
	}

	commercekit_free_shipping_notification( 'cart' );
}
add_action( 'woocommerce_cart_contents', 'commercekit_fsn_add_cart_page', 10 );

/**
 * Add min cart.
 */
function commercekit_fsn_add_mini_cart() {
	$options = get_option( 'commercekit', array() );

	$enable_fsn_mini_cart = isset( $options['fsn_mini_cart'] ) && 1 === (int) $options['fsn_mini_cart'] ? true : false;
	if ( ! $enable_fsn_mini_cart ) {
		return;
	}

	commercekit_free_shipping_notification( 'mini-cart' );
}
add_action( 'woocommerce_before_mini_cart', 'commercekit_fsn_add_mini_cart', 20 );

/**
 * Free shipping notification.
 *
 * @param  string $type type of notification.
 */
function commercekit_free_shipping_notification( $type ) {
	$cart_options  = commercekit_fsn_get_cart_options();
	$free_amount   = $cart_options['amount'];
	$free_discount = $cart_options['discount'];
	$free_coupon   = $cart_options['coupon'];
	$show_shipping = $cart_options['shipping'];
	$requires      = $cart_options['requires'];
	$free_ship     = $cart_options['free_ship'];

	if ( ! $free_amount ) {
		return;
	}

	$options = get_option( 'commercekit', array() );

	$fsn_before_ship = isset( $options['fsn_before_ship'] ) && 1 === (int) $options['fsn_before_ship'] ? true : false;
	if ( ! $fsn_before_ship ) {
		if ( ! $show_shipping ) {
			return;
		}
	}

	if ( true === commercekit_fsn_can_hide() ) {
		return;
	}

	$cart_total    = WC()->cart->get_displayed_subtotal();
	$discount      = WC()->cart->get_discount_total();
	$discount_tax  = WC()->cart->get_discount_tax();
	$price_inc_tax = WC()->cart->display_prices_including_tax();
	$price_decimal = wc_get_price_decimals();
	$options       = get_option( 'commercekit', array() );
	$initial_text  = isset( $options['fsn_initial_text'] ) && ! empty( $options['fsn_initial_text'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $options['fsn_initial_text'] ) ) : commercekit_get_default_settings( 'fsn_initial_text' );
	$progress_text = isset( $options['fsn_progress_text'] ) && ! empty( $options['fsn_progress_text'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $options['fsn_progress_text'] ) ) : commercekit_get_default_settings( 'fsn_progress_text' );
	$success_text  = isset( $options['fsn_success_text'] ) && ! empty( $options['fsn_success_text'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $options['fsn_success_text'] ) ) : commercekit_get_default_settings( 'fsn_success_text' );

	if ( $free_discount ) {
		$discount     = 0;
		$discount_tax = 0;
	}

	if ( $price_inc_tax ) {
		$cart_total = round( $cart_total - ( $discount + $discount_tax ), $price_decimal );
	} else {
		$cart_total = round( $cart_total - $discount, $price_decimal );
	}

	$remaining = $free_amount - $cart_total;
	$percent   = 100 - ( $remaining / $free_amount ) * 100;

	$initial_text  = commercekit_fsn_replace_tokens( $initial_text, $remaining, $free_amount );
	$progress_text = commercekit_fsn_replace_tokens( $progress_text, $remaining, $free_amount );
	$success_text  = commercekit_fsn_replace_tokens( $success_text, $remaining, $free_amount );

	if ( 'coupon' === $requires ) {
		return;
	}
	if ( 'both' === $requires && ! $free_coupon ) {
		return;
	}
	if ( 'either' === $requires && $free_ship ) {
		return;
	}
	if ( 'both' === $requires && ! $free_ship ) {
		return;
	}

	$shipping_bar = '';
	if ( $cart_total < $free_amount ) {
		if ( 50 >= $percent ) {
			$progress_text = $initial_text;
		}
		$shipping_bar .= '<div class="cgkit-fsn-bar cgkit-fsn-bar-' . esc_attr( $type ) . '">';
		$shipping_bar .= '<div class="cgkit-fsn-progress-bar">';
		$shipping_bar .= '<span class="cgkit-fsn-amount" style="width:' . esc_attr( $percent ) . '%;"></span>';
		$shipping_bar .= '</div>';
		$shipping_bar .= '<span class="cgkit-fsn-notice">' . wp_kses_post( $progress_text );
		if ( 'cart' === $type ) {
			$shop_page_url = isset( $options['fsn_shop_page'] ) && ! empty( $options['fsn_shop_page'] ) ? get_permalink( $options['fsn_shop_page'] ) : false;
			if ( ! $shop_page_url ) {
				$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
			}
			$shipping_bar .= '<span class="cgkit-fsn-shop-link"> <a href="' . esc_url( $shop_page_url ) . '">' . esc_html__( 'Continue Shopping', 'commercegurus-commercekit' ) . '</a></span>';
		}
		$shipping_bar .= '</span></div>';
	} else {
		$shipping_bar .= '<div class="cgkit-fsn-bar  ckit-fsn-bar-active cgkit-fsn-bar-' . esc_attr( $type ) . '"">';
		$shipping_bar .= '<div class="cgkit-fsn-progress-bar">';
		$shipping_bar .= '<span class="cgkit-fsn-amount" style="width:100%;"></span>';
		$shipping_bar .= '</div>';
		$shipping_bar .= '<span class="cgkit-fsn-title">' . wp_kses_post( $success_text ) . '</span>';
		$shipping_bar .= '</div>';
	}

	if ( 'cart' === $type && '' !== $shipping_bar ) {
		echo '<tr><td colspan="6">' . $shipping_bar . '</td></tr>'; // phpcs:ignore
	} else {
		echo $shipping_bar; // phpcs:ignore
	}
}

/**
 * Get free shipping amount.
 */
function commercekit_fsn_get_cart_options() {
	$amount   = 0;
	$requires = '';
	$discount = false;
	$cart     = WC()->cart;
	if ( $cart ) {
		$packages = $cart->get_shipping_packages();
		$package  = reset( $packages );
		$zone     = wc_get_shipping_zone( $package );
		foreach ( $zone->get_shipping_methods( true ) as $key => $method ) {
			if ( 'free_shipping' === $method->id ) {
				$instance = isset( $method->instance_settings ) ? $method->instance_settings : null;
				$amount   = isset( $instance['min_amount'] ) ? $instance['min_amount'] : 0;
				$requires = isset( $instance['requires'] ) ? $instance['requires'] : '';
				$discount = isset( $instance['ignore_discounts'] ) && 'yes' === $instance['ignore_discounts'] ? true : false;
				break;
			}
		}
	}

	$applied   = false;
	$free_ship = false;
	$coupons   = $cart->get_applied_coupons();
	foreach ( $coupons as $coupon_code ) {
		$coupon = new \WC_Coupon( $coupon_code );
		if ( $coupon ) {
			$applied = true;
			if ( $coupon->get_free_shipping() ) {
				$free_ship = true;
			}
		}
	}

	if ( empty( $requires ) || 'coupon' === $requires || ( 'both' === $requires && ! $applied ) || ( 'either' === $requires && $free_ship ) || ( 'both' === $requires && ! $free_ship ) ) {
		$amount = 0;
	}

	if ( ! $cart->needs_shipping() ) {
		$amount = 0;
	}

	$show_shipping = $cart->show_shipping();

	$return = array(
		'amount'    => $amount,
		'requires'  => $requires,
		'discount'  => $discount,
		'coupon'    => $applied,
		'shipping'  => $show_shipping,
		'free_ship' => $free_ship,
	);

	return apply_filters( 'commercekit_fsn_get_cart_options', $return );
}

/**
 * Replace tokens.
 *
 * @param  string $text message text.
 * @param  string $remaining remaining amount.
 * @param  string $amount free shipping amount.
 */
function commercekit_fsn_replace_tokens( $text, $remaining, $amount ) {
	$text = str_replace( '{remaining}', wc_price( $remaining ), $text );
	$text = str_replace( '{free_shipping_amount}', wc_price( $amount ), $text );

	return $text;
}

/**
 * Free shipping styles.
 */
function commercekit_fsn_styles() {
	$options   = get_option( 'commercekit', array() );
	$bar_color = isset( $options['fsn_bar_color'] ) && ! empty( $options['fsn_bar_color'] ) ? stripslashes_deep( $options['fsn_bar_color'] ) : commercekit_get_default_settings( 'fsn_bar_color' );
	?>
	<style type="text/css">
	.cgkit-fsn-bar{width:100%;text-align:center;}
	.cgkit-fsn-bar-cart { margin: 20px 0 10px 0; }
	.cgkit-fsn-progress-bar{height:8px;background:#ddd;border-radius:5px; margin-bottom: 3px;}
	.cgkit-fsn-bar-cart .cgkit-fsn-progress-bar { margin-bottom: 6px; }
	.cgkit-fsn-bar a { color: #111; text-decoration: underline; font-weight: bold;}
	.cgkit-fsn-amount{height:8px;background:<?php echo esc_html( $bar_color ); ?>;display:block;border-radius:5px;}
	.cgkit-fsn-bar-mini-cart.cgkit-fsn-bar { margin-bottom: 10px; }
	.cgkit-fsn-bar-mini-cart .cgkit-fsn-notice,
	.cgkit-fsn-bar-mini-cart .cgkit-fsn-title { font-size: 12px; display: inline-block; line-height: 1.4; }
	.cgkit-fsn-bar-mini-cart.ckit-fsn-bar-active .cgkit-fsn-title { font-size: 12px; } 
	.cgkit-fsn-bar-cart .cgkit-fsn-notice { font-size: 13px; }
	.cgkit-fsn-notice .amount{color:<?php echo esc_html( $bar_color ); ?>; font-weight: bold;}
	.cgkit-fsn-shop-link a { color: #111; text-decoration: underline; font-weight: bold; }
	.ckit-fsn-bar-active .cgkit-fsn-title { position: relative; padding-left: 23px; font-size: 13px; }
	.ckit-fsn-bar-active .cgkit-fsn-title:before {position: absolute; top: 50%; left: 0px; margin-top: -9px; content: ""; display: block; width: 18px; height: 18px; background: <?php echo esc_html( $bar_color ); ?>;
	-webkit-mask-image: url("data:image/svg+xml;charset=utf8,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z' stroke='%234A5568' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	mask-image: url("data:image/svg+xml;charset=utf8,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z' stroke='%234A5568' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
	-webkit-mask-position: center; -webkit-mask-repeat: no-repeat; -webkit-mask-size: contain;
	}
	</style>
	<?php
}
add_action( 'wp_footer', 'commercekit_fsn_styles' );

/**
 * Can hide free shipping by class.
 */
function commercekit_fsn_can_hide() {
	$options   = get_option( 'commercekit', array() );
	$sel_class = isset( $options['fsn_exclude_class'] ) ? array_map( 'intval', $options['fsn_exclude_class'] ) : array();
	if ( count( $sel_class ) ) {
		foreach ( WC()->cart->get_cart_contents() as $key => $values ) {
			$class_id = (int) $values['data']->get_shipping_class_id();
			if ( in_array( $class_id, $sel_class, true ) ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Hide free shipping method by class.
 *
 * @param  string $available free shipping available.
 * @param  string $package free shipping package.
 * @param  string $fs_obj free shipping object.
 */
function commercekit_fsn_free_shipping_is_available( $available, $package, $fs_obj ) {
	return $available;
}
add_filter( 'woocommerce_shipping_free_shipping_is_available', 'commercekit_fsn_free_shipping_is_available', 999, 3 );
