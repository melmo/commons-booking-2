<?php
/**
 * Timeframes Admin functions
 *
 * Handles editing, cancelling and detail view of bookings.
 *  Also provides formatting functions for row items in the table.
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Timeframes_Edit  {
	/**
	 * Slots object
	 *
	 * @var object
	 */
	public $slots_object;
	/**
	 * Slots array
	 *
	 * @var object
	 */
	public $slots_array;
	/**
	 * Current screen ('timeframe_settings', 'generate_slots', 'view')
	 *
	 * @var string
	 */
	public $screen;
	/**
	 * Form action
	 *
	 * @var string
	 */
	public $cb_form_action = '';
	public $form_footer;
	public $redirect;
	public $settings_args_defaults = array();
	public $settings_args = array();
	/**
	 * WP Admin slug, list
	 *
	 * @var string
	 */
	public $list_slug = 'cb_timeframes_table'; // slug for table screen
	/**
	 * WP Admin slug, edit
	 *
	 * @var string
	 */
	public $edit_slug = 'cb_timeframes_edit'; // slug for edit screen
	/**
	 * WP Admin slug, edit
	 *
	 * @var array
	 */
	public $names = array(
    'singular' => 'timeframe',
    'plural' => 'timeframes',
	);
	/**
	 * Possible screens
	 *
	 * @var array
	 */
	public $screens_array = array (
		'timeframe_settings',
		'generate_slots',
		'view'
	);
	/**
	 * Return from db
	 *
	 * @var bool|int
	 */
	public $sql_result = FALSE;

	public $timeframes_array;
	public $basename;
	public $message;

	public $timeframe_id;
	public $timeframe;
	public $timeframe_slots = array();

	// DB Tables
	public $bookings_table, $timeframes_table, $slots_table, $slots_bookings_relation_table;

	/**
	 * Constructor
	 */
	public function __construct() {

		global $wpdb;

		// set default settings_args
		$this->settings_args_defaults = array(
			'timeframe_id' => '',
			'item_id' => '',
			'location_id' => '',
			'date_start' => '',
			'date_end' => '',
			'description' => '',
			'owner_id' => '',
			'slot_template_select' => 0,
			'cb_form_action' => '',
			'modified' => ''
		);
		$this->timeframes_table = $wpdb->prefix . CB_TIMEFRAMES_TABLE;

		$this->screen = 'view'; // start screen

	}
	/* Initialise a new object for the retrieval of timeframes, set the context
	*/
	public function init_timeframes_object() {
			$this->timeframes_array = new CB_Object();
			$this->timeframes_array->set_context( 'admin-table' );
	}
	/**
	 * Get the timeframes id from the request array
	 *
	 * @param array $request
	 * @return array $timeframes
	 */
	public function get_timeframe_id( $request ) {

		if ( isset ( $request['timeframe_id'] ) ) {
			return $request['timeframe_id'];
		} else {
			return $this->timeframe_id;
		}

	}
	/**
	 * Get single timeframe
	 *
	 * @param array $request
	 * @return array $timeframe
	 */
	public function get_single_timeframe( $id ) {

		$this->init_timeframes_object();

		if (isset( $id )) { // we have a timeframe id

			$args = array (
				'timeframe_id' => $id,
				'scope' => '', // ignore dates
		 );
			$timeframe = $this->timeframes_array->get_timeframes( $args );

			$array = cb_obj_to_array( $timeframe );
			return $array[0];
		}
	}
/**
 * Return the number of timeframes in the db
 *
 * @return int $total_timeframes
 */
public function get_item_count( ) {

	global $wpdb;

	// will be used in pagination settings
	$total_timeframes = $wpdb->get_var("
	SELECT COUNT({$this->slots_table}.slot_id) FROM
	{$this->bookings_table}
	LEFT JOIN {$this->slots_bookings_relation_table} ON {$this->bookings_table}.booking_id={$this->slots_bookings_relation_table}.booking_id
	LEFT JOIN {$this->slots_table} ON {$this->slots_bookings_relation_table}.slot_id={$this->slots_table}.slot_id"
	);

	return $total_timeframes;
}
	/**
	 * Handle the request
	 * Creating timeframes with generating of slots and bookings
	 * Editing timeframes
	 *
	 * @param $request
	 */
	public function handle_request( $request ) {

		$this->setup_vars( $request );

		if ( isset( $request['nonce'] ) && wp_verify_nonce( $request['nonce'], $this->basename ) ) { // we are submitting the form
			$item = $this->merge_defaults( $request );

			if ( isset($request['cb_form_action'] ) && $request['cb_form_action'] == 'save_timeframe' ) { // we saving the settings

				$this->item_valid = $this->validate_timeframe_settings_form( $item );

				if ( $this->item_valid === true) { // validation passed

					if ( $this->timeframe_id == '' ) { // no id, so add new
						$sql_timeframe_result = $this->add_row( $item );
					} else { // id is present, so update
						$sql_timeframe_result = $this->update_row( $item );
					} // endif ($item_valid === true

					$this->message->output(); // diplay message(s)
					$this->maybe_set_next_screen( $sql_timeframe_result, 'generate_slots' );
					var_dump ($sql_timeframe_result);
				}
			} elseif ( isset($request['cb_form_action'] ) && $request['cb_form_action'] == 'generate_slots' ) { // we are creating the slots

				$timeframe = $this->get_single_timeframe( $this->timeframe_id );

				$this->slots_object->set_date_range ($timeframe['date_start'], $timeframe['date_end'] );

				$this->slots_object->set_slot_template_group( $item['slot_template_select'] );
				$templates = $this->slots_object->get_slot_template_group(); // get the templates array

				$this->slots_object->get_slots();
				$existing_dates = $this->slots_object->get_slot_dates_array();

				$this->slots_object->add_to_date_filter ( $existing_dates );

				$sql_slots_result = $this->slots_object->generate_slots( );

				$this->set_message( $sql_slots_result, __('Slots generated.'));

				$this->message->output(); // diplay message(s)
				// $this->set_screen('gernerate_slots');
				$this->maybe_set_next_screen( $sql_slots_result, 'view' );
			} // end if
		}

		// $this->timeframe_id = $this->get_timeframe_id ( $request );
		$this->timeframe = $this->get_single_timeframe( $this->timeframe_id );

		// setup the meta box
		$this->setup_screens( );
		return $this->timeframe_id;

	}
	/**
	 * Set up vars
	 *
	 * @param string $request
	 */
	public function setup_vars( $request ) {

		$this->settings_args = $this->merge_defaults( $request, $this->settings_args_defaults );

		if ( isset ( $this->timeframe_id ) && $this->timeframe_id != ''  ) { //  timeframe_id set
			$this->slots_object = new CB_Slots( $this->timeframe_id );
			$this->slots_array = $this->slots_object->get_slots(); //get previously created slots
		}

		if ( isset( $request['edit'] ) && $request['edit'] == 1 ) {
			$this->screen = 'timeframe_settings';
		} elseif ( isset( $request['generate_slots']) && $request['generate_slots'] == 1 ) {
			$this->screen = 'generate_slots';
		}

	}
	/**
	 * Set the next screen, if sql-result is positive
	 *
	 * @param int $result
	 * @param string $target
	 */
	public function maybe_set_next_screen( $result, $target ) {

		if ( $result ) { // operation successful, so set next screen
			$this->screen = $target;
		}
	}
	/**
	 * Set the next screen
	 */
	public function set_screen( $screen ) {

			$this->screen = $screen;
	}
	/**
	 * Set up the meta boxes
	 */
	public function setup_screens( ) {

		$this->form_fields_hidden = "";

		switch ( $this->screen ) {

			case 'timeframe_settings':
				// Metabox: Timeframe settings (Screen 1)
				add_meta_box('timeframe_form_meta_box', __('Timeframe settings', 'commons-booking') , 'render_timeframe_settings_meta_box' , 'timeframe', 'normal', 'default');

				$form_fields_redirect_action = '<input type="hidden" name="cb_form_action" value="save_timeframe">';
				$form_fields_hidden = sprintf ('<input type="hidden" name="modified" value="%s">',
				date("Y-m-d H:i:s") );
				$form_buttons = sprintf ('
					<button type="submit" value="submit" id="submit" class="button-primary" name="submit">%s</button>',
				__('Save and continue >>', 'commons-booking' ) );

				$this->form_footer = $form_fields_redirect_action . $form_fields_hidden . $form_buttons;
				break;

			case 'generate_slots':
				// Metabox: Timeframe generate slots (Screen 2)
				add_meta_box('timeframe_form_meta_box',  __('Generate Slots & Codes', 'commons-booking') , 'render_timeframe_generate_slots_meta_box' , 'timeframe', 'normal', 'default');
				$this->form_footer = sprintf ('
				<input type="hidden" name="cb_form_action" value="generate_slots">
				<input type="submit" value="%s" id="submit" class="button-primary" name="submit">',
				__('Generate slots >>', 'commons-booking' ) );
				break;

			case 'view':
				// Metabox: Timeframe detail (Screen 3)
				add_meta_box('timeframe_form_meta_box', __('Timeframe information', 'commons-booking') , 'render_timeframe_view_meta_box' , 'timeframe', 'normal', 'default');
				break;
		}

	}

	/**
	 * Return the meta box save/generate form_footer for each screen
	 */
	public function do_form_footer( ) {

		echo $this->form_footer;

	}
	/**
	 * Return the meta box title for each screen
	 *
	 * @return mixed $title
	 */
	public function do_title( ) {

		if ( $this->timeframe_id ) {

			$item = 	CB_Gui::col_format_post( $this->timeframe['item_id'] );
			$location = 	CB_Gui::col_format_post( $this->timeframe['location_id'] );
			$date_start = CB_Gui::col_format_date ( $this->timeframe['date_start'] );
			$date_end = CB_Gui::col_format_date ( $this->timeframe['date_end'] );

			$title = sprintf (
				__('Timeframe (%d): %s at %s, %s - %s', 'commons-booking' ),
				$this->timeframe_id,
				$item,
				$location,
				$date_start,
				$date_end
			);
		} else {
			$title = __('Create new', 'commons-booking');
		}
		return $title;

	}
	/**
	 * Save row in the bookings databse
	 *
	 * @param array $item
	 * @uses set_message
	 */
	public function add_row( $item ) {

		global $wpdb;

		$result = $wpdb->insert(
			$this->timeframes_table,
				array(
					'item_id' => $item['item_id'],
					'location_id' => $item['location_id'],
					'date_start' => $item['date_start'],
					'date_end' => $item['date_end'],
					'description' => $item['description'],
					'owner_id' => $item['owner_id'],
					'modified' => $item['modified']
				),
					array(
						'%d',	// item_id
						'%d',	// location_id
						'%s',	// date_start
						'%s',	// date_end
						'%s',	// description
						'%d',	// owner_id
						'%s' // modified

					)
			);
		$this->timeframe_id = $wpdb->insert_id;
		$this->settings_args['timeframe_id'] = $wpdb->insert_id;

		// save the id of the newly created entry @TODO

		$this->set_message( $result, __('Timeframe created.') );
		return ($result);
	}
	/**
	 * Update row in the bookings database
	 *
	 * @param $item
	 * @uses set_message
	 */
	public function update_row( $item ) {

				var_dump("row updated");

		global $wpdb;

		$result = $wpdb->update(
			$this->timeframes_table,
				array(
					'item_id' => $item['item_id'],
					'location_id' => $item['location_id'],
					'date_start' => $item['date_start'],
					'date_end' => $item['date_end'],
					'description' => $item['description'],
					'owner_id' => $item['owner_id']
				),
				array( 'timeframe_id' => $item['timeframe_id']),
					array(
						'%d',	// item_id
						'%d',	// location_id
						'%s',	// date_start
						'%s',	// date_end
						'%s',	// description
						'%d'	// value2
					),
				array( '%d' )
			);

		$this->set_message( $result, __('Timeframe updated.'));
		return ($result);
	}
	/**
	 * Create a new admin message.
	 * @param array|bool $result
	 * @param string $info
	 */
	public function set_message( $result, $info ) {

		$string = '';
		if ( ! empty ( $info ) ) {
			$string = ' ' . $info ;
		}

		if ($result) {
			$this->message = new WP_Admin_Notice( __( 'Success!', 'commons-booking' ) . $string, 'updated' );
		} else {
			$this->message = new WP_Admin_Notice( __( 'Error while trying: ', 'commons-booking') . $string, 'error' );
		}

	}
	/**
	 * Set the base file name (necessary to verify nonce).
	 *
	 * @param $filename
	 */
	public function set_basename( $filename ) {

		$this->basename = $filename;

	}
	/**
	 * Merge settings_args_defaults & input vars
	 *
	 * @param string $request
	 * @return array $item
	 */
	public function merge_defaults( $request ) {

		$item = shortcode_atts( $this->settings_args_defaults, $request );
		$this->timeframe_id = $item['timeframe_id'];

		return $item;
	}
/**
 * Get user info formatted to use in column
 *
 * @param int $id
 * @return string $user
 */
public function col_format_user( $id ) {

	$user_last = get_user_meta( $id, 'last_name',TRUE );
	$user_first = get_user_meta( $id, 'first_name',TRUE );
	$user_edit_link = get_edit_user_link( $id);

	$user = sprintf ( '<a href="%s">%s %s</a>', $user_edit_link, $user_first, $user_last );

	return $user;
}
/**
 * Get date formatted to use in column
 *
 * @param string $date
 * @return string $date
 */
public function col_format_date( $date ) {

  return date ('j.n.y.', strtotime( $date  )) ;

}
/**
 * Get date/time formatted to use in column
 *
 * @param int $datetime
 * @return string $datetime
 */
public function col_format_date_time( $date ) {

  return date ('j.n.y. - H', strtotime( $date  )) ;

}
/**
 * Get CB custom post type info formatted to use in column
 *
 * @param int $id
 * @return mixed $my_post
 */
public function col_format_post( $id, $title = '' ) {

	$my_post_name = get_the_title( $id );

	if ( ! empty ( $title ) ) {
		$my_post_link = edit_post_link ( $title, '', '', $id );
	} else {
		$my_post_link = edit_post_link ( $my_post_name, '', '', $id );

	}
	return $my_post_link;
}
/**
 * Validate @TODO
 *
 * @param $item
 * @return bool|string
 */
function validate_timeframe_settings_form( $item ){

		$message = '';

		if (empty($item['date_start'])) $message .= __('Start date is required. ', 'commons-booking');
		if (empty($item['date_end'])) $message .= __('End date is required. ', 'commons-booking');

    if ( ($message === '') ) return true;

		$this->set_message( FALSE , $message );
		$this->message->output(); // diplay message(s)

    return FALSE;
	}
}
?>