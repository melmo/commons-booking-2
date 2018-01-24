<?php
/**
 * Items in archives (lists) template
 * 
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 * 
 * @see       Cb_Enqueue::cb_template_chooser()
 */
?>
ITEM LIST TEMPLATE 
<?php 
$tf = new CB_Timeframe;
$timeframes = $tf->get( array ( 'item_id' => get_the_id() ) );
?>
<?php foreach ( $timeframes as $timeframe ) { ?> 
    <h4><?php echo $timeframe->description; ?></h4>
    <p><?php echo $timeframe->date_start; ?> - <?php echo $timeframe->date_end; ?></p>
<?php } ?>