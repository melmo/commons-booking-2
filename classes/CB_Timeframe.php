<?php
/**
 * CB Timeframe
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
class CB_Timeframe extends CB_Object implements Iterator  {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	var $set_id;

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public static function initialize() {
		if ( !apply_filters( 'commons_booking_cb_timeframe_initialize', true ) ) {
			return;
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
    
    	//Iterator Implementation
    public function rewind(){
        reset($this->tickets);
    }  
    public function current(){
        $var = current($this->tickets);
        return $var;
    }  
    public function key(){
        $var = key($this->tickets);
        return $var;
    }  
    public function next(){
        $var = next($this->tickets);
        return $var;
    }  
    public function valid(){
        $key = key($this->tickets);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
}
add_action( 'plugins_loaded', array( 'CB_Timeframe', 'get_instance' ) );
