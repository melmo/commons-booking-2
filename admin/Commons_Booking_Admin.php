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
 * This class should ideally be used to work with the administrative side of the WordPress site.
 */
class Commons_Booking_Admin {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public static function initialize() {
		if ( !apply_filters( 'commons_booking_cb_admin_initialize', true ) ) {
			return;
		}
		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		  if( ! is_super_admin() ) {
		  return;
		  }
		 */
		require_once( CB_PLUGIN_ROOT . 'admin/includes/CB_Enqueue_Admin.php' );
		/*
		 * Load CMB
		 */
		require_once( CB_PLUGIN_ROOT . 'admin/includes/CB_CMB.php' );
		/*
		 * Import Export settings
		 */
		require_once( CB_PLUGIN_ROOT . 'admin/includes/CB_ImpExp.php' );
		/*
		 * Contextual Help
		 */
		require_once( CB_PLUGIN_ROOT . 'admin/includes/CB_ContextualHelp.php' );
		/*
		 * All the pointers
		 */
		require_once( CB_PLUGIN_ROOT . 'admin/includes/CB_Pointers.php' );
		/*
		 * All the extras functions
		 */
		require_once( CB_PLUGIN_ROOT . 'admin/includes/CB_Extras_Admin.php' );
	}
	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		  if( ! is_super_admin() ) {
		  return;
		  }
		 */
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			try {
				self::$instance = new self;
				self::initialize();
			} catch ( Exception $err ) {
				do_action( 'commons_booking_admin_failed', $err );
				if ( WP_DEBUG ) {
					throw $err->getMessage();
				}
			}
		}
		return self::$instance;
	}
}
add_action( 'plugins_loaded', array( 'Commons_Booking_Admin', 'get_instance' ) );
