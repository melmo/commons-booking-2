<?php
/**
 * Timeframes Table Class
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */

 /**
 * Custom_Table_Example_List_Table class that will display our custom table
 * records in nice table
 */
class CB_Timeframes_Table extends WP_List_Table
{
	public $Timeframes_Edit;
	public $timeframes_array;
	public $query_args;
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
				global $status, $page;

				$this->Timeframes_Edit = new CB_Timeframes_Edit();
				$this->timeframes_array = new CB_Object();
				$this->timeframes_array->set_context( 'admin-table' );

				parent::__construct( $this->Timeframes_Edit->names );



    }

    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    /**
     * Format col: date_start
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_date_start( $item ) {
			return $this->Timeframes_Edit->col_format_date( $item['date_start'] );
    }
    /**
     * Format col: date_end
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_date_end( $item ) {
			return $this->Timeframes_Edit->col_format_date( $item['date_end'] );
    }
    /**
     * Format col: user_id
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_owner_id( $item ) {
			return $this->Timeframes_Edit->col_format_user( $item['owner_id'] );
    }
    /**
     * Format col: availability
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_availability( $item ) {

			$html = sprintf ( __( '%d booked, %d available, %d total', 'commons-booking' ),
			$item['availability']['booked'],
			$item['availability']['available'],
			$item['availability']['total']
		);
		return $html;
    }
    /**
     * Format col: item_id
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_item_id($item)
    {
        return $this->Timeframes_Edit->col_format_post( $item['item_id'] );
    }
    /**
     * Format col: location_id
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_location_id( $item )
    {
        return $this->Timeframes_Edit->col_format_post( $item['location_id'] );
    }

    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_timeframe_id( $item ) {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &person=2
        $actions = array(
            'edit' => sprintf(
							'<a href="?page=cb_timeframes_edit&timeframe_id=%s">%s</a>',
							$item['timeframe_id'],
							__('Edit', 'commons-booking')),
            'delete' => sprintf('<a href="?page=%s&action=delete&timeframe_id=%s">%s</a>', $_REQUEST['page'], $item['timeframe_id'], __('Delete', 'commons-booking')),
        );

        return sprintf('%s %s',
            $item['timeframe_id'],
            $this->row_actions($actions)
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="timeframe_id[]" value="%s" />',
            $item['timeframe_id']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'timeframe_id' => __('ID', 'commons-booking'),
            'item_id' => __('Item', 'commons-booking'),
            'location_id' => __('Location', 'commons-booking'),
            'date_start' => __('Date Start', 'commons-booking'),
            'date_end' => __('Date End', 'commons-booking'),
            'owner_id' => __('Owner', 'commons-booking'),
						'description' => __('Description', 'commons-booking'),
						'availability' => __('Availability', 'commons-booking'),
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'booking_status' => array('booking_status', true),
            'date' => array('date', false),
            'item_id' => array('item_id', false),
            'location_id' => array('location_id', false),
            'user_id' => array('user_id', false),
        );
        return $sortable_columns;
    }

    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function process_bulk_action()
    {
        // global $wpdb;
        // $table_name = $wpdb->prefix . CB_Timeframes_TABLE; // do not forget about tables prefix

        // if ('delete' === $this->current_action()) {
        //     $ids = isset($_REQUEST['timeframe_id']) ? $_REQUEST['timeframe_id'] : array();
        //     if (is_array($ids)) $ids = implode(',', $ids);

        //     if (!empty($ids)) {
        //         $wpdb->query("DELETE FROM $table_name WHERE timeframe_id IN($ids)");
        //     }
        // }
		}

    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items(){

			// create new Timeframes Object
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->timeframes_array->get_timeframes_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
				$total_items = $this->timeframes_array->get_timeframes_row_count();

				$args = array (
					'per_page' => 99,
					'paged' => TRUE,
					'offset' => 0,
					'orderby' => 'date_start',
					'order' => 'ASC'
				);

				$timeframes_object = $this->timeframes_array->get_timeframes( $args );

				$this->items = json_decode(json_encode($timeframes_object), true); // convert to array

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $args['per_page'], // per page constant defined at top of method
            'total_pages' => ceil($total_items / $args['per_page']) // calculate pages count
        ));
    }
}
