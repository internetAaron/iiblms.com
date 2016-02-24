<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package LearnPlus
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php learnplus_page_title( true ); ?>
			<div class="blog-wrapper">
				<section class="blog-desc notfound text-center">
					<header class="page-header">
						<h3><?php echo esc_html__( '404', 'learnplus' ); ?></h3>
					</header><!-- .page-header -->

					<div class="page-content">
						<p class="lead">
							<?php echo esc_html__("The page you are looking for no longer exists. Perhaps you can return", 'learnplus'); ?><br>
							<?php echo esc_html__("back to the site's homepage and see if you can find what you are looking for. Or, you can try finding", 'learnplus'); ?><br>
							<?php echo esc_html__("it with the information below.", 'learnplus'); ?>
						</p>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-default"><?php echo esc_html__( 'Back to homepage', 'learnplus'); ?></a>
						<hr class="invis">
					</div><!-- .page-content -->
				</section><!-- .error-404 -->
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
