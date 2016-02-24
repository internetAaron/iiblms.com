<?php
/**
 * The template for displaying Comments.
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

<div id="comments" class="comments-area blog-wrapper comment-wrapper">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<span> <?php printf( esc_html__( 'What others say about this post? (%s Comments)', 'learnplus'), get_comments_number() );  ?> </span>
		</h3>

		<ol class="comment-list">
			<?php
				wp_list_comments( 'type=comment&avatar_size=65&callback=learnplus_comment' );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation numeric-navigation" role="navigation">
			<?php echo paginate_comments_links(  array('prev_text' => '&laquo;', 'next_text' => '&raquo;') ); ?>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'learnplus' ); ?></p>
	<?php endif; ?>

	<?php
	$comment_field = '<p class="comment-form-comment"><textarea id="comment" placeholder="' . esc_html__( 'Your Comment', 'learnplus' ) . '"  class="form-control" name="comment" cols="45" rows="7" aria-required="true" required="required"></textarea></p>';
	?>
	<div class="clearfix"></div>
	<?php comment_form(
		array(
			'title_reply'   => esc_html__( 'Leave a Comment', 'learnplus' ),
			'comment_field' => $comment_field,
			'label_submit'  => esc_html__( 'Send Comment', 'learnplus' ),
		)
	)?>

</div><!-- #comments -->
