<?php
/*
 * Plugin Name: LearnPlus Portfolio Management
 * Version: 1.0
 * Plugin URI: http://themealien.com/
 * Description: Create and manage your works you have done and present them in the easiest way.
 * Author: Theme Alien
 * Author URI: http://themealien.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Define constants */
define( 'LEARNPLUS_PORTFOLIO_VER', '1.0' );
define( 'LEARNPLUS_PORTFOLIO_DIR', plugin_dir_path( __FILE__ ) );
define( 'LEARNPLUS_PORTFOLIO_URL', plugin_dir_url( __FILE__ ) );

/** Load files */
require_once LEARNPLUS_PORTFOLIO_DIR . '/inc/class-portfolio.php';
require_once LEARNPLUS_PORTFOLIO_DIR . '/inc/class-showcase.php';
require_once LEARNPLUS_PORTFOLIO_DIR . '/inc/shortcodes.php';
require_once LEARNPLUS_PORTFOLIO_DIR . '/inc/frontend.php';

new LearnPlus_Portfolio;
new LearnPlus_Portfolio_Showcase;

/**
 * Add image sizes
 *
 * @since  1.0.0
 *
 * @return void
 */
function learnplus_portfolio_image_sizes_init() {
	add_image_size( 'portfolio-thumbnail-normal', 480, 312, true );
	add_image_size( 'portfolio-thumbnail-wide', 960, 312, true );
	add_image_size( 'portfolio-thumbnail-long', 480, 624, true );

    add_image_size('portfolio-project', 1170, 500, true);
}

add_action( 'init', 'learnplus_portfolio_image_sizes_init' );

/**
 * Load language file
 *
 * @since  1.0.0
 *
 * @return void
 */
function learnplus_portfolio_load_text_domain() {
	load_plugin_textdomain( 'ta-portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}

add_action( 'init', 'learnplus_portfolio_load_text_domain' );
