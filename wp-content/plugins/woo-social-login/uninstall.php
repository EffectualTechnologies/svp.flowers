<?php

/**
 * Uninstall
 * 
 * Does delete all the plugin options
 * when uninstalling the plugin
 * 
 * @package WooCommerce - Social Login
 * @since 1.0
 */

// check if the plugin really gets uninstalled
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

$options = array(
				'woo_slg_set_option', 'woo_social_order', 'woo_slg_login_heading', 'woo_slg_enable_notification',
				'woo_slg_redirect_url', 'woo_slg_enable_login_page', 'woo_slg_enable_facebook', 'woo_slg_fb_app_id',
				'woo_slg_fb_app_secret', 'woo_slg_fb_language', 'woo_slg_fb_icon_url','woo_slg_fb_link_icon_url', 'woo_slg_enable_fb_avatar',
				'woo_slg_enable_googleplus', 'woo_slg_gp_client_id', 'woo_slg_gp_client_secret', 'woo_slg_gp_icon_url','woo_slg_gp_link_icon_url',
				'woo_slg_enable_gp_avatar', 'woo_slg_enable_linkedin', 'woo_slg_li_app_id', 'woo_slg_li_app_secret',
				'woo_slg_li_icon_url','woo_slg_li_link_icon_url', 'woo_slg_enable_li_avatar', 'woo_slg_enable_twitter', 'woo_slg_tw_consumer_key',
				'woo_slg_tw_consumer_secret', 'woo_slg_tw_icon_url','woo_slg_tw_link_icon_url', 'woo_slg_enable_tw_avatar', 'woo_slg_enable_yahoo',
				'woo_slg_yh_consumer_key', 'woo_slg_yh_consumer_secret', 'woo_slg_yh_app_id', 'woo_slg_yh_icon_url','woo_slg_yh_link_icon_url',
				'woo_slg_enable_yh_avatar', 'woo_slg_enable_foursquare', 'woo_slg_fs_client_id', 'woo_slg_fs_client_secret',
				'woo_slg_fs_icon_url', 'woo_slg_fs_link_icon_url','woo_slg_enable_fs_avatar', 'woo_slg_enable_windowslive', 'woo_slg_wl_client_id',
				'woo_slg_wl_client_secret', 'woo_slg_wl_icon_url','woo_slg_wl_link_icon_url', 'woo_slg_enable_vk', 'woo_slg_vk_app_id',
				'woo_slg_vk_app_secret', 'woo_slg_vk_icon_url','woo_slg_vk_link_icon_url', 'woo_slg_enable_vk_avatar', 'woo_slg_enable_instagram',
				'woo_slg_inst_client_id', 'woo_slg_inst_client_secret',	'woo_slg_inst_icon_url','woo_slg_inst_link_icon_url', 'woo_slg_enable_inst_avatar',
				'woo_slg_display_link_thank_you'
			);

//Delete all options
foreach ( $options as $key ) {
	delete_option( $key );
}