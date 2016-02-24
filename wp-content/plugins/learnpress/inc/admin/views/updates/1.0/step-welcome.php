<h2><?php _e( 'Welcome to LearnPress!', 'learn_press' ); ?></h2>
<p><?php _e( 'Thank you for choosing LearnPress to sell your course online!', 'learn_press' ); ?></p>
<p><?php _e( 'In version 1.0 of LearnPress we have a big update and need to upgrade your database to ensure system works properly.', 'learn_press' ); ?></p>
<p><?php _e( 'We are very careful in the upgrading database but be sure to backup your database before upgrading to avoid the risks may be encountered.', 'learn_press' ); ?></p>
<p><?php _e( 'Click <strong>Yes, upgrade!</strong> button to start.', 'learn_press' ); ?></p>
<p class="lp-update-actions">
	<a href="<?php echo esc_url( admin_url( '' ) ); ?>" class="button"><?php _e( 'No, back to Admin', 'learn_press' ); ?></a>
	<a id="learn-press-update-button" class="button-primary button"><?php _e( 'Yes, upgrade!', 'learn_press' ); ?></a>
</p>
<input type="hidden" name="action" value="upgrade" />