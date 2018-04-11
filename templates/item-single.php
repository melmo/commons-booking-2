<?php
/**
 * Single timeframes
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 *
 * @see       CB_Enqueue::cb_template_chooser()
 */
?>
<?php echo get_the_content(); // echo content  ?>
<?php	$timeframes = $template_args;
	if ( is_array( $timeframes )) { ?>
    <?php foreach ( $timeframes as $tf ) { ?>
			<?php // timeframe  ?>
        <div id="timeframe-<?php echo $tf->timeframe_id; ?>" class="cb-timeframe <?php echo CB_Gui::timeframe_classes(  $tf ); ?>">
					<?php echo CB_Gui::location_short( $tf ) ; ?>
					<span class="cb-slot-availability"><?php echo CB_Gui::col_format_availability( $tf->availability ); ?></span>
					<?php CB_Gui::maybe_do_message ( $tf->message );	?>
					<?php // calendar

            // Suggest to replace the calendar with the shortcode for ease of maintenance
            // Do we still want calendars per timeframe?
            echo do_shortcode('[cb_calendar timeframe_id="' . $tf->timeframe_id .'"]');  ?>
        </div> <? // end div.cb-timeframe ?>
    <?php } // endforeach $tfs ?>
<?php } //if ( is_array( $tfs )) 