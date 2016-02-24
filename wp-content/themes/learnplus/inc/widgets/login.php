<?php

class LearnPlus_Login_Widget extends WP_Widget {
	protected $default;

	/**
	 * Constructor
	 */
	function __construct() {
		$this->default = array(
			'title'       => '',
			'account_url' => '',
		);

		parent::__construct(
			'login-widget',
			esc_html__( 'LearnPlus - Login', 'learnplus' ),
			array(
				'classname'   => 'login-widget',
				'description' => esc_html__( 'Display login/register form', 'learnplus' ),
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
	function widget( $args, $instance ) {
		$instance    = wp_parse_args( $instance, $this->default );
		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		extract( $args );
		echo $before_widget;

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo $before_title . $title . $after_title;
		}
		?>

		<?php if ( ! is_user_logged_in() ) : ?>
			<a class="dropdown-toggle login-toggle" href="#" data-toggle="dropdown"><i class="fa fa-lock"></i> <?php esc_html_e( 'Login & Register', 'learnplus' ) ?>
			</a>
			<div class="dropdown-menu">
				<form method="post" action="<?php echo esc_url( wp_login_url() ) ?>">
					<div class="form-title">
						<h4><?php esc_html_e( 'Login Area', 'learnplus' ); ?></h4>
						<hr>
					</div>
					<input class="form-control" type="text" name="log" placeholder="<?php esc_attr_e( 'User Name', 'learnplus' ) ?>">

					<div class="formpassword">
						<input class="form-control" type="password" name="pwd" placeholder="******">
					</div>
					<div class="rememberme">
						<label>
							<input name="rememberme" type="checkbox" id="rememberme" value="forever">
							<?php esc_html_e( 'Remember Me', 'learnplus' ) ?>
						</label>
					</div>
					<div class="clearfix"></div>
					<button type="submit" class="btn btn-block btn-primary" name="wp-submit"><?php esc_html_e( 'Login', 'learnplus' ) ?></button>
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( $current_url ) ?>">
					<hr>
					<h4 class="register-link">
						<a href="<?php echo esc_url( wp_registration_url() ) ?>"><?php esc_html_e( 'Create an Account', 'learnplus' ) ?></a>
					</h4>
				</form>
			</div>
		<?php else : ?>
			<i class="fa fa-lock"></i>

			<?php if ( $instance['account_url'] ) : ?>
				<a href="<?php echo esc_url( $instance['account_url'] ) ?>"><?php esc_html_e( 'Account', 'learnplus' ) ?></a> |
			<?php endif; ?>
			<a href="<?php echo wp_logout_url( $current_url ) ?>">

				<?php esc_html_e( 'Logout', 'learnplus' ) ?>
			</a>
		<?php endif; ?>
		<?php
		echo $after_widget;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance
	 *
	 * @return array
	 */
	function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->default );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'account_url' ) ); ?>"><?php esc_html_e( 'Profile Page URL', 'learnplus' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'account_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'account_url' ) ); ?>" value="<?php echo esc_attr( $instance['account_url'] ); ?>" />
		</p>
		<?php
	}
}
