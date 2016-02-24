<?php
/**
 * Handle theme meta boxes
 * Add new meta boxes for posts, pages, products
 * Load scripts for meta boxes
 *
 * @package LearnPlus
 */

/**
 * Register meta boxes
 *
 * @since 1.0
 *
 * @param array $meta_boxes
 *
 * @return array
 */
function learnplus_register_meta_boxes( $meta_boxes ) {
	// Post format
	$meta_boxes[] = array(
		'id'       => 'post-format-settings',
		'title'    => esc_html__( 'Post Format Settings', 'learnplus' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name'             => esc_html__( 'Image', 'learnplus' ),
				'id'               => 'image',
				'type'             => 'image_advanced',
				'class'            => 'image',
				'max_file_uploads' => 1,
			),
			array(
				'name'  => esc_html__( 'Gallery', 'learnplus' ),
				'id'    => 'images',
				'type'  => 'image_advanced',
				'class' => 'gallery',
			),
			array(
				'name'  => esc_html__( 'Audio', 'learnplus' ),
				'id'    => 'audio',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'audio',
			),
			array(
				'name'  => esc_html__( 'Video', 'learnplus' ),
				'id'    => 'video',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'video',
			),
			array(
				'name'  => esc_html__( 'Link', 'learnplus' ),
				'id'    => 'url',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'link',
			),
			array(
				'name'  => esc_html__( 'Text', 'learnplus' ),
				'id'    => 'url_text',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'link',
			),
			array(
				'name'  => esc_html__( 'Quote', 'learnplus' ),
				'id'    => 'quote',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'quote',
			),
			array(
				'name'  => esc_html__( 'Author', 'learnplus' ),
				'id'    => 'quote_author',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'quote',
			),
			array(
				'name'  => esc_html__( 'URL', 'learnplus' ),
				'id'    => 'author_url',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'quote',
			),
			array(
				'name'  => esc_html__( 'Status', 'learnplus' ),
				'id'    => 'status',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'status',
			),
		),
	);

	$cf7           = get_posts( 'post_type=wpcf7_contact_form&numberposts=-1' );
	$contact_forms = array();
	if ( $cf7 ) {
		foreach ( $cf7 as $cform ) {
			$contact_forms[$cform->ID] = $cform->post_title;
		}
	} else {
		$contact_forms[0] = esc_html__( 'No contact forms found', 'learnplus' );
	}

	// Dispaly Settings
	$meta_boxes[] = array(
		'id'       => 'display-settings',
		'title'    => esc_html__( 'Display Settings', 'learnplus' ),
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name'  => esc_html__( 'Number posts per page', 'learnplus' ),
				'id'    => 'number_teacher',
				'type'  => 'number',
				'std'   => 5,
				'class' => 'number-teacher'
			),
			array(
				'name'    => esc_html__( 'Contact Form 7', 'learnplus' ),
				'id'      => 'contact_form_7',
				'type'    => 'select',
				'options' => $contact_forms,
				'class'   => 'lp-contact-form-7'
			),
			array(
				'name'  => esc_html__( 'Title', 'learnplus' ),
				'id'    => 'heading_title',
				'type'  => 'heading',
				'class' => 'heading-title'
			),
			array(
				'name'  => esc_html__( 'Hide Page Title', 'learnplus' ),
				'id'    => 'hide_page_title',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'heading-title'
			),
			array(
				'name'  => esc_html__( 'Layout & Styles', 'learnplus' ),
				'id'    => 'heading_layout',
				'type'  => 'heading',
				'class' => 'heading-layout'
			),
			array(
				'name'  => esc_html__( 'Custom Layout', 'learnplus' ),
				'id'    => 'custom_layout',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'heading-layout'
			),
			array(
				'name'    => esc_html__( 'Layout', 'learnplus' ),
				'id'      => 'layout',
				'type'    => 'image_select',
				'class'   => 'custom-layout',
				'options' => array(
					'full-content'    => LEARNPLUS_URL . '/inc/libs/theme-options/img/sidebars/empty.png',
					'sidebar-content' => LEARNPLUS_URL . '/inc/libs/theme-options/img/sidebars/single-left.png',
					'content-sidebar' => LEARNPLUS_URL . '/inc/libs/theme-options/img/sidebars/single-right.png',
				),
			),
			array(
				'name' => esc_html__( 'Custom CSS', 'learnplus' ),
				'id'   => 'custom_css',
				'type' => 'textarea',
				'std'  => false,
			),
			array(
				'name' => esc_html__( 'Custom JavaScript', 'learnplus' ),
				'id'   => 'custom_js',
				'type' => 'textarea',
				'std'  => false,
			),
		),
	);

	// Course attributes
	$meta_boxes[] = array(
		'id'       => 'course-attributes',
		'title'    => esc_html__( 'Course Attributes', 'learnplus' ),
		'pages'    => array( 'course' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Students', 'learnplus' ),
				'desc' => esc_html__( 'Number of students', 'learnplus' ),
				'id'   => 'learnplus_students',
				'type' => 'number',
			),
			array(
				'name' => esc_html__( 'Perriod', 'learnplus' ),
				'desc' => esc_html__( 'Number of months', 'learnplus' ),
				'id'   => 'learnplus_perriod',
				'type' => 'number',
			),
			array(
				'name' => esc_html__( 'Forum', 'learnplus' ),
				'desc' => esc_html__( 'Enter in follow format: Forum Name|Forum URL', 'learnplus' ),
				'id'   => 'learnplus_forum_uri',
				'type' => 'text',
			),
		),
	);

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'learnplus_register_meta_boxes' );

function learnplus_unregister_theme_post_types() {
	global $wp_post_types;
	if ( post_type_exists( 'team_showcase' ) ) {
		unset( $wp_post_types['team_showcase'] );
	}
}

add_action( 'init', 'learnplus_unregister_theme_post_types', 20 );


/**
 * Enqueue scripts for admin
 *
 * @since  1.0
 */
function learnplus_meta_boxes_scripts( $hook ) {

	wp_enqueue_style( 'admin', LEARNPLUS_URL . "/css/backend/admin.css", array(), LEARNPLUS_VERSION );
	// Detect to load un-minify scripts when WP_DEBUG is enable
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'learnplus-meta-boxes', LEARNPLUS_URL . "/js/backend/meta-boxes$min.js", array( 'jquery' ), LEARNPLUS_VERSION, true );
	}
}

add_action( 'admin_enqueue_scripts', 'learnplus_meta_boxes_scripts' );
