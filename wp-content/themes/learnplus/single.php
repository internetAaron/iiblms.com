<?php
/**
 * The Template for displaying all single posts.
 *
 * @package LearnPlus
 */

get_header(); ?>

<div id="primary" class="content-area <?php learnplus_content_columns() ?>">
	<main id="main" class="site-main" role="main">
		<?php do_action( 'learnplus_page_title' ); ?>

		<?php
		while ( have_posts() ) : the_post();
			if ( is_singular( 'sfwd-courses' ) ) {
				get_template_part( 'parts/content-single', 'course' );
			} elseif ( is_singular( 'sfwd-quiz' ) ) {
				get_template_part( 'parts/content-single', 'quiz' );
			} elseif ( is_singular( 'sfwd-lessons' ) ) {
				get_template_part( 'parts/content-single', 'lesson' );
			} elseif ( is_singular( 'sfwd-topic' ) ) {
				get_template_part( 'parts/content-single', 'topic' );
			} else {
				get_template_part( 'parts/content', 'single' );
			}

			// If comments are open or we have at least one comment, load up the comment template
			if ( ( comments_open() || get_comments_number() ) && ! is_singular( 'sfwd-courses' ) && ! is_singular( 'sfwd-lessons' ) && ! is_singular( 'sfwd-quiz' ) && ! is_singular( 'sfwd-topic' ) ) {
				comments_template();
			}
		endwhile; // end of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
