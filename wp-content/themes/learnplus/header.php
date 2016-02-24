<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package LearnPlus
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php if ( learnplus_theme_option( 'preloader' ) ) : ?>
		<div id="loader">
			<div class="loader"></div>
		</div>
	<?php endif; ?>
	<div id="page" class="hfeed site">

	<?php do_action( 'learnplus_before_header' ); ?>

	<header id="masthead" class="site-header header" role="banner">
		<?php do_action( 'learnplus_header' ); ?>
	</header><!-- #masthead -->

	<?php do_action( 'learnplus_page_header' ); ?>

	<div id="content" class="site-content">
		<?php if ( ! is_page_template( 'template-full-width.php' ) ) : ?>
			<div class="container">
				<div class="row">
		<?php endif; ?>
