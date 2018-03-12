<?php
/**
 * Settings for Commons Booking
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Global settings, settings for items, timeframes, etc
 * Usage: $setting = CB_Settings::get( 'bookings', 'max-slots');
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
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function initialize() {
			self::set_settings_templates();
		}
	/**
	 * Booking settings template
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_bookings() {

		$settings_bookings = array(
			'name' => __( 'Bookings', 'commons-booking' ),
			'slug' => 'bookings',
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
	 * Pages settings template
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_codes() {

		$settings_codes = array(
			'name' => __( 'Codes', 'commons-booking' ),
			'slug' => 'codes',
			'fields' => array (
					array(
						'before_row'       => __('Booking Codes', 'commons-booking' ), // Headline
						'name'             => __( 'Codes', 'commons-booking' ),
						'desc'             => __( 'Booking codes, comma-seperated', 'commons-booking' ),
						'id'               => 'codes-pool',
						'type'             => 'textarea',
						'default'          => 'none',
				)
			)
		);
		return $settings_codes;
	}
	/**
	 * Locations settings template
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_settings_template_locations_open() {

		$settings_locations_open = array(
			'name' => __( 'Locations', 'commons-booking' ),
			'slug' => 'locations',
			'fields' => array (
					array(
						'name'             => __( 'Opening hours', 'commons-booking' ),
						'id'               => 'locations-has-opening-hours',
						'type'             => 'checkbox',
						'description'      => 'Location has fixed opening hours',
						'classes'					 => 'header-condition'
					),
					array(
						'before_row'       => __('Monday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on Mondays', 'commons-booking' ),
						'id'               => 'locations-open-monday',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'locations-open-monday-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'monday-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'locations-open-monday-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'monday-hidden'
					),
					array(
						'before_row'       => __('tuesday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on tuesdays', 'commons-booking' ),
						'id'               => 'locations-open-tuesday',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'locations-open-tuesday-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'tuesday-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'locations-open-tuesday-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'tuesday-hidden'
					),
					array(
						'before_row'       => __('wednesday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on wednesdays', 'commons-booking' ),
						'id'               => 'locations-open-wednesday',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'locations-open-wednesday-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'wednesday-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'locations-open-wednesday-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'wednesday-hidden'
					),
					array(
						'before_row'       => __('thursday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on thursdays', 'commons-booking' ),
						'id'               => 'locations-open-thursday',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'locations-open-thursday-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'thursday-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'locations-open-thursday-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'thursday-hidden'
					),
					array(
						'before_row'       => __('friday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on fridays', 'commons-booking' ),
						'id'               => 'locations-open-friday',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'locations-open-friday-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'friday-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'locations-open-friday-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'friday-hidden'
					),
					array(
						'before_row'       => __('saturday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on saturdays', 'commons-booking' ),
						'id'               => 'locations-open-saturday',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'locations-open-saturday-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'saturday-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'locations-open-saturday-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'saturday-hidden'
					),
					array(
						'before_row'       => __('sunday', 'commons-booking' ), // Headline
						'name'             => __( 'Open on sundays', 'commons-booking' ),
						'id'               => 'locations-open-sunday',
						'type'             => 'checkbox',
					),
					array(
						'name'             => __( 'Opening time', 'commons-booking' ),
						'id'               => 'locations-open-sunday-from',
						'type'             => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'sunday-hidden'
					),
					array(
						'name'             => __( 'Closing time', 'commons-booking' ),
						'id'               => 'locations-open-sunday-til',
						'type' 						 => 'text_time',
						'time_format'      => 'H:i',
						'classes'					 => 'sunday-hidden'
					),
			)
		);
		return $settings_locations_open;
	}
	/**
	 * Populate settings array
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function set_settings_templates() {

		self::$plugin_settings = array (
			'pages' => self::get_settings_template_pages(),
			'bookings' => self::get_settings_template_bookings(),
			'codes' => self::get_settings_template_codes(),
			'locations-open' => self::get_settings_template_locations_open(),
		);

		}
	/**
	 * Get settings admin tabs
	 *
	 * @since 1.0.0
	 *
	 * @return mixed $html
	 */
	public static function get_admin_tabs() {

		$html = '';
		foreach ( self::$plugin_settings as $tab ) {
			$title = $tab['name'];
			$slug = $tab['slug'];
			$html .= '<li><a href="#tabs-' . $slug . '">' . $title . '</a></li>';
		}
		return apply_filters( 'cb_get_admin_tabs', $html );
	}
	/**
	 * Get settings admin box
	 *
	 * @since 1.0.0
	 *
	 * @return array $metabox
	 */
	public static function get_admin_metabox( $slug ) {

		return self::$plugin_settings[$slug]['fields'];

	}


	/**
	 * Get a setting from the WP options table
	 *
	 * @since 1.0.0
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
			CB_Object::throw_error( __FILE__, $options_page . ' is not a valid setting');
		}
	}
}
add_action( 'plugins_loaded', array( 'CB_Settings', 'get_instance' ) );
