<?php
/**
 * Plugin Shortcodes
 *
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Shortcodes extends CB_Object {

	public function __construct () {

		$this->do_setup();

	}
	/**
	 * Render a calendar (multiple timeframes on one calendar)
	 *
	 * @param array $atts
	 */
	public function calendar_shortcode ( $atts ) {

		$args = shortcode_atts( $this->default_query_args, $atts, 'cb_calendar' );

		$this->set_context('calendar');
		$timeframes = $this->get_timeframes( $args );

		cb_get_template_part(  CB_TEXTDOMAIN, 'calendar', 'timeframes', $timeframes );
	}
	/**
	 * Render timeframe(s)
	 *
	 * @param array $atts
	 */
	public function timeframe_shortcode ( $atts ) {

		$args = shortcode_atts( $this->default_query_args, $atts, 'cb_timeframe' );

		$timeframes = $this->get_timeframes( $args );

		cb_get_template_part(  CB_TEXTDOMAIN, 'item', 'list', $timeframes );
	}
}
