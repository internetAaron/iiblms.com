<?php
/**
 * Displays a lesson.
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
 *
 * $quizzes        : (array) Quizzes Array
 * $post            : (object) The lesson post object
 * $topics        : (array) Array of Topics in the current lesson
 * $all_quizzes_completed : (true/false) User has completed all quizzes on the lesson Or, there are no quizzes.
 * $lesson_progression_enabled    : (true/false)
 * $show_content    : (true/false) true if lesson progression is disabled or if previous lesson is completed.
 * $previous_lesson_completed    : (true/false) true if previous lesson is completed
 * $lesson_settings : Settings specific to the current lesson.
 *
 * @since   2.1.0
 *
 * @package LearnDash\Lesson
 */

$students    = $course_settings && isset( $course_settings['number_students'] ) ? intval( $course_settings['number_students'] ) : esc_html__( 'Unlimited', 'learnplus' );
$period      = $course_settings && isset( $course_settings['period'] ) ? esc_html( $course_settings['period'] ) : '';
$forum       = $course_settings && isset( $course_settings['forum'] ) ? $course_settings['forum'] : '';
$author_name = get_the_author_meta( 'display_name', $course->post_author );
$user_login = get_the_author_meta( 'user_login', $course->post_author );
?>
<div class="row">

	<div id="course-left-sidebar" class="col-md-4">
		<div class="course-image-widget">
			<?php echo get_the_post_thumbnail( $course_id, 'full', array( 'class' => 'img-responsive' ) ) ?>
		</div><!-- end image widget -->
		<div class="course-meta">
			<p>
				<?php esc_html_e( 'Lesson for', 'learnplus' ) ?> :
				<span><a href="<?php echo esc_url( get_permalink( $course_id ) ) ?>"><?php echo get_the_title( $course_id ) ?></a></span>
			</p>
			<hr>
			<p>
				<?php esc_html_e( 'Lesson Status', 'learnplus' ) ?> :
				<span><?php echo learndash_is_lesson_complete( $user_id, $post->ID ) ? esc_html__( 'Finished', 'learnplus' ) : esc_html__( 'Not Finished', 'learnplus' ) ; ?></span>
			</p>
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


			<?php
				/**
				 * Quizzes
				 */
				?>
				<?php if ( ! empty( $quizzes ) ) : ?>
					<hr>
					<div class="course-table">

						<table class="table">
							<thead>
							<tr>
								<th><p><?php esc_html_e( 'Quiz', 'learnplus' ) ?></p></th>
								<th><p><?php esc_html_e( 'Status', 'learnplus' ) ?></p></th>
							</tr>
							</thead>
							<tbody>
							<?php if ( ! empty( $topics ) ) : ?>
								<?php foreach ( $topics as $key => $topic ) : ?>
									<tr>
										<td>
											<p><a href="<?php echo esc_url( get_permalink( $topic->ID ) ); ?>" title="<?php echo esc_attr( $topic->post_title ); ?>"><?php echo $topic->post_title; ?></a></p>
										</td>
										<td>
											<?php if ( empty( $topic->completed ) ) : ?>
												<i class="fa fa-close"></i>
											<?php else : ?>
												<i class="fa fa-check"></i>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>

							<?php if ( ! empty( $quizzes ) ) : ?>

								<?php foreach ( $quizzes as $quiz ) : ?>
									<tr>
										<td>
											<p><a href="<?php echo esc_url( $quiz['permalink'] ); ?>"><?php echo $quiz['post']->post_title; ?></a></p>
										</td>
										<td>
											<?php if ( 'notcompleted' == $quiz['status'] ) : ?>
												<i class="fa fa-close"></i>
											<?php else : ?>
												<i class="fa fa-check"></i>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>

							<?php endif; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>

		</div><!-- end meta -->
	</div>

	<div id="course-content" class="col-md-8">
		<div class="course-description">
			
			<h3 class="course-title"><?php the_title() ?></h3>

			<?php if ( @$lesson_progression_enabled && ! @$previous_lesson_completed ) : ?>
				<span id="learndash_complete_prev_lesson"><?php esc_html_e( 'Please go back and complete the previous lesson.', 'learnplus' ); ?></span>
				<br />
				<?php add_filter( 'comments_array', 'learndash_remove_comments', 1, 2 ); ?>
			<?php endif; ?>

			<?php if ( $show_content ) : ?>

				<?php echo $content; ?>

				<?php
				/**
				 * Lesson Topics & Quizzes
				 */
				?>
				<?php if ( ! empty( $topics ) || ! empty( $quizzes ) ) : ?>
					<hr>
					<div class="course-table">
						<h2><?php esc_html_e( 'Lesson Topics & Quizzes', 'learnplus' ) ?></h2>

						<table class="table">
							<thead>
							<tr>
								<th><?php esc_html_e( 'Type', 'learnplus' ) ?></th>
								<th><?php esc_html_e( 'Title', 'learnplus' ) ?></th>
								<th><?php esc_html_e( 'Time', 'learnplus' ) ?></th>
								<th><?php esc_html_e( 'Status', 'learnplus' ) ?></th>
							</tr>
							</thead>
							<tbody>
							<?php if ( ! empty( $topics ) ) : ?>
								<?php foreach ( $topics as $key => $topic ) : ?>
									<tr>
										<td>
											<i class="fa fa-play-circle"></i>
										</td>
										<td>
											<a href="<?php echo esc_url( get_permalink( $topic->ID ) ); ?>" title="<?php echo esc_attr( $topic->post_title ); ?>"><?php echo $topic->post_title; ?></a>
										</td>
										<td>
											<?php
											$topic_settings = get_post_meta( $topic->ID, '_sfwd-topic', true );
											echo $topic_settings && isset( $topic_settings['sfwd-topic_forced_lesson_time'] ) ? $topic_settings['sfwd-topic_forced_lesson_time'] : '';
											?>
										</td>
										<td>
											<?php if ( empty( $topic->completed ) ) : ?>
												<i class="fa fa-close"></i>
											<?php else : ?>
												<i class="fa fa-check"></i>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>

							<?php if ( ! empty( $quizzes ) ) : ?>

								<?php foreach ( $quizzes as $quiz ) : ?>
									<tr>
										<td>
											<i class="fa fa-question-circle"></i>
										</td>
										<td>
											<a href="<?php echo esc_url( $quiz['permalink'] ); ?>"><?php echo $quiz['post']->post_title; ?></a>
										</td>
										<th>&nbsp;</th>
										<td>
											<?php if ( 'notcompleted' == $quiz['status'] ) : ?>
												<i class="fa fa-close"></i>
											<?php else : ?>
												<i class="fa fa-check"></i>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>

							<?php endif; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>

				<?php
				/**
				 * Display Lesson Assignments
				 */
				?>
				<?php if ( lesson_hasassignments( $post ) ) : ?>
					<?php $assignments = learndash_get_user_assignments( $post->ID, $user_id ); ?>

					<div id="learndash_uploaded_assignments">
						<h2><?php esc_html_e( 'Files you have uploaded', 'learnplus' ); ?></h2>
						<table>
							<?php if ( ! empty( $assignments ) ) : ?>
								<?php foreach ( $assignments as $assignment ) : ?>
									<tr>
										<td>
											<a href="<?php echo esc_attr( get_post_meta( $assignment->ID, 'file_link', true ) ); ?>" target="_blank"><?php echo esc_html__( 'Download', 'learnplus' ) . ' ' . get_post_meta( $assignment->ID, 'file_name', true ); ?></a>
										</td>
										<td>
											<a href="<?php echo esc_attr( get_permalink( $assignment->ID ) ); ?>"><?php esc_html_e( 'Comments', 'learnplus' ); ?></a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</table>
					</div>
				<?php endif; ?>


				<?php
				/**
				 * Display Mark Complete Button
				 */
				?>
				<?php if ( $all_quizzes_completed && $logged_in ) : ?>
					<br />
					<?php echo learndash_mark_complete( $post ); ?>
				<?php endif; ?>

			<?php endif; ?>

			<br />

			<div id="learndash_next_prev_link" class="row">
				<div class="col-md-6"><?php echo learndash_previous_post_link(); ?></div>
				<div class="col-md-6"><?php echo learndash_next_post_link(); ?></div>
			</div>
		</div>
	</div>
</div>