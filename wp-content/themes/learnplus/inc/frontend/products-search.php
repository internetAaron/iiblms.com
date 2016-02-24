<?php
/**
 * LearnPlus_Product_Search class
 *
 * @since 1.0
 */
class LearnPlus_Products_Search {

	/**
	 * Class constructor
	 *
	 * @return LearnPlus_Product_Search
	 */
	function __construct() {

		// Use ajax search product
		add_action( 'wp_ajax_search_products', array( $this, 'instance_search_result' ) );
		add_action( 'wp_ajax_nopriv_search_products', array( $this, 'instance_search_result' ) );
		add_action( 'wp_footer', array( $this,'learnplus_off_canvas_search') );
	}

	/**
	 * Add off canvas search to footer
	 *
	 * @since 1.0.0
	 */
	function learnplus_off_canvas_search() {
		$post_type = learnplus_theme_option( 'search_post_type' );
		$search_text = esc_html__( 'Search Courses', 'learnplus' );
		if( ! $post_type ) {
			$post_type = 'sfwd-courses';
		}

		if( $post_type != 'sfwd-courses' ) {
			$search_text = esc_html__( 'Search Products', 'learnplus' );
		}

		$search_text = apply_filters( 'learnplus_search_text', $search_text );
		?>
		<div id="search-panel" class="search-panel woocommerce fade">
			<div class="search-content">
				<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="instance-search">
					<input type="text" name="s" placeholder="<?php echo esc_attr( $search_text ); ?>" class="search-field">
					<input type="hidden" class="search-post-type" name="post_type" value="<?php echo esc_attr( $post_type ); ?>">
					<input type="submit" class="search-submit">
				</form>
				<a href="#" class="search-panel-close" id="search-panel-close">&#215;</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Search products
	 *
	 * @since 1.0
	 */
	function instance_search_result() {
		check_ajax_referer( '_learnplus_nonce', 'lpnonce' );

		$args = array(
			'post_type'      => trim( $_POST['postType']),
			'posts_per_page' => -1,
			's'              => trim( $_POST['term'] ),
		);

		$products = new WP_Query( $args );

		$count  = $products->found_posts;
		$response = array();

		if ( $products->have_posts() ) {
			$i = 1;
			while ( $products->have_posts() ) {
				$products->the_post();

				$response[] = array(
					'label' => get_the_title(),
					'value' => get_permalink(),
					'count' => $count,
					'price' => '',
					'thumb' => get_the_post_thumbnail( get_the_ID(), 'learnplus-course-thumb' ),
				);

				if( $i == 5 ) {
					break;
				}
				$i++;
			}
		}

		if ( empty( $response ) ) {
			$response[] = array(
				'label' => ' ',
				'value' => '#',
				'price' => '',
				'count' => 0,
				'thumb' => '',
			);
		}

		wp_send_json_success( $response );
		die();
	}

}
