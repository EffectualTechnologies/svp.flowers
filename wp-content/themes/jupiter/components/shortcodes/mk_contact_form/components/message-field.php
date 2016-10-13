<div class="mk-form-row">
	<?php if(isset($view_params['show_icon'])) : ?>
		<i class="mk-li-pencil"></i>
	<?php endif; ?>	
	<textarea required="required" placeholder="<?php _e( 'Your Message', 'mk_framework' ); ?>" class="mk-textarea s_txt-input" name="contact_content" tabindex="<?php echo $view_params['tab_index']; ?>"></textarea>
</div>