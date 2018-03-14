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
	 * Construct SQL query to get slot_sets
	 *
	 * @return string $sql
	 *
	 */
	public function prepare_slot_templates_sql( ) {

		$where = '';
		if ( $this->slot_template_group_id != '') {
			$where = sprintf ( " WHERE template_group_id = %d", $this->slot_template_group_id );
		}

		$sql =(
		"SELECT
			*
			FROM {$this->slot_templates_table}
			{$where}
			ORDER BY template_group_id
			");

		return $sql;
	}
	/**
	 * Get slot templates
	 *
	 * @param int $slot_template_id
	 * @return array $slot_template_array
	 *
	 */
	public function get_slot_templates( $slot_template_group_id='' ) {

		global $wpdb;

		$this->slot_template_group_id = $slot_template_group_id;

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
