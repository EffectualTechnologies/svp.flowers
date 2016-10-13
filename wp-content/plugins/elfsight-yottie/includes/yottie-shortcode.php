<?php

if (!defined('ABSPATH')) exit;

function elfsight_yottie_shortcode_get_optons($id) {
	global $wpdb;

	$id = intval($id);
	$wisgts_table_name = elfsight_yottie_widgets_get_table_name();
	$select_sql = '
		SELECT options FROM `' . esc_sql($wisgts_table_name) . '`
		WHERE `id` = "' . esc_sql($id) . '"
	';

	$item = $wpdb->get_row($select_sql, ARRAY_A);
	$options = !empty($item['options']) ? json_decode($item['options'], true) : array();

	return $options;
}

// shortcode [yottie]
function elfsight_yottie_shortcode($atts) {
	global $elfsight_yottie_defaults, $elfsight_yottie_add_scripts;

	$elfsight_yottie_add_scripts = true;

	if (!empty($atts['id'])) {
		$stored_options = elfsight_yottie_shortcode_get_optons($atts['id']);
		$stored_options_prepared = array();
		if (is_array($stored_options)) {
			foreach($stored_options as $name => $value) {
				$stored_options_prepared[ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $name)), '_')] = is_array($value) ? implode(', ', $value) : $value;
			}
		}

		$atts = array_combine(
			array_merge(array_keys($stored_options_prepared), array_keys($atts)),
			array_merge(array_values($stored_options_prepared), array_values($atts))
		);

		unset($atts['id']);
	}

	foreach ($elfsight_yottie_defaults as $name => $value) {
		if (isset($atts[$name]) && is_bool($value)) {
			$atts[$name] = !empty($atts[$name]) && $atts[$name] !== 'false';
		}
	}

	$options = shortcode_atts($defaults = $elfsight_yottie_defaults, $atts, 'yottie');

	$result = '<div data-yt';

	foreach ($options as $name => $value) {
		if ($value !== $elfsight_yottie_defaults[$name]) {

			// boolean
			if (is_bool($value)) {
				$value = $value ? 'true' : 'false';
			}

			// images
			if (($name == 'header_channel_logo' || $name == 'header_channel_banner') && is_numeric($value)) {
				$image_src = wp_get_attachment_image_src($value, 'full');
				if (is_array($image_src)) {
					$value = array_shift($image_src);
				}
			}

			// info
			if (($name == 'header_info' || $name == 'video_info' || $name == 'popup_info') && empty($value)) {
				$value = 'none';
			}

			// source groups
			if ($name == 'source_groups') {
				$value = json_decode(rawurldecode($value));

				if (!is_array($value)) {
					continue;
				}

				foreach($value as $key => $group) {
					if (empty($group->sources)) {
						unset($value[$key]);
					}
					elseif (is_string($group->sources)) {
						$group->sources = preg_split('/[\s\n]/', $group->sources);
					}
				}

				$value = !empty($value) ? rawurlencode(json_encode($value)) : '';
			}

			// responsive
			if ($name == 'content_responsive') {
				$value = json_decode(rawurldecode($value));

				if (is_array($value)) {
					$new_value = array();
					foreach($value as $key => $responsive_item) {
						if (!empty($responsive_item->window_width) && (!empty($responsive_item->columns) || !empty($responsive_item->rows) || !empty($responsive_item->gutter))) {
							$new_value[intval($responsive_item->window_width)] = array(
								'columns' => !empty($responsive_item->columns) ? $responsive_item->columns : '',
								'rows' => !empty($responsive_item->rows) ? $responsive_item->rows : '',
								'gutter' => !empty($responsive_item->gutter) ? $responsive_item->gutter : ''
							);
						}
					}

					$value = $new_value;
				}

				$value = rawurlencode(json_encode($value));
			}

			$result .= sprintf(' data-yt-%s="%s"', str_replace('_', '-', $name), esc_attr($value));
		}
	}

	$result .= '></div>';

	return $result;
}
add_shortcode('yottie', 'elfsight_yottie_shortcode');

?>
