<?php
/**
 * Custom functions for layout.
 *
 * @package LearnPlus
 */

/**
 * Get layout base on current page
 *
 * @return string
 */
function learnplus_get_layout() {
	$layout  = learnplus_theme_option( 'default_layout' );
	global $wp_query;
	$curauth = $wp_query->get_queried_object();

	if ( is_singular() && learnplus_get_meta( 'custom_layout' ) ) {
		$layout = learnplus_get_meta( 'layout' );
	} elseif ( is_page() ) {
		$layout = learnplus_theme_option( 'page_layout' );
	} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		$layout = learnplus_theme_option( 'shop_layout' );
	} elseif ( is_singular( array( 'product', 'forum', 'topic', 'tribe_events', 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz' ) ) ) {
		$layout = 'full-content';
	} elseif ( is_404() || is_post_type_archive( array('forum', 'tribe_events' , 'sfwd-courses') ) ) {
		$layout = 'full-content';
	} elseif( is_author() && $curauth && isset( $curauth->roles[0] ) && $curauth->roles[0] == 'instructor' ) {
		$layout = 'full-content';
	}

	return $layout;
}

/**
 * Get Bootstrap column classes for content area
 *
 * @since  1.0
 *
 * @return array Array of classes
 */
function learnplus_get_content_columns( $layout = null ) {
	$layout = $layout ? $layout : learnplus_get_layout();
	if ( 'full-content' == $layout ) {
		return array( 'col-md-12' );
	}

	return array( 'col-md-8', 'col-sm-12', 'col-xs-12' );
}

/**
 * Echos Bootstrap column classes for content area
 *
 * @since 1.0
 */
function learnplus_content_columns( $layout = null ) {
	echo implode( ' ', learnplus_get_content_columns( $layout ) );
}

/**
 * Get Bootstrap column classes for products
 *
 * @since  1.0
 *
 * @return string
 */
function learnplus_wc_content_columns( $columns ) {
	$col = array( 'col-xs-12' );
	if( ! empty( $columns ) ) {
		if ( 5 == $columns ) {
			$col[] = 'col-md-5ths col-sm-3';
		} elseif ( 2 == $columns || 3 == $columns || 4 == $columns ) {

			$column = floor( 12 / $columns );
			$col[] = 'col-sm-' . $column . ' col-md-' . $column;
		}
	}
	$col[] = 'col-product';

	echo implode( ' ', $col );
}
