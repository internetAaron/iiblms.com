<?php
/**
 * Displays a course
 *
 * Available Variables:
 * $course_id        : (int) ID of the course
 * $course        : (object) Post object of the course
 * $course_settings : (array) Settings specific to current course
 *
 * $courses_options : Options/Settings as configured on Course Options page
 * $lessons_options : Options/Settings as configured on Lessons Options page
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 *
 * $user_id        : Current User ID
 * $logged_in        : User is logged in
 * $current_user    : (object) Currently logged in user object
 *
 * $course_status    : Course Status
 * $has_access    : User has access to course or is enrolled.
 * $materials        : Course Materials
 * $has_course_content        : Course has course content
 * $lessons        : Lessons Array
 * $quizzes        : Quizzes Array
 * $lesson_progression_enabled    : (true/false)
 * $has_topics        : (true/false)
 * $lesson_topics    : (array) lessons topics
 *
 * @since   2.1.0
 *
 * @package LearnDash\Course
 */

global $post;

$students    = $course_settings && isset( $course_settings['number_students'] ) ? intval( $course_settings['number_students'] ) : esc_html__( 'Unlimited', 'learnplus' );
$period      = $course_settings && isset( $course_settings['period'] ) ? esc_html( $course_settings['period'] ) : '';
$forum       = $course_settings && isset( $course_settings['forum'] ) ? $course_settings['forum'] : '';
$author_name = get_the_author_meta( 'display_name', $post->post_author );
$user_login = get_the_author_meta( 'user_login', $post->post_author );
?>

<div class="row">
	<div id="course-left-sidebar" class="col-md-4">
		<div class="course-image-widget">
			<?php the_post_thumbnail( 'learnplus-course-thumb', array( 'class' => 'img-responsive' ) ) ?>
		</div><!-- end image widget -->
		<div class="course-meta">
			<p class="course-category">
				<?php esc_html_e( 'Category', 'learnplus' ) ?> : <?php the_category( ', ' ) ?>
			</p>
			<?php if ( has_tag() ) : ?>
				<hr>
				<p class="course-tags">
					<?php esc_html_e( 'Tags', 'learnplus' ) ?>: <?php the_tags( '', ', ', '' ) ?>
				</p>
			<?php endif ?>
			<hr>
			<p><?php esc_html_e( 'Course Status', 'learnplus' ) ?>: <span><?php echo $course_status ?></span></p>
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
				<?php esc_html_e( 'Instructor', 'learnplus' ) ?> : <?php echo get_avatar( $post->post_author, 20, '', $author_name, array( 'class' => 'img-circle' ) ) ?> <a href="<?php echo esc_url( home_url( '/author/' . $user_login ) ) ?>"><?php echo $author_name ?></a>
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
		<div class="course-button">
			<?php
			if ( ! $has_access ) :
				echo learndash_payment_buttons( $post );
			elseif ( $logged_in && ! empty( $course_certficate_link ) ) :
				printf( '<a href="%s" target="_blank" class="btn btn-primary btn-block">%s</a>', esc_url( $course_certficate_link ), esc_html__( 'PRINT YOUR CERTIFICATE', 'learnplus' ) );
			endif;
			?>
		</div>
	</div><!-- #course-left-sidebar -->

	<div id="course-content" class="col-md-8">
		<div class="course-description">
			<h3 class="course-title"><?php the_title() ?></h3>

			<?php echo $content ?>

			<?php if ( isset( $materials ) ) : ?>
				<div id="learndash_course_materials">
					<h4><?php esc_html_e( 'Course Materials', 'learnplus' ); ?></h4>

					<?php echo wpautop( $materials ); ?>
				</div>
			<?php endif; ?>
		</div><!-- end desc -->

		<?php if ( $has_course_content ) : ?>
			<div class="course-table">
				<?php if ( ! empty( $lessons ) || ! empty( $quizzes ) ) : ?>
					<h4><?php esc_html_e( 'Course Lessons', 'learnplus' ) ?></h4>
					<table class="table">
						<thead>
						<tr>
							<th><?php esc_html_e( 'Type', 'learnplus' ) ?></th>
							<th><?php esc_html_e( 'Lesson Title', 'learnplus' ) ?></th>
							<th><?php esc_html_e( 'Time', 'learnplus' ) ?></th>
							<th><?php esc_html_e( 'Status', 'learnplus' ) ?></th>
						</tr>
						</thead>
						<tbody>
						<?php if ( ! empty( $lessons ) ) : ?>
							<?php foreach ( $lessons as $lesson ) : ?>
								<?php $lesson_settings = get_post_meta( $lesson['post']->ID, '_sfwd-lessons', true ) ?>
								<tr>
									<td><i class="fa fa-play-circle"></i></td>
									<td>
										<a href="<?php echo esc_url( $lesson['permalink'] ); ?>"><?php echo $lesson['post']->post_title; ?></a>
										<?php
										/**
										 * Not available message for drip feeding lessons
										 */
										?>
										<?php if ( ! empty( $lesson['lesson_access_from'] ) ) : ?>
											<small class="notavailable_message">
												<?php echo sprintf( esc_html__( 'Available on: %s ', 'learnplus' ), date_i18n( 'd-M-Y', $lesson['lesson_access_from'] ) ); ?>
											</small>
										<?php endif; ?>
									</td>
									<td><?php echo $lesson_settings['sfwd-lessons_forced_lesson_time'] ?></td>
									<td>
										<?php if ( 'notcompleted' == $lesson['status'] ) : ?>
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
									<td><i class="fa fa-question-circle"></i></td>
									<td>
										<a href="<?php echo esc_url( $quiz['permalink'] ); ?>"><?php echo $quiz['post']->post_title; ?></a>
									</td>
									<td>&nbsp;</td>
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
				<?php endif; ?>
			</div><!-- end course-table -->
		<?php endif; ?>

		<hr class="invis">

	</div>
</div>