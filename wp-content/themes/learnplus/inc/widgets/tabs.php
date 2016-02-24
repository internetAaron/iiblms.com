<?php
/**
 * LearnPlus_Tabs_Widget widget class
 *
 * @since 1.0
 */
class LearnPlus_Tabs_Widget extends WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Class constructor
	 * Set up the widget
	 */
	function __construct() {
		$this->defaults = array(
			'popular_show'       => 1,
			'popular_title'      => esc_html__( 'Popular', 'learnplus' ),
			'popular_limit'      => 5,
			'popular_thumb'      => 1,
			'popular_thumb_size' => 'learnplus-widget-thumb',
			'popular_comments'   => 0,
			'popular_date'       => 1,
			'recent_show'        => 1,
			'recent_title'       => esc_html__( 'Recent', 'learnplus' ),
			'recent_limit'       => 5,
			'recent_thumb'       => 1,
			'recent_thumb_size'  => 'learnplus-widget-thumb',
			'recent_comments'    => 0,
			'recent_date'        => 1,
			'comments_show'      => 1,
			'comments_title'     => esc_html__( 'Comments', 'learnplus' ),
			'comments_limit'     => 5,
		);

		parent::__construct(
			'tabs-widget',
			esc_html__( 'LearnPlus - Tabs', 'learnplus' ),
			array(
				'classname'   => 'tabs-widget',
				'description' => esc_html__( 'Display most popular posts, recent posts, recent comments in tabbed widget.', 'learnplus' ),
			),
			array( 'width' => 780 )
		);

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments
	 * @param array $instance Saved values from database
	 *
	 * @return void
	 */
	function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		extract( $args );

		echo $before_widget;

		echo '<div class="tabs">';
		echo '<ul class="tabs-nav">';
		if ( $instance['popular_show'] ) {
			echo '<li class="tab-populars"><a href="#" class="active">' . $instance['popular_title'] . '</a></li>';
		}

		if ( $instance['recent_show'] ) {
			echo '<li class="tab-recents"><a href="#">' . $instance['recent_title'] . '</a></li>';
		}

		if ( $instance['comments_show'] ) {
			echo '<li class="tab-comments"><a href="#">' . $instance['comments_title'] . '</a></li>';
		}
		echo '</ul>';
		?>

		<?php if ( $instance['popular_show'] ) : ?>

			<?php
			$class = $instance['popular_thumb'] ? '' : 'no-thumbnail';
			?>
			<div class="tab-popular-posts tabs-panel active">
				<?php
				$popular_posts = new WP_Query( array(
					'posts_per_page' => $instance['popular_limit'],
					'orderby'        => 'comment_count',
					'order'          => 'DESC'
				) );
				while ( $popular_posts->have_posts() ): $popular_posts->the_post(); ?>

					<article class="popular-post <?php echo esc_attr( $class ) ?>">
						<?php
						if ( $instance['popular_thumb'] ) {
							$src = learnplus_get_image( array(
								'size'   => $instance['popular_thumb_size'],
								'format' => 'src',
								'echo'   => false,
							) );

							if ( $src ) {
								printf(
									'<a class="widget-thumb" href="%s" title="%s"><img src="%s" alt="%s"></a>',
									esc_url( get_permalink() ),
									the_title_attribute( 'echo=0' ),
									esc_url( $src ),
									the_title_attribute( 'echo=0' )
								);
							}
						}
						?>
						<div class="post-text">
							<a class="post-title" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'learnplus' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
							<?php
							if ( $instance['popular_date'] ) {
								echo '<time class="post-date" datetime="' . esc_attr( get_the_time( 'c' ) ) . '">' . get_the_time( 'd F Y' ) . '</time>';
							}

							if ( $instance['popular_comments'] ) {
								echo '<span class="post-comments">' . sprintf( esc_html__( '%s Comments', 'learnplus' ), get_comments_number() ) . '</span>';
							}
							?>
						</div>
					</article>

				<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>

		<?php endif; ?>

		<?php if ( $instance['recent_show'] ) : ?>
			<div class="tab-recent-posts tabs-panel">
				<?php
				the_widget(
					'LearnPlus_Recent_Posts_Widget',
					array(
						'limit'      => $instance['recent_limit'],
						'thumb'      => $instance['recent_thumb'],
						'thumb_size' => $instance['recent_thumb_size'],
						'date'       => $instance['recent_date'],
						'comments'   => $instance['recent_comments'],
						'readmore'   => 0
					),
					array(
						'before_widget' => '',
						'after_widget'  => '',
					)
				);
				?>
			</div>

		<?php endif; ?>

		<?php
		if ( $instance['comments_show'] ) {
			echo '<div class="tab-comments tabs-panel">';
			$comments = get_comments( array(
				'status' => 'approve',
				'number' => $instance['comments_limit'],
			) );

			foreach ( $comments as $comment ) {
				echo sprintf(
					'<div class="comment">
						<p class="comment-summary">%s <span class="author-comment">%s %s</span></p>
						<span class="post-comment">%s <a href="%s" title="%s">%s</a></span>
					</div>',
					wp_trim_words( strip_tags( $comment->comment_content ), 10 ),
					esc_html__( 'by', 'learnplus' ),
					$comment->comment_author,
					esc_html__( 'on', 'learnplus' ),
					get_comments_link( $comment->comment_post_ID ),
					get_the_title( $comment->comment_post_ID ),
					wp_trim_words( strip_tags( get_the_title( $comment->comment_post_ID ) ), 7 )
				);
			}

			echo '</div>';
		}

		echo '</div>';
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array Updated safe values to be saved
	 */
	function update( $new_instance, $old_instance ) {
		$new_instance['popular_title']      = strip_tags( $new_instance['popular_title'] );
		$new_instance['popular_limit']      = intval( $new_instance['popular_limit'] );
		$new_instance['popular_show']       = ! empty( $new_instance['popular_show'] );
		$new_instance['popular_comments']   = ! empty( $new_instance['popular_comments'] );
		$new_instance['popular_thumb']      = ! empty( $new_instance['popular_thumb'] );
		$new_instance['popular_date']       = ! empty( $new_instance['popular_date'] );

		$new_instance['recent_title']       = strip_tags( $new_instance['recent_title'] );
		$new_instance['recent_limit']       = intval( $new_instance['recent_limit'] );
		$new_instance['recent_show']        = ! empty( $new_instance['recent_show'] );
		$new_instance['recent_comments']    = ! empty( $new_instance['recent_comments'] );
		$new_instance['recent_thumb']       = ! empty( $new_instance['recent_thumb'] );
		$new_instance['recent_date']        = ! empty( $new_instance['recent_date'] );

		$new_instance['comments_title']     = strip_tags( $new_instance['comments_title'] );
		$new_instance['comments_limit']     = intval( $new_instance['comments_limit'] );
		$new_instance['comments_show']      = ! empty( $new_instance['comments_show'] );

		return $new_instance;
	}

	/**
	 * Displays the widget options
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	function form( $instance ) {
		// Merge with defaults
		$instance = wp_parse_args( $instance, $this->defaults );

		?>
		<div style="width: 250px; float: left; margin-right: 10px;">
			<p><strong><?php esc_html_e( 'Popular Posts', 'learnplus' ); ?></strong></p>

			<p>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'popular_show' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_show' ) ); ?>" value="1" <?php checked( 1, $instance['popular_show'] ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_show' ) ); ?>"><?php esc_html_e( 'Show Popular Tab', 'learnplus' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_title' ) ); ?>"><?php esc_html_e( 'Title', 'learnplus' ); ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'popular_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_title' ) ); ?>" value="<?php echo esc_attr( $instance['popular_title'] ); ?>" />
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_limit' ) ); ?>" type="text" size="2" value="<?php echo esc_attr( $instance['popular_limit'] ); ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_limit' ) ); ?>"><?php esc_html_e( 'Number Of Posts', 'learnplus' ); ?></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_date' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_date'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_date' ) ); ?>"><?php esc_html_e( 'Show Date', 'learnplus' ); ?></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_comments' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_comments'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_comments' ) ); ?>"><?php esc_html_e( 'Show Comment Number', 'learnplus' ); ?></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'popular_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular_thumb' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['popular_thumb'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'popular_thumb' ) ); ?>"><?php esc_html_e( 'Show Thumbnail', 'learnplus' ); ?></label>
			</p>

			<?php if ( method_exists( 'LearnPlus_Recent_Posts_Widget', 'get_image_sizes' ) ) : ?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'popular_thumb_size' ) ); ?>"><?php esc_html_e( 'Thumbnail Size', 'learnplus' ); ?></label>
					<select name="<?php echo esc_attr( $this->get_field_name( 'popular_thumb_size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'popular_thumb_size' ) ); ?>" class="widefat">
						<?php foreach ( $sizes = LearnPlus_Recent_Posts_Widget::get_image_sizes() as $name => $size ) : ?>
							<option value="<?php echo esc_attr( $name ) ?>" <?php selected( $name, $instance['popular_thumb_size'] ) ?>><?php echo ucfirst( $name ) . " ({$size['width']}x{$size['height']})" ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endif; ?>
		</div>

		<div style="width: 250px; float: left; margin-right: 10px;">
			<p><strong><?php esc_html_e( 'Recent Posts', 'learnplus' ); ?></strong></p>

			<p>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'recent_show' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_show' ) ); ?>" value="1" <?php checked( 1, $instance['recent_show'] ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_show' ) ); ?>"><?php esc_html_e( 'Show Recent Posts Tab', 'learnplus' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_title' ) ); ?>"><?php esc_html_e( 'Title', 'learnplus' ); ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'recent_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_title' ) ); ?>" value="<?php echo esc_attr( $instance['recent_title'] ); ?>" />
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_limit' ) ); ?>" type="text" size="2" value="<?php echo intval( $instance['recent_limit'] ); ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_limit' ) ); ?>"><?php esc_html_e( 'Number Of Posts', 'learnplus' ); ?></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_date' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_date'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_date' ) ); ?>"><?php esc_html_e( 'Show Date', 'learnplus' ); ?></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_comments' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_comments'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_comments' ) ); ?>"><?php esc_html_e( 'Show Comment Number', 'learnplus' ); ?></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'recent_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent_thumb' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['recent_thumb'] ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'recent_thumb' ) ); ?>"><?php esc_html_e( 'Show Thumbnail', 'learnplus' ); ?></label>
			</p>

			<?php if ( method_exists( 'LearnPlus_Recent_Posts_Widget', 'get_image_sizes' ) ) : ?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'recent_thumb_size' ) ); ?>"><?php esc_html_e( 'Thumbnail Size', 'learnplus' ); ?></label>
					<select name="<?php echo esc_attr( $this->get_field_name( 'recent_thumb_size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'recent_thumb_size' ) ); ?>" class="widefat">
						<?php foreach ( $sizes = LearnPlus_Recent_Posts_Widget::get_image_sizes() as $name => $size ) : ?>
							<option value="<?php echo esc_attr( $name ) ?>" <?php selected( $name, $instance['recent_thumb_size'] ) ?>><?php echo ucfirst( $name ) . " ({$size['width']}x{$size['height']})" ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endif; ?>
		</div>

		<div style="width: 250px;float:left;">
			<p><strong><?php esc_html_e( 'Recent Comments', 'learnplus' ); ?></strong></p>

			<p>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'comments_show' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'comments_show' ) ); ?>" value="1" <?php checked( 1, $instance['comments_show'] ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'comments_show' ) ); ?>"><?php esc_html_e( 'Show Comments Tab', 'learnplus' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'comments_title' ) ); ?>"><?php esc_html_e( 'Title', 'learnplus' ); ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'comments_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'comments_title' ) ); ?>" value="<?php echo esc_attr( $instance['comments_title'] ); ?>" />
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'comments_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'comments_limit' ) ); ?>" type="text" value="<?php echo intval( $instance['comments_limit'] ); ?>" size="3">
				<label for="<?php echo esc_attr( $this->get_field_id( 'comments_limit' ) ); ?>"><?php esc_html_e( 'Number of comments to show', 'learnplus' ); ?></label>
			</p>
		</div>
		<div class="clear"></div>
	<?php
	}
}
