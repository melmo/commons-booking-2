<?php
new Fake_Page(
	  array(
    'slug' => 'fake_slug',
    'post_title' => 'Fake Page Title',
    'post_content' => testing()
	  )
);

function testing() {
    $test = CB_Timeframe::get_instance();
    $setting = $test->get_setting('pages', 'item-page-id');
    // var_dump( $test->sql_conditions );
    $args = array ( 'scope' => 'future', 'location_id' => '2' );
    $test->get( $args );
}