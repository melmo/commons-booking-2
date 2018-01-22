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
 * This class contain the Enqueue stuff for the backend
 */
class Cb_Enqueue_Admin {
		/**
	 * Slug of the plugin screen.
	 *
	 * @var string
	 */
	protected $admin_view_page = null;
		/**
	 * Initialize the class
	 */
	public function initialize() {
		if ( !apply_filters( 'commons_booking_cb_enqueue_admin_initialize', true ) ) {
			return;
		}
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . CB_TEXTDOMAIN . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}
	
		/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		if ( !isset( $this->admin_view_page ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $this->admin_view_page === $screen->id || strpos( $_SERVER[ 'REQUEST_URI' ], 'index.php' ) || strpos( $_SERVER[ 'REQUEST_URI' ], get_bloginfo( 'wpurl' ) . '/wp-admin/' ) ) {
			wp_enqueue_style( CB_TEXTDOMAIN . '-settings-styles', plugins_url( 'admin/assets/css/settings.css', CB_PLUGIN_ABSOLUTE ), array( 'dashicons' ), CB_VERSION );
		}
		wp_enqueue_style( CB_TEXTDOMAIN . '-admin-styles', plugins_url( 'admin/assets/css/admin.css', CB_PLUGIN_ABSOLUTE ), array( 'dashicons' ), CB_VERSION );
	}
			/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		if ( !isset( $this->admin_view_page ) ) {
			return;
		}
		
		$screen = get_current_screen();
		if ( $this->admin_view_page === $screen->id ) {
			wp_enqueue_script( CB_TEXTDOMAIN . '-settings-script', plugins_url( 'admin/assets/js/settings.js', CB_PLUGIN_ABSOLUTE ), array( 'jquery', 'jquery-ui-tabs' ), CB_VERSION );
		}
		wp_enqueue_script( CB_TEXTDOMAIN . '-admin-script', plugins_url( 'admin/assets/js/admin.js', CB_PLUGIN_ABSOLUTE ), array( 'jquery' ), CB_VERSION );
	}
		
		/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the Settings menu
		 *
		 * @TODO:
		 *
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		  $this->admin_view_page = add_options_page(
		  __( 'Page Title', CB_TEXTDOMAIN ), CB_NAME, 'manage_options', CB_TEXTDOMAIN, array( $this, 'display_plugin_admin_page' )
		  );
		 * 
		 */
		/*
		 * Add a settings page for this plugin to the main menu
		 * 
		 */
		$this->admin_view_page = add_menu_page( __( 'Page Title', CB_TEXTDOMAIN ), CB_NAME, 'manage_options', CB_TEXTDOMAIN, array( $this, 'display_plugin_admin_page' ), 'dashicons-hammer', 90 );
	}
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public function display_plugin_admin_page() {
		include_once( CB_PLUGIN_ROOT . 'admin/views/admin.php' );
	}
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since 1.0.0
	 * 
	 * @param array $links Array of links.
	 * 
	 * @return array
	 */
	public function add_action_links( $links ) {
		return array_merge(
				array(
			'settings' => '<a href="' . admin_url( 'options-general.php?page=' . CB_TEXTDOMAIN ) . '">' . __( 'Settings' ) . '</a>',
				), $links
		);
	}
	
}
$cb_enqueue_admin = new Cb_Enqueue_Admin();
$cb_enqueue_admin->initialize();
do_action( 'commons_booking_cb_enqueue_admin_instance', $cb_enqueue_admin );
