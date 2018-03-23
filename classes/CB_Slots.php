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
	 * Already generated slots
	 *
	 * @var object
	 */
	public $slots_array = array();
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
	 * Array of dates
	 *
	 * @var object
	 */
	public $dates_array = array();
	/**
	 * Array of date meta. Holds location closed days, holidays
	 *
	 * @var object
	 */
	public $dates_meta_array = array();
	/**
	 * Array of dates to ignore when creating slots
	 *
	 * @var object
	 */
	public $filter_date_array = array();
	/**
	 * Use booking codes
	 *
	 * @var bool
	 */
	public $include_codes = FALSE;
	/**
	 * Closed days of the location within timeframe
	 *
	 * @var array
	 */
	public $location_closed_days = array();
	/**
	 * Holidays within timeframe
	 *
	 * @var array
	 */
	public $holiday_closed_days = array();
	/**
	 * Constructor
	 */
	public function __construct( $timeframe_id = '' ) {

		$this->slot_templates = new CB_Slot_Templates();

		global $wpdb;

		$this->timeframes_table = $wpdb->prefix . CB_TIMEFRAMES_TABLE;
		$this->slots_table 	= $wpdb->prefix . CB_SLOTS_TABLE;
		$this->bookings_table = $wpdb->prefix . CB_BOOKINGS_TABLE;
		$this->slots_bookings_relation_table = $wpdb->prefix . CB_SLOTS_BOOKINGS_REL_TABLE;

		$this->timeframe_id = $timeframe_id;
		$this->include_codes = FALSE;
	}
	/**
	 * Retrieve slots
	 */
	public function get_slots( ) {

		global $wpdb;

		$select = (
			$this->slots_table . '.slot_id, ' .
			$this->slots_table . '.timeframe_id, ' .
			$this->slots_table . '.date, ' .
			$this->slots_table . '.booking_code, ' .
			$this->bookings_table . '.booking_status, ' .
			$this->slots_bookings_relation_table . '.booking_id '
		);

		$sql = $this->prepare_slots_sql( $select );

		$this->slots_array = $wpdb->get_results( $sql, ARRAY_A );

		return $this->slots_array;
	}
	/**
	 * Retrieve slot dates as an array
	 */
	public function get_slot_dates_array( ) {

		$slot_dates = array();

		if ( ! isset ( $this->slots_array ) && ! empty ($this->slots_array) ) { // make sure that slots object exists
			$this->get_slots( );
		}

		if ( is_array ( $this->slots_array ) ) {
			foreach ( $this->slots_array as $slot ) {
			$slot_dates[] = $slot['date'];
			}
		}
		return $slot_dates;
	}
	/**
	 * Construct SQL query for slots of one timeframe @TODO
	 *
	 * @return string $sql
	 *
	 */
	public function prepare_slots_sql( $select ) {

			$where = sprintf ('%s.timeframe_id = %s',
				$this->slots_table,
				$this->timeframe_id
		);

		$sql =(
			"SELECT {$select}
			FROM {$this->slots_table}
			LEFT JOIN {$this->slots_bookings_relation_table}
			ON ({$this->slots_table}.slot_id={$this->slots_bookings_relation_table}.slot_id)
			LEFT JOIN {$this->bookings_table}
			ON ({$this->slots_bookings_relation_table}.booking_id = {$this->bookings_table}.booking_id)
			WHERE {$where}
			-- AND {$this->bookings_table}.booking_status != 'BOOKED'
			ORDER BY date
			");

		var_dump($sql);
		return $sql;
	}
	/**
	 * Generate slots by slot_templates
	 *
	 * @param int $template_group_id
	 * @param array $dates_array
	 */
	public function generate_slots( ) {

		$this->template_array = $this->get_slot_template_group( $this->template_group_id );

		$dates_filtered_array = $this->apply_date_filter();

		$sql = $this->prepare_slots_insert_array( $dates_filtered_array );
		$result = $this->insert_slots_sql( $sql );
		return $result;

	}
	/**
	 * Delete slots
	 *
	 * @param int $timeframe_id
	 */
	public function delete_slots( $timeframe_id ) {

		$result = $this->delete_slots_sql( $timeframe_id );
		return $result;
	}
		/**
	 * Get a specific slot_template group
	 *
	 * @param int $template_group_id
	 */
	public function set_slot_template_group( $template_group_id='' ) {
		$this->template_group_id = $template_group_id;
	}

	/**
	 * Get a specific slot_template group
	 *
	 * @param int $template_group_id
	 */
	public function get_slot_template_group( ) {

		$this->template_group = $this->slot_templates->get_slot_templates( $this->template_group_id  );

		return $this->template_group;

	}
	/**
	 * Return if slots already defined
	 *
	 * @return bool
	 */
	public function has_slots( ) {

		if ( ! empty ( $this->slots ) ) {
			return TRUE;
		} else {
			return FALSE;
		}

		// return $bool

	}
	/**
	 * Create an array of dates from start to end date
	 *
	 * @param string $start_date
	 * @param string $end_date
	 */
	public function set_date_range( $start_date, $end_date ) {

		$this->dates_array = cb_dateRange ( $start_date, $end_date );

	}
	/**
	 * Add to the filter dates array
	 *
	 * @param array $array
	 */
	public function add_to_date_filter( $array = array() ) {

		$this->filter_date_array = array_merge ( $this->filter_date_array, (array) $array );

	}
	/**
	 * Substract from the filter dates array
	 *
	 * @param array $array
	 */
	public function remove_from_date_filter( $array = array() ) {

		$this->filter_date_array = array_intersect ( $this->filter_date_array, (array) $array );

	}
	/**
	 * Apply the filter dates array
	 *
	 * @param array $array
	 * @return array $dates_array
	 */
	public function apply_date_filter( ) {

		$this->dates_array = array_diff( (array) $this->dates_array, $this->filter_date_array );

		return apply_filters('cb_slots_generate_apply_date_filter',$this->dates_array );

	}
	/**
	 * Define if codes will be generated
	 *
	 * @param bool $timeframe_id
	 */
	public function set_include_codes( $bool = FALSE ) {

		if ( $bool === TRUE OR $bool == 1 ) {
			$this->include_codes = TRUE;
		}
	}
	/**
	 * Prepare the array for insertion
	 *
	 * @return array $sql
	 *
	 */
	public function prepare_slots_insert_array( $dates_array_filtered ) {

		$insert_array = array();

		foreach ( $dates_array_filtered as $date ) { // loop through dates
			foreach ( $this->template_array as $templates ) { // loop through template_array (is always 1)
				foreach ( $templates as $template ) { // loop through slots
					$insert_array[] = array (
						'timeframe_id' => $this->timeframe_id,
						'date' => $date,
						'booking_code' => $this->maybe_return_booking_code(),
						'location_closed' => $this->is_location_closed( $date ),
						'holiday_closed' => $this->is_holiday_closed( $date),
						// @TODO replace below with 'slot_template_id' = X,
						'template_order' =>$template['order'],
						'time_start' => $template['time_start'],
						'time_end' => $template['time_end'],
						'description' => $template['description'],
					);
				}
			}
		}
		return $insert_array;
	}
	/**
	 * Check if in holiday closed days array
	 *
	 * @param string $date
	 * @return bool
	 */
	private function is_holiday_closed( $date ) {
		$bool = in_array( $date, $this->holiday_closed_days ) ? 1 : 0;
		return $bool;
	}
	/**
	 * Check if in location closed days array
	 *
	 * @return string $code
	 *
	 */
	private function is_location_closed( $date ) {
		$bool = in_array( $date, $this->location_closed_days ) ? 1 : 0;
		return $bool;
	}

	/**
	 * If include_codes is set, generate a random code
	 *
	 * @uses CB_Codes
	 * @return string $code
	 *
	 */
	private function maybe_return_booking_code( ) {

		$code = NULL;
		$codes_obj = new CB_Codes;

		if ( $this->include_codes ) {
			$code = $codes_obj->get_random_code();
		}
		return $code;

	}
	/**
	 * Insert slots into db
	 *
	 * @uses CB_Codes
	 * @return string $code
	 *
	 */
	private function insert_slots_sql( $insert_array ) {

		$result = 1;
		if ( !empty ( $insert_array ) ) { // no slots to add
			$result = wp_insert_rows( $insert_array, $this->slots_table);
		}
		return $result;
	}

	public function delete_slots_sql( $timeframe_id ) {
		global $wpdb;
		$result = $wpdb->delete( $this->slots_table, array( 'timeframe_id' => $timeframe_id ), array( '%d' ) );
		return $result;
	}

	/**
	 * Create slots for a specific timeframe
	 *
	 * 1. set the date range
	 * 2. decide if slots will be re-generated (delete all slots)
	 * 3. add all existing dates
	 * 3. get location opening times
	 * 4. apply opening times of location (closed days)
	 * 5. @TODO apply exclude list of holiday-provider (holidays)
	 * 6. generate slots
	 *
	 * @uses CB_Location
	 *
	 * @param $timeframe
	 * @param $request
	 *
	 * @return int $result
	 *
	 */
	public function re_generate_slots_function( $timeframe, $request ) {

				$this->set_date_range ($timeframe['date_start'], $timeframe['date_end'] );

				$this->set_slot_template_group( $timeframe['slot_template_group_id'] );
				$templates = $this->get_slot_template_group(); // get the templates array

				// handle regenerate slots checkbox
				if ( isset( $request['regenerate_all_slots'] ) ) { 	// regenerate slots is passed
					$this->delete_slots( $timeframe['timeframe_id'] );
				}

				$this->get_slots(); // get the previously defined slots

				// handle slots already in db: get exising dates
				$existing_dates = $this->get_slot_dates_array();

				$this->add_to_date_filter ( $existing_dates ); // add these date to ignore list, we are ignoring days that have already slots attached.

				// handle location opening times checkbox
				$location = new CB_Location ( $timeframe['location_id'] );
				$opening_times = $location->get_opening_times();
				$pickup_mode = $location->get_pickup_mode();

				if ( $timeframe['exclude_location_closed'] == 1 && $pickup_mode == 'opening_times' ) {

					$this->location_closed_days = cb_filter_dates_by_opening_times ( $timeframe['date_start'], $timeframe['date_end'], $opening_times, TRUE );

				}
				// @TODO: holiday closed days
				if ( $timeframe['exclude_holiday_closed'] == 1 ) {
					$this->holiday_closed_days = array(); //@TODO
				}

				// generate codes if set.
				$this->set_include_codes( $timeframe['codes_enabled'] );

				$sql_slots_result = $this->generate_slots( );

				return $sql_slots_result;
	}
}
