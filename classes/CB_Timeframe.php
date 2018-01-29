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
	public $context = 'timeframes';
	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct( $timeframe ) {


	}
}
