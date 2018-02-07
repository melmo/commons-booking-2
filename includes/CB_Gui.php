<?php
/**
 * CB_Gui
 *
 * Holds code snippets for items, locations, etc
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
class CB_Gui {
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
	public static function get_gui( $id, $args ) {

    $get_gui =  array(
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

  }
	/**
	 * Display a front-facing message
	 *
	 * @since 1.0.0
	 *
	 * @param $string 		The message
	 * @param $category Optional The message category
	 * @param $args array 		Optional: The key
	 *
	 * @return string
	 */
	public function maybe_do_message( $string, $category='notice' ) {

		$message = '';
		if ( ! empty ( $string ) ) {
		$message = sprintf (
			'<div class="cb-message cb-%s">%s</div>',
			$category,
			$string
		);
			return $message;
		}

  }

}
add_action( 'plugins_loaded', array( 'CB_Strings', 'get_instance' ) );
