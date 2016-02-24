<?php

/**
 * Class for all WooCommerce template modification
 *
 * @version 1.0
 */
class LearnPlus_WooCommerce {
	/**
	 * @var string Layout of current page
	 */
	public $layout;

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return learnplus_WooCommerce
	 */
	function __construct() {
		// Check if Woocomerce plugin is actived
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return false;
		}

		// Define all hook
		add_action( 'template_redirect', array( $this, 'hooks' ) );

		// Need an early hook to ajaxify update mini shop cart
		add_filter( 'add_to_cart_fragments', array( $this, 'add_to_cart_fragments' ) );
	}

	/**
	 * Hooks to WooCommerce actions, filters
	 *
	 * @since  1.0
	 * @return void
	 */
	function hooks() {
		$this->layout       = learnplus_get_layout();

		// WooCommerce Styles
		add_filter( 'woocommerce_enqueue_styles', array( $this, 'wc_styles' ) );

		// Remove breadcrumb, use theme's instead
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		// Add toolbars for shop page
		add_filter( 'woocommerce_show_page_title', '__return_false' );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		// Change shop columns
		add_filter( 'loop_shop_columns', array( $this, 'shop_columns' ), 20 );

		// Add Bootstrap classes
		add_filter( 'post_class', array( $this, 'product_class' ), 10, 3 );

		// Wrap product loop content
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'open_product_inner' ), 1 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'close_product_inner' ), 100 );

		// Add badges
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		remove_action( 'learnplus_single_product_sidebar', 'woocommerce_show_product_sale_flash' );

		// Add single product image main
		$shop_single_layout = learnplus_theme_option( 'shop_single_layout' );
		if( $shop_single_layout ) {
			add_action( 'learnplus_single_product_image', 'woocommerce_show_product_images', 5 );
		} else {
			add_action( 'learnplus_single_product_sidebar', 'woocommerce_show_product_images', 5 );
		}

		// Add single product title
		add_action( 'learnplus_single_product_content', 'woocommerce_template_single_title', 10 );

		// Add single product thumbnails
		remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
		if( ! $shop_single_layout ) {
			add_action('learnplus_single_product_content', 'woocommerce_show_product_thumbnails', 5);
		}

		// Add single product content
		add_action( 'learnplus_single_product_content', array( $this, 'single_product_content' ), 20 );

		// Add single product meta
		add_action( 'learnplus_single_product_sidebar', array( $this, 'learnplus_single_meta' ), 15 );

		// Display product excerpt and subcategory description for list view
		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 1 );
		add_action( 'woocommerce_after_subcategory', array( $this, 'show_cat_desc' ) );

		// Change add to cart text
		add_filter( 'woocommerce_product_add_to_cart_text',  array( $this, 'add_to_cart_text' ), 10, 2 );
		add_filter( 'woocommerce_product_single_add_to_cart_text',  array( $this, 'add_to_cart_text' ), 10, 2 );

		// Change price text
		add_filter( 'woocommerce_free_price_html',  array( $this, 'free_price_html' ) );

		// Change number of related products
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
		add_filter( 'woocommerce_cross_sells_columns', array( $this, 'cross_sells_columns' ) );

		// Change next and prev icon
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );

		// Change product rating html
		add_filter( 'woocommerce_product_get_rating_html', array( $this, 'product_rating_html' ), 10, 2 );

		// Change product stock html
		add_filter( 'woocommerce_stock_html', array( $this, 'product_stock_html' ), 10, 3 );

		// Add product data tabs
		remove_action( 'woocommerce_after_single_product_summary',  'woocommerce_output_product_data_tabs', 10 );
		add_action( 'learnplus_single_product_content',  'woocommerce_output_product_data_tabs', 30 );
		add_filter( 'woocommerce_product_tabs',  array( $this, 'product_tabs' ) );

		// Add products upsell display
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'upsell_products' ), 15 );

		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	}

	/**
	 * Ajaxify update cart viewer
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	function add_to_cart_fragments( $fragments ) {
		global $woocommerce;

		if ( empty( $woocommerce ) ) {
			return $fragments;
		}

		ob_start();
		?>

		<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ) ?>" class="cart-contents" title="<?php esc_attr_e( 'View your shopping cart', 'learnplus' ) ?>">
			<i class="flaticon-shopping101"></i>
			<span class="mini-cart-counter"><?php echo intval( $woocommerce->cart->cart_contents_count ) ?></span>
		</a>

		<?php
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Remove default woocommerce styles
	 *
	 * @since  1.0
	 *
	 * @param  array $styles
	 *
	 * @return array
	 */
	function wc_styles( $styles ) {
		// unset( $styles['woocommerce-general'] );
		unset( $styles['woocommerce-layout'] );
		unset( $styles['woocommerce-smallscreen'] );

		return $styles;
	}


	/**
	 * Change the shop columns
	 *
	 * @since  1.0.0
	 * @param  int $columns The default columns
	 * @return int
	 */
	function shop_columns( $columns ) {
		if ( is_woocommerce() ) {
			if ( is_product() ) {
				$columns = 3;
			} elseif ( ( is_woocommerce() || is_cart() ) && 'full-content' == $this->layout ) {
				$columns = 4;
			} elseif ( ( is_woocommerce() || is_cart() ) && 'full-content' != $this->layout ) {
				$columns = 3;
			}
		}

		return $columns;
	}

	/**
	 * Add Bootstrap's column classes for product
	 *
	 * @since 1.0
	 *
	 * @param array  $classes
	 * @param string $class
	 * @param string $post_id
	 *
	 * @return array
	 */
	function product_class( $classes, $class = '', $post_id = '' ) {
		if ( ! $post_id || get_post_type( $post_id ) !== 'product' || is_single( $post_id ) ) {
			return $classes;
		}
		global $woocommerce_loop;

		$classes[] = 'col-sm-6 col-xs-6';
		$classes[] = 'col-md-' . (12 / $woocommerce_loop['columns']);

		return $classes;
	}

	/**
	 * Wrap product content
	 * Open a div
	 *
	 * @since 1.0
	 */
	function open_product_inner() {
		echo '<div class="product-inner clearfix">';
	}

	/**
	 * Wrap add to cart text
	 * Open a div
	 *
	 * @since 1.0
	 */
	function add_to_cart_text( $text, $product ) {

		if(  $product->product_type == 'simple' ) {

			if( $product->get_price() !== '0' && $product->get_price() !=='' ) {
				if( $product->is_purchasable() && $product->is_in_stock() ) {
					$price = $product->get_price();
					$text = strip_tags( wc_price( $price ) ) . ' ' . esc_html__('Purchase', 'learnplus');
				}
			} else {
				if( $product->is_downloadable() ) {
					$text = esc_html__('Free Download', 'learnplus');
				} else {
					$text = esc_html__('Free Purchase', 'learnplus');
				}
			}

		}

		return $text;
	}

	/**
	 * Wrap price text
	 * Open a div
	 *
	 * @since 1.0
	 */
	function free_price_html( ) {
		return wc_price( 0 );
	}

	/**
	 * Wrap single product content
	 * Open a div
	 *
	 * @since 1.0
	 */
	function single_product_content( ) {
		the_content();
	}

	/**
	 * Wrap product_tabs
	 * Open a div
	 *
	 * @since 1.0
	 */
	function product_tabs( ) {
		global $product;

		$review_count = $product->get_review_count();

		return array(
			'reviews' => array(
				'title' => sprintf( esc_html__( 'What our customers said? (%s Feedbacks)', 'learnplus'), $review_count ),
				'priority' => 10,
				'callback' => 'comments_template'
			)
		);
	}

	/**
	 * Wrap learnplus single meta
	 * Open a div
	 *
	 * @since 1.0
	 */
	function learnplus_single_meta( ) {
		global $product;
		echo '<div class="lp-product-meta">';

		$product->list_attributes();

		echo '<ul class="shop-meta">';

		echo '<li><span>' . esc_html__( 'Release Date: ', 'learnplus' ) . '</span><span>' . get_the_modified_date('d F, Y') . '</span></li><hr>';

		$rating = '';
		if ( ! is_numeric( $rating ) ) {
			$rating = $product->get_average_rating();
		}

		if ( $rating > 0 ) {
			echo '<li><span class="avg-rating">' . esc_html__( 'Avarage Rating: ', 'learnplus' ) . '</span><span class="star-rating" title="' . sprintf( esc_attr__( 'Rated %s out of 5', 'learnplus' ), $rating ) . '">';
			echo '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . esc_html__( 'out of 5', 'learnplus' ) . '</span>';
			echo '</span></li><hr>';
		}

		echo '<li><span>' . esc_html__( 'Price: ', 'learnplus' ) . '</span><span>' . wc_price( $product->get_price() ) . '</span></li><hr>';

		$cat_count = sizeof( get_the_terms( $product->ID, 'product_cat' ) );
		$tag_count = sizeof( get_the_terms( $product->ID, 'product_tag' ) );
		echo $product->get_tags( ', ', '<li><span>' . _n( 'Tag: ', 'Tags: ', $tag_count, 'learnplus' ) . ' ', '</span></li><hr>' );
		echo $product->get_categories( ', ', '<li><span>' . _n( 'Category: ', 'Categories: ', $cat_count, 'learnplus' ) . ' ', '</span></li><hr>' );

		echo '</ul>';
		echo '</div>';

		do_action( 'woocommerce_' . $product->product_type . '_add_to_cart'  );
	}

	/**
	 * Wrap product content
	 * Close a div
	 *
	 * @since 1.0
	 */
	function close_product_inner() {
		echo '</div>';
	}


	/**
	 * Display description of sub-category in list view
	 *
	 * @param  object $category
	 */
	function show_cat_desc( $category ) {
		printf( '<div class="category-desc" itemprop="description">%s</div>', $category->description );
	}

	/**
	 * Change related products args to display in correct grid
	 *
	 * @param  array $args
	 *
	 * @return array
	 */
	function related_products_args( $args ) {
		$args['posts_per_page'] = 10;
		$args['columns']        = 4;

		return $args;
	}

	/**
	 * Change number of columns when display cross sells products
	 *
	 * @param  int $cl
	 * @return int
	 */
	function cross_sells_columns( $columns ) {
		return 4;
	}


	/**
	 * Change next and previous icon of pagination nav
	 *
	 * @since  1.0
	 */
	function pagination_args( $args ) {
		$args['prev_text'] = '&laquo;';
		$args['next_text'] = '&raquo;';

		return $args;
	}


	/**
	 * Display product rating
	 *
	 * @since 1.0
	 */
	function product_rating_html( $rating_html, $rating ) {
		$rating_html  = '<div class="star-rating" title="' . sprintf( esc_attr__( 'Rated %s out of 5', 'learnplus' ), $rating ) . '">';
		$rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . esc_html__( 'out of 5', 'learnplus' ) . '</span>';
		$rating_html .= '</div>';

		return $rating_html;
	}

	/**
	 * Display product stock
	 *
	 * @since 1.0
	 */
	function product_stock_html( $availability_html, $availability, $product ) {
		$availability      = $product->get_availability();
		$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . __( 'Availability: ', 'learnplus' )  . '<span>' . esc_html( $availability['availability'] ) . '</span>' . '</p>';

		return $availability_html;
	}

	/**
	 * Display upsell products
	 *
	 * @since 1.0
	 */
	function upsell_products() {
		woocommerce_upsell_display( 4, 4 );
	}
}
