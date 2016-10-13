<?php

if (!defined('ABSPATH')) exit;

?><article class="yottie-admin-page-preferences yottie-admin-page" data-yt-admin-page-id="preferences">
	<div class="yottie-admin-page-heading">
		<h2><?php _e('Preferences', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h2>

		<div class="yottie-admin-page-heading-subheading">
			<?php _e('These settings will be accepted for each Yottie gallery<br> on your website.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
		</div>
    </div>

    <div class="yottie-admin-divider"></div>

	<form class="yottie-admin-page-preferences-form" data-nonce="<?php echo wp_create_nonce('elfsight_yottie_update_preferences_nonce'); ?>">
        <div class="yottie-admin-page-preferences-option-force-script yottie-admin-page-preferences-option">
            <div class="yottie-admin-page-preferences-option-info">
                <h4 class="yottie-admin-page-preferences-option-info-name">
                    <label for="forceScriptAdd"><?php _e('Add Yottie script to every page', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></label>
                </h4>

                <div class="yottie-admin-caption">
                    <?php _e('By default the plugin adds its scripts only on pages with Yottie shortcode. This option makes the plugin add scripts on every page. It is useful for ajax websites.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                </div>
            </div>

            <div class="yottie-admin-page-preferences-option-input">
                <input type="checkbox" name="force_script_add" value="true" id="forceScriptAdd" class="yottie-admin-page-preferences-option-input-toggle"<?php echo ($force_script_add === 'on') ? ' checked' : ''?>>
                <label for="forceScriptAdd"><i></i></label>
            </div>
        </div>

        <div class="yottie-admin-divider"></div>

        <div class="yottie-admin-page-preferences-option-css yottie-admin-page-preferences-option">
            <div class="yottie-admin-page-preferences-option-info">
                <h4 class="yottie-admin-page-preferences-option-info-name">
                    <?php _e('Custom CSS', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                </h4>

                <div class="yottie-admin-caption">
                    <?php _e('Here you can specify custom styles for Yottie. It will be printed on each page with the widget.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                </div>
            </div>

            <div class="yottie-admin-page-preferences-option-editor">
                <div class="yottie-admin-page-preferences-option-editor-code" id="yottiePreferencesSnippetCSS"><?php echo htmlspecialchars($custom_css)?></div>

                <div class="yottie-admin-page-preferences-option-editor-controls">
                    <a href="#" data-custom-save="css" class="yottie-admin-page-preferences-option-editor-controls-save yottie-admin-button-green yottie-admin-button"><?php _e('Save', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>

                    <span class="yottie-admin-page-preferences-option-editor-controls-success">
                        <span class="yottie-admin-icon-check-green-small yottie-admin-icon"></span><span class="yottie-admin-page-preferences-option-editor-controls-success-label"><?php _e('Done!', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                    </span>

                    <span class="yottie-admin-page-preferences-option-editor-controls-error"></span>
                </div>
            </div>
        </div>

        <div class="yottie-admin-divider"></div>

        <div class="yottie-admin-page-preferences-option-js yottie-admin-page-preferences-option">
            <div class="yottie-admin-page-preferences-option-info">
                <h4 class="yottie-admin-page-preferences-option-info-name">
                    <?php _e('Custom JavaScript', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                </h4>

                <div class="yottie-admin-caption">
                    <?php _e('Here you can specify custom JS for initiation of Yottie. This script will be printed on each page with the widget.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                </div>
            </div>

            <div class="yottie-admin-page-preferences-option-editor">
                <div class="yottie-admin-page-preferences-option-editor-code" id="yottiePreferencesSnippetJS"><?php echo htmlspecialchars($custom_js) ?></div>

                <div class="yottie-admin-page-preferences-option-editor-controls">
                    <a href="#" data-custom-save="js" class="yottie-admin-page-preferences-option-editor-controls-save yottie-admin-button-green yottie-admin-button"><?php _e('Save', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>

                    <span class="yottie-admin-page-preferences-option-editor-controls-success">
                        <span class="yottie-admin-icon-check-green-small yottie-admin-icon"></span><span class="yottie-admin-page-preferences-option-editor-controls-success-label"><?php _e('Done!', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                    </span>

                    <span class="yottie-admin-page-preferences-option-editor-controls-error"></span>
                </div>
            </div>
        </div>
    </form>
</article>