<?php
/**
 * Plugin Name: Learndash [course_status] shortcodes
 * Plugin URI:  http://www.google.com
 * Description: Shortcodes Extending Learndash
 * Version:     0.1.0
 * Author:      badsprad
 * Author URI:  http://www.google.com
 * License:     GPLv2+
 */

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Useful global constants
define( 'SF3LD_VERSION', '0.1.0' );
define( 'SF3LD_URL', plugin_dir_url( __FILE__ ) );
define( 'SF3LD_PATH', dirname( __FILE__ ) . '/' );

//Lets Go!

if ( ! class_exists( "sf3_learndash_shortcodes" ) ) {
	class sf3_learndash_shortcodes {

		public static $instance;
		private $options;

		const OPTIONS = 'sf3_learndash_shortcodes';
		const MENU_SLUG = 'sf3_learndash';

		function __construct() {
			self::$instance = $this;

			add_shortcode( 'course_status_pending', array( $this, 'course_status_pending' ) );
			add_shortcode( 'course_status_in_progress', array( $this, 'course_status_in_progress' ) );
			add_shortcode( 'course_status_completed', array( $this, 'course_status_completed' ) );
		}

		public function sf3_learndash_shortcodes() {
			$this->__construct();
		}

		function course_status_pending( $atts ) {
			if ( ! class_exists( 'SFWD_LMS' ) ) {
				return false;
			}

			$atts['shortcode_type'] = 'pending_courses';

			global $current_user;
			$my_courses      = ld_get_mycourses( $current_user->ID );
			$pending_courses = array();

			foreach ( $my_courses as $course_id ) {
				$course_progress = $this->sf3ld_get_course_progress( $course_id );
				if ( $course_progress['percentage'] == 0 ) {
					$pending_courses[ $course_id ] = $course_progress['percentage'];
				}
			}

			ob_start();

			echo $this->sf3ld_render_courses( $atts, $pending_courses );

			return ob_get_clean();

		}

		function course_status_in_progress( $atts ) {
			if ( ! class_exists( 'SFWD_LMS' ) ) {
				return false;
			}

			$atts['shortcode_type'] = 'in_progress_courses';

			global $current_user;
			$my_courses      = ld_get_mycourses( $current_user->ID );
			$pending_courses = array();

			foreach ( $my_courses as $course_id ) {
				$course_progress = $this->sf3ld_get_course_progress( $course_id );
				if ( $course_progress['percentage'] >= 1 && $course_progress['percentage'] < 100 ) {
					$pending_courses[ $course_id ] = $course_progress['percentage'];
				}
			}

			ob_start();

			echo $this->sf3ld_render_courses( $atts, $pending_courses );

			return ob_get_clean();

		}

		function course_status_completed( $atts ) {
			if ( ! class_exists( 'SFWD_LMS' ) ) {
				return false;
			}

			$atts['shortcode_type'] = 'completed_courses';

			global $current_user;
			$my_courses      = ld_get_mycourses( $current_user->ID );
			$pending_courses = array();

			foreach ( $my_courses as $course_id ) {
				$course_progress = $this->sf3ld_get_course_progress( $course_id );
				if ( $course_progress['percentage'] == 100 ) {
					$pending_courses[ $course_id ] = $course_progress['percentage'];
				}
			}

			ob_start();

			echo $this->sf3ld_render_courses( $atts, $pending_courses );

			return ob_get_clean();

		}

		function sf3ld_get_course_progress( $course_id, $user_id = null ) {
			if ( empty( $user_id ) ) {
				$current_user = wp_get_current_user();
				$user_id      = $current_user->ID;
			}

			if ( empty( $course_id ) ) {
				$course_id = learndash_get_course_id();
			}

			if ( empty( $course_id ) ) {
				return "";
			}

			$course_progress = get_user_meta( $user_id, '_sfwd-course_progress', true );

			$percentage = 0;
			$message    = '';
			if ( ! empty( $course_progress ) && ! empty( $course_progress[ $course_id ] ) && ! empty( $course_progress[ $course_id ]['total'] ) ) {
				$completed = intVal( $course_progress[ $course_id ]['completed'] );
				$total     = intVal( $course_progress[ $course_id ]['total'] );

				if ( $completed == $total - 1 ) {
					learndash_update_completion( $user_id );
					$course_progress = get_user_meta( $user_id, '_sfwd-course_progress', true );
					$completed       = intVal( $course_progress[ $course_id ]['completed'] );
					$total           = intVal( $course_progress[ $course_id ]['total'] );
				}

				$percentage = intVal( $completed * 100 / $total );
				$percentage = ( $percentage > 100 ) ? 100 : $percentage;
				$message    = $completed . " out of " . $total . " steps completed";
			}

			return array( "percentage" => @$percentage, "completed" => @$completed, "total" => @$total );

		}

		function sf3ld_render_courses( $atts, $courses ) {
			$output = '<div class="progress_shortcode_container">';

			if ( isset( $atts['showcount'] ) && $atts['showcount'] == true ) {
				if(count($courses) == 0){

					switch($atts['shortcode_type']){
						case 'pending_courses':
							$count_message = 'You have no courses pending.';
						break;

						case 'in_progress_courses':
							$count_message = 'You have no courses in progress.';
						break;

						case 'completed_courses':
							$count_message = 'You haven\'t completed any courses yet.';
						break;						

						default:
							$count_message = 'No Courses Found';
						break;
					}
					
				} 
				
				else {
					$count_message = ( count( $courses ) == 1 ) ? 'Found ' . count( $courses ) . ' Course' : 'Found ' . count( $courses ) . ' Courses';
				}
				
				$output .= '<div class="progress-course-count"><div class="alert alert-warning alert-dismissible"><button class="close" type="button" data-dismiss="alert">×<span class="sr-only">Close</span></button><strong>' . __( $count_message, 'sf3ld' ) . '</strong></div></div>';
			}

			foreach ( $courses as $course_id => $percentage ) {
				$attributes = array(
					'course_id'  => $course_id,
					'percentage' => $percentage
				);

				$output .= $this->pwho_learndash_course_content_shortcode( $attributes, $atts );
			}

			$output .= '</div>';

			return $output;

		}
		
		function pwho_learndash_course_content_shortcode( $atts, $shortcode_attributes ) {
			if ( empty( $atts["course_id"] ) ) {
				return '';
			}

			$course_id = $atts["course_id"];

			$course = $post = get_post( $course_id );

			if ( ! is_singular() || $post->post_type != "sfwd-courses" ) {
				return '';
			}

			$current_user = wp_get_current_user();

			$user_id    = $current_user->ID;
			$has_access = sfwd_lms_has_access( $course_id, $user_id );

			$lessons           = learndash_get_course_lessons_list( $course, $atts );
			$course_percentage = $atts['percentage'];

			if ( $has_access == false ) {
				return false;
			}

			$level = ob_get_level();
			ob_start();
			include( SFWD_LMS::get_template( 'sf3ld_course_loop', null, null, true ) );
			$content         = learndash_ob_get_clean( $level );
			$content         = str_replace( array( "\n", "\r" ), " ", $content );
			$user_has_access = $has_access ? "user_has_access" : "user_has_no_access";

			$output = apply_filters( "learndash_content", $content, $post );

			return $output;
		}		
		
	} //end sf3_learndash_shortcodes
} //end if exists

$GLOBALS['sf3_learndash_shortcodes'] = new sf3_learndash_shortcodes;

?>