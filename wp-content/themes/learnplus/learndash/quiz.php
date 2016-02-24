<?php
/**
 * Displays a quiz.
 *
 * Available Variables:
 *
 * $course_id       : (int) ID of the course
 * $course      : (object) Post object of the course
 * $course_settings : (array) Settings specific to current course
 * $course_status   : Course Status
 * $has_access  : User has access to course or is enrolled.
 *
 * $courses_options : Options/Settings as configured on Course Options page
 * $lessons_options : Options/Settings as configured on Lessons Options page
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 *
 * $user_id         : (object) Current User ID
 * $logged_in       : (true/false) User is logged in
 * $current_user    : (object) Currently logged in user object
 * $post            : (object) The quiz post object
 * $lesson_progression_enabled  : (true/false)
 * $show_content    : (true/false) true if user is logged in and lesson progression is disabled or if previous lesson and topic is completed.
 * $attempts_left   : (true/false)
 * $attempts_count : (integer) No of attempts already made
 * $quiz_settings   : (array)
 *
 * Note:
 *
 * To get lesson/topic post object under which the quiz is added:
 * $lesson_post = !empty($quiz_settings["lesson"])? get_post($quiz_settings["lesson"]):null;
 *
 * @since   2.1.0
 *
 * @package LearnDash\Quiz
 */
$lesson_post = ! empty( $quiz_settings['lesson'] ) ? get_post( $quiz_settings['lesson'] ) : null;

if ( ! empty( $lesson_post ) ) {
	$lesson_settings = learndash_get_setting( $lesson_post );
	$course          = get_post( $lesson_settings['course'] );
	$course_id       = $course->ID;
	$course_settings = learndash_get_setting( $course );
}

$students    = $course_settings && isset( $course_settings['number_students'] ) ? intval( $course_settings['number_students'] ) : esc_html__( 'Unlimited', 'learnplus' );
$period      = $course_settings && isset( $course_settings['period'] ) ? esc_html( $course_settings['period'] ) : '';
$forum       = $course_settings && isset( $course_settings['forum'] ) ? $course_settings['forum'] : '';
$author_name = get_the_author_meta( 'display_name', $course->post_author );
$user_login = get_the_author_meta( 'user_login', $course->post_author );
$parent_id   = $lesson_post ? $lesson_post->ID : $course_id;
?>

<div class="row">
	<div id="course-content" class="col-md-8">
		<div class="course-description">
			<small>
				<?php esc_html_e( 'Quiz for', 'learnplus' ) ?> :
				<span><a href="<?php echo esc_url( get_permalink( $parent_id ) ) ?>"><?php echo get_the_title( $parent_id ) ?></a></span>
			</small>
			<small>
				<?php esc_html_e( 'Quiz Status', 'learnplus' ) ?> :
				<span><?php echo learndash_is_quiz_notcomplete( $user_id, array( $post->ID => 1 ) ) ? esc_html__( 'Not Finished', 'learnplus' ) : esc_html__( 'Finished', 'learnplus' ); ?></span>
			</small>
			<h3 class="course-title"><?php the_title() ?></h3>
			<?php
			if ( ! empty( $lesson_progression_enabled ) && ! is_quiz_accessable( null, $post ) ) {
				if ( empty( $quiz_settings['lesson'] ) ) {
					esc_html_e( 'Please go back and complete the previous lesson.', 'learnplus' );
					echo '<br>';
				} else {
					esc_html_e( 'Please go back and complete the previous topic.', 'learnplus' );
					echo '<br>';
				}
			}

			if ( $show_content ) {
				echo $content;

				if ( $attempts_left ) {
					echo $quiz_content;
				} else {
					?>
					<p id="learndash_already_taken"><?php echo sprintf( __( 'You have already taken this quiz %d times and may not take it again.', 'learnplus' ), $attempts_count ); ?></p>
					<?php
				}
			}
			?>
		</div><!-- end desc -->
	</div>

	<div id="course-left-sidebar" class="col-md-4">
		<div class="course-image-widget">
			<?php echo get_the_post_thumbnail( $course_id, 'full', array( 'class' => 'img-responsive' ) ) ?>
		</div><!-- end image widget -->
		<div class="course-meta">
			<p class="course-category">
				<?php esc_html_e( 'Category', 'learnplus' ) ?> : <?php the_category( ', ', '', $course_id ) ?>
			</p>
			<?php if ( has_term( '', 'post_tag', $course_id ) ) : ?>
				<hr>
				<p class="course-tags">
					<?php esc_html_e( 'Tags', 'learnplus' ) ?>: <?php get_the_term_list( $course_id, 'post_tag', '', ', ', '' ) ?>
				</p>
			<?php endif ?>
			<hr>
			<div class="course-rating shopmeta">
				<?php esc_html_e( 'Reviews', 'learnplus' ) ?> : &nbsp;
				<?php echo LearnPlus_LearnDash::get_rating_html( $course_id ) ?>
			</div><!-- end rating -->
			<hr>

			<?php if( $students ) : ?>
			<p class="course-student"><?php printf( esc_html__( 'Students : %s Members', 'learnplus' ), $students ) ?></p>
			<hr>
			<?php endif; ?>

			<?php if( $period ) : ?>
			<p class="course-time"><?php printf( esc_html__( 'Period : %s', 'learnplus' ), $period ) ?></p>
			<hr>
			<?php endif; ?>

			<p class="course-instructors">
				<?php esc_html_e( 'Instructor', 'learnplus' ) ?> : <?php echo get_avatar( $course->post_author, 20, '', $author_name, array( 'class' => 'img-circle' ) ) ?> <a href="<?php echo esc_url( home_url( '/author/' . $user_login ) ) ?>"><?php echo $author_name ?></a>
			</p>

			<?php if( $forum ) : ?>
			<hr>
			<p class="course-forum">
				<?php esc_html_e( 'Course Forum', 'learnplus' ) ?> :
				<?php
				if ( $forum ) {
					$forum = explode( '|', $forum );
					printf( '<a href="%s">%s</a>', esc_url( $forum[1] ), esc_html( $forum[0] ) );
				}
				?>
			</p>
			<?php endif; ?>
		</div><!-- end meta -->
	</div>
</div>