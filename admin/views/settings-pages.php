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
 * This class should ideally be used to work with the administrative side of the WordPress site.
 */
?>

<div id="tabs-2" class="wrap">
	<?php
		$cmb = new_cmb2_box( array(
		'id' => CB_TEXTDOMAIN . '_options-pages',
		'hookup' => false,
		'show_on' => array( 'key' => 'options-page', 'value' => array( CB_TEXTDOMAIN ), ),
		'show_names' => true,
			) );
					
		$cmb->add_field( 
			array(
			'before_row'       => __('Pages: Items and calendar', 'commons-booking' ), // Headline                    
			'name'             => __( 'Items page', 'commons-booking' ),
			'desc'             => __( 'Display list of items on this page', 'commons-booking' ),
			'id'               => 'cb-item-page-id',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => cb_get_pages_dropdown(),
		) );
		
		$cmb->add_field( 
			array(
			'name'             => __( 'Locations page', 'commons-booking' ),
			'desc'             => __( 'Display list of Locations on this page', 'commons-booking' ),
			'id'               => 'cb-location-page-id',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => cb_get_pages_dropdown(),
		) );
		
		$cmb->add_field( 
			array(
			'name'             => __( 'Calendar page', 'commons-booking' ),
			'desc'             => __( 'Display the calendar on this page', 'commons-booking' ),
			'id'               => 'cb-calendar-page-id',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => cb_get_pages_dropdown(),
		) );
		
		$cmb->add_field( 
			array(
			'before_row'       => __('Pages: Bookings', 'commons-booking' ), // Headline                                        
			'name'             => __( 'Booking review page', 'commons-booking' ),
			'desc'             => __( 'Shows the pending booking, prompts for confimation.', 'commons-booking' ),
			'id'               => 'cb-booking-review-page-id',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => cb_get_pages_dropdown(),
			) );           
			
			$cmb->add_field( 
				array(
			'name'             => __( 'Booking confirmed page', 'commons-booking' ),
			'desc'             => __( 'Displayed when the user has confirmed a booking.', 'commons-booking' ),
			'id'               => 'cb-booking-confirmed-page-id',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => cb_get_pages_dropdown(),
			) );   
			
			$cmb->add_field( 
				array(
			'name'             => __( 'Booking page', 'commons-booking' ),
			'desc'             => __( '', 'commons-booking' ),
			'id'               => 'cb-booking-page-id',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => cb_get_pages_dropdown(),
			) );         
			
			$cmb->add_field( 
				array(
			'name'             => __( 'My bookings page', 'commons-booking' ),
			'desc'             => __( 'Shows the user´s bookings.', 'commons-booking' ),
			'id'               => 'cb-user-bookings-page-id',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => cb_get_pages_dropdown(),
			)
		);

	cmb2_metabox_form( CB_TEXTDOMAIN . '_options-pages', CB_TEXTDOMAIN . '-settings-pages' );
	?>
</div>
