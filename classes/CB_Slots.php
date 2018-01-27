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
 * Retrieve the slots configured for a timeframe/date-range
 */
class CB_Slots extends CB_Calendar{
	/**
	 * Dates
	 *
	 * @var array
	 */
	public $slots = array();
	/**
	 * Date start
	 *
	 * @var array
	 */
	public $date_start;
	/**
	 * Date start
	 *
	 * @var array
	 */
	public $date_end;
	/**
	 * Condititions
	 *
	 * @var array
	 */
	public $conditions;
	/**
	 * Timeframe
	 *
	 * @var array
	 */
	public $timeframe_id;
	/**
	 * Dates array
	 *
	 * @var array
	 */
	public $dates_array;
	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * 
	 */



	public function __construct( $timeframe_id = FALSE, $dates_array  ) {

        $this->timeframe_id = $timeframe_id;   
        $this->dates_array = $dates_array;       

    }


    public function construct_sql_conditions () {
        
        if ( $this->timeframe_id && is_numeric( $this->timeframe_id ) ) {
            $this->sql_conditions[] = sprintf('timeframe_id = %d', $this->timeframe_id );
        }
        if ( is_array( $this->dates_array ) ) { 

            foreach ( $this->dates_array as $key => $val ) { // not the most elegant solution
               $this->dates_array[$key] = 'CAST("' . $val . '" as DATE)';
            }
            $dates = implode (',', $this->dates_array );
            $this->sql_conditions[] = sprintf('date IN(%s)', $dates);
        }
    
    }


    public function get_slots() {

        $this->construct_sql_conditions();

        if ( ! empty ( $this->sql_conditions ) ) {			
			$conditions = implode ( $this->sql_conditions, " AND " );
			$conditions = "WHERE ".$conditions;
		}
        
        global $wpdb;
        $slots_table_name = CB_SLOTS_TABLE;
        
        $slots_array = $wpdb->get_results(
			"SELECT * FROM {$wpdb->prefix}{$slots_table_name} {$conditions}", ARRAY_A			
        );

        $reordered = $this->reorder_slot_array ( $slots_array );
        return $reordered;
    }

    /**
	 * Re-index the slots array around the date, set array index as slot_id
	 *
	 * @since 1.0.0
	 * 
	 */
    private function reorder_slot_array( $array ) {
        
        $reordered = array();
        foreach ( $array as $key => $val ) {
            $reordered[$val['date']][$val['slot_id']] = $val;
        }
        return $reordered;
    }   
}