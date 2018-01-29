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

$CB = new CB_Object;
$CB->set_context('calendar');
$cal = $CB->get_timeframes( array( 'booking_id' => 2, 'has_slots' => FALSE ) );
var_dump($cal);


// $tf3 = $CB->get_timeframes( array('timeframe_id' => 5) );
// var_dump($cal);
// var_dump($tf2);
// var_dump($tf3);

}