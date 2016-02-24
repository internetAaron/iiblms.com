<?php
/**
 * The template part for displaying the main logo on header
 *
 * @package LearnPlus
 */

$logo = learnplus_theme_option( 'logo' );


if( ! $logo ) {
	if( learnplus_theme_option( 'header_layout' ) == 'header-left' ) {
		$logo = LEARNPLUS_URL . '/img/sidelogo.png';
	} else {
		$logo = LEARNPLUS_URL . '/img/logo.png';
	}
}

?>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
	<img alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" src="<?php echo esc_url( $logo ); ?>" />
</a>

<?php
printf(
	'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
	is_home() || is_front_page() ? 'h1' : 'p',
	esc_url( home_url( '/' ) ),
	get_bloginfo( 'name' )
);
?>
<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
