<?php
/**
 *
 * Ajax Search module
 *
 * @package CommerceKit
 * @subpackage Shoptimizer
 */

/**
 * Ajax search options
 *
 * @return string
 */
function commercekit_ajs_options() {
	$cgkit_fast_ajax_search         = defined( 'CGKIT_FAST_AJAX_SEARCH' ) && true === CGKIT_FAST_AJAX_SEARCH ? true : false;
	$commercekit_options            = get_option( 'commercekit', array() );
	$cgkit_ajs                      = array();
	$cgkit_ajs['ajax_url']          = COMMERCEKIT_AJAX::get_endpoint();
	$cgkit_ajs['ajax_search']       = isset( $commercekit_options['ajax_search'] ) && 1 === (int) $commercekit_options['ajax_search'] ? 1 : 0;
	$cgkit_ajs['char_count']        = 3;
	$cgkit_ajs['action']            = 'commercekit_ajax_search';
	$cgkit_ajs['loader_icon']       = CKIT_URI . 'assets/images/loader2.gif';
	$cgkit_ajs['no_results_text']   = isset( $commercekit_options['ajs_no_text'] ) && ! empty( $commercekit_options['ajs_no_text'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $commercekit_options['ajs_no_text'] ) ) : commercekit_get_default_settings( 'ajs_no_text' );
	$cgkit_ajs['placeholder_text']  = isset( $commercekit_options['ajs_placeholder'] ) && ! empty( $commercekit_options['ajs_placeholder'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $commercekit_options['ajs_placeholder'] ) ) : commercekit_get_default_settings( 'ajs_placeholder' );
	$cgkit_ajs['other_result_text'] = isset( $commercekit_options['ajs_other_text'] ) && ! empty( $commercekit_options['ajs_other_text'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $commercekit_options['ajs_other_text'] ) ) : commercekit_get_default_settings( 'ajs_other_text' );
	$cgkit_ajs['view_all_text']     = isset( $commercekit_options['ajs_all_text'] ) && ! empty( $commercekit_options['ajs_all_text'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $commercekit_options['ajs_all_text'] ) ) : commercekit_get_default_settings( 'ajs_all_text' );
	$cgkit_ajs['no_other_text']     = isset( $commercekit_options['ajs_no_other_text'] ) && ! empty( $commercekit_options['ajs_no_other_text'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $commercekit_options['ajs_no_other_text'] ) ) : commercekit_get_default_settings( 'ajs_no_other_text' );
	$cgkit_ajs['other_all_text']    = isset( $commercekit_options['ajs_other_all_text'] ) && ! empty( $commercekit_options['ajs_other_all_text'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $commercekit_options['ajs_other_all_text'] ) ) : commercekit_get_default_settings( 'ajs_other_all_text' );
	$cgkit_ajs['ajax_url_product']  = $cgkit_fast_ajax_search ? home_url( '/' ) . 'wp-content/plugins/commercegurus-commercekit/commercegurus-fast-ajax-search.php?search_type=product' : home_url( '/' ) . '?cgkit_ajax_search_product=1'; // phpcs:ignore;
	$cgkit_ajs['ajax_url_post']     = $cgkit_fast_ajax_search ? home_url( '/' ) . 'wp-content/plugins/commercegurus-commercekit/commercegurus-fast-ajax-search.php?search_type=post' : home_url( '/' ) . '?cgkit_ajax_search_post=1'; // phpcs:ignore;
	$cgkit_ajs['fast_ajax_search']  = $cgkit_fast_ajax_search ? 1 : 0;
	$cgkit_ajs['ajs_other_results'] = ( isset( $commercekit_options['ajs_other_results'] ) && 1 === (int) $commercekit_options['ajs_other_results'] ) || ! isset( $commercekit_options['ajs_other_results'] ) ? 1 : 0;
	$cgkit_ajs['layout']            = 'product';

	$all_post_types = get_post_types( array( 'exclude_from_search' => false ) );
	if ( is_array( $all_post_types ) && in_array( 'product', $all_post_types, true ) ) {
		unset( $all_post_types['product'] );
		unset( $all_post_types['product_variation'] );
	}
	$all_post_types   = array_filter( $all_post_types );
	$saved_post_types = isset( $commercekit_options['ajs_other_post_types'] ) ? $commercekit_options['ajs_other_post_types'] : array();
	if ( count( $all_post_types ) !== count( array_intersect( $all_post_types, $saved_post_types ) ) ) {
		$commercekit_options['ajs_other_post_types'] = $all_post_types;
		update_option( 'commercekit', $commercekit_options, false );
	}

	return $cgkit_ajs;
}
add_action( 'wp_ajax_commercekit_ajax_search', 'commercekit_ajax_do_search' );
add_action( 'wp_ajax_nopriv_commercekit_ajax_search', 'commercekit_ajax_do_search' );

/**
 * Ajax search custom query
 *
 * @param  string $vars of form.
 */
function commercekit_ajax_search_custom_query_var( $vars ) {
	$vars[] = 'cgkit_ajax_search_product';
	$vars[] = 'cgkit_ajax_search_post';

	return $vars;
}
add_filter( 'query_vars', 'commercekit_ajax_search_custom_query_var' );

/**
 * Ajax search custom query handle
 */
function commercekit_ajax_search_custom_query_var_handle() {
	$is_cgkit_ajs = (int) get_query_var( 'cgkit_ajax_search_product' );
	if ( 1 === $is_cgkit_ajs || ( isset( $_GET['cgkit_ajax_search_product'] ) && 1 === (int) $_GET['cgkit_ajax_search_product'] ) ) { // phpcs:ignore
		commercekit_ajax_do_search( 'product' );
	}
	$is_cgkit_ajs_post = (int) get_query_var( 'cgkit_ajax_search_post' );
	if ( 1 === $is_cgkit_ajs_post || ( isset( $_GET['cgkit_ajax_search_post'] ) && 1 === (int) $_GET['cgkit_ajax_search_post'] ) ) { // phpcs:ignore
		commercekit_ajax_do_search( 'post' );
	}
}
add_action( 'init', 'commercekit_ajax_search_custom_query_var_handle' );

/**
 * Ajax search form html
 *
 * @param  string $html of form.
 */
function commercekit_ajax_search_form( $html ) {
	$commercekit_options = get_option( 'commercekit', array() );
	$placeholder_text    = isset( $commercekit_options['ajs_placeholder'] ) && ! empty( $commercekit_options['ajs_placeholder'] ) ? commercekit_get_multilingual_string( stripslashes_deep( $commercekit_options['ajs_placeholder'] ) ) : commercekit_get_default_settings( 'ajs_placeholder' );

	$html = preg_replace( '/placeholder=\"([^"]*)\"/i', 'placeholder="' . $placeholder_text . '"', $html );

	return $html;
}
add_filter( 'get_search_form', 'commercekit_ajax_search_form', 99 );
add_filter( 'get_product_search_form', 'commercekit_ajax_search_form', 99 );

/**
 * Custom search template
 *
 * @param  string $template of search.
 */
function commercekit_custom_search_template( $template ) {
	global $wp_query, $cgkit_ajs_tabbed;
	$options     = get_option( 'commercekit', array() );
	$ajs_tabbed  = false;
	$ajs_display = false;

	$cgkit_ajs_tabbed = false;
	if ( $wp_query->is_search && $ajs_tabbed && $ajs_display ) {
		$cgkit_ajs_tabbed = true;
		return dirname( __FILE__ ) . '/templates/search.php';
	} else {
		return $template;
	}
}
add_filter( 'template_include', 'commercekit_custom_search_template' );

/**
 * Custom search query
 *
 * @param  string $query of search.
 */
function commercekit_custom_search_query( $query ) {
	global $commercekit_ajs_index, $commercekit_ajs_s;
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$nonce         = wp_verify_nonce( 'commercekit_nonce', 'commercekit_settings' );
	$get_post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';

	if ( 'product' === $get_post_type && $query->is_search() ) {
		$search_txt  = $query->get( 's' );
		$return_data = $commercekit_ajs_index->get_search_product_ids( $search_txt, true );
		$product_ids = isset( $return_data['ids'] ) ? $return_data['ids'] : array();
		if ( count( $product_ids ) ) {
			$commercekit_ajs_s = $search_txt;
			$query->set( 's', '' );
			$query->set( 'post__in', $product_ids );
		}
	} elseif ( 'cgkit-post' === $get_post_type && $query->is_search() ) {
		$all_post_types = get_post_types( array( 'exclude_from_search' => false ) );
		if ( is_array( $all_post_types ) && in_array( 'product', $all_post_types, true ) ) {
			unset( $all_post_types['product'] );
			unset( $all_post_types['product_variation'] );
		}
		$query->set( 'post_type', $all_post_types );
		$query->set( 'post_status', 'publish' );
	}
}
add_action( 'pre_get_posts', 'commercekit_custom_search_query', 999, 1 );

/**
 * Custom search post filter
 *
 * @param  string $posts list of posts.
 * @param  string $query of search.
 */
function commercekit_custom_search_posts( $posts, $query ) {
	global $commercekit_ajs_s;
	if ( is_admin() || ! $query->is_main_query() ) {
		return $posts;
	}

	$nonce         = wp_verify_nonce( 'commercekit_nonce', 'commercekit_settings' );
	$get_post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';

	if ( 'product' === $get_post_type && $query->is_search() && isset( $commercekit_ajs_s ) && ! empty( $commercekit_ajs_s ) ) {
		$query->set( 's', $commercekit_ajs_s );
	}

	return $posts;
}
add_filter( 'the_posts', 'commercekit_custom_search_posts', 10, 2 );

/**
 * Custom order by search query
 *
 * @param  string $query of search.
 */
function commercekit_ajs_order_by_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$nonce         = wp_verify_nonce( 'commercekit_nonce', 'commercekit_settings' );
	$get_post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';

	if ( 'product' === $get_post_type && $query->is_search() ) {
		add_filter( 'posts_clauses', 'commercekit_ajs_order_by_stock_status', 999, 1 );
	}
}
add_action( 'pre_get_posts', 'commercekit_ajs_order_by_pre_get_posts', 99, 1 );

/**
 * Custom order by stock status
 *
 * @param  string $posts_clauses posts clauses.
 */
function commercekit_ajs_order_by_stock_status( $posts_clauses ) {
	global $wpdb;
	$options     = get_option( 'commercekit', array() );
	$outofstock  = isset( $options['ajs_outofstock'] ) && 1 === (int) $options['ajs_outofstock'] ? false : true;
	$orderby_oos = isset( $options['ajs_orderby_oos'] ) && 1 === (int) $options['ajs_orderby_oos'] ? true : false;

	if ( $outofstock && $orderby_oos ) {
		$posts_clauses['join']   .= " LEFT JOIN {$wpdb->postmeta} istockstatus ON ( {$wpdb->posts}.ID = istockstatus.post_id AND istockstatus.meta_key = '_stock_status' ) ";
		$posts_clauses['orderby'] = ' istockstatus.meta_value ASC, ' . $posts_clauses['orderby'];
	} elseif ( ! $outofstock ) {
		$posts_clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} istockstatus ON ( {$wpdb->posts}.ID = istockstatus.post_id AND istockstatus.meta_key = '_stock_status' ) ";
		$posts_clauses['where'] = " AND ( istockstatus.meta_value NOT IN ( 'outofstock' ) OR istockstatus.meta_value IS NULL ) " . $posts_clauses['where'];
	}

	return $posts_clauses;
}

/**
 * Ajax search counts.
 */
function commercekit_ajax_search_counts() {
	global $wpdb;
	$ajax            = array();
	$ajax['status']  = 1;
	$ajax['message'] = '';

	$nonce     = wp_verify_nonce( 'commercekit_nonce', 'commercekit_settings' );
	$query     = isset( $_GET['query'] ) ? sanitize_text_field( wp_unslash( $_GET['query'] ) ) : '';
	$no_result = isset( $_GET['no_result'] ) ? (int) sanitize_text_field( wp_unslash( $_GET['no_result'] ) ) : 0;
	$table     = $wpdb->prefix . 'commercekit_searches';
	if ( $query ) {
		$search_ids = isset( $_COOKIE['commercekit_search_ids'] ) && ! empty( $_COOKIE['commercekit_search_ids'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_COOKIE['commercekit_search_ids'] ) ) ) : array();
		$search_ids = array_map( 'intval', $search_ids );
		$sql        = 'SELECT * FROM ' . $table . ' WHERE search_term = \'' . esc_sql( $query ) . '\'';
		$row        = $wpdb->get_row( $sql ); // phpcs:ignore
		$search_id  = 0;
		if ( $row ) {
			if ( ! in_array( (int) $row->id, $search_ids, true ) ) {
				$data   = array(
					'search_count'    => $row->search_count + 1,
					'no_result_count' => 1 === $no_result ? $row->no_result_count + 1 : $row->no_result_count,
				);
				$where  = array(
					'id' => $row->id,
				);
				$format = array( '%d', '%d' );
				$wpdb->update( $table, $data, $where, $format ); // db call ok; no-cache ok.
			}
			$search_id = $row->id;
		} else {
			$data   = array(
				'search_term'     => $query,
				'search_count'    => 1,
				'no_result_count' => 1 === $no_result ? 1 : 0,
			);
			$format = array( '%s', '%d', '%d' );
			$wpdb->insert( $table, $data, $format ); // db call ok; no-cache ok.
			$search_id = $wpdb->insert_id;
		}
		$search_ids[] = $search_id;
		setcookie( 'commercekit_search_ids', implode( ',', array_unique( $search_ids ) ), time() + ( 48 * 3600 ), '/' );
	}

	wp_send_json( $ajax );
}

add_action( 'wp_ajax_commercekit_search_counts', 'commercekit_ajax_search_counts' );
add_action( 'wp_ajax_nopriv_commercekit_search_counts', 'commercekit_ajax_search_counts' );

/**
 * Add wishlist endpoint.
 */
function commercekit_add_search_click_count() {
	global $wpdb;
	$nonce             = wp_verify_nonce( 'commercekit_nonce', 'commercekit_settings' );
	$cgkit_search_word = isset( $_GET['cgkit_search_word'] ) ? sanitize_text_field( wp_unslash( $_GET['cgkit_search_word'] ) ) : '';
	if ( $cgkit_search_word ) {
		$table = $wpdb->prefix . 'commercekit_searches';
		$sql   = 'SELECT * FROM ' . $table . ' WHERE search_term = \'' . esc_sql( $cgkit_search_word ) . '\'';
		$row   = $wpdb->get_row( $sql ); // phpcs:ignore
		if ( $row ) {
			$search_ids = isset( $_COOKIE['commercekit_search_ids'] ) && ! empty( $_COOKIE['commercekit_search_ids'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_COOKIE['commercekit_search_ids'] ) ) ) : array();
			$search_ids = array_map( 'intval', $search_ids );
			if ( ! in_array( (int) $row->id, $search_ids, true ) ) {
				$data   = array(
					'click_count' => $row->click_count + 1,
				);
				$where  = array(
					'id' => $row->id,
				);
				$format = array( '%d' );
				$wpdb->update( $table, $data, $where, $format ); // db call ok; no-cache ok.

				$search_ids[] = $row->id;
				setcookie( 'commercekit_search_ids', implode( ',', array_unique( $search_ids ) ), time() + ( 48 * 3600 ), '/' );
			}
		}
	}
}
add_action( 'init', 'commercekit_add_search_click_count' );
require_once dirname( __FILE__ ) . '/module-fast-ajax-search.php';
