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
* Renders a dropdown menu for items and locations
*
* @param string $post_type_name
* @param string $field_name
* @param string $selected id of the pre-selected item
*
* @uses cb_get_post_types_list
* @return mixed html dropdown
*/
function cb_edit_table_post_select_html( $post_type_name, $field_name, $selected ) {

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
function cb_edit_table_owner_select_html( $roles = array(), $selected ) {

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


}
add_action( 'plugins_loaded', array( 'CB_Strings', 'get_instance' ) );
