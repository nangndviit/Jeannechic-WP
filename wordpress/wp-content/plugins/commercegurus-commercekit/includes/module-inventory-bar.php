<?php
/**
 *
 * Inventory Bar module
 *
 * @package CommerceKit
 * @subpackage Shoptimizer
 */

/**
 * Get round stock quantity
 *
 * @param  string $commercekit_stock_quantity of inventory bar.
 * @return string $commercekit_stock_quantity of inventory bar.
 */
function commercekit_get_round_stock_quantity( $commercekit_stock_quantity ) {
	if ( $commercekit_stock_quantity > 30 && $commercekit_stock_quantity <= 40 ) {
		$commercekit_stock_quantity = 40;
	} elseif ( $commercekit_stock_quantity > 40 && $commercekit_stock_quantity <= 50 ) {
		$commercekit_stock_quantity = 50;
	} elseif ( $commercekit_stock_quantity > 50 && $commercekit_stock_quantity <= 60 ) {
		$commercekit_stock_quantity = 60;
	} elseif ( $commercekit_stock_quantity > 60 && $commercekit_stock_quantity <= 70 ) {
		$commercekit_stock_quantity = 70;
	} elseif ( $commercekit_stock_quantity > 70 && $commercekit_stock_quantity <= 80 ) {
		$commercekit_stock_quantity = 80;
	} elseif ( $commercekit_stock_quantity > 80 && $commercekit_stock_quantity <= 90 ) {
		$commercekit_stock_quantity = 90;
	} elseif ( $commercekit_stock_quantity > 90 && $commercekit_stock_quantity <= 100 ) {
		$commercekit_stock_quantity = 100;
	}
	return $commercekit_stock_quantity;
}

/**
 * Get percent stock quantity
 *
 * @param  string $commercekit_stock_quantity of inventory bar.
 * @return string $commercekit_stock_quantity of inventory bar.
 */
function commercekit_get_percent_stock_quantity( $commercekit_stock_quantity ) {
	if ( $commercekit_stock_quantity < 5 ) {
		$commercekit_stock_quantity = 5;
	} elseif ( $commercekit_stock_quantity > 70 ) {
		$commercekit_stock_quantity = 70;
	}
	return $commercekit_stock_quantity;
}
/**
 * Single Product Page - Inventory Bar creation
 *
 * @param  string $display_text of inventory bar.
 * @param  string $display_text_31 of inventory bar.
 * @param  string $display_text_100 of inventory bar.
 */
function commercekit_inventory_number( $display_text, $display_text_31, $display_text_100 ) {
	global $post, $product;
	$product_id = $product ? $product->get_id() : 0;
	if ( $product_id ) {
		$disable_cgkit_inventory = (int) get_post_meta( $product_id, 'commercekit_disable_inventory', true );
		if ( 1 === $disable_cgkit_inventory ) {
			return;
		}
	}
	$commercekit_stock_quantity = $product->get_stock_quantity();
	if ( 'outofstock' === $product->get_stock_status() ) {
		$commercekit_stock_quantity = 0;
	}
	if ( $product->is_type( 'simple' ) && 0 >= $commercekit_stock_quantity ) {
		return;
	}

	$product_ids = array( $product_id );
	$categories  = array();
	$cat_terms   = get_the_terms( $product_id, 'product_cat' );
	if ( is_array( $cat_terms ) && count( $cat_terms ) ) {
		foreach ( $cat_terms as $cat_term ) {
			$categories[] = $cat_term->term_id;
		}
	}

	$options   = get_option( 'commercekit', array() );
	$condition = isset( $options['inventory_condition'] ) ? $options['inventory_condition'] : 'all';
	$pids      = isset( $options['inventory_pids'] ) ? explode( ',', $options['inventory_pids'] ) : array();

	$low_threshold  = isset( $options['inventory_threshold'] ) && (int) $options['inventory_threshold'] ? (int) $options['inventory_threshold'] : commercekit_get_default_settings( 'inventory_threshold' );
	$regr_threshold = isset( $options['inventory_threshold31'] ) && (int) $options['inventory_threshold31'] ? (int) $options['inventory_threshold31'] : commercekit_get_default_settings( 'inventory_threshold31' );
	$high_threshold = isset( $options['inventory_threshold100'] ) && (int) $options['inventory_threshold100'] ? (int) $options['inventory_threshold100'] : commercekit_get_default_settings( 'inventory_threshold100' );

	$low_stock_color  = isset( $options['inventory_lsb_color'] ) && ! empty( $options['inventory_lsb_color'] ) ? $options['inventory_lsb_color'] : commercekit_get_default_settings( 'inventory_lsb_color' );
	$high_stock_color = isset( $options['inventory_rsb_color'] ) && ! empty( $options['inventory_rsb_color'] ) ? $options['inventory_rsb_color'] : commercekit_get_default_settings( 'inventory_rsb_color' );

	$can_display = false;
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

	if ( ! $can_display ) {
		return;
	}

	$stock_quantities            = array();
	$low_stock_amounts           = array();
	$stock_quantities['default'] = $commercekit_stock_quantity;
	if ( $product->is_type( 'variable' ) ) {
		$outofstocks = 0;
		$variations  = commercekit_get_available_variations( $product );
		if ( is_array( $variations ) && count( $variations ) ) {
			foreach ( $variations as $variation ) {
				if ( ! isset( $variation['is_in_stock'] ) || 1 !== (int) $variation['is_in_stock'] ) {
					$outofstocks++;
				} else {
					$stock_quantities[ $variation['variation_id'] ]  = isset( $variation['cgkit_stock_quantity'] ) ? (int) $variation['cgkit_stock_quantity'] : 0;
					$low_stock_amounts[ $variation['variation_id'] ] = isset( $variation['cgkit_low_stock_amount'] ) ? (int) $variation['cgkit_low_stock_amount'] : 0;
					if ( ! isset( $variation['cgkit_stock_quantity'] ) || ! isset( $variation['cgkit_low_stock_amount'] ) ) {
						$vproduct = wc_get_product( $variation['variation_id'] );
						if ( $vproduct ) {
							$stock_quantities[ $variation['variation_id'] ]  = (int) $vproduct->get_stock_quantity();
							$low_stock_amounts[ $variation['variation_id'] ] = (int) $vproduct->get_low_stock_amount();
						}
					}
				}
			}
			if ( count( $variations ) === $outofstocks && 0 >= $commercekit_stock_quantity ) {
				return;
			}
		} else {
			return;
		}
	}

	$can_show_script = false;
	if ( $product->is_type( 'simple' ) && $commercekit_stock_quantity ) {
		$commercekit_stock_percent = commercekit_get_percent_stock_quantity( $commercekit_stock_quantity );
		$final_display_text        = $display_text;
		if ( $commercekit_stock_quantity > $regr_threshold && $commercekit_stock_quantity <= $high_threshold ) {
			$final_display_text = $display_text_31;
		}
		if ( $commercekit_stock_quantity > $high_threshold ) {
			$final_display_text = $display_text_100;
		}
		$low_stock_class  = 'high-stock';
		$low_stock_amount = (int) $product->get_low_stock_amount();
		if ( $low_stock_amount && $commercekit_stock_quantity <= $low_stock_amount ) {
			$low_stock_class    = 'low-stock';
			$final_display_text = $display_text;
		} elseif ( ! $low_stock_amount && $commercekit_stock_quantity < $low_threshold ) {
			$low_stock_class = 'low-stock';
		}
		if ( strpos( $final_display_text, '%' ) !== false && strpos( $final_display_text, '%s' ) === false ) {
			$final_display_text = str_replace( '%', '', $final_display_text );
		}
		?>
<div class="commercekit-inventory">
	<span class="title <?php echo esc_html( $low_stock_class ); ?>"><?php echo esc_html( sprintf( $final_display_text, $commercekit_stock_quantity ) ); ?></span>
	<div class="progress-bar full-bar active <?php echo esc_html( $low_stock_class ); ?>-bar"><span style="width: <?php echo esc_html( $commercekit_stock_percent ); ?>%;"></span></div>
</div>
		<?php
		$can_show_script = true;
	}

	if ( $product->is_type( 'variable' ) && count( $stock_quantities ) ) {
		?>
		<div class="commercekit-inventory">
		<?php
		foreach ( $stock_quantities as $stock_key => $stock_value ) {
			if ( 0 >= $stock_value ) {
				continue;
			}
			$stock_percent = commercekit_get_percent_stock_quantity( $stock_value );
			if ( ! $stock_value ) {
				continue;
			}
			$final_display_text = $display_text;
			if ( $stock_value > $regr_threshold && $stock_value <= $high_threshold ) {
				$final_display_text = $display_text_31;
			}
			if ( $stock_value > $high_threshold ) {
				$final_display_text = $display_text_100;
			}
			$low_stock_class  = 'high-stock';
			$low_stock_amount = isset( $low_stock_amounts[ $stock_key ] ) ? $low_stock_amounts[ $stock_key ] : 0;
			if ( $low_stock_amount && $stock_value <= $low_stock_amount ) {
				$low_stock_class    = 'low-stock';
				$final_display_text = $display_text;
			} elseif ( ! $low_stock_amount && $stock_value < $low_threshold ) {
				$low_stock_class = 'low-stock';
			}
			if ( strpos( $final_display_text, '%' ) !== false && strpos( $final_display_text, '%s' ) === false ) {
				$final_display_text = str_replace( '%', '', $final_display_text );
			}
			?>
			<?php if ( 'default' === $stock_key ) { ?>
			<div class="cki-variation cki-variation-<?php echo esc_html( $stock_key ); ?>">
			<?php } else { ?>
			<div class="cki-variation cki-variation-<?php echo esc_html( $stock_key ); ?>" style="display: none;">
			<?php } ?>
			<span class="title <?php echo esc_html( $low_stock_class ); ?>"><?php echo esc_html( sprintf( $final_display_text, $stock_value ) ); ?></span>
			<div class="progress-bar full-bar <?php echo 'default' === $stock_key ? 'active' : ''; ?> <?php echo esc_html( $low_stock_class ); ?>-bar"><span style="width: <?php echo esc_html( $stock_percent ); ?>%;"></span></div>
		</div>
			<?php
			$can_show_script = true;
		}
		?>
		</div>
		<?php
	}

	if ( $can_show_script ) {
		?>
<style>
.commercekit-inventory { display: inline-block; width: 45%; margin-bottom: 15px; vertical-align: top; line-height: 1.25; position: relative; }
.commercekit-inventory span { font-size: 13px; }
.commercekit-inventory .progress-bar { float: none; position: relative; width: 100%; height: 10px; margin-top: 10px; padding: 0; border-radius: 5px; background-color: #e2e2e2; transition: all 0.4s ease; }
.commercekit-inventory .progress-bar span { position: absolute; top: 0; left: auto; width: 28%; height: 100%; border-radius: inherit; background: #f5b64c; transition: width 3s ease; }
.commercekit-inventory .progress-bar.full-bar span { width: 100% !important; }
.commercekit-inventory .cki-variation { width: 100%; }
@media (max-width: 500px) { .commercekit-inventory { display: block; margin-top: 20px; width: 100%; border: none; } 
.commercekit-inventory .cki-variation { position: relative; } }
.commercekit-inventory .progress-bar.low-stock-bar span { background: <?php echo esc_attr( $low_stock_color ); ?>; }
.commercekit-inventory .progress-bar.high-stock-bar span { background: <?php echo esc_attr( $high_stock_color ); ?>; }
</style>
<script>
function isInCKITViewport(element){
	var rect = element.getBoundingClientRect();
	return (
		rect.top >= 0 &&
		rect.left >= 0 &&
		rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
		rect.right <= (window.innerWidth || document.documentElement.clientWidth)
	);
}
function animateInventoryBar(){
	var bar = document.querySelector('.commercekit-inventory .progress-bar.active');
	if( bar ) {
		if( isInCKITViewport(bar) ){
			var y = setTimeout(function() {
				bar.classList.remove('full-bar');
			}, 100);
		}
	}
}
function animateInventoryHandler(entries, observer) {
	for( entry of entries ){
		if( entry.isIntersecting && entry.target.classList.contains('progress-bar') ){
			var bar = document.querySelector('.commercekit-inventory .progress-bar.active');
			if( bar )
				bar.classList.remove('full-bar');
		}
	}
}
var cgi_observer = new IntersectionObserver(animateInventoryHandler);
if( document.querySelector('.commercekit-inventory') ){
	var $cgkit_cdt = document.querySelector('#commercekit-timer');
	if( $cgkit_cdt ){
		$cgkit_cdt.classList.add('has-cg-inventory');
	}
	animateInventoryBar();
	window.onresize = animateInventoryBar;
	cgi_observer.observe(document.querySelector('.commercekit-inventory .progress-bar'));
	var vinput2 = document.querySelector('.summary input.variation_id');
	if( vinput2 ){
		vinput2_observer = new MutationObserver((changes) => {
			changes.forEach(change => {
				if(change.attributeName.includes('value')){
					setTimeout(function(){
						var cinput_val2 = vinput2.value;
						if( vinput_val2 != cinput_val2 && cinput_val2 != '' ){
							updateStockInventoryDisplay(cinput_val2);
						}
					}, 300);
				}
			});
		});
		vinput2_observer.observe(vinput2, {attributes : true});

		document.addEventListener('click', function(e){
			var input = e.target;
			var inputp = input.closest('.swatch');
			if( input.classList.contains('reset_variations') || input.classList.contains('swatch') || inputp ){
				var clear_var = false;
				if( input.classList.contains('reset_variations') ){
					clear_var = true;
				}
				setTimeout(function(){
					if( inputp ){
						input = inputp;
					}
					if( !input.classList.contains('selected') ){
						clear_var = true;
					}
					var cinput_val2 = vinput2.value;
					if( vinput_val2 != cinput_val2 && ( cinput_val2 != '' || clear_var ) ){
						updateStockInventoryDisplay(cinput_val2);
					}
				}, 300);
			}
		});
		setTimeout(function(){
			var cinput_val2 = vinput2.value;
			if( vinput_val2 != cinput_val2 && cinput_val2 != '' ){
				updateStockInventoryDisplay(cinput_val2);
			}
		}, 300);
	}
}
var vinput_val2 = '0';
function updateStockInventoryDisplay(cinput_val2){
	var btn_disabled = document.querySelector('.summary .single_add_to_cart_button.disabled');
	var display_class = '.cki-variation-'+cinput_val2;
	if( cinput_val2 == '' || cinput_val2 == '0' ){
		display_class = '.cki-variation-default';
	} else if( btn_disabled ) {
		display_class = '';
	} else {
		display_class = '.cki-variation-'+cinput_val2;
	}
	document.querySelector('.commercekit-inventory').style.display = 'none';
	var cki_vars = document.querySelectorAll('.cki-variation');
	cki_vars.forEach(function(cki_var){
		cki_var.style.display = 'none';
		var bar = cki_var.querySelector('.progress-bar');
		if( bar ){
			bar.classList.remove('active');
			bar.classList.add('full-bar');
		}
	});
	if( display_class != '' ){
		var cki_var = document.querySelector(display_class);
		if( cki_var ){
			cki_var.style.display = 'block';
			document.querySelector('.commercekit-inventory').style.display = '';
			var bar = cki_var.querySelector('.progress-bar');
			if( bar ){
				bar.classList.add('active');
			}
		}
	}
	vinput_val2 = cinput_val2;
	var bar = document.querySelector('.commercekit-inventory .progress-bar.active');
	if( bar )
		cgi_observer.observe(bar);
}
</script>
		<?php
	}
}

/**
 * Single Product Page - Display Inventory Bar
 */
function commercekit_display_inventory_counter() {
	global $product;
	$commercekit_inventory_display = false;
	$commercekit_stock_quantity    = $product->get_stock_quantity();
	$commercekit_options           = get_option( 'commercekit', array() );
	if ( isset( $commercekit_options['inventory_display'] ) && 1 === (int) $commercekit_options['inventory_display'] ) {
		$commercekit_inventory_display = true;
	}
	/* translators: %s: stock counter. */
	$display_text = isset( $commercekit_options['inventory_text'] ) && ! empty( $commercekit_options['inventory_text'] ) ? commercekit_get_multilingual_string( $commercekit_options['inventory_text'] ) : commercekit_get_default_settings( 'inventory_text' );

	/* translators: %s: stock counter. */
	$display_text_31 = isset( $commercekit_options['inventory_text_31'] ) && ! empty( $commercekit_options['inventory_text_31'] ) ? commercekit_get_multilingual_string( $commercekit_options['inventory_text_31'] ) : commercekit_get_default_settings( 'inventory_text_31' );

	$display_text_100 = isset( $commercekit_options['inventory_text_100'] ) && ! empty( $commercekit_options['inventory_text_100'] ) ? commercekit_get_multilingual_string( $commercekit_options['inventory_text_100'] ) : commercekit_get_default_settings( 'inventory_text_100' );

	if ( true === $commercekit_inventory_display ) {
		if ( $product->is_type( 'simple' ) || $product->is_type( 'variable' ) ) {
			commercekit_inventory_number( $display_text, $display_text_31, $display_text_100 );
		}
	}
}

add_action( 'woocommerce_single_product_summary', 'commercekit_display_inventory_counter', 40 );
