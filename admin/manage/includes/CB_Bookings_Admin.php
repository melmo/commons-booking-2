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
            'singular' => 'person',
            'plural' => 'persons',
	);
	public $metabox;

	function __construct() {

	}
	/**
 * This function renders our custom meta box
 * $item is row
 *
 * @param $item
 */
function edit_form_meta_box_handler( $item ) {
	?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="name"><?php _e('Name', 'commons-booking')?></label>
        </th>
        <td>
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
                   size="50" class="code" placeholder="<?php _e('Your name', 'commons-booking')?>" required>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="email"><?php _e('E-Mail', 'commons-booking')?></label>
        </th>
        <td>
            <input id="email" name="email" type="email" style="width: 95%" value="<?php echo esc_attr($item['email'])?>"
                   size="50" class="code" placeholder="<?php _e('Your E-Mail', 'commons-booking')?>" required>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="age"><?php _e('Age', 'commons-booking')?></label>
        </th>
        <td>
            <input id="age" name="age" type="number" style="width: 95%" value="<?php echo esc_attr($item['age'])?>"
                   size="50" class="code" placeholder="<?php _e('Your age', 'commons-booking')?>" required>
        </td>
    </tr>
    </tbody>
</table>';
<?php
 }
/**
 * Simple function that validates data and retrieve bool on success
 * and error message(s) on error
 *
 * @param $item
 * @return bool|string
 */
function edit_form_validate_person( $item ){
    $messages = array();

    if (empty($item['name'])) $messages[] = __('Name is required', 'commons-booking');
    if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'commons-booking');
    if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'commons-booking');
    //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
    //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
    //...

    if (empty($messages)) return true;
    return implode('<br />', $messages);
	}

	function edit_form_do_metabox() {

		add_meta_box('persons_form_meta_box', 'Person data', array($this, 'edit_form_meta_box_handler' ) , 'person', 'normal', 'default');
	}
}
?>
