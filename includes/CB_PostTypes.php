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
class CB_PostTypes {
	/**
	 * Initialize the snippet
	 */
	function __construct() {
		add_action( 'init', array( $this, 'load_cpts' ) );
	}

		/**
	 * Add support for custom CPT on the search box
	 *
	 * @param object $query Wp_Query.
	 *
	 * @since 2.0.0
	 *
	 * @return object
	 */
	public function filter_search( $query ) {
		if ( $query->is_search && !is_admin() ) {
			$post_types = $query->get( 'post_type' );
			if ( $post_types === 'post' ) {
				$post_types = array();
				$query->set( 'post_type', array_push( $post_types, $this->cpts ) );
			}
		}
		return $query;
	}

	/**
	 * Load CPT and Taxonomies on WordPress
	 *
	 * @return void
	 */
	public function load_cpts() {
		// See: https://github.com/johnbillion/extended-cpts/wiki
		// Create Custom Post Type CB_Item
		$CB_Item = register_extended_post_type( 'cb_item', array(
			# Add as sub-menu to CB Dashboard
			'show_in_nav_menus' => TRUE,
			'show_in_menu' => 'cb_dashboard_page',
			# Show all posts on the post type archive:
			'archive' => array(
				'nopaging' => true
			),
			# Add some custom columns to the admin screen:
			'admin_cols' => array(
				'featured_image' => array(
					'title' => 'Featured Image',
					'featured_image' => 'thumbnail'
				),
				'title',
				'custom_field' => array( //@TODO: Include Timeframes
					'title' => __('Timeframes', 'commons-booking'),
					'function' => array ( $this, 'admin_col_get_timeframes' ),
					'cap' => 'manage_options',
				),
				'date' => array(
					'title' => 'Date',
					'default' => 'ASC',
				),
			),
				), array(
			# Override the base names used for labels:
			'singular' => __( 'Item', CB_TEXTDOMAIN ),
			'plural' => __( 'Items', CB_TEXTDOMAIN ),
			'slug' => 'items',
			'capability_type' => array( 'demo', 'demoes' ), //@TODO: Roles
				) );
		// Create Items category https://github.com/johnbillion/extended-taxos
		$CB_Item->add_taxonomy( 'item-category', array(
			'hierarchical' => true,
			'show_ui' => true,
		) );
		// Create Custom Post Type CB_Location
		$CB_Location = register_extended_post_type( 'cb_location', array(
			# Add as sub-menu to CB Dashboard
			'show_in_nav_menus' => TRUE,
			'show_in_menu' => 'cb_dashboard_page',
			# Show all posts on the post type archive:
			'archive' => array(
				'nopaging' => true
			),
			), array(
			# Override the base names used for labels:
			'singular' => __( 'Location', CB_TEXTDOMAIN ),
			'plural' => __( 'Locations', CB_TEXTDOMAIN ),
			'slug' => 'locations',
			'capability_type' => array( 'demo', 'demoes' ), //@TODO: Roles
				) );
		// Create Items category https://github.com/johnbillion/extended-taxos
		$CB_Location->add_taxonomy( 'location-category', array(
			'hierarchical' => true,
			'show_ui' => true,
		) );
		add_filter( 'pre_get_posts', array( $this, 'filter_search' ) );
	}

	public function admin_col_get_timeframes() {
		global $post;
		echo CB_Gui::col_format_timeframe( $post->ID );
	}

}
new CB_PostTypes();
