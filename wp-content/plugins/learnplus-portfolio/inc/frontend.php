<?php
/**
 * Display portfolio on frontend
 *
 * @package TA Portfolio Management
 */

/**
 * Load template file for portfolio
 * Check if a custom template exists in the theme folder,
 * if not, load template file in plugin
 *
 * @since  1.0.0
 *
 * @param  string $template Template name with extension
 *
 * @return string
 */
function learnplus_portfolio_get_template( $template ) {
	if( $theme_file = locate_template( array( $template ) ) ) {
		$file = $theme_file;
	} else {
		$file = LEARNPLUS_PORTFOLIO_DIR . 'template/' . $template;
	}

	return apply_filters( __FUNCTION__, $file, $template );
}

/**
 * Load template file for portfolio single
 *
 * @since  1.0.0
 *
 * @param  string $template
 *
 * @return string
 */
function learnplus_portfolio_template_include( $template ) {
	if( is_singular( 'portfolio_project' ) ) {
		return learnplus_portfolio_get_template( 'single-portfolio.php' );
	}

	return $template;
}

add_filter( 'template_include', 'learnplus_portfolio_template_include' );

/**
 * Enqueue scripts and styles for display portfolio with special layout and filter
 *
 * @since  1.0.0
 *
 * @return void
 */
function learnplus_portfolio_enqueue_scripts() {
	global $wp_query;
	$content = $wp_query->post->post_content;

	wp_register_script( 'images-loaded', LEARNPLUS_PORTFOLIO_URL . 'js/imagesloaded.pkgd.min.js', array( 'jquery' ), '3.1.8', true );
	wp_register_script( 'isotope', LEARNPLUS_PORTFOLIO_URL . 'js/isotope.pkgd.min.js', array( 'jquery' ), '2.0.0', true );
	wp_register_script( 'carousel', LEARNPLUS_PORTFOLIO_URL . 'js/owl.carousel.js', array( 'jquery' ), '2.0.0', true );
	wp_register_script( 'venobox', LEARNPLUS_PORTFOLIO_URL . 'js/venobox.min.js', array( 'jquery' ), '2.0.0', true );

	if( has_shortcode( $content, 'learnplus_portfolio' ) || has_shortcode( $content, 'portfolio_showcase' ) || has_shortcode( $content, 'learnplus_portfolio_showcase' ) || is_singular( 'portfolio_project' ) || is_tax( 'portfolio_category' ) || apply_filters( 'learnplus_portfolio_enqueue_scripts', false ) ) {
		if( apply_filters( 'learnplus_portfolio_frontend_css', true ) ) {
			wp_register_style( 'venobox', LEARNPLUS_PORTFOLIO_URL . 'css/venobox.css', array(), LEARNPLUS_PORTFOLIO_VER );
			wp_register_style( 'carousel', LEARNPLUS_PORTFOLIO_URL . 'css/owl-carousel.css', array(), LEARNPLUS_PORTFOLIO_VER );
			wp_enqueue_style( 'learnplus-portfolio', LEARNPLUS_PORTFOLIO_URL . 'css/portfolio.css', array( 'venobox', 'carousel' ), LEARNPLUS_PORTFOLIO_VER );
		}

		if( apply_filters( 'learnplus_portfolio_frontend_js', true ) ) {
			wp_enqueue_script( 'learnplus-portfolio', LEARNPLUS_PORTFOLIO_URL . 'js/portfolio.js', array( 'venobox', 'images-loaded', 'isotope', 'carousel' ), LEARNPLUS_PORTFOLIO_VER, true );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'learnplus_portfolio_enqueue_scripts' );

/**
 * Filter function to add classes to portfolio item
 *
 * @since  1.0.0
 *
 * @param  array $classes Default class
 *
 * @return array
 */
function learnplus_portfolio_class( $classes ) {
	if( 'portfolio_project' != get_post_type() ) {
		return $classes;
	}

	$terms = get_the_terms( get_the_ID(), 'portfolio_category' );
	if( $terms ) {
		foreach( $terms as $term ) {
			$classes[] = $term->slug;
		}
	}

	return $classes;
}

add_filter( 'post_class', 'learnplus_portfolio_class' );

/**
 * Filter function for adding 'portfolio-wide' class to portfolio item when layout is masonry
 *
 * @since  1.0.0
 *
 * @param  array $classes Default classes
 * @param  array $atts The shortcode attributes
 * @param  int $current Current post position in query
 * @param  int $number The number of posts being displayed
 *
 * @return array
 */
function learnplus_portfolio_masonry_item_class( $classes, $atts, $current, $number ) {
	if ( 'metro' != $atts['layout'] ) {
		return $classes;
	}

	$mod = $current % 8;


	if( $atts['columns'] == 4 ) {
		if (5 >= $number) {
			if (1 == $mod || 3 == $mod) {
				$classes[] = 'portfolio-wide';
			}

			if (2 == $mod) {
				$classes[] = 'portfolio-long';
			}
		} elseif (6 == $number) {
			if (4 == $mod) {
				$classes[] = 'portfolio-wide';
			}

			if (2 == $mod) {
				$classes[] = 'portfolio-long';
			}
		} else {
			if (1 == $mod || 7 == $mod) {
				$classes[] = 'portfolio-wide';
			}

			if (2 == $mod || 3 == $mod) {
				$classes[] = 'portfolio-long';
			}
		}
	} else {
		if (5 >= $number) {
			if ( 1 == $mod) {
				$classes[] = 'portfolio-wide';
			}

		} elseif (6 == $number) {
			if (1 == $mod || 5 == $mod) {
				$classes[] = 'portfolio-wide';
			}

			if (4 == $mod) {
				$classes[] = 'portfolio-long';
			}

		} else {
			if (1 == $mod || 7 == $mod) {
				$classes[] = 'portfolio-wide';
			}

			if ( 3 == $mod || 5 == $mod) {
				$classes[] = 'portfolio-long';
			}
		}
	}

	return $classes;
}

add_filter( 'learnplus_portfolio_item_class', 'learnplus_portfolio_masonry_item_class', 10, 4 );

/**
 * Filter function for changing image size when layout is masonry
 *
 * @since  1.0.0
 *
 * @param  array $size Default thumbnail size
 * @param  array $atts The shortcode attributes
 * @param  int $current Current post position in query
 * @param  int $number The number of posts being displayed
 *
 * @return string
 */
function learnplus_portfolio_masonry_thumbnail_size( $size, $atts, $current, $number ) {
	switch ( $atts['layout'] ) {
		case 'metro':
			$mod = $current % 8;
			if( $atts['columns'] == 4 ) {
				if (5 >= $number) {
					if (1 == $mod || 3 == $mod) {
						$size = 'portfolio-thumbnail-wide';
					}

					if (2 == $mod) {
						$size = 'portfolio-thumbnail-long';
					}
				} elseif (6 == $number) {
					if (2 == $mod) {
						$size = 'portfolio-thumbnail-long';
					}

					if (4 == $mod) {
						$size = 'portfolio-thumbnail-wide';
					}
				} else {
					if (1 == $mod || 7 == $mod) {
						$size = 'portfolio-thumbnail-wide';
					}

					if (2 == $mod || 3 == $mod) {
						$size = 'portfolio-thumbnail-long';
					}
				}
			} else {
				if (5 >= $number) {
					if (1 == $mod ) {
						$size = 'portfolio-thumbnail-wide';
					}

				} elseif (6 == $number) {
					if (1 == $mod || 5 == $mod) {
						$size = 'portfolio-thumbnail-wide';
					}
					if (4 == $mod) {
						$size = 'portfolio-thumbnail-long';
					}

				} else {
					if (1 == $mod || 7 == $mod) {
						$size = 'portfolio-thumbnail-wide';
					}

					if ( 3 == $mod || 5 == $mod) {
						$size = 'portfolio-thumbnail-long';
					}
				}
			}
			break;

		case 'masonry':
			$size = 'full';
			break;
	}

	return $size;
}

add_filter( 'learnplus_portfolio_item_thumnail_size', 'learnplus_portfolio_masonry_thumbnail_size', 10, 4 );


/**
 * Retrieves related product terms
 *
 * @param string $term
 * @return array
 */
function ta_get_related_terms($term, $post_id = null) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms_array = array(0);

	$terms = wp_get_post_terms($post_id, $term);
	foreach( $terms as $term ) {
		$terms_array[] = $term->term_id;
	}

	return array_map('absint', $terms_array);
}