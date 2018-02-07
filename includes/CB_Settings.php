<?php
/**
 * Settings for Commons Booking
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Global settings, settings for items, timeframes, etc
 * Usage: $setting = CB_Settings::get( 'bookings', 'max-slots');
 */
class CB_Settings {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
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

    }
	/**
	 * Get a setting from the WP options table
	 *
	 * @since 1.0.0
	 *
	 * @param string $options_page
	 * @param string $toption (optional)
	 * @param string $checkbox (optional, @TODO)
	 *
	 * @return string/array
	 */
	public static function get( $options_page, $option = FALSE, $checkbox = FALSE ) {

		$options_page_name = CB_TEXTDOMAIN . '-settings-' . $options_page;

		$options_array = get_option( $options_page_name );

		if ( is_array ($options_array) && $option && array_key_exists ($option, $options_array )  ) { // we want a specific setting on the page and key exists
			return $options_array[ $option ];
		} elseif ( ! $option &&  is_array( $options_array ) ) {
			return $options_array;
		} else {
			CB_Object::throw_error( __FILE__, $options_page . 'is not a valid setting');
		}
	}
}
add_action( 'plugins_loaded', array( 'CB_Settings', 'get_instance' ) );
