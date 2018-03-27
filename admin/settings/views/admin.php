<?php
/**
 * Represents the view for the administration dashboard.
 *
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
?>
<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <div id="tabs" class="settings-tab">
		<ul>
			<li><a href="#tabs-1"><?php _e( 'General' ); ?></a></li>
			<?php echo CB_Settings::get_admin_tabs() ?>
			<li><a href="#tabs-importexport"><?php _e( 'Import/Export', 'commons-booking' ); ?></a></li>
		</ul>
		<?php
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-welcome.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-pages.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-calendar.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-bookings.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-codes.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-strings.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-importexport.php' );
		?>
		</div>
    </div>
</div>
