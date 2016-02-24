<?php
/**
 * Theme Alien Core functions and definitions
 *
 * @package LearnPlus
 */

/**
 * Define theme's constant
 */
if ( ! defined( 'LEARNPLUS_VERSION' ) ) {
	define( 'LEARNPLUS_VERSION', '1.0.0' );;
}
if ( ! defined( 'LEARNPLUS_DIR' ) ) {
	define( 'LEARNPLUS_DIR', get_template_directory() );
}
if ( ! defined( 'LEARNPLUS_URL' ) ) {
	define( 'LEARNPLUS_URL', get_template_directory_uri() );
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since  1.0
 *
 * @return void
 */
function learnplus_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'learnplus', get_template_directory() . '/lang' );

	// Theme supports
	add_theme_support( 'woocommerce' );
	add_theme_support( 'sensei' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );

	// Register theme nav menu
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'learnplus' ),
		'footer'  => esc_html__( 'Footer Menu', 'learnplus' ),
	) );

	// Initialize
	new LearnPlus_WooCommerce;
	new LearnPlus_Products_Search;
	LearnPlus_LearnDash::init();

	if ( is_admin() ) {
		new LearnPlus_Walker_Nav_Menu_Custom_Fields;
	}
}
add_action( 'after_setup_theme', 'learnplus_setup' );

/**
 * Add image sizes.
 * Must be added to init hook to remove sizes of portfolio plugin.
 *
 * @since 1.0
 */
function learnplus_add_image_sizes() {
	// Register new image sizes
	add_image_size( 'learnplus-category-thumb', 370, 450, true );
	add_image_size( 'learnplus-blog-small-thumb', 170, 170, true );
	add_image_size( 'learnplus-blog-thumb', 748, 433, true );
	add_image_size( 'learnplus-blog-large-thumb', 1170, 677, true );
	add_image_size( 'learnplus-widget-thumb', 60, 60, true );
	add_image_size( 'learnplus-course-thumb', 400, 280, true );


	if ( in_array( 'ta-team/ta-team.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		add_image_size( 'team-member', 526, 652, true );
	}

	if ( in_array( 'ta-testimonial/ta-testimonial.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		add_image_size( 'testimonial-thumb', '75', '75', true );
	}
}
add_action( 'init', 'learnplus_add_image_sizes', 20 );

/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function learnplus_register_sidebars() {
	$sidebars = array(
		'blog-sidebar' => esc_html__( 'Blog Sidebar', 'learnplus' ),
		'shop-sidebar' => esc_html__( 'Shop Sidebar', 'learnplus' ),
		'page-sidebar' => esc_html__( 'Page Sidebar', 'learnplus' ),
		'topbar-left'  => esc_html__( 'Topbar Left', 'learnplus' ),
		'topbar-right' => esc_html__( 'Topbar Right', 'learnplus' ),
		'header-right' => esc_html__( 'Header Bottom', 'learnplus' ),
	);

	// Register sidebars
	foreach( $sidebars as $id => $name ) {
		register_sidebar( array(
			'name'          => $name,
			'id'            => $id,
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
	}

	// Register footer sidebars
	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Footer', 'learnplus' ) . " $i",
			'id'            => "footer-sidebar-$i",
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
	}
}
add_action( 'widgets_init', 'learnplus_register_sidebars' );

/**
 * Load theme
 */

// Theme Options
require LEARNPLUS_DIR . '/inc/libs/theme-options/framework.php';
require LEARNPLUS_DIR . '/inc/backend/theme-options.php';

// Widgets
require LEARNPLUS_DIR . '/inc/widgets/widgets.php';

// Woocommerce hooks
require LEARNPLUS_DIR . '/inc/frontend/woocommerce.php';

// Learndash
require LEARNPLUS_DIR . '/inc/frontend/learndash.php';

// Product Search
require LEARNPLUS_DIR . '/inc/frontend/products-search.php';

// User
require LEARNPLUS_DIR . '/inc/backend/admin.php';

if ( is_admin() ) {
	require LEARNPLUS_DIR . '/inc/libs/class-tgm-plugin-activation.php';
	require LEARNPLUS_DIR . '/inc/backend/plugins.php';
	require LEARNPLUS_DIR . '/inc/backend/meta-boxes.php';
	require LEARNPLUS_DIR . '/inc/backend/nav-menus.php';
} else {
	// Frontend functions and shortcodes
	require LEARNPLUS_DIR . '/inc/functions/breadcrumbs.php';
	require LEARNPLUS_DIR . '/inc/functions/media.php';
	require LEARNPLUS_DIR . '/inc/functions/nav.php';
	require LEARNPLUS_DIR . '/inc/functions/layout.php';
	require LEARNPLUS_DIR . '/inc/functions/entry.php';
	require LEARNPLUS_DIR . '/inc/functions/menu-walker.php';

	// Frontend hooks
	require LEARNPLUS_DIR . '/inc/frontend/layout.php';
	require LEARNPLUS_DIR . '/inc/frontend/header.php';
	require LEARNPLUS_DIR . '/inc/frontend/nav.php';
	require LEARNPLUS_DIR . '/inc/frontend/entry.php';
	require LEARNPLUS_DIR . '/inc/frontend/comments.php';
	require LEARNPLUS_DIR . '/inc/frontend/footer.php';
}
