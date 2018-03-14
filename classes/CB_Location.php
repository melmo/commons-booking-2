<?php
/**
 * Locations
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Interface for Locations
 */
class CB_Location  {

	public $location_id;
	/**
	* Constructor
  */
	public function __construct( $location_id ) {
		$this->location_id = $location_id;
	}
	/**
	* Return true if opening hours set
	* @param int $location_id
	* @return bool
  */
	public function has_opening_times( ) {

		$bool = get_post_meta( $this->location_id, 'locations-has-opening-hours', true );
		return $bool;
	}
	/**
	* Return an array of open days & times
	* @param int $location_id
	* @return array $opening_times
  */
	public function get_opening_times( ) {

		$opening_times = array();
		$location_id = $this->location_id;

		$weekdays = array (
			'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'
		);

		if ( $this->has_opening_times() == 1 ) { // checkbox set

			foreach ( $weekdays as $weekday ) { // loop through days

				$day_open = get_post_meta( $location_id, 'location-open-' . $weekday, true );

				if ( $day_open ) {
					$day_numeric = date ('w', strtotime ($weekday));
					$opening_times[ $day_numeric ] = array (
						'from' =>
						get_post_meta( $location_id, 'location-open-' . $weekday . '-from', true ),
						'till' =>
						get_post_meta( $location_id, 'location-open-' . $weekday . '-til', true )
					);
				}
			}
		}
		return $opening_times;
	}
}
