<?php
/**
 * @TODO: this class is right now just a wrapper for CB_Object
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Timeframes extends CB_Object {
	/**
	 * Timeframe id
	 *
	 * @var object
	 */
	public $id;
	/**
	 * Args
	 *
	 * @var object
	 */
	public $default_query_args;
	/**
	 * Settings specific to this timeframe.
	 *
	 * @var object
	 */
	public $context = 'timeframe';
	/**
	 * Initialize the class
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function __construct( $args = array(), $context = 'timeframe'  ) {

		$this->args = $args;
		$this->context = $context;

	}
	/**
	 * Get timeframes
	 *
	 * @since 2.0.0
	 *
	 * @return object $timeframes
	 */
	public function get ( ) {

		$timeframes = $this->get_timeframes( $this->args );
		return $timeframes;

	}

}
