<?php
/**
 * Hooks for template archive
 *
 * @package LearnPlus
 */


/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since 1.0
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function learnplus_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'learnplus_setup_author' );

/**
 * Change more string at the end of the excerpt
 *
 * @since  1.0
 *
 * @param string $more
 *
 * @return string
 */
function learnplus_excerpt_more( $more ) {
	$more = '&hellip;';

	return $more;
}

add_filter( 'excerpt_more', 'learnplus_excerpt_more' );


/**
 * Change length of the excerpt
 *
 * @since  1.0
 *
 * @param string $length
 *
 * @return string
 */
function learnplus_excerpt_length( $length ) {
	$excerpt_length = intval( learnplus_theme_option( 'excerpt_length' ) );
	if ( $excerpt_length > 0 ) {
		return $excerpt_length;
	}

	return $length;
}

add_filter( 'excerpt_length', 'learnplus_excerpt_length' );

/**
 * Set order by get posts
 *
 * @since  1.0
 *
 * @param object $query
 *
 * @return string
 */
function learnplus_pre_get_posts( $query ) {
	if ( is_admin() ) {
		return;
	}

	if ( ! $query->is_main_query() ) {
		return;
	}

	if ( $query->get( 'page_id' ) == get_option( 'page_on_front' ) || is_front_page() ) {
		return;
	}

	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		$query->set( 'posts_per_page', absint( learnplus_theme_option( 'products_per_page' ) ) );
	}
}

add_action( 'pre_get_posts', 'learnplus_pre_get_posts' );

/**
 * The archive title
 *
 * @since  1.0
 *
 * @param  array $title
 *
 * @return mixed
 */
function learnplus_the_archive_title( $title ) {
	if ( is_search() ) {
		$title = sprintf( esc_html__( 'Search Results', 'learnplus' ) );
	} elseif ( is_404() ) {
		$title = sprintf( esc_html__( 'Page Not Found', 'learnplus' ) );
	} elseif ( is_page() ) {
		$title = get_the_title();
	} elseif ( is_singular( 'forum' ) ) {
		$title =  esc_html__( 'Forum', 'learnplus' );
	} elseif ( is_singular( 'topic' ) ) {
		$title =  esc_html__( 'Topic', 'learnplus' );
	} elseif ( is_singular( 'portfolio_project' ) ) {
		$title =  esc_html__( 'Portfolio', 'learnplus' );
	} elseif ( is_singular( 'sfwd-courses' ) ) {
		$title =  esc_html__( 'Course', 'learnplus' );
	} elseif ( is_singular( 'sfwd-lessons' ) ) {
		$title =  esc_html__( 'Lesson', 'learnplus' );
	} elseif ( is_singular( 'sfwd-quiz' ) ) {
		$title =  esc_html__( 'Quiz', 'learnplus' );
	}elseif ( is_singular( 'tribe_events' ) ) {
		$title =  esc_html__( 'Single Event', 'learnplus' );
	} elseif ( is_singular( 'post' ) ) {
		$title = esc_html__( 'Single Post', 'learnplus' );
	} elseif ( is_home() && ! is_front_page() ) {
		$title = esc_html__( 'Blog & News', 'learnplus' );
	} elseif( is_post_type_archive( 'forum' ) ) {
		$title =  esc_html__( 'Forums', 'learnplus' );
	} elseif( is_post_type_archive( 'tribe_events' ) ) {
		$title = esc_html__( 'Events', 'learnplus' );
	} elseif( is_post_type_archive( 'sfwd-courses' ) ) {
		$title = esc_html__( 'Courses', 'learnplus' );
	} elseif( function_exists( 'is_shop' ) && is_shop() ) {
		$title =  esc_html__( 'Shop', 'learnplus' );
	}  elseif( function_exists( 'is_product' ) && is_product() ) {
		$title =  esc_html__( 'Single Product', 'learnplus' );
	} elseif( is_single() ) {
		$title = esc_html__( 'Single', 'learnplus' );
	} elseif( is_author() ) {
		$title = esc_html__( 'Author', 'learnplus' );
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'learnplus_the_archive_title' );

/**
 * Show a banner on the shop page
 *
 * @since 1.0.0
 */
function learnplus_show_page_title() {

	if ( ! learnplus_theme_option( 'show_page_title' ) ) {
		return;
	}

	if ( is_page() && learnplus_get_meta( 'hide_page_title' ) ) {
		return;
	}

	$pages = learnplus_theme_option( 'page_title' );
	if ( empty( $pages ) ) {
		return;
	}

	if ( is_front_page() ) {
		return;
	} elseif ( is_home() && ! is_front_page() && ! in_array( 'blog', $pages ) ) {
		return;
	}  elseif ( is_singular( 'post' ) && ! in_array( 'post', $pages ) ) {
		return;
	} elseif ( is_page() && ! in_array( 'page', $pages ) ) {
		return;
	} elseif ( ! learnplus_boxed_content() ) {
		return;
	}

	learnplus_page_title();

}
add_action( 'learnplus_page_title', 'learnplus_show_page_title' );

/**
 * Show a banner on the shop page
 *
 * @since 1.0.0
 */
function learnplus_boxed_page_title() {

	if ( ! learnplus_theme_option( 'show_page_title' ) ) {
		return;
	}

	if ( is_page() && learnplus_get_meta( 'hide_page_title' ) ) {
		return;
	}

	$pages = learnplus_theme_option( 'page_title' );
	if ( empty( $pages ) ) {
		return;
	}

	if ( is_front_page() ) {
		return;
	} elseif(  is_singular( 'post' ) ) {
		return;
	} elseif( learnplus_boxed_content() ) {
		return;
	} elseif ( function_exists( 'is_shop' ) && is_shop() && ! in_array( 'shop', $pages ) ) {
		return;
	} elseif ( function_exists( 'is_product' ) && is_product() && ! in_array( 'product', $pages ) ) {
		return;
	} elseif ( is_page() && ! in_array( 'page', $pages ) ) {
		return;
	}

	learnplus_page_title();

}
add_action( 'learnplus_page_header', 'learnplus_boxed_page_title' );
