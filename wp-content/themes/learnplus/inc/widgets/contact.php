<?php

class LearnPlus_Contact_Widget extends WP_Widget
{
	protected $default;

	/**
	 * Constructor
	 *
	 * @return LearnPlus_Contact_Widget
	 */
	function __construct()
	{
		$this->default = array(
			'title'   => '',
			'website'  => '',
			'url'    => '',
			'email'   => '',
			'phone'   => '',
			'fax'     => '',
			'address' => '',
		);

		parent::__construct(
			'contact-widget',
			esc_html__( 'LearnPlus - Contact', 'learnplus' ),
			array(
				'classname'   => 'contact-widget',
				'description' => esc_html__( 'Display contact details.', 'learnplus' ),
			),
			array( 'width' => 600 )
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

		if ( $instance['website'] ) {
			$output[] = sprintf( '<li><i class="fa fa-link"></i> <a href="%s">%s</a></li>',
			 	esc_url( $instance['url'] ),
			 	$instance['website']
			 );
		}

		if ( $instance['email'] ) {
			$output[] = sprintf( '<li><i class="fa fa-envelope"></i> <a href="mailto:%s">%s</a></li>',
			 	esc_url( $instance['email'] ),
			 	$instance['email']
			 );
		}

		if ( $instance['phone'] ) {
			$output[] = sprintf( '<li><i class="fa fa-phone"></i>%s</li>',
			 	$instance['phone']
			 );
		}

		if ( $instance['fax'] ) {
			$output[] = sprintf( '<li><i class="fa fa-fax"></i>%s</li>',
			 	$instance['fax']
			 );
		}
		if ( $instance['address'] ) {
			$output[] = sprintf( '<li><i class="fa fa-home"></i>%s</li>',
			 	$instance['address']
			 );
		}

		printf( '<ul class="contact-details">%s</ul>', implode( '', $output ) );

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
		$new_instance['website']  =  strip_tags( $new_instance['website'] );
		$new_instance['email']    = strip_tags( $new_instance['email'] );
		$new_instance['url']    = esc_url( $new_instance['url'] );
		$new_instance['phone']    = strip_tags(  $new_instance['phone'] );
		$new_instance['fax']     = strip_tags( $new_instance['fax'] );
		$new_instance['address']  = strip_tags( $new_instance['address'] );
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'website' ) ); ?>"><?php esc_html_e( 'Website', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'website' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'website' ) ); ?>" value="<?php echo esc_attr( $instance['website'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php esc_html_e( 'URL', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" value="<?php echo esc_attr( $instance['url'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" value="<?php echo esc_attr( $instance['email'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" value="<?php echo esc_attr( $instance['phone'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>"><?php esc_html_e( 'Fax', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fax' ) ); ?>" value="<?php echo esc_attr( $instance['fax'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" value="<?php echo esc_attr( $instance['address'] ); ?>" />
		</p>
		<?php
	}
}
