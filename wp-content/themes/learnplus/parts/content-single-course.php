<?php
/**
 * The template used for displaying course content in single.php
 *
 * @package LearnPlus
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_content() ?>
</article><!-- #post-## -->

<?php
global $post;

$related = new WP_Query(
	array(
		'post_type'           => 'sfwd-courses',
		'posts_per_page'      => 8,
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1,
		'order'               => 'rand',
		'post__not_in'        => array( $post->ID ),
		'tax_query'           => array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => learnplus_get_related_terms( 'category', $post->ID ),
				'operator' => 'IN',
			),
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'term_id',
				'terms'    => learnplus_get_related_terms( 'post_tag', $post->ID ),
				'operator' => 'IN',
			),
		),
	)
);
?>


<?php if ( $related->have_posts() ) : ?>
	<div class="related-courses white">
		<div class="other-courses">
			<?php
			$related_image = learnplus_theme_option( 'course_related_image' );
			if( $related_image ) {
				printf( '<img src="%s" alt="other">', esc_url( $related_image ) );
			} else {
				printf( '<h2 class="related-title">%s</h2>', apply_filters( 'other_course_title', esc_html__( 'Other Course', 'learnplus' ) ) );
			}
			?>
		</div>

		<hr class="invis clear">

		<div id="owl-featured" class="owl-custom">

			<?php while ( $related->have_posts() ) : $related->the_post(); ?>
				<?php
				$course_options = get_post_meta( get_the_ID(), '_sfwd-courses', true );
				$students       = isset( $course_options['sfwd-courses_number_students'] ) ? intval( $course_options['sfwd-courses_number_students'] ) : false;
				$rate           = learnplus_get_average_rating( get_the_ID() );
				?>

				<div class="owl-featured">
					<div class="shop-item-list entry">
						<div class="">
							<?php the_post_thumbnail( 'learnplus-course-thumb' ) ?>

							<div class="magnifier"></div>
						</div>
						<div class="shop-item-title clearfix">
							<h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>

							<div class="shopmeta">
								<span class="pull-left"><?php if ( $students ) printf( esc_html__( '%s Students', 'learnplus' ), $students ) ?></span>

								<?php echo LearnPlus_LearnDash::get_rating_html( get_the_ID(), 'pull-right' ) ?>
							</div><!-- end shop-meta -->
						</div><!-- end shop-item-title -->
					</div><!-- end relative -->
				</div><!-- end col -->

			<?php endwhile; ?>

		</div><!-- end owl-featured -->
	</div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>