<?php

if (!defined('ABSPATH')) exit;

?><header class="yottie-admin-header">
    <div class="yottie-admin-header-title"><?php _e('YouTube Channel Plugin', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

    <a class="yottie-admin-header-logo" href="<?php echo admin_url('admin.php?page=elfsight-yottie'); ?>" title="<?php _e('Yottie - WordPress YouTube Channel Plugin', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>">
        <img src="<?php echo plugins_url('assets/img/logo.png', ELFSIGHT_YOTTIE_FILE); ?>" width="119" height="44" alt="<?php _e('Yottie - WordPress YouTube Channel Plugin', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>">
    </a>

    <div class="yottie-admin-header-version">
        <span class="yottie-admin-tooltip-trigger">
            <span class="yottie-admin-tag-2"><?php _e('Version ' . ELFSIGHT_YOTTIE_VERSION, ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
            
            <?php if ($activated && !empty($last_check_datetime) && !$has_new_version): ?>
                <span class="yottie-admin-tooltip-content">
                    <span class="yottie-admin-tooltip-content-inner">
                        <b><?php _e('You have the latest version', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></b><br>
                        <?php printf(__('Last checked on %1$s at %2$s', ELFSIGHT_YOTTIE_TEXTDOMAIN), date_i18n(get_option('date_format'), $last_check_datetime), date_i18n(get_option('time_format'), $last_check_datetime)); ?>
                    </span>
                </span>
            <?php endif ?>
        </span>
    </div>
    
    <div class="yottie-admin-header-support">
        <a class="yottie-admin-button-transparent yottie-admin-button-small yottie-admin-button" href="#/support/" data-yt-admin-page="support"><?php _e('Need help?', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>
    </div>
</header>