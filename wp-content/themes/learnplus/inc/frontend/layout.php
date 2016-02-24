<?php
/**
 * Hooks for frontend display
 *
 * @package LearnPlus
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0
 * @param array $classes Classes for the body element.
 * @return array
 */
function learnplus_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Add a class of layout style
	$classes[] = learnplus_theme_option( 'layout_style' );

	// Add a class of layout
	$classes[] = learnplus_get_layout();

	// Add a class when choose no animation
	if ( learnplus_theme_option( 'no_animation' ) ) {
		$classes[] = 'no-animation';
	}

	// Add a class when choose header layout
	$classes[] = learnplus_theme_option( 'header_layout' );

	if( learnplus_theme_option( 'topbar' ) ) {
		$classes[] = 'learnplus-topbar';
	}

	// Add a class when choose shop single layout
	if( function_exists( 'is_product' ) && is_product() ) {
		$classes[] = learnplus_theme_option( 'shop_single_layout' );
	}

	if( learnplus_boxed_content() ) {
		$classes[] = 'grey page-boxed-content';
	}

	// Add a class for color scheme
	if ( intval( learnplus_theme_option( 'custom_color_scheme' ) ) && ( learnplus_theme_option( 'custom_color_1' ) || learnplus_theme_option( 'custom_color_2' ) ) ) {
		$classes[] = 'custom-color-scheme';
	} else {
		$classes[] = learnplus_theme_option( 'color_scheme' );
	}

	if( is_search() ) {
		$classes[] = 'woocommerce shop-view-list';
	}

	return $classes;
}
add_filter( 'body_class', 'learnplus_body_classes' );
