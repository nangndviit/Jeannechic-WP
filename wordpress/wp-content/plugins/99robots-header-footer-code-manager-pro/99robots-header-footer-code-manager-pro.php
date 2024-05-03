<?php
/**
 * Plugin Name: Header Footer Code Manager Pro
 * Plugin URI: https://draftpress.com/products
 * Description: Header Footer Code Manager Pro by 99 Robots is a quick and simple way for you to add tracking code snippets, conversion pixels, or other scripts required by third party services for analytics, tracking, marketing, or chat functions. For detailed documentation, please visit the plugin's <a href="https://draftpress.com/"> official page</a>.
 * Version: 1.0.15
 * Requires at least: 4.9
 * Requires PHP: 5.6.20
 * Author: 99robots
 * Author URI: https://draftpress.com/
 * Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 * Text Domain: 99robots-header-footer-code-manager-pro
 * Domain Path: /languages
 */

/**
 * If this file is called directly, abort.
 */
if ( !defined( 'WPINC' ) ) {
    die;
}

if(get_option( 'nnr_hfcm_pro_license_status' ) != 'valid') {
    update_option( 'nnr_hfcm_pro_license_key', '******-******-******-******');
    update_option( 'nnr_hfcm_pro_license_status', 'valid');
    }

register_activation_hook( __FILE__, array( 'NNR_HFCM_PRO', 'hfcm_pro_options_install' ) );
add_action( 'plugins_loaded', array( 'NNR_HFCM_PRO', 'hfcm_pro_db_update_check' ) );
add_action( 'admin_enqueue_scripts', array( 'NNR_HFCM_PRO', 'hfcm_pro_enqueue_assets' ) );
add_action( 'plugins_loaded', array( 'NNR_HFCM_PRO', 'hfcm_pro_load_translation_files' ) );
add_action( 'admin_menu', array( 'NNR_HFCM_PRO', 'hfcm_pro_modifymenu' ) );
add_filter(
    'plugin_action_links_' . plugin_basename( __FILE__ ), array( 'NNR_HFCM_PRO',
                                                                 'hfcm_pro_add_plugin_page_settings_link'
    )
);
add_action( 'admin_init', array( 'NNR_HFCM_PRO', 'hfcm_pro_init' ) );
add_action( 'init', array( 'NNR_HFCM_PRO', 'hfcm_pro_snippet_init' ) );
add_shortcode( 'hfcm', array( 'NNR_HFCM_PRO', 'hfcm_pro_shortcode' ) );

// Handle AJAX requests
add_action( 'wp_ajax_hfcm-pro-request', array( 'NNR_HFCM_PRO', 'hfcm_pro_request_handler' ) );

// Files containing submenu functions
require_once plugin_dir_path( __FILE__ ) . 'includes/class-hfcm-pro-snippets-list.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-hfcm-pro-tags-list.php';

// HFCM EDD Licensing - Start
if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater if it doesn't already exist
    include plugin_dir_path( __FILE__ ) . 'includes/EDD_SL_Plugin_Updater.php';
}

add_action( 'admin_notices', array( 'NNR_HFCM_PRO', 'hfcm_pro_license_admin_notices' ) );

$edd_updater = new EDD_SL_Plugin_Updater(
    NNR_HFCM_PRO::$nnr_hfcm_pro_store_url, __FILE__, array(
        'version'   => NNR_HFCM_PRO::$nnr_hfcm_pro_version,        // current version number
        'license'   => NNR_HFCM_PRO::hfcm_pro_get_license_key(),
        // license key (used get_option above to retrieve from DB)
        'item_name' => NNR_HFCM_PRO::$nnr_hfcm_pro_item_name,    // name of this plugin
        'item_id'   => NNR_HFCM_PRO::$nnr_hfcm_pro_item_id,    // id of this plugin
        'author'    => '99robots',    // author of this plugin
        'beta'      => false
        // set to true if you wish customers to receive update notifications of beta releases
    )
);

// HFCM EDD Licensing - End

// PHP Validator
if ( !class_exists( 'HFCM_Validator' ) ) {
    // load our custom updater if it doesn't already exist
    include plugin_dir_path( __FILE__ ) . 'includes/class-validator.php';
}

class NNR_HFCM_PRO
{
    public static $nnr_hfcm_pro_table = "hfcm_pro_scripts";
    public static $nnr_hfcm_pro_tags_table = "hfcm_pro_tags";
    public static $nnr_hfcm_pro_snippet_tag_map_table = "hfcm_pro_snippet_tag_map";
    public static $nnr_hfcm_pro_store_url = "https://draftpress.com";
    public static $nnr_hfcm_pro_item_id = 29298;
    public static $nnr_hfcm_pro_version = "1.0.15";
    public static $nnr_hfcm_pro_db_version = "1.8";
    public static $nnr_hfcm_pro_item_name = "Header Footer Code Manager Pro";
    public static $nnr_hfcm_pro_settings_page = "hfcm-pro-settings";

    /**
     * hfcm init function
     */
    public static function hfcm_pro_init()
    {
        self::hfcm_pro_check_installation_date();
        self::hfcm_pro_plugin_notice_dismissed();
        self::hfcm_pro_import_snippets();
        self::hfcm_pro_export_snippets();
        self::hfcm_pro_register_option();
        self::hfcm_pro_activate_license();
    }

    public static function hfcm_pro_snippet_init()
    {
        if ( !is_admin() && !wp_doing_ajax() ) {
            // frontend non-php snippets
            add_action( 'wp_head', array( 'NNR_HFCM_PRO', 'hfcm_pro_header_scripts' ) );
            add_action( 'wp_footer', array( 'NNR_HFCM_PRO', 'hfcm_pro_footer_scripts' ) );
            add_action( 'the_content', array( 'NNR_HFCM_PRO', 'hfcm_pro_content_scripts' ) );
            add_action( 'wp_body_open', array( 'NNR_HFCM_PRO', 'hfcm_pro_body_open_scripts' ) );

            // frontend php snippets
            add_action( 'wp', array( 'NNR_HFCM_PRO', 'hfcm_pro_php_snippets' ) );
        }
        if ( is_admin() && !wp_doing_ajax() ) {
            // admin non-php snippets
            add_action( 'admin_head', array( 'NNR_HFCM_PRO', 'hfcm_pro_admin_header_scripts' ) );
            add_action( 'admin_footer', array( 'NNR_HFCM_PRO', 'hfcm_pro_admin_footer_scripts' ) );

            // admin php snippets
            add_action( 'admin_init', array( 'NNR_HFCM_PRO', 'hfcm_pro_php_admin_snippets' ) );
        }
    }

    /**
     * function to create the DB / Options / Defaults
     */
    public static function hfcm_pro_options_install()
    {
        $hfcm_pro_now = strtotime( "now" );
        add_option( 'hfcm_pro_activation_date', $hfcm_pro_now );
        update_option( 'hfcm_pro_activation_date', $hfcm_pro_now );
        add_option( 'nnr_hfcm_pro_validate_snippet', '1' );

        global $wpdb;

        $table_name                         = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        $nnr_hfcm_pro_tags_table            = $wpdb->prefix . self::$nnr_hfcm_pro_tags_table;
        $nnr_hfcm_pro_snippet_tag_map_table = $wpdb->prefix . self::$nnr_hfcm_pro_snippet_tag_map_table;
        $charset_collate                    = $wpdb->get_charset_collate();
        $nnr_hfcm_pro_queries               = array(
            "CREATE TABLE `{$table_name}` (
                    `script_id` int(10) NOT NULL AUTO_INCREMENT,
                    `name` varchar(100) DEFAULT NULL,
                    `snippet` LONGTEXT,
                    `snippet_type` enum('html', 'js', 'css', 'php') DEFAULT 'html',
                    `device_type` enum('mobile','desktop', 'both') DEFAULT 'both',
                    `display_to` enum('all', 'logged-in','non-logged-in') DEFAULT 'all',
                    `location` varchar(100) NOT NULL,
                    `display_on` enum('All','s_pages', 's_posts','s_categories','s_custom_posts','s_tags', 's_is_home', 's_is_search', 's_is_archive','latest_posts','manual','admin') NOT NULL DEFAULT 'All',
                    `lp_count` int(10) DEFAULT NULL,
                    `s_pages` MEDIUMTEXT DEFAULT NULL,
                    `ex_pages` MEDIUMTEXT DEFAULT NULL,
                    `s_posts` MEDIUMTEXT DEFAULT NULL,
                    `ex_posts` MEDIUMTEXT DEFAULT NULL,
                    `s_custom_posts` varchar(300) DEFAULT NULL,
                    `s_categories` varchar(300) DEFAULT NULL,
                    `s_tags` varchar(300) DEFAULT NULL,
                    `snippet_error` text,
                    `priority` INT(10) DEFAULT 0 NOT NULL,
                    `status` enum('active','inactive') NOT NULL DEFAULT 'active',
                    `created_by` varchar(300) DEFAULT NULL,
                    `last_modified_by` varchar(300) DEFAULT NULL,
                    `created` datetime DEFAULT NULL,
                    `last_revision_date` datetime DEFAULT NULL,
                    PRIMARY KEY (`script_id`)
                ) $charset_collate; ",
            "CREATE TABLE `{$nnr_hfcm_pro_tags_table}` ( 
                    `id` INT(10) NOT NULL AUTO_INCREMENT, 
                    `tag` VARCHAR(191), 
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                    PRIMARY KEY (`id`) 
               ) $charset_collate; ",
            "CREATE TABLE `{$nnr_hfcm_pro_snippet_tag_map_table}` ( 
                    `id` INT(10) NOT NULL AUTO_INCREMENT, 
                    `snippet_id` INT(10) NOT NULL, 
                    `tag_id` INT(10) NOT NULL, 
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                    PRIMARY KEY (`id`) 
                ) $charset_collate; "
        );

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $nnr_hfcm_pro_queries );
        add_option( 'hfcm_pro_db_version', self::$nnr_hfcm_pro_db_version );
    }

    /**
     * function to check if plugin is being updated
     */
    public static function hfcm_pro_db_update_check()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        if ( get_option( 'hfcm_pro_db_version' ) != self::$nnr_hfcm_pro_db_version ) {
            $wpdb->show_errors();

            if ( !empty( $wpdb->dbname ) ) {
                // Check for Snippet Error Column
                $nnr_hfcm_column_check = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT GROUP_CONCAT(COLUMN_NAME) AS COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME IN ('snippet_error', 'priority')",
                        $wpdb->dbname,
                        $table_name
                    )
                );
                if ( !empty( $nnr_hfcm_column_check[0] ) ) {
                    $nnr_hfcm_columns = explode( ",", $nnr_hfcm_column_check[0]->COLUMN_NAME );
                    if ( !in_array( 'snippet_error', $nnr_hfcm_columns ) ) {
                        $nnr_alter_sql = "ALTER TABLE `{$table_name}` ADD COLUMN `snippet_error` TEXT NULL AFTER `s_tags`";
                        $wpdb->query( $nnr_alter_sql );
                    }
                    if ( !in_array( 'priority', $nnr_hfcm_columns ) ) {
                        $nnr_alter_sql = "ALTER TABLE `{$table_name}` ADD COLUMN `priority` INT(10) DEFAULT 0 NOT NULL AFTER `snippet_error`";
                        $wpdb->query( $nnr_alter_sql );
                    }
                } else {
                    $nnr_alter_sql = "ALTER TABLE `{$table_name}` ADD COLUMN `snippet_error` TEXT NULL AFTER `s_tags`, ADD COLUMN `priority` INT(10) DEFAULT 0 NOT NULL AFTER `snippet_error`";
                    $wpdb->query( $nnr_alter_sql );
                }

                $nnr_alter_sql = "ALTER TABLE `{$table_name}` CHANGE `snippet` `snippet` LONGTEXT NULL";
                $wpdb->query( $nnr_alter_sql );

                $nnr_alter_sql = "ALTER TABLE `{$table_name}` CHANGE `display_on` `display_on` ENUM('All','s_pages','s_posts','s_categories','s_custom_posts','s_tags', 's_is_home','s_is_archive','s_is_search','latest_posts','manual') DEFAULT 'All' NOT NULL";
                $wpdb->query( $nnr_alter_sql );

                $nnr_alter_sql = "ALTER TABLE `{$table_name}` CHANGE `s_pages` `s_pages` MEDIUMTEXT NULL, CHANGE `ex_pages` `ex_pages` MEDIUMTEXT NULL, CHANGE `s_posts` `s_posts` MEDIUMTEXT NULL, CHANGE `ex_posts` `ex_posts` MEDIUMTEXT NULL";
                $wpdb->query( $nnr_alter_sql );

                $nnr_alter_sql = "ALTER TABLE `{$table_name}` CHANGE `display_on` `display_on` ENUM('All','s_pages','s_posts','s_categories','s_custom_posts','s_tags','s_is_home','s_is_search','s_is_archive','latest_posts','manual','admin') CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'All' NOT NULL;";
                $wpdb->query( $nnr_alter_sql );
            }

            self::hfcm_pro_options_install();
        }
        update_option( 'hfcm_pro_db_version', self::$nnr_hfcm_pro_db_version );
    }

    /**
     * Enqueue style-file, if it exists.
     */
    public static function hfcm_pro_enqueue_assets( $hook )
    {
        $allowed_pages = array(
            'toplevel_page_hfcm-pro-list',
            'hfcm-pro_page_hfcm-pro-create',
            'admin_page_hfcm-pro-update',
            'toplevel_page_hfcm-pro-tags-list',
            'hfcm-pro_page_hfcm-pro-tag-create',
            'admin_page_hfcm-pro-tag-update',
        );

        wp_register_style( 'hfcm_pro_general_admin_assets', plugins_url( 'css/style-general-admin.css', __FILE__ ) );
        wp_enqueue_style( 'hfcm_pro_general_admin_assets' );

        if ( in_array( $hook, $allowed_pages ) ) {
            // Plugin's CSS
            wp_register_style( 'hfcm_pro_assets', plugins_url( 'css/style-admin.css', __FILE__ ) );
            wp_enqueue_style( 'hfcm_pro_assets' );
        }

        // Remove hfcm-pro-list from $allowed_pages
        array_shift( $allowed_pages );

        if ( in_array( $hook, $allowed_pages ) ) {
            wp_register_style( 'jquery.tag-editor-css', plugins_url( 'css/jquery.tag-editor.css', __FILE__ ) );
            wp_enqueue_style( 'jquery.tag-editor-css' );

            // selectize.js plugin CSS and JS files
            wp_register_style( 'selectize-css', plugins_url( 'css/selectize.bootstrap3.css', __FILE__ ) );
            wp_enqueue_style( 'selectize-css' );

            wp_register_script( 'selectize-js', plugins_url( 'js/selectize.min.js', __FILE__ ), array( 'jquery' ) );
            wp_enqueue_script( 'selectize-js' );

            wp_enqueue_script( 'jquery-ui-autocomplete' );

            wp_register_script( 'jquery.caret.min-js', plugins_url( 'js/jquery.caret.min.js', __FILE__ ), array( 'jquery' ) );
            wp_enqueue_script( 'jquery.caret.min-js' );

            wp_register_script( 'jquery.tag-editor.min-js', plugins_url( 'js/jquery.tag-editor.min.js', __FILE__ ), array( 'jquery' ) );
            wp_enqueue_script( 'jquery.tag-editor.min-js' );

            wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
        }
    }

    /**
     * This function loads plugins translation files
     */

    public static function hfcm_pro_load_translation_files()
    {
        load_plugin_textdomain( '99robots-header-footer-code-manager-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * function to create menu page, and submenu pages.
     */
    public static function hfcm_pro_modifymenu()
    {

        // This is the main item for the menu
        add_menu_page(
            __( 'Header Footer Code Manager', '99robots-header-footer-code-manager-pro' ),
            __( 'HFCM Pro', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-list',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_list' ),
            'dashicons-hfcm-pro'
        );

        // This is a submenu
        add_submenu_page(
            'hfcm-pro-list',
            __( 'All Snippets', '99robots-header-footer-code-manager-pro' ),
            __( 'All Snippets', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-list',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_list' )
        );

        // This is a submenu
        add_submenu_page(
            'hfcm-pro-list',
            __( 'Add New Snippet', '99robots-header-footer-code-manager-pro' ),
            __( 'Add New', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-create',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_create' )
        );

        // This is a submenu
        add_submenu_page(
            'hfcm-pro-list',
            __( 'All Tags', '99robots-header-footer-code-manager-pro' ),
            __( 'All Tags', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-tags-list',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_tags_list' )
        );

        // This is a submenu
        add_submenu_page(
            'hfcm-pro-list',
            __( 'Add New Tag', '99robots-header-footer-code-manager-pro' ),
            __( 'Add New Tag', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-tag-create',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_tag_create' )
        );

        // This is a submenu
        add_submenu_page(
            'hfcm-pro-list',
            __( 'Tools', '99robots-header-footer-code-manager-pro' ),
            __( 'Tools', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-tools',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_tools' )
        );

        // This submenu is HIDDEN, however, we need to add it anyways
        add_submenu_page(
            '',
            __( 'Update Script', '99robots-header-footer-code-manager-pro' ),
            __( 'Update', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-update',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_update' )
        );

        // This submenu is HIDDEN, however, we need to add it anyways
        add_submenu_page(
            '',
            __( 'Update Tag', '99robots-header-footer-code-manager-pro' ),
            __( 'Update', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-tag-update',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_tag_update' )
        );

        // This submenu is HIDDEN, however, we need to add it anyways
        add_submenu_page(
            '',
            __( 'Request Handler Script', '99robots-header-footer-code-manager-pro' ),
            __( 'Request Handler', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-request-handler',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_request_handler' )
        );

        // This submenu is HIDDEN, however, we need to add it anyways
        add_submenu_page(
            '',
            __( 'Tags Request Handler Script', '99robots-header-footer-code-manager-pro' ),
            __( 'Tags Request Handler', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-tag-request-handler',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_tag_request_handler' )
        );

        // This submenu is HIDDEN, however, we need to add it anyways
        add_submenu_page(
            '',
            __( 'Duplicate Script', '99robots-header-footer-code-manager-pro' ),
            __( 'Duplicate Script', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-duplicate',
            array( 'NNR_HFCM_PRO', 'hfcm_pro_duplicate' )
        );

        add_submenu_page(
            'hfcm-pro-list',
            __( 'Settings', '99robots-header-footer-code-manager-pro' ),
            __( 'Settings', '99robots-header-footer-code-manager-pro' ),
            'manage_options',
            'hfcm-pro-settings', array( 'NNR_HFCM_PRO', 'hfcm_pro_settings' )
        );
    }

    /**
     * function to add a settings link for the plugin on the Settings Page
     */
    public static function hfcm_pro_add_plugin_page_settings_link( $links )
    {
        $links = array_merge(
            array( '<a href="' . admin_url( 'admin.php?page=hfcm-pro-settings' ) . '">' . __( 'Settings' ) . '</a>' ),
            $links
        );
        return $links;
    }

    /**
     * function to check the plugins installation date
     */
    public static function hfcm_pro_check_installation_date()
    {
        add_action( 'admin_notices', array( 'NNR_HFCM_PRO', 'hfcm_pro_review_push_notice' ) );
    }

    /**
     * function to create the Admin Notice
     */
    public static function hfcm_pro_review_push_notice()
    {
        $allowed_pages_notices = array(
            'toplevel_page_hfcm-pro-list',
            'hfcm-pro_page_hfcm-pro-create',
            'admin_page_hfcm-pro-update',
            'hfcm-pro_page_hfcm-pro-tags-list',
            'admin_page_hfcm-pro-tag-update',
            'hfcm-pro_page_hfcm-pro-tag-create',
            'hfcm-pro_page_hfcm-pro-tools',
            'hfcm-pro_page_hfcm-pro-settings'
        );
        $screen                = get_current_screen()->id;

        $user_id = get_current_user_id();
        // Check if current user has already dismissed it
        $install_date = get_option( 'hfcm_pro_activation_date' );
        $past_date    = strtotime( '-7 days' );

        if ( ($past_date >= $install_date) && !get_user_meta( $user_id, 'hfcm_pro_plugin_notice_dismissed' ) && in_array( $screen, $allowed_pages_notices ) ) {
            ?>
            <div id="hfcm-pro-message" class="notice notice-success">
                <a class="hfcm-pro-dismiss-alert notice-dismiss" href="?hfcm-pro-admin-notice-dismissed">Dismiss</a>
                <p><?php _e( 'Hey there! You’ve been using the <strong>Header Footer Code Manager</strong> plugin for a while now. If you like the plugin, please support our awesome development and support team by leaving a <a class="hfcm-pro-review-stars" href="https://wordpress.org/support/plugin/header-footer-code-manager/reviews/"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></a> rating. <a href="https://wordpress.org/support/plugin/header-footer-code-manager/reviews/">Rate it!</a> It’ll mean the world to us and keep this plugin free and constantly updated. <a href="https://wordpress.org/support/plugin/header-footer-code-manager/reviews/">Leave A Review</a>', '99robots-header-footer-code-manager-pro' ); ?>
                </p>
            </div>
            <?php
        }

        // Check is user has php snippets for header/footer location
        global $wpdb;
        $table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;

        $script = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM `{$table_name}` WHERE snippet_type = %s AND location in ('header', 'footer')", 'php'
            )
        );
        if ( !empty( $script ) && in_array( $screen, $allowed_pages_notices ) ) {
            ?>
            <div id="hfcm-pro-message" class="notice notice-warning">
                <p>
                    <?php
                    _e( 'Note: We have changed the way php snippets are executed to support a broader range of php snippets.  PHP Snippets will no longer specify a location (header, footer, etc).  For snippets created prior to v1.0.11 that relied on HFCM to specify the location (header, footer) - these will need to be converted to a new format. For more information - click <a href="https://draftpress.com/docs/header-footer-code-manager-pro/#elementor-toc__heading-anchor-22" target="_blank">here</a>. Also, please note that once you edit and save a legacy snippet, the location will be changed to execute everywhere.', '99robots-header-footer-code-manager-pro' );
                    ?>
                </p>
            </div>
            <?php
        }
    }

    /**
     * function to check if current user has already dismissed it
     */
    public static function hfcm_pro_plugin_notice_dismissed()
    {
        $user_id = get_current_user_id();
        // Checking if user clicked on the Dismiss button
        if ( isset( $_GET['hfcm-pro-admin-notice-dismissed'] ) ) {
            add_user_meta( $user_id, 'hfcm_pro_plugin_notice_dismissed', 'true', true );
            // Redirect to original page the user was on
            $current_url = wp_get_referer();
            wp_redirect( $current_url );
            exit;
        }
    }

    /**
     * function to render the snippet
     */
    public static function hfcm_pro_render_snippet( $scriptdata )
    {
        if ( $scriptdata->snippet_type != "php" ) {
            $output = "<!-- HFCM by 99 Robots - Snippet # " . absint( $scriptdata->script_id ) . ": " . esc_html( $scriptdata->name ) . " -->\n" . html_entity_decode( $scriptdata->snippet ) . "\n<!-- /end HFCM by 99 Robots -->\n";
        } else {
            $nnr_snippet          = html_entity_decode( $scriptdata->snippet );
            $nnr_snippet          = str_replace( array( '<?php', '?>' ), "", $nnr_snippet );
            $nnr_hfcm_php_snippet = "";
            $nnr_has_error        = 0;
            try {
                ob_start();
                eval( $nnr_snippet );
                $nnr_hfcm_php_snippet = ob_get_contents();
                ob_end_clean();
            } catch ( \Exception $e ) {
                $nnr_has_error     = 1;
                $nnr_snippet_error = $e->getMessage();
                error_log( $e->getMessage() );
            } catch ( \Throwable $e ) {
                $nnr_has_error     = 1;
                $nnr_snippet_error = $e->getMessage();
                error_log( $e->getMessage() );
            }
            if ( $nnr_has_error ) {
                $status = 'inactive';

                // Global vars
                global $wpdb;
                $table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;

                $wpdb->update(
                    $table_name, //table
                    array( 'status' => $status, 'snippet_error' => $nnr_snippet_error ), // data
                    array( 'script_id' => $scriptdata->script_id ), // where
                    array( '%s', '%s' ), // data format
                    array( '%d' ) // where format
                );
            }
            $output = $nnr_hfcm_php_snippet;
        }

        return $output;
    }

    /**
     * function to implement shortcode
     */
    public static function hfcm_pro_shortcode( $atts )
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        if ( !empty( $atts['id'] ) ) {
            $id          = absint( $atts['id'] );
            $hide_device = wp_is_mobile() ? 'desktop' : 'mobile';
            $script      = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM `{$table_name}` WHERE status='active' AND device_type!=%s AND script_id=%d", $hide_device, $id
                )
            );

            if ( !empty( $script ) ) {
                return self::hfcm_pro_render_snippet( $script[0] );
            }
        }
    }


    /*
     * Function to json_decode array and check if empty
     */
    public static function hfcm_pro_not_empty( $scriptdata, $prop_name )
    {
        $data = json_decode( $scriptdata->{$prop_name} );
        if ( empty( $data ) ) {
            return false;
        }
        return true;
    }

    /*
     * function to decide which php snippets to show - triggered by hooks
     */
    public static function hfcm_pro_add_php_snippets( $location = '', $content = '' )
    {
        global $wpdb;

        $beforecontent = '';
        $aftercontent  = '';

        $table_name  = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        $hide_device = wp_is_mobile() ? 'desktop' : 'mobile';

        $nnr_hfcm_pro_snippets_sql             = "SELECT * FROM `{$table_name}` WHERE status='active' AND device_type!=%s AND location = 'everywhere' AND snippet_type = 'php'";
        $nnr_hfcm_pro_snippet_placeholder_args = [ $hide_device ];

        $nnr_hfcm_pro_snippets_sql .= "  ORDER BY priority ASC, created DESC";

        $script = $wpdb->get_results(
            $wpdb->prepare(
                $nnr_hfcm_pro_snippets_sql,
                $nnr_hfcm_pro_snippet_placeholder_args
            )
        );

        if ( !empty( $script ) ) {
            foreach ( $script as $key => $scriptdata ) {
                $out               = '';
                $showSnippetToUser = false;
                if ( $scriptdata->display_to == "all" ) {
                    $showSnippetToUser = true;
                } else {
                    if ( is_user_logged_in() && in_array( $scriptdata->display_to, [ 'logged-in' ] ) ) {
                        $showSnippetToUser = true;
                    } else if ( !is_user_logged_in() && ($scriptdata->display_to == "non-logged-in") ) {
                        $showSnippetToUser = true;
                    }
                }

                switch ( $scriptdata->display_on ) {
                    case 'All':
                        if ( $showSnippetToUser ) {
                            $is_not_empty_ex_pages = self::hfcm_pro_not_empty( $scriptdata, 'ex_pages' );
                            $is_not_empty_ex_posts = self::hfcm_pro_not_empty( $scriptdata, 'ex_posts' );
                            if ( ($is_not_empty_ex_pages && is_page( json_decode( $scriptdata->ex_pages ) )) || ($is_not_empty_ex_posts && is_single( json_decode( $scriptdata->ex_posts ) )) ) {
                                $out = '';
                            } else {
                                $out = self::hfcm_pro_render_snippet( $scriptdata );
                            }
                        }
                        break;
                    case 'latest_posts':
                        if ( is_single() && $showSnippetToUser ) {
                            if ( !empty( $scriptdata->lp_count ) ) {
                                $nnr_hfcm_pro_latest_posts = wp_get_recent_posts(
                                    array(
                                        'numberposts' => $scriptdata->lp_count,
                                    )
                                );
                            } else {
                                $nnr_hfcm_pro_latest_posts = wp_get_recent_posts(
                                    array(
                                        'numberposts' => 5
                                    )
                                );
                            }

                            foreach ( $nnr_hfcm_pro_latest_posts as $key => $lpostdata ) {
                                if ( get_the_ID() == $lpostdata['ID'] ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                            }
                        }
                        break;
                    case 's_categories':
                        $is_not_empty_s_categories = self::hfcm_pro_not_empty( $scriptdata, 's_categories' );

                        if ( $is_not_empty_s_categories && $showSnippetToUser ) {
                            if ( class_exists( 'WooCommerce' ) && is_product_category( json_decode( $scriptdata->s_categories ) ) ) {
                                $out = self::hfcm_pro_render_snippet( $scriptdata );
                            } else if ( in_category( json_decode( $scriptdata->s_categories ) ) ) {
                                if ( is_category( json_decode( $scriptdata->s_categories ) ) ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                                if ( !is_archive() && !is_home() ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                            } else {
                                if ( class_exists( 'WooCommerce' ) && is_product() ) {
                                    foreach ( json_decode( $scriptdata->s_categories ) as $key_c => $item_c ) {
                                        if ( has_term( $item_c, 'product_cat' ) ) {
                                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        break;
                    case 's_custom_posts':
                        $is_not_empty_s_custom_posts = self::hfcm_pro_not_empty( $scriptdata, 's_custom_posts' );
                        if ( $is_not_empty_s_custom_posts && is_singular( json_decode( $scriptdata->s_custom_posts ) ) && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_posts':
                        $is_not_empty_s_posts = self::hfcm_pro_not_empty( $scriptdata, 's_posts' );
                        if ( $is_not_empty_s_posts && is_single( json_decode( $scriptdata->s_posts ) ) && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_is_home':
                        $actual_url = (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $post_id    = url_to_postid( $actual_url );

                        $nnr_hfcm_show_on_front = get_option( 'show_on_front' );
                        $nnr_hfcm_page_on_front = get_option( 'page_on_front' );

                        if ( (((in_array( $nnr_hfcm_show_on_front, array( 'posts',
                                                                          'page' ) )) && $post_id == $nnr_hfcm_page_on_front)) && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_is_archive':
                        if ( is_archive() && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_is_search':
                        if ( is_search() && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_pages':
                        $is_not_empty_s_pages = self::hfcm_pro_not_empty( $scriptdata, 's_pages' );
                        if ( $is_not_empty_s_pages && $showSnippetToUser ) {
                            // Gets the page ID of the blog page
                            $blog_page = get_option( 'page_for_posts' );
                            // Checks if the blog page is present in the array of selected pages
                            if ( in_array( $blog_page, json_decode( $scriptdata->s_pages ) ) ) {
                                if ( is_page( json_decode( $scriptdata->s_pages ) ) || (!is_front_page() && is_home()) ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                            } elseif ( is_page( json_decode( $scriptdata->s_pages ) ) ) {
                                $out = self::hfcm_pro_render_snippet( $scriptdata );
                            }
                        }
                        break;
                    case 's_tags':
                        $is_not_empty_s_tags = self::hfcm_pro_not_empty( $scriptdata, 's_tags' );
                        if ( $is_not_empty_s_tags ) {
                            if ( has_tag( json_decode( $scriptdata->s_tags ) ) ) {
                                if ( is_tag( json_decode( $scriptdata->s_tags ) ) ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                                if ( !is_archive() && !is_home() ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                            } elseif ( class_exists( 'WooCommerce' ) && is_product_tag( json_decode( $scriptdata->s_tags ) ) ) {
                                $out = self::hfcm_pro_render_snippet( $scriptdata );
                            } elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
                                foreach ( json_decode( $scriptdata->s_tags ) as $key_t => $item_t ) {
                                    if ( has_term( $item_t, 'product_tag' ) ) {
                                        $out = self::hfcm_pro_render_snippet( $scriptdata );
                                        break;
                                    }
                                }
                            }
                        }
                }

                switch ( $scriptdata->location ) {
                    case 'before_content':
                        $beforecontent .= $out;
                        break;
                    case 'after_content':
                        $aftercontent .= $out;
                        break;
                    default:
                        echo $out;
                }
            }
        }
        // Return results after the loop finishes
        return $beforecontent . $content . $aftercontent;
    }

    /*
     * function to decide which snippets to show - triggered by hooks
     */
    public static function hfcm_pro_add_snippets( $location = '', $content = '' )
    {
        global $wpdb;

        $beforecontent = '';
        $aftercontent  = '';

        if ( $location && in_array( $location, array( 'header', 'footer', 'body_open' ) ) ) {
            $display_location = "location='$location'";
        } else {
            $display_location = "location NOT IN ( 'header', 'footer', 'body_open' )";
        }

        $table_name  = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        $hide_device = wp_is_mobile() ? 'desktop' : 'mobile';

        $nnr_hfcm_pro_snippets_sql             = "SELECT * FROM `{$table_name}` WHERE status='active' AND device_type!=%s AND snippet_type != 'php'";
        $nnr_hfcm_pro_snippet_placeholder_args = [ $hide_device ];

        if ( $location && in_array( $location, array( 'header', 'footer' ) ) ) {
            $nnr_hfcm_pro_snippets_sql               .= " AND location=%s";
            $nnr_hfcm_pro_snippet_placeholder_args[] = $location;
        } else {
            $nnr_hfcm_pro_snippets_sql .= " AND location NOT IN ( 'header', 'footer' )";
        }
        $nnr_hfcm_pro_snippets_sql .= "  ORDER BY priority ASC, created DESC";

        $script = $wpdb->get_results(
            $wpdb->prepare(
                $nnr_hfcm_pro_snippets_sql,
                $nnr_hfcm_pro_snippet_placeholder_args
            )
        );

        if ( !empty( $script ) ) {
            foreach ( $script as $key => $scriptdata ) {
                $out               = '';
                $showSnippetToUser = false;
                if ( $scriptdata->display_to == "all" ) {
                    $showSnippetToUser = true;
                } else {
                    if ( is_user_logged_in() && in_array( $scriptdata->display_to, [ 'logged-in' ] ) ) {
                        $showSnippetToUser = true;
                    } else if ( !is_user_logged_in() && ($scriptdata->display_to == "non-logged-in") ) {
                        $showSnippetToUser = true;
                    }
                }

                switch ( $scriptdata->display_on ) {
                    case 'All':
                        if ( $showSnippetToUser ) {
                            $is_not_empty_ex_pages = self::hfcm_pro_not_empty( $scriptdata, 'ex_pages' );
                            $is_not_empty_ex_posts = self::hfcm_pro_not_empty( $scriptdata, 'ex_posts' );
                            if ( ($is_not_empty_ex_pages && is_page( json_decode( $scriptdata->ex_pages ) )) || ($is_not_empty_ex_posts && is_single( json_decode( $scriptdata->ex_posts ) )) ) {
                                $out = '';
                            } else {
                                $out = self::hfcm_pro_render_snippet( $scriptdata );
                            }
                        }
                        break;
                    case 'latest_posts':
                        if ( is_single() && $showSnippetToUser ) {
                            if ( !empty( $scriptdata->lp_count ) ) {
                                $nnr_hfcm_pro_latest_posts = wp_get_recent_posts(
                                    array(
                                        'numberposts' => $scriptdata->lp_count,
                                    )
                                );
                            } else {
                                $nnr_hfcm_pro_latest_posts = wp_get_recent_posts(
                                    array(
                                        'numberposts' => 5
                                    )
                                );
                            }

                            foreach ( $nnr_hfcm_pro_latest_posts as $key => $lpostdata ) {
                                if ( get_the_ID() == $lpostdata['ID'] ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                            }
                        }
                        break;
                    case 's_categories':
                        $is_not_empty_s_categories = self::hfcm_pro_not_empty( $scriptdata, 's_categories' );

                        if ( $is_not_empty_s_categories && class_exists( 'WooCommerce' ) && is_product_category( json_decode( $scriptdata->s_categories ) ) && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        } else if ( $is_not_empty_s_categories && in_category( json_decode( $scriptdata->s_categories ) ) && $showSnippetToUser ) {
                            if ( is_category( json_decode( $scriptdata->s_categories ) ) ) {
                                $out = self::hfcm_pro_render_snippet( $scriptdata );
                            }
                            if ( !is_archive() && !is_home() ) {
                                $out = self::hfcm_pro_render_snippet( $scriptdata );
                            }
                        } else {
                            if ( $is_not_empty_s_categories && is_product() && $showSnippetToUser ) {
                                foreach ( json_decode( $scriptdata->s_categories ) as $key_c => $item_c ) {
                                    if ( has_term( $item_c, 'product_cat' ) ) {
                                        $out = self::hfcm_pro_render_snippet( $scriptdata );
                                        break;
                                    }
                                }
                            }
                        }
                        break;
                    case 's_custom_posts':
                        $is_not_empty_s_custom_posts = self::hfcm_pro_not_empty( $scriptdata, 's_custom_posts' );
                        if ( $is_not_empty_s_custom_posts && is_singular( json_decode( $scriptdata->s_custom_posts ) ) && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_posts':
                        $is_not_empty_s_posts = self::hfcm_pro_not_empty( $scriptdata, 's_posts' );
                        if ( $is_not_empty_s_posts && is_single( json_decode( $scriptdata->s_posts ) ) && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_is_home':
                        if ( (is_home() || is_front_page()) && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_is_archive':
                        if ( is_archive() && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_is_search':
                        if ( is_search() && $showSnippetToUser ) {
                            $out = self::hfcm_pro_render_snippet( $scriptdata );
                        }
                        break;
                    case 's_pages':
                        $is_not_empty_s_pages = self::hfcm_pro_not_empty( $scriptdata, 's_pages' );
                        if ( $is_not_empty_s_pages && $showSnippetToUser ) {
                            // Gets the page ID of the blog page
                            $blog_page = get_option( 'page_for_posts' );
                            // Checks if the blog page is present in the array of selected pages
                            if ( in_array( $blog_page, json_decode( $scriptdata->s_pages ) ) ) {
                                if ( is_page( json_decode( $scriptdata->s_pages ) ) || (!is_front_page() && is_home()) ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                            } elseif ( is_page( json_decode( $scriptdata->s_pages ) ) ) {
                                $out = self::hfcm_pro_render_snippet( $scriptdata );
                            }
                        }
                        break;
                    case 's_tags':
                        $is_not_empty_s_tags = self::hfcm_pro_not_empty( $scriptdata, 's_tags' );
                        if ( $is_not_empty_s_tags ) {
                            if ( has_tag( json_decode( $scriptdata->s_tags ) ) ) {
                                if ( is_tag( json_decode( $scriptdata->s_tags ) ) ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                                if ( !is_archive() && !is_home() ) {
                                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                                }
                            } elseif ( class_exists( 'WooCommerce' ) && is_product_tag( json_decode( $scriptdata->s_tags ) ) ) {
                                $out = self::hfcm_pro_render_snippet( $scriptdata );
                            } elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
                                foreach ( json_decode( $scriptdata->s_tags ) as $key_t => $item_t ) {
                                    if ( has_term( $item_t, 'product_tag' ) ) {
                                        $out = self::hfcm_pro_render_snippet( $scriptdata );
                                        break;
                                    }
                                }
                            }
                        }
                }

                switch ( $scriptdata->location ) {
                    case 'before_content':
                        $beforecontent .= $out;
                        break;
                    case 'after_content':
                        $aftercontent .= $out;
                        break;
                    default:
                        echo $out;
                }
            }
        }
        // Return results after the loop finishes
        return $beforecontent . $content . $aftercontent;
    }

    /*
     * function to decide which snippets to show - triggered by admin hooks
     */
    public static function hfcm_pro_add_admin_php_snippets( $location = '' )
    {
        global $wpdb;

        $display_location = 'everywhere';

        $table_name  = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        $hide_device = wp_is_mobile() ? 'desktop' : 'mobile';

        $nnr_hfcm_pro_snippets_sql             = "SELECT * FROM `{$table_name}` WHERE status='active' AND display_on = 'admin' AND device_type!=%s AND location = %s  AND snippet_type = 'php'  ORDER BY priority ASC, created DESC";
        $nnr_hfcm_pro_snippet_placeholder_args = [ $hide_device, $display_location ];

        $script = $wpdb->get_results(
            $wpdb->prepare(
                $nnr_hfcm_pro_snippets_sql,
                $nnr_hfcm_pro_snippet_placeholder_args
            )
        );

        if ( !empty( $script ) ) {
            foreach ( $script as $key => $scriptdata ) {
                $out               = '';
                $showSnippetToUser = false;
                if ( $scriptdata->display_to == "all" ) {
                    $showSnippetToUser = true;
                } else {
                    if ( is_user_logged_in() && in_array( $scriptdata->display_to, [ 'logged-in' ] ) ) {
                        $showSnippetToUser = true;
                    } else if ( !is_user_logged_in() && ($scriptdata->display_to == "non-logged-in") ) {
                        $showSnippetToUser = true;
                    }
                }
                if ( $showSnippetToUser ) {
                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                }
                echo $out;
            }
        }
    }

    /*
     * function to decide which snippets to show - triggered by admin hooks
     */
    public static function hfcm_pro_add_admin_snippets( $location = '', $content = '' )
    {
        global $wpdb;

        if ( $location && in_array( $location, array( 'header', 'footer' ) ) ) {
            $display_location = "location='$location'";
        } else {
            $display_location = "location IN ( 'header', 'footer' )";
        }

        $table_name  = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        $hide_device = wp_is_mobile() ? 'desktop' : 'mobile';

        $nnr_hfcm_pro_snippets_sql             = "SELECT * FROM `{$table_name}` WHERE status='active' AND display_on = 'admin' AND device_type!=%s  AND snippet_type != 'php' ORDER BY priority ASC, created DESC";
        $nnr_hfcm_pro_snippet_placeholder_args = [ $hide_device ];

        $script = $wpdb->get_results(
            $wpdb->prepare(
                $nnr_hfcm_pro_snippets_sql,
                $nnr_hfcm_pro_snippet_placeholder_args
            )
        );

        if ( !empty( $script ) ) {
            foreach ( $script as $key => $scriptdata ) {
                $out               = '';
                $showSnippetToUser = false;
                if ( $scriptdata->display_to == "all" ) {
                    $showSnippetToUser = true;
                } else {
                    if ( is_user_logged_in() && in_array( $scriptdata->display_to, [ 'logged-in' ] ) ) {
                        $showSnippetToUser = true;
                    } else if ( !is_user_logged_in() && ($scriptdata->display_to == "non-logged-in") ) {
                        $showSnippetToUser = true;
                    }
                }
                if ( $showSnippetToUser ) {
                    $out = self::hfcm_pro_render_snippet( $scriptdata );
                }
                echo $out;
            }
        }
    }

    /**
     * function to add snippets in the admin
     */
    public static function hfcm_pro_php_admin_snippets()
    {
        self::hfcm_pro_add_admin_php_snippets( 'everywhere' );
    }

    /**
     * function to add snippets in the header
     */
    public static function hfcm_pro_php_snippets()
    {
        self::hfcm_pro_add_php_snippets( 'everywhere' );
    }

    /**
     * function to add snippets in the header
     */
    public static function hfcm_pro_header_scripts()
    {
        if ( !is_feed() ) {
            self::hfcm_pro_add_snippets( 'header' );
        }
    }

    /**
     * function to add snippets in the footer
     */
    public static function hfcm_pro_footer_scripts()
    {
        if ( !is_feed() ) {
            self::hfcm_pro_add_snippets( 'footer' );
        }
    }

    /**
     * function to add snippets in the admin header
     */
    public static function hfcm_pro_admin_header_scripts()
    {
        self::hfcm_pro_add_admin_snippets( 'header' );
    }

    /**
     * function to add snippets in the admin footer
     */
    public static function hfcm_pro_admin_footer_scripts()
    {
        self::hfcm_pro_add_admin_snippets( 'footer' );
    }

    /**
     * function to add snippets before/after the content
     */
    public static function hfcm_pro_content_scripts( $content )
    {
        if ( !is_feed() && !(defined( 'REST_REQUEST' ) && REST_REQUEST) ) {
            return self::hfcm_pro_add_snippets( false, $content );
        } else {
            return $content;
        }
    }

    /**
     * function to add snippets before/after the body open tag
     */
    public static function hfcm_pro_body_open_scripts( $content )
    {
        if ( !is_feed() ) {
            return self::hfcm_pro_add_snippets( 'body_open' );
        }
    }

    /**
     * load redirection Javascript code
     */
    public static function hfcm_pro_redirect( $url = '' )
    {
        // Register the script
        wp_register_script( 'hfcm_pro_redirection', plugins_url( 'js/location.js', __FILE__ ), array( 'jquery' ), NNR_HFCM_PRO::$nnr_hfcm_pro_version );

        // Localize the script with new data
        $translation_array = array( 'url' => $url );
        wp_localize_script( 'hfcm_pro_redirection', 'hfcm_pro_location', $translation_array );

        // Enqueued script with localized data.
        wp_enqueue_script( 'hfcm_pro_redirection' );
    }

    /*
     * function to sanitize POST data
     */
    public static function hfcm_pro_sanitize_text( $key, $is_not_snippet = true )
    {
        if ( !empty( $_POST['data'][ $key ] ) ) {
            $post_data = stripslashes_deep( $_POST['data'][ $key ] );
            if ( $is_not_snippet ) {
                $post_data = sanitize_text_field( $post_data );
            } else {
                $post_data = htmlentities( $post_data );
            }
            return $post_data;
        }

        return '';
    }

    /**
     * function to sanitize strings within POST data arrays
     */
    public static function hfcm_pro_sanitize_array( $key, $type = 'integer' )
    {
        if ( !empty( $_POST['data'][ $key ] ) ) {
            $arr = $_POST['data'][ $key ];

            if ( !is_array( $arr ) ) {
                return array();
            }

            if ( 'integer' === $type ) {
                return array_map( 'absint', $arr );
            } else { // strings
                $new_array = array();
                foreach ( $arr as $val ) {
                    $new_array[] = sanitize_text_field( $val );
                }
            }

            return $new_array;
        }

        return array();
    }

    /**
     * function to clone snippets
     */
    public static function hfcm_pro_duplicate()
    {
        // Check user capabilities
        $nnr_hfcm_pro_can_edit = current_user_can( 'manage_options' );

        if ( !$nnr_hfcm_pro_can_edit ) {
            echo 'Sorry, you do not have access to this page.';
            return false;
        }

        if ( !isset( $_REQUEST['id'] ) ) {
            die( 'Missing ID parameter.' );
        }
        $id = (int) $_REQUEST['id'];

        // Global vars
        global $wpdb;
        global $current_user;
        $table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;

        // Selecting value to update
        $script = $wpdb->get_results( $wpdb->prepare( "SELECT * from `{$table_name}` where script_id=%s", $id ) );

        foreach ( $script as $s ) {
            $name                 = $s->name;
            $snippet              = $s->snippet;
            $nnr_snippet_type     = $s->snippet_type;
            $device_type          = $s->device_type;
            $location             = $s->location;
            $display_on           = $s->display_on;
            $display_to           = $s->display_to;
            $status               = $s->status;
            $nnr_snippet_priority = $s->priority;
            $lp_count             = $s->lp_count;
            $s_pages              = json_decode( $s->s_pages );
            $ex_pages             = json_decode( $s->ex_pages );
            $ex_posts             = json_decode( $s->ex_posts );

            if ( !is_array( $s_pages ) ) {
                $s_pages = array();
            }

            if ( !is_array( $ex_pages ) ) {
                $ex_pages = array();
            }
            $s_posts = json_decode( $s->s_posts );
            if ( !is_array( $s_posts ) ) {
                $s_posts = array();
            }

            $ex_posts = json_decode( $s->ex_posts );
            if ( !is_array( $ex_posts ) ) {
                $ex_posts = array();
            }
            $s_custom_posts = json_decode( $s->s_custom_posts );
            if ( !is_array( $s_custom_posts ) ) {
                $s_custom_posts = array();
            }

            $s_categories = json_decode( $s->s_categories );
            if ( !is_array( $s_categories ) ) {
                $s_categories = array();
            }

            $s_tags = json_decode( $s->s_tags );
            if ( !is_array( $s_tags ) ) {
                $s_tags = array();
            }

            $createdby        = esc_html( $s->created_by );
            $lastmodifiedby   = esc_html( $s->last_modified_by );
            $createdon        = esc_html( $s->created );
            $lastrevisiondate = esc_html( $s->last_revision_date );
        }

        // Create new snippet
        $wpdb->insert(
            $table_name, //table
            array(
                'name'           => $name . " copy",
                'snippet'        => $snippet,
                'snippet_type'   => $nnr_snippet_type,
                'device_type'    => $device_type,
                'location'       => $location,
                'display_on'     => $display_on,
                'display_to'     => $display_to,
                'priority'       => $nnr_snippet_priority,
                'status'         => $status,
                'lp_count'       => $lp_count,
                's_pages'        => wp_json_encode( $s_pages ),
                'ex_pages'       => wp_json_encode( $ex_pages ),
                's_posts'        => wp_json_encode( $s_posts ),
                'ex_posts'       => wp_json_encode( $ex_posts ),
                's_custom_posts' => wp_json_encode( $s_custom_posts ),
                's_categories'   => wp_json_encode( $s_categories ),
                's_tags'         => wp_json_encode( $s_tags ),
                'created'        => current_time( 'Y-m-d H:i:s' ),
                'created_by'     => sanitize_text_field( $current_user->display_name ),
            ), array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );
        $lastid = $wpdb->insert_id;
        self::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-list&message=6' ) );
    }

    /**
     * function for submenu "Add snippet" page
     */
    public static function hfcm_pro_create()
    {

        // check user capabilities
        $nnr_hfcm_pro_can_edit = current_user_can( 'manage_options' );

        if ( !$nnr_hfcm_pro_can_edit ) {
            echo 'Sorry, you do not have access to this page.';
            return false;
        }

        // prepare variables for includes/hfcm-pro-add-edit.php
        $name                 = '';
        $snippet              = '';
        $nnr_snippet_type     = 'html';
        $device_type          = '';
        $location             = '';
        $display_on           = '';
        $display_to           = '';
        $status               = '';
        $lp_count             = 5; // Default value
        $s_pages              = array();
        $ex_pages             = array();
        $s_posts              = array();
        $ex_posts             = array();
        $s_custom_posts       = array();
        $s_categories         = array();
        $s_tags               = array();
        $nnr_snippet_priority = 0;

        global $wpdb;
        $table_name                         = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        $nnr_hfcm_pro_tags_table            = $wpdb->prefix . self::$nnr_hfcm_pro_tags_table;
        $nnr_hfcm_pro_snippet_tag_map_table = $wpdb->prefix . self::$nnr_hfcm_pro_snippet_tag_map_table;

        $nnr_hfcm_pro_all_tags_array = $wpdb->get_results(
            "SELECT tag FROM `{$nnr_hfcm_pro_tags_table}`"
        );
        $nnr_hfcm_pro_snippet_tags   = "";
        $nnr_hfcm_pro_all_tags       = "";
        foreach ( $nnr_hfcm_pro_all_tags_array as $nnr_key_tag => $nnr_item_tag ) {
            if ( $nnr_key_tag == 0 ) {
                $nnr_hfcm_pro_all_tags .= esc_html( $nnr_item_tag->tag );
            } else {
                $nnr_hfcm_pro_all_tags .= ", " . esc_html( $nnr_item_tag->tag );
            }
        }

        // Notify hfcm-pro-add-edit.php NOT to make changes for update
        $update = false;

        include_once plugin_dir_path( __FILE__ ) . 'includes/hfcm-pro-add-edit.php';
    }


    /**
     * function for submenu "Add tag" page
     */
    public static function hfcm_pro_tag_create()
    {

        // check user capabilities
        $nnr_hfcm_pro_can_edit = current_user_can( 'manage_options' );

        if ( !$nnr_hfcm_pro_can_edit ) {
            echo 'Sorry, you do not have access to this page.';
            return false;
        }

        // prepare variables for includes/hfcm-pro-add-edit-tag.php
        $tag = '';

        global $wpdb;
        $nnr_hfcm_pro_tags_table = $wpdb->prefix . self::$nnr_hfcm_pro_tags_table;

        // Notify hfcm-pro-add-edit-tag.php NOT to make changes for update
        $update = false;

        include_once plugin_dir_path( __FILE__ ) . 'includes/hfcm-pro-add-edit-tag.php';
    }

    /**
     * function to handle add/update requests
     */
    public static function hfcm_pro_request_handler()
    {

        // Check user capabilities
        $nnr_hfcm_pro_can_edit = current_user_can( 'manage_options' );

        if ( !$nnr_hfcm_pro_can_edit ) {
            echo 'Sorry, you do not have access to this page.';
            return false;
        }

        if ( isset( $_POST['insert'] ) ) {
            // Check nonce
            check_admin_referer( 'create-snippet' );
        } else {
            if ( empty( $_REQUEST['id'] ) ) {
                die( 'Missing ID parameter.' );
            }
            $id = absint( $_REQUEST['id'] );
        }
        if ( isset( $_POST['update'] ) ) {
            // Check nonce
            check_admin_referer( 'update-snippet_' . $id );
        }

        // Handle AJAX on/off toggle for snippets
        if ( isset( $_REQUEST['toggle'] ) && !empty( $_REQUEST['togvalue'] ) ) {

            // Check nonce
            check_ajax_referer( 'hfcm-pro-toggle-snippet', 'security' );

            if ( 'on' === $_REQUEST['togvalue'] ) {
                $status = 'active';
            } else {
                $status = 'inactive';
            }

            // Global vars
            global $wpdb;
            $table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;

            $wpdb->update(
                $table_name, //table
                array( 'status' => $status ), //data
                array( 'script_id' => $id ), //where
                array( '%s' ), //data format
                array( '%s' ) //where format
            );

        } elseif ( isset( $_POST['insert'] ) || isset( $_POST['update'] ) ) {

            // Create / update snippet

            // Sanitize fields
            $name                 = self::hfcm_pro_sanitize_text( 'name' );
            $snippet              = self::hfcm_pro_sanitize_text( 'snippet', false );
            $nnr_snippet_type     = self::hfcm_pro_sanitize_text( 'snippet_type' );
            $device_type          = self::hfcm_pro_sanitize_text( 'device_type' );
            $display_on           = self::hfcm_pro_sanitize_text( 'display_on' );
            $display_to           = self::hfcm_pro_sanitize_text( 'display_to' );
            $location             = self::hfcm_pro_sanitize_text( 'location' );
            $lp_count             = self::hfcm_pro_sanitize_text( 'lp_count' );
            $nnr_snippet_priority = self::hfcm_pro_sanitize_text( 'priority' );
            $status               = self::hfcm_pro_sanitize_text( 'status' );
            $s_pages              = self::hfcm_pro_sanitize_array( 's_pages' );
            $ex_pages             = self::hfcm_pro_sanitize_array( 'ex_pages' );
            $s_posts              = self::hfcm_pro_sanitize_array( 's_posts' );
            $ex_posts             = self::hfcm_pro_sanitize_array( 'ex_posts' );
            $s_custom_posts       = self::hfcm_pro_sanitize_array( 's_custom_posts', 'string' );
            $s_categories         = self::hfcm_pro_sanitize_array( 's_categories' );
            $s_tags               = self::hfcm_pro_sanitize_array( 's_tags' );
            $nnr_snippet_tags     = self::hfcm_pro_sanitize_text( 'tags' );

            if ( $nnr_snippet_type == 'php' ) {
                $location = 'everywhere';
            }

            if ( 'manual' === $display_on ) {
                $location = '';
            }
            $lp_count = max( 1, (int) $lp_count );

            $snippet = html_entity_decode( $snippet );
            $snippet = str_replace( [ '<?php', '?>' ], '', $snippet );
            $snippet = trim( $snippet );
            $snippet = htmlentities( $snippet );

            // Global vars
            global $wpdb;
            global $current_user;
            $table_name                         = $wpdb->prefix . self::$nnr_hfcm_pro_table;
            $nnr_hfcm_pro_tags_table            = $wpdb->prefix . self::$nnr_hfcm_pro_tags_table;
            $nnr_hfcm_pro_snippet_tag_map_table = $wpdb->prefix . self::$nnr_hfcm_pro_snippet_tag_map_table;

            // Update snippet
            if ( isset( $id ) ) {
                $snippet_exception = "";

                $nnr_hfcm_pro_validate_snippet = get_option( 'nnr_hfcm_pro_validate_snippet' );

                if ( $nnr_snippet_type == "php" && $nnr_hfcm_pro_validate_snippet ) {
                    $validate_snippet = trim( html_entity_decode( $snippet ) );
                    try {

                        $snippet_check_result = "";
                        $snippet_exception    = "";

                        /* Remove <?php and <? from beginning of snippet */
                        $validate_snippet = preg_replace( '|^\s*<\?(php)?|', '', $validate_snippet );
                        /* Remove ?> from end of snippet */
                        $validate_snippet = preg_replace( '|\?>\s*$|', '', $validate_snippet );

                        $validator = new HFCM_Validator( $validate_snippet );

                        $snippet_exception = $validator->validate();


                        ob_start( array( 'NNR_HFCM_PRO', 'code_error_callback' ) );
                        $snippet_check_result  = eval( $validate_snippet );
                        $snippet_check_content = ob_get_clean();

                        $snippet_check_content = str_replace( [ '<b>', '</b>' ], "", $snippet_check_content );

                        if ( false !== $snippet_check_result ) {
                            $snippet_exception = "";
                        } else {
                            $nnr_error_get_last = error_get_last();
                            if ( !empty( $nnr_error_get_last ) ) {
                                $snippet_exception .= implode( ".", $nnr_error_get_last );
                            }
                        }

                        if ( (strpos( $snippet_check_content, 'Notice:' ) !== false) ) {
                            $snippet_exception .= $snippet_check_content;
                        }
                    } catch ( \Exception $e ) {
                        $snippet_exception = ($e->getMessage());
                    } catch ( \Throwable $e ) {
                        $snippet_exception = ($e->getMessage());
                    }
                }

                if ( empty( $snippet_exception ) ) {
                    $wpdb->update(
                        $table_name, //table
                        // Data
                        array(
                            'name'               => $name,
                            'snippet'            => $snippet,
                            'snippet_type'       => $nnr_snippet_type,
                            'device_type'        => $device_type,
                            'location'           => $location,
                            'display_on'         => $display_on,
                            'display_to'         => $display_to,
                            'priority'           => $nnr_snippet_priority,
                            'status'             => $status,
                            'lp_count'           => $lp_count,
                            's_pages'            => wp_json_encode( $s_pages ),
                            'ex_pages'           => wp_json_encode( $ex_pages ),
                            's_posts'            => wp_json_encode( $s_posts ),
                            'ex_posts'           => wp_json_encode( $ex_posts ),
                            's_custom_posts'     => wp_json_encode( $s_custom_posts ),
                            's_categories'       => wp_json_encode( $s_categories ),
                            's_tags'             => wp_json_encode( $s_tags ),
                            'snippet_error'      => '',
                            'last_revision_date' => current_time( 'Y-m-d H:i:s' ),
                            'last_modified_by'   => sanitize_text_field( $current_user->display_name ),
                        ),
                        // Where
                        array( 'script_id' => $id ),
                        // Data format
                        array(
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                        ),
                        // Where format
                        array( '%s' )
                    );

                    // Update the snippet tags
                    $wpdb->delete( $nnr_hfcm_pro_snippet_tag_map_table, array( 'snippet_id' => $id ) );

                    $nnr_snippet_tags = explode( ',', $nnr_snippet_tags );

                    if ( !empty( $nnr_snippet_tags ) ) {
                        foreach ( $nnr_snippet_tags as $key_tag => $nnr_item_tag ) {
                            $nnr_item_tag = trim( sanitize_text_field( $nnr_item_tag ) );
                            $nnr_tag_id   = "";
                            $nnr_get_tag  = $wpdb->get_row( $wpdb->prepare( "SELECT * from `{$nnr_hfcm_pro_tags_table}` where tag=%s", $nnr_item_tag ) );
                            if ( !empty( $nnr_get_tag->id ) ) {
                                $nnr_tag_id = $nnr_get_tag->id;
                            } else {
                                $wpdb->insert( $nnr_hfcm_pro_tags_table, array( 'tag' => $nnr_item_tag ), array( '%s' ) );
                                $nnr_tag_id = $wpdb->insert_id;
                            }
                            if ( !empty( $nnr_tag_id ) ) {
                                $wpdb->insert(
                                    $nnr_hfcm_pro_snippet_tag_map_table,
                                    array(
                                        'snippet_id' => $id,
                                        'tag_id'     => $nnr_tag_id,
                                    ),
                                    array(
                                        '%d',
                                        '%d',
                                    )
                                );
                            }
                        }
                    }

                    self::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-update&message=1&id=' . $id ) );
                } else {
                    set_transient( 'hfcm_pro_snippet_error', $snippet_exception, 60 );
                    self::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-update&message=2&id=' . $id ) );
                }
            } else {

                $snippet_exception = "";

                $nnr_hfcm_pro_validate_snippet = get_option( 'nnr_hfcm_pro_validate_snippet' );

                if ( $nnr_snippet_type == "php" && $nnr_hfcm_pro_validate_snippet ) {
                    try {
                        $validate_snippet     = trim( html_entity_decode( $snippet ) );
                        $snippet_check_result = "";
                        $snippet_exception    = "";

                        /* Remove <?php and <? from beginning of snippet */
                        $validate_snippet = preg_replace( '|^\s*<\?(php)?|', '', $validate_snippet );
                        /* Remove ?> from end of snippet */
                        $validate_snippet = preg_replace( '|\?>\s*$|', '', $validate_snippet );

                        $validator = new HFCM_Validator( $validate_snippet );

                        $snippet_exception = $validator->validate();


                        ob_start( array( 'NNR_HFCM_PRO', 'code_error_callback' ) );
                        $snippet_check_result  = eval( $validate_snippet );
                        $snippet_check_content = ob_get_clean();

                        $snippet_check_content = str_replace( [ '<b>', '</b>' ], "", $snippet_check_content );

                        $snippet_exception = "";
                        if ( false !== $snippet_check_result ) {
                            $snippet_exception = "";
                        } else {
                            $nnr_error_get_last = error_get_last();
                            if ( !empty( $nnr_error_get_last ) ) {
                                $snippet_exception .= implode( ".", $nnr_error_get_last );
                            }
                        }

                        if ( (strpos( $snippet_check_content, 'Notice:' ) !== false) ) {
                            $snippet_exception .= $snippet_check_content;
                        }
                    } catch ( \Exception $e ) {
                        $snippet_exception = ($e->getMessage());
                    } catch ( \Throwable $e ) {
                        $snippet_exception = ($e->getMessage());
                    }
                }

                if ( empty( $snippet_exception ) ) {
                    // Create new snippet
                    $wpdb->insert(
                        $table_name, //table
                        array(
                            'name'           => $name,
                            'snippet'        => $snippet,
                            'snippet_type'   => $nnr_snippet_type,
                            'device_type'    => $device_type,
                            'location'       => $location,
                            'display_on'     => $display_on,
                            'display_to'     => $display_to,
                            'priority'       => $nnr_snippet_priority,
                            'status'         => $status,
                            'lp_count'       => $lp_count,
                            's_pages'        => wp_json_encode( $s_pages ),
                            'ex_pages'       => wp_json_encode( $ex_pages ),
                            's_posts'        => wp_json_encode( $s_posts ),
                            'ex_posts'       => wp_json_encode( $ex_posts ),
                            's_custom_posts' => wp_json_encode( $s_custom_posts ),
                            's_categories'   => wp_json_encode( $s_categories ),
                            's_tags'         => wp_json_encode( $s_tags ),
                            'created'        => current_time( 'Y-m-d H:i:s' ),
                            'created_by'     => sanitize_text_field( $current_user->display_name ),
                        ), array(
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%d',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                        )
                    );
                    $lastid = $wpdb->insert_id;

                    $nnr_snippet_tags = explode( ',', $nnr_snippet_tags );

                    if ( !empty( $nnr_snippet_tags ) ) {
                        foreach ( $nnr_snippet_tags as $key_tag => $nnr_item_tag ) {
                            $nnr_item_tag = trim( sanitize_text_field( $nnr_item_tag ) );
                            $nnr_tag_id   = "";
                            $nnr_get_tag  = $wpdb->get_row( $wpdb->prepare( "SELECT * from `{$nnr_hfcm_pro_tags_table}` where tag=%s", $nnr_item_tag ) );
                            if ( !empty( $nnr_get_tag->id ) ) {
                                $nnr_tag_id = $nnr_get_tag->id;
                            } else {
                                $wpdb->insert( $nnr_hfcm_pro_tags_table, array( 'tag' => $nnr_item_tag ), array( '%s' ) );
                                $nnr_tag_id = $wpdb->insert_id;
                            }

                            if ( !empty( $nnr_tag_id ) ) {
                                $wpdb->insert(
                                    $nnr_hfcm_pro_snippet_tag_map_table,
                                    array(
                                        'snippet_id' => $lastid,
                                        'tag_id'     => $nnr_tag_id,
                                    ),
                                    array(
                                        '%d',
                                        '%d',
                                    )
                                );
                            }
                        }
                    }
                    self::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-update&message=6&id=' . $lastid ) );
                } else {
                    set_transient( 'hfcm_pro_snippet_error', $snippet_exception, 60 );
                    self::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-create' ) );
                }
            }
        } elseif ( isset( $_POST['get_posts'] ) ) {

            // JSON return posts for AJAX

            // Check nonce
            check_ajax_referer( 'hfcm-pro-get-posts', 'security' );

            // Global vars
            global $wpdb;
            $table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;

            // Get all selected posts
            if ( -1 === $id ) {
                $s_posts  = array();
                $ex_posts = array();
            } else {

                // Select value to update
                $script  = $wpdb->get_results( $wpdb->prepare( "SELECT s_posts FROM `{$table_name}` WHERE script_id=%s", $id ) );
                $s_posts = array();
                if ( !empty( $script ) ) {
                    foreach ( $script as $s ) {
                        $s_posts = json_decode( $s->s_posts );
                        if ( !is_array( $s_posts ) ) {
                            $s_posts = array();
                        }
                    }
                }

                $ex_posts  = array();
                $script_ex = $wpdb->get_results( $wpdb->prepare( "SELECT ex_posts FROM `{$table_name}` WHERE script_id=%s", $id ) );
                if ( !empty( $script_ex ) ) {
                    foreach ( $script_ex as $s ) {
                        $ex_posts = json_decode( $s->ex_posts );
                        if ( !is_array( $ex_posts ) ) {
                            $ex_posts = array();
                        }
                    }
                }
            }

            // Get all posts
            $args = array(
                'public'   => true,
                '_builtin' => false,
            );

            $output   = 'names'; // names or objects, note names is the default
            $operator = 'and'; // 'and' or 'or'

            $c_posttypes = get_post_types( $args, $output, $operator );
            $posttypes   = array( 'post' );
            foreach ( $c_posttypes as $cpdata ) {
                $posttypes[] = $cpdata;
            }
            $posts = get_posts(
                array(
                    'post_type'      => $posttypes,
                    'posts_per_page' => -1,
                    'numberposts'    => -1,
                    'orderby'        => 'title',
                    'order'          => 'ASC',
                    'post_status'    => array( 'publish', 'private' ),
                )
            );

            $json_output = array(
                'selected' => array(),
                'posts'    => array(),
                'excluded' => array(),
            );

            if ( !empty( $posts ) ) {
                foreach ( $posts as $pdata ) {
                    $nnr_hfcm_pro_post_title = trim( $pdata->post_title );

                    if ( empty( $nnr_hfcm_pro_post_title ) ) {
                        $nnr_hfcm_pro_post_title = "(no title)";
                    }
                    if ( !empty( $ex_posts ) && in_array( $pdata->ID, $ex_posts ) ) {
                        $json_output['excluded'][] = $pdata->ID;
                    }

                    if ( !empty( $s_posts ) && in_array( $pdata->ID, $s_posts ) ) {
                        $json_output['selected'][] = $pdata->ID;
                    }

                    $json_output['posts'][] = array(
                        'text'  => sanitize_text_field( $nnr_hfcm_pro_post_title ),
                        'value' => $pdata->ID,
                    );
                }
            }

            echo wp_json_encode( $json_output );
            wp_die();
        }
    }

    /**
     * Display a custom error message when a code error is encountered
     */
    public static function code_error_callback( $out )
    {
        $error = error_get_last();

        if ( is_null( $error ) ) {
            return $out;
        }

        $m = '<h3>' . esc_html__( "Don't Panic", '99robots-header-footer-code-manager-pro' ) . '</h3>';
        /* translators: %d: line where error was produced */
        $m .= '<p>' . sprintf( esc_html__( 'The code snippet you are trying to save produced a fatal error on line %d:', '99robots-header-footer-code-manager-pro' ), intval( $error['line'] ) ) . '</p>';
        $m .= '<strong>' . esc_html( $error['message'] ) . '</strong>';
        $m .= '<p>' . esc_html__( 'The previous version of the snippet is unchanged, and the rest of this site should be functioning normally as before.', '99robots-header-footer-code-manager-pro' ) . '</p>';
        $m .= '<p>' . esc_html__( 'Please use the back button in your browser to return to the previous page and try to fix the code error.', '99robots-header-footer-code-manager-pro' );
        $m .= ' ' . esc_html__( 'If you prefer, you can close this page and discard the changes you just made. No changes will be made to this site.', '99robots-header-footer-code-manager-pro' ) . '</p>';

        return $m;
    }

    /*
     * function for submenu "Update snippet" page
     */
    public static function hfcm_pro_update()
    {

        add_action( 'wp_enqueue_scripts', 'hfcm_pro_selectize_enqueue' );

        // check user capabilities
        $nnr_hfcm_pro_can_edit = current_user_can( 'manage_options' );

        if ( !$nnr_hfcm_pro_can_edit ) {
            echo 'Sorry, you do not have access to this page.';
            return false;
        }

        if ( empty( $_GET['id'] ) ) {
            die( 'Missing ID parameter.' );
        }
        $id = absint( $_GET['id'] );

        global $wpdb;
        $table_name                         = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        $nnr_hfcm_pro_tags_table            = $wpdb->prefix . self::$nnr_hfcm_pro_tags_table;
        $nnr_hfcm_pro_snippet_tag_map_table = $wpdb->prefix . self::$nnr_hfcm_pro_snippet_tag_map_table;

        //selecting value to update
        $nnr_hfcm_pro_snippets              = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_name}` WHERE script_id=%s", $id ) );
        $nnr_hfcm_pro_snippet_tags_array    = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT nhpt.id, tag FROM `{$nnr_hfcm_pro_tags_table}` nhpt JOIN `{$nnr_hfcm_pro_snippet_tag_map_table}` nhpstm ON 
                                nhpt.id = nhpstm.tag_id
                                WHERE nhpstm.snippet_id = %d", $id
            )
        );
        $nnr_hfcm_pro_tags_table            = $wpdb->prefix . self::$nnr_hfcm_pro_tags_table;
        $nnr_hfcm_pro_snippet_tag_map_table = $wpdb->prefix . self::$nnr_hfcm_pro_snippet_tag_map_table;

        //selecting value to update
        $nnr_hfcm_pro_snippets       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_name}` WHERE script_id=%s", $id ) );
        $nnr_hfcm_pro_all_tags_array = $wpdb->get_results(
            "SELECT tag FROM $nnr_hfcm_pro_tags_table"
        );
        foreach ( $nnr_hfcm_pro_snippets as $s ) {
            $name                 = $s->name;
            $snippet              = $s->snippet;
            $nnr_snippet_type     = $s->snippet_type;
            $nnr_snippet_error    = $s->snippet_error;
            $device_type          = $s->device_type;
            $location             = $s->location;
            $display_on           = $s->display_on;
            $display_to           = $s->display_to;
            $nnr_snippet_priority = $s->priority;
            $status               = $s->status;
            $lp_count             = $s->lp_count;
            if ( empty( $lp_count ) ) {
                $lp_count = 5;
            }
            $s_pages  = json_decode( $s->s_pages );
            $ex_pages = json_decode( $s->ex_pages );
            $ex_posts = json_decode( $s->ex_posts );

            if ( !is_array( $s_pages ) ) {
                $s_pages = array();
            }

            if ( !is_array( $ex_pages ) ) {
                $ex_pages = array();
            }

            $s_posts = json_decode( $s->s_posts );
            if ( !is_array( $s_posts ) ) {
                $s_posts = array();
            }

            $ex_posts = json_decode( $s->ex_posts );
            if ( !is_array( $ex_posts ) ) {
                $ex_posts = array();
            }

            $s_custom_posts = json_decode( $s->s_custom_posts );
            if ( !is_array( $s_custom_posts ) ) {
                $s_custom_posts = array();
            }

            $s_categories = json_decode( $s->s_categories );
            if ( !is_array( $s_categories ) ) {
                $s_categories = array();
            }

            $s_tags = json_decode( $s->s_tags );
            if ( !is_array( $s_tags ) ) {
                $s_tags = array();
            }

            $createdby        = esc_html( $s->created_by );
            $lastmodifiedby   = esc_html( $s->last_modified_by );
            $createdon        = esc_html( $s->created );
            $lastrevisiondate = esc_html( $s->last_revision_date );
        }

        $nnr_hfcm_pro_snippet_tags = "";
        foreach ( $nnr_hfcm_pro_snippet_tags_array as $nnr_key_tag => $nnr_item_tag ) {
            if ( $nnr_key_tag == 0 ) {
                $nnr_hfcm_pro_snippet_tags .= esc_html( $nnr_item_tag->tag );
            } else {
                $nnr_hfcm_pro_snippet_tags .= ", " . esc_html( $nnr_item_tag->tag );
            }
        }

        $nnr_hfcm_pro_all_tags = "";
        foreach ( $nnr_hfcm_pro_all_tags_array as $nnr_key_tag => $nnr_item_tag ) {
            if ( $nnr_key_tag == 0 ) {
                $nnr_hfcm_pro_all_tags .= esc_html( $nnr_item_tag->tag );
            } else {
                $nnr_hfcm_pro_all_tags .= ", " . esc_html( $nnr_item_tag->tag );
            }
        }
        // escape for html output
        $name                 = esc_textarea( $name );
        $snippet              = esc_textarea( $snippet );
        $nnr_snippet_type     = esc_textarea( $nnr_snippet_type );
        $device_type          = esc_html( $device_type );
        $location             = esc_html( $location );
        $display_on           = esc_html( $display_on );
        $display_to           = esc_html( $display_to );
        $nnr_snippet_priority = esc_html( $nnr_snippet_priority );
        $nnr_snippet_error    = esc_html( $nnr_snippet_error );
        $status               = esc_html( $status );
        $lp_count             = esc_html( $lp_count );
        $i                    = esc_html( $lp_count );
        // Notify hfcm-pro-add-edit.php to make necesary changes for update
        $update = true;

        include_once plugin_dir_path( __FILE__ ) . 'includes/hfcm-pro-add-edit.php';
    }

    /**
     * function to handle add/update tag requests
     */
    public static function hfcm_pro_tag_request_handler()
    {

        // Check user capabilities
        $nnr_hfcm_pro_can_edit = current_user_can( 'manage_options' );

        if ( !$nnr_hfcm_pro_can_edit ) {
            echo 'Sorry, you do not have access to this page.';
            return false;
        }

        if ( isset( $_POST['insert'] ) ) {
            // Check nonce
            check_admin_referer( 'create-tag' );
        } else {
            if ( empty( $_REQUEST['id'] ) ) {
                die( 'Missing ID parameter.' );
            }
            $id = absint( $_REQUEST['id'] );
        }
        if ( isset( $_POST['update'] ) ) {
            // Check nonce
            check_admin_referer( 'update-tag_' . $id );
        }

        if ( isset( $_POST['insert'] ) || isset( $_POST['update'] ) ) {

            // Create / update snippet

            // Sanitize fields
            $tag = self::hfcm_pro_sanitize_text( 'tag' );

            // Global vars
            global $wpdb;
            global $current_user;
            $nnr_hfcm_pro_tags_table = $wpdb->prefix . self::$nnr_hfcm_pro_tags_table;

            // Update snippet
            if ( isset( $id ) ) {
                $wpdb->update(
                    $nnr_hfcm_pro_tags_table,
                    // Data
                    array(
                        'tag' => $tag,
                    ),
                    // Where
                    array( 'id' => $id ),
                    // Data format
                    array(
                        '%s'
                    ),
                    // Where format
                    array( '%s' )
                );

                self::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-tag-update&message=1&id=' . $id ) );

            } else {

                $snippet_exception = "";
                // Create new snippet
                $wpdb->insert(
                    $nnr_hfcm_pro_tags_table, //table
                    array(
                        'tag' => $tag
                    ), array(
                        '%s'
                    )
                );
                $lastid = $wpdb->insert_id;

                self::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-tag-update&message=6&id=' . $lastid ) );
            }
        }
    }

    /*
     * function for submenu "Update tag" page
     */
    public static function hfcm_pro_tag_update()
    {

        add_action( 'wp_enqueue_scripts', 'hfcm_pro_selectize_enqueue' );

        // check user capabilities
        $nnr_hfcm_pro_can_edit = current_user_can( 'manage_options' );

        if ( !$nnr_hfcm_pro_can_edit ) {
            echo 'Sorry, you do not have access to this page.';
            return false;
        }

        if ( empty( $_GET['id'] ) ) {
            die( 'Missing ID parameter.' );
        }
        $id = absint( $_GET['id'] );

        global $wpdb;
        $nnr_hfcm_pro_tags_table = $wpdb->prefix . self::$nnr_hfcm_pro_tags_table;

        //selecting value to update
        $nnr_hfcm_pro_tags = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$nnr_hfcm_pro_tags_table}` WHERE id=%s", $id ) );

        foreach ( $nnr_hfcm_pro_tags as $s ) {
            $tag       = $s->tag;
            $createdon = esc_html( $s->created_at );
        }

        // escape for html output
        $tag = esc_textarea( $tag );

        // Notify hfcm-pro-add-edit.php to make necesary changes for update
        $update = true;

        include_once plugin_dir_path( __FILE__ ) . 'includes/hfcm-pro-add-edit-tag.php';
    }

    /*
     * function to get list of all snippets
     */
    public static function hfcm_pro_list()
    {

        global $wpdb;
        $table_name    = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        $activeclass   = '';
        $inactiveclass = '';
        $allclass      = 'current';
        $snippet_obj   = new Hfcm_Pro_Snippets_List();

        $is_free_version_active = self::is_hfcm_free_active();

        if ( $is_free_version_active ) {
            ?>
            <div class="notice hfcm-pro-warning-notice notice-warning">
                <?php _e(
                    'Please deactivate the free version of this plugin in order to avoid duplication of the snippets.
                    You can use our tools to import all the snippets from the free version of this plugin.', '99robots-header-footer-code-manager-pro'
                ); ?>
            </div>
            <?php
        }
        if ( !empty( $_GET['script_status'] ) && in_array(
                $_GET['script_status'], array( 'active',
                                               'inactive' )
            )
        ) {
            $allclass = '';
            if ( 'active' === $_GET['script_status'] ) {
                $activeclass = 'current';
            }
            if ( 'inactive' === $_GET['script_status'] ) {
                $inactiveclass = 'current';
            }
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Snippets', '99robots-header-footer-code-manager-pro' ) ?>
                <a href="<?php echo admin_url( 'admin.php?page=hfcm-pro-create' ) ?>"
                   class="page-title-action"><?php esc_html_e( 'Add New Snippet', '99robots-header-footer-code-manager-pro' ) ?></a>
            </h1>

            <form method="post">
                <?php
                $snippet_obj->prepare_items();
                $snippet_obj->search_box( __( 'Search Snippets', '99robots-header-footer-code-manager-pro' ), 'search_id' );
                $snippet_obj->display();
                ?>
            </form>

        </div>
        <?php

        // Register the script
        wp_register_script( 'hfcm_pro_toggle', plugins_url( 'js/toggle.js', __FILE__ ), array( 'jquery' ), NNR_HFCM_PRO::$nnr_hfcm_pro_version );

        // Localize the script with new data
        $translation_array = array(
            'url'      => admin_url( 'admin.php' ),
            'security' => wp_create_nonce( 'hfcm-pro-toggle-snippet' ),
        );
        wp_localize_script( 'hfcm_pro_toggle', 'hfcm_pro_ajax', $translation_array );

        // Enqueued script with localized data.
        wp_enqueue_script( 'hfcm_pro_toggle' );
    }

    /*
     * function to get list of all tags
     */
    public static function hfcm_pro_tags_list()
    {

        global $wpdb;
        $table_name              = $wpdb->prefix . self::$nnr_hfcm_pro_table;
        $nnr_hfcm_pro_tags_table = $wpdb->prefix . self::$nnr_hfcm_pro_tags_table;
        $activeclass             = '';
        $inactiveclass           = '';
        $allclass                = 'current';
        $tag_obj                 = new Hfcm_Pro_Tags_List();

        $is_free_version_active = self::is_hfcm_free_active();

        if ( $is_free_version_active ) {
            ?>
            <div class="notice hfcm-pro-warning-notice notice-warning">
                <?php _e(
                    'Please deactivate the free version of this plugin in order to avoid duplication of the snippets.
                    You can use our tools to import all the snippets from the free version of this plugin.', '99robots-header-footer-code-manager-pro'
                ); ?>
            </div>
            <?php
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Tags', '99robots-header-footer-code-manager-pro' ) ?>
                <a href="<?php echo admin_url( 'admin.php?page=hfcm-pro-tag-create' ) ?>"
                   class="page-title-action"><?php esc_html_e( 'Add New Tag', '99robots-header-footer-code-manager-pro' ) ?></a>
            </h1>

            <form method="post">
                <?php
                $tag_obj->prepare_items();
                $tag_obj->search_box( __( 'Search Tags', '99robots-header-footer-code-manager-pro' ), 'search_id' );
                $tag_obj->display();
                ?>
            </form>

        </div>
        <?php

        // Register the script
        wp_register_script( 'hfcm_pro_toggle', plugins_url( 'js/toggle.js', __FILE__ ), array( 'jquery' ), NNR_HFCM_PRO::$nnr_hfcm_pro_version );

        // Localize the script with new data
        $translation_array = array(
            'url'      => admin_url( 'admin.php' ),
            'security' => wp_create_nonce( 'hfcm-pro-toggle-snippet' ),
        );
        wp_localize_script( 'hfcm_pro_toggle', 'hfcm_pro_ajax', $translation_array );

        // Enqueued script with localized data.
        wp_enqueue_script( 'hfcm_pro_toggle' );
    }


    /**
     * function to get load tools page
     */
    public static function hfcm_pro_tools()
    {
        global $wpdb;
        $nnr_hfcm_pro_table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;

        $nnr_hfcm_pro_snippets = $wpdb->get_results( "SELECT * FROM `{$nnr_hfcm_pro_table_name}`" );

        include_once plugin_dir_path( __FILE__ ) . 'includes/hfcm-pro-tools.php';
    }

    /**
     * function to export snippets
     */
    public static function hfcm_pro_export_snippets()
    {
        global $wpdb;
        $nnr_hfcm_pro_table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;

        if ( !empty( $_POST['nnr_hfcm_pro_snippet'] ) && !empty( $_POST['action'] ) && ($_POST['action'] == "download") && check_admin_referer( 'hfcm-pro-nonce' ) ) {
            $nnr_hfcm_pro_snippets_comma_separated = "";
            foreach ( $_POST['nnr_hfcm_pro_snippet'] as $nnr_hfcm_pro_key => $nnr_hfcm_pro_snippet ) {
                $nnr_hfcm_pro_snippet = str_replace( "snippet_", "", sanitize_text_field( $nnr_hfcm_pro_snippet ) );
                $nnr_hfcm_pro_snippet = absint( $nnr_hfcm_pro_snippet );
                if ( !empty( $nnr_hfcm_pro_snippet ) ) {
                    if ( empty( $nnr_hfcm_pro_snippets_comma_separated ) ) {
                        $nnr_hfcm_pro_snippets_comma_separated .= $nnr_hfcm_pro_snippet;
                    } else {
                        $nnr_hfcm_pro_snippets_comma_separated .= "," . $nnr_hfcm_pro_snippet;
                    }
                }
            }
            if ( !empty( $nnr_hfcm_pro_snippets_comma_separated ) ) {
                $nnr_hfcm_pro_snippets = $wpdb->get_results( "SELECT * FROM `{$nnr_hfcm_pro_table_name}` WHERE script_id IN (" . $nnr_hfcm_pro_snippets_comma_separated . ")" );

                if ( !empty( $nnr_hfcm_pro_snippets ) ) {
                    $nnr_hfcm_pro_export_snippets = array( "title" => "Header Footer Code Manager" );

                    foreach ( $nnr_hfcm_pro_snippets as $nnr_hfcm_pro_snippet_key => $nnr_hfcm_pro_snippet_item ) {
                        unset( $nnr_hfcm_pro_snippet_item->script_id );
                        unset( $nnr_hfcm_pro_snippet_item->snippet_error );
                        $nnr_hfcm_pro_export_snippets['snippets'][ $nnr_hfcm_pro_snippet_key ] = $nnr_hfcm_pro_snippet_item;
                    }
                    $file_name = 'hfcm-export-' . date( 'Y-m-d' ) . '.json';
                    header( "Content-Description: File Transfer" );
                    header( "Content-Disposition: attachment; filename={$file_name}" );
                    header( "Content-Type: application/json; charset=utf-8" );
                    echo json_encode( $nnr_hfcm_pro_export_snippets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
                }
            }
            die;
        }
    }

    /**
     * function to import snippets
     */
    public static function hfcm_pro_import_snippets()
    {
        if ( !empty( $_FILES['nnr_hfcm_pro_import_file']['tmp_name'] ) && check_admin_referer( 'hfcm-pro-nonce' ) ) {
            if ( !empty( $_FILES['nnr_hfcm_pro_import_file']['type'] ) && $_FILES['nnr_hfcm_pro_import_file']['type'] != "application/json" ) {
                ?>
                <div class="notice hfcm-pro-warning-notice notice-warning">
                    <?php _e( 'Please upload a valid import file', '99robots-header-footer-code-manager-pro' ); ?>
                </div>
                <?php
                return;
            }

            global $wpdb;
            $nnr_hfcm_pro_table_name = $wpdb->prefix . self::$nnr_hfcm_pro_table;

            $nnr_hfcm_pro_snippets_json = file_get_contents( $_FILES['nnr_hfcm_pro_import_file']['tmp_name'] );
            $nnr_hfcm_pro_snippets      = json_decode( $nnr_hfcm_pro_snippets_json );

            if ( empty( $nnr_hfcm_pro_snippets->title ) || (!empty( $nnr_hfcm_pro_snippets->title ) && $nnr_hfcm_pro_snippets->title != "Header Footer Code Manager") ) {
                ?>
                <div class="notice hfcm-pro-warning-notice notice-warning">
                    <?php _e( 'Please upload a valid import file', '99robots-header-footer-code-manager-pro' ); ?>
                </div>
                <?php
                return;
            }

            foreach ( $nnr_hfcm_pro_snippets->snippets as $nnr_hfcm_pro_key => $nnr_hfcm_pro_snippet ) {
                $nnr_hfcm_pro_snippet           = (array) $nnr_hfcm_pro_snippet;
                $nnr_hfcm_pro_sanitizes_snippet = [];
                foreach ( $nnr_hfcm_pro_snippet as $nnr_key => $nnr_item ) {
                    $nnr_key = sanitize_text_field( $nnr_key );
                    if ( $nnr_key == "lp_count" ) {
                        $nnr_item = absint( $nnr_item );
                    } elseif ( $nnr_key != "snippet" ) {
                        $nnr_item = sanitize_text_field( $nnr_item );
                    }
                    $nnr_hfcm_pro_sanitizes_snippet[ $nnr_key ] = $nnr_item;
                }
                $nnr_hfcm_pro_sanitizes_snippet['status'] = 'inactive';

                $wpdb->insert(
                    $nnr_hfcm_pro_table_name, $nnr_hfcm_pro_sanitizes_snippet, array(
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s'
                    )
                );
            }

            self::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-list' ) );
        }
    }

    /**
     * function to get all categories
     */
    public static function hfcm_pro_get_categories()
    {
        $args       = array(
            'public'       => true,
            'hierarchical' => true
        );
        $output     = 'objects'; // or objects
        $operator   = 'and'; // 'and' or 'or'
        $taxonomies = get_taxonomies( $args, $output, $operator );

        $nnr_hfcm_pro_categories = [];

        foreach ( $taxonomies as $taxonomy ) {
            $nnr_hfcm_pro_taxonomy_categories = get_categories(
                [
                    'taxonomy'   => $taxonomy->name,
                    'hide_empty' => 0
                ]
            );
            $nnr_hfcm_pro_taxonomy_categories = [
                'name'  => $taxonomy->label,
                'terms' => $nnr_hfcm_pro_taxonomy_categories
            ];
            $nnr_hfcm_pro_categories[]        = $nnr_hfcm_pro_taxonomy_categories;
        }

        return $nnr_hfcm_pro_categories;
    }

    /**
     * function to get all tags
     */
    public static function hfcm_pro_get_tags()
    {
        $args       = array( 'hide_empty' => 0 );
        $args       = array(
            'public'       => true,
            'hierarchical' => false
        );
        $output     = 'objects'; // or objects
        $operator   = 'and'; // 'and' or 'or'
        $taxonomies = get_taxonomies( $args, $output, $operator );

        $nnr_hfcm_pro_tags = [];

        foreach ( $taxonomies as $taxonomy ) {
            $nnr_hfcm_pro_taxonomy_tags = get_tags(
                [
                    'taxonomy'   => $taxonomy->name,
                    'hide_empty' => 0
                ]
            );
            $nnr_hfcm_pro_taxonomy_tags = [
                'name'  => $taxonomy->label,
                'terms' => $nnr_hfcm_pro_taxonomy_tags
            ];
            $nnr_hfcm_pro_tags[]        = $nnr_hfcm_pro_taxonomy_tags;
        }

        return $nnr_hfcm_pro_tags;
    }

    /**
     * function to manage settings
     */
    public static function hfcm_pro_settings()
    {
        $license                                  = get_option( 'nnr_hfcm_pro_license_key' );
        $status                                   = get_option( 'nnr_hfcm_pro_license_status' );
        $nnr_hfcm_pro_validate_snippet            = get_option( 'nnr_hfcm_pro_validate_snippet' );
        $nnr_hfcm_pro_validate_snippet_is_checked = "";
        if ( $nnr_hfcm_pro_validate_snippet ) {
            $nnr_hfcm_pro_validate_snippet_is_checked = "checked";
        }
        ?>
        <div class="wrap">
            <h2><?php _e( 'Plugin License Options', '99robots-header-footer-code-manager-pro' ); ?></h2>
            <form method="post" action="options.php">

                <?php settings_fields( 'nnr_hfcm_pro_license' ); ?>

                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e( 'License Key', '99robots-header-footer-code-manager-pro' ); ?>
                        </th>
                        <td>
                            <input id="nnr_hfcm_pro_license_key" name="nnr_hfcm_pro_license_key" type="text"
                                   class="regular-text" value="<?php esc_attr_e( $license ); ?>"/>
                            <label class="description"
                                   for="nnr_hfcm_pro_license_key"><?php _e( 'Enter your license key', '99robots-header-footer-code-manager-pro' ); ?></label>
                        </td>
                    </tr>
                    <?php if ( false !== $license ) { ?>
                        <tr>
                            <th scope="row">
                                <?php _e( 'Activate License', '99robots-header-footer-code-manager-pro' ); ?>
                            </th>
                            <td>
                                <?php if ( $status !== false && $status == 'valid' ) { ?>
                                    <span style="color:green;"><?php _e( 'active', '99robots-header-footer-code-manager-pro' ); ?></span>
                                    <?php wp_nonce_field( 'nnr_hfcm_pro_nonce', 'nnr_hfcm_pro_nonce' ); ?>
                                    <input type="submit" class="button-secondary" name="nnr_hfcm_pro_license_deactivate"
                                           value="<?php _e( 'Deactivate License', '99robots-header-footer-code-manager-pro' ); ?>"/>
                                <?php } else {
                                    wp_nonce_field( 'nnr_hfcm_pro_nonce', 'nnr_hfcm_pro_nonce' ); ?>
                                    <input type="submit" class="button-secondary" name="nnr_hfcm_pro_license_activate"
                                           value="<?php _e( 'Activate License', '99robots-header-footer-code-manager-pro' ); ?>"/>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php submit_button(); ?>

            </form>
        </div>
        <div class="wrap">
            <h2><?php _e( 'Snippet Settings', '99robots-header-footer-code-manager-pro' ); ?></h2>
            <form method="post" action="options.php">

                <?php settings_fields( 'nnr_hfcm_pro_validation_setting' ); ?>
                <?php wp_nonce_field( 'nnr_hfcm_pro_nonce', 'nnr_hfcm_pro_nonce' ); ?>

                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e( 'Validate PHP Snippets Before Saving?', '99robots-header-footer-code-manager-pro' ); ?>
                        </th>
                        <td>
                            <input id="nnr_hfcm_pro_validate_snippet" name="nnr_hfcm_pro_validate_snippet"
                                   type="checkbox" <?php echo $nnr_hfcm_pro_validate_snippet_is_checked; ?>
                                   class="regular-text" value="1"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * function to activate license key
     */
    public static function hfcm_pro_activate_license()
    {
        // listen for our activate button to be clicked
        if ( isset( $_POST['nnr_hfcm_pro_license_activate'] ) ) {

            // run a quick security check
            if ( !check_admin_referer( 'nnr_hfcm_pro_nonce', 'nnr_hfcm_pro_nonce' ) ) {
                return; // get out if we didn't click the Activate button
            }


            // data to send in our API request
            $api_params = array(
                'edd_action' => 'activate_license',
                'license'    => NNR_HFCM_PRO::hfcm_pro_get_license_key(),
                'item_name'  => urlencode( NNR_HFCM_PRO::$nnr_hfcm_pro_item_name ), // the name of our product in EDD
                'url'        => home_url()
            );

            // Call the custom API.
            $response = wp_remote_post(
                NNR_HFCM_PRO::$nnr_hfcm_pro_store_url, array( 'timeout'   => 15,
                                                              'sslverify' => false,
                                                              'body'      => $api_params )
            );

            // make sure the response came back okay
            if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                $message = (is_wp_error( $response ) && !empty( $response->get_error_message() )) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
            } else {
                $license_data = json_decode( wp_remote_retrieve_body( $response ) );
                if ( false === $license_data->success ) {
                    switch ( $license_data->error ) {
                        case 'expired' :
                            $message = sprintf(
                                __( 'Your license key expired on %s.' ),
                                date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                            );
                            break;

                        case 'revoked' :
                            $message = __( 'Your license key has been disabled.' );
                            break;

                        case 'missing' :
                            $message = __( 'Invalid license.' );
                            break;

                        case 'invalid' :
                        case 'site_inactive' :
                            $message = __( 'Your license is not active for this URL.' );
                            break;

                        case 'item_name_mismatch' :
                            $message = sprintf( __( 'This appears to be an invalid license key for %s.' ), EDD_SAMPLE_ITEM_NAME );
                            break;

                        case 'no_activations_left':
                            $message = __( 'Your license key has reached its activation limit.' );
                            break;

                        default :
                            $message = __( 'An error occurred, please try again.' );
                            break;
                    }

                }
            }

            // Check if anything passed on a message constituting a failure
            if ( !empty( $message ) ) {
                $base_url = admin_url( 'admin.php?page=' . NNR_HFCM_PRO::$nnr_hfcm_pro_settings_page );
                $redirect = add_query_arg(
                    array( 'sl_activation' => 'false',
                           'message'       => urlencode( $message ) ), $base_url
                );

                wp_redirect( $redirect );
                exit();
            }

            // $license_data->license will be either "valid" or "invalid"

            update_option( 'nnr_hfcm_pro_license_status', $license_data->license );
            wp_redirect( admin_url( 'admin.php?page=' . NNR_HFCM_PRO::$nnr_hfcm_pro_settings_page ) );
            exit();
        } else if ( isset( $_POST['nnr_hfcm_pro_license_deactivate'] ) ) {
            // run a quick security check
            if ( !check_admin_referer( 'nnr_hfcm_pro_nonce', 'nnr_hfcm_pro_nonce' ) ) {
                return; // get out if we didn't click the Activate button
            }

            // data to send in our API request
            $api_params = array(
                'edd_action' => 'deactivate_license',
                'license'    => NNR_HFCM_PRO::hfcm_pro_get_license_key(),
                'item_name'  => urlencode( NNR_HFCM_PRO::$nnr_hfcm_pro_item_name ), // the name of our product in EDD
                'url'        => home_url()
            );

            // Call the custom API.
            $response = wp_remote_post(
                NNR_HFCM_PRO::$nnr_hfcm_pro_store_url, array( 'timeout'   => 15,
                                                              'sslverify' => false,
                                                              'body'      => $api_params )
            );

            // make sure the response came back okay
            if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                $message = (is_wp_error( $response ) && !empty( $response->get_error_message() )) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
            } else {
                $license_data = json_decode( wp_remote_retrieve_body( $response ) );

                if ( false === $license_data->success ) {
                    $base_url = admin_url( 'admin.php?page=' . NNR_HFCM_PRO::$nnr_hfcm_pro_settings_page );
                    $redirect = add_query_arg(
                        array( 'sl_activation' => 'false',
                               'message'       => urlencode( 'Something went wrong!!' ) ), $base_url
                    );

                    wp_redirect( $redirect );
                    exit();
                } else {
                    update_option( 'nnr_hfcm_pro_license_status', 'invalid' );
                }
            }

            wp_redirect( admin_url( 'admin.php?page=' . NNR_HFCM_PRO::$nnr_hfcm_pro_settings_page ) );
            exit();

        }
    }

    /**
     * function for catching errors from the activation method above and displaying it to the user
     */
    public static function hfcm_pro_license_admin_notices()
    {
        if ( isset( $_GET['sl_activation'] ) && !empty( $_GET['message'] ) ) {
            switch ( $_GET['sl_activation'] ) {
                case 'false':
                    $message = urldecode( $_GET['message'] );
                    ?>
                    <div class="error">
                        <p><?php echo $message; ?></p>
                    </div>
                    <?php
                    break;

                case 'true':
                default:
                    ?>
                    <div class="error">
                        <p>Activated Successfully</p>
                    </div>
                    <?php
                    // Developers can put a custom success message here for when activation is successful if they way.
                    break;
            }
        }
    }

    /**
     * function to get license key
     */
    public static function hfcm_pro_get_license_key()
    {
        $license_key = trim( get_option( 'nnr_hfcm_pro_license_key' ) );
        return $license_key;
    }

    /**
     * function to sanitize license key
     */
    public static function hfcm_pro_sanitize_license( $new )
    {
        $old = get_option( 'nnr_hfcm_pro_license_key' );
        if ( $old && $old != $new ) {
            delete_option( 'nnr_hfcm_pro_license_status' ); // new license has been entered, so must reactivate
        }
        return $new;
    }

    /**
     * function to sanitize snippet validation setting
     */
    public static function hfcm_pro_sanitize_validate_snippet( $nnr_hfcm_pro_validate_snippet_setting_new )
    {
        $nnr_hfcm_pro_validate_snippet_setting = get_option( 'nnr_hfcm_pro_validate_snippet' );
        if ( $nnr_hfcm_pro_validate_snippet_setting && $nnr_hfcm_pro_validate_snippet_setting != $nnr_hfcm_pro_validate_snippet_setting_new ) {
            delete_option( 'nnr_hfcm_pro_validate_snippet' );
        }
        return $nnr_hfcm_pro_validate_snippet_setting_new;
    }

    /**
     * function to register options
     */
    public static function hfcm_pro_register_option()
    {
        // creates our settings in the options table
        register_setting(
            'nnr_hfcm_pro_license', 'nnr_hfcm_pro_license_key', array( 'NNR_HFCM_PRO',
                                                                       'hfcm_pro_sanitize_license' )
        );
        register_setting(
            'nnr_hfcm_pro_validation_setting', 'nnr_hfcm_pro_validate_snippet', array( 'NNR_HFCM_PRO',
                                                                                       'hfcm_pro_sanitize_validate_snippet' )
        );
    }

    /**
     * Check if HFCM Free is activated
     *
     * @return bool
     */
    public static function is_hfcm_free_active()
    {
        if ( is_plugin_active( 'header-footer-code-manager/99robots-header-footer-code-manager.php' ) ) {
            return true;
        }

        return false;
    }
}