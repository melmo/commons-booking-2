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

	public $slots;
	public $screen;
	public $form_footer;
	public $redirect;
	public $default_fields = array();
	// set vars
	public $list_slug = 'cb_timeframes_table'; // slug for table screen
	public $edit_slug = 'cb_timeframes_edit'; // slug for edit screen
	public $names = array(
    'singular' => 'timeframe',
    'plural' => 'timeframes',
	);
	/**
	 * Default query args
	 *
	 * @var array
	 */
	// public $query_args = array (
	// 	'timeframe_id' => ''
	// );

	public $timeframes_array;
	public $basename;
	public $message;
	public $timeframe_id;
	public $location_id;
	public $item_id;
	public $date_start;
	public $date_end;
	public $timeframe_slots = array();

	public $metabox;
	// DB Tables
	public $bookings_table, $timeframes_table, $slots_table, $slots_bookings_relation_table;

	/**
	 * Constructor
	 */
	public function __construct() {

		global $wpdb;

		// set default fields
		$this->default_fields = array(
			'timeframe_id' => '',
			'item_id' => '',
			'location_id' => '',
			'date_start' => '',
			'date_end' => '',
			'description' => '',
			'owner_id' => '',
		);
		$this->init_timeframes_object();
		$this->timeframes_table = $wpdb->prefix . CB_TIMEFRAMES_TABLE;

		$this->slots = new CB_Slots(); //@TODO timeframe_id

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
	public function get_timeframe_id_from_request( $request ) {

		if ( $request['timeframe_id'] ) {
			return $request['timeframe_id'];
		} else {
			return $this->default_fields['timeframe_id'];
		}

	}
	/**
	 * Get single timeframe
	 *
	 * @param array $request
	 * @return array $timeframe
	 */
	public function get_single_timeframe( $id ) {

		if (isset( $id )) { // we have a timeframe id

			$args = array (
				'timeframe_id' => $id,
				'scope' => '', // ignore dates
		 );

			$timeframe = $this->timeframes_array->get_timeframes( $args );

			if (! $timeframe ) { // @ TODO: unklar
				$timeframe = $this->default_fields['timeframe_id'];
				$this->message = new WP_Admin_Notice( __( 'Saved', CB_TEXTDOMAIN ), 'updated' );
			}

			$array = cb_obj_to_array( $timeframe );
			return $array[0];
		}

	}
/**
 * Return the number of items in the db
 *
 * @return int $total_items
 */
public function get_item_count( ) {

	global $wpdb;

	// will be used in pagination settings
	$total_items = $wpdb->get_var("
	SELECT COUNT({$this->slots_table}.slot_id) FROM
	{$this->bookings_table}
	LEFT JOIN {$this->slots_bookings_relation_table} ON {$this->bookings_table}.booking_id={$this->slots_bookings_relation_table}.booking_id
	LEFT JOIN {$this->slots_table} ON {$this->slots_bookings_relation_table}.slot_id={$this->slots_table}.slot_id"
	);

	return $total_items;
}
	/**
	 * Handle the request
	 * Creating timeframes with generating of slots and bookings
	 * Editing timeframes
	 *
	 * @param $request
	 */
	public function handle_request( $request ) {

		var_dump($request);

		$this->screen = 'timeframe_settings';

		$this->setup_vars( $request );

		// check if we are saving and nonce is valid
		$nonce = isset( $request['nonce'] ) && wp_verify_nonce( $request['nonce'], $this->basename ) ? TRUE : FALSE;

		if ( empty ( $this->timeframe_id ) ) { // no timeframe id -> settings screen
			$this->screen = 'timeframe_settings';

		} elseif ( $this->timeframe_id ) { // timeframe_id & valid saving

			if ( $this->redirect == 'generate_slots' ) {
				$this->screen = 'generate_slots';
			}

			if ( $nonce ) { // we have a nonce





			} else { // just display

			} // end if nonce

		}

		$this->setup_screens( );




		$is_edit_timeframe_settings = ! ( isset ( $this->timeframe_id ) ) && ( empty ( $this->timeframe_slots  ) ) ? TRUE : FALSE;

		$is_edit_slots_generate = isset ( $this->timeframe_id ) && ( $generate_slots == 1 ) ? TRUE : FALSE;

		$is_timeframe_view = isset ( $timeframe_id ) && ( is_array ( $this->timeframe_slots  ) ) ? TRUE : FALSE;

		$echo = ( "<br><br>is_edit_timeframe_settings" . $is_edit_timeframe_settings .
		"<br>is_edit_slots_generate" . $is_edit_slots_generate . "<br>is_timeframe_view " . $is_timeframe_view

	);

	echo $echo;

		$this->slots->get_slot_template_group();

		// var_dump($this->slots);



		if ( isset( $request['nonce'] ) && wp_verify_nonce( $request['nonce'], $this->basename ) ) { // we are trying to save

			$item = $this->merge_defaults( $request );
			$this->item_valid = $this->validate_form( $item );

			// var_dump($item['timeframe_id']);

			if ( $this->item_valid === true) {
				if ( $item['timeframe_id'] == '' ) {

					$this->add_row( $item );

				} else { // id is present, so update

					$this->update_row( $item );
				} // endif ($item_valid === true

				$this->message->output(); // diplay message(s)
			}
		} else { // we are just displaying the form

		}
	}
	/**
	 * Set the timeframe id
	 *
	 * @param int $id
	 */
	public function set_timeframe_id( $id ) {

		$this->timeframe_id = $id;

	}
	/**
	 * Set up vars
	 *
	 * @param string $request
	 */
	public function setup_vars( $request ) {

		if ( isset( $request['timeframe_id'] ) ) {

			$id = $this->get_timeframe_id_from_request( $request );
			$this->set_timeframe_id( $id  );
			$tf = $this->get_single_timeframe( $id );

			// setup the rest of the variables
			$this->location_id = $tf['location_id'];
			$this->item_id = $tf['item_id'];
			$this->date_start = $tf['date_start'];
			$this->date_end = $tf['date_end'];

			$this->redirect = $request['redirect'];

			// get slots
			$this->timeframe_slots = $this->slots->get_slots( $this->timeframe_id );


		}

	}
	/**
	 * Set up the meta boxes
	 *
	 */
	public function setup_screens( ) {

		switch ( $this->screen ) {

			case 'timeframe_settings':
				// Metabox: Timeframe settings (Screen 1)
				add_meta_box('timeframe_form_meta_box', __('Timeframe settings', 'commons-booking') , 'render_timeframe_settings_meta_box' , 'timeframe', 'normal', 'default');
				$this->form_footer = sprintf ('
					<input type="hidden" name="redirect" value="generate_slots">
					<input type="submit" value="%s" id="submit" class="button-primary" name="submit">',
				__('Save and continue >>', 'commons-booking' ) );
				break;

			case 'generate_slots':
				// Metabox: Timeframe generate slots (Screen 2)
				add_meta_box('timeframe_form_meta_box',  __('Generate Slots & Codes', 'commons-booking') , 'render_timeframe_generate_slots_meta_box' , 'timeframe', 'normal', 'default');
				$this->form_footer = sprintf ('<input type="submit" value="%s" id="submit" class="button-primary" name="submit">',
				__('Generate slots >>', 'commons-booking' ) );
				break;

			case 'view':
				// Metabox: Timeframe detail (Screen 3)
				add_meta_box('timeframe_form_meta_box', $this->do_title() . __('Timeframe settings', 'commons-booking') , 'render_timeframe_view_meta_box' , 'timeframe', 'normal', 'default');
				break;
		}

		// Metabox: Timeframe generate slots (Screen 2)
		// add_meta_box('timeframe_form_meta_box', __('Timeframe settings', 'commons-booking') , 'render_timeframe_generate_slots_meta_box' , 'timeframe', 'normal', 'default');

		// Metabox: Timeframe detail (Screen 3)
		// add_meta_box('timeframe_form_meta_box', __('Timeframe settings', 'commons-booking') , 'render_timeframe_view_meta_box' , 'timeframe', 'normal', 'default');

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

			$item = 	CB_Gui::col_format_post($this->item_id);
			$location = 	CB_Gui::col_format_post($this->location_id);

			$title = sprintf (
				__('Timeframe (%d): %s at %s, %s - %s', 'commons-booking' ),
				$this->timeframe_id,
				$item,
				$location,
				$this->date_start,
				$this->date_end
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
					'owner_id' => $item['owner_id']
				),
					array(
						'%d',	// item_id
						'%d',	// location_id
						'%s',	// date_start
						'%s',	// date_end
						'%s',	// description
						'%d'	// value2
					)
			);
		$item['timeframe_id'] = $wpdb->insert_id; // save the id of the newly created entry @TODO

		$this->set_message($result);
	}
	/**
	 * Update row in the bookings database
	 *
	 * @param $item
	 * @uses set_message
	 */
	public function update_row( $item ) {

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

		$this->set_message($result);
	}
	/**
	 * Create a new admin message.
	 *
	 * @param array|bool $result
	 */
	public function set_message( $result ) {

		if ($result) {
			$this->message = new WP_Admin_Notice( __( 'Saved', CB_TEXTDOMAIN ), 'updated' );
		} else {
			$this->message = new WP_Admin_Notice( __( 'Error saving', CB_TEXTDOMAIN ), 'error' );
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
	 * Merge default_fields & input vars
	 *
	 * @param string $request
	 * @return array $item
	 */
	public function merge_defaults( $request ) {

		$item = shortcode_atts( $this->default_fields, $request );
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
function validate_form( $item ){
    $messages = array();

    // if (empty($item['name'])) $messages[] = __('Name is required', 'commons-booking');
    // if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'commons-booking');
    // if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'commons-booking');
    //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
    //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
    //...

    if (empty($messages)) return true;
    return implode('<br />', $messages);
	}
}
?>
