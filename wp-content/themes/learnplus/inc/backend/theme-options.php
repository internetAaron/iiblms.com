<?php
/**
 * Register default theme options fields
 *
 * @package LearnPlus
 */


/**
 * Register theme options fields
 *
 * @since  1.0
 *
 * @return array Theme options fields
 */
function learnplus_theme_option_fields() {
	$options = array();

	// Help information
	$options['help'] = array(
		'document' => 'http://themealien.com/docs/learnplus/',
		'support'  => 'http://themealien.com/support/learnplus/',
	);


	// Sections
	$options['sections'] = array(
		'general'    => array(
			'icon'  => 'cog',
			'title' => esc_html__('General', 'learnplus'),
		),
		'layout'     => array(
			'icon'  => 'grid',
			'title' => esc_html__('Layout', 'learnplus'),
		),
		'style'      => array(
			'icon'  => 'palette',
			'title' => esc_html__('Style', 'learnplus'),
		),
		'header'     => array(
			'icon'  => 'browser',
			'title' => esc_html__('Header', 'learnplus'),
		),
		'page_title' => array(
			'icon'  => 'flow-tree',
			'title' => esc_html__('Page Title', 'learnplus'),
		),
		'content'    => array(
			'icon'  => 'news',
			'title' => esc_html__('Content', 'learnplus'),
		),
		'shop'       => array(
			'icon'  => 'shopping-cart',
			'title' => esc_html__('Shop', 'learnplus'),
		),
		'events'     => array(
			'icon'  => 'calendar',
			'title' => esc_html__('Events', 'learnplus'),
		),
		'lms'        => array(
			'icon'  => 'book',
			'title' => esc_html__('LMS', 'learnplus'),
		),
		'footer'     => array(
			'icon'  => 'rss',
			'title' => esc_html__('Footer', 'learnplus'),
		),
		'export'     => array(
			'icon'  => 'upload-to-cloud',
			'title' => esc_html__('Backup - Restore', 'learnplus'),
		),
	);

	// Fields
	$options['fields'] = array();
	$options['fields']['general'] = array();
	$options['fields']['general'] = array(

		array(
			'name'    => 'preloader',
			'label'   => esc_html__('Preloader', 'learnplus'),
			'type'    => 'switcher',
			'default' => true,
			'desc'    => esc_html__('Display a preloader when page is loading', 'learnplus'),
		),
		array(
			'type' => 'divider',
		),
		array(
			'name'    => 'no_animation',
			'label'   => esc_html__('No Animation', 'learnplus'),
			'type'    => 'switcher',
			'default' => false,
			'desc'    => esc_html__('Hide all animations.', 'learnplus'),
		),
		array(
			'type' => 'divider',
		),
		array(
			'name'    => 'back_to_top',
			'label'   => esc_html__('Show Back to Top', 'learnplus'),
			'type'    => 'switcher',
			'default' => false,
			'desc'    => esc_html__('Back to top icon will appear at the bottom of the page', 'learnplus'),
		),
	);

	if (!function_exists('wp_site_icon')) :
		$options['fields']['general'] = array_merge($options['fields']['general'], array(
			array(
				'type' => 'divider',
			),
			array(
				'name'  => 'favicon',
				'label' => esc_html__('Favicon', 'learnplus'),
				'type'  => 'icon',
			),
			array(
				'name'     => 'home_screen_icons',
				'label'    => esc_html__('Home Screen Icons', 'learnplus'),
				'desc'     => esc_html__('Select image file that will be displayed on home screen of handheld devices.', 'learnplus'),
				'type'     => 'group',
				'children' => array(
					array(
						'name'    => 'icon_ipad_retina',
						'type'    => 'icon',
						'subdesc' => esc_html__('IPad Retina (144x144px)', 'learnplus'),
					),
					array(
						'name'    => 'icon_ipad',
						'type'    => 'icon',
						'subdesc' => esc_html__('IPad (72x72px)', 'learnplus'),
					),

					array(
						'name'    => 'icon_iphone_retina',
						'type'    => 'icon',
						'subdesc' => esc_html__('IPhone Retina (114x114px)', 'learnplus'),
					),

					array(
						'name'    => 'icon_iphone',
						'type'    => 'icon',
						'subdesc' => esc_html__('IPhone (57x57px)', 'learnplus'),
					),
				),
			),
		));
	endif;

	$options['fields']['layout'] = array(
		array(
			'name'    => 'default_layout',
			'label'   => esc_html__('Default Layout', 'learnplus'),
			'desc'    => esc_html__('Default layout for whole site', 'learnplus'),
			'type'    => 'image_toggle',
			'default' => 'content-sidebar',
			'options' => array(
				'full-content'    => LEARNPLUS_OPTIONS_URL . 'img/sidebars/empty.png',
				'sidebar-content' => LEARNPLUS_OPTIONS_URL . 'img/sidebars/single-left.png',
				'content-sidebar' => LEARNPLUS_OPTIONS_URL . 'img/sidebars/single-right.png',
			),
		),
		array(
			'name'    => 'page_layout',
			'label'   => esc_html__('Page Layout', 'learnplus'),
			'desc'    => esc_html__('Default layout for pages', 'learnplus'),
			'type'    => 'image_toggle',
			'default' => 'full-content',
			'options' => array(
				'full-content'    => LEARNPLUS_OPTIONS_URL . 'img/sidebars/empty.png',
				'sidebar-content' => LEARNPLUS_OPTIONS_URL . 'img/sidebars/single-left.png',
				'content-sidebar' => LEARNPLUS_OPTIONS_URL . 'img/sidebars/single-right.png',
			),
		),
		array(
			'name'    => 'shop_layout',
			'label'   => esc_html__('Shop Layout', 'learnplus'),
			'desc'    => esc_html__('Default layout for shop, product archive pages', 'learnplus'),
			'type'    => 'image_toggle',
			'default' => 'full-content',
			'name'    => 'shop_layout',
			'label'   => esc_html__('Shop Layout', 'learnplus'),
			'desc'    => esc_html__('Default layout for shop, product archive pages', 'learnplus'),
			'type'    => 'image_toggle',
			'default' => 'full-content',
			'options' => array(
				'full-content'    => LEARNPLUS_OPTIONS_URL . 'img/sidebars/empty.png',
				'sidebar-content' => LEARNPLUS_OPTIONS_URL . 'img/sidebars/single-left.png',
				'content-sidebar' => LEARNPLUS_OPTIONS_URL . 'img/sidebars/single-right.png',
			),
		),
	);

	$options['fields']['style'] = array(
		array(
			'name'    => 'color_scheme',
			'label'   => esc_html__('Color Scheme', 'learnplus'),
			'desc'    => esc_html__('Select color scheme for website', 'learnplus'),
			'type'    => 'color_scheme',
			'default' => '',
			'options' => array(
				''       => '#e34b11|#5ba5af',
				'gold'   => '#eab830|#48a7d4',
				'red'    => '#ff4545|#018763',
				'violet' => '#144563|#1693A5',
				'blue'   => '#149dd2|#149dd2',
			),
		),
		array(
			'name'     => 'custom_color_scheme',
			'label'    => esc_html__('Custom Color Scheme', 'learnplus'),
			'desc'     => esc_html__('Enable custom color scheme to pick your own color scheme', 'learnplus'),
			'type'     => 'group',
			'layout'   => 'vertical',
			'children' => array(
				array(
					'name'    => 'custom_color_scheme',
					'type'    => 'switcher',
					'default' => false,
				),
				array(
					'name'    => 'custom_color_1',
					'type'    => 'color',
					'subdesc' => esc_html__('Custom Color', 'learnplus'),
				),
				array(
					'name'    => 'custom_color_2',
					'type'    => 'color',
					'subdesc' => esc_html__('Custom Color 2', 'learnplus'),
				),
			),
		),
		array(
			'type' => 'divider',
		),
		array(
			'name'     => 'custom_css',
			'label'    => esc_html__('Custom CSS', 'learnplus'),
			'type'     => 'code_editor',
			'language' => 'css',
			'subdesc'  => esc_html__('Enter your custom style rules here', 'learnplus'),
		),
	);

	$options['fields']['header'] = array(
		array(
			'name'    => 'topbar',
			'label'   => esc_html__('Topbar', 'learnplus'),
			'desc'    => esc_html__('Enable the topbar on the top of site', 'learnplus'),
			'subdesc' => sprintf(__('Please go to <a href="%s">Widgets</a> to drag widgets to topbar', 'learnplus'), admin_url('widgets.php')),
			'type'    => 'switcher',
		),
		array(
			'type' => 'divider',
		),
		array(
			'name'    => 'header_layout',
			'label'   => esc_html__('Header Layout', 'learnplus'),
			'desc'    => esc_html__('Select default layout for site header', 'learnplus'),
			'type'    => 'toggle',
			'default' => 'header-top',
			'options' => array(
				'header-top'    => esc_html__('Header Top', 'learnplus'),
				'header-left'   => esc_html__('Header Left', 'learnplus'),
				'header-sticky' => esc_html__('Header Sticky', 'learnplus'),

			),
		),
		array(
			'name'     => 'menu_extra',
			'label'    => esc_html__('Menu Extra', 'learnplus'),
			'desc'     => esc_html__('Display extra items at the end of primary menu', 'learnplus'),
			'type'     => 'checkbox_list',
			'multiple' => true,
			'options'  => array(
				'search' => '<i class="entypo-magnifying-glass"></i> Search',
			),
		),
		array(
			'name'    => 'search_post_type',
			'label'   => esc_html__('Search For', 'learnplus'),
			'desc'    => esc_html__('Select the type of search you want in the primary menu', 'learnplus'),
			'type'    => 'select',
			'options' => array(
				'sfwd-courses' => esc_html__('Courses', 'learnplus'),
				'product'      => esc_html__('Product', 'learnplus'),
			),
		),
		array(
			'type' => 'divider',
		),
		array(
			'name'  => 'logo',
			'label' => esc_html__('Logo', 'learnplus'),
			'desc'  => esc_html__('Select logo from media library or upload a new one', 'learnplus'),
			'type'  => 'image',
		),
		array(
			'name'     => 'logo_size',
			'label'    => esc_html__('Logo Size (Optional)', 'learnplus'),
			'desc'     => esc_html__('If the Retina Logo uploaded, please enter the size of the Standard Logo just upload above (not the Retina Logo)', 'learnplus'),
			'type'     => 'group',
			'children' => array(
				array(
					'name'    => 'logo_size_width',
					'type'    => 'number',
					'subdesc' => esc_html__('(Width)', 'learnplus'),
					'suffix'  => 'px',
				),
				array(
					'name'    => 'logo_size_height',
					'type'    => 'number',
					'subdesc' => esc_html__('(Height)', 'learnplus'),
					'suffix'  => 'px',
				),
			),
		),
		array(
			'name'     => 'logo_margin',
			'label'    => esc_html__('Logo Margin', 'learnplus'),
			'type'     => 'group',
			'children' => array(
				array(
					'name'    => 'logo_margin_top',
					'type'    => 'number',
					'size'    => 'mini',
					'subdesc' => esc_html__('top', 'learnplus'),
					'suffix'  => 'px',
				),
				array(
					'name'    => 'logo_margin_right',
					'type'    => 'number',
					'size'    => 'mini',
					'subdesc' => esc_html__('right', 'learnplus'),
					'suffix'  => 'px',
				),
				array(
					'name'    => 'logo_margin_bottom',
					'type'    => 'number',
					'size'    => 'mini',
					'subdesc' => esc_html__('bottom', 'learnplus'),
					'suffix'  => 'px',
				),
				array(
					'name'    => 'logo_margin_left',
					'type'    => 'number',
					'size'    => 'mini',
					'subdesc' => esc_html__('left', 'learnplus'),
					'suffix'  => 'px',
				),
			),
		),
		array(
			'type' => 'divider',
		),
		array(
			'name'  => 'header_scripts',
			'label' => esc_html__('Header Script', 'learnplus'),
			'desc'  => esc_html__('Enter your custom scripts here like Google Analytics script', 'learnplus'),
			'type'  => 'code_editor',
		),
	);

	$options['fields']['page_title'] = array(
		array(
			'name'    => 'show_page_title',
			'label'   => esc_html__('Page Title', 'learnplus'),
			'desc'    => esc_html__('Enable to show page tile bellow the site header', 'learnplus'),
			'type'    => 'switcher',
			'default' => true,
		),
		array(
			'name'    => 'page_title',
			'label'   => esc_html__('Show Page Title On', 'learnplus'),
			'desc'    => esc_html__('Select which page you want to show page title', 'learnplus'),
			'type'    => 'checkbox_list',
			'default' => array('shop', 'product', 'blog', 'post', 'page',),
			'options' => array(
				'shop'    => esc_html__('Shop', 'learnplus'),
				'product' => esc_html__('Singular product', 'learnplus'),
				'blog'    => esc_html__('Blog', 'learnplus'),
				'post'    => esc_html__('Singular blog post', 'learnplus'),
				'page'    => esc_html__('Singular page', 'learnplus'),
			),
		),
	);

	$options['fields']['content'] = array(
		array(
			'name'    => 'excerpt_length',
			'label'   => esc_html__('Excerpt Length', 'learnplus'),
			'type'    => 'number',
			'size'    => 'small',
			'default' => 30,
		),
	);

	$options['fields']['shop'] = array(
		array(
			'name'    => 'products_per_page',
			'label'   => esc_html__('Products Per Page', 'learnplus'),
			'type'    => 'number',
			'size'    => 'small',
			'desc'    => esc_html__('Specify how many products you want to show on shop page', 'learnplus'),
			'default' => 12,
		),
		array(
			'type'  => 'divider',
			'label' => esc_html__('Shop Single', 'learnplus'),
		),
		array(
			'name'    => 'shop_single_layout',
			'label'   => esc_html__('Shop Single Layout', 'learnplus'),
			'desc'    => esc_html__('Select default layout for shop single', 'learnplus'),
			'type'    => 'toggle',
			'default' => '',
			'options' => array(
				''                => esc_html__('Single Default', 'learnplus'),
				'shop-single-alt' => esc_html__('Single Alt', 'learnplus'),
			),
		),
		array(
			'name'    => 'products_related_image',
			'label'   => esc_html__('Product Related Image', 'learnplus'),
			'type'    => 'image',
			'desc'    => esc_html__('Set image for product releated title.', 'learnplus'),
			'default' => '',
		),
	);

	$cf7 = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');
	$contact_forms = array();
	if ($cf7) {
		foreach ($cf7 as $cform) {
			$contact_forms[$cform->ID] = $cform->post_title;
		}
	} else {
		$contact_forms[0] = esc_html__('No contact forms found', 'learnplus');
	}
	$options['fields']['events'] = array(
		array(
			'name'    => 'events_contact',
			'label'   => esc_html__('Contact Form 7', 'learnplus'),
			'desc'    => esc_html__('Select a contact form 7 that will be displayed in single event page.', 'learnplus'),
			'type'    => 'select',
			'default' => '0',
			'options' => $contact_forms,
		),
	);

	$options['fields']['lms'] = array(
		array(
			'name'    => 'course_related_image',
			'label'   => esc_html__('Course Related Image', 'learnplus'),
			'type'    => 'image',
			'desc'    => esc_html__('Set image for course releated title.', 'learnplus'),
			'default' => '',
		),
	);

	$options['fields']['footer'] = array(
		array(
			'name'    => 'footer_widgets',
			'label'   => esc_html__('Footer Widgets', 'learnplus'),
			'type'    => 'switcher',
			'default' => 1,
		),
		array(
			'name'    => 'footer_widget_columns',
			'label'   => esc_html__('Footer Widgets Columns', 'learnplus'),
			'desc'    => esc_html__('How many sidebar you want to show on footer', 'learnplus'),
			'type'    => 'image_toggle',
			'default' => 4,
			'options' => array(
				1 => LEARNPLUS_OPTIONS_URL . 'img/footer/one-column.png',
				2 => LEARNPLUS_OPTIONS_URL . 'img/footer/two-columns.png',
				3 => LEARNPLUS_OPTIONS_URL . 'img/footer/three-columns.png',
				4 => LEARNPLUS_OPTIONS_URL . 'img/footer/four-columns.png',
			),
		),
		array(
			'type' => 'divider',
		),
		array(
			'name'    => 'footer_copyright',
			'label'   => esc_html__('Footer Copyright', 'learnplus'),
			'subdesc' => esc_html__('HTML and shortcodes are allowed', 'learnplus'),
			'type'    => 'textarea',
			'default' => esc_html__('Copyright &copy; 2016', 'learnplus'),
		),
		array(
			'type' => 'divider',
		),
		array(
			'name'  => 'footer_script',
			'label' => esc_html__('Footer Scripts', 'learnplus'),
			'type'  => 'code_editor',
		),
	);

	$options['fields']['export'] = array(
		array(
			'name'    => 'backup',
			'label'   => esc_html__('Backup Settings', 'learnplus'),
			'subdesc' => esc_html__('You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options" button above', 'learnplus'),
			'type'    => 'backup',
		),
	);

	return $options;
}

add_filter('learnplus_theme_options', 'learnplus_theme_option_fields');

/**
 * Generate custom color scheme css
 *
 * @since 1.0
 */
function learnplus_generate_custom_color_scheme() {
	parse_str($_POST['data'], $data);

	if (!isset($data['custom_color_scheme']) || !$data['custom_color_scheme']) {
		return;
	}

	$color_1 = $data['custom_color_1'];
	$color_2 = $data['custom_color_2'];
	if (!$color_1 && !color_2) {
		return;
	}

	if (!$color_1) {
		$color_1 = '#e34b11';
	}

	if (!$color_2) {
		$color_2 = '#5ba5af';
	}

	// Getting credentials
	$url = wp_nonce_url('themes.php?page=theme-options');
	if (false === ($creds = request_filesystem_credentials($url, '', false, false, null))) {
		return; // stop the normal page form from displaying
	}

	// Try to get the wp_filesystem running
	if (!WP_Filesystem($creds)) {
		// Ask the user for them again
		request_filesystem_credentials($url, '', true, false, null);

		return;
	}

	global $wp_filesystem;

	// Prepare LESS to compile
	$less = $wp_filesystem->get_contents(LEARNPLUS_DIR . '/css/color-schemes/mixin.less');
	$less .= ".custom-color-scheme { .color-scheme($color_1, $color_2); }";

	// Compile
	require LEARNPLUS_DIR . '/inc/libs/lessc.inc.php';
	$compiler = new lessc;
	$compiler->setFormatter('compressed');
	$css = $compiler->compile($less);

	// Get file path
	$upload_dir = wp_upload_dir();
	$dir = path_join($upload_dir['basedir'], 'custom-css');
	$file = $dir . '/color-scheme.css';

	// Create directory if it doesn't exists
	wp_mkdir_p($dir);
	$wp_filesystem->put_contents($file, $css, FS_CHMOD_FILE);

	wp_send_json_success();
}

add_action('learnplus_ajax_generate_custom_css', 'learnplus_generate_custom_color_scheme');

/**
 * Load script for theme options
 *
 * @since 1.0.0
 *
 * @param string $hook
 */
function learnplus_theme_options_scripts($hook) {
	if ('appearance_page_theme-options' != $hook) {
		return;
	}

	$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script('learnplus-admin', LEARNPLUS_URL . "/js/backend/options$min.js", array('jquery'), LEARNPLUS_VERSION, true);
}

add_action('admin_enqueue_scripts', 'learnplus_theme_options_scripts');
