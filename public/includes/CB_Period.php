<?php
require('CB_PeriodStatusType.php');
require('CB_PeriodGroup.php');

class CB_Period extends CB_PostNavigator implements JsonSerializable {
	// TODO: use this generic period class
  public static $database_table = 'cb2_periods';
  public static $all = array();
  static $static_post_type = 'period';
  public static $post_type_args = array(
		'public' => TRUE,
		'menu_icon' => 'dashicons-admin-settings',
  );

  function post_type() {return self::$static_post_type;}
  public function __toString() {return $this->name;}

  static function &factory_from_wp_post( $post ) {
		// The WP_Post may have all its metadata loaded already
		// as the wordpress system adds all fields to the WP_Post dynamically
		CB_Query::get_metadata_assign( $post );

		$period_status_type = CB_Query::get_post_type( 'periodstatustype', $post->period_status_type_ID );

		$object = self::factory(
			$post->ID,
			$post->period_id,
			$post->post_title,
			$post->datetime_part_period_start,
			$post->datetime_part_period_end,
			$post->datetime_from,
			$post->datetime_to,
			$period_status_type,
			$post->recurrence_type,
			$post->recurrence_frequency,
			$post->recurrence_sequence
		);

		CB_Query::copy_all_properties( $post, $object );

		return $object;
	}

  static function &factory(
		$ID,
		$period_id,
    $name,
		$datetime_part_period_start,
		$datetime_part_period_end,
		$datetime_from,
		$datetime_to,
		$period_status_type,
		$recurrence_type,
		$recurrence_frequency,
		$recurrence_sequence
  ) {
    // Design Patterns: Factory Singleton with Multiton
		if ( isset( self::$all[$ID] ) ) {
			$object = self::$all[$ID];
    } else {
			$reflection = new ReflectionClass( __class__ );
			$object     = $reflection->newInstanceArgs( func_get_args() );
      self::$all[$ID] = $object;
    }

    return $object;
  }

  public function __construct(
		$ID,
		$period_id,
    $name,
    $datetime_part_period_start, // DateTime
    $datetime_part_period_end,   // DateTime
    $datetime_from,              // DateTime
    $datetime_to,                // DateTime (NULL)
    $period_status_type,         // CB_PeriodStatusType
    $recurrence_type,
    $recurrence_frequency,
    $recurrence_sequence
  ) {
		CB_Query::assign_all_parameters( $this, func_get_args(), __class__ );

    $this->fullday = ( $this->datetime_part_period_start && $this->datetime_part_period_end )
			&& ( 	 $this->datetime_part_period_start->format( 'H:i:s' ) == '00:00:00'
					&& $this->datetime_part_period_end->format(   'H:i:s' ) == '23:59:59'
				 );
  }

  function classes() {
		return '';
  }

  function jsonSerialize() {
		return $this;
	}

	function post_meta() {
		return array(
			'name' => $this->name,
			'datetime_part_period_start' => $this->datetime_part_period_start->format( 'c' ),
			'datetime_part_period_end' => $this->datetime_part_period_end->format( 'c' ),
			'datetime_from' => $this->datetime_from->format( 'c' ),
			'datetime_to' => ( $this->datetime_to ? $this->datetime_to->format( 'c' ) : NULL ),
			'period_status_type' => $this->period_status_type->period_status_type_id,
			'recurrence_type' => $this->recurrence_type,
			'recurrence_frequency' => ( $this->recurrence_frequency ? $this->recurrence_frequency : 0 ),
			'recurrence_sequence' => $this->recurrence_sequence,
		);
	}
}

CB_Query::register_schema_type( 'CB_Period' );
