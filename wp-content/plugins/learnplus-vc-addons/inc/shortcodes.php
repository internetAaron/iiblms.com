<?php

/**
 * Define theme shortcodes
 *
 * @package LearnPlus
 */
class LearnPlus_Shortcodes {
	/**
	 * Store variables for js
	 *
	 * @var array
	 */
	public $l10n = array();

	/**
	 * Check if WooCommerce plugin is actived or not
	 *
	 * @var bool
	 */
	private $wc_actived = false;

	/**
	 * Construction
	 *
	 * @return LearnPlus_Shortcodes
	 */
	function __construct() {
		$this->wc_actived = function_exists( 'is_woocommerce' );

		$shortcodes = array(
			'section_title',
			'heading',
			'icon_box',
			'call_to_action',
			'sliders',
			'slider',
			'team',
			'counter',
			'posts',
			'testimonial',
			'images',
			'button_box',
			'pricing',
			'contact',
			'gmaps',
			'forums',
			'products_carousel',
			'courses_carousel',
			'lp_courses',
			'year',
			'site_link',
			'portfolio_showcase',
		);

		foreach( $shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}
		add_action( 'wp_footer', array( $this, 'footer' ) );
	}

	/**
	 * Load custom js in footer
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function footer() {
		// Load Google maps only when needed
		if( isset($this->l10n['map']) ) {
			echo '<script>if ( typeof google !== "object" || typeof google.maps !== "object" )
				document.write(\'<script src="//maps.google.com/maps/api/js?sensor=false"><\/script>\')</script>';
		}

		wp_enqueue_style( 'learnplus-shortcodes', LEARNPLUS_ADDONS_URL . '/assets/css/frontend.css', array(), '1.0.0' );
		wp_register_script( 'learnplus-carousel', LEARNPLUS_ADDONS_URL . '/assets/js/carousel.js', array(), '1.0.0' );
		wp_register_script( 'learnplus-bxslider', LEARNPLUS_ADDONS_URL . '/assets/js/bxslider.js', array(), '1.0.0' );
		wp_enqueue_script( 'learnplus-shortcodes', LEARNPLUS_ADDONS_URL . '/assets/js/frontend.js', array( 'jquery', 'learnplus-carousel', 'learnplus-bxslider' ), '1.0.0', true );
		wp_localize_script( 'learnplus-shortcodes', 'learnplusShortCode', $this->l10n );
	}

	/**
	 * Shortcode year
	 * Display current year
	 *
	 * @since 1.0
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function year( $atts, $content = null ) {
		return date( 'Y' );
	}

	/**
	 * Shortcode to display a link back to the site.
	 *
	 * @since 1.0
	 *
	 * @param array $atts Shortcode attributes
	 *
	 * @return string
	 */
	function site_link( $atts ) {
		$name = get_bloginfo( 'name' );

		return '<a class="site-link" href="' . esc_url( get_home_url() ) . '" title="' . esc_attr( $name ) . '" rel="home">' . $name . '</a>';
	}

	/**
	 * Shortcode to display section title
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function section_title( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'      => '',
				'text_align' => '',
				'dark_skin'  => '',
				'el_class'   => '',
				'animation'  => '',
				'duration'   => '1000',
				'delay'      => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		if( $content ) {
			$content = sprintf( '<p>%s</p>', $content );
		}

		if( $atts['title'] ) {
			$atts['title'] = sprintf( '<h4>%s</h4>', $atts['title'] );
		}

		if( $atts['text_align'] == 'left' ) {
			$css_class[] = 'text-left';
		} elseif( $atts['text_align'] == 'right' ) {
			$css_class[] = 'text-right';
		} else {
			$css_class[] = 'text-center';
		}

		if( $atts['dark_skin'] ) {
			$css_class[] = 'darkskin';
		}

		return sprintf(
			'<div class="section-title %s" %s>%s %s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			$atts['title'],
			$content
		);
	}

	/**
	 * Shortcode to display section title
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function heading( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'      => '',
				'text_align' => '',
				'el_class'   => '',
				'animation'  => '',
				'duration'   => '1000',
				'delay'      => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		$css_class[] = 'text-' . $atts['text_align'];

		return sprintf(
			'<div class="lp-heading %s" %s><h4>%s</h4></div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			$atts['title']
		);
	}

	/**
	 * Display icon box shortcode
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function icon_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'         => '',
				'icon'          => '',
				'icon_position' => 'top',
				'el_class'      => '',
				'border_radius' => '',
				'dark_skin'     => '',
				'animation'     => '',
				'duration'      => '1000',
				'delay'         => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		$output = array();
		if( $atts['title'] ) {
			$output[] = sprintf( '<p><strong>%s</strong></p>', $atts['title'] );
		}

		if( $content ) {
			$output[] = sprintf( '<p>%s</p>', do_shortcode( $content ) );
		}

		if( $atts['icon_position'] ) {
			$css_class[] = 'icon-' . $atts['icon_position'];
		}

		if( $atts['border_radius'] ) {
			$css_class[] = 'border-radius';
		}

		if( $atts['dark_skin'] ) {
			$css_class[] = 'darkskin';
		}

		return sprintf(
			'<div class="feature-list icon-box %s" %s>
				<i class="%s"></i>
				<div class="icon-content">%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			esc_attr( $atts['icon'] ),
			implode( '', $output )
		);
	}

	/**
	 * Display call to action shortcode
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function call_to_action( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'button_text' => '',
				'icon'        => '',
				'el_class'    => '',
				'button_link' => '',
				'animation'   => '',
				'duration'    => '1000',
				'delay'       => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		$output = sprintf( '<div class="col-md-9"><h4><i class="%s fa-3x alignleft"></i>%s</h4></div>', esc_attr( $atts['icon'] ), $content );
		if( $atts['button_text'] ) {
			$output .= sprintf(
				'<div class="col-md-3"><a href="%s" class="btn btn-primary btn-block">%s</a></div>',
				esc_url( $atts['button_link'] ),
				esc_attr( $atts['button_text'] )
			);
		}

		return sprintf(
			'<div class="callout %s" %s><div class="row">
				%s
			</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			$output
		);
	}

	/**
	 * Icon box tabs shortcode
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function sliders( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'image'     => '',
				'el_class'  => '',
				'animation' => '',
				'duration'  => '1000',
				'delay'     => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		$this->sliders = array();
		do_shortcode( $content );

		if( empty($this->sliders) ) {
			return '';
		}

		$output = array();
		$total = count( $this->sliders );

		if( !$total ) {
			return '';
		}

		$image_src = '';
		if( $atts['image'] ) {
			$image_src = wp_get_attachment_image_src( $atts['image'], 'full' );
			if( $image_src ) {
				$image_src = sprintf( ' <div class="col-md-6 myimg" style="background-image: url( %s )"></div>', esc_url( $image_src[0] ), esc_attr( $atts['image'] ) );
			}
		}

		foreach( $this->sliders as $index => $slider ) {

			$output[] = sprintf(
				'<li>
                    <div class="big-title">
						<p class="lead">%s</p>
						<h3>%s</h3>
						<div class="border-title"></div>
					</div><!-- big-title -->
					<p>%s</p>
					<a href="%s" class="btn btn-default">%s</a>
                </li>',
				$slider['subtitle'],
				$slider['content'],
				$slider['desc'],
				esc_url( $slider['link'] ),
				$slider['text']
			);
		}

		return sprintf(
			'<div class="section-lp-about section %s" %s>
            	<div class="row-fluid">
                	%s
                	<div class="container">
						<div class="row">
							<div class="col-md-6 col-md-offset-6">
								<div class="section-container nopadding">
									<div class="textrotate">
										<ul class="bxslider">%s</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			$image_src,
			implode( '', $output )
		);

	}

	/**
	 * Icon box tab shortcode
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function slider( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'subtitle' => '',
				'desc'     => '',
				'text'     => '',
				'link'     => '',
			), $atts
		);

		$this->sliders[] = array(
			'subtitle' => $atts['subtitle'],
			'desc'     => $atts['desc'],
			'text'     => $atts['text'],
			'content'  => $content,
			'link'     => $atts['link'],
		);

		return '';
	}

	/**
	 * Images team shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function team( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'total'     => '4',
				'el_class'  => '',
				'columns'   => '4',
				'animation' => '',
				'duration'  => '1000',
				'delay'     => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		$output = array();

		$id = uniqid( 'team-carousel-' );
		$this->l10n['teamCarousel'][$id] = array(
			'number' => intval( $atts['columns'] ),
		);

		$query_args = array(
			'posts_per_page'      => $atts['total'],
			'post_type'           => 'team_member',
			'ignore_sticky_posts' => true,
		);

		$class_columns = '';

		switch( $atts['columns'] ) {
			case 2:
				$class_columns = array( 'col-md-6', 'col-sm-6', 'col-xs-6' );
				break;

			case 3:
				$class_columns = array( 'col-md-4', 'col-sm-6', 'col-xs-6' );
				break;

			default:
				$class_columns = array( 'col-md-3', 'col-sm-6', 'col-xs-6' );
				break;
		}
		$query = new WP_Query( $query_args );
		while( $query->have_posts() ) : $query->the_post();
			$image = get_the_post_thumbnail( get_the_ID(), 'team-member' );

			$socials = get_post_meta( get_the_ID(), '_team_member_socials', true );
			$social_links = '';

			if( $socials ) {
				$social_links = array();
				foreach( $socials as $social => $link ) {
					if( $link ) {
						$social = 'googleplus' == $social ? 'google-plus' : $social;
						$social = 'vimeo' == $social ? 'vimeo-square' : $social;

						$social_links[] = sprintf( '<a href="%s" class="fa fa-%s"></a>', $link, $social );
					}

				}

				$social_links = '<div class="social">' . implode( '', $social_links ) . '</div>';
			}

			$output[] = sprintf(
				' <div class="%s lp-owl-carousel">
					<div class="team">
						%s
						<div class="team-hover-content">
							<h5>%s</h5>
							<span>%s</span>
							<p>%s</p>
							%s
						</div>
					</div><!-- end team -->
				</div>',
				esc_attr( implode( ' ', $class_columns ) ),
				$image,
				get_the_title(),
				get_post_meta( get_the_ID(), '_team_member_job', true ),
				get_the_excerpt(),
				$social_links
			);
		endwhile;

		wp_reset_postdata();

		return sprintf(
			'<div id="%s" class="lp-team-carousel %s" %s><div class="lp-owl-list row">%s</div></div>',
			esc_attr( $id ),
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			implode( '', $output )
		);
	}

	/**
	 * Display counter shortcode
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function counter( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'value'     => '',
				'title'     => '',
				'el_class'  => '',
				'animation' => '',
				'duration'  => '1000',
				'delay'     => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		if( $atts['value'] ) {
			$atts['value'] = sprintf( '<i class="stat-count">%s</i>', intval( $atts['value'] ) );
		}

		return sprintf(
			'<div class="funfactors service-center %s" %s>
				<div class="feature-list">
					%s
					<p><strong>%s</strong></p>
					<p>%s</p>
				</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			$atts['value'],
			$atts['title'],
			$content
		);
	}

	/**
	 * Images post shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function posts( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'total'      => '3',
				'el_class'   => '',
				'columns'    => '3',
				'categories' => '',
				'animation'  => '',
				'duration'   => '1000',
				'delay'      => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		$output = array();

		$id = uniqid( 'posts-carousel-' );
		$this->l10n['postsCarousel'][$id] = array(
			'number' => intval( $atts['columns'] ),
		);

		$query_args = array(
			'posts_per_page'      => $atts['total'],
			'post_type'           => 'post',
			'ignore_sticky_posts' => true,
		);


		if( !empty($atts['categories']) ) {
			$query_args['category_name'] = $atts['categories'];
		}

		$class_columns = '';

		switch( $atts['columns'] ) {
			case 2:
				$class_columns = array( 'col-md-6', 'col-sm-6', 'col-xs-12' );
				break;

			case 3:
				$class_columns = array( 'col-md-4', 'col-sm-4', 'col-xs-12' );
				break;

			default:
				$class_columns = array( 'col-md-3', 'col-sm-6', 'col-xs-12' );
				break;
		}
		$query = new WP_Query( $query_args );
		while( $query->have_posts() ) : $query->the_post();
			$image = get_the_post_thumbnail( get_the_ID(), 'learnplus-blog-thumb' );

			if( $image ) {
				$image = sprintf(
					'
					<div class="blog-image">
						<a href="%s" title="">%s</a>
					</div>',
					esc_url( get_permalink() ),
					$image
				);
			}

			$cats = get_the_category( get_the_ID() );
			if( $cats ) {
				$cats = sprintf(
					'<a class="category_title" href="%s" title="">%s</a>',
					esc_url( get_category_link( $cats[0]->term_id ) ),
					esc_attr( get_cat_name( $cats[0]->term_id ) )
				);
			}

			$tags = get_the_tags();
			$tags_ot = array();
			if( $tags ) {
				foreach( $tags as $tag ) {
					$tags_ot[] = sprintf(
						'<a href="%s">%s</a>',
						esc_attr( get_tag_link( $tag->term_id ) ),
						$tag->name
					);
					break;
				}

				$tags = sprintf( '<i class="fa fa-tag"></i> %s', implode( ',', $tags_ot ) );
			}

			$comments = wp_count_comments( get_the_ID() );
			if( $comments ) {
				$total_comments = $comments->total_comments;
				if( $total_comments < 2 ) {
					$comments = intval( $total_comments ) . esc_html__( ' comment', 'learnplus' );
				} else {
					$comments = intval( $total_comments ) . esc_html__( ' comments', 'learnplus' );
				}
			}


			$output[] = sprintf(
				'<div class="lp-owl-carousel %s">
					<div class="blog-wrapper">
						<div class="blog-title">
							%s
							<h2><a href="%s">%s</a></h2>
							<div class="post-meta">
								<span>
								<i class="fa fa-user"></i>
								<a href="%s">%s</a>
								</span>
								<span>
								%s
								</span>
								<span>
								<i class="fa fa-comments"></i>
								<span>%s</span>
								</span>
							</div>
						</div>
						%s
						<div class="blog-desc">
							<p>%s</p>
							<a href="%s" class="btn btn-default btn-block">%s</a>
						</div>
					</div>
				</div>',
				esc_attr( implode( ' ', $class_columns ) ),
				$cats,
				esc_url( get_permalink() ),
				get_the_title(),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author(),
				$tags,
				$comments,
				$image,
				get_the_excerpt(),
				esc_url( get_permalink() ),
				esc_html__( 'Read More', 'learnplus' )
			);
		endwhile;

		wp_reset_postdata();

		return sprintf(
			'<div id="%s" class="lp-blog blog-widget %s" %s><div class="lp-owl-list row">%s</div></div>',
			esc_attr( $id ),
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			implode( '', $output )
		);
	}

	/**
	 * Images testimonial shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function testimonial( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'total'     => '3',
				'el_class'  => '',
				'columns'   => '3',
				'animation' => '',
				'duration'  => '1000',
				'delay'     => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		$output = array();

		$query_args = array(
			'posts_per_page'      => $atts['total'],
			'post_type'           => 'testimonial',
			'ignore_sticky_posts' => true,
		);

		switch( $atts['columns'] ) {
			case 2:
				$class_columns = array( 'col-md-6', 'col-sm-6', 'col-xs-12' );
				break;

			case 3:
				$class_columns = array( 'col-md-4', 'col-sm-12', 'col-xs-12' );
				break;

			default:
				$class_columns = array( 'col-md-3', 'col-sm-6', 'col-xs-12' );
				break;
		}
		$query = new WP_Query( $query_args );
		while( $query->have_posts() ) : $query->the_post();
			$image = $this->get_image(
				array(
					'size'   => 'testimonial-thumb',
					'format' => 'src',
					'echo'   => false,
				)
			);

			if( $image ) {
				$image = sprintf(
					'<img class="alignleft img-circle" src="%s" alt="%s">',
					esc_url( $image ),
					esc_attr( get_the_title() )
				);
			}

			$cats = '';
			if( taxonomy_exists( 'testimonial_category' ) ) {
				$cats = wp_get_post_terms( get_the_ID(), 'testimonial_category' );

				if( $cats ) {
					$cats = sprintf(
						'<small><a href="%s">%s</a></small>',
						esc_url( get_term_link( $cats[0] ) ),
						esc_attr( $cats[0]->name )
					);
				}
			}

			$output[] = sprintf(
				'<div class="lp-testimonial %s row">
					<div class="testimonial">
						%s
						<p>%s</p>
						<div class="testimonial-meta">
							<h4>%s %s</h4>
						</div>
					</div>
				</div>',
				esc_attr( implode( ' ', $class_columns ) ),
				$image,
				get_the_excerpt(),
				get_the_title(),
				$cats
			);
		endwhile;

		wp_reset_postdata();

		return sprintf(
			'<div class="lp-testimonial-list %s" %s><div class="lp-owl-list row">%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			implode( '', $output )
		);
	}

	/**
	 * Images shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function images( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'images'              => '',
				'image_size'          => 'thumbnail',
				'custom_links'        => '',
				'custom_links_target' => '_self',
				'number'              => '6',
				'el_class'            => '',
				'animation'           => '',
				'duration'            => '1000',
				'delay'               => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		$output = array();
		$custom_links = $atts['custom_links'] ? explode( '<br />', $atts['custom_links'] ) : '';
		$images = $atts['images'] ? explode( ',', $atts['images'] ) : '';

		$id = uniqid( 'images-carousel-' );
		$this->l10n['imagesCarousel'][$id] = array(
			'number' => intval( $atts['number'] ),
		);
		if( $images ) {
			$i = 0;
			foreach( $images as $attachment_id ) {
				$image = wp_get_attachment_image_src( $attachment_id, $atts['image_size'] );
				if( $image ) {
					$link = '';
					if( $custom_links && isset($custom_links[$i]) ) {
						$link = 'href="' . esc_url( $custom_links[$i] ) . '"';
					}
					$output[] = sprintf(
						'<div class="col-md-2 col-sm-4 col-xs-6  lp-owl-carousel"><a %s target="%s" ><img alt="%s" class="img-responsive img-thumbnail" src="%s"></a></div>',
						$link,
						esc_attr( $atts['custom_links_target'] ),
						esc_attr( $attachment_id ),
						esc_url( $image[0] )
					);
				}
				$i++;
			}
		}

		return sprintf(
			'<div id="%s" class="images-owl-carousel %s" %s><div class="lp-owl-list row">%s</div></div>',
			esc_attr( $id ),
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			implode( '', $output )
		);
	}

	/**
	 * Display button box shortcode
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function button_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'icon_1'    => '',
				'text_1'    => '',
				'link_1'    => '',
				'icon_2'    => '',
				'text_2'    => '',
				'link_2'    => '',
				'el_class'  => '',
				'animation' => '',
				'duration'  => '1000',
				'delay'     => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		if( $content ) {
			$content = sprintf( '<p>%s</p>', do_shortcode( $content ) );
		}

		if( $atts['text_1'] ) {
			if( $atts['icon_1'] ) {
				$atts['text_1'] = sprintf( '<i class="%s"></i>', esc_attr( $atts['icon_1'] ) ) . $atts['text_1'];
			}
			$content .= sprintf(
				'<a href="%s" class="btn btn-default border-radius">%s</a>',
				esc_url( $atts['link_1'] ),
				$atts['text_1']
			);
		}

		if( $atts['text_2'] ) {
			if( $atts['icon_2'] ) {
				$atts['text_2'] = sprintf( '<i class="%s"></i>', esc_attr( $atts['icon_2'] ) ) . $atts['text_2'];
			}
			$content .= sprintf(
				'<a href="%s" class="btn btn-primary">%s</a>',
				esc_url( $atts['link_2'] ),
				$atts['text_2']
			);
		}

		return sprintf(
			'<div class="button-wrapper text-center %s" %s>
				%s
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			$content
		);
	}

	/**
	 * Display pricing shortcode
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function pricing( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'columns'     => '3',
				'titles'      => '',
				'price'       => '',
				'button_text' => '',
				'button_link' => '',
				'featured'    => '',
				'el_class'    => '',
				'animation'   => '',
				'duration'    => '1000',
				'delay'       => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		$output = array();

		$titles = explode( "\n", $atts['titles'] );
		$price = explode( "\n", $atts['price'] );
		$text = explode( "\n", $atts['button_text'] );
		$link = explode( "\n", $atts['button_link'] );
		$featured = intval( $atts['featured'] );
		$columns = intval( $atts['columns'] );

		$col_class = 'col-xs-4';
		if( $columns == '2' ) {
			$col_class = 'col-xs-6';
		} elseif( $columns == '4' ) {
			$col_class = 'col-xs-3';
		}

		if( !$titles ) {
			return;
		}

		$header = array();
		$i = 0;
		foreach( $titles as $title ) {

			if( $i > $columns - 1 ) {
				break;
			}

			$plan_class = 'my_plan2';
			$btn_class = 'btn-default';
			if( $featured == $i + 1 ) {
				$plan_class = 'my_plan3';
				$btn_class = 'btn-primary';
			}
			$btext = '';
			if( isset($text[$i]) ) {
				$btext = sprintf(
					'<a href="%s" type="button" class="btn %s">%s</a>',
					isset($link[$i]) ? esc_url( strip_tags( $link[$i] ) ) : '',
					esc_attr( $btn_class ),
					strip_tags( $text[$i] )
				);
			}

			$header[] = sprintf(
				'
				<div class="my_planHeader %s %s">
					<div class="my_planTitle">%s</div>
					<div class="my_planPrice">%s</div>
					%s
				</div>',
				esc_attr( $plan_class ),
				esc_attr( $col_class ),
				strip_tags( $title ),
				isset($price[$i]) ? strip_tags( $price[$i] ) : '',
				$btext
			);
			$i++;
		}

		$output[] = sprintf(
			'
			<div class="row">
            	<div class="col-xs-12 col-sm-offset-4 col-sm-8">
                	<div class="row">
                	%s
                	</div>
                </div>
            </div>',
			implode( '', $header )
		);

		if( $content ) {
			$content = explode( "\n", $content );
			if( $content ) {
				foreach( $content as $row ) {
					$row = explode( "|", $row );
					$i = 0;
					$column_data = array();
					$column_header = array();

					if( $row ) {
						foreach( $row as $label ) {
							if( $i > $columns ) {
								break;
							}
							$label = strip_tags( $label );
							if( $i == 0 ) {
								$column_header[] = sprintf(
									'
									<div class="col-xs-12 col-sm-4 my_feature col-xs-12">
										<p>%s</p>
									</div>',
									$label
								);
							} else {
								if( $label == 'Y' ) {
									$label = '<i class="fa fa-check my_check"></i>';
								}
								if( $label == 'N' ) {
									$label = '<i class="fa fa-close no_check"></i>';
								}
								$class_plan = 'my_plan2';
								if( $featured == $i ) {
									$class_plan = 'my_plan3';
								}
								$column_data[] = sprintf(
									'
									<div class="%s my_planFeature %s">
										%s
									</div>',
									esc_attr( $col_class ),
									esc_attr( $class_plan ),
									$label
								);
							}
							$i++;
						}
					}
					$output[] = sprintf(
						'<div class="row my_featureRow">%s <div class="col-xs-12 col-sm-8"><div class="row">%s</div></div></div>',
						implode( '', $column_header ),
						implode( '', $column_data )
					);

				}
			}
		}

		return sprintf(
			'<div class="pricing-table %s" %s>
				%s
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			implode( '', $output )
		);
	}

	/**
	 * Images forums shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function forums( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'total'      => '4',
				'el_class'   => '',
				'pagination' => '',
				'animation'  => '',
				'duration'   => '1000',
				'delay'      => '200',
			), $atts
		);

		if( !class_exists( 'bbPress' ) ) {
			return;
		}

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}
		$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
		$query_args = array(
			'posts_per_page'      => $atts['total'],
			'post_type'           => 'forum',
			'orderby'             => 'date',
			'order'               => 'desc',
			'paged'               => $paged,
			'ignore_sticky_posts' => true,
		);
		$wp_query = new WP_Query( $query_args );
		if( !$wp_query->have_posts() ) {
			return;
		}
		ob_start();
		printf(
			'<div id="bbpress-forums" class="%s" %s>
				<ul id="lp-forums-list" class="bbp-forums">
					<li class="bbp-header">
						<ul class="forum-titles">
							<li class="bbp-forum-info">%s</li>
							<li class="bbp-forum-topic-count">%s</li>
							<li class="bbp-forum-reply-count">%s</li>
							<li class="bbp-forum-freshness">%s</li>
						</ul>
					</li>
					<li class="bbp-body">
			',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			esc_html__( 'Forum', 'learnplus' ),
			esc_html__( 'Topics', 'learnplus' ),
			esc_html__( 'Posts', 'learnplus' ),
			esc_html__( 'Freshness', 'learnplus' )
		);

		while( $wp_query->have_posts() ) : $wp_query->the_post();
			?>
			<ul class="forum">
				<li class="bbp-forum-info">
					<a class="bbp-forum-title"
					   href="<?php esc_url( bbp_forum_permalink( get_the_ID() ) ) ?>"><?php bbp_forum_title( get_the_ID() ) ?></a>

					<div class="bbp-forum-content"><?php bbp_forum_content( get_the_ID() ) ?></div>
				</li>

				<li class="bbp-forum-topic-count"><?php bbp_forum_topic_count( get_the_ID() ) ?></li>
				<li class="bbp-forum-reply-count"><?php bbp_forum_post_count( get_the_ID() ) ?></li>
				<li class="bbp-forum-freshness">
					<?php bbp_forum_freshness_link( get_the_ID() ) ?>
					<p class="bbp-topic-meta">
						<span
							class="bbp-topic-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id( get_the_ID() ), 'size' => 14 ) ) ?></span>
					</p>

				</li>

			</ul>
			<?php
		endwhile;
		wp_reset_postdata();

		printf( '</li></ul></div>' );
		if( $atts['pagination'] ) {
			?>
			<nav class="navigation lp-forum-navigation paging-navigation numeric-navigation" role="navigation">
				<?php
				$big = 999999999;
				$args = array(
					'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'total'     => $wp_query->max_num_pages,
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
					'type'      => 'plain',
				);

				echo paginate_links( $args );
				?>
			</nav>
			<?php
		}

		return ob_get_clean();
	}

	/**
	 * Products Carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'products'        => 'recent',
				'categories'      => '',
				'per_page'        => 12,
				'columns'         => 4,
				'gray_skin'       => '',
				'orderby'         => '',
				'order'           => '',
				'auto_play'       => false,
				'hide_navigation' => false,
				'class_name'      => '',
			), $atts
		);

		$output = $this->get_products( $atts );

		$id = uniqid( 'products-carousel-' );
		$this->l10n['productsCarousel'][$id] = array(
			'number'     => $atts['columns'],
			'autoplay'   => $atts['auto_play'],
			'navigation' => !$atts['hide_navigation'],
		);

		if( $atts['gray_skin'] ) {
			$atts['class_name'] .= 'gray-skin';
		}

		return sprintf(
			'<div class="products-carousel section-products woocommerce %s" id="%s">%s</div>',
			esc_attr( $atts['class_name'] ),
			esc_attr( $id ),
			$output
		);
	}

	/**
	 * Courses Carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function courses_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'category'        => '',
				'per_page'        => 12,
				'columns'         => 4,
				'light_skin'      => false,
				'orderby'         => 'date',
				'order'           => 'desc',
				'auto_play'       => false,
				'hide_navigation' => true,
				'class_name'      => '',
			), $atts
		);

		$param = sprintf(
			'num="%s" order="%s" orderby="%s" category_name="%s" col="%s"',
			intval( $atts['per_page'] ),
			$atts['order'],
			$atts['orderby'],
			$atts['category'],
			intval( $atts['columns'] )
		);

		$output = do_shortcode( '[ld_course_list ' . $param . ']' );

		$id = uniqid( 'carouses-carousel-' );
		$this->l10n['carousesCarousel'][$id] = array(
			'number'     => $atts['columns'],
			'autoplay'   => $atts['auto_play'],
			'navigation' => $atts['hide_navigation'],
		);

		if( $atts['light_skin'] ) {
			$atts['class_name'] .= 'light-skin';
		}

		return sprintf(
			'<div class="carouses-carousel %s" id="%s">%s</div>',
			esc_attr( $atts['class_name'] ),
			esc_attr( $id ),
			$output
		);
	}

	/**
	 * Courses Grid shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function lp_courses( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'categories'       => '',
				'per_page'         => 12,
				'col'              => 4,
				'orderby'          => 'date',
				'order'            => 'desc',
				'categoryselector' => false,
				'show'             => 'grid',
				'author'           => '',
				'mycourses'        => false,
				'keyword'          => '',
				'class_name'       => '',
			), $atts
		);

		if( $atts['show'] == 'list' ) {
			$atts['col'] = 1;
			$atts['categoryselector'] = false;
		}


		if( !class_exists( 'SFWD_LMS' ) ) {
			return '';
		}

		$per_page = intval( $atts['per_page'] );

		$atts['category_name'] = $atts['categories'];

		$output = $this->get_courses( $atts );

		$filter = array(
			'post_type'           => 'sfwd-courses',
			'post_status'         => 'publish',
			'posts_per_page'      => -1,
			'order'               => $atts['order'],
			'orderby'             => $atts['orderby'],
			'ignore_sticky_posts' => 1,
		);
		if( !empty($atts['category_name']) ) {
			$filter['category_name'] = $atts['category_name'];
		}

		if( !empty($atts['keyword']) ) {
			$filter['s'] = $atts['keyword'];
		}

		if( !empty($atts['author']) ) {
			$filter['author'] = $atts['author'];
		}

		$loop = new WP_Query( $filter );
		$total_courses = intval( $loop->post_count );
		$total_pages = ceil( $total_courses / $per_page );
		if( $total_courses > $per_page ) {
			$output .= '<nav class="text-center navigation paging-navigation numeric-navigation">';
			$current_page = max( 1, get_query_var( 'paged' ) );
			if(is_front_page()) {
				$current_page = max( 1, get_query_var( 'page' ) );
			}
			$big = 999999999;
			$args = array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'total'     => $total_pages,
				'current'   => $current_page,
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'type'      => 'plain',
			);

			$output .= paginate_links( $args );
			$output .= '</nav>';
		}
		wp_reset_postdata();

		return sprintf(
			'<div class="lp-courses courses-grid course-list  %s">%s</div>',
			esc_attr( $atts['class_name'] ),
			$output
		);
	}

	/**
	 * Helper function, get products for shortcodes
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function get_products( $atts ) {
		global $woocommerce_loop;

		if( !$this->wc_actived ) {
			return '';
		}

		$atts = shortcode_atts(
			array(
				'products'   => 'recent',
				'categories' => '',
				'per_page'   => '',
				'columns'    => '',
				'orderby'    => '',
				'order'      => '',
			), $atts
		);

		$output = '';
		$meta_query = WC()->query->get_meta_query();

		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $atts['per_page'],
		);

		if( $atts['categories'] ) {
			$categories = explode( ',', $atts['categories'] );
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'terms'    => $categories,
					'field'    => 'slug',
				),
			);
		}

		if( $atts['products'] == 'recent' ) {
			$args['orderby'] = 'date';
			$args['order'] = 'desc';
		} elseif( $atts['products'] == 'featured' ) {
			$args['orderby'] = $atts['orderby'];
			$args['order'] = $atts['order'];

			$meta_query[] = array(
				'key'   => '_featured',
				'value' => 'yes',
			);
		} elseif( $atts['products'] == 'best_selling' ) {
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'desc';
			$args['meta_key'] = 'total_sales';
		} elseif( $atts['products'] == 'top_rated' ) {
			$args['orderby'] = $atts['orderby'];
			$args['order'] = $atts['order'];

			add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );
		} elseif( $atts['products'] == 'sale' ) {
			$args['orderby'] = $atts['orderby'];
			$args['order'] = $atts['order'];

			$product_ids_on_sale = wc_get_product_ids_on_sale();
			$args['post__in'] = array_merge( array( 0 ), $product_ids_on_sale );
			$args['no_found_rows'] = 1;
		}

		$args['meta_query'] = $meta_query;
		ob_start();

		$paged = (get_query_var( 'page' )) ? get_query_var( 'page' ) : 1;
		$args['paged'] = $paged;

		$products = new WP_Query( $args );
		$woocommerce_loop['columns'] = $atts['columns'];
		if( $products->have_posts() ) :

			woocommerce_product_loop_start();

			while( $products->have_posts() ) : $products->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile; // end of the loop.

			woocommerce_product_loop_end();

		endif;

		remove_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );

		$output .= ob_get_clean();

		return $output;
	}

	/**
	 * Shortcode to list courses
	 *
	 * @since 2.1.0
	 *
	 * @param  array $attr shortcode attributes
	 *
	 * @return string        shortcode output
	 */
	function get_courses( $attr ) {
		$post_type = $post_status = $per_page = $order = $orderby = $category_name = $keyword = $categoryselector = $author = $mycourses = $col = '';
		$shortcode_atts = shortcode_atts(
			array(
				'per_page'         => '',
				'post_type'        => 'sfwd-courses',
				'post_status'      => 'publish',
				'order'            => 'desc',
				'orderby'          => 'date',
				'category_name'    => '',
				'categoryselector' => '',
				'col'              => '',
				'author'           => '',
				'keyword'          => '',
				'mycourses'        => false,
			),
			$attr
		);

		extract( $shortcode_atts );

		$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
		if(is_front_page()) {
			$paged = max( 1, get_query_var( 'page' ) );
		}
		$offset = ($paged - 1) * $per_page;

		$filter = array(
			'post_type'      => $post_type,
			'post_status'    => $post_status,
			'posts_per_page' => $per_page,
			'order'          => $order,
			'orderby'        => $orderby,
			'offset'         => $offset,
		);


		if( !empty($category_name) ) {
			$filter['category_name'] = $category_name;
		}

		if( !empty($keyword) ) {
			$filter['s'] = $keyword;
		}

		if( !empty($author) ) {
			$filter['author'] = $author;
		}

		$loop = new WP_Query( $filter );

		$level = ob_get_level();
		ob_start();

		$cats = array();
		if( trim( $categoryselector ) == 'true' ) {
			$posts = get_posts( $filter );

			foreach( $posts as $post ) {
				$post_categories = wp_get_post_categories( $post->ID );

				foreach( $post_categories as $c ) {

					if( empty($cats[$c]) ) {
						$cat = get_category( $c );
						$cats[$c] = array( 'id' => $cat->cat_ID, 'name' => $cat->name, 'slug' => $cat->slug, 'parent' => $cat->parent, 'count' => 0, 'posts' => array() ); //stdClass Object ( [term_id] => 39 [name] => Category 2 [slug] => category-2 [term_group] => 0 [term_taxonomy_id] => 41 [taxonomy] => category [description] => [parent] => 0 [count] => 3 [object_id] => 656 [filter] => raw [cat_ID] => 39 [category_count] => 3 [category_description] => [cat_name] => Category 2 [category_nicename] => category-2 [category_parent] => 0 )

					}

					$cats[$c]['count']++;
					$cats[$c]['posts'][] = $post->ID;
				}

			}
		}


		while( $loop->have_posts() ) {
			$loop->the_post();
			if( trim( $categoryselector ) == 'true' && !empty($_GET['catid']) && !in_array( get_the_ID(), (array)@$cats[$_GET['catid']]['posts'] ) ) {

				continue;
			}

			if( !$mycourses || sfwd_lms_has_access( get_the_ID() ) ) {
				echo SFWD_LMS::get_template( 'course_list_template', array( 'shortcode_atts' => $shortcode_atts ) );
			}
		}

		wp_reset_query();

		$output = learndash_ob_get_clean( $level );
		wp_reset_query();

		return apply_filters( 'ld_course_list', $output, $shortcode_atts, $filter );
	}


	/**
	 * Display contact shortcode
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function contact( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'     => '',
				'website'   => '',
				'url'       => '',
				'email'     => '',
				'phone'     => '',
				'fax'       => '',
				'address'   => '',
				'el_class'  => '',
				'animation' => '',
				'duration'  => '1000',
				'delay'     => '200',
			), $atts
		);

		$data_animation = '';
		$css_class[] = $atts['el_class'];
		if( $atts['animation'] ) {
			$css_class[] = 'wow ' . esc_attr( $atts['animation'] );
			if( $atts['duration'] ) {
				$data_animation .= ' data-wow-duration=' . esc_attr( $atts['duration'] ) . 'ms';
			}
			if( $atts['delay'] ) {
				$data_animation .= ' data-wow-delay=' . esc_attr( $atts['delay'] ) . 'ms';
			}
		}

		if( $atts['title'] ) {
			$content .= sprintf(
				'
				<div class="widget-title">
					<h4>%s</h4>
					<hr>
				</div>',
				$atts['title']
			);
		}

		$content .= '<div class="contact-list"><ul class="contact-details">';

		if( $atts['website'] ) {
			$content .= sprintf(
				'
				 <li><i class="fa fa-link"></i> <a href="%s">%s</a></li>',
				esc_url( $atts['website'] ),
				$atts['url']
			);
		}

		if( $atts['email'] ) {
			$content .= sprintf(
				'
				 <li><i class="fa fa-envelope"></i> <a href="mailto:%s">%s</a></li>',
				esc_url( $atts['email'] ),
				$atts['email']
			);
		}

		if( $atts['phone'] ) {
			$content .= sprintf(
				'
				 <li><i class="fa fa-phone"></i>%s</li>',
				$atts['phone']
			);
		}

		if( $atts['fax'] ) {
			$content .= sprintf(
				'
				 <li><i class="fa fa-fax"></i>%s</li>',
				$atts['fax']
			);
		}

		if( $atts['address'] ) {
			$content .= sprintf(
				'
				 <li><i class="fa fa-home"></i>%s</li>',
				$atts['address']
			);
		}

		$content .= '</ul></div>';

		return sprintf(
			'<div class="content-widget contact %s" %s>
				%s
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $data_animation ),
			$content
		);
	}

	/**
	 * Shortcode to display portfolio showcase
	 *
	 * @since 1.0
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	function portfolio_showcase( $atts, $content ) {
		$atts = shortcode_atts( array(
			'showcase'   => '',
			'class_name' => '',
		), $atts );

		return sprintf( '<div class="portfolio-showcase %s">%s</div>',
			esc_attr( $atts['class_name'] ),
			do_shortcode( '[learnplus_portfolio_showcase id="' . esc_attr( $atts['showcase'] ) . '"]' )
		);
	}

	/**
	 * Map shortcode
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function gmaps( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'marker'  => '',
				'address' => '',
				'width'   => '',
				'height'  => '450',
				'zoom'    => '13',
				'css'     => '',
			), $atts
		);
		$class = array(
			'learnplus-map-shortcode',
			$atts['css'],
		);

		$style = '';
		if( $atts['width'] ) {
			$unit = 'px';
			if( strpos( $atts['width'], '%' ) ) {
				$unit = '%;';
			}

			$atts['width'] = intval( $atts['width'] );
			$style .= 'width: ' . $atts['width'] . $unit;
		}
		if( $atts['height'] ) {
			$unit = 'px';
			if( strpos( $atts['height'], '%' ) ) {
				$unit = '%;';
			}

			$atts['height'] = intval( $atts['height'] );
			$style .= 'height: ' . $atts['height'] . $unit;
		}
		if( $atts['zoom'] ) {
			$atts['zoom'] = intval( $atts['zoom'] );
		}

		$id = uniqid( 'ta_map_' );
		$html = sprintf(
			'<div class="%s"><div id="%s" class="ta-map" style="%s"></div></div>',
			implode( ' ', $class ),
			$id,
			$style
		);

		$coordinates = $this->get_coordinates( $atts['address'] );

		if( isset($coordinates['error']) ) {
			return $coordinates['error'];
		}
		$marker = '';
		if( $atts['marker'] ) {
			if( filter_var( $atts['marker'], FILTER_VALIDATE_URL ) ) {
				$marker = $atts['marker'];
			} else {
				$attachment_image = wp_get_attachment_image_src( intval( $atts['marker'] ), 'full' );
				$marker = $attachment_image ? $attachment_image[0] : '';
			}
		}

		$this->l10n['map'][$id] = array(
			'type'    => 'normal',
			'lat'     => $coordinates['lat'],
			'lng'     => $coordinates['lng'],
			'address' => $atts['address'],
			'zoom'    => $atts['zoom'],
			'marker'  => $marker,
			'height'  => $atts['height'],
			'info'    => $content,
		);

		return $html;
	}

	/**
	 * Helper function to get coordinates for map
	 *
	 * @since 1.0.0
	 *
	 * @param string $address
	 * @param bool $refresh
	 *
	 * @return array
	 */
	function get_coordinates( $address, $refresh = false ) {
		$address_hash = md5( $address );
		$coordinates = get_transient( $address_hash );
		$results = array( 'lat' => '', 'lng' => '' );

		if( $refresh || $coordinates === false ) {
			$args = array( 'address' => urlencode( $address ), 'sensor' => 'false' );
			$url = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
			$response = wp_remote_get( $url );

			if( is_wp_error( $response ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'learnplus' );

				return $results;
			}

			$data = wp_remote_retrieve_body( $response );

			if( is_wp_error( $data ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'learnplus' );

				return $results;
			}

			if( $response['response']['code'] == 200 ) {
				$data = json_decode( $data );

				if( $data->status === 'OK' ) {
					$coordinates = $data->results[0]->geometry->location;

					$results['lat'] = $coordinates->lat;
					$results['lng'] = $coordinates->lng;
					$results['address'] = (string)$data->results[0]->formatted_address;

					// cache coordinates for 3 months
					set_transient( $address_hash, $results, 3600 * 24 * 30 * 3 );
				} elseif( $data->status === 'ZERO_RESULTS' ) {
					$results['error'] = esc_html__( 'No location found for the entered address.', 'learnplus' );
				} elseif( $data->status === 'INVALID_REQUEST' ) {
					$results['error'] = esc_html__( 'Invalid request. Did you enter an address?', 'learnplus' );
				} else {
					$results['error'] = esc_html__( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'learnplus' );
				}
			} else {
				$results['error'] = esc_html__( 'Unable to contact Google API service.', 'learnplus' );
			}
		} else {
			$results = $coordinates; // return cached results
		}

		return $results;
	}

	/**
	 * Adjust brightness color
	 *
	 * @param  string $hex
	 * @param  int $steps Steps should be between -255 and 255
	 *
	 * @return string
	 */
	function adjust_brightness( $hex, $steps ) {
		// Steps should be between -255 and 255. Negative = darker, positive = lighter
		$steps = max( -255, min( 255, $steps ) );

		// Format the hex color string
		$hex = str_replace( '#', '', $hex );
		if( strlen( $hex ) == 3 ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		// Get decimal values
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		// Adjust number of steps and keep it inside 0 to 255
		$r = max( 0, min( 255, $r + $steps ) );
		$g = max( 0, min( 255, $g + $steps ) );
		$b = max( 0, min( 255, $b + $steps ) );

		$r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
		$g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
		$b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

		return '#' . $r_hex . $g_hex . $b_hex;
	}

	/**
	 * Display or get post image
	 *
	 * @since  1.0
	 *
	 * @param  array $args
	 *
	 * @return void|string
	 */
	function get_image( $args = array() ) {
		$default = array(
			'post_id'   => 0,
			'size'      => 'thumbnail',
			'format'    => 'html', // html or src
			'attr'      => '',
			'thumbnail' => true,
			'scan'      => true,
			'echo'      => true,
			'default'   => '',
			'meta_key'  => '',
		);

		$args = wp_parse_args( $args, $default );

		if( !$args['post_id'] ) {
			$args['post_id'] = get_the_ID();
		}

		// Get image from cache
		$key = md5( serialize( $args ) );
		$image_cache = wp_cache_get( $args['post_id'], __FUNCTION__ );

		if( !is_array( $image_cache ) ) {
			$image_cache = array();
		}

		if( empty($image_cache[$key]) ) {
			// Get post thumbnail
			if( has_post_thumbnail( $args['post_id'] ) && $args['thumbnail'] ) {
				$id = get_post_thumbnail_id( $args['post_id'] );
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list($src) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}

			// Get the first image in the custom field
			if( !isset($html, $src) && $args['meta_key'] ) {
				$id = get_post_meta( $args['post_id'], $args['meta_key'], true );

				// Check if this post has attached images
				if( $id ) {
					$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
					list($src) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
				}
			}

			// Get the first attached image
			if( !isset($html, $src) ) {
				$image_ids = array_keys(
					get_children(
						array(
							'post_parent'    => $args['post_id'],
							'post_type'      => 'attachment',
							'post_mime_type' => 'image',
							'orderby'        => 'menu_order',
							'order'          => 'ASC',
						)
					)
				);

				// Check if this post has attached images
				if( !empty($image_ids) ) {
					$id = $image_ids[0];
					$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
					list($src) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
				}
			}

			// Get the first image in the post content
			if( !isset($html, $src) && ($args['scan']) ) {
				preg_match( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );

				if( !empty($matches) ) {
					$html = $matches[0];
					$src = $matches[1];
				}
			}

			// Use default when nothing found
			if( !isset($html, $src) && !empty($args['default']) ) {
				if( is_array( $args['default'] ) ) {
					$html = @$args['html'];
					$src = @$args['src'];
				} else {
					$html = $src = $args['default'];
				}
			}

			// Still no images found?
			if( !isset($html, $src) ) {
				return false;
			}

			$output = 'html' === strtolower( $args['format'] ) ? $html : $src;

			$image_cache[$key] = $output;
			wp_cache_set( $args['post_id'], $image_cache, __FUNCTION__ );
		} // If image already cached
		else {
			$output = $image_cache[$key];
		}

		if( !$args['echo'] ) {
			return $output;
		}

		echo $output;
	}
}
