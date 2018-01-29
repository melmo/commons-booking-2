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
	 * Fields
	 *
	 * @var array
	 */
    var $fields = array();
	/**
	 * Errors
	 *
	 * @var array
	 */
	var $errors = array();
	/**
	 * Settings
	 *
	 * @var array
	 */
    public static $settings = array();
	/**
	 * Formatted sql conditions 
	 *
	 * @var array
	 */
	private $sql_conditions_timeframes = array();
	/**
	 * Formatted sql conditions 
	 *
	 * @var array
	 */
	private $sql_conditions_slots = array();
	/**
	 * default query args 
	 *
	 * @var array
	 */

	public $default_query_args = array();
	/**
	 * supplied query args 
	 *
	 * @var array
	 */
	private $custom_query_args = array();
	/**
	 * supplied query args 
	 *
	 * @var array
	 */
	public $timeframes_array = array();
	/**
	 * supplied query args 
	 *
	 * @var array
	 */
	public $calendar_array = array();
	/**
	 * merged query args
	 *
	 * @var array
	 */
    public $query_args;
	/**
	 * merged query args
	 *
	 * @var array
	 */
    public $context = 'timeframe';
	/**
	 * merged query args
	 *
	 * @var array
	 */
    public $calendar_filter = FALSE;
	/**
	 * weekday names
	 *
	 * @var array
	 */
    public $today;
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
	 * Return query parameters
	 * 
	 * @param array $array
	 * @return array merged query params 
	 */
	public function merge_queries( $args ){

		$default_query_args = array(
			// date & sorting
			'scope' 		=> 'future',
			'day_limit' 	=> false, //@TODO: get this from settings
			'today'			=> 'today',
            'orderby' 		=> 'DATE',
            'order' 		=> 'ASC',
			// limit & pagination
            'limit' 		=> false,
			'offset'		=> 0,
            'page'			=> 1,
            'page_queryvar'	=> null,
            'pagination'	=> false,
			// cb object
            'timeframe_id' 	=> false,	// ARRAY	query by timeframe id
			// wordpress post types 
            'location_id' 	=> false,	// ARRAY	query by location_id
			'item_id' 		=> false,	// ARRAY	query by item_id
			// location 
			'location_cat' 	=> '',		// ARRAY 	query by location categories
			'location_tag' 	=> '',		// ARRAY 	query by location categories
			// item 
			'item_cat' 		=> '',		// ARRAY 	query by item categories
			'item_tag' 		=> '',		// ARRAY 	query by item categories
			// query by user / booking id
            'user_id'		=> false, 	// INT 		query by id of the user that made the booking
			'booking_id'	=> false, 	// INT 		query by id of the booking 
			'slot_id'		=> false,	// INT 		query by id of the slot
			// filters
			'has_slots'		=> false, 	// BOOL 	only retrieve days that have slots attached
			'has_bookings'  => false, 	// BOOL 	only retrieve days with slots that have bookings 
			'has_open_slots'=> false, 	// BOOL 	only retrieve days with slots that can be booked 

		);
        
		//Return default query if nothing passed		
		if( empty( $args ) ){
			return $default_query_args;
        } else {      
			$query = array_merge( $default_query_args, $args );
        }
		return apply_filters('cb_object_merge_queries', $query );
		
	}
	/**
	 * Construct SQL query to retrieve timeframes
	 * 
	 * @return array sql conditions 
	 */
	public function build_sql_conditions_timeframes( ) {

		global $wpdb;

		$args = $this->query_args;
		$this->today = date('Y-m-d');

		$sql_conditions = array();
		
		// date & sorting
		if ( $args['scope'] == 'future' ) {
			$sql_conditions[] = sprintf('date_end >= CAST("%s" AS DATE)', $this->today);
			
		} elseif ( $args['scope'] == 'past') {
			$sql_conditions[] = sprintf('date_end <= CAST("%s" AS DATE)', $this->$today);
		} 
		// filter by ids
		if ( $args['timeframe_id'] && is_numeric( $args['timeframe_id'] ) ) {
			$sql_conditions[] = sprintf(' timeframe_id = %d', $args['timeframe_id'] );	
		} 
		if ( $args['location_id'] && is_numeric( $args['location_id'] ) ) {
			$sql_conditions[] = sprintf(' location_id = %d', $args['location_id'] );
		}
		if ( $args['item_id'] && is_numeric( $args['item_id'] ) ) {
			$sql_conditions[] = sprintf(' item_id = %d', $args['item_id'] );
		}
		//limit @TODO
		if ( ( $args['limit'] ) && is_numeric( $args['limit'] ) ) {
			$sql_conditions['limit'] = sprintf (" LIMIT %d ", $args['limit'] );
		}

		return $sql_conditions;
	}
	/**
	 * Construct SQL query to retrieve slots & bookings
	 * 
	 * @return array sql conditions 
	 */
	public function build_sql_conditions_slots( $args ) {
		
		global $wpdb; 

		$tf_args = $this->query_args; // master query args 
		$sql_conditions_slots = array(); // array holding the sql conditions

		$slots_table 	= $wpdb->prefix . CB_SLOTS_TABLE;
		$bookings_table = $wpdb->prefix . CB_BOOKINGS_TABLE;

		// array of table row names the return
		$sql_fields_slots = array (
			$slots_table . '.slot_id AS slot_id', 
			$slots_table . '.timeframe_id', 
			$slots_table . '.date', 
			$slots_table . '.time_start', 
			$slots_table . '.time_end', 
			$slots_table . '.description', 
			$slots_table . '.booking_code', 
			$bookings_table . '.booking_status', 
			$bookings_table . '.user_id'
		);

		$sql_conditions_slots['select'] = $sql_fields_slots;

		// filter by timeframe
		if ( $args['timeframe_id'] && is_array( $args['timeframe_id'] ) ) {
			$timeframe_ids = implode (',', $args['timeframe_id'] );
			$sql_conditions_slots['WHERE'][] =  sprintf(' %s.timeframe_id IN (%s)', $slots_table, $timeframe_ids );	
		} 

		// Filter by booking, user, etc. 
		if ( $tf_args['user_id'] && is_numeric( $tf_args['user_id'] ) ) {
			$sql_conditions_slots['WHERE'][] = sprintf(' %s.user_id = %d', $bookings_table,  $args['user_id'] );
		}
		if ( $tf_args['booking_id'] && is_numeric( $tf_args['booking_id'] ) ) {
			$sql_conditions_slots['WHERE'][] = sprintf(' %s.booking_id = %d', $bookings_table, $tf_args['booking_id'] );
		}
		
		// filter by date
		if ( $args['date_start'] && empty( $args['date_end'] ) ) {
			$sql_conditions_slots['WHERE'][] =  sprintf(' %s.date >= CAST("%s" AS DATE)', $slots_table, $args['date_start'] );	
		} elseif ( empty( $args['date_start'] ) && $args['date_end'] ) {
			$sql_conditions_slots['WHERE'][] =  sprintf(' %s.date <= CAST("%s" AS DATE)', $slots_table, $args['date_end'] );	
		} elseif ( $args['date_start'] && $args['date_end'] ) {
			$sql_conditions_slots['WHERE'][] =  sprintf(' %s.date BETWEEN CAST("%s" AS DATE) AND CAST("%s" AS DATE)', $slots_table, $args['date_start'], $args['date_end'] );	
		}

		return $sql_conditions_slots;
	}
	/**
	 * Get query args
	 * @param array $args
	 * @return array  
	 */
	public function get_query_args(  ) {
		return $this->query_args;
	}
	/**
	 * Get timeframes
	 * @param array $args
	 * @return array  
	 */
	public function get_timeframes( $args = array() ) {
		
		$this->query_args = $this->merge_queries( $args ); // user supplied arguments and defaults
		$conditions_timeframes = $this->build_sql_conditions_timeframes();		
		$timeframe_results = $this->do_sql_timeframes( $conditions_timeframes );

		if( $timeframe_results ) { // tf found

			$slot_query_args = array(); // array to hold our slot query args
			
			if ( $this->context == 'timeframe' ) { // loop through timeframes, map slots to each timeframe´s calendar

				foreach ( $timeframe_results as $timeframe_result ) {
					
					$timeframe_calendar = new CB_Calendar( $timeframe_result->timeframe_id, $timeframe_result->date_start, $timeframe_result->date_end  );					
					
					// set query args by parent timeframe
					$slot_query_args['timeframe_id'] =  (array) $timeframe_result->timeframe_id;					
					$slot_query_args['date_start'] =  $timeframe_result->date_start;					
					$slot_query_args['date_end'] =  $timeframe_result->date_end;	
					
					// get the slots				
					$conditions_slots = $this->build_sql_conditions_slots( $slot_query_args );
					$slot_results = $this->do_sql_slots( $conditions_slots );
										
					// merge calendar (days array) with slots array		
					$timeframe_calendar->calendar = $this->map_slots_to_cal_filtered ( $timeframe_calendar->dates_array, $slot_results );

					$timeframe_result->calendar = $timeframe_calendar->calendar; // add calendar to the timeframe results object
					
					$this->timeframes_array[] = $timeframe_result;					
				} 

				return $this->timeframes_array;
								
			} elseif ( $this->context == 'calendar' ) { // one calendar, slots mapped to dates
					
				// add additional query args from timeframe
				$slot_query_args['date_start'] = $this->today;
				$slot_query_args['date_end'] = date('Y-m-d', strtotime("+30 days"));
				$slot_query_args['timeframe_id'] = array_column( $timeframe_results, 'timeframe_id');
				
				// get the slots				
				$conditions_slots = $this->build_sql_conditions_slots( $slot_query_args );
				$slot_results = $this->do_sql_slots( $conditions_slots );				
				
				$calendar = new CB_Calendar( FALSE, $slot_query_args['date_start'], $slot_query_args['date_end'] );

				$calendar->calendar = $this->map_slots_to_cal_filtered( $calendar->dates_array, $slot_results );

				return $calendar;

			}

		} else { // no timeframes found
			
			return CB_Strings::throw_error( __FILE__,' no timeframes!' );
		}

	}

	/**
	 * Do an sql search for timeframes matching $query_args   
	 * 
	 * @since 1.0.0
	 * 
	 * @return object timeframes
	 * 
	 */
	public function do_sql_timeframes( $args ) {

		global $wpdb;
		$timeframes_table_name = CB_TIMEFRAMES_TABLE;

		if ( ! empty ( $args ) ) {			
			$conditions = implode ( $args, " AND " );
			$conditions = "WHERE ". $conditions;
		}
				
		$timeframes = $wpdb->get_results(
			" SELECT * FROM {$wpdb->prefix}{$timeframes_table_name} {$conditions}"  			
		);
		return $timeframes;
	}
	/**
	 * Do an sql search for timeframes matching $query_args, apply filters 
	 * 
	 * @since 1.0.0
	 * 
	 * @return object timeframes
	 * 
	 */
	public function map_slots_to_cal_filtered( $dates_array, $slots_array ) {
		
		// merge calendar (days array) with slots array		
		$calendar = array_merge_recursive( $dates_array, $slots_array );

		$filter_has_slots = $this->query_args['has_slots'];
		
		if ( $filter_has_slots ) { // return only days that have slots
			$calendar  = array_intersect_key( $calendar, $slots_array );
		} 
		return $calendar;
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
		$slots_table_name = $wpdb->prefix . CB_SLOTS_TABLE;
		$bookings_table_name = $wpdb->prefix . CB_BOOKINGS_TABLE;

		if ( ( $args['WHERE'] ) ) {			
			$where = implode ( $args['WHERE'], " AND " );
			$where = "WHERE ". $where;
		}
		
		if ( ! empty ( $args['select'] ) ) {			
			$select = implode ( $args['select'], ',' );
		} else {
			$select = '*';
		}
		// get the slots & bookings for the selected timeframe within the time limits
		$slots = $wpdb->get_results(
			" SELECT 
				{$select}
				FROM {$slots_table_name}
				LEFT JOIN {$bookings_table_name} ON ({$slots_table_name}.slot_id = {$bookings_table_name}.slot_id)
				{$where}
				ORDER BY date", ARRAY_A
		);

		
		$slots_reordered = array();
		foreach ( $slots as $key => $val ) {
			$slots_reordered[$val['date']]['slots'][$val['slot_id']] = $val;
		}
		return $slots_reordered;

	}
	/**
	 * Set context   
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
	 * Do query   
	 * 
	 * @since 1.0.0
	 * 
	 * @param string $table_name sql table name 
	 * @param string $key mysql row
	 * @param string $condition
	 * @param string $val mysql val  
	 * 
	 */
	public function do_sql_query( ) {


	}
	/**
	 * Helper: Return table name prefixed   
	 * 
	 * @since 1.0.0
	 * 
	 * @param string $table_name 
	 * @return string  
	 * 
	 */
	public function get_table_prefixed( $table_name ) {
		global $wpdb;
		$table_prefixed = $wpdb->prefix . $table_name;
		return $table_prefixed;
	}

	/**
	 * Returns the id of a particular object in the table it is stored, be it Item (event_id), Location (location_id), Tag, Booking etc.
     *
	 * @since 1.0.0
	 *
     * @return int 
	 */
	function get_the_id(){
	    switch( get_class( $this ) ){
	        case 'CB_Item':
	            return $this->item_id;
	        case 'CB_Location':
	            return $this->location_id;
	        case 'CB_Timeframe':
	            return $this->timeframe_id;
	        case 'CB_Slot':
	            return $this->slot_id;
	        case 'CB_Booking':
	            return $this->booking_id;
	    }
	    return 0;
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

}
add_action( 'plugins_loaded', array( 'CB_Object', 'get_instance' ) );
