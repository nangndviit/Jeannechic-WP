<?php
/**
 * Plugin Name:		Maintenance Mode
 * Plugin URI:		https://helderk.com/
 * Description:		Simple Maintenance Mode for Developers
 * Version:			3.0.2
 * Tested up to:	6.4.3
 * Text Domain:		hkdev-maintenance-mode
 * Domain Path:		/languages/
 * License:			GPLv2 or later
 * Author:			helderk
 * Copyright:
 *  Modifications: helderk 2020-2024, Peter Hardy-vanDoorn 2018
 *  Original: Jack Finch 2010-2012
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//include class
include_once( plugin_dir_path( __FILE__ ) . 'class-hkdev-maintenance-mode.php' );


if (class_exists("HkDevMaintenanceMode")) {
	$hkdev_MM = new HkDevMaintenanceMode();
}

// initialize the admin and users panel
if (!function_exists("hkdev_maintenance_mode_ap")) {
	function hkdev_maintenance_mode_ap() {
		if( current_user_can('manage_options') ) {
			global $hkdev_MM;
			global $ajax_nonce; 
			$ajax_nonce = wp_create_nonce( "hkdev_nonce" ); 
			if( !isset($hkdev_MM) ) return;
			if (function_exists('add_options_page')) {
				add_options_page( 
					__( "Maintenance Mode", 'hkdev-maintenance-mode' ), 
					__( "Maintenance Mode", 'hkdev-maintenance-mode' ), 
					'manage_options', 
					'hkdev_Maintenance_Mode', 
					array( $hkdev_MM, 'print_admin_page' )
				);
			}
		}
	}
}

// initialize codemirror enqueue scripts (hk)
if (!function_exists("hkdev_codemirror_enqueue_scripts")) {
	function hkdev_codemirror_enqueue_scripts($hook_suffix) {
		if ($hook_suffix == 'settings_page_hkdev_Maintenance_Mode') {
			$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/html'));
			wp_localize_script('jquery', 'cm_settings', $cm_settings);
			//wp_enqueue_script('wp-theme-plugin-editor');
			wp_enqueue_style('wp-codemirror');
			wp_enqueue_style('hkdev_select2', plugin_dir_url(__FILE__) . '/assets/select2.min.css', array(), '4.1.0' );
			wp_enqueue_script('hkdev_select2', plugin_dir_url(__FILE__) . '/assets/select2.min.js', array('jquery'), '4.1.0', true );
		}
	}
}

if (!function_exists("hkdev_load_textdomain")) {
	function hkdev_load_textdomain(){
		load_plugin_textdomain('hkdev-maintenance-mode', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}
}


// actions and filters	
if( isset( $hkdev_MM ) ) {
	// actions
	add_action( 'admin_menu',			 'hkdev_maintenance_mode_ap' );
	add_action( 'admin_bar_menu',		 array( $hkdev_MM, 'ab_indicator'), 100 ); //hk
	add_action( 'admin_head',			 array( $hkdev_MM, 'ab_indicator_style' ) ); //hk
	add_action( 'send_headers',			 array( $hkdev_MM, 'process_redirect'), 1 );
	add_action( 'admin_notices',		 array( $hkdev_MM, 'display_status_if_active' ) );
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($hkdev_MM, 'action_links'));
	add_action( 'plugins_loaded', 		 'hkdev_load_textdomain' );
	
	// ajax actions
	add_action( 'wp_ajax_hkdev_mm_getposts',  array( $hkdev_MM, 'get_posts_ajax_callback') ); // wp_ajax_{action}
	add_action( 'wp_ajax_hkdev_mm_toggle_maintenance_mode', array( $hkdev_MM, 'toggle_maintenance_mode') );
	add_action( 'wp_ajax_hkdev_mm_add_ip',    array( $hkdev_MM, 'add_new_ip'       ) );
	add_action( 'wp_ajax_hkdev_mm_toggle_ip', array( $hkdev_MM, 'toggle_ip_status' ) );
	add_action( 'wp_ajax_hkdev_mm_delete_ip', array( $hkdev_MM, 'delete_ip'        ) );
	add_action( 'wp_ajax_hkdev_mm_add_ak',    array( $hkdev_MM, 'add_new_ak'       ) );
	add_action( 'wp_ajax_hkdev_mm_toggle_ak', array( $hkdev_MM, 'toggle_ak_status' ) );
	add_action( 'wp_ajax_hkdev_mm_delete_ak', array( $hkdev_MM, 'delete_ak'        ) );
	add_action( 'wp_ajax_hkdev_mm_resend_ak', array( $hkdev_MM, 'resend_ak'        ) );

	add_action( 'admin_enqueue_scripts', 'hkdev_codemirror_enqueue_scripts' ); //hk
	
	// activation ( deactivation is later enhancement... )
	register_activation_hook( __FILE__, array( $hkdev_MM, 'init' ) );
}

