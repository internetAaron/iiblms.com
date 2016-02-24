<?php
/**
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

global $course;
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !( $lesson = $course->current_lesson ) ) {
	return;
}
?>

<?php if ( learn_press_user_has_completed_lesson() ) { ?>

	<?php learn_press_display_message( __( 'Congratulations! You have completed this lesson.', 'learn_press' ) ); ?>

<?php } else { ?>

	<?php if ( !LP()->user->has( 'finished-course', $course->id ) && LP()->user->has( 'enrolled-course', $course->id ) ) { ?>

		<button class="complete-lesson-button" data-id="<?php print_r( $lesson->id );?>"><?php _e( 'Complete Lesson', 'learn_press' );?></button>

	<?php } ?>

<?php } ?>