<?php
/**
 * Handles item templates and meta information
 * @TODO: this class is not in use right now.
 * Using CB_Enque to handle item display on the frontend
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Items  {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function initialize() {
		if ( !apply_filters( 'commons_booking_cb_admin_initialize', true ) ) {
			return;
			add_filter( 'the_content', array( $this, 'get_item_template' ) );
		}
	}
	public function __construct() {
	}
	/**
	* Override the template system on the frontend
	*
	* @param string $original_template The original templace HTML.
	*
	* @since 2.0.0
	*
	* @return string
	*/
	public static function get_item_template( $content ) {
		if ( is_singular( 'cb_item' ) ) {
			return wpbp_get_template_part( CB_TEXTDOMAIN, 'content', 'demo', false );
		}
		return $content;
	}
	/**
	 * Return an instance of this class.
	 *
	 * @since 2.0.0
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
}
add_action( 'plugins_loaded', array( 'CB_Items', 'get_instance' ) );
