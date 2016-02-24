<?php
/**
 * Displays a topic.
 *
 * Available Variables:
 *
 * $course_id        : (int) ID of the course
 * $course        : (object) Post object of the course
 * $course_settings : (array) Settings specific to current course
 * $course_status    : Course Status
 * $has_access    : User has access to course or is enrolled.
 *
 * $courses_options : Options/Settings as configured on Course Options page
 * $lessons_options : Options/Settings as configured on Lessons Options page
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 *
 * $user_id        : (object) Current User ID
 * $logged_in        : (true/false) User is logged in
 * $current_user    : (object) Currently logged in user object
 * $quizzes        : (array) Quizzes Array
 * $post            : (object) The topic post object
 * $lesson_post    : (object) Lesson post object in which the topic exists
 * $topics        : (array) Array of Topics in the current lesson
 * $all_quizzes_completed : (true/false) User has completed all quizzes on the lesson Or, there are no quizzes.
 * $lesson_progression_enabled    : (true/false)
 * $show_content    : (true/false) true if lesson progression is disabled or if previous lesson and topic is completed.
 * $previous_lesson_completed    : (true/false) true if previous lesson is completed
 * $previous_topic_completed    : (true/false) true if previous topic is completed
 *
 * @since   2.1.0
 *
 * @package LearnDash\Topic
 */

$students        = isset( $course_settings) && $course_settings && isset( $course_settings['number_students'] ) ? intval( $course_settings['number_students'] ) : esc_html__( 'Unlimited', 'learnplus' );
$period          = isset( $course_settings) && $course_settings && isset( $course_settings['period'] ) ? esc_html( $course_settings['period'] ) : '';
$forum           = isset( $course_settings) && $course_settings && isset( $course_settings['forum'] ) ? $course_settings['forum'] : '';
$author_name     = isset( $course ) && $course ? get_the_author_meta( 'display_name', $course->post_author ): '';
$user_login = isset( $course ) && $course ? get_the_author_meta( 'user_login', $course->post_author ) : '';
$topic_completed = false;

foreach ( $topics as $topic ) {
	if ( $topic->ID === $post->ID && ! empty( $topic->completed ) ) {
		$topic_completed = true;
		break;
	}
}
?>

<div class="row">
	<div id="course-content" class="col-md-8">
		<div class="course-description">
			<small>
				<?php esc_html_e( 'Topic for', 'learnplus' ) ?> :
				<span><a href="<?php echo esc_url( get_permalink( $lesson_id ) ) ?>"><?php echo get_the_title( $lesson_id ) ?></a></span>
			</small>
			<small>
				<?php esc_html_e( 'Topic Status', 'learnplus' ) ?> :
				<span><?php echo $topic_completed ? esc_html__( 'Finished', 'learnplus' ) : esc_html__( 'Not Finished', 'learnplus' ); ?></span>
			</small>

			<h3 class="course-title"><?php the_title() ?></h3>

			<?php
			/**
			 * Topic Dots
			 */
			?>
			<?php if ( ! empty( $topics ) ) : ?>
				<div id='learndash_topic_dots-<?php echo esc_attr( $lesson_id ); ?>' class="learndash_topic_dots type-dots">

					<b><?php _e( 'Topic Progress:', 'learnplus' ); ?></b>

					<?php foreach ( $topics as $key => $topic ) : ?>
						<?php $completed_class = empty( $topic->completed ) ? 'topic-notcompleted' : 'topic-completed'; ?>
						<a class='<?php echo esc_attr( $completed_class ); ?>' href='<?php echo get_permalink( esc_attr( $topic->ID ) ); ?>' title='<?php echo esc_attr( $topic->post_title ); ?>'>
							<span title='<?php echo esc_attr( $topic->post_title ); ?>'></span>
						</a>
					<?php endforeach; ?>

				</div>
			<?php endif; ?>

			<?php if ( $lesson_progression_enabled && ! $previous_topic_completed ) : ?>

				<span id="learndash_complete_prev_topic"><?php _e( 'Please go back and complete the previous topic.', 'learnplus' ); ?></span>
				<br />

			<?php elseif ( $lesson_progression_enabled && ! $previous_lesson_completed ) : ?>

				<span id="learndash_complete_prev_lesson"><?php _e( 'Please go back and complete the previous lesson.', 'learnplus' ); ?></span>
				<br />

			<?php endif; ?>

			<?php if ( $show_content ) : ?>

				<?php echo $content; ?>

				<?php if ( ! empty( $quizzes ) ) : ?>
					<div id="learndash_quizzes">
						<div id="quiz_heading">
							<span><?php _e( 'Quizzes', 'learnplus' ) ?></span><span class="right"><?php _e( 'Status', 'learnplus' ) ?></span>
						</div>

						<div id="quiz_list">
							<?php foreach ( $quizzes as $quiz ) : ?>
								<div id='post-<?php echo esc_attr( $quiz['post']->ID ); ?>' class='<?php echo esc_attr( $quiz['sample'] ); ?>'>
									<div class="list-count"><?php echo $quiz['sno']; ?></div>
									<h4>
										<a class='<?php echo esc_attr( $quiz['status'] ); ?>' href='<?php echo esc_attr( $quiz['permalink'] ); ?>'><?php echo $quiz['post']->post_title; ?></a>
									</h4>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( lesson_hasassignments( $post ) ) : ?>

					<?php $assignments = learndash_get_user_assignments( $post->ID, $user_id ); ?>

					<div id="learndash_uploaded_assignments">
						<h2><?php _e( 'Files you have uploaded', 'learnplus' ); ?></h2>
						<table>
							<?php if ( ! empty( $assignments ) ) : ?>
								<?php foreach ( $assignments as $assignment ) : ?>
									<tr>
										<td>
											<a href='<?php echo esc_attr( get_post_meta( $assignment->ID, 'file_link', true ) ); ?>' target="_blank"><?php echo __( 'Download', 'learnplus' ) . ' ' . get_post_meta( $assignment->ID, 'file_name', true ); ?></a>
										</td>
										<td>
											<a href='<?php echo esc_attr( get_permalink( $assignment->ID ) ); ?>'><?php _e( 'Comments', 'learnplus' ); ?></a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</table>
					</div>

				<?php endif; ?>


				<?php
				/**
				 * Show Mark Complete Button
				 */
				?>
				<?php if ( $all_quizzes_completed ) : ?>
					<?php echo '<br />' . learndash_mark_complete( $post ); ?>
				<?php endif; ?>

			<?php endif; ?>

			<div id="learndash_next_prev_link" class="row">
				<div class="col-md-6"><?php echo learndash_previous_post_link(); ?></div>
				<div class="col-md-6"><?php echo learndash_next_post_link(); ?></div>
			</div>
		</div>
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
			<p class="course-student"><?php printf( esc_html__( 'Students : %s Members', 'learnplus' ), $students ) ?></p>
			<?php if( $period ) : ?>
			<hr>
			<p class="course-time"><?php printf( esc_html__( 'Period : %s', 'learnplus' ), $period ) ?></p>
			<?php endif; ?>
			<?php if( isset( $course ) && $course ) : ?>
			<hr>
			<p class="course-instructors">
				<?php esc_html_e( 'Instructor', 'learnplus' ) ?> : <?php echo get_avatar( $course->post_author, 20, '', $author_name, array( 'class' => 'img-circle' ) ) ?> <a href="<?php echo esc_url( home_url( '/author/' . $user_login ) ) ?>"><?php echo $author_name ?></a>
			</p>
			<?php endif; ?>
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