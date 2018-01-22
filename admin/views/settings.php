<?php
/**
 * Commons_Booking Settings - Welcome
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

<div id="tabs-1" class="wrap">
	<?php
		$cmb = new_cmb2_box( array(
		'id' => CB_TEXTDOMAIN . '_options',
		'hookup' => false,
		'show_on' => array( 'key' => 'options-page', 'value' => array( CB_TEXTDOMAIN ), ),
		'show_names' => false,
			) );
					
		$cmb->add_field( 
			array(
			'before_row'       	=>  __('Welcome to', 'commons-booking' ), // Headline                    
			'name'             	=> __( '', 'commons-booking' ),
			'id'               	=> 'cb-item-page-welcome',
			'type'				=> ''
 		) );

	cmb2_metabox_form( CB_TEXTDOMAIN . '_options', CB_TEXTDOMAIN . '-settings' );
				?>
	<!-- @TODO: Provide other markup for your options page here. -->
</div>
