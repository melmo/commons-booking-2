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
	 * @since 1.0.0
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
		// Create Custom Post Type https://github.com/johnbillion/extended-cpts/wiki
		$tax = register_extended_post_type( 'demo', array(
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
				'genre' => array(
					'taxonomy' => 'demo-section'
				),
				'p2p' => array(
					'title' => 'Connected Posts',
					'connection' => 'demo_to_pages',
					'link' => 'edit'
				),
				'custom_field' => array(
					'title' => 'By Lib',
					'meta_key' => '_demo_' . CB_TEXTDOMAIN . '_text',
					'cap' => 'manage_options',
				),
				'date' => array(
					'title' => 'Date',
					'default' => 'ASC',
				),
			),
			# Add a dropdown filter to the admin screen:
			'admin_filters' => array(
				'genre' => array(
					'taxonomy' => 'demo-section'
				)
			)
				), array(
			# Override the base names used for labels:
			'singular' => __( 'Demo', CB_TEXTDOMAIN ),
			'plural' => __( 'Demos', CB_TEXTDOMAIN ),
			'slug' => 'demo',
			'capability_type' => array( 'demo', 'demoes' ),
				) );
		add_filter( 'pre_get_posts', array( $this, 'filter_search' ) );
		$tax->add_taxonomy( 'demo-section', array(
			'hierarchical' => false,
			'show_ui' => false,
		) );
		// Create Custom Taxonomy https://github.com/johnbillion/extended-taxos
		register_extended_taxonomy( 'demo-section', 'demo', array(
			# Use radio buttons in the meta box for this taxonomy on the post editing screen:
			'meta_box' => 'radio',
			# Show this taxonomy in the 'At a Glance' dashboard widget:
			'dashboard_glance' => true,
			# Add a custom column to the admin screen:
			'admin_cols' => array(
				'featured_image' => array(
					'title' => 'Featured Image',
					'featured_image' => 'thumbnail'
				),
			),
				), array(
			# Override the base names used for labels:
			'singular' => __( 'Demo Category', CB_TEXTDOMAIN ),
			'plural' => __( 'Demo Categories', CB_TEXTDOMAIN ),
			'slug' => 'demo-cat',
						'capabilities' => array(
				'manage_terms' => 'manage_demoes',
				'edit_terms' => 'manage_demoes',
				'delete_terms' => 'manage_demoes',
				'assign_terms' => 'read_demo',
			)
		) );
	}
	
}
new CB_PostTypes();
