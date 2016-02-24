<?php

/**
 * Register profile
 *
 * @param object $user
 *
 * @since  1.0
 */
function learnplus_add_profile( $user ) {
	?>
	<h3><?php esc_html_e( 'Teacher Profile', 'learnplus' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="profession"><?php esc_html_e( 'Profession', 'learnplus' ); ?></label></th>
			<td>
				<input type="text" name="profession" value="<?php echo esc_attr( get_the_author_meta( 'profession', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
	</table>


	<table class="form-table">
		<th><label for="skills"><?php esc_html_e( 'Skills', 'learnplus' ); ?></label></th>
		<td>
			<textarea name="skills" rows="5" cols="30"><?php echo esc_attr( get_the_author_meta( 'skills', $user->ID ) ); ?></textarea>

			<p><?php esc_html_e( 'Enter values for graph - value, title. Divide value sets with linebreak "Enter" (Example: 90|Development).', 'learnplus' ); ?></p>
		</td>

	</table>
	<table class="form-table">
		<th><label for="avartar"><?php esc_html_e( 'Custom Avatar URL:', 'learnplus' ); ?></label></th>
		<td>
			<textarea name="custom_avatar" rows="2" cols="30"><?php echo esc_attr( get_the_author_meta( 'custom_avatar', $user->ID ) ); ?></textarea>
		</td>

	</table>
	<?php
}

add_action( 'show_user_profile', 'learnplus_add_profile' );
add_action( 'edit_user_profile', 'learnplus_add_profile' );

/**
 * Save profile
 *
 * @param string $user_id
 *
 * @since  1.0
 */
function learnplus_save_profile( $user_id ) {
	update_user_meta( $user_id, 'profession', $_POST['profession'] );
	update_user_meta( $user_id, 'skills', $_POST['skills'] );
	update_user_meta( $user_id, 'custom_avatar', $_POST['custom_avatar'] );
}

add_action( 'personal_options_update', 'learnplus_save_profile' );
add_action( 'edit_user_profile_update', 'learnplus_save_profile' );

/**
 * Apply custom avatar
 *
 * @param $avatar
 * @param $id_or_email
 * @param $size
 * @param $default
 * @param $alt
 *
 * @return string
 */
function learnplus_gravatar( $avatar, $id_or_email, $size, $default, $alt ) {

	$id = '';
	if ( is_numeric( $id_or_email ) ) {

		$id = (int) $id_or_email;

	} elseif ( is_object( $id_or_email ) ) {

		if ( ! empty( $id_or_email->user_id ) ) {
			$id = (int) $id_or_email->user_id;
		}

	} else {
		$user = get_user_by( 'email', $id_or_email );
		if ( $user ) {
			$id = $user->ID;
		}
	}

	if ( empty( $id ) ) {
		return $avatar;
	}

	$custom_avatar = get_the_author_meta( 'custom_avatar', $id );
	if ( $custom_avatar ) {
		$return = '<img src="' . $custom_avatar . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '" />';
	} elseif ( $avatar ) {
		$return = $avatar;
	} else {
		$return = '<img src="' . $default . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '" />';
	}

	return $return;
}

add_filter( 'get_avatar', 'learnplus_gravatar', 10, 5 );

/**
 * Load custom style for login page
 *
 * @since 1.0.0
 */
function learnplus_login_scripts() {
	$logo = learnplus_theme_option( 'logo' );
	$logo = $logo ? $logo : LEARNPLUS_URL . '/img/login-logo.png';
	?>
	<style type="text/css">
		.login h1 a {
			background-image: url(<?php echo esc_attr( $logo ) ?>);
		}
	</style>
	<?php
	wp_enqueue_style( 'learnplus-login', LEARNPLUS_URL . '/css/login.css', array(), LEARNPLUS_VERSION );
	wp_enqueue_script( 'learnplus-login', LEARNPLUS_URL . '/js/login.js', array( 'jquery' ), LEARNPLUS_VERSION );
}
add_action( 'login_enqueue_scripts', 'learnplus_login_scripts' );

function learnplus_login_logo_url() {
	return home_url();
}
add_filter( 'login_headerurl', 'learnplus_login_logo_url' );