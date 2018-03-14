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
	public function has_opening_hours( $location_id ) {
		$bool = get_post_meta( $location_id, 'locations-has-opening-hours', true );
		return $bool;
	}
}
