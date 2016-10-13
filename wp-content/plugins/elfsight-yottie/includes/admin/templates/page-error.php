<?php

if (!defined('ABSPATH')) exit;

?><article class="yottie-admin-page-error yottie-admin-page" data-yt-admin-page-id="error">
    <h1><?php _e('Oops, something went wrong', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h1>

    <p class="yottie-admin-page-error-message">
        <?php _e('Unfortunately, there is no such page in Yottie.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
    </p>

    <a class="yottie-admin-page-error-button yottie-admin-button-large yottie-admin-button-green yottie-admin-button" href="#/galleries/" data-yt-admin-page="galleries"><?php _e('Back to Home', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>
</article>