<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package LearnPlus
 */

if ( 'full-content' == learnplus_get_layout() ) {
	return;
}

$sidebar = 'blog-sidebar';

if ( is_page() ) {
	$sidebar = 'page-sidebar';
} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
	$sidebar = 'shop-sidebar';
}
?>
<aside id="primary-sidebar" class="widgets-area primary-sidebar <?php echo esc_attr( $sidebar ) ?> col-xs-12 col-sm-12 col-md-4" role="complementary">
	<?php dynamic_sidebar( $sidebar ) ?>
</aside><!-- #secondary -->
