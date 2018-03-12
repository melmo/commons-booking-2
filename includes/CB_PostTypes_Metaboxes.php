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

		// Start with an underscore to hide fields from custom fields list
		$prefix = '_' . $this->slug . '_';
		$fields_locations_open = CB_Settings::get_admin_metabox( 'locations-open');

		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box( array(
			'id'            => 'locations-open',
			'title'         => __( 'Opening hours', 'cmb2' ),
			'object_types'  => array( 'cb_location', ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			'cmb_styles' => false, // false to disable the CMB stylesheet
			'fields'				=> $fields_locations_open
		) );
	}

}
new CB_PostTypes_Metaboxes();
