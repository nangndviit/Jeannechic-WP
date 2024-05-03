<?php
/**
 *
 * Admin Size Guides
 *
 * @package CommerceKit
 * @subpackage Shoptimizer
 */

?>
<div id="settings-content" class="postbox content-box">
	<h2><span class="table-heading"><?php esc_html_e( 'Size Guides', 'commercegurus-commercekit' ); ?></span></h2>

	<div class="inside">
		<table class="form-table product-gallery" role="presentation">
			<tr> <th scope="row"><?php esc_html_e( 'Enable', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_size_guide" class="toggle-switch"> <input name="commercekit[size_guide]" type="checkbox" id="commercekit_size_guide" value="1" <?php echo isset( $commercekit_options['size_guide'] ) && 1 === (int) $commercekit_options['size_guide'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Enable size guides', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Display in search results', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_size_guide_search" class="toggle-switch"> <input name="commercekit[size_guide_search]" type="checkbox" id="commercekit_size_guide_search" value="1" <?php echo ( ( isset( $commercekit_options['size_guide_search'] ) && 1 === (int) $commercekit_options['size_guide_search'] ) || ( ! isset( $commercekit_options['size_guide_search'] ) && 1 === (int) commercekit_get_default_settings( 'size_guide_search' ) ) ) ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Make size guide pages findable within search results.', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Size guide label', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_size_guide_label"> <input name="commercekit[size_guide_label]" class="pc100" type="text" id="commercekit_size_guide_label" value="<?php echo isset( $commercekit_options['size_guide_label'] ) && ! empty( $commercekit_options['size_guide_label'] ) ? esc_attr( stripslashes_deep( $commercekit_options['size_guide_label'] ) ) : commercekit_get_default_settings( 'size_guide_label' ); // phpcs:ignore ?>" /></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Default size guide', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_default_size_guide"> <select name="commercekit[default_size_guide]" class="pc100" id="commercekit_default_size_guide"><option value=""><?php esc_html_e( 'No default size guide set', 'commercegurus-commercekit' ); ?></option>
				<?php
					$selected_sg = isset( $commercekit_options['default_size_guide'] ) && ! empty( $commercekit_options['default_size_guide'] ) ? (int) $commercekit_options['default_size_guide'] : 0;
					$sg_arges    = array(
						'post_type'        => 'ckit_size_guide',
						'post_status'      => 'publish',
						'posts_per_page'   => -1,
						'suppress_filters' => false,
						'orderby'          => 'title',
						'order'            => 'ASC',
					);
					$sg_posts    = get_posts( $sg_arges );
					$sg_no_posts = true;
					if ( count( $sg_posts ) ) {
						$sg_no_posts = false;
						foreach ( $sg_posts as $sg_post ) {
							$sel = '';
							if ( (int) $sg_post->ID === $selected_sg ) {
								$sel = 'selected="selected"';
							}
							echo '<option value="' . esc_attr( $sg_post->ID ) . '" ' . $sel . '>' . esc_attr( $sg_post->post_title ) . '</option>'; // phpcs:ignore
						}
					}
					?>
				</select>&nbsp;&nbsp;<?php echo true === $sg_no_posts ? '<a href="' . esc_url( admin_url( 'post-new.php?post_type=ckit_size_guide' ) ) . '">' . esc_html__( 'Add your first size guide', 'commercegurus-commercekit' ) . '</a>' : ''; ?></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Size guide icon', 'commercegurus-commercekit' ); ?></th> <td> <label><input type="radio" value="0" name="commercekit[size_guide_icon]" <?php echo ( isset( $commercekit_options['size_guide_icon'] ) && 0 === (int) $commercekit_options['size_guide_icon'] ) || ! isset( $commercekit_options['size_guide_icon'] ) ? 'checked="checked"' : ''; ?> onchange="if(jQuery(this).prop('checked')){jQuery('#size_guide_icon_html').hide();}else{jQuery('#size_guide_icon_html').show();}"/>&nbsp;<?php esc_html_e( 'Default', 'commercegurus-commercekit' ); ?></label> <span class="radio-space">&nbsp;</span><label><input type="radio" value="1" name="commercekit[size_guide_icon]" <?php echo isset( $commercekit_options['size_guide_icon'] ) && 1 === (int) $commercekit_options['size_guide_icon'] ? 'checked="checked"' : ''; ?> onchange="if(jQuery(this).prop('checked')){jQuery('#size_guide_icon_html').show();}else{jQuery('#size_guide_icon_html').hide();}"/>&nbsp;<?php esc_html_e( 'Custom', 'commercegurus-commercekit' ); ?></label></td></tr>
			<tr id="size_guide_icon_html" <?php echo isset( $commercekit_options['size_guide_icon'] ) && 1 === (int) $commercekit_options['size_guide_icon'] ? '' : 'style="display: none;"'; ?>> <th scope="row">&nbsp;</th> <td> <label for="commercekit_size_guide_icon_html"> <textarea name="commercekit[size_guide_icon_html]" class="pc100" rows="5" id="commercekit_size_guide_icon_html"><?php echo isset( $commercekit_options['size_guide_icon_html'] ) && ! empty( $commercekit_options['size_guide_icon_html'] ) ? stripslashes_deep( $commercekit_options['size_guide_icon_html'] ) : ''; // phpcs:ignore ?></textarea></label><br /><small><em><?php esc_html_e( 'Paste in the SVG code for the icon you would like to use. You can find example icons at ', 'commercegurus-commercekit' ); ?><a href="https://heroicons.com/" target="_blank">Heroicons</a><?php esc_html_e( ' and  ', 'commercegurus-commercekit' ); ?><a href="https://feathericons.com/" target="_blank">Feathericons</a></em></small>.</td></tr>
			<tr> <th scope="row"><?php esc_html_e( 'Display mode', 'commercegurus-commercekit' ); ?></th> <td> <label><input type="radio" value="1" name="commercekit[size_guide_mode]" <?php echo ( isset( $commercekit_options['size_guide_mode'] ) && 1 === (int) $commercekit_options['size_guide_mode'] ) || ! isset( $commercekit_options['size_guide_mode'] ) ? 'checked="checked"' : ''; ?> >&nbsp;<?php esc_html_e( 'Modal', 'commercegurus-commercekit' ); ?></label> <span class="radio-space">&nbsp;</span><label><input type="radio" value="2" name="commercekit[size_guide_mode]" <?php echo isset( $commercekit_options['size_guide_mode'] ) && 2 === (int) $commercekit_options['size_guide_mode'] ? 'checked="checked"' : ''; ?> />&nbsp;<?php esc_html_e( 'WooCommerce Tab', 'commercegurus-commercekit' ); ?></label></td></tr>
		</table>

		<input type="hidden" name="tab" value="size-guide" />
		<input type="hidden" name="action" value="commercekit_save_settings" />

	</div>
</div>

<div class="postbox" id="settings-note">
	<h4><?php esc_html_e( 'Size Guides', 'commercegurus-commercekit' ); ?></h4>
	<p><?php esc_html_e( 'If your products require sizing, this feature is crucial. It helps reduce costly returns, and improves the consumer experience.' ); ?></p>
	<p><?php esc_html_e( 'See the ', 'commercegurus-commercekit' ); ?><a href="https://www.commercegurus.com/docs/commercekit/commercekit-size-guides/" target="_blank">documentation</a> <?php esc_html_e( 'area for more details on setting up this module.', 'commercegurus-commercekit' ); ?></p>
</div>
