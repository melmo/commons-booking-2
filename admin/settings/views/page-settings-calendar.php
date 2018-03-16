<?php
/**
 * Commons_Booking Settings - calendar
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * calendar.
 */
?>
<?php $slug = 'calendar'; ?>

<div id="tabs-<?php echo $slug; ?>" class="wrap">
	<div class="metabox-holder"><div class="postbox"><div class="inside">
			<?php
				$cmb_bookings = new_cmb2_box(
					array(
						'id' => 'commons-booking' . '_options-' . $slug,
						'hookup' => false,
						'show_on' => array(
							'key' => 'options-page',
							'value' => array( 'commons-booking' ), ),
						'show_names' => true,
						'fields' => CB_Settings::get_admin_metabox( $slug)
					) );

				cmb2_metabox_form( 'commons-booking' . '_options-' . $slug, 'commons-booking' . '-settings-' . $slug );
	?></div></div></div></div>
