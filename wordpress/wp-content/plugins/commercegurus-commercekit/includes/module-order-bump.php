<?php
/**
 *
 * Order Bump module
 *
 * @package CommerceKit
 * @subpackage Shoptimizer
 */

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

/**
 * Checkout order bump.
 */
function commercekit_checkout_order_bump() {
	$options = get_option( 'commercekit', array() );
	$enabled = isset( $options['order_bump'] ) && 1 === (int) $options['order_bump'] ? true : false;
	if ( $enabled ) {
		commercekit_show_order_bumps( 'checkout' );
	}
}
add_action( 'woocommerce_review_order_before_submit', 'commercekit_checkout_order_bump', 99 );

/**
 * Mini cart order bumps.
 */
function commercekit_minicart_order_bump() {
	$options = get_option( 'commercekit', array() );
	$enabled = isset( $options['order_bump_mini'] ) && 1 === (int) $options['order_bump_mini'] ? true : false;
	if ( $enabled ) {
		commercekit_show_order_bumps( 'minicart' );
	}
}
add_action( 'woocommerce_mini_cart_contents', 'commercekit_minicart_order_bump', 99 );

/**
 * Show order bumps.
 *
 * @param  string $position of order bumbs.
 */
function commercekit_show_order_bumps( $position ) {
	$product_ids = array();
	$categories  = array();
	foreach ( WC()->cart->get_cart() as $item ) {
		if ( isset( $item['product_id'] ) && (int) $item['product_id'] ) {
			$product_ids[] = (int) $item['product_id'];
		}
		if ( isset( $item['variation_id'] ) && (int) $item['variation_id'] ) {
			$product_ids[] = (int) $item['variation_id'];
		}
		$terms = get_the_terms( $item['product_id'], 'product_cat' );
		if ( is_array( $terms ) && count( $terms ) ) {
			foreach ( $terms as $term ) {
				$categories[] = $term->term_id;
			}
		}
	}

	$options       = get_option( 'commercekit', array() );
	$product_title = '';
	$button_text   = esc_html__( 'Click to add', 'commercegurus-commercekit' );
	$button_added  = esc_html__( 'Added!', 'commercegurus-commercekit' );
	$pid           = 0;
	$order_bumps   = array();
	$cart_total    = (float) WC()->cart->get_displayed_subtotal();

	$order_bump_product  = array();
	$enable_multiple_obp = false;
	if ( 'checkout' === $position ) {
		$order_bump_product  = isset( $options['order_bump_product'] ) ? $options['order_bump_product'] : array();
		$enable_multiple_obp = isset( $options['multiple_obp'] ) && 1 === (int) $options['multiple_obp'] ? true : false;
	}
	if ( 'minicart' === $position ) {
		$order_bump_product  = isset( $options['order_bump_minicart'] ) ? $options['order_bump_minicart'] : array();
		$enable_multiple_obp = isset( $options['multiple_obp_mini'] ) && 1 === (int) $options['multiple_obp_mini'] ? true : false;
	}

	if ( isset( $order_bump_product['product']['title'] ) && count( $order_bump_product['product']['title'] ) > 0 ) {
		foreach ( $order_bump_product['product']['title'] as $k => $product_title ) {
			if ( 'checkout' === $position && empty( $product_title ) ) {
				continue;
			}
			if ( 'minicart' === $position && ( ! isset( $order_bump_product['product']['id'][ $k ] ) || 0 === (int) $order_bump_product['product']['id'][ $k ] ) ) {
				continue;
			}
			if ( isset( $order_bump_product['product']['active'][ $k ] ) && 1 === (int) $order_bump_product['product']['active'][ $k ] ) {
				$can_display  = false;
				$condition    = isset( $order_bump_product['product']['condition'][ $k ] ) ? $order_bump_product['product']['condition'][ $k ] : 'all';
				$pids         = isset( $order_bump_product['product']['pids'][ $k ] ) ? explode( ',', $order_bump_product['product']['pids'][ $k ] ) : array();
				$pid          = isset( $order_bump_product['product']['id'][ $k ] ) ? (int) $order_bump_product['product']['id'][ $k ] : 0;
				$button_text  = isset( $order_bump_product['product']['button_text'][ $k ] ) ? commercekit_get_multilingual_string( $order_bump_product['product']['button_text'][ $k ] ) : esc_html__( 'Click to add', 'commercegurus-commercekit' );
				$button_added = isset( $order_bump_product['product']['button_added'][ $k ] ) ? commercekit_get_multilingual_string( $order_bump_product['product']['button_added'][ $k ] ) : esc_html__( 'Added!', 'commercegurus-commercekit' );
				$min_total    = isset( $order_bump_product['product']['cart_total_min'][ $k ] ) ? (float) $order_bump_product['product']['cart_total_min'][ $k ] : 0;
				$max_total    = isset( $order_bump_product['product']['cart_total_max'][ $k ] ) ? (float) $order_bump_product['product']['cart_total_max'][ $k ] : 0;

				if ( 'all' === $condition ) {
					$can_display = true;
				} elseif ( 'products' === $condition ) {
					if ( count( array_intersect( $product_ids, $pids ) ) ) {
						$can_display = true;
					}
				} elseif ( 'non-products' === $condition ) {
					if ( ! count( array_intersect( $product_ids, $pids ) ) ) {
						$can_display = true;
					}
				} elseif ( 'categories' === $condition ) {
					if ( count( array_intersect( $categories, $pids ) ) ) {
						$can_display = true;
					}
				} elseif ( 'non-categories' === $condition ) {
					if ( ! count( array_intersect( $categories, $pids ) ) ) {
						$can_display = true;
					}
				}

				if ( $min_total >= 0 && $max_total > 0 && $max_total >= $min_total ) {
					if ( $can_display && $min_total <= $cart_total && $max_total >= $cart_total ) {
						$can_display = true;
					} else {
						$can_display = false;
					}
				}

				if ( $can_display && $pid && ! in_array( $pid, $product_ids, true ) ) {
					$product_title = commercekit_get_multilingual_string( $product_title );
					$product_id    = $pid;
					$product       = wc_get_product( $pid );
					if ( $product && $product->is_in_stock() ) {
						$image = '';
						if ( has_post_thumbnail( $product_id ) ) {
							$image = get_the_post_thumbnail( $product_id, 'thumbnail' );
						} elseif ( $product->is_type( 'variation' ) ) {
							$parent_id = $product->get_parent_id();
							if ( has_post_thumbnail( $parent_id ) ) {
								$image = get_the_post_thumbnail( $parent_id, 'thumbnail' );
							}
						}
						if ( $product->is_type( 'variation' ) && ! $product->variation_is_visible() ) {
							continue;
						}
						if ( $product->has_child() ) {
							$children_ids = $product->get_children();
							$product_id   = reset( $children_ids );
							if ( in_array( (int) $product_id, $product_ids, true ) ) {
								continue;
							}
						}

						$product_id = (int) $product_id;
						$view_ids   = isset( $_COOKIE['commercekit_obp_view_ids'] ) && ! empty( $_COOKIE['commercekit_obp_view_ids'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_COOKIE['commercekit_obp_view_ids'] ) ) ) : array();
						$view_ids   = array_map( 'intval', $view_ids );
						if ( ! in_array( $product_id, $view_ids, true ) ) {
							$order_bump_stats_views = (int) get_option( 'commercekit_obp_views' );
							$order_bump_stats_views++;
							update_option( 'commercekit_obp_views', $order_bump_stats_views, false );

							$view_ids[] = $product_id;
							setcookie( 'commercekit_obp_view_ids', implode( ',', $view_ids ), time() + ( 24 * 3600 ), '/' );
						}
						$order_bumps[ $product_id ] = array(
							'product_title' => $product_title,
							'image'         => $image,
							'product'       => $product,
							'button_text'   => $button_text,
							'button_added'  => $button_added,
						);
						if ( $enable_multiple_obp ) {
							continue;
						} else {
							break;
						}
					}
				}
			}
		}
	}
	if ( count( $order_bumps ) ) {
		echo 'minicart' === $position ? '<li>' : '';
		commercekit_order_bump_template( $order_bumps, $position );
		echo 'minicart' === $position ? '</li>' : '';
	}
}

/**
 * Order bump template
 *
 * @param  string $order_bumps list.
 * @param  string $position of order bumps.
 */
function commercekit_order_bump_template( $order_bumps, $position = 'checkout' ) {
	$options   = get_option( 'commercekit', array() );
	$multi_obp = false;
	$obp_label = '';
	if ( 'checkout' === $position ) {
		$multi_obp = isset( $options['multiple_obp'] ) && 1 === (int) $options['multiple_obp'] ? true : false;
		$obp_label = isset( $options['multiple_obp_label'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $options['multiple_obp_label'] ) ) : commercekit_get_default_settings( 'multiple_obp_label' );
	}
	if ( 'minicart' === $position ) {
		$multi_obp = isset( $options['multiple_obp_mini'] ) && 1 === (int) $options['multiple_obp_mini'] ? true : false;
		$obp_label = isset( $options['multiple_obp_mini_lbl'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $options['multiple_obp_mini_lbl'] ) ) : commercekit_get_default_settings( 'multiple_obp_mini_lbl' );
	}
	?>
<div class="commercekit-order-bump-wrap cgkit-<?php echo esc_html( $position ); ?> <?php echo true === $multi_obp ? 'multiple-order-bumps' : ''; ?> <?php echo 1 === count( $order_bumps ) ? 'cgkit-single-order-bump' : ''; ?>">
	<?php if ( $multi_obp && ! empty( $obp_label ) ) { ?>
		<div class="ckobp-before-you-go"><?php echo esc_html( $obp_label ); ?></div>
	<?php } ?>
	<div class="commercekit-order-bumps-wrap">
	<div class="commercekit-order-bumps">
	<?php
	$counter = 0;
	foreach ( $order_bumps as $product_id => $order_bump ) {
		$product_title = $order_bump['product_title'];
		$image         = $order_bump['image'];
		$product       = $order_bump['product'];
		$button_text   = $order_bump['button_text'];
		$button_added  = $order_bump['button_added'];
		$product_link  = $product->get_permalink();
		$counter++;
		?>
		<div class="commercekit-order-bump <?php echo 1 === $counter ? 'active' : ''; ?>" data-index="<?php echo esc_attr( $counter ); ?>" id="ckobp-<?php echo esc_html( $position ); ?>-<?php echo esc_html( $product_id ); ?>">
			<?php if ( ! empty( $product_title ) ) { ?>
			<div class="ckobp-title"><?php echo esc_html( $product_title ); ?></div>
			<?php } ?>
			<div class="ckobp-wrapper">
			<div class="ckobp-item">
			<div class="ckobp-image"><a href="<?php echo esc_url( $product_link ); ?>"><?php commercekit_module_output( $image ); ?></a></div>
			<div class="ckobp-product">
				<div class="ckobp-name"><a href="<?php echo esc_url( $product_link ); ?>"><?php commercekit_module_output( get_the_title( $product_id ) ); ?></a></div>
				<div class="ckobp-price"><?php commercekit_module_output( $product->get_price_html() ); ?></div>
			</div>
			</div>
			<div class="ckobp-actions">
				<div class="ckobp-button"><button type="button" onclick="commercekitOrderBumpAdd(<?php echo esc_html( $product_id ); ?>, this, '<?php echo esc_html( $position ); ?>');"><?php echo esc_html( $button_text ); ?></button></div>
				<div class="ckobp-added" style="display:none;"><button type="button"><?php echo esc_html( $button_added ); ?></button></div>
			</div>
			</div>
		</div>
	<?php } ?>
	</div>
	<?php
	if ( $multi_obp && count( $order_bumps ) > 1 ) {
		$counter = 0;
		echo '<div class="ckobp-nav">';
		echo '<div class="ckobp-prevnext">';
		echo '<div class="ckobp-prev ckobp-disabled" role="button" tabindex="0" aria-label="Previous"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" /></svg></div>';
		echo '<div class="ckobp-next" role="button" tabindex="0" aria-label="Next"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg></div>';
		echo '</div>';
		echo '<div class="ckobp-bullets" data-index="1" data-total="' . count( $order_bumps ) . '">';
		foreach ( $order_bumps as $product_id => $order_bump ) {
			$counter++;
			echo '<div id="bullet-ckobp-' . esc_html( $position ) . '-' . esc_html( $product_id ) . '" class="ckobp-bullet ' . ( 1 === $counter ? 'active' : '' ) . '" data-index="' . esc_attr( $counter ) . '">&nbsp;</div>';
		}
		echo '</div>';
		echo '</div>';
	}
	?>
	</div>
</div>
	<?php
}

/**
 * Order bump scripts
 */
function commercekit_order_bump_scripts() {
	?>
<style>
.ckobp-before-you-go { font-size: 15px; color: #111; font-weight: bold; }
.commercekit-order-bump { border: 1px solid #e2e2e2; box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.06); padding: 20px; margin: 8px 0 0 0; border-radius: 6px; }
.commercekit-order-bump .ckobp-title { width: 100%; padding-bottom: 10px; font-weight: bold; font-size: 14px; line-height: 1.4; color: #111; }
.commercekit-order-bump .ckobp-wrapper { display: flex; justify-content: space-between; }
.commercekit-order-bump .ckobp-item { display: flex; }
.commercekit-order-bump .ckobp-actions { display: flex; flex-shrink: 0; }
.commercekit-order-bump .ckobp-image { width: 50px; flex-shrink: 0; padding-top: 3px; }
.commercekit-order-bump .ckobp-image img { max-width: 50px; }
.commercekit-order-bump .ckobp-image img:nth-child(2n) { display: none; }
.commercekit-order-bump .ckobp-product { margin: -5px 15px 0 15px; }
.commercekit-order-bump .ckobp-name { color: #111; font-size: 13px; line-height: 1.4; display: inline-flex; }
.commercekit-order-bump .ckobp-name a { color: #111; }
.commercekit-order-bump .ckobp-price { margin-top: 2px; font-size: 12px; }
.commercekit-order-bump .ckobp-price, .commercekit-order-bump .ckobp-price ins { color: #DE9915; }
.commercekit-order-bump .ckobp-price del { margin-right: 5px; color: #999; font-weight: normal; }
.commercekit-order-bump .ckobp-actions button { padding: 5px 13px; font-size: 13px; font-weight: bold; color: #111; border: 1px solid #e2e2e2; background: linear-gradient(180deg, white, #eee 130%) no-repeat; border-radius: 4px; transition: 0.2s all; }
.commercekit-order-bump .ckobp-actions button:hover { border-color: #ccc; }
.ckobp-bullets { min-height: 1px; display: flex;}
.ckobp-bullets.processing { opacity: 0.5; pointer-events: none; }
.ckobp-bullets .ckobp-bullet { display: inline-block; width: 8px; height: 8px; background-color: #ccc; border-radius: 50%; cursor: pointer; margin-right: 7px; }
.ckobp-bullets .ckobp-bullet.active {  background-color: #000; }
@media (max-width: 500px) {
	.commercekit-order-bump .ckobp-wrapper { display: block; }
	.commercekit-order-bump .ckobp-actions { display: block; width: 100%; margin-top: 10px; }
	.commercekit-order-bump .ckobp-actions button { width: 100%; }
	.commercekit-order-bump .ckobp-name, .commercekit-order-bump .ckobp-title, .commercekit-order-bump .ckobp-actions button { font-size: 13px; }
}
.commercekit-order-bumps { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; scroll-snap-stop: always; scroll-behavior: smooth; -webkit-overflow-scrolling: touch; position: relative; -ms-overflow-style: none; scrollbar-width: none; width: 100%; }
.commercekit-order-bumps::-webkit-scrollbar { width: 6px; height: 6px; }
.commercekit-order-bumps::-webkit-scrollbar-thumb { background-color:rgba(0,0,0,.2); border-radius: 6px; }
.commercekit-order-bumps::-webkit-scrollbar-track { background: transparent; }
.commercekit-order-bumps::-webkit-scrollbar { display: none; }
.commercekit-order-bumps .commercekit-order-bump { scroll-snap-align: center; flex-shrink: 0; margin-right: 15px; border-radius: 10px; transform-origin: center center; transform: scale(1); transition: transform 0.5s; position: relative; justify-content: center; align-items: center; width: 100%; }
.cgkit-single-order-bump .commercekit-order-bumps::-webkit-scrollbar { width: 0px; height: 0px; }
.product_list_widget li:has(.commercekit-order-bump-wrap) { padding-bottom: 0; }
.commercekit-order-bump-wrap.cgkit-single-order-bump { margin-bottom: 15px; }
.commercekit-order-bumps-wrap { position: relative; }
.commercekit-order-bumps-wrap .ckobp-prev.ckobp-disabled, .commercekit-order-bumps-wrap .ckobp-next.ckobp-disabled { opacity: 0.25; }
.commercekit-order-bumps-wrap .ckobp-prev, .commercekit-order-bumps-wrap .ckobp-next {cursor: pointer; z-index: 2; display: inline-flex; margin-left: 5px;}
.commercekit-order-bumps-wrap .ckobp-prev svg, .commercekit-order-bumps-wrap .ckobp-next svg { width: 18px; height: 18px;}
.commercekit-order-bumps-wrap .ckobp-nav { display: flex; justify-content: space-between; align-items: center; margin: 8px 0 20px 0; }
.commercekit-order-bumps-wrap .ckobp-prevnext { display: flex; order: 2; }
.commercekit-order-bump-wrap.cgkit-minicart:not(.cgkit-single-order-bump) { margin-right: 8px; }
/* RTL */
.rtl .commercekit-order-bump-wrap.cgkit-minicart:not(.cgkit-single-order-bump) { margin-left: 8px; margin-right: 0 }
.rtl .ckobp-bullets .ckobp-bullet { margin-right: 0; margin-left: 7px; }
.rtl .commercekit-order-bumps-wrap .ckobp-prev { order: 1; }
.rtl .commercekit-order-bumps-wrap .ckobp-prev, .rtl .commercekit-order-bumps-wrap .ckobp-next { margin-left: 0; margin-right: 5px; }
</style>
<script>
function commercekitOrderBumpAdd(product_id, obj, position){
	obj.setAttribute('disabled', 'disabled');
	var wrap = obj.closest('.commercekit-order-bump-wrap');
	if( wrap ){
		var bullets = wrap.querySelector('.ckobp-bullets');
		if( bullets ){
			bullets.classList.add('processing');
		}
	}
	var formData = new FormData();
	formData.append('product_id', product_id);
	fetch( commercekit_ajs.ajax_url + '=commercekit_order_bump_add', {
		method: 'POST',
		body: formData,
	}).then(response => response.json()).then( json => {
		var ppp = document.querySelector('.paypalplus-paywall');
		if( ppp ) {
			window.location.reload();
		} else {
			var ucheckout = new Event('update_checkout');
			document.body.dispatchEvent(ucheckout);
			var ufragment = new Event('wc_fragment_refresh');
			document.body.dispatchEvent(ufragment);
		}
	});
}
var ckit_obp_clicked = false;
var ckit_obp_clicked_id = 0; 
document.addEventListener('click', function(e){
	$this = e.target;
	if( $this.classList.contains( 'ckobp-bullet' ) ) {
		e.preventDefault();
		e.stopPropagation();
		ckit_obp_clicked = true;
		ckit_obp_make_active($this, true);
		if( ckit_obp_clicked_id ){
			clearTimeout( ckit_obp_clicked_id );
		}
		ckit_obp_clicked_id = setTimeout(function(){ ckit_obp_clicked = false; ckit_obp_clicked_id = 0; }, 1000);
	}
	$thisp = $this.closest('.ckobp-prev');
	if( $this.classList.contains( 'ckobp-prev' ) || $thisp ) {
		e.preventDefault();
		e.stopPropagation();
		var parent = $this.closest( '.commercekit-order-bump-wrap' );
		var par_divs = parent.querySelector('.ckobp-bullets');
		var $is_rtl = document.querySelector('body.rtl');
		if( par_divs ){
			var $index = parseInt(par_divs.getAttribute('data-index'));
			if( $index == 1 && ! $is_rtl ){
				return true;
			}
			var $nindex = $is_rtl ? $index + 1 : $index - 1;
			var $bullet = parent.querySelector('.ckobp-bullets .ckobp-bullet[data-index="'+$nindex+'"]');
			if( $bullet ){
				$bullet.click();
			}
		}
	}
	$thisp = $this.closest('.ckobp-next');
	if( $this.classList.contains( 'ckobp-next' ) || $thisp ) {
		e.preventDefault();
		e.stopPropagation();
		var parent = $this.closest( '.commercekit-order-bump-wrap' );
		var par_divs = parent.querySelector('.ckobp-bullets');
		var $is_rtl = document.querySelector('body.rtl');
		if( par_divs ){
			var total = parseInt(par_divs.getAttribute('data-total'));
			var $index = parseInt(par_divs.getAttribute('data-index'));
			if( $index == total && ! $is_rtl ){
				return true;
			}
			var $nindex = $is_rtl ? $index - 1 : $index + 1;
			var $bullet = parent.querySelector('.ckobp-bullets .ckobp-bullet[data-index="'+$nindex+'"]');
			if( $bullet ){
				$bullet.click();
			}
		}
	}
});
function ckit_obp_make_active($this, $scroll){
	var parent = $this.closest( '.commercekit-order-bump-wrap' );
	var $id = $this.getAttribute( 'id' ).replace( 'bullet-', '' );
	var $mthis = parent.querySelector( '#' + $id );
	var main_divs = parent.querySelectorAll('.commercekit-order-bumps .commercekit-order-bump');
	$this.classList.add( 'active' );
	$mthis.classList.add( 'active' );
	main_divs.forEach(function(main_div){
		if( main_div !== $mthis ){
			main_div.classList.remove( 'active' );
		}
	});
	var sub_divs = parent.querySelectorAll('.ckobp-bullets .ckobp-bullet');
	sub_divs.forEach(function(sub_divs){
		if( sub_divs !== $this ){
			sub_divs.classList.remove( 'active' );
		}
	});
	var $index = parseInt($mthis.getAttribute('data-index'));
	var par_divs = parent.querySelector('.ckobp-bullets');
	if( par_divs ){
		var total = parseInt(par_divs.getAttribute('data-total'));
		par_divs.setAttribute('data-index', $index);
		ckit_obp_update_prev_next(parent, total, $index);
	}
	if( $scroll ){
		var $width = $mthis.clientWidth;
		var $scroll_left = ( $index - 1 ) * $width;
		var $is_rtl = document.querySelector('body.rtl');
		if( $is_rtl ){
			$scroll_left = -$scroll_left;
		}
		var ckit_obps = parent.querySelector('.commercekit-order-bumps');
		if( ckit_obps ){
			ckit_obps.scroll({
				left: $scroll_left,
				top: 0,
				behavior: 'smooth'
			});
		}
	}
}
document.addEventListener('scroll', function(e){
	var $this = e.target;
	if( $this.classList && $this.classList.contains('commercekit-order-bumps') && !ckit_obp_clicked ){
		var sub_div = $this.querySelector('.commercekit-order-bump:first-child');
		if( sub_div ){
			var parent = $this.closest( '.commercekit-order-bump-wrap' );
			var $width = sub_div.clientWidth;
			var $scroll_left = Math.abs($this.scrollLeft);
			var $index = Math.round( $scroll_left / $width ) + 1;
			var $bullet = parent.querySelector('.ckobp-bullets .ckobp-bullet[data-index="'+$index+'"]');
			if( $bullet ){
				ckit_obp_make_active($bullet, false);
			}
		}
	}
}, true);
function ckit_obp_update_prev_next(parent, total, $index){
	var prev = parent.querySelector('.ckobp-prev');
	var next = parent.querySelector('.ckobp-next');
	if( prev && next ){
		next.classList.remove('ckobp-disabled');
		prev.classList.remove('ckobp-disabled');
		var $is_rtl = document.querySelector('body.rtl');
		if( $is_rtl ){
			if( $index == 1 ) {
				next.classList.add('ckobp-disabled');
			}
			if( $index == total ) {
				prev.classList.add('ckobp-disabled');
			}
		} else {
			if( $index == 1 ) {
				prev.classList.add('ckobp-disabled');
			}
			if( $index == total ) {
				next.classList.add('ckobp-disabled');
			}
		}
	}
}
</script>
	<?php
}

/**
 * Footer scripts.
 */
function commercekit_order_bump_footer_scripts() {
	$options                = get_option( 'commercekit', array() );
	$enable_order_bump      = isset( $options['order_bump'] ) && 1 === (int) $options['order_bump'] ? true : false;
	$enable_order_bump_mini = isset( $options['order_bump_mini'] ) && 1 === (int) $options['order_bump_mini'] ? true : false;
	if ( ( is_checkout() && $enable_order_bump ) || $enable_order_bump_mini ) {
		commercekit_order_bump_scripts();
	}
}
add_action( 'wp_footer', 'commercekit_order_bump_footer_scripts' );

/**
 * Ajax order bump add.
 */
function commercekit_ajax_order_bump_add() {
	$ajax            = array();
	$ajax['status']  = 0;
	$ajax['message'] = esc_html__( 'Error on adding to cart.', 'commercegurus-commercekit' );

	$nonce       = wp_verify_nonce( 'commercekit_nonce', 'commercekit_settings' );
	$product_id  = isset( $_POST['product_id'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : 0;
	$product_ids = array();
	foreach ( WC()->cart->get_cart() as $item ) {
		if ( isset( $item['product_id'] ) && (int) $item['product_id'] ) {
			$product_ids[] = (int) $item['product_id'];
		}
		if ( isset( $item['variation_id'] ) && (int) $item['variation_id'] ) {
			$product_ids[] = (int) $item['variation_id'];
		}
	}
	if ( ! in_array( $product_id, $product_ids, true ) ) {
		$variation_id = 0;
		if ( 'product_variation' === get_post_type( $product_id ) ) {
			$variation_id = $product_id;
			$product_id   = wp_get_post_parent_id( $variation_id );
		}
		if ( WC()->cart->add_to_cart( $product_id, 1, $variation_id ) ) {
			$ajax['status']  = 1;
			$ajax['message'] = esc_html__( 'Sucessfully added to cart.', 'commercegurus-commercekit' );

			WC()->session->set( 'cgkit_order_bump_added', true );
			$product_id = 0 !== (int) $variation_id ? (int) $variation_id : (int) $product_id;
			$click_ids  = isset( $_COOKIE['commercekit_obp_click_ids'] ) && ! empty( $_COOKIE['commercekit_obp_click_ids'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_COOKIE['commercekit_obp_click_ids'] ) ) ) : array();
			$click_ids  = array_map( 'intval', $click_ids );
			if ( ! in_array( $product_id, $click_ids, true ) ) {
				$order_bump_stats_clicks = (int) get_option( 'commercekit_obp_clicks' );
				$order_bump_stats_clicks++;
				update_option( 'commercekit_obp_clicks', $order_bump_stats_clicks, false );

				$click_ids[] = $product_id;
				setcookie( 'commercekit_obp_click_ids', implode( ',', $click_ids ), time() + ( 24 * 3600 ), '/' );
			}
		}
	}

	wp_send_json( $ajax );
}

add_action( 'wp_ajax_commercekit_order_bump_add', 'commercekit_ajax_order_bump_add' );
add_action( 'wp_ajax_nopriv_commercekit_order_bump_add', 'commercekit_ajax_order_bump_add' );

/**
 * Order bump record sales
 *
 * @param  string $order_id of order.
 */
function commercekit_order_bump_record_sales( $order_id ) {
	$order       = wc_get_order( $order_id );
	$product_ids = array();
	$quantities  = array();
	$click_ids   = isset( $_COOKIE['commercekit_obp_click_ids'] ) && ! empty( $_COOKIE['commercekit_obp_click_ids'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_COOKIE['commercekit_obp_click_ids'] ) ) ) : array();
	$matched_ids = array();
	if ( count( $click_ids ) ) {
		foreach ( $order->get_items() as $item_id => $item ) {
			if ( $item['variation_id'] > 0 ) {
				$product_id = $item['variation_id'];
			} else {
				$product_id = $item['product_id'];
			}
			$product_ids[] = $product_id;

			$quantities[ $product_id ] = (int) $item['quantity'];
		}
	} else {
		return;
	}
	if ( count( $product_ids ) ) {
		$matched_ids = array_intersect( $click_ids, $product_ids );
		if ( count( $matched_ids ) ) {
			$order_bump_stats_sales = (int) get_option( 'commercekit_obp_sales' );
			$order_bump_stats_price = (float) get_option( 'commercekit_obp_sales_revenue' );
			foreach ( $matched_ids as $matched_id ) {
				$product = wc_get_product( $matched_id );
				if ( $product ) {
					$order_bump_stats_sales++;
					$order_bump_stats_price += $quantities[ $matched_id ] * (float) $product->get_price();
				}
			}
			update_option( 'commercekit_obp_sales', $order_bump_stats_sales, false );
			update_option( 'commercekit_obp_sales_revenue', number_format( $order_bump_stats_price, 2, '.', '' ), false );
		}
	}

	if ( $order ) {
		$order->update_meta_data( 'commercekit_obp_clicks', $click_ids );
		$order->update_meta_data( 'commercekit_obp_sales', $matched_ids );
		$order->save();
	}

	setcookie( 'commercekit_obp_click_ids', '', time() - ( 24 * 3600 ), '/' );
	setcookie( 'commercekit_obp_view_ids', '', time() - ( 24 * 3600 ), '/' );
}

add_action( 'woocommerce_thankyou', 'commercekit_order_bump_record_sales' );

/**
 * Order bump order review fragments
 *
 * @param  string $fragments of order.
 */
function commercekit_order_bump_order_review_fragments( $fragments ) {
	global $cgkit_obp_scripts;
	$cgkit_order_bump_added = WC()->session->get( 'cgkit_order_bump_added' );
	if ( true === $cgkit_order_bump_added ) {
		if ( isset( $fragments['.woocommerce-checkout-payment'] ) ) {
			unset( $fragments['.woocommerce-checkout-payment'] );
			if ( isset( $fragments['.woocommerce-checkout-review-order-table'] ) ) {
				$fragments['.woocommerce-checkout-review-order-table'] .= '<script> document.querySelectorAll(\'.woocommerce-checkout-payment .blockUI\').forEach(function(div){ div.style.display = \'none\'; }); </script>';
			}
		}
		ob_start();
		$cgkit_obp_scripts = true;
		commercekit_checkout_order_bump();
		$fragments['.commercekit-order-bump-wrap.cgkit-checkout'] = ob_get_clean();
		WC()->session->set( 'cgkit_order_bump_added', false );
	}

	return $fragments;
}

add_filter( 'woocommerce_update_order_review_fragments', 'commercekit_order_bump_order_review_fragments', 99, 1 );

/**
 * Order bump tracking meta box.
 */
function commercekit_order_bump_tracking_meta_box() {
	if ( class_exists( 'WooCommerce' ) ) {
		$screen = wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
		add_meta_box( 'commercekit-order-bump-tracking', esc_html__( 'CommerceKit Order Bump', 'commercegurus-commercekit' ), 'commercekit_order_bump_tracking_meta_box_display', $screen, 'side', 'low' );
	}
}
add_action( 'add_meta_boxes', 'commercekit_order_bump_tracking_meta_box' );

/**
 * Order bump tracking meta box display.
 *
 * @param string $post post object.
 */
function commercekit_order_bump_tracking_meta_box_display( $post ) {
	$order     = ( $post instanceof WP_Post ) ? wc_get_order( $post->ID ) : $post;
	$click_ids = array();
	$sales_ids = array();
	if ( method_exists( $order, 'get_meta' ) ) {
		$cids = $order->get_meta( 'commercekit_obp_clicks', true );
		$sids = $order->get_meta( 'commercekit_obp_sales', true );
		if ( is_array( $cids ) && count( $cids ) ) {
			foreach ( $cids as $cid ) {
				$product = wc_get_product( $cid );
				$title   = $product ? $product->get_name() : '';
				$product = $product && $product->get_parent_id() ? wc_get_product( $product->get_parent_id() ) : $product;
				if ( $product && $title ) {
					$product_link = admin_url( 'post.php?post=' . $product->get_id() . '&action=edit' );
					$click_ids[]  = '<a href="' . esc_url( $product_link ) . '">' . wp_kses_post( $title ) . '</a>';
				}
			}
		}
		if ( is_array( $sids ) && count( $sids ) ) {
			foreach ( $sids as $sid ) {
				$product = wc_get_product( $sid );
				$title   = $product ? $product->get_name() : '';
				$product = $product && $product->get_parent_id() ? wc_get_product( $product->get_parent_id() ) : $product;
				if ( $product && $title ) {
					$product_link = admin_url( 'post.php?post=' . $product->get_id() . '&action=edit' );
					$sales_ids[]  = '<a href="' . esc_url( $product_link ) . '">' . wp_kses_post( $title ) . '</a>';
				}
			}
		}
	}
	if ( ! count( $click_ids ) && ! count( $sales_ids ) ) {
		echo '<style>#commercekit-order-bump-tracking{display:none;}</style>';
	} else {
		if ( count( $click_ids ) ) {
			echo '<strong>' . esc_html__( 'Clicked products:', 'commercegurus-commercekit' ) . '</strong><br />';
			echo implode( '<br />', $click_ids ) . '<br /><br />'; // phpcs:ignore
		}
		if ( count( $sales_ids ) ) {
			echo '<strong>' . esc_html__( 'Purchased products:', 'commercegurus-commercekit' ) . '</strong><br />';
			echo implode( '<br />', $sales_ids ) . '<br />'; // phpcs:ignore
		}
	}
}
