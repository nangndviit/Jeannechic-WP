<?php
/**
 *
 * Admin Ajax Search
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
if ( 'settings' === $section || '' === $section ) {
	$template = "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_type = 'product' AND post_parent = '0' AND post_status = 'publish' AND ID > %d ";
	$query    = $wpdb->prepare( $template, 0 ); // phpcs:ignore
	$total    = (int) $wpdb->get_var( $query ); // phpcs:ignore
	$ajs_id   = isset( $commercekit_options['generating_ajs_id'] ) ? (int) $commercekit_options['generating_ajs_id'] : 0;
	$pending  = $wpdb->prepare( $template, $ajs_id ); // phpcs:ignore
	$complete = $total - (int) $wpdb->get_var( $pending ); // phpcs:ignore
	$complete = $complete >= 0 ? $complete : 0;
}
if ( 'reports' === $section ) {
	$reports = get_transient( 'commercekit_search_reports' );
	if ( ! $reports ) {
		$table        = $wpdb->prefix . 'commercekit_searches';
		$sql1         = 'SELECT SUM(search_count) FROM ' . $table;
		$search_count = (int) $wpdb->get_var( $sql1 ); // phpcs:ignore
		$sql2         = 'SELECT SUM(click_count) FROM ' . $table;
		$click_count  = (int) $wpdb->get_var( $sql2 ); // phpcs:ignore
		$sql3         = 'SELECT SUM(no_result_count) FROM ' . $table;
		$no_res_count = (int) $wpdb->get_var( $sql3 ); // phpcs:ignore
		$sql4         = 'SELECT search_term, search_count FROM ' . $table . ' ORDER BY search_count DESC LIMIT 0, 20';
		$most_results = $wpdb->get_results( $sql4, ARRAY_A ); // phpcs:ignore
		$sql5         = 'SELECT search_term, no_result_count FROM ' . $table . ' WHERE no_result_count > 0 ORDER BY no_result_count DESC LIMIT 0, 20';
		$no_results   = $wpdb->get_results( $sql5, ARRAY_A ); // phpcs:ignore

		$reports                  = array();
		$reports['search_count']  = number_format( $search_count, 0 );
		$reports['click_percent'] = $search_count > 0 ? number_format( ( $click_count / $search_count ) * 100, 1 ) : 0;
		$reports['nores_percent'] = $search_count > 0 ? number_format( ( $no_res_count / $search_count ) * 100, 1 ) : 0;
		$reports['most_results']  = $most_results;
		$reports['no_results']    = $no_results;

		set_transient( 'commercekit_search_reports', $reports, DAY_IN_SECONDS );
	}
}
?>
<ul class="subtabs">
	<li><a href="?page=commercekit&tab=ajax-search" class="<?php echo ( 'settings' === $section || '' === $section ) ? 'active' : ''; ?>"><?php esc_html_e( 'Settings', 'commercegurus-commercekit' ); ?></a></li>
	<li><a href="?page=commercekit&tab=ajax-search&section=reports" class="<?php echo 'reports' === $section ? 'active' : ''; ?>"><?php esc_html_e( 'Reports', 'commercegurus-commercekit' ); ?></a> </li>
</ul>
<div id="settings-content" class="postbox content-box">
	<?php if ( 'reports' === $section ) { ?>
	<h2><span class="table-heading"><?php esc_html_e( 'Ajax Search Reports', 'commercegurus-commercekit' ); ?></span></h2>
	<?php } ?>
	<?php if ( 'settings' === $section ) { ?>
	<h2><span class="table-heading"><?php esc_html_e( 'Ajax Search Settings', 'commercegurus-commercekit' ); ?></span></h2>
	<?php } ?>
	<?php if ( 'settings' === $section || '' === $section ) { ?>
	<div class="inside">
		<table class="form-table" role="presentation">
			<tr> <th scope="row"><?php esc_html_e( 'Enable', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajax_search" class="toggle-switch"> <input name="commercekit[ajax_search]" type="checkbox" id="commercekit_ajax_search" value="1" <?php echo isset( $commercekit_options['ajax_search'] ) && 1 === (int) $commercekit_options['ajax_search'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Enable Ajax Search in the main search bar', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Placeholder', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_placeholder"> <input name="commercekit[ajs_placeholder]" type="text" class="pc100" id="commercekit_ajs_placeholder" value="<?php echo isset( $commercekit_options['ajs_placeholder'] ) && ! empty( $commercekit_options['ajs_placeholder'] ) ? esc_attr( stripslashes_deep( $commercekit_options['ajs_placeholder'] ) ) : commercekit_get_default_settings( 'ajs_placeholder' ); // phpcs:ignore ?>"  /></label></td> </tr>
			<tr> <td colspan="2"><h3 style="margin:0;"><?php esc_html_e( 'Products', 'commercegurus-commercekit' ); ?></h3></td> </tr>
			<tr style="display: none;"> <th scope="row"><?php esc_html_e( 'Display', 'commercegurus-commercekit' ); ?></th> <td> <label><input type="radio" value="all" name="commercekit[ajs_display]" <?php echo ( isset( $commercekit_options['ajs_display'] ) && 'all' === $commercekit_options['ajs_display'] ) || ! isset( $commercekit_options['ajs_display'] ) ? 'checked="checked"' : ''; ?> onchange="if(jQuery(this).prop('checked')){jQuery('#ajs_tabbed_wrap').hide();jQuery('#ajs_tabbed_wrap2').hide();}else{jQuery('#ajs_tabbed_wrap').hide();jQuery('#ajs_tabbed_wrap2').hide();}"/>&nbsp;<?php esc_html_e( 'All contents', 'commercegurus-commercekit' ); ?></label> <span class="radio-space">&nbsp;</span><label><input type="radio" value="product" name="commercekit[ajs_display]" <?php echo isset( $commercekit_options['ajs_display'] ) && 'product' === $commercekit_options['ajs_display'] ? 'checked="checked"' : ''; ?> onchange="if(jQuery(this).prop('checked')){jQuery('#ajs_tabbed_wrap').hide();jQuery('#ajs_tabbed_wrap2').hide();}else{jQuery('#ajs_tabbed_wrap').hide();jQuery('#ajs_tabbed_wrap2').hide();}"/>&nbsp;<?php esc_html_e( 'Just products', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr id="ajs_tabbed_wrap" <?php echo isset( $commercekit_options['ajs_display'] ) && 'product' === $commercekit_options['ajs_display'] ? 'style="display:none;"' : 'style="display: none;"'; ?>> <th scope="row"><?php esc_html_e( 'Tabbed search results', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_tabbed" class="toggle-switch"> <input name="commercekit[ajs_tabbed]" type="checkbox" id="commercekit_ajs_tabbed" value="1" <?php echo isset( $commercekit_options['ajs_tabbed'] ) && 1 === (int) $commercekit_options['ajs_tabbed'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Enable search results tabs', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr id="ajs_tabbed_wrap2" <?php echo isset( $commercekit_options['ajs_display'] ) && 'product' === $commercekit_options['ajs_display'] ? 'style="display:none;"' : 'style="display: none;"'; ?>> <th scope="row"><?php esc_html_e( 'Preserve selected tab', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_pre_tab" class="toggle-switch"> <input name="commercekit[ajs_pre_tab]" type="checkbox" id="commercekit_ajs_pre_tab" value="1" <?php echo isset( $commercekit_options['ajs_pre_tab'] ) && 1 === (int) $commercekit_options['ajs_pre_tab'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Enable preserve selected tab on next visit', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( '&ldquo;No results&rdquo; text', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_no_text"> <input name="commercekit[ajs_no_text]" type="text" class="pc100" id="commercekit_ajs_no_text" value="<?php echo isset( $commercekit_options['ajs_no_text'] ) && ! empty( $commercekit_options['ajs_no_text'] ) ? esc_attr( stripslashes_deep( $commercekit_options['ajs_no_text'] ) ) : commercekit_get_default_settings( 'ajs_no_text' ); // phpcs:ignore ?>"  /></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( '&ldquo;View all&rdquo; text', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_all_text"> <input name="commercekit[ajs_all_text]" type="text" class="pc100" id="commercekit_ajs_all_text" value="<?php echo isset( $commercekit_options['ajs_all_text'] ) && ! empty( $commercekit_options['ajs_all_text'] ) ? esc_attr( stripslashes_deep( $commercekit_options['ajs_all_text'] ) ) : commercekit_get_default_settings( 'ajs_all_text' ); // phpcs:ignore ?>"  /></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Out of stock products', 'commercegurus-commercekit' ); ?></th> <td> <label><input type="radio" value="0" name="commercekit[ajs_outofstock]" <?php echo ( isset( $commercekit_options['ajs_outofstock'] ) && 0 === (int) $commercekit_options['ajs_outofstock'] ) || ! isset( $commercekit_options['ajs_outofstock'] ) ? 'checked="checked"' : ''; ?> onchange="if(jQuery(this).prop('checked')){jQuery('#ajs_orderby_oos').show();}else{jQuery('#ajs_orderby_oos').hide();}"/>&nbsp;<?php esc_html_e( 'Include', 'commercegurus-commercekit' ); ?></label> <span class="radio-space">&nbsp;</span><label><input type="radio" value="1" name="commercekit[ajs_outofstock]" <?php echo isset( $commercekit_options['ajs_outofstock'] ) && 1 === (int) $commercekit_options['ajs_outofstock'] ? 'checked="checked"' : ''; ?> onchange="if(jQuery(this).prop('checked')){jQuery('#ajs_orderby_oos').hide();}else{jQuery('#ajs_orderby_oos').show();}"/>&nbsp;<?php esc_html_e( 'Exclude', 'commercegurus-commercekit' ); ?></label></td></tr>
			<tr id="ajs_orderby_oos" <?php echo isset( $commercekit_options['ajs_outofstock'] ) && 1 === (int) $commercekit_options['ajs_outofstock'] ? 'style="display:none;"' : ''; ?>> <th scope="row"><?php esc_html_e( 'Out of stock results order', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_orderby_oos" class="toggle-switch"> <input name="commercekit[ajs_orderby_oos]" type="checkbox" id="commercekit_ajs_orderby_oos" value="1" <?php echo isset( $commercekit_options['ajs_orderby_oos'] ) && 1 === (int) $commercekit_options['ajs_orderby_oos'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Display out of stock items at the end of the search results', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Exclude', 'commercegurus-commercekit' ); ?></th>  <td> <label for="commercekit_ajs_excludes"> <input name="commercekit[ajs_excludes]" type="text" id="commercekit_ajs_excludes" class="pc100" value="<?php echo isset( $commercekit_options['ajs_excludes'] ) && ! empty( $commercekit_options['ajs_excludes'] ) ? esc_attr( $commercekit_options['ajs_excludes'] ) : ''; ?>"  /></label><br /><small><em><?php esc_html_e( 'Enter Product ID&rsquo;s to be excluded, separated by a comma.', 'commercegurus-commercekit' ); ?></em></small></td></tr>  
			<tr style="display: none;"> <th scope="row"><?php esc_html_e( 'Hide variations', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_hidevar" class="toggle-switch"> <input name="commercekit[ajs_hidevar]" type="checkbox" id="commercekit_ajs_hidevar" value="1" <?php echo isset( $commercekit_options['ajs_hidevar'] ) && 1 === (int) $commercekit_options['ajs_hidevar'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Hide variations from search suggestions', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Number of products displayed', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_product_count"> <input name="commercekit[ajs_product_count]" type="number" class="pc100" id="commercekit_ajs_product_count" min="1" max="5" value="<?php echo isset( $commercekit_options['ajs_product_count'] ) && ! empty( $commercekit_options['ajs_product_count'] ) ? esc_attr( stripslashes_deep( $commercekit_options['ajs_product_count'] ) ) : commercekit_get_default_settings( 'ajs_product_count' ); // phpcs:ignore ?>" style="max-width: 100px;" /></label></td> </tr>
			<tr> <td colspan="2"><h3 style="margin:0;"><?php esc_html_e( 'Other results', 'commercegurus-commercekit' ); ?></h3></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Enable other results', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_other_results" class="toggle-switch"> <input name="commercekit[ajs_other_results]" type="checkbox" id="commercekit_ajs_other_results" value="1" <?php echo ( isset( $commercekit_options['ajs_other_results'] ) && 1 === (int) $commercekit_options['ajs_other_results'] ) || ! isset( $commercekit_options['ajs_other_results'] ) ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Display other results like posts / pages etc.', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( '&ldquo;Other results&rdquo; text', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_other_text"> <input name="commercekit[ajs_other_text]" type="text" class="pc100" id="commercekit_ajs_other_text" value="<?php echo isset( $commercekit_options['ajs_other_text'] ) && ! empty( $commercekit_options['ajs_other_text'] ) ? esc_attr( stripslashes_deep( $commercekit_options['ajs_other_text'] ) ) : commercekit_get_default_settings( 'ajs_other_text' ); // phpcs:ignore ?>"  /></label></td> </tr>
			<tr style="display:none"> <th scope="row"><?php esc_html_e( '&ldquo;No other results&rdquo; text', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_no_other_text"> <input name="commercekit[ajs_no_other_text]" type="text" class="pc100" id="commercekit_ajs_no_other_text" value="<?php echo isset( $commercekit_options['ajs_no_other_text'] ) && ! empty( $commercekit_options['ajs_no_other_text'] ) ? esc_attr( stripslashes_deep( $commercekit_options['ajs_no_other_text'] ) ) : commercekit_get_default_settings( 'ajs_no_other_text' ); // phpcs:ignore ?>"  /></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( '&ldquo;View other all&rdquo; text', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_other_all_text"> <input name="commercekit[ajs_other_all_text]" type="text" class="pc100" id="commercekit_ajs_other_all_text" value="<?php echo isset( $commercekit_options['ajs_other_all_text'] ) && ! empty( $commercekit_options['ajs_other_all_text'] ) ? esc_attr( stripslashes_deep( $commercekit_options['ajs_other_all_text'] ) ) : commercekit_get_default_settings( 'ajs_other_all_text' ); // phpcs:ignore ?>"  /></label></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Exclude other results', 'commercegurus-commercekit' ); ?></th>  <td> <label for="commercekit_ajs_excludes_other"> <input name="commercekit[ajs_excludes_other]" type="text" id="commercekit_ajs_excludes_other" class="pc100" value="<?php echo isset( $commercekit_options['ajs_excludes_other'] ) && ! empty( $commercekit_options['ajs_excludes_other'] ) ? esc_attr( $commercekit_options['ajs_excludes_other'] ) : ''; ?>"  /></label><br /><small><em><?php esc_html_e( 'Enter Post / Page ID&rsquo;s to be excluded, separated by a comma.', 'commercegurus-commercekit' ); ?></em></small></td></tr>  
			<tr> <th scope="row"><?php esc_html_e( 'Number of other result displayed', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_other_count"> <input name="commercekit[ajs_other_count]" type="number" class="pc100" id="commercekit_ajs_other_count" min="1" max="5" value="<?php echo isset( $commercekit_options['ajs_other_count'] ) && ! empty( $commercekit_options['ajs_other_count'] ) ? esc_attr( stripslashes_deep( $commercekit_options['ajs_other_count'] ) ) : commercekit_get_default_settings( 'ajs_other_count' ); // phpcs:ignore ?>" style="max-width: 100px;" /></label></td> </tr>
			<tr> <td colspan="2"><hr /><h3><?php esc_html_e( 'Clear Ajax Search Index', 'commercegurus-commercekit' ); ?></h3></td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Total products', 'commercegurus-commercekit' ); ?></th> <td> <strong><span title="<?php esc_html_e( 'Total products', 'commercegurus-commercekit' ); ?>" id="cgkit-ajs-total"><?php echo esc_attr( $total ); ?></span></strong> </td> </tr>
			<tr> <th scope="row"><?php esc_html_e( 'Indexed products', 'commercegurus-commercekit' ); ?></th> <td> <strong style="position: relative; bottom: -1px"><span title="<?php esc_html_e( 'Total indexed products', 'commercegurus-commercekit' ); ?>" id="cgkit-ajs-cached"><?php echo esc_attr( $complete ); ?></span> </strong> <button type="button" class="button button-primary" id="wc-product-ajs-index" onclick="if(confirm('<?php esc_html_e( 'Are you sure you want to clear and rebuild the ajax search index?', 'commercegurus-commercekit' ); ?>')){update_wc_product_ajs_index_status(true);}" style="margin-left: 40px; vertical-align: middle;" disabled="disabled"><?php esc_html_e( 'Clear and rebuild ajax search index', 'commercegurus-commercekit' ); ?></button> </td> </tr>
				<tr id="cgkit-ajs-logger" class="disable-events"> <th scope="row"><?php esc_html_e( 'Enable logger', 'commercegurus-commercekit' ); ?></th> <td> <label for="commercekit_ajs_index_logger" class="toggle-switch"> <input name="commercekit[ajs_index_logger]" type="checkbox" id="commercekit_ajs_index_logger" value="1" <?php echo isset( $commercekit_options['ajs_index_logger'] ) && 1 === (int) $commercekit_options['ajs_index_logger'] ? 'checked="checked"' : ''; ?>><span class="toggle-slider"></span></label><label>&nbsp;&nbsp;<?php esc_html_e( 'Enable Product ajax search index rebuilding logger', 'commercegurus-commercekit' ); ?></label></td> </tr>
			<tr> <td colspan="2">
			<div id="cgkit-ajs-cache-status">
				<!-- When starting -->
				<div class="cache-event-alert" id="event-created" style="display:none;">
					<div class="cache-loader">
						<div class="att-loader"></div><?php esc_html_e( 'Indexing event being created...', 'commercegurus-commercekit' ); ?>
					</div>
				</div>

				<!-- Switch to this when processing -->
				<div class="cache-event-alert" id="event-processing" style="display:none;">
					<div class="cache-processing">
						<div class="cache-bar">
							<div class="cache-progress" id="percent" style="width: 0%"></div>
						</div>
						<div class="cache-value">
							<div class="att-loader"></div><?php esc_html_e( 'Processing indexing event.', 'commercegurus-commercekit' ); ?>&nbsp;<span id="complete">0</span>/<span id="total">0</span>&nbsp;<?php esc_html_e( 'completed...', 'commercegurus-commercekit' ); ?>
						</div>
					</div>
				</div>

				<!-- Switch to this when completed -->
				<div class="cache-event-alert" id="event-completed" style="display:none;">
					<div class="cache-processing">
						<div class="cache-bar">
							<div class="cache-completed" style="width: 100%"></div>
						</div>
						<div class="cache-value completed">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
							</svg>
							<?php esc_html_e( 'Index rebuild complete', 'commercegurus-commercekit' ); ?>
						</div>
					</div>
					<h3 class="ckit_sub-heading" style="display:none;"><?php esc_html_e( 'Last log message', 'commercegurus-commercekit' ); ?></h3>
					<div class="log-message" style="display:none;"><?php esc_html_e( 'The index process apparently succeeded and is now complete', 'commercegurus-commercekit' ); ?> (<span id="build_done"></span>)</div>
				</div>

				<!-- Switch to this when interrupt -->
				<div class="cache-event-alert" id="event-interrupt" style="display:none;">
					<div class="log-message log-failed"><?php esc_html_e( 'The indexing process interrupted due to disabled Ajax Search module. Enable Ajax Search module and click on "Clear and rebuild ajax search index" button to rebuild all products ajax search index.', 'commercegurus-commercekit' ); ?></div>
				</div>
			</div>			
			</td> </tr>
		</table>
		<input type="hidden" name="tab" value="ajax-search" />
		<input type="hidden" name="action" value="commercekit_save_settings" />
	</div>
	<?php } ?>

	<?php if ( 'reports' === $section ) { ?>
	<div class="inside ajax-search-reports">
		<div class="ajax-search-reports-boxes">
			<div class="ajax-search-reports-box">
				<h2><?php esc_html_e( 'Total searches', 'commercegurus-commercekit' ); ?></h2>
				<h3><?php echo isset( $reports['search_count'] ) ? esc_attr( $reports['search_count'] ) : 0; ?></h3>
				<p><?php esc_html_e( 'How many searches have been performed.', 'commercegurus-commercekit' ); ?></p>
			</div>
			<div class="ajax-search-reports-box">
				<h2><?php esc_html_e( 'Clickthrough rate', 'commercegurus-commercekit' ); ?></h2>
				<h3><?php echo isset( $reports['click_percent'] ) ? esc_attr( $reports['click_percent'] ) : 0; ?>%</h3>
				<p><?php esc_html_e( 'The % of searches that resulted in a click.', 'commercegurus-commercekit' ); ?></p>
			</div>
			<div class="ajax-search-reports-box">
				<h2><?php esc_html_e( 'No result rate', 'commercegurus-commercekit' ); ?></h2>
				<h3 id="no-result-percent"><?php echo isset( $reports['nores_percent'] ) ? esc_attr( $reports['nores_percent'] ) : 0; ?>%</h3>
				<p><?php esc_html_e( 'The % of searches that returned no results.', 'commercegurus-commercekit' ); ?></p>
			</div>
		</div>

		<h2><?php esc_html_e( 'Most frequent searches', 'commercegurus-commercekit' ); ?></h2>
		<p><?php esc_html_e( 'Discover what your users are searching for most.', 'commercegurus-commercekit' ); ?></p>
		<table class="ajax-search-reports-list">
			<tr><th align="left"><?php esc_html_e( 'Term', 'commercegurus-commercekit' ); ?></th><th align="right"><?php esc_html_e( 'Count', 'commercegurus-commercekit' ); ?></th></tr>
			<?php if ( isset( $reports['most_results'] ) && count( $reports['most_results'] ) ) { ?>
				<?php foreach ( $reports['most_results'] as $index => $row ) { ?>
					<tr><td align="left"><span><?php echo esc_attr( str_pad( $index + 1, 2, '0', STR_PAD_LEFT ) ); ?></span> <?php echo isset( $row['search_term'] ) ? esc_attr( $row['search_term'] ) : ''; ?></td><td align="right"><?php echo isset( $row['search_count'] ) ? esc_attr( number_format( $row['search_count'], 0 ) ) : 0; ?></td></tr>
				<?php } ?>
			<?php } else { ?>
				<tr><td align="center" colspan="2"><?php esc_html_e( 'No terms', 'commercegurus-commercekit' ); ?></td></tr>
			<?php } ?>
		</table>

		<h2><?php esc_html_e( 'Most frequent searches returning 0 results', 'commercegurus-commercekit' ); ?></h2>
		<p><?php esc_html_e( 'Users are searching for these queries and encounter no results.', 'commercegurus-commercekit' ); ?> <button type="button" class="button button-primary" id="reset-ajs-no-result" onclick="if(confirm('<?php esc_html_e( 'Are you sure you want to reset no result counts only?', 'commercegurus-commercekit' ); ?>')){reset_ajs_zero_results();}" style="float: right;"><?php esc_html_e( 'Reset no result counts', 'commercegurus-commercekit' ); ?></button></p>
		<table class="ajax-search-reports-list" id="ajs-zero-results">
			<tr><th align="left"><?php esc_html_e( 'Term', 'commercegurus-commercekit' ); ?></th><th align="right"><?php esc_html_e( 'Count', 'commercegurus-commercekit' ); ?></th></tr>
			<?php if ( isset( $reports['no_results'] ) && count( $reports['no_results'] ) ) { ?>
				<?php foreach ( $reports['no_results'] as $index => $row ) { ?>
					<tr><td align="left"><span><?php echo esc_attr( str_pad( $index + 1, 2, '0', STR_PAD_LEFT ) ); ?></span> <?php echo isset( $row['search_term'] ) ? esc_attr( $row['search_term'] ) : ''; ?></td><td align="right"><?php echo isset( $row['no_result_count'] ) ? esc_attr( number_format( $row['no_result_count'], 0 ) ) : 0; ?></td></tr>
				<?php } ?>
			<?php } else { ?>
				<tr><td align="center" colspan="2"><?php esc_html_e( 'No terms', 'commercegurus-commercekit' ); ?></td></tr>
			<?php } ?>
		</table><br /><br />
		<p class="report-note"><?php esc_html_e( 'NOTE: Report data is updated every 24 hours.', 'commercegurus-commercekit' ); ?></p>
	</div>
	<?php } ?>
</div>
<div class="postbox" id="settings-note">
	<h4><?php esc_html_e( 'Ajax Search', 'commercegurus-commercekit' ); ?></h4>
	<p><?php esc_html_e( 'Research has shown that instant search results are an important feature on eCommerce sites. It helps users save time and find products faster.', 'commercegurus-commercekit' ); ?></p>
</div>

<script>
jQuery(document).ready(function(){
	if( jQuery('#wc-product-ajs-index').length > 0 ) {
		update_wc_product_ajs_index_status(false);
	}
});
function update_wc_product_ajs_index_status(generate){
	var cach_btn = jQuery('#wc-product-ajs-index');
	var ajs_logger = jQuery('#cgkit-ajs-logger');
	cach_btn.attr('disabled', 'disabled');
	ajs_logger.addClass('disable-events');
	var generate_ajs = generate ? 1 : 0;
	if( generate ) {
		jQuery('#cgkit-ajs-cache-status .cache-event-alert').hide();
	}
	jQuery.ajax({
		url: ajaxurl+'?action=commercekit_ajs_generate_wc_product_index',
		type: 'POST',
		data: { generate_ajs: generate_ajs },
		dataType: 'json',
		success: function( json ) {
			jQuery('#cgkit-ajs-cache-status .cache-event-alert').hide();
			if( json.interrupt_ajs == 1 ){
				jQuery('#cgkit-ajs-cached').html(json.complete);
				jQuery('#event-interrupt').show();
			} else if( json.generating_ajs == 1 && json.complete == 0 ){
				jQuery('#cgkit-ajs-cached').html('0');
				jQuery('#event-created').show();
			} else if ( json.generating_ajs == 1 && json.complete != 0 ){
				jQuery('#cgkit-ajs-cached').html(json.complete);
				jQuery('#complete').html(json.complete);
				jQuery('#total').html(json.total);
				jQuery('#percent').css('width', json.percent+'%');
				jQuery('#event-processing').show();
			} else if ( json.generating_ajs != 1 && json.total == json.complete ){
				jQuery('#cgkit-ajs-cached').html(json.complete);
				jQuery('#build_done').html(json.build_done);
				jQuery('#event-completed').show();
			}
			if ( json.generating_ajs == 1 ){
				setTimeout( function(){ update_wc_product_ajs_index_status(false); }, 5000 );
			} else {
				cach_btn.removeAttr('disabled');
				ajs_logger.removeClass('disable-events');
			}
		}
	});
}
function reset_ajs_zero_results(){
	jQuery('#ajax-loading-mask').show();
	jQuery.ajax({
		url: ajaxurl+'?action=commercekit_ajs_reset_zero_results',
		type: 'POST',
		dataType: 'json',
		success: function( json ) {
			jQuery('#ajax-loading-mask').hide();
			jQuery('#ajs-zero-results').html(json.html);
			jQuery('#no-result-percent').html(json.percent);
		}
	});
}
</script>
