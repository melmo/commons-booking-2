<?php
/**
 * Items in archives, list timeframes below item excerpt.
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
<?php	$cal = $template_args;
	if ( !empty ( $cal )) { ?>
		<?php // calendar ?>
			<ul class="cb-calendar">
				<?php if ( is_array( $cal['calendar'] )) { ?>
					<?php foreach ( $cal['calendar'] as $cal_date => $date ) { ?>
						<li class="cb-date weekday_<?php echo date ( 'w', strtotime( $cal_date ) );  ?>" id="<?php echo $cal_date; ?>">
							<span class="cb-holiday"><?php echo $date['holiday']; ?></span>
							<span class="cb-M"><?php echo date ( 'M', strtotime( $cal_date ) );  ?></span>
							<span class="cb-j"><?php echo date ( 'j', strtotime( $cal_date ) );  ?></span>
									<ul class="cb-slots"></span>
										<?php foreach ( $date['slots'] as $slot ) { ?>
											<li id="<?php echo $slot['slot_id']; ?>" class="cb-slot" alt="<?php echo esc_html( $slot['description'] ); ?>" <?php echo CB_Gui::slot_attributes( $slot ); ?>>
												<!-- checkbox or similar here -->
											</li>
										<?php } // endforeach $slots ?>
									</ul>
							</li><? // end li.cb-date ?>
					<?php } // endforeach $cal ?>
				<?php } //if ( is_array( $cal['calendar'] ))  ?>
			</ul><? // end ul.cb-calendar ?>
	</div> <? // end div.cb-calendar ?>
<?php } //if ( is_array( $calendar )) ?>
