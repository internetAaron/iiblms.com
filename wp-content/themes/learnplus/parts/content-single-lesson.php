<?php
/**
 * The template used for displaying lesson
 *
 * @package LearnPlus
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_content() ?>
</div><!-- #post-## -->