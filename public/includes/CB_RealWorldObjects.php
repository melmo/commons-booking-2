<?php
// --------------------------------------------------------------------
// --------------------------------------------------------------------
// --------------------------------------------------------------------
class CB_User extends CB_PostNavigator implements JsonSerializable {
  public static $all    = array();
  public static $schema = 'with-periods'; //this-only, with-periods
  public function __toString() {return $this->user_login;}
  static $static_post_type     = 'user';
  public static $post_type_args = array(
		'public' => FALSE,
  );

  function post_type() {return self::$static_post_type;}

  protected function __construct( $ID, $user_login = NULL ) {
    $this->periods    = array();

    // WP_Post values
    $this->ID         = $ID;
    $this->user_login = $user_login;
    $this->post_title = $user_login;
    $this->post_type  = self::$static_post_type;

    parent::__construct( $this->periods );
  }

  static function factory( $ID, $user_login = NULL ) {
    // Design Patterns: Factory Singleton with Multiton
    $key = $ID;

    if ( isset( self::$all[$key] ) ) $object = self::$all[$key];
    else {
      $object = new self( $ID, $user_login );
      self::$all[$key] = $object;
    }

    return $object;
  }

  function add_period( &$period ) {
    array_push( $this->periods, $period );
  }

  function jsonSerialize() {
    $array = array(
      'ID' => $this->ID,
      'user_login' => $this->user_login,
    );
    if ( self::$schema == 'with-periods' ) $array[ 'periods' ] = &$this->periods;

    return $array;
  }
}
CB_Query::register_schema_type( 'CB_User' );

// --------------------------------------------------------------------
// --------------------------------------------------------------------
// --------------------------------------------------------------------
class CB_Post extends CB_PostNavigator implements JsonSerializable {
  public static $schema         = 'with-periods'; //this-only, with-periods
  public static $posts_table    = FALSE;
  public static $postmeta_table = FALSE;
  public static $database_table = FALSE;

  public function __toString() {return $this->post_title;}

  protected function __construct( $ID ) {
    $this->periods = array();

    // WP_Post values
    $this->ID         = $ID;

    parent::__construct( $this->periods );
  }

  function add_period( &$period ) {
    array_push( $this->periods, $period );
  }

  function get_field_this( $class = '', $date_format = 'H:i' ) {
		$permalink = get_the_permalink( $this );
		return "<a href='$permalink' class='$class' title='view $this'>$this</a>";
	}

	function get_the_content() {
		return property_exists( $this, 'post_content' ) ? $this->post_content : '';
	}

  function jsonSerialize() {
    $array = array(
      'ID' => $this->ID,
      'post_title' => $this->post_title,
    );
    if ( self::$schema == 'with-periods' ) $array[ 'periods' ] = &$this->periods;

    return $array;
  }
}

// --------------------------------------------------------------------
// --------------------------------------------------------------------
// --------------------------------------------------------------------
class CB_Location extends CB_Post implements JsonSerializable {
  public static $all = array();
  static $static_post_type  = 'location';
  public static $post_type_args = array(
		'menu_icon' => 'dashicons-admin-tools',
  );

  function post_type() {return self::$static_post_type;}

  protected function __construct( $ID ) {
    parent::__construct( $ID );
    $this->items = array();

    // WP_Post values
    $this->post_type = self::$static_post_type;
  }

  static function &factory_from_wp_post( $post ) {
		CB_Query::get_metadata_assign( $post ); // Retrieves ALL meta values

		$object = self::factory(
			$post->ID
		);

		CB_Query::copy_all_properties( $post, $object );

		return $object;
  }

  static function factory( $ID ) {
    // Design Patterns: Factory Singleton with Multiton
    $key = $ID;

    if ( isset( self::$all[$key] ) ) $object = self::$all[$key];
    else {
      $object = new self( $ID );
      self::$all[$key] = $object;
    }

    return $object;
  }

  function add_item( &$item ) {
    array_push( $this->items, $item );
    return $this;
  }

  function jsonSerialize() {
    return array_merge( parent::jsonSerialize(),
      array(
        'items' => &$this->items
    ));
  }
}
CB_Query::register_schema_type( 'CB_Location' );

// --------------------------------------------------------------------
// --------------------------------------------------------------------
// --------------------------------------------------------------------
class CB_Item extends CB_Post implements JsonSerializable {
  public static $all = array();
  static $static_post_type   = 'item';
  public static $post_type_args = array(
		'menu_icon' => 'dashicons-video-alt',
  );

  function post_type() {return self::$static_post_type;}

  protected function __construct( $ID ) {
    parent::__construct( $ID );

    // WP_Post values
    $this->post_type = self::$static_post_type;
  }

  static function &factory_from_wp_post( $post ) {
		CB_Query::get_metadata_assign( $post ); // Retrieves ALL meta values

		$object = self::factory(
			$post->ID
		);

		CB_Query::copy_all_properties( $post, $object );

		return $object;
  }

  static function &factory( $ID ) {
    // Design Patterns: Factory Singleton with Multiton
    $key = $ID;

    if ( isset( self::$all[$key] ) ) $object = self::$all[$key];
    else {
      $object = new self( $ID );
      self::$all[$key] = $object;
    }

    return $object;
  }
}
CB_Query::register_schema_type( 'CB_Item' );

