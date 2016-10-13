<?php

if (!defined('ABSPATH')) exit;


// register styles and scripts
function elfsight_yottie_lib() {
	global $elfsight_yottie_add_scripts;

	$force_script_add = get_option('elfsight_yottie_force_script_add');

	$uploads_dir_params = wp_upload_dir();
	$uploads_dir = $uploads_dir_params['basedir'] . '/' . ELFSIGHT_YOTTIE_SLUG;
	$uploads_url = $uploads_dir_params['baseurl'] . '/' . ELFSIGHT_YOTTIE_SLUG;

	wp_register_script('yottie', plugins_url('assets/yottie/dist/jquery.yottie.bundled.js', ELFSIGHT_YOTTIE_FILE), array(), ELFSIGHT_YOTTIE_VERSION);
	wp_register_script('yottie-custom', $uploads_url . '/yottie-custom.js', array('yottie'), ELFSIGHT_YOTTIE_VERSION);

	wp_register_style('yottie-custom', $uploads_url . '/yottie-custom.css', array(), ELFSIGHT_YOTTIE_VERSION);

	if ($elfsight_yottie_add_scripts || $force_script_add === 'on') {
		$custom_css_path = $uploads_dir . '/yottie-custom.css';
		$custom_js_path = $uploads_dir . '/yottie-custom.js';

		wp_print_scripts('yottie');

		if (is_readable($custom_js_path) && filesize($custom_js_path) > 0) {
			wp_print_scripts('yottie-custom');
		}

		if (is_readable($custom_css_path) && filesize($custom_css_path) > 0) {
			wp_print_styles('yottie-custom');
		}
	}
}
add_action('wp_footer', 'elfsight_yottie_lib');

?>
