<?php global $post;?>
<script type="text/html" id="tmpl-quick-add-lesson">
	<div id="lpr-quick-add-lesson-form" class="lpr-quick-add-form">
		<input type="text" name="" />
		<button type="button" class="button" data-action="cancel"><?php _e( 'Cancel [ESC]', 'learn_press' ); ?></button>
		<button type="button" class="button" data-action="add"><?php _e( 'Add [Enter]', 'learn_press' ); ?></button>
		<span class="lpr-ajaxload">...</span>
	</div>
</script>
<script type="text/html" id="tmpl-quick-add-quiz">
	<div id="lpr-quick-add-quiz-form" class="lpr-quick-add-form">
		<input type="text" name="" />
		<button type="button" class="button" data-action="cancel"><?php _e( 'Cancel [ESC]', 'learn_press' ); ?></button>
		<button type="button" class="button" data-action="add"><?php _e( 'Add [Enter]', 'learn_press' ); ?></button>
		<span class="lpr-ajaxload">...</span>
	</div>
</script>
<script type="text/html" id="tmpl-curriculum-section">
	<?php
	learn_press_admin_view(
		'meta-boxes/course/loop-section.php',
		array(
			'class' 		=> 'lp-section-empty',
			'toggle_class'	=> 'dashicons-minus',
			'section_name'	=> '',
			'content_items'	=> ''
		)
	);
	?>
</script>
<script type="text/html" id="tmpl-section-item">
	<?php
	$item = learn_press_post_object( array( 'post_type' => LP()->lesson_post_type ) );
	$item->post_title = '{{data.text}}';
	$item->item_id = '{{data.id}}';
	$item->post_type = '{{data.type}}';
	learn_press_admin_view(
		'meta-boxes/course/loop-item.php',
		array(
			'item' => $item
		)
	)
	?>
</script>

<?php $curriculum_items = LP_Course::get_course( $post )->get_curriculum_items( array( 'group' => true, 'field' => 'ID' ) );?>
<script type="text/html" id="tmpl-lp-modal-search-lesson">
	<div id="lp-modal-search-lesson" class="lp-modal-search">
		<?php
		$exclude_lessons = $curriculum_items['lessons'];
		$lessons = learn_press_get_current_user()->get_lessons(array('orderby' => 'name', 'order' => 'ASC', 'posts_per_page' => -1));
		?>
		<div class="lp-search-items">
			<input type="text" name="lp-item-name" placeholder="<?php _e( 'Type here to search the lesson', 'learn_press' );?>" />
			<!--<button type="button" class="button lp-add-new-item"><?php _e( 'Add New', 'learn_press' );?></button>-->
		</div>
		<ul>
			<?php
			if ( $lessons ) {
				foreach( $lessons as $lesson ){
					$hidden = in_array( $lesson->ID, $exclude_lessons ) ? 'selected hide-if-js' : '';
					?>
					<li class="<?php echo $hidden;?>" data-id="<?php echo $lesson->ID;?>" data-type="<?php echo $lesson->post_type;?>" data-text="<?php echo esc_attr( $lesson->post_title );?>">
						<label>
							<input type="checkbox" value="<?php echo $lesson->ID;?>">
							<span class="lp-item-text"><?php echo $lesson->post_title;?></span>
						</label>
					</li>
					<?php
				}
			}
			?>
			<li class="lp-search-no-results hide-if-js" data-id="0"><?php _e( 'No results', 'learn_press' );?></li>
		</ul>
		<button class="lp-add-item button" disabled="disabled" data-text="<?php _e( 'Add to section', 'learn_press' );?>"><?php _e( 'Add to section', 'learn_press' );?></button>
		<button class="lp-close-lightbox button" onclick="LearnPress.MessageBox.hide();"><?php _e( 'Close', 'learn_press' );?></button>
	</div>
</script>
<script type="text/html" id="tmpl-lp-modal-search-quiz">
	<div id="lp-modal-search-quiz" class="lp-modal-search">
		<?php
		$exclude_quizzes = $curriculum_items['quizzes'];
		$quizzes = learn_press_get_current_user()->get_quizzes(array('orderby' => 'name', 'order' => 'ASC', 'posts_per_page' => -1));
		?>
		<div class="lp-search-items">
			<input type="text" name="lp-item-name" placeholder="<?php _e( 'Type here to search the quiz', 'learn_press' );?>" />
			<!--<button type="button" class="button lp-add-new-item"><?php _e( 'Add New', 'learn_press' );?></button>-->
		</div>
		<ul>
			<?php
			if ( $quizzes ) {
				foreach( $quizzes as $quiz ){
					$hidden = in_array( $quiz->ID, $exclude_quizzes ) ? 'selected hide-if-js' : '';
					?>
					<li class="<?php echo $hidden;?>" data-id="<?php echo $quiz->ID;?>" data-type="<?php echo $quiz->post_type;?>" data-text="<?php echo esc_attr( $quiz->post_title );?>">
						<label>
							<input type="checkbox" value="<?php echo $quiz->ID;?>">
							<span class="lp-item-text"><?php echo $quiz->post_title;?></span>
						</label>
					</li>
					<?php
				}
			}
			?>
			<li class="lp-search-no-results hide-if-js" data-id="0"><?php _e( 'No results', 'learn_press' );?></li>
		</ul>
		<button class="lp-add-item button" disabled="disabled" data-text="<?php _e( 'Add to section', 'learn_press' );?>"><?php _e( 'Add to section', 'learn_press' );?></button>
		<button class="lp-close-lightbox button" onclick="LearnPress.MessageBox.hide();"><?php _e( 'Close', 'learn_press' );?></button>
	</div>
</script>
<script type="text/javascript">
var LP_Curriculum_Settings = {
	selectedItems: [<?php echo join(',', array_merge( $exclude_quizzes, $exclude_lessons ) ) ;?>]
}
</script>