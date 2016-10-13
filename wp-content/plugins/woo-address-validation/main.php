<?php
/*
Plugin Name: snapCX - WooCommerce Address Validation
Plugin URI: https://wordpress.org/plugins/woo-address-validation/
Description: Easy to use and Free Address Validation for your WooCommerce store
Version: 1.1.0
Author: snapCX
Author URI: https://snapcx.io
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define ( 'UBERCX_ADDR_PLUGIN_PATH' , plugin_dir_path( __FILE__ ) );
define('UBERCX_ADDR_DOMAIN', 'ubercx-addr-val');

include_once(UBERCX_ADDR_PLUGIN_PATH . 'inc/UberCXAddrVal.php');




/**
 * Loads the right js & css assets
*/
function load_ubercx_addr_scripts(){
	//load up the javascript
	wp_enqueue_script('jquery');
	wp_enqueue_script('ubercx-js', plugins_url( '/js/ubercx.js', __FILE__ ), 'jquery');
	wp_enqueue_style( 'ubercx-addr-css',  plugin_dir_url( __FILE__ ). 'css/style.css' );
}

add_action('admin_enqueue_scripts', 'load_ubercx_addr_scripts');
add_action('wp_enqueue_scripts', 'load_ubercx_addr_scripts');

$ubercxAddrVal = new UBERCXAddrVal();

?>
