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
 * Pages settings.
 */
?>

<?php $slug = 'pages'; ?>

<div id="tabs-<?php echo $slug; ?>" class="wrap">
	<div class="metabox-holder"><div class="postbox"><div class="inside">

@TODO NOT YET IMPLEMENTED.
@TODO Items page, Locations page should be depreciated, "items-list" and "calendar" used
@TODO "booking review page",  "booking confirmed page" no longer as seperate pages

	<?php
		$cmb_pages = new_cmb2_box(
			array(
				'id' => CB_TEXTDOMAIN  . '_options-' . $slug,
				'hookup' => false,
				'show_on' => array(
					'key' => 'options-page',
					'value' => array( 'commons-booking' ),
				),
				'show_names' => true,
				'fields' => CB_Settings::get_admin_metabox( $slug)
			)
		);

	cmb2_metabox_form( CB_TEXTDOMAIN  . '_options-' . $slug , CB_TEXTDOMAIN . '-settings-' . $slug  );
	?>
</div></div></div></div>
