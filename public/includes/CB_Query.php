<?php
require( 'CB_Database.php' );
require( 'CB_PostNavigator.php' );
require( 'CB_PeriodItem.php' );
require( 'CB_RealWorldObjects.php' );
require( 'the_template_functions.php' );
require( 'CB_Time_Classes.php' );
require( 'WP_Query_integration.php' );

// System PERIOD_STATUS_TYPEs
define( 'PERIOD_STATUS_TYPE_AVAILABLE', 1 );
define( 'PERIOD_STATUS_TYPE_BOOKED', 2 );
define( 'PERIOD_STATUS_TYPE_CLOSED', 3 );
define( 'PERIOD_STATUS_TYPE_OPEN', 4 );
define( 'PERIOD_STATUS_TYPE_REPAIR', 5 );

class CB_Query {
	private static $schema_types = array();

  public  static $javascript_date_format = 'Y-m-d H:i:s';
  public  static $meta_NULL = 'NULL';
  public  static $without_meta = array(
		'key'     => 'item_ID',
		'value'   => 'NOT_USED',
		'compare' => 'NOT EXISTS',
	);

  // -------------------------------------------------------------------- Form helpers
  // TODO: move these functions in to a separate class
  static function location_options() {
    return CB_Query::get_options( 'posts', 'ID', 'post_title', "post_type = 'location' AND post_status = 'publish'" );
  }

  static function item_options() {
    return CB_Query::get_options( 'posts', 'ID', 'post_title', "post_type = 'item' AND post_status = 'publish'" );
  }

  static function user_options() {
    return CB_Query::get_options( 'users', 'ID', 'user_login' );
  }

  static function period_status_type_options() {
    return CB_Query::get_options( 'cb2_period_status_types', 'period_status_type_id', 'name' );
  }

  static function get_options( $table, $id_field = 'ID', $name_field = 'post_title', $condition = '1=1' ) {
    //TODO: cache this
    global $wpdb;
    return $wpdb->get_results( "select $id_field as ID, $name_field as name from $wpdb->prefix$table where $condition", OBJECT_K );
  }

  static function select_options( $records, $current_value = NULL, $add_none = TRUE, $by_name = FALSE ) {
    $html = '';
    if ( $add_none ) $html .= "<option value=''>--none--</option>";
    foreach ( $records as $value => $name ) {
      if ( is_object( $name ) ) $name  = $name->name;
      if ( $by_name )           $value = $name;
      $selected = ( $current_value == $value ? 'selected="1"' : '' );
      $html .= "<option value='$value' $selected>$name</option>";
    }
    return $html;
  }

  static function schema_options() {
		$post_types = array();
		$classes    = CB_Query::schema_types();
		foreach ( $classes as $post_type => $Class )
			$post_types[ $post_type ] = $post_type;
    return $post_types;
  }

  static function reset_data( $pass ) {
    global $wpdb;

    if ( WP_DEBUG && $pass == 'fryace4' ) {
			CB_Database_Truncate::factory_truncate( 'cb2_timeframe_options', 'option_id' )->run();
			CB_Database_Truncate::factory_truncate( 'cb2_global_period_groups', 'period_group_id' )->run();
			CB_Database_Truncate::factory_truncate( 'cb2_timeframe_period_groups', 'period_group_id' )->run();
			CB_Database_Truncate::factory_truncate( 'cb2_timeframe_user_period_groups', 'period_group_id' )->run();
			CB_Database_Truncate::factory_truncate( 'cb2_location_period_groups', 'period_group_id' )->run();
			CB_Database_Truncate::factory_truncate( 'cb2_period_group_period', 'period_group_id' )->run();
			CB_Database_Truncate::factory_truncate( 'cb2_periods', 'period_id' )->run();
			CB_Database_Truncate::factory_truncate( 'cb2_period_groups', 'period_group_id' )->run();
    }
  }

  // -------------------------------------------------------------------- Reflection
  // post_type to Class lookups
  static function register_schema_type( $Class ) {
		self::$schema_types[ $Class::$static_post_type ] = $Class;
  }

  static function &schema_types() {
    return self::$schema_types;
  }

  static function schema_type_class( $post_type ) {
		$post_types = self::schema_types();
		return isset( $post_types[$post_type] ) ? $post_types[$post_type] : NULL;
  }

  static function schema_type_all_objects( $post_type, $values_only = TRUE ) {
		$all = NULL;
		if ( $Class = CB_Query::schema_type_class( $post_type ) ) {
			if ( property_exists( $Class, 'all' ) ) {
				if ( $values_only ) $all = array_values( $Class::$all );
				else $all = $Class::$all;
			}
		}
		return $all;
  }

  // -------------------------------------------------------------------- WordPress integration
  // Complementary to WordPress
  // With CB Object understanding
	static function get_post_type( $post_type, $post = null, $output = OBJECT, $filter = 'raw' ) {
		// get_post() with table switch
		// This will use standard WP cacheing
		global $wpdb;
		$old_wpdb_posts = NULL;

		$wpdb->posts = "{$wpdb->prefix}posts";

		if ( $Class = self::schema_type_class($post_type) ) {
			if ( ! property_exists( $Class, 'posts_table' ) || $Class::$posts_table !== FALSE ) {
				$posts_table = "cb2_view_{$Class::$static_post_type}_posts";
				if ( property_exists( $Class, 'posts_table' ) && is_string( $Class::$posts_table ) )
					$posts_table    = $Class::$posts_table;
				$old_wpdb_posts = $wpdb->posts;
				$wpdb->posts    = "$wpdb->prefix$posts_table";
			}
		}

		$post = get_post( $post, $output, $filter );

		if ( $Class ) {
			if ( is_null( $post ) )
				throw new Exception( "[$Class/$post_type] not found in [$wpdb->posts] for [$post]" );
			if ( $old_wpdb_posts )
				$wpdb->posts = $old_wpdb_posts;
			if ( method_exists( $Class, 'factory_from_wp_post' ) )
				$post = $Class::factory_from_wp_post( $post );
		}

		return $post;
	}

	static function get_user( $ID ) {
		return get_user_by( 'ID', $ID );
	}

	static function ensure_correct_classes( &$records ) {
		// TODO: move static <Time class>::$all arrays on to the CB_Query
		// so that several embedded queries can co-exist
		// Currently, several queries happen in the page load (counts and things)
		// which gives wrong results unless cleared each time:
		CB_Week::$all = array();

		// In place change the records
		$post_classes = self::schema_types();
		foreach ( $records as &$record )
			$record = CB_Query::ensure_correct_class( $record, $post_classes );

		return $records;
  }

	static function ensure_correct_class( &$record, $post_classes = NULL ) {
    // Creation will aslo create the extra time based data structure
		if ( ! $post_classes ) $post_classes = self::schema_types();
		if ( property_exists( $record, 'post_type' ) && isset( $post_classes[$record->post_type] ) ) {
			$Class = $post_classes[$record->post_type];
			if ( method_exists( $Class, 'factory_from_wp_post' ) ) {
				$record = $Class::factory_from_wp_post( $record );
				if ( is_null( $record ) ) throw new Exception( "Failed to create [$Class] class from post" );
			}
		}

		return $record;
	}


	static function post_type_from_ID( $ID ) {
		global $wpdb;
		// TODO: Cache this post_type_from_ID
		return $wpdb->get_var( $wpdb->prepare(
			"SELECT post_type FROM {$wpdb->prefix}cb2_post_types
			  WHERE ID_base <= %d
			  ORDER BY ID_base DESC LIMIT 1", array( $ID ) )
		);
	}

	static function ID_from_id_post_type( $id, $post_type ) {
		global $wpdb;
		// TODO: Cache this ID_from_id()
		return $wpdb->get_var( $wpdb->prepare( "SELECT %d * ID_multiplier + ID_Base
			FROM {$wpdb->prefix}cb2_post_types
			WHERE post_type = %s", array( $id, $post_type ) )
		);
	}

	static function id_from_ID( $ID ) {
		global $wpdb;
		// TODO: Cache this id_from_ID()
		return $wpdb->get_var( $wpdb->prepare( "SELECT (%d - ID_Base) / ID_multiplier
			FROM {$wpdb->prefix}cb2_post_types
			WHERE ID_base <= %d
			ORDER BY ID_base DESC LIMIT 1", array( $ID, $ID ) )
		);
	}

	static function class_from_SELECT( $query ) {
		// Checks to see if the SQL is attempting to access
		// one of our post_types or post IDs
		// TODO: proper char by char decompilation is needed here in CB_Database
		$Class       = FALSE;
		$type        = 'SELECT';
		$query       = trim( $query );
		$query_type  = strtoupper( preg_replace( '/\\s.*/im', '', $query ) );
		$post_type   = NULL;

		if ( $query_type == $type ) {
			// = large ID
			preg_match( '/ID`?\s+=\s+(\d+)/im', $query, $ids );
			if ( is_array( $ids ) && count( $ids ) )
				$post_type = self::post_type_from_ID( $ids[1] );

			// IN( large ids )
			preg_match( '/ID`?\s+IN\s*\(\s*(\d+)/im', $query, $ids );
			if ( is_array( $ids ) && count( $ids ) )
				$post_type = self::post_type_from_ID( $ids[1] );

			// .post_type = '...'
			preg_match( '/\.post_type`?\s*=\s*\'([a-z0-9_]+)\'/im', $query, $post_types );
			if ( is_array( $post_types ) && count( $post_types ) )
				$post_type = $post_types[1];

			if ( $post_type ) $Class = self::schema_type_class( $post_type );
		}

		return $Class;
	}

	static function template_loader_context() {
		// Copied and altered from template-loader.php
		$context = FALSE;
		if     ( is_embed()           ) $context = 'embed';
		elseif ( is_404()             ) $context = '404';
		elseif ( is_search()          ) $context = 'search';
		elseif ( is_front_page()      ) $context = 'front_page';
		elseif ( is_home()            ) $context = 'home';
		elseif ( is_post_type_archive()  ) $context = 'post_type_archive';
		elseif ( is_tax()             ) $context = 'taxonomy';
		elseif ( is_attachment()      ) $context = 'attachment';
		elseif ( is_single()          ) $context = 'single';
		elseif ( is_page()            ) $context = 'page';
		elseif ( is_singular()        ) $context = 'singular';
		elseif ( is_category()        ) $context = 'category';
		elseif ( is_tag()             ) $context = 'tag';
		elseif ( is_author()          ) $context = 'author';
		elseif ( is_date()            ) $context = 'date';
		elseif ( is_archive()         ) $context = 'archive';

		// Extra: set $wp_query->is_list = TRUE to activate this
		if ( is_list() ) $context = 'list';

		return $context;
	}

	static function get_metadata_assign( $post ) {
		// Switch base tables to our views
		// Load all associated metadata and assign to the post object
		global $wpdb;
		$meta_type = 'post';

		if ( is_object( $post ) ) {
			if ( ! $post->ID )        throw new Exception( 'get_metadata_assign: $post->ID required' );
			if ( ! $post->post_type ) throw new Exception( 'get_metadata_assign: $post->post_type required' );

			if ( ! property_exists( $post, '_get_metadata_assign' ) ) {
				if ( $Class = self::schema_type_class( $post->post_type ) ) {
					// get_metadata( $meta_type, ... )
					//   meta.php has _get_meta_table( $meta_type );
					//   $table_name = $meta_type . 'meta';
					if ( ! property_exists( $Class, 'postmeta_table' ) || $Class::$postmeta_table !== FALSE ) {
						$post_type_stub         = CB_Query::substring_before( $post->post_type );
						$meta_type              = $post_type_stub;
						$meta_table_stub        = "{$meta_type}meta";
						$meta_table             = "cb2_view_{$meta_table_stub}";
						if ( property_exists( $Class, 'postmeta_table' ) && is_string( $Class::$postmeta_table ) )
							$meta_table = $Class::$posts_table;
						$wpdb->$meta_table_stub = "$wpdb->prefix$meta_table";
					}
				}

				$metadata = get_metadata( $meta_type, $post->ID );

				// Populate object
				foreach ( $metadata as $this_meta_key => $meta_value )
					$post->$this_meta_key = $meta_value[0];
				$post->_get_metadata_assign = TRUE;
			}
		}
  }

  // -------------------------------------------------------------------- Class, Function and parameter utilities
  static function ensure_bitarray( $object ) {
		if ( is_array( $object ) )
			$object = CB_Database::bitarray_to_int( $object );
		return $object;
  }

  static function substring_before( $string, $delimiter = '-' ) {
		return ( strpos( $string, $delimiter ) === FALSE ? $string : substr( $string, 0, strpos( $string, $delimiter ) ) );
	}

	static function ensure_datetime( $object ) {
		// Maintains NULLs
		$datetime = NULL;

		if      ( $object instanceof DateTime ) $datetime = &$object;
		else if ( is_string( $object ) )        $datetime = new DateTime( $object );
		else if ( is_null( $object ) )          $datetime = NULL;
		else throw new Exception( 'unhandled datetime type' );

		return $datetime;
	}

	static function ensure_time( $object ) {
		// TODO: ensure_time()
		return $object;
	}

	static function assign_all_parameters( $object, $parameter_values, $class_name = NULL, $method = '__construct' ) {
		// Take all function parameters
		// And make them properties of the class
		// Typical usage:
		//   CB_Query::assign_all_parameters( $this, func_get_args(), __class__ );
		if ( ! $class_name ) $class_name = get_class( $object );
		$reflection            = new ReflectionMethod( $class_name, $method ); // PHP 5, PHP 7
		$parameters            = $reflection->getParameters();
		$parameter_value_count = count( $parameter_values );
		foreach ( $parameters as $i => &$parameter ) {
			$name  = $parameter->name;
			$value = ( $i >= $parameter_value_count ? $parameter->getDefaultValue() : $parameter_values[$i] );
			$value = self::cast_parameter( $name, $value );

			$object->$name = $value;
			if ( WP_DEBUG && FALSE ) {
				print( "<i>$class_name->$name</i>: <b>" );
				if      ( $value instanceof DateTime ) print( $value->format( 'c' ) );
				else if ( is_object( $value ) ) var_dump( $value );
				else if ( is_array(  $value ) ) var_dump( $value );
				else print( $value );
				print( '</b><br/>' );
			}
		}
	}

	static function copy_all_properties( $from, $to, $overwrite = TRUE ) {
		foreach ( $from as $name => $value ) {
			if ( $overwrite || ! property_exists( $to, $name ) ) {
				$to->$name = self::cast_parameter( $name, $value );
			}
		}
	}

	static function cast_parameter( $name, $value ) {
		if ( ! is_null( $value ) ) {
			if      ( substr( $name, 0, 9 ) == 'datetime_' ) $value = self::ensure_datetime( $value );
			else if ( $name == 'date' )                      $value = self::ensure_datetime( $value );
			else if ( substr( $name, 0, 5 ) == 'time_' )     $value = $value;
			else if ( substr( $name, -3 ) == '_id' )         $value = (int) $value;
			else if ( substr( $name, -6 ) == '_index' )      $value = (int) $value;
			else if ( substr( $name, -9 ) == '_sequence' )   $value = self::ensure_bitarray( $value );
			else if ( $name == 'ID' )                        $value = (int) $value;
		}

		return $value;
	}

	static function sanitize_data_for_table( $table, $data ) {
		$new_data   = array();
		$columns    = CB_Database::columns( $table, TRUE );

		foreach ( $data as $field_name => $field_value ) {
			// Standard mappings
			$native_field_name = $field_name;
			switch ( $field_name ) {
				case 'post_title':   $native_field_name = 'name';        break;
				case 'post_content': $native_field_name = 'description'; break;
			}

			// Check table
			if ( isset( $columns[$native_field_name] ) ) {
				// Meta data queries use arrays
				if ( is_array( $field_value ) ) $field_value = $field_value[0];
				// Data conversion
				$column_definition = $columns[$native_field_name];
				switch ( self::substring_before( $column_definition->Type, '(' ) ) {
					case 'bit':
						$field_value = CB_Database::int_to_bitstring( $field_value );
						break;
				}
				$new_data[ $native_field_name ] = $field_value;
			}
		}

		return $new_data;
	}
}
