<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Social File
 *
 * Handles load all social related files
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

global $woo_slg_social_facebook, $woo_slg_social_google,$woo_slg_social_linkedin,$woo_slg_social_windowslive,
	$woo_slg_social_twitter,$woo_slg_social_yahoo,$woo_slg_social_foursquare,$woo_slg_social_vk,$woo_slg_social_instagram, 
	$woo_slg_social_amazon, $woo_slg_social_paypal;

//Social Media Facebook Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/facebook.php');
$woo_slg_social_facebook = new WOO_Slg_Social_Facebook();
	
//Social Media Google Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/google.php');
$woo_slg_social_google = new WOO_Slg_Social_Google();

//Social Media LinkedIn Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/linkedin.php');
$woo_slg_social_linkedin = new WOO_Slg_Social_LinkedIn();

//Social Media Twitter Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/twitter.php');
$woo_slg_social_twitter = new WOO_Slg_Social_Twitter();

//Social Media Yahoo Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/yahoo.php');
$woo_slg_social_yahoo = new WOO_Slg_Social_Yahoo();

//Social Media Foursquare Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/foursquare.php');
$woo_slg_social_foursquare = new WOO_Slg_Social_Foursquare();

//Social Media Windows Live Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/windowslive.php');
$woo_slg_social_windowslive = new WOO_Slg_Social_Windowslive();

//Social Media VK Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/vk.php');
$woo_slg_social_vk = new WOO_Slg_Social_VK();

//Social Media Instagram Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/instagram.php');
$woo_slg_social_instagram = new WOO_Slg_Social_Instagram();

//Social Media Amazon Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/amazon.php');
$woo_slg_social_amazon = new WOO_Slg_Social_Amazon();

//Social Media Paypal Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR .'/paypal.php');
$woo_slg_social_paypal = new WOO_Slg_Social_Paypal();