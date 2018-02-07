<?php
/**
 * Commons_Booking Settings - Pages
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Bookings settings.
 */
?>

<div id="tabs-bookings" class="wrap">
	<?php
		$cmb_bookings = new_cmb2_box(
			array(
				'id' => CB_TEXTDOMAIN . '_options-bookings',
				'hookup' => false,
				'show_on' => array(
					'key' => 'options-page',
					'value' => array( CB_TEXTDOMAIN ), ),
				'show_names' => true,
			) );

		$cmb_bookings->add_field(
			array(
			'before_row'       => __('General booking settings', 'commons-booking' ), // Headline
			'name'             => __( 'Maximum slots', 'commons-booking' ),
			'desc'             => __( 'Maximum consecutive slots a user is allowed to book at once', 'commons-booking' ),
			'id'               => 'max-slots',
			'type'             => 'text_small',
			'default'          => 30
		) );

	cmb2_metabox_form( CB_TEXTDOMAIN . '_options-bookings', CB_TEXTDOMAIN . '-settings-bookings' );
	?>
</div>
