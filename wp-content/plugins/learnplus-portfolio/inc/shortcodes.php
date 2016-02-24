<?php
/**
 * Portfolio shortcodes
 *
 * @package TA Portfolio Management
 */

/**
 * Shortcode function to display portfolio
 *
 * @since  1.0.0
 *
 * @param  array $atts
 *
 * @return string
 */
function learnplus_portfolio_shortcode($atts) {
	$atts = shortcode_atts(
		array(
			'layout'   => 'masonry',
			'cats'     => '',
			'field'    => 'slug',
			'limit'    => 8,
			'filter'   => 'yes',
			'columns'  => 4,
			'paginate' => 'yes',
			'gutter'   => 0,
		),
		$atts
	);

	// Allow themes or other plugins change content
	$html = apply_filters('learnplus_portfolio_showcase', '', $atts);

	echo $html;

	if (!empty($html)) {
		return $html;
	}

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	if (is_front_page()) {
		$paged = (get_query_var('page')) ? get_query_var('page') : 1;
	}
	$args = array(
		'post_type'      => 'portfolio_project',
		'posts_per_page' => intval($atts['limit']),
		'paged'          => max(1, $paged),
	);

	if (!empty($atts['cats'])) {
		$cats = is_string($atts['cats']) ? explode(',', trim($atts['cats'])) : $atts['cats'];
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'portfolio_category',
				'field'    => $atts['field'],
				'terms'    => $cats,
			),
		);
	}

	$query = new WP_Query($args);

	if (!$query->have_posts()) {
		return '';
	}

	/*
	 * Filter
	 */
	$filter = '';
	if ('yes' == $atts['filter'] && 'carousel' != $atts['layout']) {
		if (empty($cats)) {
			$cats = get_terms('portfolio_category');
		}

		$filter .= '<div id="' . uniqid('portfolio-filter-') . '" class="portfolio-filter album-cat filter clearfix"><div class="container"><ul>';


		$filter .= sprintf(
			'<li class="active"><a href="#" data-filter="*" title="%s" class="active">%s</a></li>',
			__('View all items', 'learnplus-portfolio'),
			__('All', 'learnplus-portfolio')
		);

		global $wp_query;
		$term = $wp_query->queried_object;
		if ($term) {
			$term = $term->slug;
		}
		foreach ($cats as $cat) {
			if (is_string($cat)) {
				$type = 'slug' == $atts['field'] ? 'slug' : 'id';
				$cat = get_term_by($type, $cat, 'portfolio_category');
			}

			$css_class = '';
			if ($term == $cat->slug) {
				$css_class = 'active';
			}

			$filter .= sprintf(
				'<li class="%s"><a href="%s" data-filter=".%s" title="%s" >%s</a></li>',
				esc_attr($css_class),
				get_term_link($cat->slug, 'portfolio_category'),
				$cat->slug,
				__('View all items under', 'learnplus-portfolio') . ' ' . $cat->name,
				$cat->name
			);
		}

		$filter .= '</ul></div></div>';
	}

	/*
	 * Works
	 */
	$works = '';
	$tag = current_theme_supports('html5') ? 'article' : 'div';
	if (in_array($atts['layout'], array('metro', 'masonry'))) {
		$works .= '<div class="portfolio-sizer"></div>';
	}
	while ($query->have_posts()) : $query->the_post();
		// Allow theme themes and plugins create portfolio item in another markup
		if ($item = apply_filters('learnplus_portfolio_item_content', '', $atts, $query->current_post, $tag)) {
			$works .= $item;
		} else {
			// Allow filters by themes and other plugins
			// We use it as well: learnplus_portfolio_item_classes
			$classes = apply_filters('learnplus_portfolio_item_class', array(), $atts, $query->current_post, $query->post_count);
			$attrs = apply_filters('learnplus_portfolio_item_attr', array(), $atts, $query->current_post, $query->post_count);
			$attributes = '';
			if (!empty($attrs)) {
				foreach ($attrs as $attr => $attr_val) {
					$attributes .= ' ' . $attr . '="' . $attr_val . '"';
				}
			}

			$cats = wp_get_post_terms(get_the_ID(), 'portfolio_category');
			$cat_name = $cats ? $cats[0]->name : '';

			$works .= '<' . $tag . ' class="' . implode(' ', get_post_class($classes)) . '" ' . $attributes . '>';
			$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), "full");
			if ($image_src) $image_src = $image_src[0];
			$works .= '<div class="portfolio-detail item">';

			$portfolio_type = get_post_meta(get_the_ID(), '_project_type', true);
			$dalearnplus_type = '';
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

			$works .= get_the_post_thumbnail(get_the_ID(), apply_filters('learnplus_portfolio_item_thumnail_size', 'portfolio-thumbnail-normal', $atts, $query->current_post, $query->post_count));
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
		}
	endwhile;

	$works_class = array('portfolio-showcase', 'learnplus-portfolio-shortcode', 'portfolio-layout-' . $atts['layout'], 'portfolio-columns-' . $atts['columns'], 'clearfix');
	$works_class = apply_filters('learnplus_portfolio_showcase_class', $works_class, $atts);
	$works = sprintf(
		'<div id="%s" data-columns="%s" data-layout="%s" data-gutter="%d" class="main-project %s">%s</div>',
		uniqid('portfolio-showcase-'),
		esc_attr($atts['columns']),
		esc_attr($atts['layout']),
		esc_attr($atts['gutter']),
		implode(' ', $works_class),
		$works
	);


	/*
 * Paging
 */
	$paging = '';
	if ('yes' == $atts['paginate'] && 1 < $query->max_num_pages) {
		$big = 999999999;

		$paging = paginate_links(
			array(
				'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format'    => '?paged=%#%',
				'current'   => max(1, $paged),
				'total'     => $query->max_num_pages,
				'prev_text' => apply_filters('learnplus_portfolio_paging_prev_text', __('«', 'learnplus-portfolio'), $atts),
				'next_text' => apply_filters('learnplus_portfolio_paging_next_text', __('View More', 'learnplus-portfolio'), $atts),
			)
		);

		$paging = '<div id="' . uniqid('portfolio-pagination-') . '" class="portfolio-pagination portfolio-pagination ">' . $paging . '</div>';
	}


	wp_reset_postdata();

	return apply_filters(__FUNCTION__, $filter . $works . $paging, $atts, $filter, $works, $paging);
}

add_shortcode('learnplus_portfolio', 'learnplus_portfolio_shortcode');

/**
 * Shortcode function to display portfolio showcase
 *
 * @since  1.0.0
 *
 * @param  array $atts
 *
 * @return string
 */
function learnplus_portfolio_showcase_shortcode($atts) {
	$atts = shortcode_atts(array('id' => 0), $atts);

	$args = array(
		'layout'   => get_post_meta($atts['id'], '_portfolio_showcase_layout', true),
		'cats'     => get_post_meta($atts['id'], '_portfolio_showcase_cat', true),
		'field'    => 'term_id',
		'limit'    => get_post_meta($atts['id'], '_portfolio_showcase_limit', true),
		'filter'   => get_post_meta($atts['id'], '_portfolio_showcase_filter', true),
		'columns'  => get_post_meta($atts['id'], '_portfolio_showcase_columns', true),
		'paginate' => get_post_meta($atts['id'], '_portfolio_showcase_pagination', true),
		'gutter'   => get_post_meta($atts['id'], '_portfolio_showcase_gutter', true),
	);

	return apply_filters(__FUNCTION__, learnplus_portfolio_shortcode($args), $atts, $args);
}

add_shortcode('learnplus_portfolio_showcase', 'learnplus_portfolio_showcase_shortcode');
