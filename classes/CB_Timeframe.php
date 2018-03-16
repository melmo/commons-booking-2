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
class CB_Timeframe extends CB_Object {
	/**
	 * This timeframe
	 *
	 * @var object
	 */
	public $id;
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
	public function __construct( $id = NULL ) {

		if ( $id ) {
			$this->timeframe_id = $id;
		}

		$this->timeframes = new CB_Object;

	}
	public function get ( $args=array() ) {
		$tf = $this->timeframes->get_timeframes( $args );
		return $tf;
	}

}
