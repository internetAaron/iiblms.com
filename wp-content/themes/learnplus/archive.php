<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package LearnPlus
 */

get_header();
?>

<div id="primary" class="content-area <?php learnplus_content_columns() ?>">
	<main id="main" class="site-main" role="main">

		<?php
		global $wp_query;
		$curauth = $wp_query->get_queried_object();

		if( is_author() && $curauth && isset( $curauth->roles[0] ) && $curauth->roles[0] == 'instructor' ) :
			$param = sprintf( ' author="%s" col="1"',  $curauth->ID );
			echo do_shortcode( '[lp_courses ' . $param . ']' );

		elseif( is_post_type_archive( 'sfwd-courses' ) ) :
			echo do_shortcode( '[lp_courses]' );
		else:
			?>
			<?php do_action( 'learnplus_page_title' ); ?>
			<?php if ( have_posts()  ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'parts/content', get_post_format() );
					?>

				<?php endwhile; ?>

				<div class="pagination">
					<?php
					learnplus_numeric_pagination();
					?>
				</div>

			<?php else : ?>

				<?php get_template_part( 'parts/content', 'none' ); ?>

			<?php endif; ?>
		<?php endif; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
