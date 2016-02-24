<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}
?>
<div class="row">
<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class( 'col-xs-12 col-sm-12 col-md-12' ); ?>>
	<div class="product-details row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<?php
				/**
				 * learnplus_single_product_image hook
				 *
				 * @hooked single_product_image 5
				 */
				do_action( 'learnplus_single_product_image' );
			?>
		</div>
		<div class="col-md-8 col-sm-8 col-xs-12 product-content">
		<?php
			/**
			 * learnplus_single_product_content hook
			 *
			 * @hooked woocommerce_show_product_thumbnails - 5
			 * @hooked woocommerce_template_single_title - 10
			 * @hooked single_product_content - 20
			 * @hooked woocommerce_output_product_data_tabs - 30
			 */
			do_action( 'learnplus_single_product_content' );
		?>
		</div>

		<div class="product-sidebar col-md-4 col-sm-4 col-xs-12">

			<?php
				/**
				 * learnplus_single_product_sidebar hook
				 *
				 * @hooked woocommerce_show_product_images - 5
				 * @hooked learnplus_single_meta - 10
				 * @hooked woocommerce_template_single_add_to_cart - 20
				 */
				do_action( 'learnplus_single_product_sidebar' );
			?>

		</div><!-- .summary -->
	</div>

	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />
</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
</div>
