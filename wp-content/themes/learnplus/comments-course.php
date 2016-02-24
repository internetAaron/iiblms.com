<?php
/**
 * The template for displaying Comments of Courses
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package LearnPlus
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<p>
	<a class="btn btn-default btn-block" role="button" data-toggle="collapse" href="#courses-comments" aria-expanded="false" aria-controls="courses-comments">
		<?php printf( esc_html__( 'What our customers said? (%d Feedbacks)', 'learnplus' ), get_comments_number() ); ?>
	</a>
</p>

<div class="collapse" id="courses-comments">
	<?php if ( have_comments() ) : ?>
		<div class="well">
			<?php
			wp_list_comments( 'type=comment&avatar_size=65&callback=learnplus_course_comment' );
			?>
		</div><!-- end well -->
	<?php endif; ?>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation numeric-navigation" role="navigation">
			<?php echo paginate_comments_links( array( 'prev_text' => '&laquo;', 'next_text' => '&raquo;' ) ); ?>
		</nav><!-- #comment-nav-below -->
	<?php endif; // check for comment navigation ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'learnplus' ); ?></p>
	<?php endif; ?>

	<?php
	$comment_field = '<p class="comment-form-comment"><textarea id="comment" placeholder="' . esc_html__( 'Your Comment', 'learnplus' ) . '"  class="form-control" name="comment" cols="45" rows="7" aria-required="true" required="required"></textarea></p>';
	?>
	<div class="clearfix"></div>
	<?php comment_form(
		array(
			'title_reply'   => esc_html__( 'Leave Your Comment', 'learnplus' ),
			'comment_field' => $comment_field,
			'label_submit'  => esc_html__( 'Send Comment', 'learnplus' ),
			'class_submit'  => 'btn btn-primary btn-block',
		)
	) ?>
</div><!-- end collapse -->