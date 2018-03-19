<?php
/**
 * Format common snippets
 *
 * Provides snippets for items, locations, etc in the desired formatting
 * Example Usage: CB_Gui::col_format_post( $id );
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Gui {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
    /**
	 * Object holding all strings
	 *
	 * @var object
	 */
    public $strings = array ();

	/**
	 * Return an instance of this class.
	 *
	 * @since 2.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			try {
				self::$instance = new self;
				self::initialize();
			} catch ( Exception $err ) {
				do_action( 'commons_booking_admin_failed', $err );
				if ( WP_DEBUG ) {
					throw $err->getMessage();
				}
			}
		}
		return self::$instance;
    }
	/**
	 * Initialize
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function initialize() {

    }
	/**
	 * Retrieve a interface string
	 *
	 * @TODO: not in use, maybe depreciate
	 *
	 * @since 2.0.0
	 *
	 * @param $category The string category
	 * @param $key 		Optional: The key
	 *
	 * @return array string
	 */
	public static function get_gui( $id, $args ) {

    $get_gui =  array(
			'cal' => array (
				'weekday_names' => array(
					__('Monday', 'commons-booking'),
					__('Tuesday', 'commons-booking'),
					__('Wednesday', 'commons-booking'),
					__('Thursday', 'commons-booking'),
					__('Friday', 'commons-booking'),
					__('Saturday', 'commons-booking'),
					__('Sunday', 'commons-booking'),
				)
			),
			'category' => array (
				'key' => 'testing this'
            )
		);

	}
	/**
	 * Format WP posts as clickable links with title
	 *
	 * @since 2.0.0
 *
 * @param int $id
 * @param string $title
 * @return mixed $html
 */
public static function col_format_post( $id, $title = '') {

	if ( empty ( $title ) ) {
		$title = get_the_title( $id );
	}

	$html = sprintf ( '<a href="%s">%s</a>',
		get_edit_post_link( $id ),
		$title
	);

	return $html;
}
	/**
	 * Format Dates @TODO: localize
	 *
	 * @since 2.0.0
 *
 * @param string $date
 * @return mixed $html
 */
public static function col_format_date( $date ) {

	return date ('d.m.y', strtotime($date) );
}
/**
 * Get date/time formatted to use in column
 *
 * @since 2.0.0
 *
 * @param string $datetime
 * @return string $datetime
 */
public static function col_format_date_time( $date ) {

  return date ('j.n.y. - H', strtotime( $date  )) ;

}
	/**
	 * Format End date, return either date or ∞
	 *
	 * @since 2.0.0
 *
 * @param string $date
 * @return mixed $html
 */
public static function col_format_date_end( $date, $has_end_date ) {
	if ( ! $has_end_date ) {
		return '&#8734; ' . __('(automatically extended)', 'commons-booking');
	} else {
		return CB_Gui::col_format_date($date);
	}
}


	/**
	 * Display a front-facing message
	 *
	 * @since 2.0.0
	 *
	 * @param $string 		The message
	 * @param $category Optional The message category
	 * @param $args array 		Optional: The key
	 *
	 * @return string
	 */
	public function maybe_do_message( $string, $category='notice' ) {

		$message = '';
		if ( ! empty ( $string ) ) {
		$message = sprintf (
			'<div class="cb-message cb-%s">%s</div>',
			$category,
			$string
		);
			return $message;
		}
	}
/**
* Renders a dropdown menu for slot templates
*
* @param string $slot_templates array
* @param string $selected id of the pre-selected item
*
* @uses cb_get_post_types_list
* @return mixed html dropdown
*/
public static function edit_table_slot_template_select_html( $field_name, $selected ) {

	$html = '';

	$slot_templates_array = cb_get_slot_templates_dropdown();

	if ( isset ( $slot_templates_array ) && is_array ( $slot_templates_array ) ) {

		$html .= '<select name="' . $field_name .'" size="1" class="cb_'. $field_name .'">';

		if ( ! $selected ) {
			$new = "selected disabled"; } else { $new = ""; } // if new entry, set pre-selected

			foreach ( $slot_templates_array as $key => $value ) { // loop through posts array

				if ( $key == $selected ) {
					$s = ' selected'; } else { $s = '';
				}
				$html .= '<option value="' . $key . '"' . $s .' >' . $key . ' - ' . $value . '</option>';
			} // endforeach

			$html .= '</select>';
		} else {
			$html .= sprintf( __('<span class="cb-notice">No slot templates found.</span>', 'commons-booking' ));
		}

		return $html;
}
/**
* Renders a dropdown menu for items and locations
*
* @param string $post_type_name
* @param string $field_name
* @param string $selected id of the pre-selected item
*
* @uses cb_get_post_types_list
* @return mixed html dropdown
*/
public static function edit_table_post_select_html( $post_type_name, $field_name, $selected ) {

	$html = '';
	$post_types_array = cb_get_post_types_list( $post_type_name );

	if ( isset ( $post_types_array ) && is_array ( $post_types_array ) ) {

		$html .= '<select name="' . $field_name .'" size="1" class="cb_'. $field_name .'">';

		if ( ! $selected ) {
			$new = "selected disabled"; } else { $new = ""; } // if new entry, set pre-selected

			foreach ( $post_types_array as $key => $value ) { // loop through posts array

				if ( $key == $selected ) {
					$s = ' selected'; } else { $s = '';
				}
				$html .= '<option value="' . $key . '"' . $s .' >' . $value . '</option>';
			} // endforeach

			$html .= '</select>';
		} else {
			$html .= sprintf( __('<span class="cb-notice">No items of type %s found. Please create at least one.</span>', 'commons-booking' ), $post_type_name);
		}

		return $html;
}
/**
* Renders a dropdown menu users
*
* @param string $post_type_name
* @param string $field_name
* @param string $selected id of the pre-selected item
*
* @uses cb_get_post_types_list
* @return mixed html dropdown
*/
public static function edit_table_owner_select_html( $roles = array(), $selected ) {

	$html = '';
	$users_array = cb_get_users_list( );

	if ( isset ( $users_array ) && is_array ( $users_array ) ) {

		$html .= '<select name="owner_id" size="1" class="cb_owner_select">';

		if ( ! $selected ) {
			$new = "selected disabled"; } else { $new = ""; } // if new entry, set pre-selected

			foreach ( $users_array as $key => $value ) { // loop through posts array

				if ( $key == $selected ) {
					$s = ' selected'; } else { $s = '';
				}
				$html .= '<option value="' . $key . '"' . $s .' >' . $value . '</option>';
			} // endforeach

			$html .= '</select>';
		} else {
			$html .= sprintf( __('<span class="cb-notice">No users found. Please create at least one.</span>', 'commons-booking' ));
		}

		return $html;
}
/**
 * Get user info formatted to use in column
 *
 * @param int $id
 * @return string $user
 */
public static function col_format_user( $id ) {

	$user = get_user_by( 'id',  $id );

	$user_nicename = $user->user_nicename;
	$user_edit_link = get_edit_user_link( $user->ID );

	$user_html = sprintf ( '<a href="%s"> %s </a>', $user_edit_link, $user_nicename);

	return $user_html;
}
/**
 * Get slot availability formatted to use in column
 *
 * @param array $item
 * @return mixed $html
 */
public static function col_format_availability( $availability = '' ) {

	$html = '';

	if ( isset ( $availability ) ) {
		$html = sprintf( __( 'Slots: %d total, %d booked, %d available ', 'commons-booking'),
					$availability['total'],
					$availability['booked'],
					$availability['available']
					);
		}	else {
			$html = __('No slots configured', 'commons-booking');
		}
	return $html;
}
/**
 * Get timeframe for display in admin tables
 *
 * @param array $item
 * @return mixed $html
 */
public static function col_format_timeframe( $post_id ) {

	$html = '';

	$timeframe_object = new CB_Timeframe;

	$args = array (
		'item_id' => $post_id, // This template is called by item, so you need to supply the id
		'order_by' => 'date_start',
		'order' => 'ASC'
	);

	$timeframes = $timeframe_object->get( $args );

	if ( isset( $timeframes ) && is_array( $timeframes ) ) {
		foreach ($timeframes as $timeframe) {
			$date_start = self::col_format_date( $timeframe->date_start);
			$date_end = self::col_format_date_end( $timeframe->date_end, $timeframe->has_end_date);
			$availability = self::col_format_availability( $timeframe->availability);
			$edit_link =  self::timeframes_admin_url( 'view', $post_id );
			$location = get_the_title( $timeframe->location_id );
			$html .= sprintf( '<strong>%s - %s</strong> %s<br>%s<br>%s<hr>',$date_start, $date_end, $edit_link, $location, $availability );
		}
	} else {
		$html .=  __( 'No timeframes configured.', 'commons-booking' );
		$html .= ' ' . self::timeframes_admin_url( 'table', $post_id );
	}
	return $html;
}
/**
 * List slot templates
 *
 * @param array $slot_template_group_id
 * @return mixed $html
 */
public static function list_slot_templates_html( $slot_template_group_id, $list_format=TRUE ) {

	$html = '';
	$html_rows = '';

	$slots = new CB_Slots();
	$slots->set_slot_template_group( $slot_template_group_id );
	$templates = $slots->get_slot_template_group( );

	foreach ( $templates as $template_group ) {
		foreach ( $template_group as $template_slot) {
			$row =  sprintf (
				'%s ( %s - %s) ',
				$template_slot['description'],
				$template_slot['time_start'],
				$template_slot['time_end']
			);
			$html_rows .= ( $list_format ) ? '<li>' . $row . '</li>' : $row;
		}
	}
	$html .= ( $list_format ) ? '<ol>' . $html_rows . '</ol>' : $html_rows;

	return $html;
}
/**
 * List location opening times (days + hours)
 *
 * @param array $location_id
 * @return mixed $html
 */
public static function list_location_opening_times_html( $location_id) {
	$location = new CB_Location ( $location_id );
	$opening_times = $location->get_opening_times();
	$pickup_mode = $location->get_pickup_mode();

	$html = '';

	if ( $pickup_mode == 'opening_times' && is_array( $opening_times ) && ! empty ( $opening_times ) ){
		$html .= '<ul class="opening_hours_list">';
		foreach ( $opening_times as $day => $hours_array ) {
			$html .=  sprintf ('<li>%s<ul>', jddayofweek( $day, 2 ) );

			// from / till may or may not be set.
			$from = ( ! empty ($hours_array ) ) ? $hours_array['from'] : '';
			$till = ( ! empty ($hours_array ) ) ? $hours_array['till'] : '';

			$html .= sprintf( '<li>%s</li><li>%s</li>',
				$from,
				$till
			);

			$html .= '</ul></li>';

		} // end foreach
		$html .= '</ul>';
	} elseif ( $pickup_mode == 'personal_contact' ) {
		$html = __('No opening times, users will have to contact the location.');
	} else {
		$html = __('No opening times defined for this location.');
	}
	return $html;
}

/**
 * Return settings url
 *
 * @param string $options_page
 * @return mixed $html
 */
public static function settings_admin_url( $options_page = '' ) {

	if ( $options_page ) {
		$url =  admin_url( 'admin.php?page=cb_settings_page#tabs-' . $options_page );
	} else {
		$url =  admin_url( 'admin.php?page=cb_settings_page' );
	}

	$link = sprintf ( '<a href="%s" target="_blank">' . __( 'Settings', 'commons-booking') . '</a>', $url );

	return $link;
}
/**
 * Return timeframes admin url(s)
 *
 * @TODO enable targets: table, edit(with id), view
 *
 * @return mixed $html
 */
public static function timeframes_admin_url( $target='table', $item_id = '' ) {

	$item_edit = '';
	if ( $item_id ) {
		$item_edit = '&item_id=' . $item_id;
	}

	$url = admin_url( 'admin.php?page=cb_timeframes_table' . $item_edit );

	$link = sprintf ( '<a href="%s">' . __( 'Edit', 'commons-booking') . '</a>', $url );

	return $link;
}

}
add_action( 'plugins_loaded', array( 'CB_Gui', 'get_instance' ) );
