<?php
/**
 * Template Name: Boxed Content
 *
 * The template file for displaying one page.
 *
 * @package LearnPlus
 */

get_header(); ?>

<?php
if ( have_posts() ) :
	?>
	<div id="primary" class="content-area <?php learnplus_content_columns() ?>">
		<main id="main" class="site-main" role="main">
			<?php do_action( 'learnplus_page_title' ); ?>
			<div class="blog-wrapper">
				<?php
				while ( have_posts() ) : the_post();
					the_content();
				endwhile;
				?>
			</div>
		</main>
	</div>
<?php
endif;
?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
