<?php
/**
 * This file contains the code that displays the course list.
 *
 * @since 2.1.0
 *
 * @package LearnDash\Course
 */

global $post;
?>

<div class="row course-list">
	<div class="col-md-12 col-md-12">
		<div class="card clearfix">
			<div class="shop-list-desc">
				<?php the_title( '<h4 class="pull-left"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h4>' ); ?>
			
				<!-- <p><?php the_excerpt() ?></p> -->
				<a href="<?php the_permalink() ?>" class="btn btn-default btn-sm pull-right"><?php esc_html_e( 'Review Course', 'learnplus' ) ?></a>
			</div>
		</div>
	</div>
</div>

