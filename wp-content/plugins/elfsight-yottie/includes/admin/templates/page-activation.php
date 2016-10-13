<?php

if (!defined('ABSPATH')) exit;

?><article class="yottie-admin-page-activation yottie-admin-page" data-yt-admin-page-id="activation">
	<div class="yottie-admin-page-heading">
		<h2><?php _e('Activation', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h2>

        <div class="yottie-admin-page-activation-status">
            <span class="yottie-admin-page-activation-status-activated"><?php _e('Activated', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
            <span class="yottie-admin-page-activation-status-not-activated"><?php _e('Not Activated', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
        </div>

		<div class="yottie-admin-page-heading-subheading">
			<?php _e('Activate your plugin in order to get awesome benefits for our customers!', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
		</div>
    </div>

    <div class="yottie-admin-divider"></div>

    <div class="yottie-admin-page-activation-benefits">
        <h4><?php _e('Get Awesome Benefits', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h4>

        <ul class="yottie-admin-page-activation-benefits-list">
            <li class="yottie-admin-page-activation-benefits-list-item-live-updates yottie-admin-page-activation-benefits-list-item">
                <div class="yottie-admin-page-activation-benefits-list-item-icon-container">
                    <span class="yottie-admin-page-activation-benefits-list-item-icon">
                        <span class="yottie-admin-icon-live-updates yottie-admin-icon"></span>
                    </span>
                </div>

                <div class="yottie-admin-page-activation-benefits-list-item-info">
                    <div class="yottie-admin-page-activation-benefits-list-item-title"><?php _e('Simple Live Updates', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                    <div class="yottie-admin-page-activation-benefits-list-item-description"><?php _e('Always be aware of fresh updates and download them easily and quickly right from your admin panel.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
                </div>
            </li>

            <li class="yottie-admin-page-activation-benefits-list-item-support yottie-admin-page-activation-benefits-list-item">
                <div class="yottie-admin-page-activation-benefits-list-item-icon-container">
                    <span class="yottie-admin-page-activation-benefits-list-item-icon">
                        <span class="yottie-admin-icon-support yottie-admin-icon"></span>
                    </span>
                </div>

                <div class="yottie-admin-page-activation-benefits-list-item-info">
                    <div class="yottie-admin-page-activation-benefits-list-item-title"><?php _e('Fast & Premium Support', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                    <div class="yottie-admin-page-activation-benefits-list-item-description"><?php _e('Submit your ticket and get our direct support in the fastest way. We are ready to solve all your issues.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
                </div>
            </li>
        </ul>
    </div>

    <div class="yottie-admin-divider"></div>

	<div class="yottie-admin-page-activation-form-container">
        <form class="yottie-admin-page-activation-form" data-nonce="<?php echo wp_create_nonce('elfsight_yottie_update_activation_data_nonce'); ?>" data-activation-url="<?php echo ELFSIGHT_YOTTIE_UPDATE_URL; ?>" data-activation-slug="<?php echo ELFSIGHT_YOTTIE_SLUG; ?>" data-activation-version="<?php echo ELFSIGHT_YOTTIE_VERSION; ?>">
            <h4><?php _e('Activate Yottie', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h4>

            <div class="yottie-admin-page-activation-form-field">
                <label>
                    <span class="yottie-admin-page-activation-form-field-label"><?php _e('Please enter your CodeCanyon Yottie purchase code', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                    <input class="yottie-admin-page-activation-form-activated-input" type="hidden" name="activated" value="<?php echo $activated; ?>">
                    <input class="yottie-admin-page-activation-form-purchase-code-input" type="text" placeholder="<?php _e('Purchase code', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>" name="purchase_code" value="<?php echo $purchase_code; ?>" class="regular-text" spellcheck="false" autocomplete="off">
                </label>
            </div>

            <div class="yottie-admin-page-activation-form-message-success yottie-admin-page-activation-form-message"><?php _e('Yottie is successfuly activated', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
            <div class="yottie-admin-page-activation-form-message-error yottie-admin-page-activation-form-message"><?php _e('Your purchase code is not valid', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
            <div class="yottie-admin-page-activation-form-message-fail yottie-admin-page-activation-form-message"><?php _e('Error occurred while checking your purchase code. Please, contact our support team via <a href="mailto:support@elfsight.com">support@elfsight.com</a>. We apologize for inconveniences.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

            <div class="yottie-admin-page-activation-form-field">
                <div class="yottie-admin-page-activation-form-submit yottie-admin-button-green yottie-admin-button"><?php _e('Activate', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
            </div>
        </form>

        <div class="yottie-admin-page-activation-faq">
            <h4><?php _e('FAQ', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h4>

            <ul class="yottie-admin-page-activation-faq-list">
                <li class="yottie-admin-page-activation-faq-list-item">
                    <div class="yottie-admin-page-activation-faq-list-item-title"><?php _e('What is item purchase code?', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
                    <div class="yottie-admin-page-activation-faq-list-item-text">
                        <?php printf(__('Purchase code is a licensed key, which you will get after buying item on <a href="%1$s" target="_blank">Codecanyon</a>.', ELFSIGHT_YOTTIE_TEXTDOMAIN), ELFSIGHT_YOTTIE_PRODUCT_URL); ?>
                    </div>
                </li>

                <li class="yottie-admin-page-activation-faq-list-item">
                    <div class="yottie-admin-page-activation-faq-list-item-title"><?php _e('How to get purchase code?', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
                    <div class="yottie-admin-page-activation-faq-list-item-text">
                        <?php _e('After buying the item you have to visit the following page <a href="http://codecanyon.net/downloads" target="_blank">http://codecanyon.net/downloads</a>, click the Download button and select “License Certificate & Purchase Code”. In the downloaded file you’ll find your purchase code.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</article>