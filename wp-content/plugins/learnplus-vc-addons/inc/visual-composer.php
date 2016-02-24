<?php
/**
 * Custom functions for Visual Composer
 *
 * @package LearnPlus
 * @subpackage Visual Composer
 */

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Class LearnPlus_VC
 *
 * @since 1.0.0
 */
class LearnPlus_VC {

	/**
	 * Construction
	 */
	function __construct() {
		// Stop if VC is not installed
		if ( ! is_plugin_active( 'js_composer/js_composer.php' ) ) {
			return false;
		}

		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			vc_add_shortcode_param( 'select_category', array( $this, 'select_category_param' ), LEARNPLUS_ADDONS_URL . '/assets/js/vc/select-field.js' );
			vc_add_shortcode_param( 'select_product_cat', array( $this, 'select_product_cat_param' ), LEARNPLUS_ADDONS_URL . '/assets/js/vc/select-field.js' );
		} elseif ( function_exists( 'add_shortcode_param' ) ) {
			vc_add_shortcode_param( 'select_category', array( $this, 'select_category_param' ), LEARNPLUS_ADDONS_URL . '/assets/js/vc/select-field.js' );
			vc_add_shortcode_param( 'select_product_cat', array( $this, 'select_product_cat_param' ), LEARNPLUS_ADDONS_URL . '/assets/js/vc/select-field.js' );
		} else {
			return false;
		}

		add_action( 'vc_before_init', array( $this, 'map_shortcodes' ) );
	}

	/**
	 * Add new params or add new shortcode to VC
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function map_shortcodes() {

		vc_remove_param( 'vc_row', 'parallax_image' );
		vc_remove_param( 'vc_row', 'parallax' );
		vc_remove_param( 'vc_row', 'video_bg_parallax' );

		$attributes = array(
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Overlay', 'learnplus' ),
				'param_name'  => 'overlay',
				'group'       => esc_html__( 'Design Options', 'learnplus' ),
				'value'       => '',
				'description' => esc_html__( 'Select an overlay color for this row', 'learnplus' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Enable Parallax effect', 'learnplus' ),
				'param_name'  => 'enable_parallax',
				'group'       => esc_html__( 'Design Options', 'learnplus' ),
				'value'       => array( esc_html__( 'Enable', 'learnplus' ) => 'yes' ),
				'description' => esc_html__( 'Enable this option if you want to have parallax effect on this row. When you enable this option, please set background repeat option as "Theme defaults" to make it works.', 'learnplus' ),
			),
		);

		vc_add_params( 'vc_row', $attributes );

		// Add section title shortcode
		vc_map( array(
			'name'     => esc_html__( 'Section Title', 'learnplus' ),
			'base'     => 'section_title',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Title', 'learnplus' ),
					'param_name'  => 'title',
					'value'       => '',
					'description' => esc_html__( 'Enter the title content', 'learnplus' ),
				),
				array(
					'type'        => 'textarea_html',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Description', 'learnplus' ),
					'param_name'  => 'content',
					'value'       => '',
					'description' => esc_html__( 'Enter a short description for section', 'learnplus' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Dark Skin', 'learnplus' ),
					'param_name'  => 'dark_skin',
					'value'       => '',
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Text Align', 'learnplus' ),
					'param_name'  => 'text_align',
					'value'       => array(
						esc_html__('Center', 'learnplus') => '',
						esc_html__('Left', 'learnplus')   => 'left',
						esc_html__('Right', 'learnplus')  => 'right',
					),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );

		// Add heading shortcode
		vc_map( array(
			'name'     => esc_html__( 'LearnPlus Heading', 'learnplus' ),
			'base'     => 'heading',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Title', 'learnplus' ),
					'param_name'  => 'title',
					'value'       => '',
					'description' => esc_html__( 'Enter the title content', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Text Align', 'learnplus' ),
					'param_name'  => 'text_align',
					'value'       => array(
						esc_html__('Left', 'learnplus')   => '',
						esc_html__('Center', 'learnplus') => 'center',
						esc_html__('Right', 'learnplus')  => 'right',
					),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );

		// Add Icon Box shortcode
		vc_map( array(
			'name'     => esc_html__( 'Icon Box', 'learnplus' ),
			'base'     => 'icon_box',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'iconpicker',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Icon', 'learnplus' ),
					'param_name'  => 'icon',
					'value'       => '',
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Border Radius', 'learnplus' ),
					'param_name'  => 'border_radius',
					'value'       => '',
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Icon Position', 'learnplus' ),
					'param_name'  => 'icon_position',
					'value'       => array(
						esc_html__( 'Top', 'learnplus' )   => '',
						esc_html__( 'Left', 'learnplus' )  => 'left',
						esc_html__( 'Right', 'learnplus' ) => 'right',
					),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Title', 'learnplus' ),
					'param_name'  => 'title',
					'value'       => '',
				),
				array(
					'type'        => 'textarea_html',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Content', 'learnplus' ),
					'param_name'  => 'content',
					'value'       => '',
					'description' => __( 'Enter the content of this box', 'learnplus' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Dark Skin', 'learnplus' ),
					'param_name'  => 'dark_skin',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				)
			),
		) );

		// Add Call to Action shortcode
		vc_map( array(
			'name'     => esc_html__( 'LearnPlus Call to Action', 'learnplus' ),
			'base'     => 'call_to_action',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'iconpicker',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Icon', 'learnplus' ),
					'param_name'  => 'icon',
					'value'       => '',
				),
				array(
					'type'        => 'textarea_html',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Content', 'learnplus' ),
					'param_name'  => 'content',
					'value'       => '',
					'description' => __( 'Enter the content of this box', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Text', 'learnplus' ),
					'param_name'  => 'button_text',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Link', 'learnplus' ),
					'param_name'  => 'button_link',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				)
			),
		) );

		vc_map( array(
			'name'                    => esc_html__('About Sliders', 'learnplus'),
			'base'                    => 'sliders',
			'as_parent'               => array('only' => 'slider'),
			'content_element'         => true,
			'params'                  => array(
				array(
					'type'        => 'attach_image',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Image', 'learnplus' ),
					'param_name'  => 'image',
					'value'       => '',
					'description' => esc_html__( 'Select an image from media library', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__('Extra class name', 'learnplus'),
					'param_name'  => 'el_class',
					'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus'),
				)
			),
			'js_view' => 'VcColumnView'
		) );
		vc_map( array(
			'name'            => esc_html__('About', 'learnplus'),
			'base'            => 'slider',
			'content_element' => true,
			'as_child'        => array('only' => 'sliders'),
			'params'          => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'SubTitle', 'learnplus' ),
					'param_name'  => 'subtitle',
					'value'       => '',
				),
				array(
					'type'        => 'textarea_html',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Title', 'learnplus' ),
					'param_name'  => 'content',
					'value'       => '',
				),
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Description', 'learnplus' ),
					'param_name'  => 'desc',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Text', 'learnplus' ),
					'param_name'  => 'text',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Link', 'learnplus' ),
					'param_name'  => 'link',
					'value'       => '',
				),
			)
		) );

		// Add team shortcode
		vc_map( array(
			'name'     => esc_html__( 'Team', 'learnplus' ),
			'base'     => 'team',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Total members', 'learnplus' ),
					'param_name'  => 'total',
					'value'       => '4',
					'description' => esc_html__( 'Set numbers of members to show.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Columns', 'learnplus' ),
					'param_name'  => 'columns',
					'value'       => array(
						esc_html__( '4 columns', 'learnplus' ) => '4',
						esc_html__( '2 columns', 'learnplus' ) => '2',
						esc_html__( '3 columns', 'learnplus' ) => '3',
					),
					'description' => __( 'Select numbers of columns you want to display.', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				)
			),
		) );

		// Add Counter shortcode
		vc_map( array(
			'name'     => esc_html__( 'Counter', 'learnplus' ),
			'base'     => 'counter',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Counter Value', 'learnplus' ),
					'param_name'  => 'value',
					'value'       => '',
					'description' => esc_html__( 'Input integer value for counting', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Title', 'learnplus' ),
					'param_name'  => 'title',
					'value'       => '',
					'description' => esc_html__( 'Enter the title of this box', 'learnplus' ),
				),
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Description', 'learnplus' ),
					'param_name'  => 'content',
					'value'       => '',
					'description' => esc_html__( 'Enter the description of this box', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				)
			),
		) );

		// Add team shortcode
		vc_map( array(
			'name'     => esc_html__( 'LearnPlus Posts', 'learnplus' ),
			'base'     => 'posts',
			'class'    => '',
			'admin_enqueue_js'  => LEARNPLUS_ADDONS_URL . '/assets/js/vc/select2.min.js',
			'admin_enqueue_css' => LEARNPLUS_ADDONS_URL . '/assets/css/vc/select2.css',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Total posts', 'learnplus' ),
					'param_name'  => 'total',
					'value'       => '3',
					'description' => esc_html__( 'Set numbers of posts to show.', 'learnplus' ),
				),
				array(
					'type'        => 'select_category',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Categories', 'learnplus' ),
					'param_name'  => 'categories',
					'description' => esc_html__( 'Select a category or select All to get posts from all categories.', 'learnplus' )
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Columns', 'learnplus' ),
					'param_name'  => 'columns',
					'value'       => array(
						esc_html__( '3 columns', 'learnplus' ) => '3',
						esc_html__( '4 columns', 'learnplus' ) => '4',
						esc_html__( '2 columns', 'learnplus' ) => '2',
					),
					'description' => __( 'Select numbers of columns you want to display.', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				)
			),
		) );

		// Add testinonial shortcode
		vc_map( array(
			'name'     => esc_html__( 'Testimonial', 'learnplus' ),
			'base'     => 'testimonial',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Total testimonial', 'learnplus' ),
					'param_name'  => 'total',
					'value'       => '3',
					'description' => esc_html__( 'Set numbers of testimonial to show.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Columns', 'learnplus' ),
					'param_name'  => 'columns',
					'value'       => array(
						esc_html__( '3 columns', 'learnplus' ) => '3',
						esc_html__( '4 columns', 'learnplus' ) => '4',
						esc_html__( '2 columns', 'learnplus' ) => '2',
					),
					'description' => __( 'Select numbers of columns you want to display.', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				)
			),
		) );

		// Add images carousel shortcode
		vc_map( array(
			'name'     => esc_html__( 'LearnPlus Images', 'learnplus' ),
			'base'     => 'images',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'attach_images',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Images', 'learnplus' ),
					'param_name'  => 'images',
					'value'       => '',
					'description' => esc_html__( 'Select images from media library', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Image size', 'learnplus' ),
					'param_name'  => 'image_size',
					'description' => esc_html__( 'Enter image size. Example: thumbnail, medium, large, full. Leave empty to use "thumbnail" size.', 'learnplus' ),
				),
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Custom links', 'learnplus' ),
					'param_name'  => 'custom_links',
					'description' => esc_html__( 'Enter links for each slide here. Divide links with linebreaks (Enter).', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Custom link target', 'learnplus' ),
					'param_name'  => 'custom_links_target',
					'value'       => array(
						esc_html__( 'Same window', 'learnplus' ) => '_self',
						esc_html__( 'New window', 'learnplus' )  => '_blank',
					),
					'description' => esc_html__( 'Select where to open custom links.', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );

		// Add images carousel shortcode
		vc_map( array(
			'name'     => esc_html__( 'Buttons Box', 'learnplus' ),
			'base'     => 'button_box',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Description', 'learnplus' ),
					'param_name'  => 'content',
				),
				array(
					'type'        => 'iconpicker',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Icon 1', 'learnplus' ),
					'param_name'  => 'icon_1',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Text 1', 'learnplus' ),
					'param_name'  => 'text_1',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Link 1', 'learnplus' ),
					'param_name'  => 'link_1',
				),
				array(
					'type'        => 'iconpicker',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Icon 2', 'learnplus' ),
					'param_name'  => 'icon_2',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Text 2', 'learnplus' ),
					'param_name'  => 'text_2',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Button Link 2', 'learnplus' ),
					'param_name'  => 'link_2',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );

		// Add pricing shortcode
		vc_map( array(
			'name'     => esc_html__( 'Pricing Table', 'learnplus' ),
			'base'     => 'pricing',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Plans', 'learnplus' ),
					'param_name'  => 'columns',
					'value'       => array(
						esc_html__( '3 plans', 'learnplus' ) => '3',
						esc_html__( '4 plans', 'learnplus' ) => '4',
						esc_html__( '2 plans', 'learnplus' ) => '2',
					),
					'description' => __( 'Select numbers of plans you want to display.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Featured', 'learnplus' ),
					'param_name'  => 'featured',
					'value'       => array(
						esc_html__('None', 'learnplus')    => '',
						esc_html__('3th', 'learnplus') => '3',
						esc_html__('4th', 'learnplus') => '4',
						esc_html__('2nd', 'learnplus') => '2',
					),
					'description' => __( 'Select numbers of plans you want to set as featured.', 'learnplus' ),
				),
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => esc_html__('Titles','learnplus'),
					'param_name'  => 'titles',
					'value'       => '',
					'description' => esc_html__("Enter titles for element (Note: divide columns linebreaks (Enter)).",'learnplus')
				),
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => esc_html__('Price','learnplus'),
					'param_name'  => 'price',
					'value'       => '',
					'description' => esc_html__("Enter Price for element (Note: divide columns linebreaks (Enter)).",'learnplus')
				),
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => esc_html__('Button Text','learnplus'),
					'param_name'  => 'button_text',
					'value'       => '',
					'description' => esc_html__("Enter button text for element (Note: divide columns linebreaks (Enter)).",'learnplus')
				),
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => esc_html__('Button Link','learnplus'),
					'param_name'  => 'button_link',
					'value'       => '',
					'description' => esc_html__("Enter button link for element (Note: divide columns linebreaks (Enter)).",'learnplus')
				),
				array(
					'type'        => 'textarea_html',
					'holder'      => 'div',
					'heading'     => esc_html__('Content','learnplus'),
					'param_name'  => 'content',
					'value'       => '',
					'description' => esc_html__("Enter the content ( Note: divide columns with '|' and devide rows with linebreaks (Enter)",'learnplus')
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => __( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'value'       => '',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				)
			),
		) );

		// Add contact shortcode
		vc_map( array(
			'name'     => esc_html__( 'Contact Details', 'learnplus' ),
			'base'     => 'contact',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Title', 'learnplus' ),
					'param_name'  => 'title',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Website', 'learnplus' ),
					'param_name'  => 'website',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'URL', 'learnplus' ),
					'param_name'  => 'url',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Email', 'learnplus' ),
					'param_name'  => 'email',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Phone', 'learnplus' ),
					'param_name'  => 'phone',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Fax', 'learnplus' ),
					'param_name'  => 'fax',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Address', 'learnplus' ),
					'param_name'  => 'address',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );

		// Add contact shortcode
		vc_map( array(
			'name'     => esc_html__( 'LearnPlus Google Maps', 'learnplus' ),
			'base'     => 'gmaps',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'attach_image',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Marker', 'learnplus' ),
					'param_name'  => 'marker',
					'value'       => '',
					'description' => esc_html__( 'Select an image from media library', 'learnplus' ),
				),
				array(
					'type'        => 'textarea',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Address', 'learnplus' ),
					'param_name'  => 'address',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Width(px)', 'learnplus' ),
					'param_name'  => 'width',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Height(px)', 'learnplus' ),
					'param_name'  => 'height',
					'value'       => '450',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Zoom', 'learnplus' ),
					'param_name'  => 'zoom',
					'value'       => '13',
				),
				array(
					'type'        => 'textarea_html',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Content', 'learnplus' ),
					'param_name'  => 'content',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );

		// Add products carousel shortcode
		vc_map( array(
			'name'     => esc_html__( 'LearnPlus Products Carousel', 'learnplus' ),
			'base'     => 'products_carousel',
			'class'    => '',
			'admin_enqueue_js'  => LEARNPLUS_ADDONS_URL . '/assets/js/vc/select2.min.js',
			'admin_enqueue_css' => LEARNPLUS_ADDONS_URL . '/assets/css/vc/select2.css',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Products', 'learnplus' ),
					'param_name'  => 'products',
					'value'       => array(
						esc_html__( 'Recent', 'learnplus' )       => 'recent',
						esc_html__( 'Featured', 'learnplus' )     => 'featured',
						esc_html__( 'Best Selling', 'learnplus' ) => 'best_selling',
						esc_html__( 'Top Rated', 'learnplus' )    => 'top_rated',
						esc_html__( 'On Sale', 'learnplus' )      => 'sale',
					)
				),
				array(
					'type'        => 'select_product_cat',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Category', 'learnplus' ),
					'param_name'  => 'categories',
					'description' => esc_html__( 'Select a category or select categories to get products.', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Total Products', 'learnplus' ),
					'param_name'  => 'per_page',
					'value'       => '12',
					'description' => esc_html__( 'Set numbers of products to show.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Columns', 'learnplus' ),
					'param_name'  => 'columns',
					'value'       => array(
						esc_html__( '4 columns', 'learnplus' ) => '4',
						esc_html__( '3 columns', 'learnplus' ) => '3'
					),
					'description' => esc_html__( 'Select numbers of columns you want to display.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Order By', 'learnplus' ),
					'param_name'  => 'orderby',
					'value'       => array(
						''                            => '',
						esc_html__( 'Date', 'learnplus' )       => 'date',
						esc_html__( 'Title', 'learnplus' )      => 'title',
						esc_html__( 'Menu Order', 'learnplus' ) => 'menu_order',
						esc_html__( 'Random', 'learnplus' )     => 'rand',
					),
					'dependency'  => array( 'element' => 'products', 'value' => array( 'top_rated', 'sale', 'featured' ) ),
					'description' => esc_html__( 'Select to order products. Leave empty to use the default order by of theme.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Order', 'learnplus' ),
					'param_name'  => 'order',
					'value'       => array(
						''                             => '',
						esc_html__( 'Ascending ', 'learnplus' )  => 'asc',
						esc_html__( 'Descending ', 'learnplus' ) => 'desc',
					),
					'dependency'  => array( 'element' => 'products', 'value' => array( 'top_rated', 'sale', 'featured' ) ),
					'description' => esc_html__( 'Select to sort products. Leave empty to use the default sort of theme', 'learnplus' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Slider autoplay', 'learnplus' ),
					'param_name'  => 'auto_play',
					'value'       => array( __( 'Yes', 'learnplus' ) => 'true' ),
					'description' => esc_html__( 'Enables autoplay mode.', 'learnplus' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Hide prev/next buttons', 'learnplus' ),
					'param_name'  => 'hide_navigation',
					'value'       => array( esc_html__( 'Yes', 'learnplus' ) => 'false' ),
					'description' => esc_html__( 'If "YES" prev/next control will be removed.', 'learnplus' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Gray Skin', 'learnplus' ),
					'param_name'  => 'gray_skin',
					'value'       => array( esc_html__( 'Yes', 'learnplus' ) => 'true' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'class_name',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );

		// Add products carousel shortcode
		vc_map( array(
			'name'     => esc_html__( 'Courses Carousel', 'learnplus' ),
			'base'     => 'courses_carousel',
			'class'    => '',
			'admin_enqueue_js'  => LEARNPLUS_ADDONS_URL . '/assets/js/vc/select2.min.js',
			'admin_enqueue_css' => LEARNPLUS_ADDONS_URL . '/assets/css/vc/select2.css',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'select_category',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Category', 'learnplus' ),
					'param_name'  => 'category',
					'description' => esc_html__( 'Select a category get courses ', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Total Courses', 'learnplus' ),
					'param_name'  => 'per_page',
					'value'       => '12',
					'description' => esc_html__( 'Set numbers of courses to show.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => __( 'Columns', 'learnplus' ),
					'param_name'  => 'columns',
					'value'       => array(
						esc_html__( '4 columns', 'learnplus' ) => '4',
						esc_html__( '3 columns', 'learnplus' ) => '3'
					),
					'description' => esc_html__( 'Select numbers of columns you want to display.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Order By', 'learnplus' ),
					'param_name'  => 'orderby',
					'value'       => array(
						esc_html__('Date', 'learnplus')       => 'date',
						esc_html__('Comments', 'learnplus')    => 'comment_count',
						esc_html__('Title', 'learnplus')      => 'title',
						esc_html__('Menu Order', 'learnplus') => 'menu_order',
						esc_html__('Random', 'learnplus')     => 'rand',
					),
					'description' => esc_html__( 'Select to order courses. Leave empty to use the default order by of theme.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Order', 'learnplus' ),
					'param_name'  => 'order',
					'value'       => array(
						esc_html__( 'Descending ', 'learnplus' ) => 'desc',
						esc_html__( 'Ascending ', 'learnplus' )  => 'asc',
					),
					'description' => esc_html__( 'Select to sort courses. Leave empty to use the default sort of theme', 'learnplus' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Slider autoplay', 'learnplus' ),
					'param_name'  => 'auto_play',
					'value'       => array( esc_html__( 'Yes', 'learnplus' ) => 'true' ),
					'description' => esc_html__( 'Enables autoplay mode.', 'learnplus' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Hide prev/next buttons', 'learnplus' ),
					'param_name'  => 'hide_navigation',
					'value'       => array( esc_html__( 'Yes', 'learnplus' ) => 'false' ),
					'description' => esc_html__( 'If "YES" prev/next control will be removed.', 'learnplus' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => __( 'Light Skin', 'learnplus' ),
					'param_name'  => 'light_skin',
					'value'       => array( esc_html__( 'Yes', 'learnplus' ) => 'true' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'class_name',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );

		// Add products carousel shortcode
		vc_map( array(
			'name'     => esc_html__( 'LearnPlus Courses', 'learnplus' ),
			'base'     => 'lp_courses',
			'class'    => '',
			'admin_enqueue_js'  => LEARNPLUS_ADDONS_URL . '/assets/js/vc/select2.min.js',
			'admin_enqueue_css' => LEARNPLUS_ADDONS_URL . '/assets/css/vc/select2.css',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Show', 'learnplus' ),
					'param_name'  => 'show',
					'value'       => array(
						esc_html__( 'Grid', 'learnplus' ) => 'grid',
						esc_html__( 'List', 'learnplus' ) => 'list'
					),
					'description' => esc_html__( 'Select courses type you want to display.', 'learnplus' ),
				),
				array(
					'type'        => 'checkbox',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Filterable', 'learnplus' ),
					'param_name'  => 'categoryselector',
					'dependency'  => array( 'element' => 'show', 'value' => array( 'grid' ) ),
					'value'       => array( esc_html__( 'Yes', 'learnplus' ) => 'true' ),
				),
				array(
					'type'        => 'select_category',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Categories', 'learnplus' ),
					'param_name'  => 'categories',
					'description' => esc_html__( 'Select a category or select categories to get courses ', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Courses per view', 'learnplus' ),
					'param_name'  => 'per_page',
					'value'       => '12',
					'description' => esc_html__( "Set numbers of courses you want to display at the same time.", 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Columns', 'learnplus' ),
					'param_name'  => 'columns',
					'value'       => array(
						esc_html__( '4 columns', 'learnplus' ) => '4',
						esc_html__( '3 columns', 'learnplus' ) => '3'
					),
					'dependency'  => array( 'element' => 'show', 'value' => array( 'grid' ) ),
					'description' => esc_html__( 'Select numbers of columns you want to display.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Order By', 'learnplus' ),
					'param_name'  => 'orderby',
					'value'       => array(
						esc_html__('Date', 'learnplus')       => 'date',
						esc_html__('Title', 'learnplus')      => 'title',
						esc_html__('Menu Order', 'learnplus') => 'menu_order',
						esc_html__('Random', 'learnplus')     => 'rand',
					),
					'description' => esc_html__( 'Select to order courses. Leave empty to use the default order by of theme.', 'learnplus' ),
				),
				array(
					'type'        => 'dropdown',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Order', 'learnplus' ),
					'param_name'  => 'order',
					'value'       => array(
						esc_html__( 'Descending ', 'learnplus' ) => 'desc',
						esc_html__( 'Ascending ', 'learnplus' )  => 'asc',
					),
					'description' => esc_html__( 'Select to sort courses. Leave empty to use the default sort of theme', 'learnplus' ),
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'class_name',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );


		// Add forum shortcode
		if( class_exists( 'bbPress' ) ) {
			vc_map( array(
				'name'     => esc_html__( 'BBPress Forums', 'learnplus' ),
				'base'     => 'forums',
				'class'    => '',
				'category' => esc_html__( 'Content', 'learnplus' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'holder'      => 'div',
						'heading'     => esc_html__( 'Total Forums', 'learnplus' ),
						'param_name'  => 'total',
						'value'       => '4',
						'description' => esc_html__( 'Set numbers of forums to show.', 'learnplus' ),
					),
					array(
						'type'        => 'checkbox',
						'holder'      => 'div',
						'heading'     => esc_html__( 'Show Pagination', 'learnplus' ),
						'param_name'  => 'pagination',
						'value'       => '',
					),
					array(
						'type'        => 'textfield',
						'holder'      => 'div',
						'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
					)
				),
			) );
		}

		// Add custom link shortcode
		vc_map( array(
			'name'     => esc_html__( 'Portfolio Showcase', 'learnplus' ),
			'base'     => 'portfolio_showcase',
			'class'    => '',
			'category' => esc_html__( 'Content', 'learnplus' ),
			'params'   => array(
				array(
					'type'       => 'dropdown',
					'holder'     => 'div',
					'heading'    => esc_html__( 'Showcase', 'learnplus' ),
					'param_name' => 'showcase',
					'value'      => $this->portfolio_showcase()
				),
				array(
					'type'        => 'textfield',
					'holder'      => 'div',
					'heading'     => esc_html__( 'Extra class name', 'learnplus' ),
					'param_name'  => 'class_name',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'learnplus' ),
				),
			),
		) );

		// Add Animation
		$attributes = array(
			array(
				'type'        => 'dropdown',
				'holder'      => 'div',
				'heading'     => esc_html__( 'Animation', 'learnplus' ),
				'param_name'  => 'animation',
				'group'       => esc_html__( 'Animation', 'learnplus' ),
				'description' => esc_html__( 'Select an animation for this element.', 'learnplus' ),
				'value'       => $this->learnplus_list_animation(),
			),
			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'heading'     => esc_html__( 'Duration', 'learnplus' ),
				'param_name'  => 'duration',
				'group'       => esc_html__( 'Animation', 'learnplus' ),
				'value'       => '',
				'description' => esc_html__( 'Duration of animation (in ms). Leave empty to use "1000 ms".', 'learnplus' ),
			),
			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'heading'     => esc_html__( 'Delay', 'learnplus' ),
				'group'       => esc_html__( 'Animation', 'learnplus' ),
				'param_name'  => 'delay',
				'value'       => '',
				'description' => esc_html__( 'Delay of animation (in ms). Leave empty to use "200 ms".', 'learnplus' ),
			),
		);

		$elements = array(
			'section_title',
			'icon_box',
			'testimonial',
			'images',
			'heading',
			'call_to_action',
			'sliders',
			'team',
			'counter',
			'posts',
			'button_box',
			'pricing',
			'contact'
		);

		if( class_exists( 'bbPress' )  ) {
			$elements[] = 'forums';
		}
		foreach ( $elements as $element ) {
			vc_add_params( $element, $attributes );
		}
	}

	/**
	 * Get available image sizes
	 *
	 * @return string
	 */
	function image_sizes() {
		$output = array();
		$output[esc_html__( 'Full Size', 'learnplus' )] = 'full';
		foreach ( $this->get_image_sizes() as $name => $size ) {
			$output[ucfirst( $name ) . ' (' . $size['width'] . 'x' . $size['height'] . ')'] = $name;
		}
		return $output;
	}

	/**
	 * Get available image sizes with width and height following
	 *
	 * @return array|bool
	 */
	function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes       = array();
		$image_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info
		foreach ( $image_sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[$size]['width']  = get_option( $size . '_size_w' );
				$sizes[$size]['height'] = get_option( $size . '_size_h' );
			} elseif ( isset( $_wp_additional_image_sizes[$size] ) ) {
				$sizes[$size] = array(
					'width'  => $_wp_additional_image_sizes[$size]['width'],
					'height' => $_wp_additional_image_sizes[$size]['height'],
				);
			}
		}

		return $sizes;
	}

	/**
	 * Get portfolio showcase
	 *
	 * @return array|string
	 */
	function portfolio_showcase() {
		$output = array();

		$args = array( 'post_type' => 'portfolio_showcase', 'posts_per_page' => '-1' );
		$the_query = new WP_Query( $args );
		while( $the_query->have_posts() ) : $the_query->the_post();
			$output[get_the_title()] = get_the_ID();
		endwhile;
		wp_reset_postdata();

		return $output;
	}

	/**
	 * Get categories
	 *
	 * @return array|string
	 */
	function get_categories( $taxonomy = 'category' ) {
		$output[esc_html__( 'All', 'learnplus' )] = '';
		$categories = get_terms( $taxonomy );
		if( $categories  ) {
			foreach ( $categories as $category ) {
				if( $category ) {
					$output[$category->name] = $category->slug;
				}
			}
		}
		return $output;
	}

	/**
	 * Get MailPoet Form
	 *
	 * @return array|string
	 */
	function get_mailpoet_forms() {
		if( ! class_exists( 'WYSIJA' ) ) {
			return '';
		}

		$model_forms = WYSIJA::get( 'forms', 'model' );
		$model_forms->reset();
		$forms = $model_forms->getRows( array( 'form_id', 'name' ) );

		$output = array();
		$output[esc_html__( 'Select a form', 'learnplus' )] = '0';
		if( $forms ) {
			foreach ( $forms as $form ) {
				$output[$form['name']] = $form['form_id'];
			}
		}
		return $output;
	}

	/**
	 * Return setting UI for select category param type
	 *
	 * @param  array $settings
	 * @param  string $value
	 *
	 * @return string
	 */
	function select_category_param( $settings, $value ) {
		// Generate dependencies if there are any
		return $this->get_taxonomy( $settings, $value, 'category' );
	}

	/**
	 * Return setting UI for select product category param type
	 *
	 * @param  array $settings
	 * @param  string $value
	 *
	 * @return string
	 */
	function select_product_cat_param( $settings, $value ) {
		// Generate dependencies if there are any
		return $this->get_taxonomy( $settings, $value, 'product_cat' );
	}

	/**
	 * Return setting UI for taxonomy param type
	 *
	 * @param  array $settings
	 * @param  string $value
	 *
	 * @return string
	 */
	function get_taxonomy( $settings, $value, $tax ) {
		// Generate dependencies if there are any
		$categories = get_terms( $tax );

		$cat = array();
		foreach( $categories as $category ) {
			if( $category ) {
				$cat[] = sprintf('<option value="%s">%s</option>',
					esc_attr( $category->slug ),
					$category->name
				);
			}

		}

		return sprintf(
			'<input type="hidden" name="%s" value="%s" class="wpb-input-categories wpb_vc_param_value wpb-textinput %s %s_field">
			<select class="select-categories-post">
			%s
			</select>',
			esc_attr( $settings['param_name'] ),
			esc_attr( $value ),
			esc_attr( $settings['param_name'] ),
			esc_attr( $settings['type'] ),
			implode( '', $cat )
		);
	}

	/**
	 * Get List Animation
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function learnplus_list_animation() {
		$icons = array(
			esc_html__( 'No Animation', 'learnplus' )       => '',
			esc_html__( 'Bounce', 'learnplus' )             => 'bounce',
			esc_html__( 'Flash', 'learnplus' )              => 'flash',
			esc_html__( 'Pulse', 'learnplus' )              => 'pulse',
			esc_html__( 'Rubberband', 'learnplus' )         => 'rubberBand',
			esc_html__( 'Shake', 'learnplus' )              => 'shake',
			esc_html__( 'Swing', 'learnplus' )              => 'swing',
			esc_html__( 'Tada', 'learnplus' )               => 'tada',
			esc_html__( 'Wobble', 'learnplus' )             => 'wobble',
			esc_html__( 'Bouncein', 'learnplus' )           => 'bounceIn',
			esc_html__( 'Bounceindown', 'learnplus' )       => 'bounceInDown',
			esc_html__( 'Bounceinleft', 'learnplus' )       => 'bounceInLeft',
			esc_html__( 'Bounceinright', 'learnplus' )      => 'bounceInRight',
			esc_html__( 'Bounceinup', 'learnplus' )         => 'bounceInUp',
			esc_html__( 'Bounceout', 'learnplus' )          => 'bounceOut',
			esc_html__( 'Bounceoutdown', 'learnplus' )      => 'bounceOutDown',
			esc_html__( 'Bounceoutleft', 'learnplus' )      => 'bounceOutLeft',
			esc_html__( 'Bounceoutright', 'learnplus' )     => 'bounceOutRight',
			esc_html__( 'Bounceoutup', 'learnplus' )        => 'bounceOutUp',
			esc_html__( 'Fadein', 'learnplus' )             => 'fadeIn',
			esc_html__( 'Fadeindown', 'learnplus' )         => 'fadeInDown',
			esc_html__( 'Fadeindownbig', 'learnplus' )      => 'fadeInDownBig',
			esc_html__( 'Fadeinleft', 'learnplus' )         => 'fadeInLeft',
			esc_html__( 'Fadeinleftbig', 'learnplus' )      => 'fadeInLeftBig',
			esc_html__( 'Fadeinright', 'learnplus' )        => 'fadeInRight',
			esc_html__( 'Fadeinrightbig', 'learnplus' )     => 'fadeInRightBig',
			esc_html__( 'Fadeinup', 'learnplus' )           => 'fadeInUp',
			esc_html__( 'Fadeinupbig', 'learnplus' )        => 'fadeInUpBig',
			esc_html__( 'Fadeout', 'learnplus' )            => 'fadeOut',
			esc_html__( 'Fadeoutdown', 'learnplus' )        => 'fadeOutDown',
			esc_html__( 'Fadeoutdownbig', 'learnplus' )     => 'fadeOutDownBig',
			esc_html__( 'Fadeoutleft', 'learnplus' )        => 'fadeOutLeft',
			esc_html__( 'Fadeoutleftbig', 'learnplus' )     => 'fadeOutLeftBig',
			esc_html__( 'Fadeoutright', 'learnplus' )       => 'fadeOutRight',
			esc_html__( 'Fadeoutrightbig', 'learnplus' )    => 'fadeOutRightBig',
			esc_html__( 'Fadeoutup', 'learnplus' )          => 'fadeOutUp',
			esc_html__( 'Fadeoutupbig', 'learnplus' )       => 'fadeOutUpBig',
			esc_html__( 'Flip', 'learnplus' )               => 'flip',
			esc_html__( 'Flipinx', 'learnplus' )            => 'flipInX',
			esc_html__( 'Flipiny', 'learnplus' )            => 'flipInY',
			esc_html__( 'Flipoutx', 'learnplus' )           => 'flipOutX',
			esc_html__( 'Flipouty', 'learnplus' )           => 'flipOutY',
			esc_html__( 'Lightspeedin', 'learnplus' )       => 'lightSpeedIn',
			esc_html__( 'Lightspeedout', 'learnplus' )      => 'lightSpeedOut',
			esc_html__( 'Rotatein', 'learnplus' )           => 'rotateIn',
			esc_html__( 'Rotateindownleft', 'learnplus' )   => 'rotateInDownLeft',
			esc_html__( 'Rotateindownright', 'learnplus' )  => 'rotateInDownRight',
			esc_html__( 'Rotateinupleft', 'learnplus' )     => 'rotateInUpLeft',
			esc_html__( 'Rotateinupright', 'learnplus' )    => 'rotateInUpRight',
			esc_html__( 'Rotateout', 'learnplus' )          => 'rotateOut',
			esc_html__( 'Rotateoutdownleft', 'learnplus' )  => 'rotateOutDownLeft',
			esc_html__( 'Rotateoutdownright', 'learnplus' ) => 'rotateOutDownRight',
			esc_html__( 'Rotateoutupleft', 'learnplus' )    => 'rotateOutUpLeft',
			esc_html__( 'Rotateoutupright', 'learnplus' )   => 'rotateOutUpRight',
			esc_html__( 'Hinge', 'learnplus' )              => 'hinge',
			esc_html__( 'Rollin', 'learnplus' )             => 'rollIn',
			esc_html__( 'Rollout', 'learnplus' )            => 'rollOut',
			esc_html__( 'Zoomin', 'learnplus' )             => 'zoomIn',
			esc_html__( 'Zoomindown', 'learnplus' )         => 'zoomInDown',
			esc_html__( 'Zoominleft', 'learnplus' )         => 'zoomInLeft',
			esc_html__( 'Zoominright', 'learnplus' )        => 'zoomInRight',
			esc_html__( 'Zoominup', 'learnplus' )           => 'zoomInUp',
			esc_html__( 'Zoomout', 'learnplus' )            => 'zoomOut',
			esc_html__( 'Zoomoutdown', 'learnplus' )        => 'zoomOutDown',
			esc_html__( 'Zoomoutleft', 'learnplus' )        => 'zoomOutLeft',
			esc_html__( 'Zoomoutright', 'learnplus' )       => 'zoomOutRight',
			esc_html__( 'Zoomoutup', 'learnplus' )          => 'zoomOutUp',
		);
		return $icons;
	}

}


if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Sliders extends WPBakeryShortCodesContainer {
	}
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Slider extends WPBakeryShortCode {
	}
}