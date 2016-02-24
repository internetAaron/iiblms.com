<?php
/**
 * Custom functions for images, audio, videos.
 *
 * @package LearnPlus
 */


/**
 * Display or get post image
 *
 * @since  1.0
 *
 * @param  array $args
 *
 * @return void|string
 */
function learnplus_get_image( $args = array() ) {
	$default = array(
		'post_id'   => 0,
		'size'      => 'thumbnail',
		'format'    => 'html', // html or src
		'attr'      => '',
		'thumbnail' => true,
		'scan'      => true,
		'echo'      => true,
		'default'   => '',
		'meta_key'  => '',
	);

	$args = wp_parse_args( $args, $default );

	if ( !$args['post_id'] )
		$args['post_id'] = get_the_ID();

	// Get image from cache
	$key = md5( serialize( $args ) );
	$image_cache = wp_cache_get( $args['post_id'], __FUNCTION__ );

	if ( !is_array( $image_cache ) )
		$image_cache = array();

	if ( empty( $image_cache[$key] ) )
	{
		// Get post thumbnail
		if ( has_post_thumbnail( $args['post_id'] ) && $args['thumbnail'] )
		{
			$id = get_post_thumbnail_id( $args['post_id'] );
			$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
			list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
		}

		// Get the first image in the custom field
		if ( !isset( $html, $src ) && $args['meta_key'] )
		{
			$id = get_post_meta( $args['post_id'], $args['meta_key'], true );

			// Check if this post has attached images
			if ( $id )
			{
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}
		}

		// Get the first attached image
		if ( !isset( $html, $src ) )
		{
			$image_ids = array_keys( get_children( array(
				'post_parent'    => $args['post_id'],
				'post_type'	     => 'attachment',
				'post_mime_type' => 'image',
				'orderby'        => 'menu_order',
				'order'	         => 'ASC',
			) ) );

			// Check if this post has attached images
			if ( !empty( $image_ids ) )
			{
				$id = $image_ids[0];
				$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
				list( $src ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
			}
		}

		// Get the first image in the post content
		if ( !isset( $html, $src ) && ( $args['scan'] ) )
		{
			preg_match( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );

			if ( !empty( $matches ) )
			{
				$html = $matches[0];
				$src = $matches[1];
			}
		}

		// Use default when nothing found
		if ( !isset( $html, $src ) && !empty( $args['default'] ) )
		{
			if ( is_array( $args['default'] ) )
			{
				$html = @$args['html'];
				$src = @$args['src'];
			}
			else
			{
				$html = $src = $args['default'];
			}
		}

		// Still no images found?
		if ( !isset( $html, $src ) )
			return false;

		$output = 'html' === strtolower( $args['format'] ) ? $html : $src;

		$image_cache[$key] = $output;
		wp_cache_set( $args['post_id'], $image_cache, __FUNCTION__ );
	}
	// If image already cached
	else
	{
		$output = $image_cache[$key];
	}

	if ( !$args['echo'] )
		return $output;

	echo $output;
}

/**
 * Register fonts
 *
 * @since  1.0.0
 *
 * @return string
 */
function learnplus_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by RobotoSlab, translate this to 'off'. Do not translate
	* into your own language.
	*/
    $roboto_slab = _x( 'on', 'Roboto Slab font: on or off', 'learnplus' );

	/* Translators: If there are characters in your language that are not
	* supported by OpenSans, translate this to 'off'. Do not translate
	* into your own language.
	*/
    $open_sans = _x( 'on', 'Open Sans font: on or off', 'learnplus' );

	/* Translators: If there are characters in your language that are not
    * supported by DroidSerif, translate this to 'off'. Do not translate
    * into your own language.
    */
    $droid_serif = _x( 'on', 'Droid Serif font: on or off', 'learnplus' );

	if ( 'off' !== $roboto_slab || 'off' !== $open_sans || 'off' !== $droid_serif ) {
		$font_families = array();

		if ( 'off' !== $roboto_slab ) {
			$font_families[] = 'Roboto Slab:400,100,300,700';
		}

		if ( 'off' !== $open_sans ) {
			$font_families[] = 'Open Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic';
		}

		if ( 'off' !== $droid_serif ) {
			$font_families[] = 'Droid Serif:400,400italic,700,700italic';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Get uploaded image information
 * Base on plugin Meta Box
 *
 * @see RWMB_Helper::image_info
 *
 * @since 1.0
 *
 * @param int   $id   Attachment image ID (post ID). Required.
 * @param array $args Array of arguments (for size). Required.
 *
 * @return array|bool False if file not found. Array of (id, name, path, url) on success
 */
function learnplus_get_image_info( $id, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'size' => 'thumbnail',
	) );

	$img_src = wp_get_attachment_image_src( $id, $args['size'] );
	if ( empty( $img_src ) )
		return false;

	$attachment = get_post( $id );
	$path = get_attached_file( $id );
	return array(
		'ID'          => $id,
		'name'        => basename( $path ),
		'path'        => $path,
		'url'         => $img_src[0],
		'width'       => $img_src[1],
		'height'      => $img_src[2],
		'full_url'    => wp_get_attachment_url( $id ),
		'title'       => $attachment->post_title,
		'caption'     => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'alt'         => get_post_meta( $id, '_wp_attachment_image_alt', true ),
	);
}
