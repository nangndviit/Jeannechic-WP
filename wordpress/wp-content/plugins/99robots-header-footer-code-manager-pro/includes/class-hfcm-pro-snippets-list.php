<?php
if ( !class_exists( 'WP_List_Table' ) ) {
    include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Hfcm_Pro_Snippets_List extends WP_List_Table
{

    /**
     * Class constructor
     */
    public function __construct()
    {

        parent::__construct(
            array(
                'singular' => esc_html__( 'Snippet', '99robots-header-footer-code-manager-pro' ),
                'plural'   => esc_html__( 'Snippets', '99robots-header-footer-code-manager-pro' ),
                'ajax'     => false,
            )
        );
    }

    /**
     * Retrieve snippets data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_snippets( $per_page = 20, $page_number = 1, $customvar = 'all' )
    {

        global $wpdb;
        $table_name                   = "{$wpdb->prefix}hfcm_pro_scripts";
        $nnr_hfcm_pro_snippet_tag_map = "{$wpdb->prefix}hfcm_pro_snippet_tag_map";
        $page_number                  = absint( $page_number );
        $per_page                     = absint( $per_page );
        $customvar                    = sanitize_text_field( $customvar );
        $orderby                      = 'script_id';
        $order                        = 'ASC';

        if ( !empty( $_GET['orderby'] ) ) {
            $orderby = sanitize_sql_orderby( $_GET['orderby'] );
            if ( empty( $orderby ) || !in_array( $orderby, array( 'script_id', 'name', 'location' ) ) ) {
                $orderby = 'script_id';
            }
        }
        if ( !empty( $_GET['order'] ) ) {
            $order = strtolower( sanitize_sql_orderby( $_GET['order'] ) );
            if ( empty( $order ) || !in_array( $order, array( 'desc', 'asc' ) ) ) {
                $order = 'ASC';
            }
        }

        $sql              = "SELECT * FROM `{$table_name}`";
        $placeholder_args = array();

        if ( !empty( $_POST['nnr_selected_tag'] ) ) {
            $nnr_selected_tag = addslashes( sanitize_text_field( $_POST['nnr_selected_tag'] ) );
            $sql              .= " JOIN $nnr_hfcm_pro_snippet_tag_map 
                    ON $table_name.script_id = $nnr_hfcm_pro_snippet_tag_map.snippet_id 
                    WHERE $nnr_hfcm_pro_snippet_tag_map.tag_id = '$nnr_selected_tag'";
        } else {
            $sql .= " WHERE 1";
        }
        if ( in_array( $customvar, array( 'inactive', 'active' ) ) ) {
            $sql                .= " AND status = '%s'";
            $placeholder_args[] = $customvar;
        }

        if ( !empty( $_POST['snippet_type'] ) ) {
            if ( check_admin_referer( 'bulk-snippets' ) ) {
                $snippet_type = addslashes( sanitize_text_field( $_POST['snippet_type'] ) );
                if ( in_array( $snippet_type, array( 'html', 'js', 'css', 'php' ) ) ) {
                    $sql                .= " AND snippet_type = %s";
                    $placeholder_args[] = $snippet_type;
                }
            }
        }
        if ( !empty( $_POST['s'] ) ) {
            if ( check_admin_referer( 'bulk-snippets' ) ) {
                $search_query       = addslashes( sanitize_text_field( $_POST['s'] ) );
                $sql                .= " AND name LIKE %s";
                $placeholder_args[] = '%' . $search_query . '%';
            }
        }

        $sql .= ' ORDER BY %s %s LIMIT %d OFFSET %d';

        $placeholder_args[] = $orderby;
        $placeholder_args[] = $order;
        $placeholder_args[] = $per_page;
        $placeholder_args[] = ($page_number - 1) * $per_page;

        if ( !empty( $placeholder_args ) ) {
            $sql = $wpdb->prepare( $sql, $placeholder_args );
        }

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        return $result;
    }

    /**
     * Delete a snipppet record.
     *
     * @param int $id snippet ID
     */
    public static function delete_snippet( $id )
    {
        $id = absint( $id );
        if ( empty( $id ) ) {
            return;
        }

        global $wpdb;
        $table_name = "{$wpdb->prefix}hfcm_pro_scripts";

        $wpdb->delete(
            $table_name, array( 'script_id' => $id ), array( '%d' )
        );
    }

    /**
     * Activate a snipppet record.
     *
     * @param int $id snippet ID
     */
    public static function activate_snippet( $id )
    {

        $id = absint( $id );
        if ( empty( $id ) ) {
            return;
        }

        global $wpdb;
        $table_name = "{$wpdb->prefix}hfcm_pro_scripts";

        $wpdb->update(
            $table_name, array(
            'status' => 'active',
        ), array( 'script_id' => $id ), array( '%s' ), array( '%d' )
        );
    }

    /**
     * Deactivate a snipppet record.
     *
     * @param int $id snippet ID
     */
    public static function deactivate_snippet( $id )
    {

        $id = absint( $id );
        if ( empty( $id ) ) {
            return;
        }

        global $wpdb;
        $table_name = "{$wpdb->prefix}hfcm_pro_scripts";

        $wpdb->update(
            $table_name, array(
            'status' => 'inactive',
        ), array( 'script_id' => $id ), array( '%s' ), array( '%d' )
        );
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count( $customvar = 'all' )
    {

        global $wpdb;
        $table_name       = "{$wpdb->prefix}hfcm_pro_scripts";
        $sql              = "SELECT COUNT(*) FROM `{$table_name}`";
        $placeholder_args = [];

        $customvar = sanitize_text_field( $customvar );

        if ( in_array( $customvar, array( 'inactive', 'active' ) ) ) {
            $sql                .= " WHERE status = %s";
            $placeholder_args[] = $customvar;
        }
        if ( !empty( $placeholder_args ) ) {
            $sql = $wpdb->prepare( $sql, $placeholder_args );
        }

        return $wpdb->get_var( $sql );
    }

    /**
     * Text displayed when no snippet data is available
     */
    public function no_items()
    {
        esc_html_e( 'No Snippets available.', '99robots-header-footer-code-manager-pro' );
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name )
    {
        switch ( $column_name ) {
            case 'name':
                $nnr_column_html = esc_html( $item[ $column_name ] );
                return $nnr_column_html;
            case 'display_on':
                $nnr_hfcm_pro_display_array = array(
                    'All'            => esc_html__( 'Site Wide', '99robots-header-footer-code-manager-pro' ),
                    's_posts'        => esc_html__( 'Specific Posts', '99robots-header-footer-code-manager-pro' ),
                    's_pages'        => esc_html__( 'Specific Pages', '99robots-header-footer-code-manager-pro' ),
                    's_categories'   => esc_html__( 'Specific Categories', '99robots-header-footer-code-manager-pro' ),
                    's_custom_posts' => esc_html__( 'Specific Custom Post Types', '99robots-header-footer-code-manager-pro' ),
                    's_tags'         => esc_html__( 'Specific Tags', '99robots-header-footer-code-manager-pro' ),
                    's_is_home'      => esc_html__( 'Home Page', '99robots-header-footer-code-manager-pro' ),
                    's_is_search'    => esc_html__( 'Search Page', '99robots-header-footer-code-manager-pro' ),
                    's_is_archive'   => esc_html__( 'Archive Page', '99robots-header-footer-code-manager-pro' ),
                    'latest_posts'   => esc_html__( 'Latest Posts', '99robots-header-footer-code-manager-pro' ),
                    'manual'         => esc_html__( 'Shortcode Only', '99robots-header-footer-code-manager-pro' ),
                    'admin'          => esc_html__( 'Admin', '99robots-header-footer-code-manager-pro' ),
                );

                if ( 's_posts' === $item[ $column_name ] ) {

                    $empty   = 1;
                    $s_posts = json_decode( $item['s_posts'] );

                    foreach ( $s_posts as $id ) {
                        $id = absint( $id );
                        if ( 'publish' === get_post_status( $id ) ) {
                            $empty = 0;
                            break;
                        }
                    }
                    if ( $empty ) {
                        return '<span class="hfcm-pro-red">' . esc_html__( 'No post selected', '99robots-header-footer-code-manager-pro' ) . '</span>';
                    }
                }

                return esc_html( $nnr_hfcm_pro_display_array[ $item[ $column_name ] ] );

            case 'location':

                if ( !$item[ $column_name ] ) {
                    return esc_html__( 'N/A', '99robots-header-footer-code-manager-pro' );
                }

                $nnr_hfcm_pro_locations = array(
                    'header'         => esc_html__( 'Header', '99robots-header-footer-code-manager-pro' ),
                    'body_open'      => esc_html__( 'Body Open', '99robots-header-footer-code-manager-pro' ),
                    'before_content' => esc_html__( 'Before Content', '99robots-header-footer-code-manager-pro' ),
                    'after_content'  => esc_html__( 'After Content', '99robots-header-footer-code-manager-pro' ),
                    'footer'         => esc_html__( 'Footer', '99robots-header-footer-code-manager-pro' ),
                    'everywhere'     => esc_html__( 'Everywhere', '99robots-header-footer-code-manager-pro' ),
                );
                return esc_html( $nnr_hfcm_pro_locations[ $item[ $column_name ] ] );

            case 'device_type':

                if ( 'both' === $item[ $column_name ] ) {
                    return esc_html__( 'Show on All Devices', '99robots-header-footer-code-manager-pro' );
                } elseif ( 'mobile' === $item[ $column_name ] ) {
                    return esc_html__( 'Only Mobile Devices', '99robots-header-footer-code-manager-pro' );
                } elseif ( 'desktop' === $item[ $column_name ] ) {
                    return esc_html__( 'Only Desktop', '99robots-header-footer-code-manager-pro' );
                } else {
                    return esc_html( $item[ $column_name ] );
                }
            case 'snippet_type':
                $snippet_types = array(
                    'html' => esc_html__( 'HTML', '99robots-header-footer-code-manager-pro' ),
                    'css'  => esc_html__( 'CSS', '99robots-header-footer-code-manager-pro' ),
                    'js'   => esc_html__( 'Javascript', '99robots-header-footer-code-manager-pro' ),
                    'php'  => esc_html__( 'PHP', '99robots-header-footer-code-manager-pro' )
                );
                return esc_html( $snippet_types[ $item[ $column_name ] ] );

            case 'status':

                if ( 'inactive' === $item[ $column_name ] ) {
                    return '<div class="nnr-switch">
								<label for="nnr-round-toggle' . esc_attr( $item['script_id'] ) . '">OFF</label>
								<input id="nnr-round-toggle' . esc_attr( $item['script_id'] ) . '" class="round-toggle round-toggle-round-flat" type="checkbox" data-id="' . esc_attr( $item['script_id'] ) . '" />
								<label for="nnr-round-toggle' . esc_attr( $item['script_id'] ) . '"></label>
								<label for="nnr-round-toggle' . esc_attr( $item['script_id'] ) . '">ON</label>
							</div>
							';
                } elseif ( 'active' === $item[ $column_name ] ) {
                    return '<div class="nnr-switch">
								<label for="nnr-round-toggle' . esc_attr( $item['script_id'] ) . '">OFF</label>
								<input id="nnr-round-toggle' . esc_attr( $item['script_id'] ) . '" class="round-toggle round-toggle-round-flat" type="checkbox" data-id="' . esc_attr( $item['script_id'] ) . '" checked="checked" />
								<label for="nnr-round-toggle' . esc_attr( $item['script_id'] ) . '"></label>
								<label for="nnr-round-toggle' . esc_attr( $item['script_id'] ) . '">ON</label>
							</div>
							';
                } else {
                    return esc_html( $item[ $column_name ] );
                }

            case 'script_id':
                $nnr_column_html = esc_html( $item[ $column_name ] );
                if ( $item['snippet_type'] == 'php' && in_array( $item['location'], [ 'header', 'footer' ] ) ) {
                    $nnr_column_html .= '<span class="nnr-warning-badge" title="' .
                        __( 'Note: We have changed the way php snippets are executed to support a broader range of php snippets.  PHP Snippets will no longer specify a location (header, footer, etc).  For snippets created prior to v1.0.11 that relied on HFCM to specify the location (header, footer) - these will need to be converted to a new format. For more information - go to https://draftpress.com/docs/header-footer-code-manager-pro/#elementor-toc__heading-anchor-22. Also, please note that once you edit and save a legacy snippet, the location will be changed to execute everywhere.', '99robots-header-footer-code-manager-pro' )
                        . '"><span class="plugin-count">!</span></span>';
                }
                return $nnr_column_html;

            case 'shortcode':
                return '[hfcm id="' . absint( $item['script_id'] ) . '"]';

            default:
                return print_r( $item, true ); // Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item )
    {
        return sprintf(
            '<input type="checkbox" name="snippets[]" value="%s" />', $item['script_id']
        );
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_name( $item )
    {

        $delete_nonce = wp_create_nonce( 'hfcm_pro_delete_snippet' );
        $edit_nonce   = wp_create_nonce( 'hfcm_pro_edit_snippet' );

        $title = '<strong>' . esc_html( $item['name'] ) . '</strong>';

        $nnr_current_screen = get_current_screen();

        if ( !empty( $nnr_current_screen->parent_base ) ) {
            $page = $nnr_current_screen->parent_base;
        } else {
            $page = sanitize_text_field( $_GET['page'] );
        }
        $actions = array(
            'edit'      => sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">' . esc_html__( 'Edit', '99robots-header-footer-code-manager-pro' ) . '</a>', esc_attr( 'hfcm-pro-update' ), 'edit', absint( $item['script_id'] ), $edit_nonce ),
            'duplicate' => sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">' . esc_html__( 'Duplicate', '99robots-header-footer-code-manager-pro' ) . '</a>', esc_attr( 'hfcm-pro-duplicate' ), 'duplicate', absint( $item['script_id'] ), $edit_nonce ),
            'copy'      => sprintf( '<a href="javascript:void(0);" data-shortcode=\'[hfcm id="%s"]\'  class="hfcm_pro_copy_shortcode" id="hfcm_pro_copy_shortcode_%s">' . esc_html__( 'Copy Shortcode', '99robots-header-footer-code-manager-pro' ) . '</a>', absint( $item['script_id'] ), absint( $item['script_id'] ) ),
            'delete'    => sprintf( '<a href="?page=%s&action=%s&snippet=%s&_wpnonce=%s">' . esc_html__( 'Delete', '99robots-header-footer-code-manager-pro' ) . '</a>', $page, 'delete', absint( $item['script_id'] ), $delete_nonce ),
        );

        return $title . $this->row_actions( $actions );
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'script_id'    => esc_html__( 'ID', '99robots-header-footer-code-manager-pro' ),
            'status'       => esc_html__( 'Status', '99robots-header-footer-code-manager-pro' ),
            'name'         => esc_html__( 'Snippet Name', '99robots-header-footer-code-manager-pro' ),
            'display_on'   => esc_html__( 'Display On', '99robots-header-footer-code-manager-pro' ),
            'location'     => esc_html__( 'Location', '99robots-header-footer-code-manager-pro' ),
            'snippet_type' => esc_html__( 'Snippet Type', '99robots-header-footer-code-manager-pro' ),
            'device_type'  => esc_html__( 'Devices', '99robots-header-footer-code-manager-pro' ),
            'shortcode'    => esc_html__( 'Shortcode', '99robots-header-footer-code-manager-pro' ),
        );

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {

        return array(
            'name'      => array( 'name', true ),
            'location'  => array( 'location', true ),
            'script_id' => array( 'script_id', false ),
        );
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {

        return array(
            'bulk-activate'   => esc_html__( 'Activate', '99robots-header-footer-code-manager-pro' ),
            'bulk-deactivate' => esc_html__( 'Deactivate', '99robots-header-footer-code-manager-pro' ),
            'bulk-delete'     => esc_html__( 'Remove', '99robots-header-footer-code-manager-pro' ),
        );
    }

    /**
     * Add filters and extra actions above and below the table
     *
     * @param string $which Are the actions displayed on the table top or bottom
     */
    public function extra_tablenav( $which )
    {
        if ( 'top' === $which ) {
            $query            = isset( $_POST['snippet_type'] ) ? sanitize_text_field( $_POST['snippet_type'] ) : '';
            $nnr_selected_tag = isset( $_POST['nnr_selected_tag'] ) ? sanitize_text_field( $_POST['nnr_selected_tag'] ) : '';
            $snippet_type     = array(
                'html' => esc_html__( 'HTML', '99robots-header-footer-code-manager-pro' ),
                'css'  => esc_html__( 'CSS', '99robots-header-footer-code-manager-pro' ),
                'js'   => esc_html__( 'Javascript', '99robots-header-footer-code-manager-pro' ),
                'php'  => esc_html__( 'PHP', '99robots-header-footer-code-manager-pro' )
            );

            echo '<div class="alignleft actions">';
            echo '<select name="snippet_type">';
            echo '<option value="">' . esc_html__( 'All Snippet Types', '99robots-header-footer-code-manager-pro' ) . '</option>';

            foreach ( $snippet_type as $key_type => $type ) {
                if ( $key_type == $query ) {
                    echo '<option value="' . esc_attr( $key_type ) . '" selected>' . esc_html( $type ) . '</option>';
                } else {
                    echo '<option value="' . esc_attr( $key_type ) . '">' . esc_html( $type ) . '</option>';
                }
            }

            echo '</select>';

            global $wpdb;
            $nnr_hfcm_pro_tags_table = $wpdb->prefix . 'hfcm_pro_tags';

            $nnr_hfcm_pro_all_tags_array = $wpdb->get_results(
                "SELECT id, tag FROM `{$nnr_hfcm_pro_tags_table}`"
            );

            echo '<select name="nnr_selected_tag">';
            echo '<option value="">' . esc_html__( 'All Tags', '99robots-header-footer-code-manager-pro' ) . '</option>';

            foreach ( $nnr_hfcm_pro_all_tags_array as $nnr_key_tag => $nnr_item_tag ) {
                if ( $nnr_item_tag->id == $nnr_selected_tag ) {
                    echo '<option selected value="' . esc_attr( $nnr_item_tag->id ) . '">' . esc_html( $nnr_item_tag->tag ) . '</option>';
                } else {
                    echo '<option value="' . esc_attr( $nnr_item_tag->id ) . '">' . esc_html( $nnr_item_tag->tag ) . '</option>';
                }
            }
            echo '</select>';
            submit_button( __( 'Filter', '99robots-header-footer-code-manager-pro' ), 'button', 'filter_action', false );
            echo '</div>';
        }

        echo '<div class="alignleft actions">';


        echo '</div>';
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {

        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();

        // Retrieve $customvar for use in query to get items.
        $customvar = 'all';
        if ( !empty( $_GET['customvar'] ) ) {
            $customvar = sanitize_text_field( $_GET['customvar'] );
            if ( empty( $customvar ) || !in_array( $customvar, [ 'inactive', 'active', 'all' ] ) ) {
                $customvar = 'all';
            }
        }
        $this->_column_headers = array( $columns, $hidden, $sortable );

        /**
         * Process bulk action
         */
        $this->process_bulk_action();
        $this->views();
        $per_page     = $this->get_items_per_page( 'snippets_per_page', 20 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page'    => $per_page,
            )
        );

        $this->items = self::get_snippets( $per_page, $current_page, $customvar );
    }

    public function get_views()
    {
        $views   = array();
        $current = 'all';
        if ( !empty( $_GET['customvar'] ) ) {
            $current = sanitize_text_field( $_GET['customvar'] );
        }

        //All link
        $class        = 'all' === $current ? 'current' : '';
        $all_url      = remove_query_arg( 'customvar' );
        $views['all'] = '<a href="' . esc_attr( $all_url ) . '" class="' . esc_attr( $class ) . '">' . esc_html__( 'All', '99robots-header-footer-code-manager-pro' ) . ' (' . esc_html__( $this->record_count() ) . ')</a>';

        //Foo link
        $foo_url         = add_query_arg( 'customvar', 'active' );
        $class           = ('active' === $current ? 'current' : '');
        $views['active'] = '<a href="' . esc_attr( $foo_url ) . '" class="' . esc_attr( $class ) . '">' . esc_html__( 'Active', '99robots-header-footer-code-manager-pro' ) . ' (' . esc_html__( $this->record_count( 'active' ) ) . ')</a>';

        //Bar link
        $bar_url           = add_query_arg( 'customvar', 'inactive' );
        $class             = ('inactive' === $current ? 'current' : '');
        $views['inactive'] = '<a href="' . esc_attr( $bar_url ) . '" class="' . esc_attr( $class ) . '">' . esc_html__( 'Inactive', '99robots-header-footer-code-manager-pro' ) . ' (' . esc_html__( $this->record_count( 'inactive' ) ) . ')</a>';

        return $views;
    }

    public function process_bulk_action()
    {
        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

            if ( !wp_verify_nonce( $nonce, 'hfcm_pro_delete_snippet' ) ) {
                die( 'Go get a life script kiddies' );
            } else {
                if ( !empty( $_GET['snippet'] ) ) {
                    $snippet_id = absint( $_GET['snippet'] );
                    if ( !empty( $snippet_id ) ) {
                        self::delete_snippet( $snippet_id );
                    }
                }

                NNR_HFCM_PRO::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-list' ) );
                return;
            }
        }

        // If the delete bulk action is triggered
        if ( (isset( $_POST['action'] ) && 'bulk-delete' === $_POST['action'])
            || (isset( $_POST['action2'] ) && 'bulk-delete' === $_POST['action2'])
        ) {
            if ( check_admin_referer( 'bulk-snippets' ) ) {
                $bulk_snippets = $_POST['snippets'];

                // loop over the array of record IDs and delete them
                foreach ( $bulk_snippets as $id ) {
                    $id = absint( $id );
                    if ( !empty( $id ) && is_int( $id ) ) {
                        self::delete_snippet( $id );
                    }
                }

                NNR_HFCM_PRO::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-list' ) );
                return;
            }
        } elseif ( (isset( $_POST['action'] ) && 'bulk-activate' === $_POST['action'])
            || (isset( $_POST['action2'] ) && 'bulk-activate' === $_POST['action2'])
        ) {
            if ( check_admin_referer( 'bulk-snippets' ) ) {
                $bulk_snippets = $_POST['snippets'];

                // loop over the array of record IDs and activate them
                foreach ( $bulk_snippets as $id ) {
                    $id = absint( $id );
                    if ( !empty( $id ) && is_int( $id ) ) {
                        self::activate_snippet( $id );
                    }
                }

                NNR_HFCM_PRO::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-list' ) );
                return;
            }
        } elseif ( (isset( $_POST['action'] ) && 'bulk-deactivate' === $_POST['action'])
            || (isset( $_POST['action2'] ) && 'bulk-deactivate' === $_POST['action2'])
        ) {

            if ( check_admin_referer( 'bulk-snippets' ) ) {
                $bulk_snippets = $_POST['snippets'];
                // loop over the array of record IDs and deactivate them
                foreach ( $bulk_snippets as $id ) {
                    $id = absint( $id );
                    if ( !empty( $id ) && is_int( $id ) ) {
                        self::deactivate_snippet( $id );
                    }
                }

                NNR_HFCM_PRO::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-list' ) );

                return;
            }
        }
    }

    /**
     * Displays the search box.
     *
     * @param string $text The 'submit' button label.
     * @param string $input_id ID attribute value for the search input field.
     * @since 3.1.0
     */
    public function search_box( $text, $input_id )
    {
        if ( empty( $_REQUEST['s'] ) && !$this->has_items() ) {
            return;
        }
        $input_id = $input_id . '-search-input';
        ?>
        <p class="search-box">
            <label class="screen-reader-text"
                   for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $text ); ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s"
                   value="<?php esc_attr( _admin_search_query() ); ?>"/>
            <?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
        <?php
    }
}

