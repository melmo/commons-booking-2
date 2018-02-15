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
$timeframe = new CB_Timeframe;

$args = array (
	'item_id' => get_the_id(), // This template is called in the loop, so you need to supply the id
	'discard_empty' => TRUE,
	// 'has_open_slots' => TRUE,
	// 'has_bookings' => TRUE,
	// 'user_id' => 3,
	'paged' => TRUE,
	'per_page' => 2,
	'order_by' => 'date_end',
	'order' => 'DESC'
);

$tfs = $timeframe->get( $args );

// var_dump($tfs);
?>
<?php if ( is_array( $tfs )) { ?>
    <?php foreach ( $tfs as $tf ) { ?>
        <div class="cb-timeframe" id="timeframe-<?php echo $tf->description; ?>">
						<span class="cb-location-info">
							<?php
								// location info
								printf (
								'<a href="%s">%s</a>: %s - %s',
								get_permalink( $tf->location_id ),
								get_the_title( $tf->location_id ),
								date_i18n( get_option( 'date_format' ), strtotime ( $tf->date_start ) ),
								date_i18n( get_option( 'date_format' ),  strtotime ( $tf->date_end ) )
								);
							?>
							</span>
							<span class="cb-slot-availability">
							<?php
								// Availability
								printf (
								__( '%s slots booked, %s slots available, %s total', 'commons-booking' ),
								$tf->availability['booked'],
								$tf->availability['available'],
								$tf->availability['total']
								);
							?>
							</span>
								<?php $timeframe->maybe_message ( $tf->message );	?>
							</span>
            <ul class="cb-dates">
                <?php if ( is_array( $tf->calendar )) { ?>
                    <?php foreach ( $tf->calendar as $date ) { ?>
                        <li>
                            <?php echo $date['meta']['name']; ?> - <?php echo $date['meta']['date']; ?>
                            <?php if ( ! empty ( $date['slots'][$tf->timeframe_id] ) && is_array( $date['slots'][$tf->timeframe_id] ) ) {
															// var_dump($date['slots']); ?>
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
