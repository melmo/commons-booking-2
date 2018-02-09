<?php
new Fake_Page(
	  array(
    'slug' => 'fake_slug',
    'post_title' => 'Fake Page Title',
    'post_content' => testing()
	  )
);

function testing() {

    // return ( CB_Strings::get_string( 'category', 'key' ) );

$args = array (
	// 'item_id' => get_the_id(), // This template is called in the loop, so you need to supply the id
	// 'has_slots' => TRUE,
	// 'orderby' => 'date_start',
	// 'order' => 'ASC',

	// 'today' => '0 days'
	// 'limit' => 1
);

$tf = new CB_Timeframe();
$tf->set_context('calendar');
$tf->get_timeframes( $args );

$setting = CB_Settings::get( 'bookings', 'max-slots');
// var_dump($setting);

// $option = get_option('commons-booking-settings-pages');

// var_dump($tf);

// var_dump( $tf );

// $CB = new CB_Object;
// $CB->set_context('calendar');
// $cal = $CB->get_timeframes( $args );

// var_dump ($cal);

// var_dump($cal);
// $tf3 = $CB->get_timeframes( array('timeframe_id' => 5) );
// var_dump($cal);
// var_dump($tf2);
// var_dump($tf3);

}
