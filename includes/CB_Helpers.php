<?php
/**
 * Commons_Booking
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * This file contains helper functions
 */
/**
 * Get a List of all wordpress pages for use in dropdown selects.
 *
 * @return Array of wordpress pages as [pagedID][title]
 */

function cb_get_pages_dropdown() {
  // dropdown for page select
  $pages = get_pages();
  $dropdown = array();

  foreach ( $pages as $page ) {
    $dropdown[$page->ID] = $page->post_title;
  }
  return $dropdown;
}
/**
 * Get array of post types @TODO: Apply filters to let users only add their own post types
 *
 * @param string post type single name
 *
 * @return array of wordpress post types as [postID][title]
 */

function cb_get_post_types_list( $post_type_name ) {

	$args = array(
		'post_type'        => $post_type_name,
		'author'	   			 => '',
		'suppress_filters' => true
	);

	$posts = get_posts( $args  );
  $dropdown = array();

  foreach ( $posts as $post ) {
    $dropdown[$post->ID] = $post->post_title;
  }
  return apply_filters('cb_get_post_types_list', $dropdown );
}
/**
 * Get array of users.
 *
 * @param string post type single name
 *
 * @return array of wordpress post types as [postID][title]
 */

function cb_get_users_list( $roles=array() ) {

	$users = get_users( [ 'role__in' => $roles ] );

	// Array of WP_User objects.
	foreach ( $users as $user ) {
			echo '<span>' . esc_html( $user->display_name ) . '</span>';
	}
  $list = array();

  foreach ( $users as $user ) {
    $list[$user->ID] = $user->display_name;
  }
  return apply_filters('cb_get_users_list', $list );
}
/**
 * Create a date Range
 *
 * @return array dates in the format
 */
function cb_dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {

	$dates = array();
	$current = strtotime( $first );
	$last = strtotime( $last );

	while( $current <= $last ) {

		$dates[] = date( $format, $current );
		$current = strtotime( $step, $current );
	}

	return $dates;
}
/**
 * Convert object to array
 *
 * @param object
 *
 * @return array
 */
function cb_obj_to_array( $object ) {

	$array = json_decode( json_encode( $object), true);
	return $array;
}
