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
			<li><a href="#tabs-1"><?php _e( 'Settings' ); ?></a></li>
			<li><a href="#tabs-2"><?php _e( 'Settings 2', CB_TEXTDOMAIN ); ?></a></li>
			<?php
						?>
			<li><a href="#tabs-3"><?php _e( 'Import/Export', CB_TEXTDOMAIN ); ?></a></li>
			<?php
						?>
		</ul>
		<?php
		require_once( plugin_dir_path( __FILE__ ) . 'settings.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'settings-2.php' );
		?>
		<?php
				?>
		<div id="tabs-3" class="metabox-holder">
			<div class="postbox">
				<h3 class="hndle"><span><?php _e( 'Export Settings', CB_TEXTDOMAIN ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Export the plugin\'s settings for this site as a .json file. This will allows you to easily import the configuration to another installation.', CB_TEXTDOMAIN ); ?></p>
					<form method="post">
						<p><input type="hidden" name="cb_action" value="export_settings" /></p>
						<p>
							<?php wp_nonce_field( 'cb_export_nonce', 'cb_export_nonce' ); ?>
							<?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?>
						</p>
					</form>
				</div>
			</div>
			<div class="postbox">
				<h3 class="hndle"><span><?php _e( 'Import Settings', CB_TEXTDOMAIN ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Import the plugin\'s settings from a .json file. This file can be retrieved by exporting the settings from another installation.', CB_TEXTDOMAIN ); ?></p>
					<form method="post" enctype="multipart/form-data">
						<p>
							<input type="file" name="cb_import_file"/>
						</p>
						<p>
							<input type="hidden" name="cb_action" value="import_settings" />
							<?php wp_nonce_field( 'cb_import_nonce', 'cb_import_nonce' ); ?>
							<?php submit_button( __( 'Import' ), 'secondary', 'submit', false ); ?>
						</p>
					</form>
				</div>
			</div>
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
