<?php
/**
 *
 * Admin Order Bump
 *
 * @package CommerceKit
 * @subpackage Shoptimizer
 */

$order_bump_product  = isset( $commercekit_options['order_bump_product'] ) ? $commercekit_options['order_bump_product'] : array();
$order_bump_minicart = isset( $commercekit_options['order_bump_minicart'] ) ? $commercekit_options['order_bump_minicart'] : array();
?>
<div id="settings-content">
<div class="postbox content-box">
	<h2><span class="table-heading"><?php esc_html_e( 'Order Bump', 'commercegurus-commercekit' ); ?></span></h2>
	<div class="inside">
		<table class="form-table" role="presentation">
			<tr id="order_bump_mini"> <th scope="row"><?php esc_html_e( 'Mini cart', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_order_bump_mini" class="toggle-switch"> <input name="commercekit[order_bump_mini]" type="checkbox" id="commercekit_order_bump_mini" value="1" <?php echo isset( $commercekit_options['order_bump_mini'] ) && 1 === (int) $commercekit_options['order_bump_mini'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Enable order bumps within the mini cart.', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr id="multiple_obp_mini" <?php echo isset( $commercekit_options['order_bump_mini'] ) && 1 === (int) $commercekit_options['order_bump_mini'] ? '' : 'style="display:none;"'; ?>> <th scope="row"><?php esc_html_e( 'Allow multiple', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_multiple_obp_mini" class="toggle-switch"> <input name="commercekit[multiple_obp_mini]" type="checkbox" id="commercekit_multiple_obp_mini" value="1" <?php echo isset( $commercekit_options['multiple_obp_mini'] ) && 1 === (int) $commercekit_options['multiple_obp_mini'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Allow multiple order bumps within the mini cart.', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr id="multiple_obp_mini_lbl" <?php echo isset( $commercekit_options['order_bump_mini'] ) && 1 === (int) $commercekit_options['order_bump_mini'] && isset( $commercekit_options['multiple_obp_mini'] ) && 1 === (int) $commercekit_options['multiple_obp_mini'] ? '' : 'style="display:none;"'; ?>> <th scope="row"><?php esc_html_e( 'Heading', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_multiple_obp_mini_lbl"> <input name="commercekit[multiple_obp_mini_lbl]" type="text" class="pc100" id="commercekit_multiple_obp_mini_lbl" value="<?php echo isset( $commercekit_options['multiple_obp_mini_lbl'] ) ? esc_attr( stripslashes_deep( $commercekit_options['multiple_obp_mini_lbl'] ) ) : commercekit_get_default_settings( 'multiple_obp_mini_lbl' ); // phpcs:ignore ?>"  /></label></td> </tr>
			<tr> <td colspan="2"><hr /></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Checkout', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_order_bump" class="toggle-switch"> <input name="commercekit[order_bump]" type="checkbox" id="commercekit_order_bump" value="1" <?php echo isset( $commercekit_options['order_bump'] ) && 1 === (int) $commercekit_options['order_bump'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Enable order bumps on checkout page.', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr id="multiple_obp" <?php echo isset( $commercekit_options['order_bump'] ) && 1 === (int) $commercekit_options['order_bump'] ? '' : 'style="display:none;"'; ?>> <th scope="row"><?php esc_html_e( 'Allow multiple', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_multiple_obp" class="toggle-switch"> <input name="commercekit[multiple_obp]" type="checkbox" id="commercekit_multiple_obp" value="1" <?php echo isset( $commercekit_options['multiple_obp'] ) && 1 === (int) $commercekit_options['multiple_obp'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Allow multiple order bumps on checkout page.', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr id="multiple_obp_label" <?php echo isset( $commercekit_options['order_bump'] ) && 1 === (int) $commercekit_options['order_bump'] && isset( $commercekit_options['multiple_obp'] ) && 1 === (int) $commercekit_options['multiple_obp'] ? '' : 'style="display:none;"'; ?>> <th scope="row"><?php esc_html_e( 'Heading', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_multiple_obp_label"> <input name="commercekit[multiple_obp_label]" type="text" class="pc100" id="commercekit_multiple_obp_label" value="<?php echo isset( $commercekit_options['multiple_obp_label'] ) ? esc_attr( stripslashes_deep( $commercekit_options['multiple_obp_label'] ) ) : commercekit_get_default_settings( 'multiple_obp_label' ); // phpcs:ignore ?>"  /></label></td> </tr>
		</table>
	</div>
</div>

<div class="postbox content-box" id="order_bump_mini_box" <?php echo isset( $commercekit_options['order_bump_mini'] ) && 1 === (int) $commercekit_options['order_bump_mini'] ? '' : 'style="display:none;"'; ?>>
	<h2><span class="table-heading"><?php esc_html_e( 'Mini cart', 'commercegurus-commercekit' ); ?></span></h2>

	<div class="inside meta-box-sortables" id="minicart-orderbump">

		<div class="postbox no-change closed" id="first-row-mini" style="display:none;">
			<button type="button" class="handlediv" aria-expanded="true"><span class="toggle-indicator" aria-hidden="true"></span></button>
			<h2 class="gray"><span><?php esc_html_e( 'Title', 'commercegurus-commercekit' ); ?></span></h2>
			<div class="inside">
				<table class="form-table admin-order-bump" role="presentation">
					<tr> 
						<th width="20%"><?php esc_html_e( 'Enable', 'commercegurus-commercekit' ); ?> </th>
						<td> <label class="toggle-switch"><input name="commercekit_obp_pdt_at[]" type="checkbox" class="check pdt-active" value="1"><span class="toggle-slider"></span><input type="hidden" name="commercekit[order_bump_minicart][product][active][]" class="pdt-active-val" value="0" /><input type="hidden" name="commercekit[order_bump_minicart][product][activeo][]" class="pdt-active-val" value="0" /></label>&nbsp;&nbsp;<?php esc_html_e( 'Enable order bump within the mini cart', 'commercegurus-commercekit' ); ?></td> 
					</tr>
					<tr>
						<th><?php esc_html_e( 'Select', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><select name="commercekit[order_bump_minicart][product][id][]" class="select2 order-bump-product required" data-type="products" data-tab="order-bump" data-mode="full" data-placeholder="Select a product to offer..." style="width:100%;"></select></label><br /><small><em><?php esc_html_e( 'This is the order bump product which will appear within the mini cart. Simple and variable products only.', 'commercegurus-commercekit' ); ?></em></small></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Title (optional)', 'commercegurus-commercekit' ); ?> </th>
						<td> <label><input name="commercekit[order_bump_minicart][product][title][]" type="text" class="title text" value="" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Button text', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_minicart][product][button_text][]" type="text" class="title text btext required" value="<?php esc_html_e( 'Click to add', 'commercegurus-commercekit' ); ?>" /></label></td> 
					</tr>
					<tr> 
						<th>Button added <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_minicart][product][button_added][]" type="text" class="title text badded required" value="<?php esc_html_e( 'Added!', 'commercegurus-commercekit' ); ?>" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Conditions', 'commercegurus-commercekit' ); ?> </th>
						<td> 
							<select name="commercekit[order_bump_minicart][product][condition][]" class="conditions">
								<option value="all" selected="selected"><?php esc_html_e( 'All products', 'commercegurus-commercekit' ); ?></option>
								<option value="products"><?php esc_html_e( 'Specific products', 'commercegurus-commercekit' ); ?></option>
								<option value="non-products"><?php esc_html_e( 'All products apart from', 'commercegurus-commercekit' ); ?></option>
								<option value="categories"><?php esc_html_e( 'Specific categories', 'commercegurus-commercekit' ); ?></option>
								<option value="non-categories"><?php esc_html_e( 'All categories apart from', 'commercegurus-commercekit' ); ?></option>
							</select>
						</td> 
					</tr>
					<tr class="product-ids" style="display:none;">
						<th class="options">
						<?php esc_html_e( 'Specific products:', 'commercegurus-commercekit' ); ?>
						</th>
						<td> <label><select name="commercekit_obp_pdt_pids[]" class="select2" data-type="all" data-tab="order-bump" data-mode="full" multiple="multiple" style="width:100%;"></select><input type="hidden" name="commercekit[order_bump_minicart][product][pids][]" class="select3 text" value="" /></label></td> 
					</tr>
					<tr>
						<th><?php esc_html_e( 'Cart total between', 'commercegurus-commercekit' ); ?></th>
						<td class="cart-total">
							<div class="cart-total-wrapper">
								<label><input name="commercekit[order_bump_minicart][product][cart_total_min][]" type="number" class="title text min-total" value="0" /></label><label class="and-text"><?php esc_html_e( 'and', 'commercegurus-commercekit' ); ?></label><label><input name="commercekit[order_bump_minicart][product][cart_total_max][]" type="number" class="title text max-total" value="0" /></label>
							</div>
							<div>
								<small><em><?php esc_html_e( 'If both values are zero, this condition will be ignored.', 'commercegurus-commercekit' ); ?></em></small>
							</div>
						</td>
					</tr>
					<tr><td colspan="2" align="right"><!--DELETE--></td></tr>
				</table>
			</div>
		</div>
		<?php
		if ( isset( $order_bump_minicart['product']['title'] ) && count( $order_bump_minicart['product']['title'] ) > 0 ) {
			foreach ( $order_bump_minicart['product']['title'] as $k => $product_title ) {
				if ( ! isset( $order_bump_minicart['product']['id'][ $k ] ) || 0 === (int) $order_bump_minicart['product']['id'][ $k ] ) {
					continue;
				}
				?>
		<div class="postbox no-change closed">
			<button type="button" class="handlediv" aria-expanded="true"><span class="toggle-indicator" aria-hidden="true"></span></button>
			<h2 class="gray"><span>
				<?php
				$pid = isset( $order_bump_minicart['product']['id'][ $k ] ) ? (int) $order_bump_minicart['product']['id'][ $k ] : 0;
				if ( $pid ) {
					echo esc_html( commercekit_limit_title( get_the_title( $pid ) ) );
				} else {
					echo esc_html__( 'Title', 'commercegurus-commercekit' );
				}
				?>
			</span></h2>
			<div class="inside">
				<table class="form-table admin-order-bump" role="presentation">
					<tr> 
						<th width="20%"><?php esc_html_e( 'Enable', 'commercegurus-commercekit' ); ?> </th>
						<td> <label class="toggle-switch"><input name="commercekit_obp_pdt_at[]" type="checkbox" class="check pdt-active" value="1" <?php echo isset( $order_bump_minicart['product']['active'][ $k ] ) && 1 === (int) $order_bump_minicart['product']['active'][ $k ] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span><input type="hidden" name="commercekit[order_bump_minicart][product][active][]" class="pdt-active-val" value="<?php echo isset( $order_bump_minicart['product']['active'][ $k ] ) ? esc_attr( $order_bump_minicart['product']['active'][ $k ] ) : '0'; ?>" /><input type="hidden" name="commercekit[order_bump_minicart][product][activeo][]" class="pdt-active-val" value="<?php echo isset( $order_bump_minicart['product']['activeo'][ $k ] ) ? esc_attr( $order_bump_minicart['product']['activeo'][ $k ] ) : '0'; ?>" /></label>&nbsp;&nbsp;<?php esc_html_e( 'Enable order bump within the mini cart', 'commercegurus-commercekit' ); ?></td> 
					</tr>
					<tr>
						<th><?php esc_html_e( 'Select', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><select name="commercekit[order_bump_minicart][product][id][]" class="select2 order-bump-product required" data-type="products" data-tab="order-bump" data-mode="full" data-placeholder="Select a product to offer..." style="width:100%;">
						<?php
						$pid = isset( $order_bump_minicart['product']['id'][ $k ] ) ? (int) $order_bump_minicart['product']['id'][ $k ] : 0;
						if ( $pid ) {
							echo '<option value="' . esc_attr( $pid ) . '" selected="selected">#' . esc_attr( $pid ) . ' - ' . esc_html( commercekit_limit_title( get_the_title( $pid ) ) ) . '</option>';
						}
						?>
						</select></label><br /><small><em><?php esc_html_e( 'This is the order bump product which will appear within the mini cart. Simple and variable products only.', 'commercegurus-commercekit' ); ?></em></small></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Title (optional)', 'commercegurus-commercekit' ); ?> </th>
						<td> <label><input name="commercekit[order_bump_minicart][product][title][]" type="text" class="title text" value="<?php echo isset( $order_bump_minicart['product']['title'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_minicart['product']['title'][ $k ] ) ) : ''; ?>" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Button text', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_minicart][product][button_text][]" type="text" class="title text btext required" value="<?php echo isset( $order_bump_minicart['product']['button_text'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_minicart['product']['button_text'][ $k ] ) ) : 'Click to add'; ?>" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Button added', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_minicart][product][button_added][]" type="text" class="title text badded required" value="<?php echo isset( $order_bump_minicart['product']['button_added'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_minicart['product']['button_added'][ $k ] ) ) : 'Added!'; ?>" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Conditions', 'commercegurus-commercekit' ); ?> </th>
						<td> 
							<?php
							$ctype = 'all';
							if ( isset( $order_bump_minicart['product']['condition'][ $k ] ) && in_array( $order_bump_minicart['product']['condition'][ $k ], array( 'products', 'non-products' ), true ) ) {
								$ctype = 'products';
							}
							if ( isset( $order_bump_minicart['product']['condition'][ $k ] ) && in_array( $order_bump_minicart['product']['condition'][ $k ], array( 'categories', 'non-categories' ), true ) ) {
								$ctype = 'categories';
							}
							?>
							<select name="commercekit[order_bump_minicart][product][condition][]" class="conditions">
								<option value="all" <?php echo isset( $order_bump_minicart['product']['condition'][ $k ] ) && 'all' === $order_bump_minicart['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'All products', 'commercegurus-commercekit' ); ?></option>
								<option value="products" <?php echo isset( $order_bump_minicart['product']['condition'][ $k ] ) && 'products' === $order_bump_minicart['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'Specific products', 'commercegurus-commercekit' ); ?></option>
								<option value="non-products" <?php echo isset( $order_bump_minicart['product']['condition'][ $k ] ) && 'non-products' === $order_bump_minicart['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'All products apart from', 'commercegurus-commercekit' ); ?></option>
								<option value="categories" <?php echo isset( $order_bump_minicart['product']['condition'][ $k ] ) && 'categories' === $order_bump_minicart['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'Specific categories', 'commercegurus-commercekit' ); ?></option>
								<option value="non-categories" <?php echo isset( $order_bump_minicart['product']['condition'][ $k ] ) && 'non-categories' === $order_bump_minicart['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'All categories apart from', 'commercegurus-commercekit' ); ?></option>
							</select>
						</td> 
					</tr>
					<tr class="product-ids" <?php echo 'all' === $ctype ? 'style="display:none;"' : ''; ?>>
						<th class="options">
						<?php
						echo 'all' === $ctype || 'products' === $ctype ? esc_attr( 'Specific products:' ) : '';
						echo 'categories' === $ctype ? esc_html__( 'Specific categories:', 'commercegurus-commercekit' ) : '';
						?>
						</th>
						<td> <label><select name="commercekit_obp_pdt_pids[]" class="select2" data-type="<?php echo esc_attr( $ctype ); ?>" data-tab="order-bump" data-mode="full" multiple="multiple" style="width:100%;">
						<?php
						$pids = isset( $order_bump_minicart['product']['pids'][ $k ] ) ? explode( ',', $order_bump_minicart['product']['pids'][ $k ] ) : array();
						if ( 'all' !== $ctype && count( $pids ) ) {
							foreach ( $pids as $pid ) {
								if ( empty( $pid ) ) {
									continue;
								}
								if ( 'products' === $ctype ) {
									echo '<option value="' . esc_attr( $pid ) . '" selected="selected">#' . esc_attr( $pid ) . ' - ' . esc_html( commercekit_limit_title( get_the_title( $pid ) ) ) . '</option>';
								}
								if ( 'categories' === $ctype ) {
									$nterm = get_term_by( 'id', $pid, 'product_cat' );
									if ( $nterm ) {
										echo '<option value="' . esc_attr( $pid ) . '" selected="selected">#' . esc_attr( $pid ) . ' - ' . esc_html( $nterm->name ) . '</option>';
									}
								}
							}
						}
						?>
						</select><input type="hidden" name="commercekit[order_bump_minicart][product][pids][]" class="select3 text" value="<?php echo esc_html( implode( ',', $pids ) ); ?>" /></label></td> 
					</tr>
					<tr>
						<th><?php esc_html_e( 'Cart total between', 'commercegurus-commercekit' ); ?></th>
						<td class="cart-total">
							<div class="cart-total-wrapper">
								<label><input name="commercekit[order_bump_minicart][product][cart_total_min][]" type="number" class="title text min-total" value="<?php echo isset( $order_bump_minicart['product']['cart_total_min'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_minicart['product']['cart_total_min'][ $k ] ) ) : 0; ?>" /></label><label class="and-text"><?php esc_html_e( 'and', 'commercegurus-commercekit' ); ?></label><label><input name="commercekit[order_bump_minicart][product][cart_total_max][]" type="number" class="title text max-total" value="<?php echo isset( $order_bump_minicart['product']['cart_total_max'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_minicart['product']['cart_total_max'][ $k ] ) ) : 0; ?>" /></label>
							</div>
							<div>
								<small><em><?php esc_html_e( 'If both values are zero, this condition will be ignored.', 'commercegurus-commercekit' ); ?></em></small>
							</div>
						</td>
					</tr>
					<tr><td colspan="2" align="right"><a href="javascript:;" class="delete-orderbump" onclick="delete_product_orderbump(this);"><?php esc_html_e( 'Delete order bump', 'commercegurus-commercekit' ); ?></a></td></tr>
				</table>
			</div>
		</div>
				<?php
			}
		}
		?>
	</div>

	<div class="inside add-new-order-bump"><button type="button" class="button button-secondary" onclick="add_new_order_bump_mini();"><span class="dashicons dashicons-plus"></span> <?php esc_html_e( 'Add new order bump', 'commercegurus-commercekit' ); ?></button></div>
</div>

<div class="postbox content-box" id="order_bump_box" <?php echo isset( $commercekit_options['order_bump'] ) && 1 === (int) $commercekit_options['order_bump'] ? '' : 'style="display:none;"'; ?>>
	<h2><span class="table-heading"><?php esc_html_e( 'Checkout', 'commercegurus-commercekit' ); ?></span></h2>

	<div class="inside meta-box-sortables" id="product-orderbump">

		<div class="postbox no-change closed" id="first-row" style="display:none;">
			<button type="button" class="handlediv" aria-expanded="true"><span class="toggle-indicator" aria-hidden="true"></span></button>
			<h2 class="gray"><span><?php esc_html_e( 'Title', 'commercegurus-commercekit' ); ?></span></h2>
			<div class="inside">
				<table class="form-table admin-order-bump" role="presentation">
					<tr> 
						<th width="20%"><?php esc_html_e( 'Enable', 'commercegurus-commercekit' ); ?> </th>
						<td> <label class="toggle-switch"><input name="commercekit_obp_pdt_at[]" type="checkbox" class="check pdt-active" value="1"><span class="toggle-slider"></span><input type="hidden" name="commercekit[order_bump_product][product][active][]" class="pdt-active-val" value="0" /><input type="hidden" name="commercekit[order_bump_product][product][activeo][]" class="pdt-active-val" value="0" /></label>&nbsp;&nbsp;<?php esc_html_e( 'Enable order bump on checkout', 'commercegurus-commercekit' ); ?></td> 
					</tr>
					<tr>
						<th><?php esc_html_e( 'Select', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><select name="commercekit[order_bump_product][product][id][]" class="select2 order-bump-product required" data-type="products" data-tab="order-bump" data-mode="full" data-placeholder="Select a product to offer..." style="width:100%;"></select></label><br /><small><em><?php esc_html_e( 'This is the order bump product which will appear on the checkout page. Simple and variable products only.', 'commercegurus-commercekit' ); ?></em></small></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Title', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_product][product][title][]" type="text" class="title text required" value="" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Button text', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_product][product][button_text][]" type="text" class="title text btext required" value="<?php esc_html_e( 'Click to add', 'commercegurus-commercekit' ); ?>" /></label></td> 
					</tr>
					<tr> 
						<th>Button added <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_product][product][button_added][]" type="text" class="title text badded required" value="<?php esc_html_e( 'Added!', 'commercegurus-commercekit' ); ?>" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Conditions', 'commercegurus-commercekit' ); ?> </th>
						<td> 
							<select name="commercekit[order_bump_product][product][condition][]" class="conditions">
								<option value="all" selected="selected"><?php esc_html_e( 'All products', 'commercegurus-commercekit' ); ?></option>
								<option value="products"><?php esc_html_e( 'Specific products', 'commercegurus-commercekit' ); ?></option>
								<option value="non-products"><?php esc_html_e( 'All products apart from', 'commercegurus-commercekit' ); ?></option>
								<option value="categories"><?php esc_html_e( 'Specific categories', 'commercegurus-commercekit' ); ?></option>
								<option value="non-categories"><?php esc_html_e( 'All categories apart from', 'commercegurus-commercekit' ); ?></option>
							</select>
						</td> 
					</tr>
					<tr class="product-ids" style="display:none;">
						<th class="options">
						<?php esc_html_e( 'Specific products:', 'commercegurus-commercekit' ); ?>
						</th>
						<td> <label><select name="commercekit_obp_pdt_pids[]" class="select2" data-type="all" data-tab="order-bump" data-mode="full" multiple="multiple" style="width:100%;"></select><input type="hidden" name="commercekit[order_bump_product][product][pids][]" class="select3 text" value="" /></label></td> 
					</tr>
					<tr>
						<th><?php esc_html_e( 'Cart total between', 'commercegurus-commercekit' ); ?></th>
						<td class="cart-total">
							<div class="cart-total-wrapper">
								<label><input name="commercekit[order_bump_product][product][cart_total_min][]" type="number" class="title text min-total" value="0" /></label><label class="and-text"><?php esc_html_e( 'and', 'commercegurus-commercekit' ); ?></label><label><input name="commercekit[order_bump_product][product][cart_total_max][]" type="number" class="title text max-total" value="0" /></label>
							</div>
							<div>
								<small><em><?php esc_html_e( 'If both values are zero, this condition will be ignored.', 'commercegurus-commercekit' ); ?></em></small>
							</div>
						</td>
					</tr>
					<tr><td colspan="2" align="right"><!--DELETE--></td></tr>
				</table>
			</div>
		</div>
		<?php
		if ( isset( $order_bump_product['product']['title'] ) && count( $order_bump_product['product']['title'] ) > 0 ) {
			foreach ( $order_bump_product['product']['title'] as $k => $product_title ) {
				if ( empty( $product_title ) ) {
					continue;
				}
				?>
		<div class="postbox no-change closed">
			<button type="button" class="handlediv" aria-expanded="true"><span class="toggle-indicator" aria-hidden="true"></span></button>
			<h2 class="gray"><span>
				<?php
				$pid = isset( $order_bump_product['product']['id'][ $k ] ) ? (int) $order_bump_product['product']['id'][ $k ] : 0;
				if ( $pid ) {
					echo esc_html( commercekit_limit_title( get_the_title( $pid ) ) );
				} else {
					echo esc_html__( 'Title', 'commercegurus-commercekit' );
				}
				?>
			</span></h2>
			<div class="inside">
				<table class="form-table admin-order-bump" role="presentation">
					<tr> 
						<th width="20%"><?php esc_html_e( 'Enable', 'commercegurus-commercekit' ); ?> </th>
						<td> <label class="toggle-switch"><input name="commercekit_obp_pdt_at[]" type="checkbox" class="check pdt-active" value="1" <?php echo isset( $order_bump_product['product']['active'][ $k ] ) && 1 === (int) $order_bump_product['product']['active'][ $k ] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span><input type="hidden" name="commercekit[order_bump_product][product][active][]" class="pdt-active-val" value="<?php echo isset( $order_bump_product['product']['active'][ $k ] ) ? esc_attr( $order_bump_product['product']['active'][ $k ] ) : '0'; ?>" /><input type="hidden" name="commercekit[order_bump_product][product][activeo][]" class="pdt-active-val" value="<?php echo isset( $order_bump_product['product']['activeo'][ $k ] ) ? esc_attr( $order_bump_product['product']['activeo'][ $k ] ) : '0'; ?>" /></label>&nbsp;&nbsp;<?php esc_html_e( 'Enable order bump on checkout', 'commercegurus-commercekit' ); ?></td> 
					</tr>
					<tr>
						<th><?php esc_html_e( 'Select', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><select name="commercekit[order_bump_product][product][id][]" class="select2 order-bump-product required" data-type="products" data-tab="order-bump" data-mode="full" data-placeholder="Select a product to offer..." style="width:100%;">
						<?php
						$pid = isset( $order_bump_product['product']['id'][ $k ] ) ? (int) $order_bump_product['product']['id'][ $k ] : 0;
						if ( $pid ) {
							echo '<option value="' . esc_attr( $pid ) . '" selected="selected">#' . esc_attr( $pid ) . ' - ' . esc_html( commercekit_limit_title( get_the_title( $pid ) ) ) . '</option>';
						}
						?>
						</select></label><br /><small><em><?php esc_html_e( 'This is the order bump product which will appear on the checkout page. Simple and variable products only.', 'commercegurus-commercekit' ); ?></em></small></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Title', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_product][product][title][]" type="text" class="title text required" value="<?php echo isset( $order_bump_product['product']['title'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_product['product']['title'][ $k ] ) ) : ''; ?>" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Button text', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_product][product][button_text][]" type="text" class="title text btext required" value="<?php echo isset( $order_bump_product['product']['button_text'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_product['product']['button_text'][ $k ] ) ) : 'Click to add'; ?>" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Button added', 'commercegurus-commercekit' ); ?> <span class="star">*</span></th>
						<td> <label><input name="commercekit[order_bump_product][product][button_added][]" type="text" class="title text badded required" value="<?php echo isset( $order_bump_product['product']['button_added'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_product['product']['button_added'][ $k ] ) ) : 'Added!'; ?>" /></label></td> 
					</tr>
					<tr> 
						<th><?php esc_html_e( 'Conditions', 'commercegurus-commercekit' ); ?> </th>
						<td> 
							<?php
							$ctype = 'all';
							if ( isset( $order_bump_product['product']['condition'][ $k ] ) && in_array( $order_bump_product['product']['condition'][ $k ], array( 'products', 'non-products' ), true ) ) {
								$ctype = 'products';
							}
							if ( isset( $order_bump_product['product']['condition'][ $k ] ) && in_array( $order_bump_product['product']['condition'][ $k ], array( 'categories', 'non-categories' ), true ) ) {
								$ctype = 'categories';
							}
							?>
							<select name="commercekit[order_bump_product][product][condition][]" class="conditions">
								<option value="all" <?php echo isset( $order_bump_product['product']['condition'][ $k ] ) && 'all' === $order_bump_product['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'All products', 'commercegurus-commercekit' ); ?></option>
								<option value="products" <?php echo isset( $order_bump_product['product']['condition'][ $k ] ) && 'products' === $order_bump_product['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'Specific products', 'commercegurus-commercekit' ); ?></option>
								<option value="non-products" <?php echo isset( $order_bump_product['product']['condition'][ $k ] ) && 'non-products' === $order_bump_product['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'All products apart from', 'commercegurus-commercekit' ); ?></option>
								<option value="categories" <?php echo isset( $order_bump_product['product']['condition'][ $k ] ) && 'categories' === $order_bump_product['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'Specific categories', 'commercegurus-commercekit' ); ?></option>
								<option value="non-categories" <?php echo isset( $order_bump_product['product']['condition'][ $k ] ) && 'non-categories' === $order_bump_product['product']['condition'][ $k ] ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'All categories apart from', 'commercegurus-commercekit' ); ?></option>
							</select>
						</td> 
					</tr>
					<tr class="product-ids" <?php echo 'all' === $ctype ? 'style="display:none;"' : ''; ?>>
						<th class="options">
						<?php
						echo 'all' === $ctype || 'products' === $ctype ? esc_attr( 'Specific products:' ) : '';
						echo 'categories' === $ctype ? esc_html__( 'Specific categories:', 'commercegurus-commercekit' ) : '';
						?>
						</th>
						<td> <label><select name="commercekit_obp_pdt_pids[]" class="select2" data-type="<?php echo esc_attr( $ctype ); ?>" data-tab="order-bump" data-mode="full" multiple="multiple" style="width:100%;">
						<?php
						$pids = isset( $order_bump_product['product']['pids'][ $k ] ) ? explode( ',', $order_bump_product['product']['pids'][ $k ] ) : array();
						if ( 'all' !== $ctype && count( $pids ) ) {
							foreach ( $pids as $pid ) {
								if ( empty( $pid ) ) {
									continue;
								}
								if ( 'products' === $ctype ) {
									echo '<option value="' . esc_attr( $pid ) . '" selected="selected">#' . esc_attr( $pid ) . ' - ' . esc_html( commercekit_limit_title( get_the_title( $pid ) ) ) . '</option>';
								}
								if ( 'categories' === $ctype ) {
									$nterm = get_term_by( 'id', $pid, 'product_cat' );
									if ( $nterm ) {
										echo '<option value="' . esc_attr( $pid ) . '" selected="selected">#' . esc_attr( $pid ) . ' - ' . esc_html( $nterm->name ) . '</option>';
									}
								}
							}
						}
						?>
						</select><input type="hidden" name="commercekit[order_bump_product][product][pids][]" class="select3 text" value="<?php echo esc_html( implode( ',', $pids ) ); ?>" /></label></td> 
					</tr>
					<tr>
						<th><?php esc_html_e( 'Cart total between', 'commercegurus-commercekit' ); ?></th>
						<td class="cart-total">
							<div class="cart-total-wrapper">
								<label><input name="commercekit[order_bump_product][product][cart_total_min][]" type="number" class="title text min-total" value="<?php echo isset( $order_bump_product['product']['cart_total_min'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_product['product']['cart_total_min'][ $k ] ) ) : 0; ?>" /></label><label class="and-text"><?php esc_html_e( 'and', 'commercegurus-commercekit' ); ?></label><label><input name="commercekit[order_bump_product][product][cart_total_max][]" type="number" class="title text max-total" value="<?php echo isset( $order_bump_product['product']['cart_total_max'][ $k ] ) ? esc_attr( stripslashes_deep( $order_bump_product['product']['cart_total_max'][ $k ] ) ) : 0; ?>" /></label>
							</div>
							<div>
								<small><em><?php esc_html_e( 'If both values are zero, this condition will be ignored.', 'commercegurus-commercekit' ); ?></em></small>
							</div>
						</td>
					</tr>
					<tr><td colspan="2" align="right"><a href="javascript:;" class="delete-orderbump" onclick="delete_product_orderbump(this);"><?php esc_html_e( 'Delete order bump', 'commercegurus-commercekit' ); ?></a></td></tr>
				</table>
			</div>
		</div>
				<?php
			}
		}
		?>
	</div>
	<script> 
	var global_delete_orderbump = '<?php esc_html_e( 'Delete order bump', 'commercegurus-commercekit' ); ?>';
	var global_delete_orderbump_confirm = '<?php esc_html_e( 'Are you sure you want to delete this product order bump?', 'commercegurus-commercekit' ); ?>';
	var global_required_text = '<?php esc_html_e( 'This field is required', 'commercegurus-commercekit' ); ?>';
	</script>

	<div class="inside add-new-order-bump"><button type="button" class="button button-secondary" onclick="add_new_order_bump();"><span class="dashicons dashicons-plus"></span> <?php esc_html_e( 'Add new order bump', 'commercegurus-commercekit' ); ?></button></div>
		<input type="hidden" name="tab" value="order-bump" />
		<input type="hidden" name="action" value="commercekit_save_settings" />
</div>
</div>

<div class="postbox" id="settings-note">
	<h4><?php esc_html_e( 'Order Bump', 'commercegurus-commercekit' ); ?></h4>
	<p><?php esc_html_e( 'Maximising opportunities in the mini cart and checkout are a great way to increase revenues and can significantly improve conversion rates and average order values.', 'commercegurus-commercekit' ); ?></p>
</div>
<script>
jQuery(document).ready(function($){
	$('#commercekit_order_bump_mini').on('change', function(){
		if( $(this).prop('checked') ){
			$('#order_bump_mini_box').show();
			$('#multiple_obp_mini').show();
			if( $('#commercekit_multiple_obp_mini').prop('checked') ){
				$('#multiple_obp_mini_lbl').show();
			} else {
				$('#multiple_obp_mini_lbl').hide();
			}
		} else {
			$('#order_bump_mini_box').hide();
			$('#multiple_obp_mini').hide();
			$('#multiple_obp_mini_lbl').hide();
		}
	});
	$('#commercekit_multiple_obp_mini').on('change', function(){
		if( $(this).prop('checked') ){
			$('#multiple_obp_mini_lbl').show();
		} else {
			$('#multiple_obp_mini_lbl').hide();
		}
	});
	$('#commercekit_order_bump').on('change', function(){
		if( $(this).prop('checked') ){
			$('#order_bump_box').show();
			$('#multiple_obp').show();
			if( $('#commercekit_multiple_obp').prop('checked') ){
				$('#multiple_obp_label').show();
			} else {
				$('#multiple_obp_label').hide();
			}
		} else {
			$('#order_bump_box').hide();
			$('#multiple_obp').hide();
			$('#multiple_obp_label').hide();
		}
	});
	$('#commercekit_multiple_obp').on('change', function(){
		if( $(this).prop('checked') ){
			$('#multiple_obp_label').show();
		} else {
			$('#multiple_obp_label').hide();
		}
	});
});
</script>