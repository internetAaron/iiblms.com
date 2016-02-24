<?php

class LearnPlus_Course_Widget extends WP_Widget
{
	protected $default;

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->default = array(
			'title'   => '',
			'number'  => 9
		);

		parent::__construct(
			'course-widget',
			esc_html__( 'LearnPlus - Courses', 'learnplus' ),
			array(
				'classname'   => 'popular-courses',
				'description' => esc_html__( 'Displays a list of courses.', 'learnplus' ),
			)
		);
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 *
	 * @return void Echoes it's output
	 */
	function widget( $args, $instance )
	{
		$instance = wp_parse_args( $instance, $this->default );

		extract( $args );
		echo $before_widget;

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) )
			echo $before_title . $title . $after_title;

		$output = array();

		$query_args = array(
			'posts_per_page'      => $instance['number'],
			'post_type'           => 'sfwd-courses',
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $query_args );
		while ( $query->have_posts() ) : $query->the_post();
			$image = learnplus_get_image( array(
				'size'   => 'thumbnail',
				'format' => 'src',
				'echo'   => false,
			) );
			$output[] = sprintf( '<li><a href="%s"><img class="img-thumbnail" src="%s" alt="%s"></a></li>',
				esc_url( get_the_permalink() ),
				esc_url($image),
				esc_attr( get_the_title() )
			);
		endwhile;

		wp_reset_postdata();

		printf( '<ul>%s</ul>', implode( '', $output ) );

		echo $after_widget;
	}


	/**
	 * Update widget
	 *
	 * @param array $new_instance New widget settings
	 * @param array $old_instance Old widget settings
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$new_instance['title']    = strip_tags( $new_instance['title'] );
		$new_instance['number']  =  intval( $new_instance['number'] );
		return $new_instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance
	 *
	 * @return array
	 */
	function form( $instance )
	{
		$instance = wp_parse_args( $instance, $this->default );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of courses', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" value="<?php echo esc_attr( $instance['number'] ); ?>" />
		</p>
		<?php
	}
}
