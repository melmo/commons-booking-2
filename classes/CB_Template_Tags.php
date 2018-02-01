<?php
/**
 * Commons Booking Template Tags
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Template Tags
 */
class CB_TemplateTags {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $template_tags = array();
	/**
	 * Initialize the class
	 */
	public static function initialize() {
		if ( !apply_filters( 'commons_booking_cb_template_tags_initialize', true ) ) {
			return;
		}
	}
}
