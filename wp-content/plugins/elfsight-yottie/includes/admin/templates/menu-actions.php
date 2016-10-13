<?php

if (!defined('ABSPATH')) exit;

?><div class="yottie-admin-menu-actions">
	<div class="yottie-admin-menu-actions-activate-container">
    	<a class="yottie-admin-menu-actions-activate yottie-admin-button-red yottie-admin-button-border yottie-admin-button" href="#/activation/" data-yt-admin-page="activation"><?php _e('Activate now', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>
	</div>

    <?php if ($has_new_version) {?>
        <span class="yottie-admin-menu-actions-update-container">
        	<span class="yottie-admin-menu-actions-update-label yottie-admin-tag-2"><?php _e('A new version is available', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

        	<a class="yottie-admin-menu-actions-update yottie-admin-button-green yottie-admin-button" href="<?php echo is_multisite() ? network_admin_url('update-core.php') : admin_url('update-core.php'); ?>"><?php _e('Update to', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?> <?php echo $latest_version; ?></a>
    	</span>
    <?php }?>
</div>