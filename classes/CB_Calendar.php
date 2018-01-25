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
class CB_Calendar extends CB_Timeframe {
	/**
	 * Dates
	 *
	 * @var array
	 */
	public $date_meta_array = array();
	/**
	 * Date start
	 *
	 * @var array
	 */
	public $date_start;
	/**
	 * Date start
	 *
	 * @var array
	 */
	public $date_end;
	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * 
	 */
	public function __construct( $date_start, $date_end ) {
		
		$this->date_start = $date_start;
		$this->date_end = $date_end;

		$this->weekday_names = array(
			__('Sunday', 'commons-booking'),
			__('Monday', 'commons-booking'),
			__('Tuesday', 'commons-booking'),
			__('Wednesday', 'commons-booking'),
			__('Thursday', 'commons-booking'),
			__('Friday', 'commons-booking'),
			__('Saturday', 'commons-booking')
		);
		

		$this->create_days_array( );
		var_dump( $this->query_args );


	}
    public function create_days_array( ) {
		
		$dates = cb_dateRange( $this->date_start, $this->date_end );
		
		foreach ($dates as $date) {
			$this->add_date( $date );
		}
		return $this->date_meta_array;

	}
    public function add_date( $date ) {

		$weekday = date('N', strtotime( $date ) );        

		$this->date_meta_array[$date] = array ( 
			'date'		=> $date,
			'name' 		=> $this->weekday_names[ $weekday - 1 ],
			'number' 	=> $weekday
		);
		
	}

	public function add_slot() {

	}
}