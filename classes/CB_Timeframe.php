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
class CB_Timeframe extends CB_Object {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	/**
	 * Settings specific to this timeframe.
	 *
	 * @var object
	 */
	public $timeframes;
	/**
	 * Settings specific to this timeframe.
	 *
	 * @var object
	 */
	// static $query_args;
	/**
	 * Settings specific to this timeframe.
	 *
	 * @var object
	 */
	public $CB;
	/**
	 * Settings specific to this timeframe.
	 *
	 * @var object
	 */
	public $context = 'timeframes';
	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public function __construct( $args = array() ) {
		
		$CB = new CB_Object; 
			
		$this->timeframes = $CB->get_timeframes( $args );
		$this->query_args = $CB->get_query_args( );	

		$slots = $this->get_slots();
		var_dump ($slots);

	}
	/**
	 * Get timeframes.
	 *
	 * @since 1.0.0
	 *
	 * @return array errors
	 */
	public function get_slots() {

		if ($this->timeframes) {

			global $wpdb;
						// get all the slots & bookings for the selected timeframe within the time limits
			$slots = $wpdb->get_results(
				"SELECT wp_cb_slots.slot_id,wp_cb_slots.timeframe_id, wp_cb_slots.date, wp_cb_slots.time_start, wp_cb_slots.time_end,  wp_cb_slots.description, wp_cb_slots.booking_code, wp_cb_bookings.booking_status, wp_cb_bookings.user_id
					FROM wp_cb_slots
					LEFT JOIN wp_cb_bookings ON (wp_cb_slots.slot_id = wp_cb_bookings.slot_id)
					WHERE wp_cb_slots.timeframe_id IN (5,3) 
					AND wp_cb_slots.date BETWEEN CAST('2018-01-27' AS DATE) AND CAST('2018-01-31' AS DATE) 
					ORDER BY date", ARRAY_A
			);

			$reordered = array();
			foreach ( $slots as $key => $val ) {
				$reordered[$val['date']]['slots'][$val['slot_id']] = $val;
			}
			return $reordered;

		} else {
			var_dump("no_slots");
		}

	}
    
}
