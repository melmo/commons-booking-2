<?php
/**
 * Custom meta boxes for items & locations
 * @TODO: item metaboxes
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * This class contain the Post Types and Taxonomy initialize code
 */
class CB_PostTypes_Metaboxes {
	/**
	 * Initialize
	 *
	 * @since 2.0.0
	 *
	 */
	function __construct() {

		$this->slug = CB_TEXTDOMAIN;

		add_action( 'add_meta_boxes', array( $this, 'add_item_wp_metaboxes' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_location_metaboxes' ) );
	}
	/**
	 * Register item timeframe meta box
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function add_item_wp_metaboxes() {
		/**
		 * Metabox: We are using WP internal metabox instead of cmb2 here, since we just render the timeframes and do not save any stuff
		 */
		add_meta_box( 'timeframe_meta_box', __( 'Timeframes', 'commons-booking' ), array( $this, 'do_timeframe_item_metabox'), 'cb_item' );
	}
	/**
	 * Wrapper for CB_Gui in timeframe meta box
	 *
	 * @since 2.0.0
	 */
	public function do_timeframe_item_metabox( ) {

		global $post;
		echo CB_Gui::col_format_timeframe( $post->ID );
		echo CB_Gui::timeframes_admin_url( 'edit' );
	}

	/**
	 * Load CPT and Taxonomies on WordPress
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function add_location_metaboxes() {

		/**
		 * Metabox: Pickup mode
		 */
		$prefix = '_' . $this->slug . '_';
		$fields_location_pickup_mode = CB_Settings::get_admin_metabox( 'location-pickup-mode');

		$cmb = new_cmb2_box( array(
			'id'            => 'location-pickup-mode',
			'title'         => __( 'Location pickup mode', 'commons-booking' ),
			'object_types'  => array( 'cb_location', ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			'cmb_styles' => false, // false to disable the CMB stylesheet
			'fields'				=> $fields_location_pickup_mode
		) );

		/**
		 * Metabox: Personal pickup contact info (conditional)
		 */
		$prefix = '_' . $this->slug . '_';
		$fields_location_personal_contact_info = CB_Settings::get_admin_metabox( 'location-personal-contact-info');

		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box( array(
			'id'            => 'location-personal-contact-info',
			'title'         => __( 'Location personal contact info', 'commons-booking' ),
			'object_types'  => array( 'cb_location', ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			'cmb_styles' => false, // false to disable the CMB stylesheet
			'fields'				=> $fields_location_personal_contact_info
		) );

		/**
		 * Metabox: Opening times (conditional)
		 */
		$prefix = '_' . $this->slug . '_';
		$fields_location_opening_times = CB_Settings::get_admin_metabox( 'location-opening-times');

		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box( array(
			'id'            => 'location-opening-times',
			'title'         => __( 'Location opening times', 'commons-booking' ),
			'object_types'  => array( 'cb_location', ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			'cmb_styles' => false, // false to disable the CMB stylesheet
			'fields'				=> $fields_location_opening_times
		) );
	}

}
new CB_PostTypes_Metaboxes();
