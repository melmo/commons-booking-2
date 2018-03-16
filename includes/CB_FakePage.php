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

// see https://github.com/flegfleg/cb-temp/blob/master/classes/CB_Object.php#L161
$args = array (
	'item_id' => get_the_id(), // This template is called in the loop, so you need to supply the id
);

$tf = new CB_Timeframe();
$tf->set_context('admin_table'); // either 1) 'timeframe' (default): group by timeframe or 2) 'calendar': group by date or 3) 'admin_table'
$tf->get_timeframes( $args );

$setting = CB_Settings::get( 'bookings', 'max-slots');

$codes_string = CB_Settings::get( 'codes', 'codes-pool');
// var_dump($codes_string);


$slot_templates = new CB_Slot_Templates();
$templates = $slot_templates->get_slot_templates();

// var_dump($templates);

// var_dump($slot_templates);


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
