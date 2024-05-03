<?php
if (!class_exists('WP_List_Table') ) {
    include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Hfcm_Pro_Tags_List extends WP_List_Table
{

    /**
     * Class constructor
     */
    public function __construct()
    {

        parent::__construct(
            array(
                'singular' => esc_html__('Tag', '99robots-header-footer-code-manager-pro'),
                'plural'   => esc_html__('Tags', '99robots-header-footer-code-manager-pro'),
                'ajax'     => false,
            )
        );
    }

    /**
     * Retrieve tags data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_tags( $per_page = 20, $page_number = 1 )
    {

        global $wpdb;
        $table_name  = "{$wpdb->prefix}hfcm_pro_tags";
        $page_number = absint($page_number);
        $per_page    = absint($per_page);
        $orderby     = 'id';
        $order       = 'ASC';

        if (!empty($_GET['orderby']) ) {
            $orderby = sanitize_sql_orderby($_GET['orderby']);
            if (empty($orderby) || !in_array($orderby, array( 'id', 'tag' )) ) {
                $orderby = 'id';
            }
        }
        if (!empty($_GET['order']) ) {
            $order = strtolower(sanitize_sql_orderby($_GET['order']));
            if (empty($order) || !in_array($order, array( 'desc', 'asc' )) ) {
                $order = 'ASC';
            }
        }

        $sql = "SELECT * FROM `{$table_name}` WHERE 1";
        $placeholder_args = [];

        if (!empty($_POST['s']) ) {
            $search_query       = addslashes(sanitize_text_field($_POST['s']));
            $sql                .= " AND name LIKE %s";
            $placeholder_args[] = '%' . $search_query . '%';
        }

        $sql .= ' ORDER BY %s %s LIMIT %d OFFSET %d';

        $placeholder_args[] = $orderby;
        $placeholder_args[] = $order;
        $placeholder_args[] = $per_page;
        $placeholder_args[] = ($page_number - 1) * $per_page;

        if (!empty($placeholder_args) ) {
            $sql = $wpdb->prepare($sql, $placeholder_args);
        }

        $result = $wpdb->get_results($sql, 'ARRAY_A');
        return $result;
    }

    /**
     * Delete a tag record.
     *
     * @param int $id tag ID
     */
    public static function delete_tag( $id )
    {
        $id = absint($id);
        if (empty($id) ) {
            return;
        }

        global $wpdb;
        $table_name = "{$wpdb->prefix}hfcm_pro_tags";

        $wpdb->delete(
            $table_name, array( 'id' => $id ), array( '%d' )
        );
        $table_name = "{$wpdb->prefix}hfcm_pro_snippet_tag_map";

        $wpdb->delete(
            $table_name, array( 'tag_id' => $id ), array( '%d' )
        );
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {

        global $wpdb;
        $table_name = "{$wpdb->prefix}hfcm_pro_tags";
        $sql        = "SELECT COUNT(*) FROM `{$table_name}`";

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when no tag data is available
     */
    public function no_items()
    {
        esc_html_e('No Tags available.', '99robots-header-footer-code-manager-pro');
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array  $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name )
    {

        switch ( $column_name ) {
        case 'tag':
            return esc_html($item[ $column_name ]);
        case 'id':
            return esc_html($item[ $column_name ]);
        default:
            return print_r($item, true); // Show the whole array for troubleshooting purposes
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
            '<input type="checkbox" name="tags[]" value="%s" />', $item['id']
        );
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_tag( $item )
    {

        $delete_nonce = wp_create_nonce('hfcm_pro_delete_tag');
        $edit_nonce   = wp_create_nonce('hfcm_pro_edit_tag');

        $title = '<strong>' . esc_html($item['tag']) . '</strong>';

        $page = sanitize_text_field($_GET['page']);

        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">' . esc_html__('Edit', '99robots-header-footer-code-manager-pro') . '</a>', esc_attr('hfcm-pro-tag-update'), 'edit', absint($item['id']), $edit_nonce),
            'delete'    => sprintf('<a href="?page=%s&action=%s&tag=%s&_wpnonce=%s">' . esc_html__('Delete', '99robots-header-footer-code-manager-pro') . '</a>', $page, 'delete', absint($item['id']), $delete_nonce),
        );

        return $title . $this->row_actions($actions);
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb'     => '<input type="checkbox" />',
            'id'     => esc_html__('ID', '99robots-header-footer-code-manager-pro'),
            'tag'    => esc_html__('Tag Name', '99robots-header-footer-code-manager-pro'),
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
            'tag' => array( 'tag', true ),
            'id'  => array( 'id', false ),
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
            'bulk-delete'     => esc_html__('Remove', '99robots-header-footer-code-manager-pro'),
        );
    }

    /**
     * Add filters and extra actions above and below the table
     *
     * @param string $which Are the actions displayed on the table top or bottom
     */
    public function extra_tablenav( $which )
    {
        if ('top' === $which ) {

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

        $this->_column_headers = array( $columns, $hidden, $sortable );

        /**
         * Process bulk action
        */
        $this->process_bulk_action();
        $this->views();
        $per_page     = $this->get_items_per_page('tags_per_page', 20);
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args(
            array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            )
        );

        $this->items = self::get_tags($per_page, $current_page);
    }

    public function get_views()
    {
        $views   = array();
        return $views;
    }

    public function process_bulk_action()
    {
        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = sanitize_text_field($_REQUEST['_wpnonce']);

            if (!wp_verify_nonce($nonce, 'hfcm_pro_delete_tag') ) {
                die('Go get a life script kiddies');
            } else {
                if (!empty($_GET['tag']) ) {
                    $tag_id = absint($_GET['tag']);
                    if (!empty($tag_id) ) {
                        self::delete_tag($tag_id);
                    }
                }

                NNR_HFCM_PRO::hfcm_pro_redirect(admin_url('admin.php?page=hfcm-pro-tags-list'));
                return;
            }
        }

        // If the delete bulk action is triggered
        if ((isset($_POST['action']) && 'bulk-delete' === $_POST['action'])
            || (isset($_POST['action2']) && 'bulk-delete' === $_POST['action2'])
        ) {
            if ( check_admin_referer( 'bulk-tags' ) ) {
                $bulk_tags = $_POST['tags'];

                // loop over the array of record IDs and delete them
                foreach ( $bulk_tags as $id ) {
                    $id = absint( $id );
                    if ( !empty( $id ) && is_int( $id ) ) {
                        self::delete_tag( $id );
                    }
                }

                NNR_HFCM_PRO::hfcm_pro_redirect( admin_url( 'admin.php?page=hfcm-pro-tags-list' ) );
                return;
            }
        }
    }

    /**
     * Displays the search box.
     *
     * @param string $text     The 'submit' button label.
     * @param string $input_id ID attribute value for the search input field.
     * @since 3.1.0
     */
    public function search_box( $text, $input_id )
    {
        if (empty($_REQUEST['s']) && !$this->has_items() ) {
            return;
        }

        $input_id = $input_id . '-search-input';
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo esc_html($text); ?>:</label>
            <input type="search" id="<?php echo esc_attr($input_id); ?>" name="s"
                   value="<?php esc_attr(_admin_search_query()); ?>"/>
            <?php submit_button($text, '', '', false, array( 'id' => 'search-submit' )); ?>
        </p>
        <?php
    }
}

