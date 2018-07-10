<?php
/**
 * Enqueues for the front end.
 *
 * Scripts, styles, etc.
 * Content filters: Overwrite items, location with templates
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Enqueue {
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

		add_filter( 'the_content', array ( __CLASS__, 'cb_template_chooser' ) );

		$shortcodes = new CB_Shortcodes();
		add_shortcode( 'cb_calendar', array( $shortcodes, 'calendar_shortcode' ) ) ;
		add_shortcode( 'cb_timeframe', array( $shortcodes, 'timeframe_shortcode' ) ) ;
		add_shortcode( 'cb_map', array( $shortcodes, 'map_shortcode' ) ) ;

		// create an API end point
		$API = new CB_API;

	}
	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function enqueue_styles() {
		wp_enqueue_style( CB_TEXTDOMAIN . '-plugin-styles', plugins_url( 'public/assets/css/public.css', CB_PLUGIN_ABSOLUTE ), array(), CB_VERSION );
	}
	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function enqueue_scripts() {
		wp_enqueue_script( CB_TEXTDOMAIN . '-plugin-script', plugins_url( 'public/assets/js/public.min.js', CB_PLUGIN_ABSOLUTE ), array( 'jquery' ), CB_VERSION );
	}
	/**
	 * Templates for cb_items and cb_locations.
	 *
	 * @since 2.0.0
	 *
	 * @return mixed html
	 *
	 * @see /templates
	 *
	 */
	public static function cb_template_chooser( $content ) {
		// items
		if ( is_post_type_archive( 'cb_item' ) && in_the_loop() ) {
			$args = array ( 'item_id' => get_the_id() );
			$timeframe_object = new CB_Timeframes( $args );
			$CB_Timeframes = $timeframe_object->get( );
			cb_get_template_part(  CB_TEXTDOMAIN, 'item', 'list', $CB_Timeframes );
		} elseif ( is_singular( 'cb_item' ) && in_the_loop() ) {
			$args = array ( 'item_id' => get_the_id() );
			$timeframe_object = new CB_Timeframes( $args );
			$CB_Timeframes = $timeframe_object->get( );
			cb_get_template_part(  CB_TEXTDOMAIN, 'item', 'single', $CB_Timeframes );
		// locations
		} elseif ( is_post_type_archive( 'cb_location' ) && in_the_loop() ) {
			$args = array ( 'location_id' => get_the_id() );
			$timeframe_object = new CB_Timeframes( $args );
			$CB_Timeframes = $timeframe_object->get( );
			cb_get_template_part(  CB_TEXTDOMAIN, 'location', 'list', $CB_Timeframes );
		} elseif ( is_singular( 'cb_location') && in_the_loop() ) {
			$args = array ( 'location_id' => get_the_id() );
			$timeframe_object = new CB_Timeframes( $args );
			$CB_Timeframes = $timeframe_object->get( );
			cb_get_template_part(  CB_TEXTDOMAIN, 'location', 'single', $CB_Timeframes );
		} else {
			return $content;
		}
	}

}
$cb_enqueue = new CB_Enqueue();
$cb_enqueue->initialize();
do_action( 'commons_booking_cb_enqueue_instance', $cb_enqueue );
