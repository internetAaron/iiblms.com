<?php
/**
 * Custom functions for entry.
 *
 * @package LearnPlus
 */

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0
 * @return void
 */
function learnplus_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if(get_the_time('U') !== get_the_modified_time('U')) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}
	$time_string = sprintf(
		$time_string,
		esc_attr(get_the_date('c')),
		esc_html(get_the_date()),
		esc_attr(get_the_modified_date('c')),
		esc_html(get_the_modified_date())
	);
	$posted_on = sprintf(
		_x('Posted on %s', 'post date', 'learnplus'),
		'<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
	);
	$byline = sprintf(
		_x('by %s', 'post author', 'learnplus'),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
	);
	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';
}

/**
 * Get post meta
 *
 * @since  1.0
 *
 * @param  string $key
 * @param  array $args
 * @param  int $post_id
 *
 * @return mixed
 */
function learnplus_get_meta($key, $args = array(), $post_id = null) {
	if(function_exists('rwmb_meta')) {
		return rwmb_meta($key, $args, $post_id);
	}

	/**
	 * Base on Meta Box plugin function
	 */
	$post_id = empty($post_id) ? get_the_ID() : $post_id;
	$args = wp_parse_args(
		$args, array(
			'type' => 'text',
		)
	);

	// Set 'multiple' for fields based on 'type'
	if(!isset($args['multiple'])) {
		$args['multiple'] = in_array($args['type'], array('checkbox_list', 'file', 'file_advanced', 'image', 'image_advanced', 'plupload_image', 'thickbox_image'));
	}

	$meta = get_post_meta($post_id, $key, !$args['multiple']);

	// Get uploaded files info
	if(in_array($args['type'], array('file', 'file_advanced'))) {
		if(is_array($meta) && !empty($meta)) {
			$files = array();
			foreach( $meta as $id ) {
				$path = get_attached_file($id);
				$files[$id] = array(
					'ID'    => $id,
					'name'  => basename($path),
					'path'  => $path,
					'url'   => wp_get_attachment_url($id),
					'title' => get_the_title($id),
				);
			}
			$meta = $files;
		}
	} // Get uploaded images info
	elseif(in_array($args['type'], array('image', 'plupload_image', 'thickbox_image', 'image_advanced'))) {
		global $wpdb;

		$meta = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT meta_value FROM $wpdb->postmeta
					WHERE post_id = %d AND meta_key = '%s'
					ORDER BY meta_id ASC
				", $post_id, $key
			)
		);

		if(is_array($meta) && !empty($meta)) {
			$images = array();
			foreach( $meta as $id ) {
				$images[$id] = fastlat_get_image_info($id, $args);
			}
			$meta = $images;
		}
	} // Get terms
	elseif('taxonomy_advanced' == $args['type']) {
		if(!empty($args['taxonomy'])) {
			$term_ids = array_map('intval', array_filter(explode(',', $meta . ',')));

			// Allow to pass more arguments to "get_terms"
			$func_args = wp_parse_args(
				array(
					'include'    => $term_ids,
					'hide_empty' => false,
				), $args
			);
			unset($func_args['type'], $func_args['taxonomy'], $func_args['multiple']);
			$meta = get_terms($args['taxonomy'], $func_args);
		} else {
			$meta = array();
		}
	} // Get post terms
	elseif('taxonomy' == $args['type']) {
		$meta = empty($args['taxonomy']) ? array() : wp_get_post_terms($post_id, $args['taxonomy']);
	}

	return $meta;
}


/**
 * Get or display limited words from given string.
 * Strips all tags and shortcodes from string.
 *
 * @since 1.0
 *
 * @param integer $num_words The maximum number of words
 * @param string $content The content limit.
 * @param string $more More link.
 * @param bool $echo Echo or return output
 *
 * @return string|void Limited content.
 */
function learnplus_content_limit($content, $num_words, $more = "&hellip;", $echo = true) {

	// Strip tags and shortcodes so the content truncation count is done correctly
	$content = strip_tags(strip_shortcodes($content), apply_filters('learnplus_content_limit_allowed_tags', '<script>,<style>'));

	// Remove inline styles / scripts
	$content = trim(preg_replace('#<(s(cript|tyle)).*?</\1>#si', '', $content));

	// Truncate $content to $max_char
	$content = wp_trim_words($content, $num_words);

	if($more) {
		$output = sprintf(
			'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
			$content,
			get_permalink(),
			sprintf(esc_attr__('Continue reading &quot;%s&quot;', 'learnplus'), the_title_attribute('echo=0')),
			esc_html($more)
		);
	} else {
		$output = sprintf('<p>%s</p>', $content);
	}

	if(!$echo) {
		return $output;
	}

	echo $output;
}

/**
 * Show entry thumbnail base on its format
 *
 * @since  1.0
 */
function learnplus_entry_thumbnail($size = 'learnplus-blog-thumb') {
	$html = '';
	$css_class = 'format-' . get_post_format();
	if('full-content' == learnplus_get_layout()) {
		$size = 'learnplus-blog-large-thumb';
	}
	$size = apply_filters('learnplus_post_format_thumbnail_size', $size);

	switch( get_post_format() ) {
		case 'image':
			$image = learnplus_get_image(
				array(
					'size'     => $size,
					'format'   => 'src',
					'meta_key' => 'image',
					'echo'     => false,
				)
			);

			if(!$image) {
				break;
			}

			$html = sprintf(
				'<a class="entry-image" href="%1$s" title="%2$s"><img src="%3$s" alt="%2$s"></a>',
				esc_url(get_permalink()),
				the_title_attribute('echo=0'),
				esc_url($image)
			);
			break;
		case 'gallery':
			$images = learnplus_get_meta('images', "type=image&size=$size");

			if(empty($images)) {
				break;
			}

			$gallery = array();
			foreach( $images as $image ) {
				$gallery[] = '<li>' . '<img src="' . esc_url($image['url']) . '" alt="' . the_title_attribute('echo=0') . '">' . '</li>';
			}
			$html .= '<div class="format-gallery-slider entry-image"><ul class="slides">' . implode('', $gallery) . '</ul></div>';
			break;
		case 'audio':

			$audio = learnplus_get_meta('audio');
			if(!$audio) {
				break;
			}

			// If URL: show oEmbed HTML or jPlayer
			if(filter_var($audio, FILTER_VALIDATE_URL)) {
				// Try oEmbed first
				if($oembed = @wp_oembed_get($audio)) {
					$html .= $oembed;
				} // Use audio shortcode
				else {
					$html .= '<div class="audio-player">' . wp_audio_shortcode(array('src' => $audio)) . '</div>';
				}
			} // If embed code: just display
			else {
				$html .= $audio;
			}
			break;
		case 'video':
			$video = learnplus_get_meta('video');
			if(!$video) {
				break;
			}

			// If URL: show oEmbed HTML
			if(filter_var($video, FILTER_VALIDATE_URL)) {
				if($oembed = @wp_oembed_get($video)) {
					$html .= $oembed;
				} else {
					$atts = array(
						'src'   => $video,
						'width' => 848,
					);
					if(has_post_thumbnail()) {
						$atts['poster'] = learnplus_get_image('format=src&echo=0&size=full');
					}
					$html .= wp_video_shortcode($atts);
				}
			} // If embed code: just display
			else {
				$html .= $video;
			}
			break;
		case 'link':

			$link = learnplus_get_meta('url');
			$text = learnplus_get_meta('url_text');

			if(!$link) {
				break;
			}

			$html .= sprintf('<a href="%s" class="link-block">%s</a>', esc_url($link), $text ? $text : $link);

			break;
		case 'quote':

			$quote = learnplus_get_meta('quote');
			$author = learnplus_get_meta('quote_author');
			$author_url = learnplus_get_meta('author_url');

			if(!$quote) {
				break;
			}

			$html .= sprintf(
				'<blockquote>%s<cite>%s</cite></blockquote>',
				esc_html($quote),
				empty($author_url) ? $author : '<a href="' . esc_url($author_url) . '"> - ' . $author . '</a>'
			);

			break;
		default:
			$thumb = learnplus_get_image(
				array(
					'size'     => $size,
					'meta_key' => 'image',
					'echo'     => false,
				)
			);
			if(empty($thumb)) {
				break;
			}

			$html .= '<a class="entry-image" href="' . get_permalink() . '">' . $thumb . '</a>';
			break;
	}

	if($html = apply_filters(__FUNCTION__, $html, get_post_format())) {
		$css_class = esc_attr($css_class);
		echo "<div class='entry-format $css_class'>$html</div>";
	}
}

/**
 * Prints HTML with  information for the page title and breacrumb.
 *
 * @since 1.0
 * @return string
 */
function learnplus_page_title() {
	if(learnplus_boxed_content()) :
		if( is_single() ) :
		?>
			<div class="blog-wrapper">
				<div class="row second-bread">
					<div class="col-md-12 col-sm-12">
						<div class="bread">
							<div class="breadcrumb">
								<?php learnplus_breadcrumbs(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		else:
		?>
			<div class="blog-wrapper">
				<div class="row second-bread">
					<div class="col-md-6 col-sm-6 text-left">
						<?php the_archive_title('<h1>', '</h1>'); ?>
					</div>
					<div class="col-md-6 col-sm-6 text-right">
						<div class="bread">
							<div class="breadcrumb">
								<?php learnplus_breadcrumbs(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php else:
		?>
		<section class="grey page-title">
			<div class="container">
				<div class="row ">
					<div class="col-md-6 col-sm-6 text-left">
						<?php the_archive_title('<h1>', '</h1>'); ?>
					</div>
					<!-- end col -->
					<div class="col-md-6 col-sm-6 text-right">
						<div class="bread">
							<div class="breadcrumb">
								<?php learnplus_breadcrumbs(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php
	endif;
}

/**
 * Check page boxed content
 *
 * @since 1.0
 * @return bool
 */
function learnplus_boxed_content() {
	global $wp_query;
	$curauth = $wp_query->get_queried_object();

	if((!is_page() || is_page_template('template-boxed-content.php'))
		&& !is_post_type_archive('forum')
		&& !is_post_type_archive('tribe_events')
		&& !is_post_type_archive('sfwd-courses')
		&& !is_tax('product_cat')
		&& (function_exists('is_shop') && !is_shop())
		&& (function_exists('is_product') && !is_product())
		&& !is_singular('forum')
		&& !is_singular('topic')
		&& !is_singular('sfwd-courses')
		&& !is_singular('sfwd-lessons')
		&& !is_singular('sfwd-quiz')
		&& !is_singular('sfwd-topic')
		&& !is_singular('tribe_events')
		&& !is_singular('portfolio_project')
		&& !(is_author() && $curauth && isset( $curauth->roles[0] ) && $curauth->roles[0] == 'instructor' )
	) {
		return true;
	}

	return false;
}

/**
 * Retrieves related product terms
 *
 * @param string $term
 * @return array
 */
function learnplus_get_related_terms($term, $post_id = null) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms_array = array(0);

	$terms = wp_get_post_terms($post_id, $term);
	foreach( $terms as $term ) {
		$terms_array[] = $term->term_id;
	}

	return array_map('absint', $terms_array);
}