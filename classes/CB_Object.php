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
 * Interface for Items
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
	 * Return search parameters
	 * @param array $array
	 * @return array
	 */
	public function get_default_search( $search = array() ){
        
        $defaults = array(
            'limit' => false,
            'scope' => 'future',
            'order' => 'ASC',
            'orderby' => false,
            'category' => 0,
            'tag' => 0,
            'location' => false,
            'offset'=>0,
            'page'=>1,
            'page_queryvar'=>null,
            'pagination'=>false,
            'owner'=>false,
            'booking'=>false
            );
        
        //Return default if nothing passed
		if( ! empty( $search ) ){
			return $defaults;
        } else {           
            $defaults = array_merge( $search, $defaults );
        }
        return apply_filters('cb_object_get_default_search', $defaults );
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
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public function get_setting( $option_key_short, $field_id ) {

		$option_key = 'commons-booking-settings-' . $option_key_short;
		$serialized = get_option ( $option_key ); // all options in this section, serialized

		if ( $serialized && key_exists( $field_id, $serialized ) ) {
			return $serialized[$field_id];
		} 

	}
    function hello() {
		echo "CB Object says hello";
		echo get_class( $this );
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
