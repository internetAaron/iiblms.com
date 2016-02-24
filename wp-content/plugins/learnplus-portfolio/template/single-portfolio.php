<?php
/**
 * Template for displaying single portfolio
 *
 * @package LearnPlus Portfolio Management
 */

get_header(); ?>

<div id="primary" class="content-area">
	<div class="works-single" role="main">

		<?php while (have_posts()) : the_post(); ?>

			<article <?php post_class() ?>>

				<div class="col-md-12 col-sm-12">
					<div class="gallery-main-carousel work-slider flexslider">
						<?php
						$gallery = get_post_meta(get_the_ID(), 'images', false);

						if ($gallery) {
							foreach ($gallery as $image) {
								$image = wp_get_attachment_image_src($image, 'portfolio-project');
								if ($image) {
									printf('<div class="item"><img src="%s"  alt=""/></div>',
										esc_url($image[0])
									);
								}
							}
						} else {
							$gallery = get_post_thumbnail_id(get_the_ID());
							$image = wp_get_attachment_image_src($gallery, 'portfolio-project');
							if ($image) {
								printf('<div class="item"><img src="%s"  alt=""/></div>',
									esc_url($image[0])
								);
							}
						}
						?>
					</div>
				</div>
				<div class="col-md-8 col-sm-8">
					<header class="entry-header  clearfix">
						<h1 class="entry-title"><?php the_title() ?></h1>
					</header>
					<div class="entry-content">
						<?php
						echo get_the_content();

						if (comments_open() || get_comments_number()) :
							comments_template();
						endif;
						?>

					</div>
				</div>
				<div class="col-md-4 col-sm-4 ">
					<div class="work-single-desc">
						<div class="detail">
							<span class="desc"><?php esc_html_e( 'Date', 'learnplus-fortfolio' ) ?></span>
							<span class="value"><?php echo get_the_date( 'd M, Y' ) ?></span>
						</div>
						<?php
						$client = get_post_meta( get_the_ID(), '_project_client', true );
						if( $client ) :
							?>
							<div class="detail">
								<span class="desc"><?php esc_html_e( 'Client', 'learnplus-fortfolio' ) ?></span>
								<span class="value"><?php echo $client ?></span>
							</div>
						<?php endif; ?>
						<?php
						$author = get_post_meta( get_the_ID(), '_project_author', true );
						if( $author ) :
							?>
							<div class="detail">
								<span class="desc"><?php esc_html_e( 'Author', 'learnplus-fortfolio' ) ?></span>
								<span class="value"><?php echo $author ?></span>
							</div>
						<?php endif; ?>
						<?php
						$cats = wp_get_post_terms( get_the_ID(), 'portfolio_category' );
						$album = $cats ? $cats[0]->name : '';
						if( $album ) :
							?>
							<div class="detail">
								<span class="desc"><?php esc_html_e( 'Category', 'learnplus-fortfolio' ) ?></span>
								<span class="value"><?php echo $album ?></span>
							</div>
						<?php endif; ?>
						<?php
						$facebook = get_post_meta( get_the_ID(), '_project_facebook', true );
						$linkedin = get_post_meta( get_the_ID(), '_project_linkedin', true );
						$instagram = get_post_meta( get_the_ID(), '_project_instagram', true );
						$pinterest = get_post_meta( get_the_ID(), '_project_pinterest', true );
						$twitter = get_post_meta( get_the_ID(), '_project_twitter', true );
						$socials = '';
						if( $facebook ) {
							$socials .= sprintf( '<a href="%s">%s</a>', esc_url( $facebook ), '<i class="fa fa-facebook"></i>' );
						}
						if( $linkedin ) {
							$socials .= sprintf( '<a href="%s">%s</a>', esc_url( $linkedin ), '<i class="fa fa-linkedin"></i>' );
						}
						if( $instagram ) {
							$socials .= sprintf( '<a href="%s">%s</a>', esc_url( $facebook ), '<i class="fa fa-instagram"></i>' );
						}
						if( $pinterest ) {
							$socials .= sprintf( '<a href="%s">%s</a>', esc_url( $pinterest ), '<i class="fa fa-pinterest-p"></i>' );
						}
						if( $twitter ) {
							$socials .= sprintf( '<a href="%s">%s</a>', esc_url( $twitter ), '<i class="fa fa-twitter"></i>' );
						}

						if( $socials ) :
							?>
							<div class="detail">
								<span class="desc"><?php esc_html_e( 'Share On', 'learnplus-fortfolio' ) ?></span>
                                <span class="socials-work">
                                    <?php echo $socials; ?>
                                </span>
							</div>
						<?php endif; ?>
						<?php
						$client = get_post_meta( get_the_ID(), '_project_client', true );
						if( $client ) {
							printf( '<a href="%s" class="btn btn-fourth">%s</a>', esc_url( $client ), esc_html__( 'Live Demo', 'learnplus-fortfolio' ) );
						}
						?>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
		<?php
		global $post;

		$related = new WP_Query(
			array(
				'post_type'           => 'portfolio_project',
				'posts_per_page'      => apply_filters('other_project_per_page', 8),
				'ignore_sticky_posts' => 1,
				'no_found_rows'       => 1,
				'order'               => 'rand',
				'post__not_in'        => array($post->ID),
				'tax_query'           => array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'portfolio_category',
						'field'    => 'term_id',
						'terms'    => ta_get_related_terms('portfolio_category', $post->ID),
						'operator' => 'IN',
					),
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'term_id',
						'terms'    => ta_get_related_terms('post_tag', $post->ID),
						'operator' => 'IN',
					),
				),
			)
		);
		?>

		<?php if ($related->have_posts()) : ?>
			<div class="related-works portfolio-showcase col-md-12 col-sm-12">
				<div class="other-works">
					<div class="main-heading">
						<h3><?php echo apply_filters('other_works_title', esc_html__('Related Works', 'learnplus-fortfolio')); ?></h3>

						<div class="heading-line"></div>
						<div class="heading-small-line"></div>
					</div>
				</div>

				<div class="row">
					<div id="related-works" class="owl-custom main-project">
						<?php
						$works = '';
						$tag = current_theme_supports('html5') ? 'article' : 'div';
						?>

						<?php while ($related->have_posts()) : $related->the_post(); ?>
							<?php

							$cats = wp_get_post_terms(get_the_ID(), 'portfolio_category');
							$cat_name = $cats ? $cats[0]->name : '';

							$works .= '<' . $tag . ' class="' . implode(' ', get_post_class()) . '" >';
							$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), "full");
							if ($image_src) $image_src = $image_src[0];
							$works .= '<div class="portfolio-detail item">';

							$portfolio_type = get_post_meta(get_the_ID(), '_project_type', true);
							$data_type = '';
							$link = '';
							$item_class = 'venobox vbox-item';

							if ($portfolio_type == 'youtube') {
								$link = get_post_meta(get_the_ID(), '_project_youtube_url', true);
								$dalearnplus_type = 'data-type=youtube';
							} elseif ($portfolio_type == 'vimeo') {
								$link = get_post_meta(get_the_ID(), '_project_vimeo_url', true);
								$dalearnplus_type = 'data-type=vimeo';
							} elseif ($portfolio_type == 'image') {
								$link = $image_src;
							} else {
								$dalearnplus_type = 'data-gall=gallery-' . get_the_ID();
								$gallery = get_post_meta(get_the_ID(), 'images', false);
								if( $gallery && isset($gallery[0]) ) {
									$image = wp_get_attachment_image_src($gallery[0], 'full');

									if( $image && isset($image[0]) ) {
										$link = $image[0];
									} else {
										$link = $image_src;
									}
								}
							}

							$works .= get_the_post_thumbnail(get_the_ID(), 'portfolio-thumbnail-normal');
							$works .= '<div class="item-overlay"><div class="item-overlay-actions">';

							$works .= sprintf('
								<a class="%s" %s data-title="<span>%s </span> / %s" href="%s"><i class="fa fa-search"></i></a>
								<a href="%s" class="link-detail"><i class="fa fa-link"></i></a>',
								esc_attr($item_class),
								esc_attr($dalearnplus_type),
								esc_attr($cat_name),
								get_the_title(),
								esc_url($link),
								esc_url(get_the_permalink())
							);
							$works .= '</div></div></div>';

							$gallery = get_post_meta(get_the_ID(), 'images', false);
							if ($gallery && $portfolio_type == 'gallery') {

								$i = 0;
								foreach ($gallery as $image) {
									$i ++;
									if( $i == 1 ) {
										continue;
									}
									$image = wp_get_attachment_image_src($image, 'full');
									if ($image) {
										$works .= sprintf('<a class="venobox vbox-item hidden" %s data-gall="gallery-%s" data-title="<span>%s </span> / %s" href="%s"></a>',
											esc_attr($dalearnplus_type),
											esc_attr(get_the_ID()),
											esc_attr($cat_name),
											esc_attr(get_the_title()),
											esc_url($image[0])
										);
									}
								}
							}

							$works .= "</$tag>";

							?>

						<?php endwhile; ?>

						<?php echo $works; ?>

					</div>
				</div>
				<!-- end owl-featured -->
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>
	<!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>
