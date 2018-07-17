<?php
// --------------------------------------------------------------------
// --------------------------------------------------------------------
// --------------------------------------------------------------------
class CB_PeriodStatusType implements JsonSerializable {
  public  static $database_table = 'cb2_period_status_types';
  public  static $all = array();
  public  static $standard_fields = array( 'name' );
  private $db_insert;
  static  $static_post_type = 'periodstatustype';
  public  static $post_type_args = array(
		'menu_icon' => 'dashicons-admin-settings',
		'label'     => 'Period Status Types',
  );

  function post_type() {return self::$static_post_type;}
  public function __toString() {return $this->name;}

  public function __construct(
		$ID,
    $period_status_type_id = NULL,
    $name     = NULL,
    $colour   = NULL,
    $opacity  = NULL,
    $priority = NULL,
    $return   = NULL,
    $collect  = NULL,
    $use      = NULL
  ) {
		CB_Query::assign_all_parameters( $this, func_get_args(), __class__ );

    // WP_Post values
    $this->post_title = $name;
		$this->post_type  = self::$static_post_type;
  }

  static function &factory_from_wp_post( $post ) {
		CB_Query::get_metadata_assign( $post ); // Retrieves ALL meta values

		$object = self::factory(
			$post->ID,
		  $post->period_status_type_id,
			$post->post_title,
			$post->colour,
			$post->opacity,
			$post->priority,
			( $post->return  != '0' ),
			( $post->collect != '0' ),
			( $post->use     != '0' )
		);

		CB_Query::copy_all_properties( $post, $object );

		return $object;
	}


  static function &factory(
		$ID,
    $period_status_type_id,
    $name     = NULL,
    $colour   = NULL,
    $opacity  = NULL,
    $priority = NULL,
    $return   = NULL,
    $collect  = NULL,
    $use      = NULL
  ) {
    // Design Patterns: Factory Singleton with Multiton
    $key = $period_status_type_id;
    if ( isset( self::$all[$key] ) ) $object = self::$all[$key];
    else {
      $Class = 'CB_PeriodStatusType';
      // Hardcoded system status types
      // TODO: create a trigger preventing deletion of these
      switch ( $period_status_type_id ) {
        case 1: $Class = 'CB_PeriodStatusType_Available'; break;
        case 2: $Class = 'CB_PeriodStatusType_Booked';    break;
        case 3: $Class = 'CB_PeriodStatusType_Closed';    break;
        case 4: $Class = 'CB_PeriodStatusType_Open';      break;
        case 5: $Class = 'CB_PeriodStatusType_Repair';    break;
      }

			$reflection = new ReflectionClass( $Class );
			$object     = $reflection->newInstanceArgs( func_get_args() );
      self::$all[$key] = $object;
    }

    return $object;
  }

  function styles() {
    $styles = '';
    if ( $this->colour   ) $styles .= 'color:#'  . $this->colour           . ';';
    if ( $this->priority ) $styles .= 'z-index:' . $this->priority + 10000 . ';';
    if ( $this->opacity && $this->opacity != 100 ) $styles .= 'opacity:' . $this->opacity / 100    . ';';
    return $styles;
  }

  function classes() {
    return '';
  }

  function indicators() {
    $indicators = array();
    array_push( $indicators, ( $this->return  === TRUE ? 'return'  : 'no-return'  ) );
    array_push( $indicators, ( $this->collect === TRUE ? 'collect' : 'no-collect' ) );
    array_push( $indicators, ( $this->use     === TRUE ? 'use'     : 'no-use'     ) );

    return $indicators;
  }

  function jsonSerialize() {
    return array_merge( (array) $this, array(
      'styles'     => $this->styles(),
      'classes'    => $this->classes(),
      'indicators' => $this->indicators(),
    ) );
  }
}

CB_Query::register_schema_type( 'CB_PeriodStatusType' );

// --------------------------------------------------------------------
// --------------------------------------------------------------------
// --------------------------------------------------------------------
class CB_PeriodStatusType_Available extends CB_PeriodStatusType {
	public function __construct(...$args) {
		call_user_func_array( array( get_parent_class(), '__construct' ), func_get_args() );
	}
}
class CB_PeriodStatusType_Booked    extends CB_PeriodStatusType {
	public function __construct(...$args) {
		call_user_func_array( array( get_parent_class(), '__construct' ), func_get_args() );
	}
}
class CB_PeriodStatusType_Closed    extends CB_PeriodStatusType {
	public function __construct(...$args) {
		call_user_func_array( array( get_parent_class(), '__construct' ), func_get_args() );
	}
}
class CB_PeriodStatusType_Open      extends CB_PeriodStatusType {
	public function __construct(...$args) {
		call_user_func_array( array( get_parent_class(), '__construct' ), func_get_args() );
	}
}
class CB_PeriodStatusType_Repair    extends CB_PeriodStatusType {
	public function __construct() {
		call_user_func_array( array( get_parent_class(), '__construct' ), func_get_args() );
	}
}
