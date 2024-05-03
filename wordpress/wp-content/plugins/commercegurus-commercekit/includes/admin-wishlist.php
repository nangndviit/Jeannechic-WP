<?php
/**
 *
 * Admin Wishlist
 *
 * @package CommerceKit
 * @subpackage Shoptimizer
 */

?>
<?php
global $wpdb;
if ( isset( $section ) && ! in_array( $section, array( 'settings', 'reports' ), true ) ) {
	$section = 'settings';
} elseif ( ! isset( $section ) ) {
	$section = 'settings';
}
if ( 'reports' === $section ) {
	$reports = get_transient( 'commercekit_wishlist_reports' );
	if ( ! $reports ) {
		$table      = $wpdb->prefix . 'commercekit_wishlist';
		$table2     = $wpdb->prefix . 'commercekit_wishlist_items';
		$sql1       = 'SELECT COUNT(*) FROM ' . $table;
		$list_count = (int) $wpdb->get_var( $sql1 ); // phpcs:ignore
		$sql2       = 'SELECT COUNT(DISTINCT user_id) FROM ' . $table2 . ' WHERE user_id != 0';
		$user_count = (int) $wpdb->get_var( $sql2 ); // phpcs:ignore
		$sql3       = 'SELECT product_id, COUNT(product_id) AS product_count FROM ' . $table2 . ' GROUP BY product_id ORDER BY product_count DESC LIMIT 0, 20';
		$results    = $wpdb->get_results( $sql3, ARRAY_A ); // phpcs:ignore

		$reports                   = array();
		$reports['wishlist_count'] = number_format( $list_count + $user_count, 0 );
		$reports['most_results']   = $results;

		set_transient( 'commercekit_wishlist_reports', $reports, DAY_IN_SECONDS );
	}
}
?>
<ul class="subtabs">
	<li><a href="?page=commercekit&tab=wishlist" class="<?php echo ( 'settings' === $section || '' === $section ) ? 'active' : ''; ?>"><?php esc_html_e( 'Settings', 'commercegurus-commercekit' ); ?></a></li>
	<li><a href="?page=commercekit&tab=wishlist&section=reports" class="<?php echo 'reports' === $section ? 'active' : ''; ?>"><?php esc_html_e( 'Reports', 'commercegurus-commercekit' ); ?></a> </li>
</ul>
<div id="settings-content" class="postbox content-box">
	<?php if ( 'reports' === $section ) { ?>
	<h2><span class="table-heading"><?php esc_html_e( 'Wishlist Reports', 'commercegurus-commercekit' ); ?></span></h2>
	<?php } ?>
	<?php if ( 'settings' === $section ) { ?>
	<h2><span class="table-heading"><?php esc_html_e( 'Wishlist Settings', 'commercegurus-commercekit' ); ?></span></h2>
	<?php } ?>
	<?php if ( 'settings' === $section || '' === $section ) { ?>
	<div class="inside">
			<div class="cg-notice-success"><p><?php esc_html_e( 'Note: You will need to create a wishlist page and include this shortcode on it - [commercegurus_wishlist]', 'commercegurus-commercekit' ); ?></p></div>
			<table class="form-table admin-wishlist" role="presentation">
				<tr> <th scope="row"><?php esc_html_e( 'Enable', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_wishlist" class="toggle-switch"> <input name="commercekit[wishlist]" type="checkbox" id="commercekit_wishlist" value="1" <?php echo isset( $commercekit_options['wishlist'] ) && 1 === (int) $commercekit_options['wishlist'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Enable wishlist functionality', 'commercegurus-commercekit' ); ?></label></td> </tr>
				<tr> <th scope="row"><?php esc_html_e( '&ldquo;Add to wishlist&rdquo; text', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_wsl_adtext"> <input name="commercekit[wsl_adtext]" class="pc100" type="text" id="commercekit_wsl_adtext" value="<?php echo isset( $commercekit_options['wsl_adtext'] ) && ! empty( $commercekit_options['wsl_adtext'] ) ? esc_attr( stripslashes_deep( $commercekit_options['wsl_adtext'] ) ) : commercekit_get_default_settings( 'wsl_adtext' ); // phpcs:ignore ?>" /></label></td> </tr>
				<tr> <th scope="row"><?php esc_html_e( '&ldquo;Product added&rdquo; text', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_wsl_pdtext"> <input name="commercekit[wsl_pdtext]" class="pc100" type="text" id="commercekit_wsl_pdtext" value="<?php echo isset( $commercekit_options['wsl_pdtext'] ) && ! empty( $commercekit_options['wsl_pdtext'] ) ? esc_attr( stripslashes_deep( $commercekit_options['wsl_pdtext'] ) ) : commercekit_get_default_settings( 'wsl_pdtext' ); // phpcs:ignore ?>" /></label></td> </tr>
				<tr> <th scope="row"><?php esc_html_e( '&ldquo;Browse wishlist&rdquo; text', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_wsl_brtext"> <input name="commercekit[wsl_brtext]" class="pc100" type="text" id="commercekit_wsl_brtext" value="<?php echo isset( $commercekit_options['wsl_brtext'] ) && ! empty( $commercekit_options['wsl_brtext'] ) ? esc_attr( stripslashes_deep( $commercekit_options['wsl_brtext'] ) ) : commercekit_get_default_settings( 'wsl_brtext' ); // phpcs:ignore ?>" /></label></td> </tr>
				<tr> <th scope="row"><?php esc_html_e( 'Wishlist page', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_wsl_page">
				<?php $selected = isset( $commercekit_options['wsl_page'] ) ? esc_attr( $commercekit_options['wsl_page'] ) : 0; ?>
				<select name="commercekit[wsl_page]" id="commercekit_wsl_page" class="pc100 select2" data-type="pages" data-placeholder="Select wishlist page">
				<?php
				$pid = isset( $commercekit_options['wsl_page'] ) ? esc_attr( $commercekit_options['wsl_page'] ) : 0;
				if ( $pid ) {
					echo '<option value="' . esc_attr( $pid ) . '" selected="selected">#' . esc_attr( $pid ) . ' - ' . esc_html( get_the_title( $pid ) ) . '</option>';
				}
				?>
				</select>
				</label><br /><small><em><?php esc_html_e( 'Choose your wishlist page and set it to be full width. Ensure that it is excluded from any caching solutions.', 'commercegurus-commercekit' ); ?></em></small></td> </tr>
			</table>
			<input type="hidden" name="tab" value="wishlist" />
			<input type="hidden" name="action" value="commercekit_save_settings" />
	</div>
	<?php } ?>

	<?php if ( 'reports' === $section ) { ?>
	<div class="inside ajax-search-reports">
		<div class="ajax-search-reports-boxes">
			<div class="ajax-search-reports-box">
				<h2><?php esc_html_e( 'Total wishlists', 'commercegurus-commercekit' ); ?></h2>
				<h3><?php echo isset( $reports['wishlist_count'] ) ? esc_attr( $reports['wishlist_count'] ) : 0; ?></h3>
				<p><?php esc_html_e( 'How many wishlists have been created.', 'commercegurus-commercekit' ); ?></p>
			</div>
		</div>

		<h2><?php esc_html_e( 'Most popular products', 'commercegurus-commercekit' ); ?></h2>
		<p><?php esc_html_e( 'Discover which products are most wished for in your catalog.', 'commercegurus-commercekit' ); ?></p>
		<table class="ajax-search-reports-list">
			<tr><th align="left"><?php esc_html_e( 'Product', 'commercegurus-commercekit' ); ?></th><th align="right"><?php esc_html_e( 'Count', 'commercegurus-commercekit' ); ?></th></tr>
			<?php if ( isset( $reports['most_results'] ) && count( $reports['most_results'] ) ) { ?>
				<?php foreach ( $reports['most_results'] as $index => $row ) { ?>
					<?php
					$product_title = '';
					$product_elink = '';
					$product_vlink = '';
					if ( isset( $row['product_id'] ) ) {
						$product_title = get_the_title( $row['product_id'] );
						$product_elink = get_edit_post_link( $row['product_id'] );
						$product_vlink = get_permalink( $row['product_id'] );
					}
					if ( ! $product_title ) {
						continue;
					}
					?>
					<tr><td align="left"><span><?php echo esc_attr( str_pad( $index + 1, 2, '0', STR_PAD_LEFT ) ); ?></span> <a href="<?php echo esc_url( $product_vlink ); ?>"><?php echo esc_html( $product_title ); ?></a> (ID: <a href="<?php echo esc_url( $product_elink ); ?>"><?php echo esc_attr( $row['product_id'] ); ?></a>)</td><td align="right"><?php echo isset( $row['product_count'] ) ? esc_attr( number_format( $row['product_count'], 0 ) ) : 0; ?></td></tr>
				<?php } ?>
			<?php } else { ?>
				<tr><td align="center" colspan="2"><?php esc_html_e( 'No products', 'commercegurus-commercekit' ); ?></td></tr>
			<?php } ?>
		</table>

		<br /><br />
		<p class="report-note"><?php esc_html_e( 'NOTE: Report data is updated every 24 hours.', 'commercegurus-commercekit' ); ?></p>
	</div>
	<?php } ?>
</div>

<div class="postbox" id="settings-note">
	<h4><?php esc_html_e( 'Wishlist', 'commercegurus-commercekit' ); ?></h4>
	<p><?php esc_html_e( 'A wishlist allows shoppers to create personalized collections of products they want to buy and save them for future reference.', 'commercegurus-commercekit' ); ?></p>
</div>
