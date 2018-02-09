<?php
/**
 * Bookings Admin functions
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * This class should ideally be used to work with the public-facing side of the WordPress site.
 */
class CB_Bookings_Admin {

	// set vars
	public $list_slug = 'cb_bookings_table';
	public $edit_slug = 'cb_bookings_edit';
	public $names = array(
            'singular' => 'booking',
            'plural' => 'bookings',
	);
	public $metabox;

	public function __construct() {

	}

	/**
	 * Prepare the sql statement
	 *
	 * @param $item
	 */
	public function prepare_sql( $booking_id=FALSE ) {

		global $wpdb;
		$bookings_table = $wpdb->prefix . CB_BOOKINGS_TABLE;
		$timeframes_table = $wpdb->prefix . CB_TIMEFRAMES_TABLE;
		$slots_table = $wpdb->prefix . CB_SLOTS_TABLE;
		$slots_bookings_relation_table = $wpdb->prefix . 'wp_cb_slots_bookings_relation';

		// if we have a booking id, we query only one row
		$where = '';
		if ( $booking_id ) {
			$where = sprintf ( ' WHERE booking_id = %d', $booking_id );
		}

		$sql =(
		"SELECT
		booking_id,
		{$bookings_table}.slot_id,
		booking_status,
		user_id,
		{$slots_table}.timeframe_id,
		date,
		time_start,
		time_end,
		booking_code,
		{$slots_table}.description,
		item_id,
		location_id
		FROM {$bookings_table}
		LEFT JOIN {$slots_table} ON {$bookings_table}.slot_id = {$slots_table}.slot_id
		LEFT JOIN {$timeframes_table} ON {$slots_table}.timeframe_id = {$timeframes_table}.timeframe_id {$where}"
		);
		return $sql;
}
	/**
	 * Prepare the bookings sql statement
	 *
	 * @param $item
	 */
	public function prepare_booking_sql( $args=array() ) {

		global $wpdb;
		$bookings_table = $wpdb->prefix . CB_BOOKINGS_TABLE;
		$timeframes_table = $wpdb->prefix . CB_TIMEFRAMES_TABLE;
		$slots_table = $wpdb->prefix . CB_SLOTS_TABLE;
		$slots_bookings_relation_table = $wpdb->prefix . CB_SLOTS_BOOKINGS_REL_TABLE;


		// if we have a booking id, we query only one row
		$where_args = array();
		if ( ! empty ( $args[ 'booking_id' ] ) ) {
			$where_args[] = sprintf ( ' %s.booking_id = %d', $bookings_table, $args[ 'booking_id' ] );
		}
		if ( ! empty ( $args[ 'status' ] ) ) {
			$where_args[] = sprintf ( " %s.booking_status = '%s'", $bookings_table, $args[ 'status' ] );
		}

		// glue where
		if ( ! empty ( $where_args ) ) {
			$where = 'WHERE '. implode ( $where_args, ' AND ' );
		} else {
			$where = '';
		}

		$sql =(
			"
			SELECT
			{$bookings_table}.booking_id,
			{$bookings_table}.booking_status,
			{$bookings_table}.user_id,
			{$bookings_table}.booking_meta,
			{$bookings_table}.booking_time,
			{$slots_table}.slot_id,
			{$slots_table}.date,
			{$slots_table}.time_start,
			{$slots_table}.time_end,
			{$slots_table}.status AS slot_status,
			{$slots_table}.description AS slot_description,
			{$slots_table}.order AS slot_order,
			{$slots_table}.timeframe_id,
			{$timeframes_table}.timeframe_id,
			{$timeframes_table}.item_id,
			{$timeframes_table}.location_id,
			{$timeframes_table}.owner_id
			FROM {$bookings_table}
			LEFT JOIN {$slots_bookings_relation_table} ON {$bookings_table}.booking_id={$slots_bookings_relation_table}.booking_id
			LEFT JOIN {$slots_table} ON {$slots_bookings_relation_table}.slot_id={$slots_table}.slot_id
			LEFT JOIN {$timeframes_table} ON {$slots_table}.timeframe_id={$timeframes_table}.timeframe_id
			{$where}
		"
		);
		return $sql;
}
	/**
 * Return the number of items in the db
 *
 * @param $item
 */
public function get_item_count( ) {

	global $wpdb;
	$bookings_table = $wpdb->prefix . CB_BOOKINGS_TABLE;

	// will be used in pagination settings
	$total_items = $wpdb->get_var("
	SELECT COUNT(booking_id) FROM
	{$bookings_table}"
	);

	return $total_items;
}
/**
 * Get user info formatted to use in column
 *
 * @param $item
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
 * @param $item
 */
public function col_format_date( $date ) {

  return date ('j.n.y.', strtotime( $date  )) ;

}
/**
 * Get date/time formatted to use in column
 *
 * @param $item
 */
public function col_format_date_time( $date ) {

  return date ('j.n.y. - H', strtotime( $date  )) ;

}
/**
 * Get cb custom post type info formatted to use in column
 *
 * @param $item
 */
public function col_format_post( $id ) {

	$my_post_link = get_the_permalink( $id );
	$my_post_name = get_the_title( $id );

	$my_post = sprintf ( '<a href="%s">%s</a>', $my_post_link, $my_post_name );

	return $my_post;
}

	/**
 * This function renders our custom meta box
 * $item is row
 *
 * @param $item
 */
function bookings_edit_meta_box( $slots ) {

	$info = $slots[0];

	?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
		 <tr class="form-field">
        <th valign="top" scope="row">
            <label for="user"><?php _e('Name', 'commons-booking')?></label>
        </th>
        <td>
						<?php echo $this->col_format_user($info['user_id']); ?>
        </td>
    </tr>
		<!-- @TODO pull in more user info here -->
		 <tr class="form-field">
        <th valign="top" scope="row">
            <label for="date_time"><?php _e('Booking Date & time', 'commons-booking')?></label>
        </th>
        <td>
						<?php echo $this->col_format_date_time($info['booking_time']); ?>
        </td>
    </tr>
		 <tr class="form-field">
        <th valign="top" scope="row">
            <label for="item"><?php _e('Item', 'commons-booking')?></label>
        </th>
        <td>
						<?php echo $this->col_format_post($info['item_id']); ?>
        </td>
    </tr>
		 <tr class="form-field">
        <th valign="top" scope="row">
            <label for="location"><?php _e('Location', 'commons-booking')?></label>
        </th>
        <td>
						<?php echo $this->col_format_post($info['location_id']); ?>
        </td>
    </tr>
		 <tr class="form-field">
        <th valign="top" scope="row">
            <label for="status"><?php _e('Status', 'commons-booking')?></label>
        </th>
        <td>
 						<input id="booking_status" name="booking_status" type="text" style="width: 95%" value="<?php echo esc_attr($info['booking_status'])?>"
                   size="50" class="code" placeholder="<?php _e('Status', 'commons-booking')?>" required>
        </td>
    </tr>
		<?php foreach ( $slots as $slot ) { // loop through slots ?>
		 <tr class="form-field">
        <th valign="top" scope="row">
            <label for="date_time"><?php _e('Date & time', 'commons-booking')?></label>
        </th>
        <td>
						<?php echo $this->col_format_date($slot['date']); ?>:
						<?php echo $this->col_format_date($slot['time_start']); ?> -
						<?php echo $this->col_format_date($slot['time_end']); ?>
        </td>
    </tr>
		<?php } ?>
    </tbody>
</table>'
<?php
 }
/**
 * Simple function that validates data and retrieve bool on success
 * and error message(s) on error
 *
 * @param $item
 * @return bool|string
 */
function edit_form_validate_booking( $item ){
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

	function edit_form_do_metabox() {

		add_meta_box('persons_form_meta_box', __('Booking', 'commons-booking') , array( $this, 'bookings_edit_meta_box' ) , 'person', 'normal', 'default');
	}
}
?>
