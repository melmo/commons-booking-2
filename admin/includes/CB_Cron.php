<?php
/**
 * CB Cron functions
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Cron {

	/**
	 * Extend timeframes that have no end date
	 */
	public function auto_extend_timeframes() {

		// @TODO
		// get timeframes that have no end date (uing CB_Object)
		// check if their current end date is + (settings) days
		// if not, extend the missing days.

	}
}
