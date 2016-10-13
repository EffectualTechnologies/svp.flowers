<?php

if (!defined('ABSPATH')) exit;


require_once(ELFSIGHT_YOTTIE_PATH . '/includes/yottie-update.class.php');


function elfsight_yottie_update() {
	$purchase_code = get_option('elfsight_yottie_purchase_code', '');

	new YottieUpdate(ELFSIGHT_YOTTIE_VERSION, ELFSIGHT_YOTTIE_UPDATE_URL, ELFSIGHT_YOTTIE_PLUGIN_SLUG, $purchase_code);
}
add_action('init', 'elfsight_yottie_update');

?>
