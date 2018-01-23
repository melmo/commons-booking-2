<?php
/**
   * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 *
 * Plugin Name:       Commons Booking
 * Plugin URI:        @TODO
 * Description:       @TODO
 * Version:           1.0.0
 * Author:            Florian Egermann
 * Author URI:        http://commonsbooking.wielebenwir.de
 * Text Domain:       commons-booking
 * License:           GPL 2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * commons-booking: v2.0.5
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}
define( 'CB_VERSION', '1.0.0' );
define( 'CB_TEXTDOMAIN', 'commons-booking' );
define( 'CB_NAME', 'Commons Booking' );
define( 'CB_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'CB_PLUGIN_ABSOLUTE',  __FILE__  );
/**
 * Load the textdomain of the plugin
 * 
 * @return void
 */
function cb_load_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), CB_TEXTDOMAIN );
	load_textdomain( CB_TEXTDOMAIN, trailingslashit( WP_PLUGIN_DIR ) . CB_TEXTDOMAIN . '/languages/' . CB_TEXTDOMAIN . '-' . $locale . '.mo' );
}
add_action( 'plugins_loaded', 'cb_load_plugin_textdomain', 1 );
require_once( CB_PLUGIN_ROOT . 'composer/autoload.php' );
require_once( CB_PLUGIN_ROOT . 'includes/CB_PostTypes.php' );
require_once( CB_PLUGIN_ROOT . 'includes/CB_Helpers.php' );
require_once( CB_PLUGIN_ROOT . 'public/Commons_Booking.php' );
require_once( CB_PLUGIN_ROOT . 'classes/CB_Object.php' );
require_once( CB_PLUGIN_ROOT . 'classes/CB_Timeframe.php' );
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once( CB_PLUGIN_ROOT . 'includes/CB_WPCli.php' );
}
require_once( CB_PLUGIN_ROOT . 'includes/CB_FakePage.php' );


if ( is_admin() ) {
	if (
			(function_exists( 'wp_doing_ajax' ) && !wp_doing_ajax() ||
			(!defined( 'DOING_AJAX' ) || !DOING_AJAX ) )
	) {
		require_once( CB_PLUGIN_ROOT . 'admin/Commons_Booking_Admin.php' );
	}
}
