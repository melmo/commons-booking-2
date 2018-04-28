<?php
/**
 * CB Timeframe Options
 *
 * Options for timeframes.
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Timeframe_Options {
	/**
	 * Timeframe id
	 *
	 * @var object
	 */
	public $timeframe_id;
	/**
	 * Constructor
	 */
	public function __construct( ) {

		global $wpdb;
		$this->timeframe_options_table = $wpdb->prefix . CB_TIMEFRAME_OPTIONS_TABLE;

					new WP_Admin_Notice( __('Options saved', 'commons-booking') , 'updated' );

	}
	/**
	 * Get the timeframe option, fall back on plugin setting if not available
	 *
	 * @param string $option_name
	 * @return string $value
	 *
	 * @uses CB_Settings
	 */
	public function set_timeframe_id( $timeframe_id='' ) {

		if ( isset ( $timeframe_id ) && cb_timeframe_exists( $timeframe_id ) ) { // make sure timeframe exists
			$this->timeframe_id = $timeframe_id;
		} else {
			echo ("timeframe not found"); //@TODO: use messages
		}

	}
	/**
	 * Get the timeframe option, fall back on plugin setting if not available
	 *
	 * @return array $result
	 *
	 * @uses CB_Settings
	 */
	public function get_timeframe_option_for_form( $data, $id, $arguments, $field ) {
		global $wpdb;

		$query = sprintf( "SELECT option_name, option_value
		FROM %s
		WHERE timeframe_id = %d AND option_name = '%s'"
		, $this->timeframe_options_table, $this->timeframe_id, $arguments['field_id'] ) ;

		$results =  $wpdb->get_results( $query, 'OBJECT' );

		if ( $results ) {

			$option = $results[0]->option_value;

			return $option;
		}

	}

	/**
	 * Get the timeframe option, fall back on plugin setting if not available
	 *
	 * @return array $result
	 *
	 * @uses CB_Settings
	 */
	public function get_timeframe_options( ) {
		global $wpdb;
		$query = sprintf( 'SELECT option_name, option_value
		FROM %s
		WHERE timeframe_id = %d
		', $this->timeframe_options_table, $this->timeframe_id ) ;

		$results =  $wpdb->get_results( $query, 'OBJECT' );

		if ( $results ) {
			$array = array();
			foreach ($results as $result){
					$array[$result->option_name] = $result->option_value;
			}
			return $array;

		}

	}
	/**
	 * Save the timeframe option to the db table
	 *
	 * @param string $data
	 * @return array $values
	 *
	 * @uses cmb2
	 */
	public function save_timeframe_option( $data, $values ) {

		global $wpdb;

		//handle checkbox
		$option_name = $values['field_id'];
		$value = is_null( $values['value'] ) ? 0 : $values['value'];

		$result = $wpdb->replace(
			'wp_cb2_timeframe_options',
				array(
					'timeframe_id' => $this->timeframe_id,
					'option_name' => $option_name,
					'option_value' => $value
				),
				array( '%d', '%s', '%s' )
			);

			return true;
	}


}
