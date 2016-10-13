<?php

if (!defined('ABSPATH')) exit;

?><article class="yottie-admin-page-galleries yottie-admin-page" data-yt-admin-page-id="galleries">
    <div class="yottie-admin-page-heading">
        <h2><?php _e('Galleries', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h2>

        <a class="yottie-admin-page-galleries-add-new yottie-admin-button-green yottie-admin-button" href="#/add-gallery/" data-yt-admin-page="add-gallery"><?php _e('Add gallery', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>

        <div class="yottie-admin-page-heading-subheading"><?php _e('Create, edit or remove your YouTube galleries. Use their shortcodes to insert them into the desired place.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
    </div>

    <table class="yottie-admin-page-galleries-list">
        <thead>
            <tr>
                <th><span><?php _e('Name', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span></th>
                <th><span><?php _e('Shortcode', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span></th>
                <th><span><?php _e('Actions', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span></th>
            </tr>
        </thead>

        <tbody></tbody>
    </table>

    <template class="yottie-admin-template-galleries-list-item yottie-admin-template">
        <tr class="yottie-admin-page-galleries-list-item">
            <td class="yottie-admin-page-galleries-list-item-name"><a href="#" data-yt-admin-page="edit-gallery"></a></td>

            <td class="yottie-admin-page-galleries-list-item-shortcode">
                <span class="yottie-admin-page-galleries-list-item-shortcode-hidden"></span>

                <input type="text" class="yottie-admin-page-galleries-list-item-shortcode-value" readonly></input>

                <div class="yottie-admin-page-galleries-list-item-shortcode-copy">
                    <span class="yottie-admin-page-galleries-list-item-shortcode-copy-trigger"><span>Copy</span></span>
                    
                    <div class="yottie-admin-page-galleries-list-item-shortcode-copy-error">An error occured. Try to copy manually.</div>
                </div>
            </td>

            <td class="yottie-admin-page-galleries-list-item-actions">
                <a href="#" class="yottie-admin-page-galleries-list-item-actions-edit"><?php _e('Edit', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>
                <a href="#" class="yottie-admin-page-galleries-list-item-actions-duplicate"><?php _e('Duplicate', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>
                <a href="#" class="yottie-admin-page-galleries-list-item-actions-remove"><?php _e('Remove', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>

                <span class="yottie-admin-page-galleries-list-item-actions-restore">
                    <span class="yottie-admin-page-galleries-list-item-actions-restore-label"><?php _e('The gallery has been removed. ', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                    <a href="#"><?php _e('Restore it', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>
                </span>
            </td>
        </tr>
    </template>

     <template class="yottie-admin-template-galleries-list-empty yottie-admin-template">
        <tr class="yottie-admin-page-galleries-list-empty-item">
            <td class="yottie-admin-page-galleries-list-empty-item-text" colspan="3">
                <?php _e('There is no any gallery yet.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                <a href="#/add-gallery/" data-yt-admin-page="add-gallery"><?php _e('Create the first one.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></a>
            </td>
        </tr>
    </template>
</article>
