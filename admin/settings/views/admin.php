<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
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
			<li><a href="#tabs-pages"><?php _e( 'Pages', CB_TEXTDOMAIN ); ?></a></li>
			<li><a href="#tabs-bookings"><?php _e( 'Bookings', CB_TEXTDOMAIN ); ?></a></li>
			<li><a href="#tabs-importexport"><?php _e( 'Import/Export', CB_TEXTDOMAIN ); ?></a></li>
			<?php
						?>
		</ul>
		<?php
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-welcome.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-pages.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-bookings.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'page-settings-importexport.php' );
		?>
		</div>
		<?php
				?>
    </div>
    <div class="right-column-settings-page metabox-holder">
		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Plugin Name.', CB_TEXTDOMAIN ); ?></span></h3>
			<div class="inside">
				<a href="https://github.com/WPBP/commons-booking"><img src="https://raw.githubusercontent.com/WPBP/boilerplate-assets/master/icon-256x256.png" alt=""></a>
			</div>
		</div>
    </div>
</div>
