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

$args = array (
	'item_id' => get_the_id(), // This template is called in the loop, so you need to supply the id
	'has_slots' => FALSE,
	// 'orderby' => 'date_start',
	// 'today'   => '+3 day',
	'order' => 'ASC',
	'discard_empty' => FALSE
);

$tfs = $obj->get_timeframes( $args );
?>
<?php if ( is_array( $tfs )) { ?>
    <?php foreach ( $tfs as $tf ) { ?>
		<?php //var_dump($tf); ?>
        <div class="cb-timeframe">
					<span class="timeframe-description">Descr: <?php echo $tf->description; ?></span><br>
					<span class="timeframe-time-start">timeframe-time-start: <?php echo $tf->date_start; ?></span><br>
					<span class="timeframe-time-end">timeframe-time-end: <?php echo $tf->date_end; ?></span><br>
					<span class="timeframe-id">timeframe-id: <?php echo $tf->timeframe_id; ?></span><br>
            <ul class="cb-dates">
                <?php if ( is_array( $tf->calendar )) { ?>
                    <?php foreach ( $tf->calendar as $date ) { ?>
                        <li>
                            <?php echo $date['meta']['name']; ?> - <?php echo $date['meta']['date']; ?>
                            <?php if ( ! empty ( $date['slots'] ) && is_array( $date['slots'] ) ) { ?>
                                <ul class="cb-slots">
                                    <?php foreach ( $date['slots'][$tf->timeframe_id] as $slot ) { ?>
                                        <li class="cb-slot">
																					<span class="slot-description">Descr: <?php echo $slot['description']; ?></span>
																					<span class="slot-time-start">slot-time-start: <?php echo $slot['time_start']; ?></span>
																					<span class="slot-time-end">slot-time-end: <?php echo $slot['time_end']; ?></span>
																					<span class="slot-booking-status">slot-booking-status: <?php echo $slot['booking_status']; ?></span>
																					</li>
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
