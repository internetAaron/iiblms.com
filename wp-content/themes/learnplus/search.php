<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package LearnPlus
 */

get_header();
?>

	<section id="primary" class="content-areas <?php learnplus_content_columns() ?>">
		<main id="main" class="site-main" role="main">
		<?php

		if( is_post_type_archive( 'sfwd-courses') ) :
			$keyword = $_GET['s'];
			echo do_shortcode( '[lp_courses keyword="' . $keyword . '"]' );
		else :
		?>
			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<?php do_action( 'learnplus_page_title' ); ?>
				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'parts/content', 'search' );
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
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
