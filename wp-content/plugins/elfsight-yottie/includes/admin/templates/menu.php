<?php

if (!defined('ABSPATH')) exit;

?><nav class="yottie-admin-menu">
    <ul class="yottie-admin-menu-list">
        <li class="yottie-admin-menu-list-item"><a href="#/galleries/" data-yt-admin-page="galleries"><?php _e('Galleries', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a></li>
        <li class="yottie-admin-menu-list-item"><a href="#/support/" data-yt-admin-page="support"><?php _e('Support', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a></li>
        <li class="yottie-admin-menu-list-item"><a href="#/preferences/" data-yt-admin-page="preferences"><?php _e('Preferences', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a></li>
        <li class="yottie-admin-menu-list-item-activation yottie-admin-menu-list-item">
            <a href="#/activation/" data-yt-admin-page="activation" class="yottie-admin-tooltip-trigger">
                <?php _e('Activation', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>

                <span class="yottie-admin-menu-list-item-notification"></span>

                <span class="yottie-admin-tooltip-content">
                    <span class="yottie-admin-tooltip-content-inner">
                        <?php _e('Yottie is not activated', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                    </span>
                </span>
            </a>
        </li>
    </ul>
</nav>   