<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$option_name = 'hfcm_pro_db_version';
delete_option( $option_name );

$option_name = 'hfcm_pro_activation_date';
delete_option( $option_name );
// For site options in Multisite
delete_site_option( $option_name );

$option_name = 'nnr_hfcm_pro_license_key';
delete_option( $option_name );
// For site options in Multisite
delete_site_option( $option_name );

$option_name = 'nnr_hfcm_pro_license_status';
delete_option( $option_name );
// For site options in Multisite
delete_site_option( $option_name );

// Drop a custom db table
global $wpdb;
$table_name                         = $wpdb->prefix . 'hfcm_pro_scripts';
$nnr_hfcm_pro_tags_table            = $wpdb->prefix . 'hfcm_pro_tags';
$nnr_hfcm_pro_snippet_tag_map_table = $wpdb->prefix . 'hfcm_pro_snippet_tag_map';

$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
$wpdb->query( "DROP TABLE IF EXISTS $nnr_hfcm_pro_tags_table" );
$wpdb->query( "DROP TABLE IF EXISTS $nnr_hfcm_pro_snippet_tag_map_table" );
