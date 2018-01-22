<?php
/**
 * Plugin_name
 * 
 * @package   Plugin_name
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * This class contain all the snippet or extra that improve the experience on the frontend
 */
class Cb_Extras {
	/**
	 * Initialize the snippet
	 */
	function initialize() {
		add_filter( 'body_class', array( __CLASS__, 'add_cb_class' ), 10, 3 );
	}
		/**
	 * Add class in the body on the frontend
	 * 
	 * @param array $classes THe array with all the classes of the page.
	 *
	 * @since 1.0.0
	 * 
	 * @return array
	 */
	public static function add_cb_class( $classes ) {
		$classes[] = CB_TEXTDOMAIN;
		return $classes;
	}
	}
$cb_extras = new Cb_Extras();
$cb_extras->initialize();
do_action( 'commons_booking_cb_extras_instance', $cb_extras );
