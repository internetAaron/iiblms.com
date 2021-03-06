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
 * @since 2.1.0
 * 
 * @package LearnDash\Quiz
 */

if( ! empty( $lesson_progression_enabled ) && ! is_quiz_accessable( null, $post ) ) {
    if ( empty( $quiz_settings['lesson'] ) ) {
        _e( 'Please go back and complete the previous lesson.<br/>', 'learndash' );
    } else {
        _e( 'Please go back and complete the previous topic.<br/>', 'learndash' );
    }
}

if( $show_content ) {
    echo $content;
    if ( $attempts_left ) {
        echo $quiz_content;
    } else {
		?>
			<p id="learndash_already_taken"><?php echo sprintf( __( 'You have already taken this quiz %d times and may not take it again.', 'learndash' ), $attempts_count ); ?></p>
		<?php
    }
}