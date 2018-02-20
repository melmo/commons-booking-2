<?php
/**
 * CB Slot Templates.
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Mostly admin-related functions to query Slot Templates.
 */
class CB_Slot_Templates {
	/**
	 * ID of the slot set
	 *
	 * @var array
	 */
	public $slot_templates_array = array();
	/**
	 * Constructor
	 */
	public function __construct() {

		global $wpdb;

		// set table names
		$this->bookings_table = $wpdb->prefix . CB_BOOKINGS_TABLE;
		$this->slot_templates_table = $wpdb->prefix . CB_SLOT_TEMPLATES_TABLE;

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
	 * Construct SQL query to get slot_sets
	 *
	 * @return string $sql
	 *
	 */
	public function prepare_slot_templates_sql( ) {

		$where = '';
		if ( ! empty ( $this->slot_template_group_id ) ) {
			$where = sprintf ( " WHERE slot_set_id = %d", $this->slot_template_group_id );
		}

		$sql =(
		"SELECT
			*
			FROM {$this->slot_templates_table}
			ORDER BY template_group_id
			{$where}
			");

		return $sql;
	}
	/**
	 * Get slot templates
	 *
	 * @param int $slot_set_id
	 * @return array $slot_template_array
	 *
	 */
	public function get_slot_templates( $slot_template_group_id='' ) {

		global $wpdb;

		if ( ! empty ( $slot_template_group_id ) ) {
			$this->slot_template_group_id = $slot_template_group_id;
		}

		$sql = $this->prepare_slot_templates_sql();

		$slot_templates = $wpdb->get_results( $sql, ARRAY_A );

		$slot_templates_formatted = $this->array_format_slot_templates( $slot_templates );

		return $slot_templates_formatted;

	}
	/**
	 * Map SQL Results to new array with the index of template_group_id
	 *
	 * @param array $slot_template_array
	 * @return array $templates_formatted
	 *
	 */
	private function array_format_slot_templates ( $slot_template_array = array()) {

		$templates_formatted = array();

		foreach ( $slot_template_array as $key => $val ) {
			$templates_formatted[$val['template_group_id']][] = $val;
		}

		return $templates_formatted;

	}

}
