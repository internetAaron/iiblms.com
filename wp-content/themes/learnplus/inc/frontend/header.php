<?php
/**
 * Hooks for template header
 *
 * @package LearnPlus
 */


if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep   Optional separator.
	 *
	 * @return string The filtered title.
	 */
	function learnplus_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}
		global $page, $paged;
		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'learnplus' ), max( $paged, $page ) );
		}

		return $title;
	}

	add_filter( 'wp_title', 'learnplus_wp_title', 10, 2 );
	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function learnplus_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}

	add_action( 'wp_head', 'learnplus_render_title' );
endif;

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0
 */
function learnplus_enqueue_scripts() {
	/* Register and enqueue styles */
	wp_deregister_style( 'font-awesome' ); // Doesn't use font awesome from Visual Composer. It is older version.
	wp_register_style( 'font-awesome', LEARNPLUS_URL . '/css/font-awesome.min.css', array(), '4.3.0' );
	wp_register_style( 'font-flaticon', LEARNPLUS_URL . '/css/flaticon.css', array(), '1.0.0' );
	wp_register_style( 'font-icons', LEARNPLUS_URL . '/css/stroke.min.css', array(), LEARNPLUS_VERSION );
	wp_register_style( 'bootstrap', LEARNPLUS_URL . '/css/bootstrap.min.css', array(), '3.3.2' );
	wp_register_style( 'learnplus-fonts', learnplus_fonts_url(), array(), LEARNPLUS_VERSION );

	wp_enqueue_style( 'learnplus', get_stylesheet_uri(), array( 'learnplus-fonts', 'bootstrap', 'font-awesome', 'font-icons', 'font-flaticon' ), LEARNPLUS_VERSION );

	// Load custom color scheme file
	if ( intval( learnplus_theme_option( 'custom_color_scheme' ) ) && ( learnplus_theme_option( 'custom_color_1' ) || learnplus_theme_option( 'custom_color_2' ) ) ) {
		$upload_dir = wp_upload_dir();
		$dir        = path_join( $upload_dir['baseurl'], 'custom-css' );
		$file       = $dir . '/color-scheme.css';
		wp_enqueue_style( 'learnplus-color-scheme', $file, LEARNPLUS_VERSION );
	}

	/** Register and enqueue scripts */
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'html5shiv', LEARNPLUS_URL . '/js/html5shiv.min.js', array(), '3.7.2' );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'respond', LEARNPLUS_URL . '/js/respond.min.js', array(), '1.4.2' );
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

	wp_register_script( 'learnplus-plugins', LEARNPLUS_URL . "/js/plugins$min.js", array( 'jquery' ), LEARNPLUS_VERSION, true );
	wp_enqueue_script( 'learnplus', LEARNPLUS_URL . "/js/scripts$min.js", array( 'learnplus-plugins',  'jquery-ui-autocomplete' ), LEARNPLUS_VERSION, true );


	wp_localize_script(
		'learnplus',
		'learnplus',
		array(
			'ajax_url'  => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( '_learnplus_nonce' ),
			'direction' => is_rtl() ? 'rtl' : '',
			'search_results' => esc_html__( 'Search Results for', 'learnplus' ),
			'all_results'    => esc_html__( 'All Results', 'learnplus' )
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'learnplus_enqueue_scripts' );

/**
 * Get favicon and home screen icons
 *
 * @since  1.0
 */
function learnplus_site_icons() {
	if ( function_exists( 'wp_site_icon' ) ) {
		return;
	}

	$favicon      = learnplus_theme_option( 'favicon' );
	$header_icons = ( $favicon ) ? '<link rel="icon" type="image/x-ico" href="' . esc_url( $favicon ) . '" />' : '';

	$icon_ipad_retina = learnplus_theme_option( 'icon_ipad_retina' );
	$header_icons .= ( $icon_ipad_retina ) ? '<link rel="apple-touch-icon" href="' . esc_url( $icon_ipad_retina ) . '" />' : '';

	$icon_ipad = learnplus_theme_option( 'icon_ipad' );
	$header_icons .= ( $icon_ipad ) ? '<link rel="apple-touch-icon" href="' . esc_url( $icon_ipad ) . '" />' : '';

	$icon_iphone_retina = learnplus_theme_option( 'icon_iphone_retina' );
	$header_icons .= ( $icon_iphone_retina ) ? '<link rel="apple-touch-icon" href="' . esc_url( $icon_iphone_retina ) . '" />' : '';

	$icon_iphone = learnplus_theme_option( 'icon_iphone' );
	$header_icons .= ( $icon_iphone ) ? '<link rel="apple-touch-icon" href="' . esc_url( $icon_iphone ) . '" />' : '';

	echo $header_icons;
}

add_action( 'wp_head', 'learnplus_site_icons' );

/**
 * Custom scripts and styles on header
 *
 * @since  1.0.0
 */
function learnplus_header_scripts() {
	/**
	 * All Custom CSS rules
	 */
	$inline_css = '';


	// Logo
	$logo_size_width = intval( learnplus_theme_option( 'logo_size_width' ) );
	$logo_css        = $logo_size_width ? 'width:' . $logo_size_width . 'px; ' : '';

	$logo_size_height = intval( learnplus_theme_option( 'logo_size_height' ) );
	$logo_css .= $logo_size_height ? 'height:' . $logo_size_height . 'px; ' : '';

	$logo_margin_top = intval( learnplus_theme_option( 'logo_margin_top' ) );
	$logo_css .= $logo_margin_top ? 'margin-top:' . $logo_margin_top . 'px;' : '';

	$logo_margin_right = intval( learnplus_theme_option( 'logo_margin_right' ) );
	$logo_css .= $logo_margin_right ? 'margin-right:' . $logo_margin_right . 'px;' : '';

	$logo_margin_bottom = intval( learnplus_theme_option( 'logo_margin_bottom' ) );
	$logo_css .= $logo_margin_bottom ? 'margin-bottom:' . $logo_margin_bottom . 'px;' : '';

	$logo_margin_left = intval( learnplus_theme_option( 'logo_margin_left' ) );
	$logo_css .= $logo_margin_left ? 'margin-left:' . $logo_margin_bottom . 'px;' : '';

	if ( ! empty( $logo_css ) ) {
		$inline_css .= '.site-header .site-branding .logo img ' . ' {' . $logo_css . '}';
	}

	// Custom CSS from singule post/page
	$css_custom = learnplus_get_meta( 'custom_page_css' ) . learnplus_theme_option( 'custom_css' );
	if ( ! empty( $css_custom ) ) {
		$inline_css .= $css_custom;
	}

	// Output CSS
	if ( ! empty( $inline_css ) ) {
		echo '<style type="text/css">' . $inline_css . '</style>';
	}

	/**
	 * Custom header javascripts
	 */
	$custom_js = '';
	if ( $header_scripts = learnplus_theme_option( 'header_scripts' ) ) {
		$custom_js .= $header_scripts;
	}

	// Output javascipt
	if ( ! empty( $custom_js ) ) {
		echo $custom_js;
	}
}

add_action( 'wp_head', 'learnplus_header_scripts' );

/**
 * Display topbar on top of site
 *
 * @since 1.0.0
 */
function learnplus_show_topbar() {
	if ( ! learnplus_theme_option( 'topbar' ) ) {
		return;
	}
	?>
	<div id="topbar" class="topbar">
		<div class="container">
			<div class="row">
				<div class="topbar-left topbar-widgets col-sm-6 col-md-6 text-left hidden-xs">
					<?php if ( is_active_sidebar( 'topbar-left' ) ) : ?>
						<?php dynamic_sidebar( 'topbar-left' ); ?>
					<?php endif; ?>
				</div>
				<div class="topbar-right topbar-widgets col-xs-12 col-sm-6 col-md-6 text-right">
					<?php if ( is_active_sidebar( 'topbar-right' ) ) : ?>
						<?php dynamic_sidebar( 'topbar-right' ); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'learnplus_before_header', 'learnplus_show_topbar', 5 );

/**
 * Display the site header
 *
 * @since 1.0.0
 */
function learnplus_show_header() {
	?>
	<div class="container">
		<div class="navbar row" role="navigation">
			<div class="navbar-header col-xs-12 col-sm-12 col-md-3">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="fa fa-bars"></span>
				</button>
				<?php get_template_part( 'parts/logo' ); ?>
			</div><!-- end navbar-header -->

			<nav id="site-navigation" class="primary-nav nav col-xs-12 col-md-9" role="navigation">
				<div class="main-nav">
					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'container'      => false,
								'walker'         => new LearnPlus_Walker_Mega_Menu,
							)
						);
					}
					?>
				</div>

				<?php if ( is_active_sidebar( 'header-right' ) && learnplus_theme_option( 'header_layout' ) == 'header-left' ) : ?>
					<div class="header-right header-widgets hidden-md hidden-xs hidden-sm col-lg-3">
						<?php
						ob_start();
						dynamic_sidebar( 'header-right' );
						$header_right = ob_get_clean();
						echo apply_filters( 'learnplus_widget_header', $header_right );
						?>
					</div>
				<?php endif; ?>
			</nav>
		</div>
	</div>
	<?php
}

add_action( 'learnplus_header', 'learnplus_show_header' );

/**
 * Change archive label for shop page
 *
 * @since  1.0.0
 *
 * @param  array $args
 *
 * @return array
 */
function learnplus_breadcrumbs_labels( $args ) {
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$args['labels']['archive'] = esc_html__( 'Shop', 'learnplus' );
	}

	return $args;
}

add_filter( 'learnplus_breadcrumbs_args', 'learnplus_breadcrumbs_labels' );
