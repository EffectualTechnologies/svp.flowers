<?php

if (!defined('ABSPATH')) exit;

?><article class="yottie-admin-page-support yottie-admin-page" data-yt-admin-page-id="support">
	<div class="yottie-admin-page-heading">
		<h2><?php _e('Support', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h2>

		<div class="yottie-admin-page-heading-subheading">
			<?php _e('We understand all the importance of product support for our customers. That’s why we are ready to solve all your issues and answer any question related to our plugin.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
		</div>
    </div>

    <div class="yottie-admin-divider"></div>

	<div class="yottie-admin-page-support-ticket">
		<h4><?php _e('Before submitting a ticket, be sure that:', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h4>

		<ul class="yottie-admin-page-support-ticket-steps">
			<li class="yottie-admin-page-support-ticket-steps-item-latest-version yottie-admin-page-support-ticket-steps-item">
				<span class="yottie-admin-page-support-ticket-steps-item-icon">
					<span class="yottie-admin-icon-support-latest-version yottie-admin-icon"></span>
				</span>

				<span class="yottie-admin-page-support-ticket-steps-item-label"><?php _e('You use the latest version', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
			</li>

			<li class="yottie-admin-page-support-ticket-steps-item-javascript-errors yottie-admin-page-support-ticket-steps-item">
				<span class="yottie-admin-page-support-ticket-steps-item-icon">
					<span class="yottie-admin-icon-support-javascript-errors yottie-admin-icon"></span>
				</span>

				<span class="yottie-admin-page-support-ticket-steps-item-label"><?php _e('No javascript errors on your website', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
			</li>

			<li class="yottie-admin-page-support-ticket-steps-item-documentation yottie-admin-page-support-ticket-steps-item">
				<span class="yottie-admin-page-support-ticket-steps-item-icon">
					<span class="yottie-admin-icon-support-documentation yottie-admin-icon"></span>
				</span>

				<span class="yottie-admin-page-support-ticket-steps-item-label"><?php _e('The documentation can\'t help', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
			</li>
		</ul>

		<div class="yottie-admin-page-support-ticket-submit">
			<?php printf(__('Can\'t find what you\'re looking for? <a href="%1$s" target="_blank">Submit a ticket</a> to our Support Center.', ELFSIGHT_YOTTIE_TEXTDOMAIN), ELFSIGHT_YOTTIE_SUPPORT_URL); ?>
		</div>
	</div>

	<div class="yottie-admin-divider"></div>

	<div class="yottie-admin-page-support-includes-container">
		<div class="yottie-admin-page-support-includes">
			<h4><?php _e('Our Support Includes', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h4>

			<ul class="yottie-admin-page-support-includes-list">
				<li class="yottie-admin-page-support-includes-list-item">
					<div class="yottie-admin-page-support-includes-list-item-title"><?php _e('Fixing Product Bugs', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
					
					<p class="yottie-admin-page-support-includes-list-item-description"><?php _e('Our product doesn’t work properly on your website? Report your issue or bug by describing it in detail and providing us with a link to your website. We will do our best to find a solution.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></p>
				</li>
				
				<li class="yottie-admin-page-support-includes-list-item">
					<div class="yottie-admin-page-support-includes-list-item-title"><?php _e('Life-Time Updates', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
					
					<p class="yottie-admin-page-support-includes-list-item-description"><?php _e('We provide you with all possible updates and new features, which were and will be also released in the future. Just don’t forget to check the latest version in your WordPress admin panel.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></p>
				</li>

				<li class="yottie-admin-page-support-includes-list-item">
					<div class="yottie-admin-page-support-includes-list-item-title"><?php _e('Considering Your Suggestions', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
					
					<p class="yottie-admin-page-support-includes-list-item-description"><?php _e('We are open to your ideas. If you want to see some specific features, which might improve our products, then just drop us a line. We will consider them and include the best in further updates.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></p>
				</li>
			</ul>
		</div>

		<div class="yottie-admin-page-support-not-includes">
			<h4><?php _e('Our Support Doesn’t Include', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></h4>
			
			<ul class="yottie-admin-page-support-not-includes-list">
				<li class="yottie-admin-page-support-not-includes-list-item">
					<div class="yottie-admin-page-support-not-includes-list-item-title"><?php _e('Product Installation', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
					
					<p class="yottie-admin-page-support-not-includes-list-item-description"><?php _e('We don’t provide installation services for our products. Otherwise, we can give you our recommendations concerning its installation. And if you face any issue during installation, feel free to contact us. ', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></p>
				</li>
				
				<li class="yottie-admin-page-support-not-includes-list-item">
					<div class="yottie-admin-page-support-not-includes-list-item-title"><?php _e('Customization of Our Products', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
					
					<p class="yottie-admin-page-support-not-includes-list-item-description"><?php _e('We don’t provide customization services of our products. If you want to see more features in our product, then send us a description of your ideas and we will consider them for future updates. ', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></p>
				</li>

				<li class="yottie-admin-page-support-not-includes-list-item">
					<div class="yottie-admin-page-support-not-includes-list-item-title"><?php _e('3rd-Party Issues', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
					
					<p class="yottie-admin-page-support-not-includes-list-item-description"><?php _e('We don’t fix bugs or issues caused by other plugins and themes, which relates to 3rd-party developers. Also we don’t  provide services for integrating our products with 3rd-party plugins and themes.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></p>
				</li>
			</ul>
		</div>
	</div>
</article>