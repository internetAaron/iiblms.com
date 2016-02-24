<?php
/**
 * The template used for displaying topic
 *
 * @package LearnPlus
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_content() ?>
</div><!-- #post-## -->