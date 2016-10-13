<?php

if (!defined('ABSPATH')) exit;

?><article class="yottie-admin-page-edit-gallery yottie-admin-page" data-yt-admin-page-id="edit-gallery">
    <div class="yottie-admin-page-heading">
        <a class="yottie-admin-page-back-button" href="#/galleries/" data-yt-admin-page="galleries">
            <svg class="yottie-admin-svg-arrow-back">
                <line x1="0.5" y1="4.5" x2="4.5" y2="0"></line>
                <line x1="0.5" y1="4.5" x2="4.5" y2="8.5"></line>
            </svg>
            <?php _e('Back to list', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
        </a>

        <h2 class="yottie-admin-page-edit-gallery-title-add"><?php _e('Add Gallery', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h2>

        <h2 class="yottie-admin-page-edit-gallery-title-edit"><?php _e('Edit Gallery', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h2>

        <div class="yottie-admin-page-heading-subheading"><?php _e('Name your gallery and adjust options, save it. In the gallery list you will see the shortcode of the new gallery, which you can copy paste in the desired place of your website.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
    </div>

    <div class="yottie-admin-divider"></div>

    <div class="yottie-admin-page-edit-gallery-form">
        <div class="yottie-admin-page-edit-gallery-form-field">
            <label>
                <span class="yottie-admin-page-edit-gallery-form-field-label"><?php _e('Gallery name', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                <input class="yottie-admin-page-edit-gallery-name-input" type="text" name="galleryName">
            </label>        

            <div class="yottie-admin-page-edit-gallery-form-field-hint">
                <?php _e('Give any name for your gallery. It will be displayed only in your admin panel.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
            </div>
        </div>

        <div class="yottie-admin-divider"></div>

        <div class="yottie-admin-page-edit-gallery-form-field">
            <div class="yottie-admin-page-edit-gallery-form-field-label"><?php _e('Adjust options', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
            
            <div class="yottie-admin-demo-container"></div>

            <template class="yottie-admin-template-demo yottie-admin-template">
                <?php require_once(ELFSIGHT_YOTTIE_PATH . implode(DIRECTORY_SEPARATOR, array('includes', 'admin', 'yottie-demo.php'))); ?>
            </template>

             <script>
                function getYottieDefaults() {
                    return <?php echo json_encode($yottie_json); ?>;
                }

                function getYottieColorSchemes() {
                    return <?php echo json_encode($yottie_color_schemes_json); ?>;
                }
            </script>
        </div>

        <div class="yottie-admin-page-edit-gallery-form-field">
            <div class="yottie-admin-page-edit-gallery-form-submit yottie-admin-button-large yottie-admin-button-green yottie-admin-button"><?php _e('Save gallery', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
        </div>
    </div>
</article>