<?php
/**
 * Commons_Booking Settings - Codes
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Codes pool.
 */
?>
<?php $slug = 'locations'; ?>
<?php $setting = 'locations-opening-times'; ?>

<div id="tabs-<?php echo $slug; ?>" class="wrap">
	<div class="metabox-holder"><div class="postbox"><div class="inside">
			<?php
				$cmb_bookings = new_cmb2_box(
					array(
						'id' => 'commons-booking' . '_options-' . $setting,
						'hookup' => false,
						'show_on' => array(
							'key' => 'options-page',
							'value' => array( 'commons-booking' ), ),
						'show_names' => true,
						'fields' => CB_Settings::get_admin_metabox( $setting)
					) );

				cmb2_metabox_form( 'commons-booking' . '_options-' . $setting, 'commons-booking' . '-settings-' . $setting );
	?></div></div></div></div>
