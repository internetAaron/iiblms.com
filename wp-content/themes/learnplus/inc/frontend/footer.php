<?php
/**
 * Hooks for displaying elements on footer
 *
 * @package LearnPlus
 */

/**
 * Display footer widgets
 *
 * @since 1.0.0
 */
function learnplus_footer_widgets() {
	if ( ! learnplus_theme_option( 'footer_widgets' ) ) {
		return;
	}
	?>
	<div id="footer-widgets" class="footer-widgets widgets-area">
		<div class="container">
			<div class="row">
				<?php
				$columns = max( 1, absint( learnplus_theme_option( 'footer_widget_columns' ) ) );
				$col_class = 'col-xs-12 col-sm-6 col-md-' . floor( 12 / $columns );

				for ( $i = 1; $i <= $columns; $i++ ) :
					?>
					<div class="footer-sidebar footer-<?php echo esc_attr( $i ) ?> <?php echo esc_attr( $col_class ) ?>">
						<?php dynamic_sidebar( "footer-$i" ); ?>
					</div>
				<?php endfor; ?>

			</div>
		</div>
	</div>
	<?php
}
add_action( 'learnplus_footer', 'learnplus_footer_widgets', 5 );


/**
 * Display footer nav menu before site footer
 *
 * @since 1.0.0
 */
function learnplus_footer_menu() {
	?>
	<div class="copyright">
		<div class="container">
			<div class="row">
				<div class="col-md-6 text-left">
					<?php echo do_shortcode( wp_kses( learnplus_theme_option( 'footer_copyright' ), wp_kses_allowed_html( 'post' ) ) ); ?>
				</div>
				<div class="col-md-6 text-right">
					<nav id="footer-nav" class="footer-nav nav">
						<?php
						if ( has_nav_menu( 'footer' ) ) {
							wp_nav_menu( array(
								'theme_location' => 'footer',
								'container'      => false,
								'class' => 'list-inline'
							) );
						}
						?>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<?php if ( learnplus_theme_option( 'back_to_top' ) ) : ?>
		<a id="scroll-top" class="backtotop" href="#page-top">
			<i class="fa fa-angle-up"></i>
		</a>
	<?php endif; ?>
	<?php
}
add_action( 'learnplus_after_footer', 'learnplus_footer_menu', 15 );

/**
 * Add scripts to footer
 *
 * @since 1.0.0
 */
function learnplus_footer_scripts() {
	$scripts = '';

	// Get custom js from theme options
	$scripts .= learnplus_theme_option( 'footer_scripts' );

	// Get custom js from singular page
	if ( is_singular() && $custom_js = learnplus_get_meta( 'custom_js' ) ) {
		$scripts .= $custom_js;
	}

	echo $scripts;
}
add_action( 'wp_footer', 'learnplus_footer_scripts' );
