<?php
/**
 * Register Showcase CPT and meta boxes for it
 *
 * @package TA Portfolio Management
 */

/**
 * Class LearnPlus_Portfolio
 */
class LearnPlus_Portfolio_Showcase {
	/**
	 * Construction function
	 *
	 * @since 1.0.0
	 *
	 * @return LearnPlus_Portfolio_Showcase
	 */
	public function __construct() {
		// Register custom post type and custom taxonomy
		add_action( 'init', array( $this, 'register_post_type' ) );

		// Handle post columns
		add_filter( 'manage_portfolio_showcase_posts_columns', array( $this, 'register_custom_columns' ) );
		add_action( 'manage_portfolio_showcase_posts_custom_column', array( $this, 'manage_custom_columns' ), 10, 2 );

		// Add meta box
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		global $post_type;

		if ( $post_type == 'portfolio_showcase' ) {
			wp_enqueue_script( 'learnplus-portfolio-showcase', LEARNPLUS_PORTFOLIO_URL . '/js/showcase.js', array( 'jquery'), LEARNPLUS_PORTFOLIO_VER, true );

		}
	}

	/**
	 * Register portfolio showcase post type
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_post_type() {
		// Return if post type is exists
		if ( post_type_exists( 'portfolio_showcase' ) ) {
			return;
		}

		$labels = array(
			'name'               => _x( 'Showcase', 'Post Type General Name', 'learnplus-portfolio' ),
			'singular_name'      => _x( 'Showcase', 'Post Type Singular Name', 'learnplus-portfolio' ),
			'menu_name'          => __( 'Showcase', 'learnplus-portfolio' ),
			'parent_item_colon'  => __( 'Parent Showcase', 'learnplus-portfolio' ),
			'all_items'          => __( 'Showcases', 'learnplus-portfolio' ),
			'view_item'          => __( 'View Showcase', 'learnplus-portfolio' ),
			'add_new_item'       => __( 'Add New Showcase', 'learnplus-portfolio' ),
			'add_new'            => __( 'Add New', 'learnplus-portfolio' ),
			'edit_item'          => __( 'Edit Showcase', 'learnplus-portfolio' ),
			'update_item'        => __( 'Update Showcase', 'learnplus-portfolio' ),
			'search_items'       => __( 'Search Showcase', 'learnplus-portfolio' ),
			'not_found'          => __( 'Not found', 'learnplus-portfolio' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'learnplus-portfolio' ),
		);
		$args   = array(
			'label'               => __( 'Showcase', 'learnplus-portfolio' ),
			'description'         => __( 'Create showcase shortcode', 'learnplus-portfolio' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=portfolio_project',
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'rewrite'             => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'query_var'           => false,
			'capability_type'     => 'post',
		);
		register_post_type( 'portfolio_showcase', $args );
	}

	/**
	 * Add custom column to manage portfolio screen
	 * Add shortcode column
	 *
	 * @since  1.0.0
	 *
	 * @param  array $columns Default columns
	 *
	 * @return array
	 */
	public function register_custom_columns( $columns ) {
		$columns['shortcode'] = __( 'Shortcode', 'learnplus-portfolio' );

		return $columns;
	}

	/**
	 * Handle custom column display
	 *
	 * @since  1.0.0
	 *
	 * @param  string $column
	 * @param  int    $post_id
	 *
	 * @return void
	 */
	public function manage_custom_columns( $column, $post_id ) {
		if ( 'shortcode' == $column ) {
			echo '[learnplus_portfolio_showcase id="' . $post_id . '"]';
		}
	}

	/**
	 * Add portfolio showcase settings meta box
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box( 'portfolio-showcase-options', __( 'Showcase Options', 'learnplus-portfolio' ), array( $this, 'showcase_meta_box' ), 'portfolio_showcase', 'normal', 'high' );
		add_meta_box( 'portfolio-showcase-shortcode', __( 'Shortcode', 'learnplus-portfolio' ), array( $this, 'shortcode_meta_box' ), 'portfolio_showcase', 'side', 'high' );
	}

	/**
	 * Display portfolio showcase settings meta box
	 *
	 * @since  1.0.0
	 *
	 * @param  object $post
	 *
	 * @return void
	 */
	public function showcase_meta_box( $post ) {
        $cats = get_post_meta($post->ID, '_portfolio_showcase_cat', true);
        $limit = get_post_meta($post->ID, '_portfolio_showcase_limit', true);
        $layout = get_post_meta($post->ID, '_portfolio_showcase_layout', true);
        $gutter = get_post_meta($post->ID, '_portfolio_showcase_gutter', true);
        $pagination = get_post_meta($post->ID, '_portfolio_showcase_pagination', true);
        $filter = get_post_meta($post->ID, '_portfolio_showcase_filter', true);
		$columns = get_post_meta($post->ID, '_portfolio_showcase_columns', true);
        $filter = $filter ? $filter : 'yes';

		wp_nonce_field( 'learnplus_portfolio_showcase_' . $post->ID, '_learnplusnonce' );
		?>

		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( 'Category to display', 'learnplus-portfolio' ) ?></th>
				<td>
					<ul id="portfolio-category-checklist" class="categorychecklist">
						<?php
						wp_terms_checklist(
							0,
							array(
								'selected_cats' => $cats,
								'taxonomy'      => 'portfolio_category',
								'checked_ontop' => false,
							)
						);
						?>
					</ul>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="portfolio-limit"><?php _e( 'Number of portfolio', 'learnplus-portfolio' ) ?></th>
				<td>
					<input type="number" name="_portfolio_showcase_limit" value="<?php echo $limit ? $limit : 8; ?>" id="portfolio-limit" size="3">
					<p class="description">
						<?php _e( 'With Metro layout, the value for the best view is 5, 6, 8 or a multiple of 8, eg: 8, 16, 24...', 'learnplus-portfolio' ) ?>
					</p>
				</td>

			</tr>

			<tr>
				<th scope="row"><label for="portfolio-layout"><?php _e( 'Showcase Layout', 'learnplus-portfolio' ) ?></th>
				<td>
					<select name="_portfolio_showcase_layout" id="portfolio-layout">
						<option value="fitRows" <?php selected( 'fitRows', $layout ) ?>><?php _e( 'Grid', 'learnplus-portfolio' ) ?></option>
						<option value="masonry" <?php selected( 'masonry', $layout ) ?>><?php _e( 'Masonry', 'learnplus-portfolio' ) ?></option>
						<option value="metro" <?php selected( 'metro', $layout ) ?>><?php _e( 'Metro', 'learnplus-portfolio' ) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="portfolio-filter"><?php _e( 'Portfolio Columns', 'learnplus-portfolio' ) ?></th>
				<td>
					<select name="_portfolio_showcase_columns" id="portfolio-columns"">
					<option value="4" <?php selected( '4', $columns ) ?>><?php _e( '4 columns', 'learnplus-portfolio' ) ?></option>
					<option value="3" <?php selected( '3', $columns ) ?>><?php _e( '3 columns', 'learnplus-portfolio' ) ?></option>
					</select>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="portfolio-gutter"><?php _e( 'Spacing', 'learnplus-portfolio' ) ?></th>
				<td>
					<input type="number" name="_portfolio_showcase_gutter" value="<?php echo $gutter ? $gutter : 0; ?>" id="portfolio-gutter" size="3"> px

					<p class="description">
						<?php _e( 'This is spacing between portfolios', 'learnplus-portfolio' ) ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="portfolio-filter"><?php _e( 'Enable Filter', 'learnplus-portfolio' ) ?></th>
				<td>
					<input type="checkbox" name="_portfolio_showcase_filter" value="yes" <?php checked( 'yes', $filter ) ?> id="portfolio-filter">

					<p class="description">
						<?php _e( 'Enable this feature to show the filter by category on top of portfolio showcase', 'learnplus-portfolio' ) ?>
					</p>
				</td>
			</tr>

			<tr id="learnplus-portfolio-pagination">
				<th scope="row"><label for="portfolio-pagination"><?php _e( 'Enable View More', 'learnplus-portfolio' ) ?>
				</th>
				<td>
					<input type="checkbox" name="_portfolio_showcase_pagination" value="yes" <?php checked( 'yes', $pagination ) ?> id="portfolio-pagination">

					<p class="description">
						<?php _e( 'Enable this feature to show view more button at the bottom of portfolio showcase', 'learnplus-portfolio' ) ?>
					</p>
				</td>
			</tr>

			<?php do_action( 'learnplus_portfolio_showcase_fields', $post ); ?>
		</table>

		<?php
	}

	/**
	 * Display generated shortcode
	 *
	 * @since  1.0.0
	 *
	 * @param  object $post
	 *
	 * @return void
	 */
	public function shortcode_meta_box( $post ) {
		echo '[learnplus_portfolio_showcase id="' . $post->ID . '"]';
		echo '<br><br>';
		echo '<p class="description">' . __( 'Copy and paste this shortcode to any page you want to display portfolio showcase.', 'learnplus-portfolio' ) . '</p>';
	}

	/**
	 * Save portfolio details
	 *
	 * @since  1.0.0
	 *
	 * @param  int $post_id
	 *
	 * @return void
	 */
	public function save_meta_boxes( $post_id ) {
		// Verify nonce
		if ( ( get_post_type() != 'portfolio_showcase' ) || ( isset( $_POST['_learnplusnonce'] ) && ! wp_verify_nonce( $_POST['_learnplusnonce'], 'learnplus_portfolio_showcase_' . $post_id ) ) ) {
			return;
		}

		// Verify user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Update post meta
		update_post_meta( $post_id, '_portfolio_showcase_cat', $_POST['tax_input']['portfolio_category'] );
		update_post_meta( $post_id, '_portfolio_showcase_limit', absint( $_POST['_portfolio_showcase_limit'] ) );
		update_post_meta( $post_id, '_portfolio_showcase_layout', $_POST['_portfolio_showcase_layout'] );
		update_post_meta( $post_id, '_portfolio_showcase_gutter', absint( $_POST['_portfolio_showcase_gutter'] ) );
		update_post_meta( $post_id, '_portfolio_showcase_pagination', isset( $_POST['_portfolio_showcase_pagination'] ) ? $_POST['_portfolio_showcase_pagination'] : 'no' );
		update_post_meta( $post_id, '_portfolio_showcase_filter', isset( $_POST['_portfolio_showcase_filter'] ) ? $_POST['_portfolio_showcase_filter'] : 'no' );
        update_post_meta( $post_id, '_portfolio_showcase_columns', $_POST['_portfolio_showcase_columns'] );
		do_action( 'learnplus_portfolio_showcase_save_metadata', $post_id );
	}
}
