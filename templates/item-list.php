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
$tfs = $obj->get_timeframes( array ( 'item_id' => get_the_id() ) ); // This template is called in the loop, so you need to supply the id
?>
<?php if ( is_array( $tfs )) { ?>
    <?php foreach ( $tfs as $tf ) { ?>
        <div class="cb-timeframe">
            <h4><?php echo $tf->description; ?></h4>
            <h4><?php echo $tf->timeframe_id; ?></h4>
            <p><?php echo $tf->date_start; ?> - <?php echo $tf->date_end; ?></p>
            <ul class="cb-dates">
                <?php if ( is_array( $tf->calendar )) { ?>
                    <?php foreach ( $tf->calendar as $date ) { ?>
                        <li>
                            <?php echo $date['meta']['name']; ?> - <?php echo $date['meta']['date']; ?>
                            <?php if ( ! empty ( $date['slots'] ) && is_array( $date['slots'] ) ) { ?>
                                <ul class="cb-slots">
                                    <?php foreach ( $date['slots'] as $slot ) { ?>
                                        <li><?php echo $slot['description']; ?>: <?php echo $slot['time_start']; ?> - <?php echo $slot['time_end']; ?></li>
                                    <?php } // endforeach $slots ?>
                                </ul>
                            <?php } // if ( is_array( $date['slots'] ) ) { ?>
                        </li>
                    <?php } // endforeach $cal ?>
                <?php } //if ( is_array( $tf->calendar ))  ?>
            </ul>
        </div>
    <?php } // endforeach $tfs ?>
<?php } //if ( is_array( $tfs )) ?>
