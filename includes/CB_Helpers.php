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