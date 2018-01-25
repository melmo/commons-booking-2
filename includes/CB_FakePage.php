<?php
new Fake_Page(
	  array(
    'slug' => 'fake_slug',
    'post_title' => 'Fake Page Title',
    'post_content' => testing()
	  )
);

function testing() {
    
    return ( CB_Strings::get_string( 'categorys', 'key' ) );

}