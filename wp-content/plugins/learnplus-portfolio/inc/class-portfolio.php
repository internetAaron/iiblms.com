<?php
/**
 * Register Portfolio CPT and meta boxes for it
 * Post type name and meta key names are follow 'content type standard',
 * see more about it here: https://github.com/justintadlock/content-type-standards/wiki/Content-Type:-Portfolio
 *
 * @package LearnPlus Portfolio Management
 */

/**
 * Class LearnPlus_Portfolio
 */
class LearnPlus_Portfolio {
	/**
	 * Construction function
	 *
	 * @since 1.0.0
	 *
	 * @return LearnPlus_Portfolio
	 */
	public function __construct() {
		// Register custom post type and custom taxonomy
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );

		// Handle post columns
		add_filter( 'manage_portfolio_project_posts_columns', array( $this, 'register_custom_columns' ) );
		add_action( 'manage_portfolio_project_posts_custom_column', array( $this, 'manage_custom_columns' ), 10, 2 );

		// Add meta box
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_metadata' ) );

        // Enqueue style and javascript
        add_action( 'admin_print_styles-post.php', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_print_styles-post-new.php', array( $this, 'enqueue_scripts' ) );

        // Handle ajax callbacks
        add_action( 'wp_ajax_learnplus_portfolio_attach_images', array( $this, 'ajax_attach_images' ) );
        add_action( 'wp_ajax_learnplus_portfolio_order_images', array( $this, 'ajax_order_images' ) );
        add_action( 'wp_ajax_learnplus_portfolio_delete_image', array( $this, 'ajax_delete_image' ) );
	}

	/**
	 * Register portfolio post type
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_post_type() {
		// Return if post type is exists
		if ( post_type_exists( 'portfolio_project' ) ) {
			return;
		}

		$labels = array(
			'name'               => _x( 'Portfolio', 'Post Type General Name', 'learnplus-portfolio' ),
			'singular_name'      => _x( 'Portfolio', 'Post Type Singular Name', 'learnplus-portfolio' ),
			'menu_name'          => __( 'Portfolios', 'learnplus-portfolio' ),
			'parent_item_colon'  => __( 'Parent Portfolio', 'learnplus-portfolio' ),
			'all_items'          => __( 'All Portfolios', 'learnplus-portfolio' ),
			'view_item'          => __( 'View Portfolio', 'learnplus-portfolio' ),
			'add_new_item'       => __( 'Add New Portfolio', 'learnplus-portfolio' ),
			'add_new'            => __( 'Add New', 'learnplus-portfolio' ),
			'edit_item'          => __( 'Edit Portfolio', 'learnplus-portfolio' ),
			'update_item'        => __( 'Update Portfolio', 'learnplus-portfolio' ),
			'search_items'       => __( 'Search Portfolio', 'learnplus-portfolio' ),
			'not_found'          => __( 'Not found', 'learnplus-portfolio' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'learnplus-portfolio' ),
		);
		$args   = array(
			'label'               => __( 'Portfolio', 'learnplus-portfolio' ),
			'description'         => __( 'Create and manage all works', 'learnplus-portfolio' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'rewrite'             => array( 'slug' => apply_filters( 'learnplus_portfolio_slug', 'portfolio' ) ),
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'menu_icon'           => 'dashicons-portfolio',
		);
		register_post_type( 'portfolio_project', $args );
	}

	/**
	 * Register portfolio category taxonomy
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_taxonomy() {
		$labels = array(
			'name'              => __( 'Categories', 'learnplus-portfolio' ),
			'singular_name'     => __( 'Category', 'learnplus-portfolio' ),
			'search_items'      => __( 'Search Categories', 'learnplus-portfolio' ),
			'all_items'         => __( 'All Categories', 'learnplus-portfolio' ),
			'parent_item'       => __( 'Parent Category', 'learnplus-portfolio' ),
			'parent_item_colon' => __( 'Parent Category:', 'learnplus-portfolio' ),
			'edit_item'         => __( 'Edit Category', 'learnplus-portfolio' ),
			'update_item'       => __( 'Update Category', 'learnplus-portfolio' ),
			'add_new_item'      => __( 'Add New Category', 'learnplus-portfolio' ),
			'new_item_name'     => __( 'New Category Name', 'learnplus-portfolio' ),
			'menu_name'         => __( 'Categories', 'learnplus-portfolio' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'rewrite'           => array(
				'slug'         => apply_filters( 'learnplus_portfolio_category_slug', 'portfolio-category' ),
				'with_front'   => true,
				'hierarchical' => false,
			),
		);

		register_taxonomy( 'portfolio_category', array( 'portfolio_project' ), $args );
	}

	/**
	 * Add custom column to manage portfolio screen
	 * Add Thumbnail column
	 *
	 * @since  1.0.0
	 *
	 * @param  array $columns Default columns
	 *
	 * @return array
	 */
	public function register_custom_columns( $columns ) {
		$cb              = array_slice( $columns, 0, 1 );
		$cb['thumbnail'] = __( 'Thumbnail', 'learnplus-portfolio' );

		return array_merge( $cb, $columns );
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
		if ( 'thumbnail' == $column ) {
			echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
		}
	}

    public function enqueue_scripts() {
        global $post_type;

        if ( $post_type == 'portfolio_project' ) {
            wp_enqueue_media();
            wp_enqueue_style( 'learnplus-portfolio', LEARNPLUS_PORTFOLIO_URL . '/css/admin.css' );
            wp_enqueue_script( 'learnplus-portfolio', LEARNPLUS_PORTFOLIO_URL . '/js/admin.js', array( 'jquery', 'underscore', 'jquery-ui-sortable' ), LEARNPLUS_PORTFOLIO_VER, true );

            wp_localize_script(
                'learnplus-portfolio', 'learnplusPortfolio', array(
                    'frameTitle' => __( 'Select Or Upload Images', 'learnplus-portfolio' ),
                )
            );
        }
    }

	/**
	 * Add portfolio details meta box
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box( 'portfolio-type', __( 'Portfolio Type', 'learnplus-portfolio' ), array( $this, 'portfolio_type_meta_box' ), 'portfolio_project', 'normal', 'high' );
		add_meta_box( 'portfolio-gallery', __( 'Portfolio Gallery', 'learnplus-portfolio' ), array( $this, 'portfolio_gallery_meta_box' ), 'portfolio_project', 'normal', 'high' );
		add_meta_box( 'portfolio-detail', __( 'Portfolio Details', 'learnplus-portfolio' ), array( $this, 'portfolio_detail_meta_box' ), 'portfolio_project', 'normal', 'high' );
	}

	/**
	 * Display portfolio details meta box
	 * It contains project url
	 *
	 * @since  1.0.0
	 *
	 * @param  object $post
	 *
	 * @return void
	 */
	public function portfolio_detail_meta_box( $post ) {
		wp_nonce_field( 'learnplus_portfolio_details_' . $post->ID, 'learnplus_nonce' );
		?>

		<p>
			<label for="project-client"><?php _e( 'Project Client', 'learnplus-portfolio' ) ?></label><br>
			<input type="text" name="_project_client" value="<?php echo get_post_meta( $post->ID, '_project_client', true ) ?>" id="project-client" class="widefat">
		</p>

		<p>
			<label for="project-author"><?php _e( 'Author', 'learnplus-portfolio' ) ?></label><br>
			<input type="text" name="_project_author" value="<?php echo get_post_meta( $post->ID, '_project_author', true ) ?>" id="project-author" class="widefat">
		</p>

		<p>
			<?php _e( 'Project Social', 'learnplus-portfolio' ) ?>
		</p>

		<p>
			<label for="project-facebook"><?php _e( 'Facebook URL', 'learnplus-portfolio' ) ?></label><br>
			<input type="text" name="_project_facebook" value="<?php echo get_post_meta( $post->ID, '_project_facebook', true ) ?>" id="project-facebook" class="widefat">
		</p>

		<p>
			<label for="project-linkedin"><?php _e( 'Linkedin URL', 'learnplus-portfolio' ) ?></label><br>
			<input type="text" name="_project_linkedin" value="<?php echo get_post_meta( $post->ID, '_project_linkedin', true ) ?>" id="project-linkedin" class="widefat">
		</p>

		<p>
			<label for="project-instagram"><?php _e( 'Instagram URL', 'learnplus-portfolio' ) ?></label><br>
			<input type="text" name="_project_instagram" value="<?php echo get_post_meta( $post->ID, '_project_instagram', true ) ?>" id="project-instagram" class="widefat">
		</p>

		<p>
			<label for="project-twitter"><?php _e( 'Twitter URL', 'learnplus-portfolio' ) ?></label><br>
			<input type="text" name="_project_twitter" value="<?php echo get_post_meta( $post->ID, '_project_twitter', true ) ?>" id="project-twitter" class="widefat">
		</p>

		<p>
			<label for="project-pinterest"><?php _e( 'Pinterest URL', 'learnplus-portfolio' ) ?></label><br>
			<input type="text" name="_project_pinterest" value="<?php echo get_post_meta( $post->ID, '_project_pinterest', true ) ?>" id="project-pinterest" class="widefat">
		</p>

		<?php do_action( 'learnplus_portfolio_details_fields', $post ); ?>

		<?php
	}

	/**
	 * Display portfolio gallery meta box
	 * It contains project url
	 *
	 * @since  1.0.0
	 *
	 * @param  object $post
	 *
	 * @return void
	 */
	public function portfolio_gallery_meta_box( $post ) {
		wp_nonce_field( 'learnplus_portfolio_gallery_' . $post->ID, 'learnplus_nonce' );
		?>

		<ul id="project-images" class="images-holder" data-nonce="<?php echo wp_create_nonce( 'learnplus-portfolio-images-' . $post->ID ) ?>">
			<?php
			foreach ( $images = array_filter( (array) get_post_meta( $post->ID, 'images', false ) ) as $image ) {
				echo $this->gallery_item( $image );
			}
			?>
		</ul>
		<input type="button" id="learnplus-images-upload" class="button" value="<?php _e( 'Select Or Upload Images', 'learnplus-portfolio' ) ?>" data-nonce="<?php echo wp_create_nonce( 'learnplus-upload-images-' . $post->ID ) ?>">

		<?php
	}

	/**
	 * Display portfolio type meta box
	 * It contains project url
	 *
	 * @since  1.0.0
	 *
	 * @param  object $post
	 *
	 * @return void
	 */
	public function portfolio_type_meta_box( $post ) {
		wp_nonce_field( 'learnplus_portfolio_type_' . $post->ID, 'learnplus_nonce' );
		?>

		<p>
			<?php _e( 'Choose portfolio type to view pop up', 'learnplus-portfolio' ) ?>
		</p>

		<?php
		$type = get_post_meta( $post->ID, '_project_type', true );
		$image = $type == 0 ? 'checked="checked"' : '';
		$gallery = $type == 'gallery' ? 'checked="checked"' : '';
		$youtube = $type == 'youtube' ? 'checked="checked"' : '';
		$vimeo = $type == 'vimeo' ? 'checked="checked"' : '';
		?>

		<p>
			<input type="radio" <?php echo esc_attr( $image ) ?> name="_project_type" value="image" id="project-image" class="widefat">
			<label for="project-image"><?php _e( 'Single Image', 'learnplus-portfolio' ) ?></label><br>
		</p>

		<p>
			<input type="radio" <?php echo esc_attr( $gallery ) ?> name="_project_type" value="gallery" id="project-gallery" class="widefat">
			<label for="project-gallery"><?php _e( 'Gallery', 'learnplus-portfolio' ) ?></label><br>
		</p>
		<p>
			<input type="radio" <?php echo esc_attr( $vimeo ) ?> name="_project_type" value="vimeo" id="project-vimeo" class="widefat">
			<label for="project-vimeo"><?php _e( 'Vimeo (Use the simple url such as: http://www.vimeo.com/your_video_id)', 'learnplus-portfolio' ) ?></label><br>
			<br>
			<input type="text" placeholder="<?php _e( 'Vimeo URL', 'learnplus-portfolio' ) ?>" name="_project_vimeo_url" value="<?php echo get_post_meta( $post->ID, '_project_vimeo_url', true ) ?>" id="project-vimeo-url" class="widefat">
		</p>


		<p>
			<input type="radio" <?php echo esc_attr( $youtube ) ?> name="_project_type" value="youtube" id="project-youtube" class="widefat">
			<label for="project-youtube"><?php _e( 'Youtube (Use the simple url such as: http://www.youtu.be/your_video_id)', 'learnplus-portfolio' ) ?></label><br>
			<br>
			<input type="text" placeholder="<?php _e( 'Youtube URL', 'learnplus-portfolio' ) ?>" name="_project_youtube_url" value="<?php echo get_post_meta( $post->ID, '_project_youtube_url', true ) ?>" id="project-youtube-url" class="widefat">
		</p>


		<?php
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
	public function save_metadata( $post_id ) {
		// Verify nonce
		if ( ( get_post_type() != 'portfolio_project' ) || ( isset( $_POST['learnplus_nonce'] ) && ! wp_verify_nonce( $_POST['learnplus_nonce'], 'learnplus_portfolio_details_' . $post_id ) ) ) {
			return;
		}

		// Verify user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Update post meta
		update_post_meta( $post_id, '_project_client', esc_html( $_POST['_project_client'] ) );
		update_post_meta( $post_id, '_project_author', esc_html( $_POST['_project_author'] ) );
		update_post_meta( $post_id, '_project_facebook', esc_html( $_POST['_project_facebook'] ) );
		update_post_meta( $post_id, '_project_linkedin', esc_html( $_POST['_project_linkedin'] ) );
		update_post_meta( $post_id, '_project_instagram', esc_html( $_POST['_project_instagram'] ) );
		update_post_meta( $post_id, '_project_pinterest', esc_html( $_POST['_project_pinterest'] ) );
		update_post_meta( $post_id, '_project_twitter', esc_html( $_POST['_project_twitter'] ) );

		// Update post meta
		update_post_meta( $post_id, '_project_type', esc_html( $_POST['_project_type'] ) );
		update_post_meta( $post_id, '_project_youtube_url', esc_html( $_POST['_project_youtube_url'] ) );
		update_post_meta( $post_id, '_project_vimeo_url', esc_html( $_POST['_project_vimeo_url'] ) );

		do_action( 'learnplus_portfolio_save_metadata', $post_id );
	}

    /**
     * Get html markup for one gallery's image item
     *
     * @param  int $attachment_id
     *
     * @return string
     */
    public function gallery_item( $attachment_id ) {
        return sprintf(
            '<li id="item_%1$s">
				%5$s
				<p class="image-actions">
					<a title="%3$s" class="learnplus-portfolio-edit-image" href="%2$s" target="_blank">%3$s</a> |
					<a title="%4$s" class="learnplus-portfolio-delete-image" href="#" data-attachment_id="%1$s" data-nonce="%6$s">×</a>
				</p>
			</li>',
            $attachment_id,
            get_edit_post_link( $attachment_id ),
            __( 'Edit', 'learnplus-portfolio' ),
            __( 'Delete', 'learnplus-portfolio' ),
            wp_get_attachment_image( $attachment_id ),
            wp_create_nonce( 'learnplus-portfolio-delete-image-' . $attachment_id )
        );
    }

    /**
     * Ajax callback for attaching media to field
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function ajax_attach_images() {
        $post_id        = isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0;
        $attachment_ids = isset( $_POST['attachment_ids'] ) ? $_POST['attachment_ids'] : array();

        check_ajax_referer( 'learnplus-upload-images-' . $post_id );
        $items = '';

        foreach ( $attachment_ids as $attachment_id ) {
            add_post_meta( $post_id, 'images', $attachment_id, false );
            $items .= $this->gallery_item( $attachment_id );
        }
        wp_send_json_success( $items );
    }

    /**
     * Ajax callback for ordering images
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function ajax_order_images() {
        $order   = isset( $_POST['order'] ) ? $_POST['order'] : 0;
        $post_id = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;

        check_ajax_referer( 'learnplus-portfolio-images-' . $post_id );

        parse_str( $order, $items );

        delete_post_meta( $post_id, 'images' );
        foreach ( $items['item'] as $item ) {
            add_post_meta( $post_id, 'images', $item, false );
        }
        wp_send_json_success();
    }

    /**
     * Ajax callback for deleting an image from portfolio's gallery
     *
     * @since  1.0.0
     *
     * @return void
     */
    function ajax_delete_image() {
        $post_id       = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
        $attachment_id = isset( $_POST['attachment_id'] ) ? intval( $_POST['attachment_id'] ) : 0;

        check_ajax_referer( 'learnplus-portfolio-delete-image-' . $attachment_id );

        delete_post_meta( $post_id, 'images', $attachment_id );
        wp_send_json_success();
    }
}
