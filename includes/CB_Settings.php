<?php
/**
 * Admin settings & CB-Posttypes Metaboxes for Commons Booking
 *
 * Global settings, settings for items, timeframes, etc
 * Get setting usage: $setting = CB_Settings::get( 'bookings', 'max-slots');
 *
 * All post type metaboxes are defined here.
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
class CB_Settings {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	/**
	 * Settings array
	 *
	 * @var object
	 */
	protected static $plugin_settings;
	/**
	 * Settings groups, 1 group is a metabox
	 *
	 * @var array
	 */
	protected static $settings_groups;
	/**
	 * Admin menu tabs
	 *
	 * @var array
	 */
	protected static $admin_settings_tabs;
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
				do_action( 'commons_booking_settings_failed', $err );
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

		// self::add_settings_tab( 'general', __( 'General', 'commons-booking' ) );
		// self::add_settings_tab( 'pages', __( 'Pages', 'commons-booking' ) );
		// self::add_settings_tab( 'bookings', __( 'Bookings', 'commons-booking' ) );

		self::apply_settings_templates();


		}
	/**
	 * Booking settings template
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function init_settings() {

	}

	/**
	 * Booking settings template
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_bookings() {

		$settings_bookings = array(
			'name' => __( 'Bookings', 'commons-booking' ),
			'slug' => 'bookings',
			'tab'  => 'bookings',
			'fields' => array (
					array (
						'name'             => __( 'Maximum slots', 'commons-booking' ),
						'desc'             => __( 'Maximum slots a user is allowed to book at once', 'commons-booking' ),
						'id'               => 'max-slots',
						'type'             => 'text_small',
						'default'          => 3
					),
					array(
						'name'             => __( 'Consecutive slots', 'commons-booking' ),
						'desc'             => __( 'Slots must be consecutive', 'commons-booking' ),
						'id'               => 'consecutive-slots',
						'type'             => 'checkbox',
						'default' 				=> cmb2_set_checkbox_default_for_new_post( true )
					),
					array(
						'name'             => __( 'Use booking codes', 'commons-booking' ),
						'desc'             => __( 'Create codes for every slot', 'commons-booking' ),
						'id'               => 'use-codes',
						'type'             => 'checkbox',
						'default' 				=> cmb2_set_checkbox_default_for_new_post( true )
					),
				)
			);
		return $settings_bookings;
	}
	/**
	 * calendar settings template
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_calendar() {

		$settings_calendar = array(
			'name' => __( 'Calendar', 'commons-booking' ),
			'slug' => 'calendar',
			'tab'  => 'bookings',
			'fields' => array (
					array(
						'name'             => __( 'Calendar limit', 'commons-booking' ),
						'desc'             => __( 'Calendar limit', 'commons-booking' ),
						'id'               => 'limit',
						'type'             => 'text_small',
						'default'          => '30',
						'description'			 => __('Limit calendars to X future days.')
					),
					array(
						'name'             => __( 'Holidays', 'commons-booking' ),
						'desc'             => __( 'Select country to show local holidays in the calendar and block those holidays from pickup/return.', 'commons-booking' ),
						'id'               => 'holiday_provider',
						'type'             => 'select',
						'show_option_none' => true,
						'options' 				 => CB_Holidays::get_providers()
					)
			)
		);
		return $settings_calendar;
	}
	/**
	 * Pages settings template
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_pages() {

		$settings_pages = array(
			'name' => __( 'Pages', 'commons-booking' ),
			'slug' => 'pages',
			'fields' => array (
					array(
						'before_row'       => __('Pages: Items and calendar', 'commons-booking' ), // Headline
						'name'             => __( 'Items page', 'commons-booking' ),
						'desc'             => __( 'Display list of items on this page', 'commons-booking' ),
						'id'               => 'item-page-id',
						'type'             => 'select',
						'show_option_none' => true,
						'default'          => 'none',
						'options'          => cb_get_pages_dropdown(),
					),
					array(
						'name'             => __( 'Locations page', 'commons-booking' ),
						'desc'             => __( 'Display list of Locations on this page', 'commons-booking' ),
						'id'               => 'location-page-id',
						'type'             => 'select',
						'show_option_none' => true,
						'default'          => 'none',
						'options'          => cb_get_pages_dropdown(),
					),
					array(
						'name'             => __( 'Calendar page', 'commons-booking' ),
						'desc'             => __( 'Display the calendar on this page', 'commons-booking' ),
						'id'               => 'calendar-page-id',
						'type'             => 'select',
						'show_option_none' => true,
						'default'          => 'none',
						'options'          => cb_get_pages_dropdown(),
					),
					array(
						'before_row'       => __('Pages: Bookings', 'commons-booking' ), // Headline
						'name'             => __( 'Booking review page', 'commons-booking' ),
						'desc'             => __( 'Shows the pending booking, prompts for confimation.', 'commons-booking' ),
						'id'               => 'booking-review-page-id',
						'type'             => 'select',
						'show_option_none' => true,
						'default'          => 'none',
						'options'          => cb_get_pages_dropdown(),
					),
					array(
						'name'             => __( 'Booking confirmed page', 'commons-booking' ),
						'desc'             => __( 'Displayed when the user has confirmed a booking.', 'commons-booking' ),
						'id'               => 'booking-confirmed-page-id',
						'type'             => 'select',
						'show_option_none' => true,
						'default'          => 'none',
						'options'          => cb_get_pages_dropdown(),
					),
					array(
						'name'             => __( 'Booking page', 'commons-booking' ),
						'desc'             => __( '', 'commons-booking' ),
						'id'               => 'booking-page-id',
						'type'             => 'select',
						'show_option_none' => true,
						'default'          => 'none',
						'options'          => cb_get_pages_dropdown(),
					),
					array(
						'name'             => __( 'My bookings page', 'commons-booking' ),
						'desc'             => __( 'Shows the user´s bookings.', 'commons-booking' ),
						'id'               => 'user-bookings-page-id',
						'type'             => 'select',
						'show_option_none' => true,
						'default'          => 'none',
						'options'          => cb_get_pages_dropdown(),
					)
				)
		);
		return $settings_pages;
	}
	/**
	 * Codes settings template
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_codes() {

		$settings_codes = array(
			'name' => __( 'Codes', 'commons-booking' ),
			'slug' => 'codes',
			'fields' => array (
					array(
						'name'             => __( 'Codes', 'commons-booking' ),
						'desc'             => __( 'Booking codes, comma-seperated', 'commons-booking' ),
						'id'               => 'codes-pool',
						'type'             => 'textarea_code',
						'default'          => 'none',
				)
			)
		);
		return $settings_codes;
	}
	/**
	 * Strings (for possible overwrite in the backend
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_cb_strings() {

		$strings_array = CB_Strings::get();
		$fields_array = array();

		// reformat array to fit our cmb2 settings fields
		foreach ( $strings_array as $category => $fields) {
			// add title field
			$fields_array[] = array(
						'name'  => $category,
						'id'    => $category . '-title',
						'type'  => 'title',
			);
			foreach ($fields as $field_name => $field_value ) {

				$fields_array[] = array(
							'name'             => $field_name,
							'id'               => $category . '_' . $field_name,
							'type'             => 'textarea_small',
							'default'					 => $field_value
				);
			} // end foreach fields

		} // end foreach strings_array

		$settings_template_cb_strings = array(
			'name' => __( 'Strings', 'commons-booking' ),
			'slug' => 'strings',
			'show_in_plugin_settings' => true,
			'fields' => $fields_array
		);

		return $settings_template_cb_strings;
	}
	/**
	 * Locations meta box: opening times template
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_location_opening_times() {

		$settings_template_location_opening_times = array(
			'name' => __( 'Location Opening Times', 'commons-booking' ),
			'slug' => 'locations',
			'show_in_plugin_settings' => false,
			'fields' => array (
					array(
						'before_row'       => __('Monday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on Mondays', 'commons-booking' ),
						'id'               => 'location-open-mon',
						'type'             => 'checkbox',
					),
					array(
						'before_row'       => __('Monday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on Mondays', 'commons-booking' ),
						'id'               => 'location-open-mon',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'location-open-mon-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'mon-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'location-open-mon-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'mon-hidden'
					),
					array(
						'before_row'       => __('tuesday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on tuesdays', 'commons-booking' ),
						'id'               => 'location-open-tue',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'location-open-tue-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'tue-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'location-open-tue-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'tue-hidden'
					),
					array(
						'before_row'       => __('wednesday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on wednesdays', 'commons-booking' ),
						'id'               => 'location-open-wed',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'location-open-wed-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'wed-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'location-open-wed-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'wed-hidden'
					),
					array(
						'before_row'       => __('thursday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on thursdays', 'commons-booking' ),
						'id'               => 'location-open-thu',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'location-open-thu-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'thu-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'location-open-thu-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'thu-hidden'
					),
					array(
						'before_row'       => __('friday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on fridays', 'commons-booking' ),
						'id'               => 'location-open-fri',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'location-open-fri-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'fri-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'location-open-fri-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'fri-hidden'
					),
					array(
						'before_row'       => __('saturday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on saturdays', 'commons-booking' ),
						'id'               => 'location-open-sat',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'location-open-sat-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'sat-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'location-open-sat-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'sat-hidden'
					),
					array(
						'before_row'       => __('sunday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on sundays', 'commons-booking' ),
						'id'               => 'location-open-sun',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'location-open-sun-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'sun-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'location-open-sun-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'sun-hidden'
					),
			)
		);
		return $settings_template_location_opening_times;
	}
	/**
	 * Locations meta box: choose pickup mode template
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_location_pickup_mode() {

		$settings_template_location_pickup_mode = array(
			'name' => __( 'Pickup mode', 'commons-booking' ),
			'slug' => 'locations',
			'show_in_plugin_settings' => false,
			'fields' => array (
				array(
						'name'             => __( 'Pickup mode', 'commons-booking' ),
						'id'               => 'location-pickup-mode',
						'type'             => 'radio_inline',
						'options' 				 => array(
																	'personal_contact'   => __( 'Contact the location for pickup', 'commons-booking' ),
																	'opening_times' 		 => __( 'Fixed opening times for pickup', 'commons-booking' ),
																	),
						'default' => 'personal_contact',
				),
			)
		);
		return $settings_template_location_pickup_mode;
	}
	/**
	 * Locations meta box: location contact (personal contact)
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_location_personal_contact_info() {

		$settings_template_location_personal_contact_info = array(
			'name' => __( 'Personal contact', 'commons-booking' ),
			'slug' => 'locations',
			'show_in_plugin_settings' => false,
			'fields' => array (
				array(
						'name'             => __( 'my title', 'commons-booking' ),
						'id'               => 'location-personal-contact-info-title',
						'type'             => 'title',
				),
				array(
						'name'             => __( 'Public', 'commons-booking' ),
						'id'               => 'location-personal-contact-info-public',
						'type'             => 'textarea_small',
						'default'					 => __('Please contact the location after booking. The contact information will be in your confirmation email.', 'commons-booking' ),
				),
				array(
						'name'             => __( 'Private', 'commons-booking' ),
						'id'               => 'location-personal-contact-info-private',
						'type'             => 'textarea_small',
						'default'					 => __('Contact info: Phone, mail, etc.', 'commons-booking' ),
				),
			)
		);
		return $settings_template_location_personal_contact_info;
	}
	/**
	 * Populate settings array
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function apply_settings_templates() {

		// all settings groups (including those only used at wp post meta boxes)
		self::$plugin_settings = array (
			'pages' => self::get_settings_template_pages(),
			'bookings' => self::get_settings_template_bookings(),
			'calendar' => self::get_settings_template_calendar(),
			'codes' => self::get_settings_template_codes(),
			'location-opening-times' => self::get_settings_template_location_opening_times(),
			'location-pickup-mode' => self::get_settings_template_location_pickup_mode(),
			'location-personal-contact-info' => self::get_settings_template_location_personal_contact_info(),
			'strings' => self::get_settings_template_cb_strings(),
		);

		self::add_settings_tab( 'intro',
			array(
				'title' => __( 'Welcome', 'commons-booking' ),
				'description' => __( 'Welcome to CB', 'commons-booking' ),
				'groups' => array()
			)
		);
		self::add_settings_tab( 'bookings',
			array(
				'title' => __( 'Bookings', 'commons-booking' ),
				'description' => __( 'General Booking settings', 'commons-booking' ),
				'groups' => array (
					'bookings', 'calendar', 'codes'
				)
			)
		);

	}
	/**
	 * Render the admin settings
	 *
	 * @since 2.0.0
	 *
	 * @param string $tab
	 */
	public static function do_admin_settings( ) {

		$tabs = self::$admin_settings_tabs;

		foreach ( $tabs as $tab => $value ) { ?>
				<div id="tabs-<?php echo $tab ; ?>" class="wrap">
			<?php
			echo $value['description'];

			foreach ( $value['groups'] as $group ) { // render all metaboxes
				self::do_settings_group( $group );
			}
			?>
					</div>
     <?php
		}

	}
	/**
	 * Render a settings group
	 *
	 * @since 2.0.0
	 *
	 * @param string $group_id
	 */
	public static function do_settings_group( $slug ) {
		?>
			<div class="metabox-holder">
				<div class="postbox">
					<div class="inside">
						<?php
							$cmb_bookings = new_cmb2_box(
								array(
									'id' => CB_TEXTDOMAIN  . '_options-' . $slug,
									'show_on' => array(
										'key' => 'options-page',
										'value' => array( 'commons-booking' ), ),
									'show_names' => true,
									'fields' => self::get_settings_group_fields( $slug )
								) );

						cmb2_metabox_form( CB_TEXTDOMAIN  . '_options-' . $slug, CB_TEXTDOMAIN  . '-settings-' . $slug );
						?>
					</div>
				</div>
			</div>
<?
	}
	/**
	 * Add a settings tab
	 *
	 * @since 2.0.0
	 *
	 * @param array $args
	 */
	public static function add_settings_tab( $slug, $tab = array() ) {

		self::$admin_settings_tabs[$slug] = $tab;

	}
	/**
	 * Get settings admin tabs
	 *
	 * @since 2.0.0
	 *
	 * @return mixed $html
	 */
	public static function do_admin_tabs( ) {

		$html = '';
		foreach ( self::$admin_settings_tabs as $key => $value ) {
				$slug = $key;
				$html .= '<li><a href="#tabs-' . $slug . '">' . $value['title'] . '</a></li>';
		}
		return apply_filters( 'cb_do_admin_tabs', $html );
	}
	/**
	 * Get settings admin box
	 *
	 * @since 2.0.0
	 *
	 * @return array $metabox
	 */
	public static function get_admin_metabox( $slug ) {

		return self::$plugin_settings[$slug]['fields'];

	}
	/**
	 * Get settings admin box
	 *
	 * @since 2.0.0
	 *
	 * @return array $metabox
	 */
	public static function get_settings_group_fields( $slug ) {

		return self::$plugin_settings[$slug]['fields'];

	}


	/**
	 * Get a setting from the WP options table
	 *
	 * @since 2.0.0
	 *
	 * @param string $options_page
	 * @param string $toption (optional)
	 * @param string $checkbox (optional, @TODO)
	 *
	 * @return string/array
	 */
	public static function get( $options_page, $option = FALSE, $checkbox = FALSE ) {

		$options_page_name = CB_TEXTDOMAIN . '-settings-' . $options_page;

		$options_array = get_option( $options_page_name );

		if ( is_array ($options_array) && $option && array_key_exists ($option, $options_array )  ) { // we want a specific setting on the page and key exists
			return $options_array[ $option ];
		} elseif ( ! $option &&  is_array( $options_array ) ) {
			return $options_array;
		} else {
			// @TODO rework message system, so it does not block usage.
			// CB_Object::throw_error( __FILE__, $options_page . ' is not a valid setting');
		}
	}
}
add_action( 'plugins_loaded', array( 'CB_Settings', 'get_instance' ) );
