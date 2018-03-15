<?php
/**
 * Commons_Booking
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
	 * Initialize the snippet
	 */
	function __construct() {

		$this->slug = CB_TEXTDOMAIN;

		add_action( 'cmb2_admin_init', array( $this, 'add_location_metaboxes' ) );
	}
			/**
	 * Load CPT and Taxonomies on WordPress
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
