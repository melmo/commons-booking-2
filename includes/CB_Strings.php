<?php
/**
 * CB_Strings
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Translatable Strings
 */
class CB_Strings extends CB_Object {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
    /**
	 * Object holding all strings
	 *
	 * @var object
	 */
    public $strings = array ();

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
	 * Initialize
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function initialize() {
        //

    }
	/**
	 * Retrieve a interface string
	 *
	 * @since 1.0.0
	 *
	 * @param $category The string category
	 * @param $key 		Optional: The key
	 *
	 * @return array string
	 */
	public static function get_string( $category, $key = FALSE ) {

        $cb_strings =  array(
			'cal' => array (
                'weekday_names' => array(
									__('Monday', 'commons-booking'),
									__('Tuesday', 'commons-booking'),
									__('Wednesday', 'commons-booking'),
									__('Thursday', 'commons-booking'),
									__('Friday', 'commons-booking'),
									__('Saturday', 'commons-booking'),
									__('Sunday', 'commons-booking'),
				)
			),
			'category' => array (
				'key' => 'testing this'
            )
		);

		// check that the string is in our pre-defined array, throw errors if not
		if ( array_key_exists( $category, $cb_strings ) ) {
			if ( ! $key  ) {
				return $cb_strings[ $category ];
			} elseif ( $key && array_key_exists( $key, $cb_strings[ $category ] ) ){
				return $cb_strings[ $category ][ $key ];
			} else {
				CB_Timeframe::throw_error( __FILE__, $key . ' not defined' );
			}
		} else {
			CB_Timeframe::throw_error( __FILE__, $category . ' not defined' );
		}
    }

}
add_action( 'plugins_loaded', array( 'CB_Strings', 'get_instance' ) );
