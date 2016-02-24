<?php
/**
 * Hooks for comments
 *
 * @package LearnPlus
 */

/**
 * Validate the comment ratings.
 *
 * @param  array $comment_data
 *
 * @return array
 */
function learnplus_check_comment_rating( $comment_data ) {
	// If posting a comment (not trackback etc) and not logged in
	if (
		! is_admin()
		&& 'sfwd-courses' === get_post_type( $_POST['comment_post_ID'] )
		&& empty( $_POST['rating'] )
		&& '' === $comment_data['comment_type']
	) {
		wp_die( esc_html__( 'Please rate the product.', 'learnplus' ) );
		exit;
	}

	return $comment_data;
}

add_filter( 'preprocess_comment', 'learnplus_check_comment_rating', 0 );

/**
 * Rating field for comments.
 *
 * @param int $comment_id
 */
function learnplus_add_comment_rating( $comment_id ) {
	if ( isset( $_POST['rating'] ) && 'sfwd-courses' === get_post_type( $_POST['comment_post_ID'] ) ) {
		if ( ! $_POST['rating'] || $_POST['rating'] > 5 || $_POST['rating'] < 0 ) {
			return;
		}
		add_comment_meta( $comment_id, 'rating', (int) esc_attr( $_POST['rating'] ), true );
	}
}

add_action( 'comment_post', 'learnplus_add_comment_rating', 1 );


/**
 * Custom fields comment form
 *
 * @since  1.0
 *
 * @return  array  $fields
 */
function learnplus_comment_form_fields() {
	global $commenter, $aria_req;

	$fields = array();

	$fields['author'] = '<p class="comment-form-author">' .
		'<input id ="author" placeholder="' . esc_html__( 'Name', 'learnplus' ) . ' " class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
		'" size    ="30"' . $aria_req . ' /></p>';

	$fields['email'] = '<p class="comment-form-email">' .
		'<input id ="email" placeholder="' . esc_html__( 'Email', 'learnplus' ) . '" class="form-control" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) .
		'" size    ="30"' . $aria_req . ' /></p>';

	if ( ! is_singular( 'sfwd-courses' ) ) {
		$fields['url'] = '<p class="comment-form-url">' .
			'<input id ="url" placeholder="' . esc_html__( 'Website', 'learnplus' ) . '" class="form-control" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size    ="30" /></p>';
	}

	if ( is_singular( 'sfwd-courses' ) ) {
		$fields['rating'] = '<p class="comment-form-rating">' .
			'<span class="stars"><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span>' .
			'<select name="rating" id="rating" style="display: none;">' .
			'<option value="">' . esc_html__( 'Rate...', 'learnplus' ) . '</option>' .
			'<option value="5">' . esc_html__( 'Perfect', 'learnplus' ) . '</option>' .
			'<option value="4">' . esc_html__( 'Good', 'learnplus' ) . '</option>' .
			'<option value="3">' . esc_html__( 'Average', 'learnplus' ) . '</option>' .
			'<option value="2">' . esc_html__( 'Not that bad', 'learnplus' ) . '</option>' .
			'<option value="1">' . esc_html__( 'Very poor', 'learnplus' ) . '</option>' .
			'</select></p>';
	}

	return $fields;
}

add_filter( 'comment_form_default_fields', 'learnplus_comment_form_fields' );

/**
 * Template Comment
 *
 * @since  1.0
 *
 * @param  array $comment
 * @param  array $args
 * @param  int   $depth
 *
 * @return mixed
 */
function learnplus_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );

	if ( 'div' == $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li ';
		$add_below = 'div-comment';
	}
	?>

	<<?php echo $tag ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<article id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>

	<div class="comment-author vcard">
		<?php
		if ( $args['avatar_size'] != 0 ) {
			echo get_avatar( $comment, $args['avatar_size'] );
		}
		?>
	</div>
	<div class="comment-meta commentmetadata">
		<?php printf( '<cite class="author-name">%s</cite>', get_comment_author_link() ); ?>

		<a class="author-posted" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php printf( '%1$s ', get_comment_date( 'd M Y' ) ); ?>
		</a>

		<?php
		comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => esc_html__( 'Reply', 'learnplus' ) ) ) );
		edit_comment_link( esc_html__( 'Edit', 'learnplus' ), '  ', '' );
		?>
		<div class="clear"></div>

		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'learnplus' ); ?></em>
		<?php endif; ?>

		<div class="comment-content">
			<?php comment_text(); ?>
		</div>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
		</article>
	<?php endif; ?>
	<?php
}

/**
 * Template Comment for courses
 *
 * @since  1.0
 *
 * @param  array $comment
 * @param  array $args
 * @param  int   $depth
 *
 * @return mixed
 */
function learnplus_course_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$rate               = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
	?>

	<div <?php comment_class( empty( $args['has_children'] ) ? 'media' : 'media parent' ) ?> id="comment-<?php comment_ID() ?>">
		<div class="media-left">
			<?php echo get_avatar( $comment, 65 ); ?>
		</div>

		<div class="media-body">
			<h4 class="media-heading"><?php echo get_comment_author_link() ?></h4>

			<div class="rating">
				<?php
				for ( $i = 1; $i <= 5; $i ++ ) {
					if ( $i <= $rate ) {
						echo '<i class="fa fa-star"></i>';
					} else {
						echo '<i class="fa fa-star-o"></i>';
					}
				}
				?>
			</div><!-- end rating -->
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'learnplus' ); ?></em>
			<?php endif; ?>
			<p><?php comment_text(); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Get the average rating of product.
 *
 * @since  1.0.0
 *
 * @param int $id the post ID
 *
 * @return float
 */
function learnplus_get_average_rating( $id ) {
	$comment_array = get_approved_comments( $id );

	if ( $comment_array ) {
		$count = 0;
		$total = 0;

		foreach ( $comment_array as $comment ) {
			$rate = get_comment_meta( $comment->comment_ID, 'rating', true );
			if ( isset( $rate ) && $rate !== '' ) {
				$count ++;
				$total += $rate;
			}
		}

		if ( $count == 0 ) {
			return false;
		} else {
			return round( $total / $count, 2 );
		}
	} else {
		return 0;
	}
}