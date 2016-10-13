=== Plugin Name ===
Contributors: terrytsang
Plugin Name: WooCommerce Facebook Login Checkout
Plugin URI:  http://terrytsang.com/shop/shop/woocommerce-facebook-login-checkout/
Tags: woocommerce, facebook, login, checkout, e-commerce
Requires at least: 2.7
Tested up to: 3.4.2
Stable tag: 1.0.0
Version: 1.0.0
License: Single Site License

== Description ==

A premium WooCommerce plugin that aims to implement Facebook Login so that new customers can sign in woocommerce site and checkout by using their Facebook account. 
This will fasten the checkout process and enhance good user experience which is very important to any eCommerce sites.

In WooCommerce Settings Panel, there will be a new tab called 'Facebook Login' where you can:
1. Enabled / Disabled the Facebook Login Checkout
2. Fill in Facebook App ID & Facebook App Secret after you have created new app at https://developers.facebook.com/apps
3. New user account created for woocommerce site after sign in with facebook
4. Auto redirect to checkout page after sign in with facebook
5. Show facebook login button at Checkout page

NOTE: This plugin requires the WooCommerce Extension & Facebook App with App ID & Secrets.

== Installation ==

1. Go to https://developers.facebook.com/apps and click "+Create New App" button at top right
2. Fill in "App Name"(required) and "App Namespace"(optional) and then click "Continue" button
3. Fill in "Captcha" field and then click "Continue" button
4. You will see newly created Facebook App page. Fill in "App Domains" (your site url), and also checked "Website with Facebook Login" at [Select how your app integrates with Facebook] section
5. Fill in "Site URL" field and then click "Save Changes" button
6. Upload the entire *woocommerce-facebook-checkout* folder to the */wp-content/plugins/* directory
7. Activate the plugin through the 'Plugins' menu in WordPress
8. Go to WooCommerce Settings panel at left sidebar menu and update the options at 'Facebook Login' tab there.
9. Enabled the extension by checking the checkbox, and remember to fill in two required fields as well - "Facebook App ID" and "Facebook App Secret" 
10. That's it. You're ready to go and cheers!

== Screenshots ==

1. [screenhot-1.png] Screenshot Facebook Apps - Create New App
2. [screenhot-2.png] Screenshot Facebook Apps - Fill in Captcha
3. [screenhot-3.png] Screenshot Facebook Apps - Edit App Details
4. [screenhot-4.png] Screenshot Admin WooCommerce Settings - Facebook Login Tab
5. [screenhot-5.png] Screenshot Customer Checkout Page - Sign In with Facebook button
6. [screenhot-6.png] Screenshot Customer - Login Facebook
7. [screenhot-7.png] Screenshot Customer Received Email - New Account


== Changelog ==

= 1.0.0 =

* Initial Release
* Sign in woocommerce site with facebook login and checkout
* Only work with valid App ID and App Secrets

