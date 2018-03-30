<?php
/**
 * Handles all date-related functions.
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Calendar extends CB_Object {
	/**
	 * Dates
	 *
	 * @var array
	 */
	public $calendar = array();
	/**
	 * Dates
	 *
	 * @var array
	 */
	public $dates_array = array();
	/**
	 * Date start
	 *
	 * @var array
	 */
	public $timeframe_id;
	/**
	 * Date start
	 *
	 * @var array
	 */
	public $slots_array = array();
	/**
	 * Initialize the class
	 *
	 * @since 2.0.0
	 *
	 * @param int $timeframe_id
	 * @param string $date_start
	 * @param string $date_end
	 * @return array $dates_array
	 *
	 */
	public function __construct( $timeframe_id, $date_start, $date_end ) {

		$this->timeframe_id = $timeframe_id;
		$this->date_start = $date_start;

		$cal_end_default = CB_Settings::get( 'calendar', 'limit' );

		// Timeframes may not have an end date, in this case, use +30 days @TODO: Use Setting
		if ( ! isset ( $date_end ) OR $date_end == '0000-00-00' ) {
			$this->date_end = date("Y-m-d", strtotime( "+" . $cal_end_default . " days", strtotime( $date_start ) ) );
		} else {
			$this->date_end = $date_end;
		}

		$this->create_days_array();

		return $this->dates_array;

	}
	/**
	 * set the timeframe id
	 *
	 * @since 2.0.0
	 *
	 * @param int $timeframe_id
   * @return void
	 *
	 */
	public function set_timeframe( $id ) {
		$this->timeframe_id = $id;
	}
	/**
	 * Create an array of dates from start date to end date
	 *
	 * @since 2.0.0
	 *
   * @return void
	 *
	 */
  public function create_days_array( ) {

		$dates_array = cb_dateRange( $this->date_start, $this->date_end );
		foreach ($dates_array as $date) {
			$this->add_holiday( $date );
		}

	}
	/**
	 * Return holiday
	 *
	 * @since 2.0.0
	 *
	 * @param string $date
	 *
	 */
  public function add_holiday( $date ) {

		$year = date( 'Y', strtotime( $date ) );
		$holidays = CB_Holidays::get_holidays_list( array( $year ) );

		if ( key_exists( $date, $holidays) ) {
			$this->dates_array[$date]['holiday'] = $holidays[$date];
		} else {
			$this->dates_array[$date]['holiday'] = '';
		}
	}

}
