<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package LearnPlus
 */
?>
		<?php if ( ! is_page_template( 'template-full-width.php' )  ) : ?>
				</div> <!-- .row -->
			</div><!-- .container -->
		<?php endif; ?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer dark footer section" role="contentinfo">
		<?php do_action( 'learnplus_footer' ); ?>
	</footer><!-- #colophon -->

	<?php do_action( 'learnplus_after_footer' ); ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
