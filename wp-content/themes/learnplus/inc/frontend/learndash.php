<?php
/**
 * Hooks for for LMS
 *
 * @package LearnPlus
 */

/**
 * Class LearnPlus_LearnDash
 */
class LearnPlus_LearnDash {
	/**
	 * LearnPlus_LearnDash constructor.
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'hooks' ) );

		if ( is_admin() ) {
			add_filter( 'learndash_post_args', array( __CLASS__, 'course_args' ) );
			add_action( 'switch_theme', array( __CLASS__, 'add_roles' ) );
		}
	}

	/**
	 * Frontend hooks
	 *
	 * @since 1.0.0
	 */
	public static function hooks() {
		add_filter( 'learndash_template', array( __CLASS__, 'course_list_template' ), 100, 5 );
		add_filter( 'ld_course_list', array( __CLASS__, 'course_list_wrap' ), 10, 3 );
		add_filter( 'ld_categorydropdown', '__return_null' );

		add_filter( 'learndash_quiz_content', array( __CLASS__, 'quiz_content' ) );

		add_filter( 'learndash_previous_post_link', array( __CLASS__, 'previous_post_link' ), 10, 3 );
		add_filter( 'learndash_next_post_link', array( __CLASS__, 'next_post_link' ), 10, 3 );
	}

	/**
	 * Get course price
	 *
	 * @param int $post_id
	 *
	 * @return string
	 */
	public static function get_price( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();

		$options  = get_option( 'sfwd_cpt_options' );
		$currency = null;
		if ( ! is_null( $options ) ) {
			if ( isset( $options['modules'] ) && isset( $options['modules']['sfwd-courses_options'] ) && isset( $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'] ) ) {
				$currency = $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'];
			}
		}
		if ( is_null( $currency ) ) {
			$currency = 'USD';
		}

		$course_options = get_post_meta( $post_id, "_sfwd-courses", true );
		$price          = $course_options && isset( $course_options['sfwd-courses_course_price'] ) ? $course_options['sfwd-courses_course_price'] : esc_html__( 'Free', 'learnplus' );
		if ( $price == '' ) {
			$price = esc_html__( 'Free', 'learnplus' );
		}

		if ( is_numeric( $price ) ) {
			if ( $currency == 'USD' ) {
				$price = '$' . $price;
			} else {
				$price .= ' ' . $currency;
			}
		}

		return $price;
	}

	/**
	 * Add more meta for single course
	 * This hook is run in backend
	 *
	 * @since 1.0.0
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public static function course_args( $args ) {
		foreach ( $args as $key => $post_arg ) {
			if ( $post_arg['post_type'] == 'sfwd-courses' ) {
				$args[$key]['fields'] = array_merge(
					array(
						'number_students' => array(
							'name'      => esc_html__( 'Number Of Students', 'learnplus' ),
							'type'      => 'text',
							'help_text' => esc_html__( 'Total number of students for this course', 'learnplus' ),
						),
						'period'          => array(
							'name'      => esc_html__( 'Period', 'learnplus' ),
							'type'      => 'text',
							'help_text' => esc_html__( 'The total length of course. Eg: "12 months".', 'learnplus' ),
						),
						'forum'           => array(
							'name'      => esc_html__( 'Forum', 'learnplus' ),
							'type'      => 'text',
							'help_text' => esc_html__( 'The forum URL for this course. Enter in format: "Forum name | Forum URL"', 'learnplus' ),
						),
					),
					$args[$key]['fields']
				);
			}
		}

		return $args;
	}

	/**
	 * Add new user role for instructors
	 *
	 * @TODO  Remove remove_role function when release
	 *
	 * @since 1.0.0
	 */
	public static function add_roles() {
		remove_role( 'instructor' );
		add_role(
			'instructor',
			esc_html__( 'Instructor', 'learnplus' ),
			array(
				'read'    => true,
				'level_3' => true,
			)
		);
	}

	/**
	 * Select template for displaying courses list
	 * Condition to check is 'col' attribute.
	 * If 'col' equal to 1 or 0, use 'course_list_template.php' inside learndash folder in theme.
	 * If 'col' greater than to 1, use 'course_grid_template.php' inside learndash folder in theme.
	 *
	 * @param $filepath
	 * @param $name
	 * @param $args
	 * @param $echo
	 * @param $return_file_path
	 *
	 * @return string
	 */
	public static function course_list_template( $filepath, $name, $args, $echo, $return_file_path ) {
		if ( $name == 'course_list_template' ) {
			$shortcode_atts = $args['shortcode_atts'];

			if ( isset( $shortcode_atts['categoryselector'] ) && trim( $shortcode_atts['categoryselector'] ) == 'true' ) {
				return LEARNPLUS_DIR . '/learndash/course_grid_template.php';
			} elseif ( ! isset( $shortcode_atts['col'] ) || ! $shortcode_atts['col'] || $shortcode_atts['col'] == 1 ) {
				return LEARNPLUS_DIR . '/learndash/course_list_template.php';
			} else {
				return LEARNPLUS_DIR . '/learndash/course_grid_template.php';
			}
		}

		return $filepath;
	}

	/**
	 * Wrap course grid with a div
	 *
	 * @param string $output
	 * @param array  $atts
	 *
	 * @return string
	 */
	public static function course_list_wrap( $output, $atts, $filter ) {
		if ( isset( $atts['categoryselector'] ) && trim( $atts['categoryselector'] ) == 'true' ) {
			$cats  = array();
			$posts = get_posts( $filter );

			foreach ( $posts as $post ) {
				$post_categories = wp_get_post_categories( $post->ID );

				foreach ( $post_categories as $c ) {

					if ( empty( $cats[$c] ) ) {
						$cat      = get_category( $c );
						$cats[$c] = array( 'id' => $cat->cat_ID, 'name' => $cat->name, 'slug' => $cat->slug, 'parent' => $cat->parent, 'count' => 0, 'posts' => array() );
					}

					$cats[$c]['count'] ++;
					$cats[$c]['posts'][] = $post->ID;
				}

			}

			$filter = array(
				'<li><a href="#filter" class="selected" data-option-value="*"><i class="fa fa-filter"></i> ' . esc_html__( 'All Courses', 'learnplus' ) . '</a></li>'
			);
			foreach ( $cats as $category ) {
				$filter[] = sprintf( '<li><a href="#filter" data-option-value=".category-%s">%s</a></li>', esc_attr( $category['slug'] ), esc_html( $category['name'] ) );
			}

			$output = '<div id="filters" class="filters-dropdown"><ul class="option-set" data-option-key="filter">' . implode( "\n", $filter ) . '</ul></div>'
				. '<div class="portfolio course-list">' . $output . '</div>';
		} elseif ( 1 < intval( $atts['col'] ) ) {
			$output = '<div class="row course-list course-grid">' . $output . '</div>';
		}

		return $output;
	}

	/**
	 * Get course rating html
	 *
	 * @param int          $course_id
	 * @param array|string $class
	 *
	 * @return string
	 */
	public static function get_rating_html( $course_id = null, $class = array(), $show_empty = true ) {
		$course_id = $course_id ? $course_id : get_the_ID();
		$rate      = learnplus_get_average_rating( $course_id );
		$html      = sprintf(
			'<div class="rating %s">
				<i class="fa fa-star-o"></i>
				<i class="fa fa-star-o"></i>
				<i class="fa fa-star-o"></i>
				<i class="fa fa-star-o"></i>
				<i class="fa fa-star-o"></i>
				<span style="width: %s">
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
				</span>
			</div>',
			is_array( $class ) ? esc_attr( join( ' ', $class ) ) : esc_attr( $class ),
			esc_attr( round( $rate / 5 * 100, 2 ) ) . '%'
		);

		if ( 0 === $rate && ! $show_empty ) {
			return '';
		}

		return $html;
	}

	/**
	 * Change quiz content
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public static function quiz_content( $content ) {
		$content = str_replace( 'class="wpProQuiz_button"', 'class="btn btn-primary btn-block"', $content );
		$content = str_replace( 'id="quiz_continue_link"', 'id="quiz_continue_link" class="btn btn-primary btn-block"', $content );

		return $content;
	}

	public static function previous_post_link( $link, $permalink, $link_name ) {
		return sprintf( '<a href="%s" class="btn btn-default btn-block" rel="prev">%s</a>', esc_url( $permalink ), esc_html( $link_name ) );
	}

	public static function next_post_link( $link, $permalink, $link_name ) {
		return sprintf( '<a href="%s" class="btn btn-default btn-block" rel="next">%s</a>', esc_url( $permalink ), esc_html( $link_name ) );
	}
}

