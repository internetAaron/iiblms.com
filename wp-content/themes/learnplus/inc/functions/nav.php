<?php
/**
 * Custom functions for nav menu
 *
 * @package LearnPlus
 */


/**
 * Display numeric pagination
 *
 * @since 1.0
 */
function learnplus_numeric_pagination() {
	global $wp_query;

	if( $wp_query->max_num_pages < 2 ) {
        return;
	}

	?>
	<nav class="navigation paging-navigation numeric-navigation" role="navigation">
		<?php
		$big = 999999999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => '&laquo;',
			'next_text' => '&raquo;',
			'type'      => 'plain',
		);

		echo paginate_links( $args );
		?>
	</nav>
<?php
}

/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since 1.0
 */
function learnplus_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'learnplus' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'learnplus' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
<?php
}


/**
 * Display navigation to next/previous post when applicable.
 *
 * @since 1.0
 */
function learnplus_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<div class="nav-links">
			<?php
			previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'learnplus' ) );
			next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'learnplus' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
<?php
}

