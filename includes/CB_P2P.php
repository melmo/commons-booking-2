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
 * This class contain the Posts 2 Posts code
 */
class Cb_P2P {
	/**
	 * Initialize the snippet
	 */
	function __construct() {
		require_once( 'lib/posts-to-posts/posts-to-posts.php' );
		add_action( 'p2p_init', array( $this, 'my_connection_types' ) );
	}
	/**
	 * https://github.com/scribu/wp-posts-to-posts/wiki/Basic-usage
	 * 
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public function my_connection_types() {
		p2p_register_connection_type( array(
			'name' => 'demo_to_pages',
			'from' => 'demo',
			'to' => 'page'
		) );
	}
}
new Cb_P2P();
