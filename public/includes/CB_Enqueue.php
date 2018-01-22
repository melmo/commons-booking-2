<?php
/**
 * Commons_Booking
 * 
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * This class contain the Enqueue stuff for the frontend
 */
class Cb_Enqueue {
	/**
	 * Initialize the class
	 */
	public function initialize() {
		if ( !apply_filters( 'commons_booking_cb_enqueue_initialize', true ) ) {
			return;
		}
		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}
		/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public static function enqueue_styles() {
		wp_enqueue_style( CB_TEXTDOMAIN . '-plugin-styles', plugins_url( 'public/assets/css/public.css', CB_PLUGIN_ABSOLUTE ), array(), CB_VERSION );
	}
			/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public static function enqueue_scripts() {
		wp_enqueue_script( CB_TEXTDOMAIN . '-plugin-script', plugins_url( 'public/assets/js/public.js', CB_PLUGIN_ABSOLUTE ), array( 'jquery' ), CB_VERSION );
	}
		
}
$cb_enqueue = new Cb_Enqueue();
$cb_enqueue->initialize();
do_action( 'commons_booking_cb_enqueue_instance', $cb_enqueue );
