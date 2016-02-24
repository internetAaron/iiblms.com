<?php
/**
 * Display breadcrumbs for posts, pages, archive page with the microdata that search engines understand
 *
 * @see http://support.google.com/webmasters/bin/answer.py?hl=en&answer=185417
 *
 * @param array|string $args
 */
function learnplus_breadcrumbs($args = '') {
	$args = wp_parse_args($args, array(
		'separator'         => '<span class="sep">&#47;</span>',
		'home_class'        => 'home',
		'before'            => '',
		'before_item'       => '',
		'after_item'        => '',
		'taxonomy'          => 'category',
		'display_last_item' => true,
		'show_on_front'     => true,
		'labels'            => array(
			'home'      => esc_html__('Home', 'learnplus'),
			'archive'   => esc_html__('Archives', 'learnplus'),
			'blog'      => esc_html__('Blog', 'learnplus'),
			'search'    => esc_html__('Search results for', 'learnplus'),
			'404'       => esc_html__('404', 'learnplus'),
			'single'    => esc_html__('Single Post', 'learnplus'),
			'product'   => esc_html__('Single Shop Page', 'learnplus'),
			'portfolio' => esc_html__('Single Portfolio', 'learnplus'),
			'event'     => esc_html__('Single event', 'learnplus'),
			'events'    => esc_html__('Events', 'learnplus'),
			'courses'   => esc_html__('Courses', 'learnplus'),
			'course'    => esc_html__('Single Course', 'learnplus'),
			'lesson'    => esc_html__('Single Lesson', 'learnplus'),
			'quiz'      => esc_html__('Single Quiz', 'learnplus'),
			'forum'     => esc_html__('Forum', 'learnplus'),
			'author'    => esc_html__('Author:', 'learnplus'),
			'day'       => esc_html__('Daily Archives:', 'learnplus'),
			'month'     => esc_html__('Monthly Archives:', 'learnplus'),
			'year'      => esc_html__('Yearly Archives:', 'learnplus'),
		),
	));

	$args = apply_filters('learnplus_breadcrumbs_args', $args);

	if (is_front_page() && !$args['show_on_front']) {
		return;
	}

	if (function_exists('bbp_get_breadcrumb')) {
		if (is_singular('forum') || is_singular('topic') || is_post_type_archive('forum')) {
			$args = array(
				'sep'       => '<span class="sep">&#47;</span>',
				'home_text' => esc_html__('Home', 'learnplus'),
			);
			echo bbp_get_breadcrumb($args);

			return;
		}
	}

	$items = array();

	// HTML template for each item
	$item_tpl = $args['before_item'] . '
		<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a href="%s" itemprop="url"><span itemprop="title">%s</span></a>
		</span>
	' . $args['after_item'];
	$item_text_tpl = $args['before_item'] . '
		<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<span itemprop="title">%s</span>
		</span>
	' . $args['after_item'];

	// Home
	if (!$args['home_class']) {
		$items[] = sprintf($item_tpl, get_home_url(), $args['labels']['home']);
	} else {
		$items[] = sprintf(
			'%s<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a class="%s" href="%s" itemprop="url"><span itemprop="title">%s</span></a>
			</span>%s',
			$args['before_item'],
			$args['home_class'],
			get_home_url(),
			$args['labels']['home'],
			$args['after_item']
		);
	}

	// Front page
	if (is_front_page()) {
		$items = array();
		$items[] = sprintf($item_text_tpl, $args['labels']['home']);
	} // Blog
	elseif (is_home() && !is_front_page()) {
		$items[] = sprintf(
			$item_text_tpl,
			$args['labels']['blog']
		);
	} // Single
	elseif (is_singular('portfolio_project')) {

		$taxonomy = 'portfolio_category';

		$terms = get_the_terms(get_the_ID(), $taxonomy);
		$term = current($terms);
		$terms = learnplus_get_term_parents($term->term_id, $taxonomy);
		$terms[] = $term->term_id;

		foreach ($terms as $term_id) {
			$term = get_term($term_id, $taxonomy);
			$items[] = sprintf($item_tpl, get_term_link($term, $taxonomy), $term->name);
		}
		// Terms
		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, get_the_title());
		}
	} elseif (is_singular('product')) {

		$taxonomy = 'product_cat';

		$terms = get_the_terms(get_the_ID(), $taxonomy);
		$term = current($terms);
		$terms = learnplus_get_term_parents($term->term_id, $taxonomy);
		$terms[] = $term->term_id;

		foreach ($terms as $term_id) {
			$term = get_term($term_id, $taxonomy);
			$items[] = sprintf($item_tpl, get_term_link($term, $taxonomy), $term->name);
		}
		// Terms
		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, get_the_title());
		}
	} // Single
	elseif (is_singular('tribe_events')) {
		$items[] = sprintf($item_tpl, get_post_type_archive_link('tribe_events'), $args['labels']['events']);
		// Terms

		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, $args['labels']['event']);
		}
	} // Single
	elseif (is_post_type_archive('tribe_events')) {
		// Terms
		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, $args['labels']['events']);
		}
	} elseif (is_post_type_archive('sfwd-courses')) {
		// Terms
		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, $args['labels']['courses']);
		}
	} elseif (is_singular('sfwd-courses')) {
		$items[] = sprintf($item_tpl, get_home_url(), $args['labels']['courses']);
		// Terms
		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, get_the_title());
		}
	} elseif (is_singular('sfwd-lessons')) {
		$items[] = sprintf($item_tpl, get_post_type_archive_link('sfwd-courses'), $args['labels']['courses']);
		// Terms
		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, get_the_title());
		}
	} elseif (is_singular('sfwd-topic')) {
		$items[] = sprintf($item_tpl, get_post_type_archive_link('sfwd-courses'), $args['labels']['courses']);
		// Terms
		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, get_the_title());
		}
	}// Single
	elseif (is_singular('sfwd-quiz')) {
		$items[] = sprintf($item_tpl, get_post_type_archive_link('sfwd-courses'), $args['labels']['courses']);
		// Terms
		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, get_the_title());
		}
	}// Single
	elseif (is_single()) {
		$taxonomy = $args['taxonomy'];
		$terms = get_the_terms(get_the_ID(), $taxonomy);
		$term = current($terms);
		$terms = learnplus_get_term_parents($term->term_id, $taxonomy);
		$terms[] = $term->term_id;

		foreach ($terms as $term_id) {
			$term = get_term($term_id, $taxonomy);
			$items[] = sprintf($item_tpl, get_term_link($term, $taxonomy), $term->name);
		}

		if ($args['display_last_item']) {
			$items[] = sprintf($item_text_tpl, get_the_title());
		}
	} // Page
	elseif (is_page()) {
		$pages = learnplus_get_post_parents(get_queried_object_id());
		foreach ($pages as $page) {
			$items[] = sprintf($item_tpl, get_permalink($page), get_the_title($page));
		}
		if ($args['display_last_item'])
			$items[] = sprintf($item_text_tpl, get_the_title());
	} // Taxonomy
	elseif (is_tax() || is_category() || is_tag()) {
		$current_term = get_queried_object();
		$terms = learnplus_get_term_parents(get_queried_object_id(), $current_term->taxonomy);
		foreach ($terms as $term_id) {
			$term = get_term($term_id, $current_term->taxonomy);
			$items[] = sprintf($item_tpl, get_category_link($term_id), $term->name);
		}
		if ($args['display_last_item'])
			$items[] = sprintf($item_text_tpl, $current_term->name);
	} // Search
	elseif (is_search()) {
		$items[] = sprintf($item_text_tpl, $args['labels']['search'] . ' &quot;' . get_search_query() . '&quot;');
	} // 404
	elseif (is_404()) {
		$items[] = sprintf($item_text_tpl, $args['labels']['404']);
	} // Author archive
	elseif (is_author()) {
		// Queue the first post, that way we know what author we're dealing with (if that is the case).

		global $wp_query;
		$curauth = $wp_query->get_queried_object();

		if ($curauth && isset($curauth->roles[0]) && $curauth->roles[0] == 'instructor') :
			$items[] = sprintf($args['labels']['author'] . ' <span class="vcard">' . $curauth->nickname . '</span>');
		else:
			the_post();
			$items[] = sprintf(
				$item_text_tpl,
				$args['labels']['author'] . ' <span class="vcard"><a class="url fn n" href="' . get_author_posts_url(get_the_author_meta('ID')) . '" title="' . esc_attr(get_the_author()) . '" rel="me">' . get_the_author() . '</a></span>'
			);
			rewind_posts();
		endif;
	} // Day archive
	elseif (is_day()) {
		$items[] = sprintf(
			$item_text_tpl,
			sprintf(__('%s %s', 'learnplus'), $args['labels']['day'], get_the_date())
		);
	} // Month archive
	elseif (is_month()) {
		$items[] = sprintf(
			$item_text_tpl,
			sprintf(__('%s %s', 'learnplus'), $args['labels']['month'], get_the_date('F Y'))
		);
	} // Year archive
	elseif (is_year()) {
		$items[] = sprintf(
			$item_text_tpl,
			sprintf(__('%s %s', 'learnplus'), $args['labels']['year'], get_the_date('Y'))
		);
	} // Archive
	else {
		$items[] = sprintf(
			$item_text_tpl,
			$args['labels']['archive']
		);
	}

	echo $args['before'] . implode($args['separator'], $items);
}

/**
 * Searches for term parents' IDs of hierarchical taxonomies, including current term.
 * This function is similar to the WordPress function get_category_parents() but handles any type of taxonomy.
 * Modified from Hybrid Framework
 *
 * @param int|string $term_id The term ID
 * @param object|string $taxonomy The taxonomy of the term whose parents we want.
 *
 * @return array Array of parent terms' IDs.
 */
function learnplus_get_term_parents($term_id = '', $taxonomy = 'category') {
	// Set up some default arrays.
	$list = array();

	// If no term ID or taxonomy is given, return an empty array.
	if (empty($term_id) || empty($taxonomy)) {
		return $list;
	}

	do {
		$list[] = $term_id;

		// Get next parent term
		$term = get_term($term_id, $taxonomy);
		$term_id = $term->parent;
	} while ($term_id);

	// Reverse the array to put them in the proper order for the trail.
	$list = array_reverse($list);
	array_pop($list);

	return $list;
}

/**
 * Gets parent posts' IDs of any post type, include current post
 * Modified from Hybrid Framework
 *
 * @param int|string $post_id ID of the post whose parents we want.
 *
 * @return array Array of parent posts' IDs.
 */
function learnplus_get_post_parents($post_id = '') {
	// Set up some default array.
	$list = array();

	// If no post ID is given, return an empty array.
	if (empty($post_id)) {
		return $list;
	}

	do {
		$list[] = $post_id;

		// Get next parent post
		$post = get_post($post_id);
		$post_id = $post->post_parent;
	} while ($post_id);

	// Reverse the array to put them in the proper order for the trail.
	$list = array_reverse($list);
	array_pop($list);

	return $list;
}
