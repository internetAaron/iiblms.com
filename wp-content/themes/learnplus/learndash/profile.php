<?php
/**
 * Displays a user's profile.
 *
 * Available Variables:
 *
 * $user_id 		: Current User ID
 * $current_user 	: (object) Currently logged in user object
 * $user_courses 	: Array of course ID's of the current user
 * $quiz_attempts 	: Array of quiz attempts of the current user
 *
 * @since 2.1.0
 *
 * @package LearnDash\User
 */
?>

<div  class="row">
	<div id="course-left-sidebar" class="col-md-3">
		<div class="course-image-widget">
			<?php echo get_avatar( $user_id, 262, '', $current_user->display_name, array( 'class' => 'img-responsive' ) ) ?>
		</div><!-- end image widget -->
		<div class="course-meta">
			<p><?php echo $current_user->display_name ?></p>
			<hr>
			<p><?php esc_html_e( 'My Profile', 'learnplus' ) ?><small><a href="<?php echo get_edit_user_link() ?>"><?php esc_html_e( 'Edit', 'learnplus' ) ?></a></small></p>
			<hr>
			<p><?php esc_html_e( 'Email', 'learnplus' ) ?><small><?php echo $current_user->user_email; ?></small></p>
			<hr>
			<p><?php esc_html_e( 'My Courses', 'learnplus' ) ?><small class="label label-primary"><?php echo count( $user_courses ) ?></small></p>
			<hr>
			<p><?php esc_html_e( 'Completed Quiz', 'learnplus' ) ?><small class="label label-primary"><?php echo count( $quiz_attempts, 1 ) ?></small></p>
		</div><!-- end meta -->
	</div>

	<div id="course-content" class="col-md-9">
		<div class="course-description" id="learndash_profile">
			<h3 class="course-title"><?php esc_html_e( 'Biography', 'learnplus' ) ?></h3>
			<p><?php echo $current_user->description ?></p>

			<div class="learndash_profile_heading no_radius clear_both">
				<span><?php _e( 'Registered Courses', 'learnplus' ); ?></span>
				<span class="ld_profile_status"><?php _e( 'Status', 'learnplus' ); ?></span>
			</div>

			<div id="course_list">

				<?php if ( ! empty( $user_courses ) ) : ?>

					<?php foreach ( $user_courses as $course_id ) : ?>
						<?php
						$course = get_post( $course_id);

						$course_link = get_permalink( $course_id);

						$progress = learndash_course_progress( array(
							'user_id'   => $user_id,
							'course_id' => $course_id,
							'array'     => true
						) );

						$status = ( $progress['percentage'] == 100 ) ? 'completed' : 'notcompleted';
						?>
						<div id='course-<?php echo esc_attr( $user_id ) . '-' . esc_attr( $course->ID ); ?>'>
							<div class="list_arrow collapse flippable"><a href='<?php echo esc_attr( $course_link ); ?>'><?php esc_html_e( 'View Detail', 'learnplus' ); ?></a></div>


							<?php
							/**
							 * @todo Remove h4 container.
							 */
							?>
							<h4>
								<a class='<?php echo esc_attr( $status ); ?>'  onClick='return flip_expand_collapse("#course-<?php echo esc_attr( $user_id ); ?>", <?php echo esc_attr( $course->ID ); ?>);' href='<?php echo esc_attr( $course_link ); ?>'><?php echo $course->post_title; ?></a>

								<div class="flip" style="display:none;">

									<div class="learndash_profile_heading course_overview_heading"><?php _e( 'Course Progress Overview', 'learnplus' ); ?></div>

									<div>
										<dd class="course_progress" title='<?php echo sprintf( __( '%s out of %s steps completed', 'learnplus' ), $progress['completed'], $progress['total'] ); ?>'>
											<div class="course_progress_blue" style='width: <?php echo esc_attr( $progress['percentage'] ); ?>%;'>
										</dd>

										<div class="right">
											<?php echo sprintf( __( '%s%% Complete', 'learnplus' ), $progress['percentage'] ); ?>
										</div>
									</div>

									<?php if ( ! empty( $quiz_attempts[ $course_id ] ) ) : ?>
										<div class="learndash_profile_quizzes clear_both">

											<div class="learndash_profile_quiz_heading">
												<div class="quiz_title"><?php _e( 'Quizzes', 'learnplus' ); ?></div>
												<div class="certificate"><?php _e( 'Certificate', 'learnplus' ); ?></div>
												<div class="scores"><?php _e( 'Score', 'learnplus' ); ?></div>
												<div class="quiz_date"><?php _e( 'Date', 'learnplus' ); ?></div>
											</div>

											<?php foreach ( $quiz_attempts[ $course_id ] as $k => $quiz_attempt ) : ?>
												<?php
												$certificateLink = @$quiz_attempt['certificate']['certificateLink'];

												$status = empty( $quiz_attempt['pass'] ) ? 'failed' : 'passed';

												$quiz_title = ! empty( $quiz_attempt['post']->post_title) ? $quiz_attempt['post']->post_title : @$quiz_attempt['quiz_title'];

												$quiz_link = ! empty( $quiz_attempt['post']->ID) ? get_permalink( $quiz_attempt['post']->ID ) : '#';
												?>
												<?php if ( ! empty( $quiz_title ) ) : ?>
													<div class='<?php echo esc_attr( $status ); ?>'>

														<div class="quiz_title">
															<span class='<?php echo esc_attr( $status ); ?>_icon'></span>
															<a href='<?php echo esc_attr( $quiz_link ); ?>'><?php echo esc_attr( $quiz_title ); ?></a>
														</div>

														<div class="certificate">
															<?php if ( ! empty( $certificateLink ) ) : ?>
																<a href='<?php echo esc_attr( $certificateLink ); ?>&time=<?php echo esc_attr( $quiz_attempt['time'] ) ?>' target="_blank">
																	<div class="certificate_icon"></div></a>
															<?php else : ?>
																<?php echo '-';	?>
															<?php endif; ?>
														</div>

														<div class="scores"><?php echo round( $quiz_attempt['percentage'], 2 ); ?>%</div>

														<div class="quiz_date"><?php echo date_i18n( 'd-M-Y', $quiz_attempt['time'] ); ?></div>

													</div>
												<?php endif; ?>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							</h4>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

			</div>
		</div><!-- end desc -->
	</div>
</div>
