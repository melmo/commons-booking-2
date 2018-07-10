<?php
/**
 * Show available items on a map
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 *
 * @see       CB_Enqueue::cb_template_chooser()
 *
*/
$cal = $template_args;
	if ( !empty ( $cal )) { ?>
		
		<?php
			$today = date('Y-m-d');
			if (isset($cal['calendar'][$today]) && isset($cal['calendar'][$today]['slots']) && is_array($cal['calendar'][$today]['slots'])) {
				$locations = array();
				foreach ($cal['calendar'][$today]['slots'] as $slot) {
					$loc_id = $slot['location_id'];
					if (!isset($locations[$loc_id])) { // first time we've seen this location, fetch the lat lng
						
						$locations[$loc_id] = array();
						$locations[$loc_id]['items'] = array();

						$loc = new CB_Locations( $loc_id );
						$geo = $loc->get_lat_lng();
						$locations[$loc_id]['geo'] = $geo;
						
					} 
					$item_id = $slot['item_id'];
					$locations[$loc_id]['items'][$item_id] = array(
						'title' => get_the_title($slot['item_id'] ),
						'state' => $slot['state'],
						'thumbnail' => get_the_post_thumbnail_url($slot['item_id'] ),
						'url' => get_permalink($slot['item_id'] )
					); 
				}
			}
		?>
		<div id="cb_map_data" data-locations='<?php echo json_encode($locations); ?>'>
		</div>
		<h2>Items available today</h2>
		<div id="cb_map">
		</div>

		<?php //  print_r($cal); ?>


<?php } //if ( is_array( $calendar )) 	?>

