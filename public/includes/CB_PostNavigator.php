<?php
class CB_PostNavigator {
  protected function __construct( &$posts = NULL ) {
    $this->zero_array = array();
    if ( is_null( $posts ) ) $this->posts = &$this->zero_array;
    else                     $this->posts = &$posts;

    if ( ! property_exists( $this, 'ID' ) )
			throw new Exception( get_class( $this ) . ' CB_PostNavigator requires an ID' );

    // WP_Post default values
    if ( ! property_exists( $this, 'post_status' ) )   $this->post_status   = 'publish';
    if ( ! property_exists( $this, 'post_password' ) ) $this->post_password = '';
    if ( ! property_exists( $this, 'post_author' ) )   $this->post_author   = 1;
    if ( ! property_exists( $this, 'post_date' ) )     $this->post_date     = date( 'c' );
    if ( ! property_exists( $this, 'post_modified' ) ) $this->post_modified = date( 'c' );
    if ( ! property_exists( $this, 'post_excerpt' ) )  $this->post_excerpt  = $this->get_the_excerpt();
    if ( ! property_exists( $this, 'post_content' ) )  $this->post_content  = $this->get_the_content();
    $this->post_date_gmt = $this->post_date;
    $this->post_modified_gmt = $this->post_modified;
		$this->filter = 'suppress'; // Prevent WP_Query from converting objects to WP_Post

    // This will cause subsequent WP_Post::get_instance() calls to return $this
    // rather than attempting to access the wp_posts table
    wp_cache_add( $this->ID, $this, 'posts' );
  }

  public function __toString() {return get_class( $this ) . "($this->ID)";}

  // ------------------------------------------------- Navigation
  function have_posts() {
    return current( $this->posts );
  }

  function next_post() {
    $post = current( $this->posts );
    next( $this->posts );
    return $post;
  }

  function rewind_posts() {
    reset( $this->posts );
    $this->post = NULL;
  }

  function &the_post() {
    global $post;
    $post = $this->next_post();
    $this->setup_postdata( $post );
    return $post;
  }

  // ------------------------------------------------- Properties
  function is_feed()    {return FALSE;}
  function is_page()    {return FALSE;}
  function is_single()  {return FALSE;}
  function get( $name ) {return NULL;}

  function add_actions( &$actions ) {}

  function setup_postdata( $post ) {
		global $id, $authordata, $currentday, $currentmonth, $page, $pages, $multipage, $more, $numpages;

    if ( ! $post ) {
        return;
    }

    $id = (int) $post->ID;

    $authordata = get_userdata($post->post_author);

    $currentday = mysql2date('d.m.y', $post->post_date, false);
    $currentmonth = mysql2date('m', $post->post_date, false);
    $numpages = 1;
    $multipage = 0;
    $page = $this->get( 'page' );
    if ( ! $page )
        $page = 1;

    /*
     * Force full post content when viewing the permalink for the $post,
     * or when on an RSS feed. Otherwise respect the 'more' tag.
     */
    if ( $post->ID === get_queried_object_id() && ( $this->is_page() || $this->is_single() ) ) {
        $more = 1;
    } elseif ( $this->is_feed() ) {
        $more = 1;
    } else {
        $more = 0;
    }

    $content = $post->post_content;
    if ( false !== strpos( $content, '<!--nextpage-->' ) ) {
        $content = str_replace( "\n<!--nextpage-->\n", '<!--nextpage-->', $content );
        $content = str_replace( "\n<!--nextpage-->", '<!--nextpage-->', $content );
        $content = str_replace( "<!--nextpage-->\n", '<!--nextpage-->', $content );

        // Ignore nextpage at the beginning of the content.
        if ( 0 === strpos( $content, '<!--nextpage-->' ) )
            $content = substr( $content, 15 );

        $pages = explode('<!--nextpage-->', $content);
    } else {
        $pages = array( $post->post_content );
    }

    /**
     * Filters the "pages" derived from splitting the post content.
     *
     * "Pages" are determined by splitting the post content based on the presence
     * of `<!-- nextpage -->` tags.
     *
     * @since 4.4.0
     *
     * @param array   $pages Array of "pages" derived from the post content.
     *                       of `<!-- nextpage -->` tags..
     * @param WP_Post $post  Current post object.
     */
    $pages = apply_filters( 'content_pagination', $pages, $post );

    $numpages = count( $pages );

    if ( $numpages > 1 ) {
        if ( $page > 1 ) {
            $more = 1;
        }
        $multipage = 1;
    } else {
        $multipage = 0;
    }

    /**
     * Fires once the post data has been setup.
     *
     * @since 2.8.0
     * @since 4.1.0 Introduced `$this` parameter.
     *
     * @param WP_Post  $post The Post object (passed by reference).
     * @param WP_Query $this The current Query object (passed by reference).
     */
    do_action_ref_array( 'the_post', array( &$post, &$this ) );

    return true;
	}

  function templates( $context = NULL, $type = NULL ) {
		$templates = array();
		$post_type = $this->post_type;
		do {
			if ( $context && $type ) array_push( $templates, "$context-$post_type-$type" );
			if ( $context )          array_push( $templates, "$context-$post_type" );
			if ( $type )             array_push( $templates, "$post_type-$type" );
			array_push( $templates, $post_type );

			if ( strpos( $post_type, '-' ) === FALSE ) $post_type = NULL;
			else $post_type = CB_Query::substring_before( $post_type );
		} while ( $post_type );

		// Sanitize
		// TODO: lazy cache this templates() file_exists()
		$templates_valid = array();
		foreach ( $templates as $template ) {
			$template_path = cb2_template_path() . "/$template.php";
			if ( file_exists( $template_path ) )
				array_push( $templates_valid, $template );
		}

		return $templates_valid;
	}

  // ------------------------------------------------- Output
  function the_json_content( $options = NULL ) {
    print( $this->get_the_json_content( $options ) );
  }

  function get_the_json_content( $options = NULL ) {
    wp_json_encode( $this, $options );
  }

  function the_content( $more_link_text = null, $strip_teaser = false ) {
		// Borrowed from wordpress
		// https://developer.wordpress.org/reference/functions/the_content/
    $content = $this->get_the_content( $more_link_text, $strip_teaser );
    $content = apply_filters( 'the_content', $content );
    $content = str_replace( ']]>', ']]&gt;', $content );
    echo $content;
  }

  function get_the_excerpt() {
		return '';
  }

  function get_the_content() {
		return '';
  }

  function classes() {
		return '';
  }
}

