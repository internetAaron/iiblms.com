<?php
/**
 * Hooks for template nav menus
 *
 * @package LearnPlus
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since 1.0
 * @param array $args Configuration arguments.
 * @return array
 */
function learnplus_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'learnplus_page_menu_args' );

/**
 * Add extra items to the end of primary menu
 *
 * @since  1.0.0
 *
 * @param  string $items Items list
 * @param  object $args  Menu options
 *
 * @return string
 */
function learnplus_nav_menu_extra_items( $items, $args ) {

	if ( 'primary' != $args->theme_location ) {
		return $items;
	}

	$extras = learnplus_theme_option( 'menu_extra' );
	if ( ! $extras ) {
		return $items;
	}

	foreach ( $extras as $item ) {
		switch ($item) {
			case 'search':
				$items .= sprintf(
					'<li class="extra-menu-item menu-item-search">
						<i class="flaticon-search74"></i>
					</li>',
					esc_url(home_url('/')),
					esc_html__('Search here', 'learnplus')
				);
			break;
		}
	}


	return $items;
}

add_filter( 'wp_nav_menu_items', 'learnplus_nav_menu_extra_items', 10, 2 );