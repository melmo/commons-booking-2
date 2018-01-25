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
<?php 
$obj = new CB_Object;
$tfs = $obj->get( array ( 'item_id' => get_the_id() ) );
?>
<?php foreach ( $tfs as $tf ) { ?> 
    <h4><?php echo $tf->description; ?></h4>
    <h4><?php echo $tf->timeframe_id; ?></h4>
    <p><?php echo $tf->date_start; ?> - <?php echo $tf->date_end; ?></p>
    <?php 
        var_dump($obj);

        $cal = new CB_Calendar( $tf->date_start, $tf->date_end ); 
        // var_dump($cal);
        // $days = $cal->create_calendar_array( );
        // var_dump ($cal);
        foreach ( $cal->date_meta_array as $day ) {
            echo $day['date'] . " - " . $day['name'] . "<br>";
         } // endforeach $cal
    ?> 
<?php } // endforeach $timeframes ?>