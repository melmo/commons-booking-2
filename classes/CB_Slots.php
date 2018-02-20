<?php
/**
 * CB Slots
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Mostly admin-related functions to generate and query slots. For retrieving slots at the front-end, see CB_Object.
 */
class CB_Slots {
	/**
	 * Slots array
	 *
	 * @var object
	 */
	public $slots = array();
	/**
	 * Slots templates
	 *
	 * @var object
	 */
	public $slots_templates = array();
	/**
	 * Slot template group
	 *
	 * @var array
	 */
	public $template_group = array();
	/**
	 * Timeframe id
	 *
	 * @var object
	 */
	public $timeframe_id;
	/**
	 * Timeframe id
	 *
	 * @var object
	 */
	public $date_array = array();
	/**
	 * Constructor
	 */
	public function __construct( ) {

		$this->slot_templates = new CB_Slot_Templates();

		global $wpdb;

		$this->timeframes_table = $wpdb->prefix . CB_TIMEFRAMES_TABLE;
		$this->slots_table 	= $wpdb->prefix . CB_SLOTS_TABLE;
		$this->bookings_table = $wpdb->prefix . CB_BOOKINGS_TABLE;
		$this->slots_bookings_relation_table = $wpdb->prefix . CB_SLOTS_BOOKINGS_REL_TABLE;
	}
	/**
	 * Retrieve slots
	 */
	public function get_slots( $timeframe_id ) {

		global $wpdb;

		$this->timeframe_id = $timeframe_id;
		$sql = $this->prepare_slots_sql();

		$results = $wpdb->get_results( $sql );

		return $results;

	}
	/**
	 * Set the timeframe id
	 *
	 * @param int $id
	 */
	public function set_timeframe_id( $id ) {
		$this->timeframe_id = $id;
	}
	/**
	 * Construct SQL query for slots of one timeframe @TODO
	 *
	 * @return string $sql
	 *
	 */
	public function prepare_slots_sql() {

		$sql =(
		"SELECT
			{$this->slots_table}.slot_id,
			{$this->slots_table}.timeframe_id,
			{$this->slots_table}.date,
			{$this->slots_table}.booking_code,
			{$this->bookings_table}.booking_status,
			{$this->slots_bookings_relation_table}.booking_id
			FROM {$this->slots_table}
			LEFT JOIN {$this->slots_bookings_relation_table}
			ON ({$this->slots_table}.slot_id={$this->slots_bookings_relation_table}.slot_id)
			LEFT JOIN {$this->bookings_table}
			ON ({$this->slots_bookings_relation_table}.booking_id = {$this->bookings_table}.booking_id)
			WHERE {$this->slots_table}.timeframe_id = {$this->timeframe_id}
			-- AND {$this->bookings_table}.booking_status != 'BOOKED'
			ORDER BY date
			");

		return $sql;
	}
	/**
	 * Generate slots by slot_templates
	 *
	 * @param int $template_group_id
	 * @param array $dates_array
	 */
	public function generate_slots( $template_group_id, $dates_array ) {



		// foreach ( $dates_array as $date ) {

		// }

	}
	/**
	 * Get a specific slot_template group
	 *
	 * @param int $template_group_id
	 */
	public function get_slot_template_group( $template_group_id='' ) {

		$this->template_group = $this->slot_templates->get_slot_templates( $template_group_id  );
		// var_dump($this->template_group );
		// foreach ( $dates_array as $date ) {

		// }

	}

	/**
	 * Construct SQL query to generate slots @TODO
	 *
	 * @return string $sql
	 *
	 */
	public function prepare_slots_generate_sql() {

		$sql =(
		"INSERT INTO table_name (column1, column2, column3,...)
		VALUES (value1, value2, value3,...)
			");

		return $sql;
	}
}
