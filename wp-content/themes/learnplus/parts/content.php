<?php
/**
 * The template used for displaying entry content on archive pages
 *
 * @package LearnPlus
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-wrapper' ); ?>>
	<header class="entry-header  clearfix">
		<div class="blog-title">
			<?php
			$cats = get_the_category( get_the_ID() );
			if( $cats ) {
				printf( '<a class="category_title" href="%s" title="">%s</a>',
					esc_url( get_category_link( $cats[0]->term_id ) ),
					esc_attr( get_cat_name( $cats[0]->term_id ) )
				);
			}
			?>
			<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title() ?></a></h2>

			<div class="post-meta">
				<span>
					<i class="fa fa-user"></i>
					<?php
					printf( '<a href="%s">%s</a>',
						esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
						get_the_author()
					);
					?>
				</span>
				<?php
				$tags = get_the_tags();
				$tags_ot = array();
				if(  $tags) {
					foreach( $tags as $tag ) {
						$tags_ot[] = sprintf( '<a href="%s">%s</a>',
							esc_attr( get_tag_link($tag->term_id) ),
							$tag->name
						);
						break;
					}

					printf( '<span><i class="fa fa-tag"></i> %s</span>', implode( ',', $tags_ot ) );
				}
				?>

				<span><i class="fa fa-comments"></i> <span><?php comments_popup_link( esc_html__( 'No Comment', 'learnplus' ), esc_html__( '1 Comment', 'learnplus' ), esc_html__( '% Comments', 'learnplus' ), 'comments-link', __( 'Comments Off', 'learnplus' ) ); ?></span></span>
			</div>

		</div>
		<div class="blog-image">
			<?php learnplus_entry_thumbnail(); ?>
		</div>

	</header>

	<div class="entry-summary blog-desc">
		<div class="post-date">
			<span class="day"><?php echo get_the_date( 'd' ) ?></span>
			<span class="month"><?php echo get_the_date( 'M' ) ?></span>
		</div>
		<?php the_excerpt(); ?>
	</div>

	<footer class="entry-footer">
		<a class="readmore" href="<?php the_permalink() ?>"><?php esc_html_e( 'Read more', 'learnplus' ) ?></a>
	</footer>
</article>
