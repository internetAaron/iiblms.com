<?php
/**
 * Load and register widgets
 *
 * @package LearnPlus
 */

require_once LEARNPLUS_DIR . '/inc/widgets/recent-posts.php';
require_once LEARNPLUS_DIR . '/inc/widgets/tabs.php';
require_once LEARNPLUS_DIR . '/inc/widgets/social-media-links.php';
require_once LEARNPLUS_DIR . '/inc/widgets/tweets.php';
require_once LEARNPLUS_DIR . '/inc/widgets/contact.php';
require_once LEARNPLUS_DIR . '/inc/widgets/course.php';
require_once LEARNPLUS_DIR . '/inc/widgets/login.php';

/**
 * Register widgets
 *
 * @since  1.0
 *
 * @return void
 */
function learnplus_register_widgets() {
	register_widget( 'LearnPlus_Recent_Posts_Widget' );
	register_widget( 'LearnPlus_Tabs_Widget' );
    register_widget( 'LearnPlus_Social_Links_Widget' );
	register_widget( 'LearnPlus_Tweets_Widget' );
	register_widget( 'LearnPlus_Contact_Widget' );
	register_widget( 'LearnPlus_Course_Widget' );
	register_widget( 'LearnPlus_Login_Widget' );
}
add_action( 'widgets_init', 'learnplus_register_widgets' );
