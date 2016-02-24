<?php
/**
 * Plugin Name: LearnPlus Visual Composer Addons
 * Plugin URI: https://themealien.com
 * Description: Extra elements for Visual Composer. It was built for LearnPlus theme.
 * Version: 1.0.1
 * Author: ThemeAlien
 * Author URI: http://www.themealien.com
 * License: GPL2+
 * Text Domain: learnplus
 * Domain Path: /lang/
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'LEARNPLUS_ADDONS_DIR' ) ) {
	define( 'LEARNPLUS_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'LEARNPLUS_ADDONS_URL' ) ) {
	define( 'LEARNPLUS_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

require_once LEARNPLUS_ADDONS_DIR . '/inc/visual-composer.php';
require_once LEARNPLUS_ADDONS_DIR . '/inc/shortcodes.php';

/**
 * Init
 */
function learnplus_vc_addons_init() {
	load_plugin_textdomain( 'learnplus', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

	new LearnPlus_VC;

	if ( ! is_admin() ) {
		new LearnPlus_Shortcodes;
	}
}

add_action( 'after_setup_theme', 'learnplus_vc_addons_init' );
