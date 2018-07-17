<?php
class CB_PeriodGroup implements JsonSerializable {
	// TODO: use this generic period class
  public static $database_table = 'cb2_period_groups';
	public static $postmeta_table = FALSE;
	public static $all = array();
  static $static_post_type = 'periodgroup';
  public static $post_type_args = array(
		'public'    => TRUE,
		'menu_icon' => 'dashicons-admin-settings',
		'label'     => 'Period Groups',
  );

  function post_type() {return self::$static_post_type;}

  static function &factory_from_wp_post( $post ) {
		CB_Query::get_metadata_assign( $post );

		$object = self::factory(
			$post->ID,
			$post->period_id
		);

		CB_Query::copy_all_properties( $post, $object );

		return $object;
	}

  static function &factory(
		$ID,
		$period_id
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
		$period_id
  ) {
		CB_Query::assign_all_parameters( $this, func_get_args(), __class__ );
  }

  function classes() {
		return '';
  }

  function jsonSerialize() {
		return $this;
	}
}

CB_Query::register_schema_type( 'CB_PeriodGroup' );
