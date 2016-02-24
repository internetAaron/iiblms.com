<?php
/**
 * @package LearnPlus
 */
global $post;

if ( trim( $shortcode_atts['categoryselector'] ) != 'true' ) {
	$col   = empty( $shortcode_atts['col'] ) ? 4 : intval( $shortcode_atts['col'] );
	$smcol = $col / 1.5;
	$col   = empty( $col ) ? 1 : ( $col >= 12 ) ? 12 : $col;
	$smcol = empty( $smcol ) ? 1 : ( $smcol >= 12 ) ? 12 : $smcol;
	$col   = intval( 12 / $col );
	$smcol = intval( 12 / $smcol );
}

$options  = get_option( 'sfwd_cpt_options' );
$currency = null;
if ( ! is_null( $options ) ) {
	if ( isset( $options['modules'] ) && isset( $options['modules']['sfwd-courses_options'] ) && isset( $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'] ) ) {
		$currency = $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'];
	}
}
if ( is_null( $currency ) ) {
	$currency = 'USD';
}


$course_options = get_post_meta( $post->ID, '_sfwd-courses', true );
$price          = $course_options && isset( $course_options['sfwd-courses_course_price'] ) ? $course_options['sfwd-courses_course_price'] : esc_html__( 'Free', 'learnplus' );
$students       = isset( $course_options['sfwd-courses_number_students'] ) ? intval( $course_options['sfwd-courses_number_students'] ) : false;

if ( $price == '' ) {
	$price .= esc_html__( 'Free', 'learnplus' );
}

if ( is_numeric( $price ) ) {
	if ( $currency == "USD" ) {
		$price = '$' . $price;
	} else {
		$price .= ' ' . $currency;
	}
}

$classes = trim( $shortcode_atts['categoryselector'] ) == 'true' ? 'item' : "ld_course_grid col-sm-$smcol col-md-$col";
?>
<div <?php post_class( $classes ) ?>>
	<div class="shop-item-list entry">
		<div>
			<?php the_post_thumbnail( 'learnplus-course-thumb' ); ?>
			<div class="magnifier"></div>
		</div>
		<div class="shop-item-title clearfix">
			<h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>

			<div class="shopmeta">
				<?php if ( $students ) : ?>
					<span class="pull-left"><?php echo $students . ' ' . esc_attr__( 'Students', 'learnplus' ) ?></span>
				<?php endif; ?>

				<?php echo LearnPlus_LearnDash::get_rating_html( get_the_ID(), 'pull-right' ) ?>
			</div><!-- end shop-meta -->
		</div>
	</div><!-- #post-## -->
</div>
