<?php
/**
 * CB_Gui
 *
 * Holds code snippets for items, locations, etc
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Translatable Strings
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function initialize() {

    }
	/**
	 * Retrieve a interface string
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
 *
 * @param int $id
 * @param string $title
 * @return mixed $html
 */
public static function col_format_post( $id, $title = '' ) {

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
	 * @since 1.0.0
 *
 * @param string $date
 * @return mixed $html
 */
public static function col_format_date( $date ) {

	return date ('d.m.y', strtotime($date) );
}
	/**
	 * Format End date, return either date or ∞
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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
public static function cb_edit_table_slot_template_select_html( $field_name, $selected ) {

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
public static function cb_edit_table_post_select_html( $post_type_name, $field_name, $selected ) {

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
public static function cb_edit_table_owner_select_html( $roles = array(), $selected ) {

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

	$user_last = get_user_meta( $id, 'last_name',TRUE );
	$user_first = get_user_meta( $id, 'first_name',TRUE );
	$user_edit_link = get_edit_user_link( $id);

	$user = sprintf ( '<a href="%s">%s %s</a>', $user_edit_link, $user_first, $user_last );

	return $user;
}
/**
 * Get slot availability formatted to use in column
 *
 * @param array $item
 * @return mixed $html
 */
public static function col_format_availability( $item ) {

	$html = '';

	if ( isset ( $item['availability'] ) ) {
		$html = sprintf( __( 'Slots: %d total, %d booked, %d available ', 'commons-booking'),
					$item['availability']['total'],
					$item['availability']['booked'],
					$item['availability']['available']
					);
		}	else {
			$html = __('No slots configured', 'commons-booking');
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


}
add_action( 'plugins_loaded', array( 'CB_Strings', 'get_instance' ) );
