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
 * Build a calendar
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
	// public $dates_array = array();
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
	 * @since 1.0.0
	 *
	 */
	public function __construct( $timeframe_id, $date_start, $date_end ) {

		$this->timeframe_id = $timeframe_id; //@tODO: retire
		$this->date_start = $date_start;
		$this->date_end = $date_end;

		$this->create_days_array();

		return $this->dates_array;

	}

	public function set_timeframe( $id ) {
		$this->timeframe_id = $id;
	}

    public function create_days_array( ) {

		$dates_array = cb_dateRange( $this->date_start, $this->date_end );

		foreach ($dates_array as $date) {
			$this->add_date_meta( $date );
			// $this->map_slots_to_dates( $date );
		}

	}

    public function add_date_meta( $date ) {

		$weekday = date('N', strtotime( $date ) );
		$weekname_array = CB_Strings::get_string( 'cal', 'weekday_names' );

		$this->dates_array[$date]['meta'] = array (
			'date'		=> $date,
			'name' 		=> $weekname_array[ $weekday -1 ],
			'number' 	=> $weekday
		);
	}

    // private function add_timeframe_meta( $date ) {

	// 	$this->days_array[$date]['timeframe_id'] = $this->timeframe_id;
	// }


    // public function map_slots_to_date( $date ) {
	// 	if ( ! empty ( $this->slots_array[$date] ) ) {
	// 		$this->days_array[$date]['slots'] = $this->slots_array[$date];
	// 	}
	// }


	// public function add_slots( $date ) {
	// 	$slots = new CB_Slots( $this->timeframe_id, $date );
	// 	$this->slots_array = $slots->get_slots();
	// 	// return $slots_array;
	// }
}
