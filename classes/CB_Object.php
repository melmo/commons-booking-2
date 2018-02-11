<?php
/**
 * Base class, template for other classes
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Shared Interface for queries.
 */
class CB_Object {
	/**
	 * DB or other error messages
	 *
	 * @var array
	 */
	public $error_messages = array();
	/**
	 * User-facing messages
	 *
	 * @var array
	 */
	public $message = '';
	/**
	 * Settings
	 *
	 * @var array
	 */
  public static $settings = array();
	/**
	 * Formatted sql conditions for timeframes query
	 *
	 * @var array
	 */
	// private $sql_conditions_timeframes = array();
	/**
	 * Formatted sql conditions for slots/bookings query
	 *
	 * @var array
	 */
	// private $sql_conditions_slots_bookings = array();
	/**
	 * default query args
	 *
	 * @var array
	 */
	// private $default_query_args = array();
	/**
	 * array holding the timeframe objects // context: timeframes
	 *
	 * @var array
	 */
	// public $timeframes_array = array();
	/**
	 * array holding the timeframe objects // context: calendar
	 *
	 * @var array
	 */
	// public $calendar;
	/**
	 * Merged query args.
	 *
	 * @var array
	 */
  public $query_args;
	/**
	 * merged query args
	 *
	 * @var array
	 */
    // var $calendar_filter = FALSE;
	/**
	 * weekday names
	 *
	 * @var array
	 */
    public $today;
	/**
	 * context
	 *
	 * @var array
	 */
    public $context;
	/**
	 * gui
	 *
	 * @var object
	 */
    public $gui;
	/**
	 * Prefix & Table names
	 *	 */
		// var $db_prefix;
    // var $timeframes_table;
    // var $slots_table;
    // var $bookings_table;
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
    protected static $instance = null;
	/**
	 * Initialize the class
	 */
	public static function initialize() {

		if ( !apply_filters( 'commons_booking_cb_object_initialize', true ) ) {
			return;
		}
	}
	/**
	 * Init base variables
	 *
   * Setup db table names
	 * Set today
	 */
	public function do_setup( ) {

		$this->set_default_query_args();

		if ( ! ( $this->context ) ) {
			$this->set_context('timeframe');
		}
	}
	/**
	 * Return default query parameters merged with user args
	 *
	 * @param array $array
	 * @return array merged query params
	 */
	public function merge_query_args( $args ){

		//Return default query if nothing passed
		if ( empty( $args ) ) {
			return $this->default_query_args;
        } else {
					$query = array_merge( $this->default_query_args, $args );
        }
		return apply_filters('cb_object_merge_query_args', $query );

	}
	/**
	 * Set today
	 *
	 * @param string $date
	 */
	public function set_today( $date ){

		$today = date( 'Y-m-d', strtotime( $date ) );

		if( $today ) {
			$this->today = $today;
		}	else {
			$this->today = date( 'Y-m-d' );
		}
	}
	/**
	 * Set default query args
	 */
	public function set_default_query_args( ){

		$this->default_query_args = array(
			// scope of the timeframe results, slots are queried accordingly
			'scope' 				=> 'current',	// STRING current, past
			'today'					=> 'today',		// STRING current date parseable with strtotime().
			'cal_limit' 		=> false, 		// BOOL or INT return only x days of a timeframe (from $today).
			// order the timeframe results, slots are ordered by slot_order field
      'orderby' 			=> 'date_start',		// STRING order the timeframe results, slots are ordered by slot_order field
      'order' 				=> 'ASC',						// STRING
			// limit
			'limit' 				=> false,	// INT 	how many timeframes to return
			'offset'				=> false, // INT 	how many timeframes to return
			// cb object
      'timeframe_id' 	=> false,	// ARRAY	query by timeframe id
      'owner_id' 			=> false,	// ARRAY	query by the user that created the timeframe
			// wordpress post types
      'location_id' 	=> false,	// ARRAY	query by location_id
			'item_id' 			=> false,	// ARRAY	query by item_id
			// location
			'location_cat' 	=> '',		// ARRAY 	query by location categories
			// item
			'item_cat' 			=> '',			// ARRAY 	query by item categories
			// query by user / booking id
      'user_id'				=> false, 		// INT 		query by id of the user that made the booking
			'booking_id'		=> false, 		// INT 		query by id of the booking
			'slot_id'				=> false,			// INT 		query by id of the slot
			// geo
			'city'					=> false, 		// STRING	only retrieve timeframes mapped to a location in city @TODO
			// availability filters
			'has_bookings'  => false, 	// BOOL 	only retrieve days with slots that are booked @TODO
			'has_open_slots'=> false, 	// BOOL 	only retrieve days with slots that can be booked @TODO
			'discard_empty' => false,		// BOOL		days without slots will not be retrieved
			'include_booking' => TRUE		// BOOL		include booking information
		);
	}
	/**
	 * Construct SQL query to retrieve timeframes
	 *
	 * @return array sql conditions
	 */
	public function build_sql_conditions_timeframes( ) {

		global $wpdb;

		$args = $this->query_args;

		$this->set_today($args['today']);

		$sql_conditions = array();

				// array of table row names the return
		$sql_fields_timeframe = array (
			$wpdb->prefix . CB_TIMEFRAMES_TABLE . '.timeframe_id',
			$wpdb->prefix . CB_TIMEFRAMES_TABLE . '.location_id',
			$wpdb->prefix . CB_TIMEFRAMES_TABLE . '.item_id',
			$wpdb->prefix . CB_TIMEFRAMES_TABLE . '.set_id',
			$wpdb->prefix . CB_TIMEFRAMES_TABLE . '.date_start',
			$wpdb->prefix . CB_TIMEFRAMES_TABLE . '.date_end',
			$wpdb->prefix . CB_TIMEFRAMES_TABLE . '.description'
		);

		$sql_conditions['SELECT'] = $sql_fields_timeframe;

		// date & sorting
		if ( $args['scope'] == 'current') {
			$sql_conditions['WHERE'][] = sprintf('date_end >= CAST("%s" AS DATE)', $this->today);
		} elseif ( $args['scope'] == 'past') {
			$sql_conditions['WHERE'][] = sprintf('date_end <= CAST("%s" AS DATE)', $this->$today);
		}
		// select by id: timeframe
		if ( $args['timeframe_id'] && is_numeric( $args['timeframe_id'] ) ) {
			$sql_conditions['WHERE'][] = sprintf(' timeframe_id = %d', $args['timeframe_id'] );
		}
		// select by id: location
		if ( $args['location_id'] && is_numeric( $args['location_id'] ) ) {
			$sql_conditions['WHERE'][] = sprintf(' location_id = %d', $args['location_id'] );
		}
		// select by id: item
		if ( $args['item_id'] && is_numeric( $args['item_id'] ) ) {
			$sql_conditions['WHERE'][] = sprintf(' item_id = %d', $args['item_id'] );
		}

		// select by item taxonomy @TODO: seems buggy.
		if ( $args['item_cat'] && term_exists( $args['item_cat'], 'item-category' ) ) {
			$sql_conditions['SELECT'][] = 't.term_id, t.name as taxonomy_name';
			$sql_conditions['JOIN'][] = sprintf('
			LEFT JOIN %sterm_relationships AS tr ON (item_id = tr.object_id)
			LEFT JOIN %sterm_taxonomy AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
			LEFT JOIN %sterms AS t ON (t.term_id = tt.term_id)',
			$wpdb->prefix,
			$wpdb->prefix,
			$wpdb->prefix
		);
			$sql_conditions['WHERE'][] = sprintf( ' tt.term_id = %d', $args['item_cat'] );
		}
		// select by location taxonomy
		if ( $args['location_cat'] && term_exists( $args['location_cat'], 'location-category' ) ) {
			$sql_conditions['SELECT'][] = 't.term_id, t.name as taxonomy_name';
			$sql_conditions['JOIN'][] = sprintf('
			LEFT JOIN %sterm_relationships AS tr ON (location_id = tr.object_id)
			LEFT JOIN %sterm_taxonomy AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
			LEFT JOIN %sterms AS t ON (t.term_id = tt.term_id)',
			$wpdb->prefix,
			$wpdb->prefix,
			$wpdb->prefix
		);
			$sql_conditions['WHERE'][] = sprintf( ' tt.term_id = %d', $args['location_cat'] );
		}
		// order by fields
		if ( ( $args['orderby'] ) && ( $args['order'] ) ) {
			if ( in_array( $args['orderby'], array ( 'timeframe_id', 'date_start', 'date_end' ) ) && in_array( $args['order'], array( 'ASC', 'DESC' ) ) ) {
				$sql_conditions['SQLORDER'] = sprintf (" ORDER BY %s.%s %s", $wpdb->prefix . CB_TIMEFRAMES_TABLE, $args['orderby'], $args['order'] );
			}
		}
		//limit
		if ( ( $args['limit'] ) && is_numeric( $args['limit'] ) ) {
			$sql_conditions['LIMIT'] = sprintf (" LIMIT %d ", $args['limit'] );
		}
		if ( ( $args['offset'] ) && is_numeric( $args['offset'] ) ) {
			$sql_conditions['OFFSET'] = sprintf (" OFFSET %d ", $args['offset'] );
		}
		return $sql_conditions;
	}
	/**
	 * Construct SQL query to retrieve slots & bookings
	 *
	 * @return array sql conditions
	 */
	public function build_sql_conditions_slots_bookings( $args ) {

		global $wpdb;

		$tf_args = $this->query_args; // master query args
		$sql_conditions_slots_bookings = array(); // array holding the sql conditions

		$timeframes_table = $wpdb->prefix . CB_TIMEFRAMES_TABLE;
		$slots_table 	= $wpdb->prefix . CB_SLOTS_TABLE;
		$bookings_table = $wpdb->prefix . CB_BOOKINGS_TABLE;
		$slots_bookings_relation_table = $wpdb->prefix . CB_SLOTS_BOOKINGS_REL_TABLE;

		// array of table column names to return
		$sql_fields_slots = array (
			$slots_table . '.slot_id AS slot_id',
			$slots_table . '.timeframe_id',
			$slots_table . '.date',
			$slots_table . '.time_start',
			$slots_table . '.time_end',
			$slots_table . '.description',
			$slots_table . '.booking_code',
			$bookings_table . '.booking_status',
			$bookings_table . '.user_id',
			$timeframes_table . '.item_id',
			$timeframes_table . '.location_id',
			$slots_bookings_relation_table . '.slot_id',
			$slots_bookings_relation_table . '.booking_id',
		);

		$sql_conditions_slots_bookings['SELECT'] = $sql_fields_slots;

		// Select by timeframe
		if ( $args['timeframe_id'] && is_array( $args['timeframe_id'] ) ) {
			$timeframe_ids = implode (',', $args['timeframe_id'] );
			$sql_conditions_slots_bookings['WHERE'][] =  sprintf(' %s.timeframe_id IN (%s)', $slots_table, $timeframe_ids );
		}

		// Select by booking user id
		if ( $tf_args['user_id'] && is_numeric( $tf_args['user_id'] ) ) {
			$sql_conditions_slots_bookings['WHERE'][] = sprintf(' %s.user_id = %d', $bookings_table,  $args['user_id'] );
		}
		// Select by booking id
		if ( $tf_args['booking_id'] && is_numeric( $tf_args['booking_id'] ) ) {
			$sql_conditions_slots_bookings['WHERE'][] = sprintf(' %s.booking_id = %d', $bookings_table, $tf_args['booking_id'] );
		}
		// Select by date
		if ( $args['date_start'] && empty( $args['date_end'] ) ) {
			$sql_conditions_slots_bookings['WHERE'][] =  sprintf(' %s.date >= CAST("%s" AS DATE)', $slots_table, $args['date_start'] );
		} elseif ( empty( $args['date_start'] ) && $args['date_end'] ) {
			$sql_conditions_slots_bookings['WHERE'][] =  sprintf(' %s.date <= CAST("%s" AS DATE)', $slots_table, $args['date_end'] );
		} elseif ( $args['date_start'] && $args['date_end'] ) {
			$sql_conditions_slots_bookings['WHERE'][] =  sprintf(' %s.date BETWEEN CAST("%s" AS DATE) AND CAST("%s" AS DATE)', $slots_table, $args['date_start'], $args['date_end'] );
		}
		// Filter: Retrieve only booked slots
		if ( $tf_args['has_bookings'] ) {
			$sql_conditions_slots_bookings['WHERE'][] = sprintf(' %s.booking_id IS NOT NULL AND %s.booking_status = "BOOKED"', $slots_bookings_relation_table, $slots_bookings_relation_table);
		}
		// Filter: Retrieve only available slots
		if ( $tf_args['has_open_slots'] ) {
			$sql_conditions_slots_bookings['WHERE'][] = sprintf(' %s.booking_id IS NULL OR %s.booking_status != "BOOKED"', $bookings_table, $bookings_table);
		}
		return $sql_conditions_slots_bookings;
	}
	/**
	 * Get timeframes
	 * @param array $args
	 * @return array
	 */
	public function get_timeframes( $args = array() ) {

		$this->do_setup();

		$this->query_args = $this->merge_query_args( $args ); // user supplied arguments and defaults
		$conditions_timeframes = $this->build_sql_conditions_timeframes();
		$timeframe_results = $this->do_sql_timeframes( $conditions_timeframes );

		if( $timeframe_results ) { // timeframes matching the initial query

			$slot_query_args = array(); // array to hold our slot query args

			if ( $this->context == 'timeframe' ) { // loop through timeframes, map slots to each timeframe´s calendar
				foreach ( $timeframe_results as $timeframe_result ) {

					// Create new calendar object with an array of dates
					$timeframe_calendar = new CB_Calendar( $timeframe_result->timeframe_id, $this->today, $timeframe_result->date_end  );

					// set query args by parent timeframe
					$slot_query_args['timeframe_id'] =  (array) $timeframe_result->timeframe_id;
					$slot_query_args['date_start'] =  $this->today;
					$slot_query_args['date_end'] =  $timeframe_result->date_end;

					// get the slots
					$conditions_slots = $this->build_sql_conditions_slots_bookings( $slot_query_args );
					$slot_results = $this->do_sql_slots( $conditions_slots );



					// set the current objects´ availability count:
					$timeframe_result->availability = $this->set_timeframe_availability( $slot_results );

					// set the message
					$timeframe_result->message = '';
					if ( empty ( $slot_results ) ) {
						$timeframe_result->message = __('No slots found', 'commons-booking');
					}

						// merge calendar (days array) with slots array
						$timeframe_calendar->calendar = $this->map_slots_to_cal ( $timeframe_calendar->dates_array, $slot_results );

						$timeframe_result->calendar = $timeframe_calendar->calendar; // add calendar to the timeframe results object


						$this->timeframes_array[] = $timeframe_result;


				}
				// return an array of timeframes with their respective calendars
				return $this->timeframes_array;

			} elseif ( $this->context == 'calendar' ) { // one calendar, slots mapped to dates

				// add additional query args from timeframe
				$slot_query_args['date_start'] = $this->today;
				$slot_query_args['date_end'] = date('Y-m-d', strtotime("+30 days")); //@TODO TODAY
				$slot_query_args['timeframe_id'] = array_column( $timeframe_results, 'timeframe_id');

				// get the slots
				$conditions_slots = $this->build_sql_conditions_slots_bookings( $slot_query_args );
				$slot_results = $this->do_sql_slots( $conditions_slots );

				// Create new calendar object with an array of dates
				$this->calendar = new CB_Calendar( FALSE, $slot_query_args['date_start'], $slot_query_args['date_end'] );

				// set the current objects´ availability count:
				$this->calendar->availability = $this->set_timeframe_availability( $slot_results );

				// set the message
				$this->calendar->message = '';
				if ( empty ( $slot_results ) ) {
					$this->calendar->message = __('No slots found', 'commons-booking');
				}

				// merge calendar (days array) with slots array @TODO: Apply filters
				$this->calendar->calendar = $this->map_slots_to_cal( $this->calendar->dates_array, $slot_results );

				// return an calendar object with an array of days and  all matching timeframes mapped to it
				return $this->calendar;

			} elseif ( $this->context = 'admin_table' ){

				return $timeframe_results;


			}// end if ( $this->context == 'timeframe' )

		} else { // no timeframes found

			return CB_Strings::throw_error( __FILE__,' no timeframes!' ); //@TODO: This will be shown to a front-end user. No dev "error",  use CB_guistrings (also, todo).
		}
	}
	/**
	 * Do an sql search for timeframes matching $query_args
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @return object timeframes
	 *
	 */
	public function do_sql_timeframes( $args ) {

		global $wpdb;

		$timeframes_table_name = $wpdb->prefix.CB_TIMEFRAMES_TABLE;

		if ( ! empty ( $args['WHERE'] ) ) {
			$conditions = implode ( $args['WHERE'], " AND " );
			$conditions = "WHERE ". $conditions;
		}

		if ( ! empty ( $args['SELECT'] ) ) {
			$select = implode ( $args['SELECT'], ',' );
		} else {
			$select = '*';
		}

		if ( ! empty ( $args['SQLORDER'] ) ) {
			$orderby = $args['SQLORDER'];
		} else {
			$orderby = '';
		}
		if ( ! empty ( $args['LIMIT'] ) ) {
			$limit = $args['LIMIT'];
		} else {
			$limit = '';
		}
		if ( ! empty ( $args['OFFSET'] ) ) {
			$offset = $args['OFFSET'];
		} else {
			$offset = '';
		}

		$timeframes = $wpdb->get_results(
		" SELECT {$select} FROM {$timeframes_table_name} {$conditions} {$orderby} {$limit} {$offset}"
		);

		return $timeframes;
	}
		/**
	 * Do an sql search for slots matching $slots_query_args
	 *
	 * @since 1.0.0
	 *
	 * @return object slots
	 *
	 */
	public function do_sql_slots( $args ) {

		global $wpdb;
		$slots_table = $wpdb->prefix . CB_SLOTS_TABLE ;
		$bookings_table = $wpdb->prefix . CB_BOOKINGS_TABLE;
		$timeframes_table = $wpdb->prefix . CB_TIMEFRAMES_TABLE;
		$slots_bookings_relation_table = $wpdb->prefix . CB_SLOTS_BOOKINGS_REL_TABLE;


		if ( ( $args['WHERE'] ) ) {
			$where = implode ( $args['WHERE'], " AND " );
			$where = "WHERE ". $where;
		}

		if ( ! empty ( $args['SELECT'] ) ) {
			$select = implode ( $args['SELECT'], ',' );
		} else {
			$select = '*';
		}
		// get the slots & bookings for the selected timeframe within the time limits
		$slots = $wpdb->get_results(
			" SELECT
				{$select}
				FROM {$slots_table}
				LEFT JOIN {$slots_bookings_relation_table} ON ({$slots_table}.slot_id={$slots_bookings_relation_table}.slot_id)
				LEFT JOIN {$bookings_table} ON ({$slots_bookings_relation_table}.booking_id = {$bookings_table}.booking_id)
				LEFT JOIN {$timeframes_table} ON ({$slots_table}.timeframe_id = {$timeframes_table}.timeframe_id)
				{$where}
				ORDER BY date", ARRAY_A
		);

		/**
		 * reformat the slot results to into the following array:
		 * 'slots' =>
		 *    5 => 														// timeframe id
		 *       1 => 												// slot_id
		 *          'slot_id' 			=> '1'    // slot properties..
		 * 					'timeframe_id' =>  '5'
		 * 					...
		 * 			 3 =>
		 *          'slot_id' 			=> '3'
		 * 					'timeframe_id' =>  '5'
		 * 					...
		 *    6 =>														// timeframe id
		 */
		$slots_reformated = array();
		foreach ( $slots as $key => $val ) {
			$slots_reformated[$val['date']]['slots'][$val['timeframe_id']][$val['slot_id']] = $val;
		}
		// var_dump($slots_reformatted);
		return $slots_reformated;

	}
	/**
	 * Map the slots array to the dates array, filter,  apply filters
	 *
	 * @since 1.0.0
	 *
	 * @param  	array $dates_array
	 * @param  	array $slots_array
	 * @return	array $calendar merged array
	 *
	 */
	public function map_slots_to_cal( $dates_array, $slots_array ) {

		$calendar = array_merge_recursive( $dates_array, $slots_array ); // merge calendar (days array) with slots array

		$filter_discard_empty = $this->query_args['discard_empty'];

		if ( $filter_discard_empty ) { // discard all days without slots
			$calendar  = array_intersect_key( $calendar, $slots_array );
		}
		return apply_filters('cb_object_map_slots_to_cal', $calendar );
	}
	/**
	 * Count slots as booked/available
	 *
	 * @since 1.0.0
	 *
	 * @param  array $slots_array
	 *
	 */
	public function set_timeframe_availability( $slots_array ) {

		$slots_count = 0;
		$slots_available_count = 0;
		$slots_booked_count = 0;

		foreach ( $slots_array as $day ) { // loop through days
			foreach ( $day[ 'slots' ] as $slots ) { // loop through slots
				foreach ( $slots as $slot ) {
					$slots_count++;
					if ( $slot[ 'booking_status' ] == NULL ) {
						$slots_available_count++;
					} elseif ( $slot[ 'booking_status' ] == 'BOOKED' ) {
						$slots_booked_count++;
					}
				}
			}
		}

		$slots_availability = array (
			'total' => $slots_count,
			'available' => $slots_available_count,
			'booked' => $slots_booked_count
		);

		return $slots_availability;
	}
	/**
	 * Set context @TODO
	 *
	 * @since 1.0.0
	 *
	 * @param string $context
	 *
	 */
	public function set_context( $context ) {
		$this->context = $context;
	}
	/**
	 * Get a setting from the options table
	 *
	 * @since 1.0.0
	 *
 	 * @param string $option_key_short short name for the option
 	 * @param string $field_id name of the field
	 * @return string the option
	 */
	public static function get_setting( $option_key_short, $field_id ) {

		$option_key = 'commons-booking-settings-' . $option_key_short;
		$serialized = get_option ( $option_key ); // all options in this section, serialized

		if ( $serialized && key_exists( $field_id, $serialized ) ) {
			return $serialized[$field_id];
		}
	}
	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			try {
				self::$instance = new self;
				self::initialize();
			} catch ( Exception $err ) {
				do_action( 'commons_booking_admin_failed', $err );
				if ( WP_DEBUG ) {
					throw $err->getMessage();
				}
			}
		}

		return self::$instance;
	}
	/**
	 * Error logging.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file
	 * @param string $error
	 *
	 */
	public function throw_error( $file, $error ){

		if( WP_DEBUG === true ) {
			printf ( 'Error: <strong>%s</strong> (%s)<br/> ', $error, $file );
		}
	}
	/**
	 * User Facing messages.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 *
	 */
	public function maybe_message( $message ){
		$gui = CB_Gui::get_instance();
		echo $gui->maybe_do_message( $message );
	}

}
add_action( 'plugins_loaded', array( 'CB_Object', 'get_instance' ) );
