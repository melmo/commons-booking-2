<?php
/**
 * The main object, base class 
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
	private $sql_conditions = array();
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
	 * merged query args
	 *
	 * @var array
	 */
    private $query_args = array();
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
	 * @param array $array
	 * @return array merged query params 
	 */
	public function get_default_query( $args ){
        
        $this->default_query_args = array(
			// date & sorting
			'scope' 	=> 'future',
			'day_limit' => false, //@TODO: get this from settings
			'today'		=> 'today',
            'order' 	=> 'ASC',
            'orderby' 	=> false,
            'category' 	=> 0,
			'tag' 		=> 0,
			// limit & pagination
            'limit' 	=> false,
			'offset'	=> 0,
            'page'		=> 1,
            'page_queryvar'=>null,
            'pagination'	=>false,
			// query by id 
            'timeframe_id' 	=> false,
            'location_id' 	=> false,
            'item_id' 		=> false,
            'user_id'		=> false,
			'booking_id'	=> false,
			// grouping_sql
			'group_by'		=> false
            );
        
        //Return default query if nothing passed
		if( empty( $args ) ){
			return $this->default_query_args;
        } else {      
            $this->query_args = array_merge( $this->default_query_args, $args );
        }
        return apply_filters('cb_object_get_default_query', $this->query_args );
	}
	/**
	 * Construct SQL query
	 * @param array $args
	 * @return string sql query 
	 */
	public function construct_query_sql( $args = array() ) {

		global $wpdb;

		// date & sorting
		$today = date('Y-m-d');

		if ( $args['scope'] == 'future' ) {
			$this->sql_conditions[] = sprintf('date_end >= CAST("%s" AS DATE)', $today);
			
		} elseif ( $args['scope'] == 'past') {
			$this->sql_conditions[] = sprintf('date_end <= CAST("%s" AS DATE)', $today);

		} 

		// filter by ids
		if ( $args['timeframe_id'] && is_numeric( $args['timeframe_id'] ) ) {
			$this->sql_conditions[] = sprintf(' timeframe_id = %d', $args['timeframe_id'] );	
		} 
		if ( $args['location_id'] && is_numeric( $args['location_id'] ) ) {
			$this->sql_conditions[] = sprintf(' location_id = %d', $args['location_id'] );
		}
		if ( $args['item_id'] && is_numeric( $args['item_id'] ) ) {
			$this->sql_conditions[] = sprintf(' item_id = %d', $args['item_id'] );
		}
		if ( $args['user_id'] && is_numeric( $args['user_id'] ) ) {
			$this->sql_conditions[] = sprintf(' user_id = %d', $args['user_id'] );
		}
		if ( $args['booking_id'] && is_numeric( $args['booking_id'] ) ) {
			$this->sql_conditions[] = sprintf(' booking_id = %d', $args['booking_id'] );
		}


		//limit @TODO
		if ( ( $args['limit'] ) && is_numeric( $args['limit'] ) ) {
			$this->sql_conditions['limit'] = sprintf (" LIMIT %d ", $args['limit'] );
		}

	}
	public function get( $args = array() ) {
		
		global $wpdb;
		$table_name = CB_TIMEFRAMES_TABLE;

		$query = $this->get_default_query( $args ); 
		$this->construct_query_sql( $query );

		if ( ! empty ( $this->sql_conditions ) ) {
			
			$conditions = implode ( $this->sql_conditions, " AND " );
			$conditions = "WHERE ".$conditions;
		}


		$results = $wpdb->get_results(
			" SELECT * FROM {$wpdb->prefix}{$table_name} {$conditions}"  			
		);

		return $results;
		// var_dump( $results );

	}
	/**
	 * Add condition to mysql query   
	 * 
	 * @since 1.0.0
	 * 
	 * @param string $table_name sql table name 
	 * @param string $key mysql row
	 * @param string $condition
	 * @param string $val mysql val  
	 * 
	 */
	public function add_sql_condition( $table_name, $key, $condition, $val ) {

		global $wpdb;
		// $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}_votes WHERE post_id = %d AND username = %s", $post_id, $username );
		$table = $table_name;
		$sql_string = $wpdb->prepare( "{$wpdb->prefix}{$table_name}.%s %s %d", $key, $condition, $val);
		array_push ( $this->sql_conditions,  $sql_string);
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
	public function get_setting( $option_key_short, $field_id ) {

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
	 * Adds an error to the object
	 */
	function add_error($errors){
		if(!is_array($errors)){ $errors = array($errors); } //make errors var an array if it isn't already
		if(!is_array($this->errors)){ $this->errors = array(); } //create empty array if this isn't an array
		foreach($errors as $key => $error){			
			if( !in_array($error, $this->errors) ){
			    if( !is_array($error) ){
					$this->errors[] = $error;
			    }else{
			        $this->errors[] = array($key => $error);
			    }
			}
		}
	}


}
add_action( 'plugins_loaded', array( 'CB_Object', 'get_instance' ) );
