<?php

if (!defined('ABSPATH')) exit;


function elfsight_yottie_vc() {
	global $elfsight_yottie_defaults, $elfsight_yottie_add_scripts;
	extract($elfsight_yottie_defaults, EXTR_SKIP);

	if (!empty($_GET['vc_editable'])) {
		$elfsight_yottie_add_scripts = true;
	}

	if (!$channel) {
		$channel = 'https://www.youtube.com/user/ThePianoGuys';
	}

	vc_map(array(
		'name' => __('Yottie', ELFSIGHT_YOTTIE_TEXTDOMAIN),
		'description' => __('YouTube Channel Plugin', ELFSIGHT_YOTTIE_TEXTDOMAIN),
		'base' => 'yottie',
		'class' => '',
		'category' => __('Social', ELFSIGHT_YOTTIE_TEXTDOMAIN),
		'icon' => plugins_url('assets/img/yottie-vc-icon.png', ELFSIGHT_YOTTIE_FILE),
		'front_enqueue_js' => plugins_url('assets/yottie-vc.js', ELFSIGHT_YOTTIE_FILE),
		'params' => array(
			// SOURCE
			array(
				'type' => 'textfield',
				'heading' => __('YouTube channel URL', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'channel',
				'value' => esc_attr($channel),
				'description' => __('Insert URL of a YouTube channel to display its information and videos in the plugin.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Source'
			),
			array(
				'type' => 'param_group',
				'heading' => __('Source groups', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'source_groups',
				'description' => __('Create custom groups of videos from any YouTube source (channels, playlists, videos).', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __('Group name', ELFSIGHT_YOTTIE_TEXTDOMAIN),
						'param_name' => 'name',
						'admin_label' => true
					),
					array(
						'type' => 'textarea',
						'heading' => __('Sources', ELFSIGHT_YOTTIE_TEXTDOMAIN),
						'param_name' => 'sources',
						'description' => __('List of YouTube source URLs (channels, playlists, videos). Each source on a new row', ELFSIGHT_YOTTIE_TEXTDOMAIN)
					)
				),
				'group' => 'Source'
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Order', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'order',
				'value' => array(
					__('Date: new to old', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'date#desc',
					__('Date: old to new', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'date#asc',
					__('Views: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'views#desc',
					__('Views: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'views#asc',
					__('Likes: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'likes#desc',
					__('Likes: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'likes#asc',
					__('Dislikes: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'dislikes#desc',
					__('Dislikes: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'dislikes#asc',
					__('Position: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'position#desc',
					__('Position: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'position#asc',
					__('Comments: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'comments#desc',
					__('Comments: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'comments#asc',
					__('Random', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'random'
				),
				'std' => esc_attr($order),
				'description' => __('Choose sort order of videos in the gallery.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Source'
			),
			array(
				'type' => 'textfield',
				'heading' => __('Cache time', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'cache_time',
				'value' => esc_attr($cache_time),
				'description' => __('It defines how long a data from YouTube will be cached in a client side database IndexedDB. Set "0" to turn the cache off.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Source'
			),

			// SIZES
			array(
				'type' => 'textfield',
				'heading' => __('Width', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'width',
				'value' => esc_attr($width),
				'description' => __('Plugin width (any CSS valid value: px, %, em, etc). Set "auto" to make the plugin responsive.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Sizes'
			),

			// LANGUAGE
			array(
				'type' => 'dropdown',
				'heading' => __('Language', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'lang',
				'value' => array(
					__('Deutsch', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'de',
					__('English', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'en',
					__('Español', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'es',
					__('Français', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'fr',
					__('Hrvatski', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'hr',
					__('Italiano', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'it',
					__('Nederlands', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'nl',
					__('Norsk', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'no',
					__('Polski', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'pl',
					__('Português', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'pt-BR',
					__('Svenska', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'sv',
					__('Türkçe', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'tr',
					__('Русский', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ru',
					__('हिन्दी', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'hi',
					__('中文', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'zh-HK',
					__('日本語', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ja'
				),
				'std' => esc_attr($lang),
				'description' => __('Choose one of 15 available languages of plugin\'s UI.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Language'
			),

			// HEADER
			array(
				'type' => 'checkbox',
				'param_name' => 'header_visible',
				'value' => array(
					__('Visible', ELFSIGHT_YOTTIE_TEXTDOMAIN) => $header_visible
				),
				'std' => (bool)$header_visible,
				'description' => __('Turn on/off the header in the plugin.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Header'
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Header layout', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'header_layout',
				'value' => array(
					__('Classic', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'classic',
					__('Accent', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'accent',
					__('Minimal', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'minimal'
				),
				'std' => esc_attr($header_layout),
				'description' => __('Choose one of three header layouts: classic (like in YouTube), accent (pay more attention to your channel), minimal (attract more attention to your playlist).', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Header'
			),
			array(
				'type' => 'checkbox',
				'heading' => __('Header info', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'header_info',
				'value' => array(
					__('Logo', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'logo',
					__('Banner', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'banner',
					__('Channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'channelName',
					__('Channel description', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'channelDescription',
					__('Videos counter', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'videosCounter',
					__('Subscribers counter', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'subscribersCounter',
					__('Views counter', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'viewsCounter',
					__('Subscribe button', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'subscribeButton'
				),
				'std' => str_replace(' ', '', $header_info),
				'description' => __('Check info types of your channel which should be displayed in the header.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Header'
			),
			array(
				'type' => 'textfield',
				'heading' => __('Custom channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'header_channel_name',
				'value' => esc_attr($header_channel_name),
				'description' => __('Specify custom channel name instead of your channel name in YouTube.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Header'
			),
			array(
				'type' => 'textarea',
				'heading' => __('Custom channel description', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'header_channel_description',
				'value' => esc_attr($header_channel_description),
				'description' => __('Specify custom channel description instead of your channel description in YouTube.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Header'
			),
			array(
				'type' => 'attach_image',
				'heading' => __('Custom channel logo', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'header_channel_logo',
				'value' => esc_attr($header_channel_logo),
				'description' => __('Specify custom channel logo instead of your channel logo in YouTube. The required image size is 100x100.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Header'
			),
			array(
				'type' => 'attach_image',
				'heading' => __('Custom channel banner', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'header_channel_banner',
				'value' => esc_attr($header_channel_banner),
				'description' => __('Specify custom channel banner instead of your channel banner in YouTube. The required image size is 2120x352.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Header'
			),

			// GROUPS
			array(
				'type' => 'checkbox',
				'param_name' => 'groups_visible',
				'value' => array(
					__('Visible', ELFSIGHT_YOTTIE_TEXTDOMAIN) => $groups_visible
				),
				'std' => (bool)$groups_visible,
				'description' => __('Turn on/off tabs of source groups in the plugin.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Groups'
			),

			// CONTENT
			array(
				'type' => 'textfield',
				'heading' => __('Columns', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'content_columns',
				'value' => esc_attr($content_columns),
				'description' => __('Number of columns in the grid.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'textfield',
				'heading' => __('Rows', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'content_rows',
				'value' => esc_attr($content_rows),
				'description' => __('Number of rows in the grid.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'textfield',
				'heading' => __('Gutter', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'content_gutter',
				'value' => esc_attr($content_gutter),
				'description' => __('Interval between videos in the grid in pixels.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'content_arrows_control',
				'value' => array(
					__('Arrows control', ELFSIGHT_YOTTIE_TEXTDOMAIN) => $content_arrows_control
				),
				'std' => (bool)$content_arrows_control,
				'description' => __('Activate arrows in the video gallery.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'content_scroll_control',
				'value' => array(
					__('Scroll control', ELFSIGHT_YOTTIE_TEXTDOMAIN) => $content_scroll_control
				),
				'std' => (bool)$content_scroll_control,
				'description' => __('Activate scroll in the video gallery.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'content_drag_control',
				'value' => array(
					__('Drag control', ELFSIGHT_YOTTIE_TEXTDOMAIN) => $content_drag_control
				),
				'std' => (bool)$content_drag_control,
				'description' => __('Activate drag in the video gallery.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Direction', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'content_direction',
				'value' => array(
					__('Horizontal', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'horizontal',
					__('Vertical', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'vertical'
				),
				'std' => esc_attr($content_direction),
				'description' => __('Moving direction of video gallery’s slides (horizontal or vertical).', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'content_free_mode',
				'value' => array(
					__('Free mode', ELFSIGHT_YOTTIE_TEXTDOMAIN) => $content_free_mode
				),
				'std' => (bool)$content_free_mode,
				'description' => __('Switch the video gallery in free scroll mode.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'scrollbar',
				'value' => array(
					__('Scrollbar', ELFSIGHT_YOTTIE_TEXTDOMAIN) => $content_scrollbar
				),
				'std' => (bool)$content_scrollbar,
				'description' => __('Show scrollbar in the video gallery.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Transition effect', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'content_transition_effect',
				'value' => array(
					__('Slide', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'slide',
					__('Fade', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'fade',
					__('Coverflow', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'coverflow',
					__('Cube', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'cube',
					__('Flip', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'flip'
				),
				'std' => esc_attr($content_transition_effect),
				'description' => __('Slide, fade, coverflow, cube and flip animation of slide switching.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'textfield',
				'heading' => __('Transition speed', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'content_transition_speed',
				'value' => esc_attr($content_transition_speed),
				'description' => __('Animation speed of slide switching (in ms).', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			/*array(
				'type' => 'dropdown',
				'heading' => __('Transition easing', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'content_transition_easing',
				'value' => array(
					__('linear', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'linear',
					__('ease', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ease',
					__('ease-in', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ease-in',
					__('ease-out', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ease-out',
					__('ease-in-out', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ease-in-out'
				),
				'std' => esc_attr($content_transition_easing),
				'description' => __('Choose animation easing of slide switching.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),*/
			array(
				'type' => 'textfield',
				'heading' => __('Autorotation', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'content_auto',
				'value' => esc_attr($content_auto),
				'description' => __('Autorotation of slides in the video gallery (in ms). Set "0" to turn it off.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'content_auto_pause_on_hover',
				'value' => array(
					__('Pause on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN) => $content_auto_pause_on_hover
				),
				'std' => (bool)$content_auto_pause_on_hover,
				'description' => __('Disable auto slide switching by hovering on the video slider.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Content'
			),
			array(
				'type' => 'param_group',
				'heading' => __('Responsive breakpoints', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'content_responsive',
				'description' => __('Specify the breakpoints to set the columns, rows and gutter in the video gallery grid depending on a window width.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __('Window width', ELFSIGHT_YOTTIE_TEXTDOMAIN),
						'param_name' => 'window_width',
						'description' => __('Window width in pixels', ELFSIGHT_YOTTIE_TEXTDOMAIN),
						'admin_label' => true
					),
					array(
						'type' => 'textfield',
						'heading' => __('Columns', ELFSIGHT_YOTTIE_TEXTDOMAIN),
						'param_name' => 'columns'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Rows', ELFSIGHT_YOTTIE_TEXTDOMAIN),
						'param_name' => 'rows'
					),
					array(
						'type' => 'textfield',
						'heading' => __('Gutter', ELFSIGHT_YOTTIE_TEXTDOMAIN),
						'param_name' => 'gutter'
					)
				),
				'group' => 'Content'
			),

			// VIDEO
			array(
				'type' => 'dropdown',
				'heading' => __('Video layout', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'video_layout',
				'value' => array(
					__('Classic', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'classic',
					__('Horizontal', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'horizontal',
					__('Cinema', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'cinema'
				),
				'std' => esc_attr($video_layout),
				'description' => __('Choose one of three video layouts: classic (video card with full information), cinema (pay more attention to video preview. Info displays on hover), horizontal (appropriate layout for displaying videos in 1-2 columns as a list).', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Video'
			),
			array(
				'type' => 'checkbox',
				'heading' => __('Video info', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'video_info',
				'value' => array(
					__('Play icon', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'playIcon',
					__('Duration', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'duration',
					__('Title', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'title',
					__('Date', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'date',
					__('Description', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'description',
					__('Views counter', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'viewsCounter',
					__('Likes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'likesCounter',
					__('Comments counter', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'commentsCounter'
				),
				'std' => str_replace(' ', '', $video_info),
				'description' => __('Check info types of each video which should be displayed in the video gallery.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Video'
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Play mode', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'video_play_mode',
				'value' => array(
					__('Popup', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'popup',
					__('Inline', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'inline',
					__('YouTube', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'youtube'
				),
				'std' => esc_attr($video_play_mode),
				'description' => __('Choose the mode of watching videos: in popup ("popup"), directly in the video gallery ("inline"), or in a new browser tab right in YouTube ("youtube").', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Video'
			),

			// POPUP
			array(
				'type' => 'checkbox',
				'heading' => __('Popup info', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'popup_info',
				'value' => array(
					__('Title', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'title',
					__('Channel logo', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'channelLogo',
					__('Channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'channelName',
					__('Subscribe button', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'subscribeButton',
					__('Views counter', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'viewsCounter',
					__('Likes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'likesCounter',
					__('Dislikes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'dislikesCounter',
					__('Likes ratio', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'likesRatio',
					__('Date', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'date',
					__('Description', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'description',
					__('Description more button', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'descriptionMoreButton',
					__('Comments', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'comments'
				),
				'std' => str_replace(' ', '', $popup_info),
				'description' => __('Check info types of video which should be displayed in the popup.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Popup'
			),
			array(
				'type' => 'checkbox',
				'param_name' => 'popup_autoplay',
				'value' => array(
					__('Autoplay', ELFSIGHT_YOTTIE_TEXTDOMAIN) => $popup_autoplay
				),
				'std' => (bool)$popup_autoplay,
				'description' => __('Turn on/off autoplay while openning a video in the popup.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Popup'
			),
			/*array(
				'type' => 'textfield',
				'heading' => __('Transition speed', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'popup_transition_speed',
				'value' => esc_attr($popup_transition_speed),
				'description' => __('!!!', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Popup'
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Transition easing', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'popup_transition_easing',
				'value' => array(
					__('linear', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'linear',
					__('ease', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ease',
					__('ease-in', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ease-in',
					__('ease-out', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ease-out',
					__('ease-in-out', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'ease-in-out'
				),
				'std' => esc_attr($popup_transition_easing),
				'description' => __('!!!', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Popup'
			),*/

			// COLORS
			/*array(
				'type' => 'dropdown',
				'heading' => __('Color scheme', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_scheme',
				'value' => array(
					__('Default', ELFSIGHT_YOTTIE_TEXTDOMAIN) => 'default'
				),
				'std' => esc_attr($color_scheme),
				'description' => __('Choose one of three color schemas of plugin\'s UI.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Colors'
			),*/
			array(
				'type' => 'colorpicker',
				'heading' => __('Header background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_header_bg',
				'value' => esc_attr($color_header_bg),
				'description' => __('It redefines the related color in a color scheme.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Header banner overlay', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_header_banner_overlay',
				'value' => esc_attr($color_header_banner_overlay),
				'description' => __('It lays on the header banner for contrast with text. Available in "accent" and "minimal" header layouts.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Header channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_header_channel_name',
				'value' => esc_attr($color_header_channel_name),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Header channel name on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_header_channel_name_hover',
				'value' => esc_attr($color_header_channel_name_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Header channel description', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_header_channel_description',
				'value' => esc_attr($color_header_channel_description),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Header description anchors', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_header_anchor',
				'value' => esc_attr($color_header_anchor),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Header description anchors on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_header_anchor_hover',
				'value' => esc_attr($color_header_anchor_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Header counters', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_header_counters',
				'value' => esc_attr($color_header_counters),
				'group' => 'Colors'
			),

			array(
				'type' => 'colorpicker',
				'heading' => __('Groups background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_groups_bg',
				'value' => esc_attr($color_groups_bg),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Groups link', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_groups_link',
				'value' => esc_attr($color_groups_link),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Groups link on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_groups_link_hover',
				'value' => esc_attr($color_groups_link_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Groups active link', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_groups_link_active',
				'value' => esc_attr($color_groups_link_active),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Groups highlight', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_groups_highlight',
				'value' => esc_attr($color_groups_highlight),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Groups highlight on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_groups_highlight_hover',
				'value' => esc_attr($color_groups_highlight_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Groups highlight active', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_groups_highlight_active',
				'value' => esc_attr($color_groups_highlight_active),
				'group' => 'Colors'
			),

			array(
				'type' => 'colorpicker',
				'heading' => __('Content background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_content_bg',
				'value' => esc_attr($color_content_bg),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Content arrows', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_content_arrows',
				'value' => esc_attr($color_content_arrows),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Content arrows on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_content_arrows_hover',
				'value' => esc_attr($color_content_arrows_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Content arrows background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_content_arrows_bg',
				'value' => esc_attr($color_content_arrows_bg),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Content arrows background on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_content_arrows_bg_hover',
				'value' => esc_attr($color_content_arrows_bg_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Content scrollbar background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_content_scrollbar_bg',
				'value' => esc_attr($color_content_scrollbar_bg),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Content scrollbar slider background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_content_scrollbar_slider_bg',
				'value' => esc_attr($color_content_scrollbar_slider_bg),
				'group' => 'Colors'
			),

			array(
				'type' => 'colorpicker',
				'heading' => __('Video background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_bg',
				'value' => esc_attr($color_video_bg),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video overlay', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_overlay',
				'value' => esc_attr($color_video_overlay),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video play icon', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_play_icon',
				'value' => esc_attr($color_video_play_icon),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video play icon on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_play_icon_hover',
				'value' => esc_attr($color_video_play_icon_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video duration', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_duration',
				'value' => esc_attr($color_video_duration),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video duration background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_duration_bg',
				'value' => esc_attr($color_video_duration_bg),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video title', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_title',
				'value' => esc_attr($color_video_title),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video title on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_title_hover',
				'value' => esc_attr($color_video_title_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video date', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_date',
				'value' => esc_attr($color_video_date),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video description', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_description',
				'value' => esc_attr($color_video_description),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video description anchors', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_anchor',
				'value' => esc_attr($color_video_anchor),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video description anchors on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_anchor_hover',
				'value' => esc_attr($color_video_anchor_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Video counters', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_video_counters',
				'value' => esc_attr($color_video_counters),
				'group' => 'Colors'
			),

			array(
				'type' => 'colorpicker',
				'heading' => __('Popup background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_bg',
				'value' => esc_attr($color_popup_bg),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup anchors', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_anchor',
				'value' => esc_attr($color_popup_anchor),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup anchors on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_anchor_hover',
				'value' => esc_attr($color_popup_anchor_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup overlay', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_overlay',
				'value' => esc_attr($color_popup_overlay),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup title', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_title',
				'value' => esc_attr($color_popup_title),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_channel_name',
				'value' => esc_attr($color_popup_channel_name),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup channel name on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_channel_name_hover',
				'value' => esc_attr($color_popup_channel_name_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup views counter', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_views_counter',
				'value' => esc_attr($color_popup_views_counter),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup likes ratio', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_likes_ratio',
				'value' => esc_attr($color_popup_likes_ratio),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup dislikes ratio', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_dislikes_ratio',
				'value' => esc_attr($color_popup_dislikes_ratio),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup likes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_likes_counter',
				'value' => esc_attr($color_popup_likes_counter),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup dislikes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_dislikes_counter',
				'value' => esc_attr($color_popup_dislikes_counter),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup date', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_date',
				'value' => esc_attr($color_popup_date),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup description', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_description',
				'value' => esc_attr($color_popup_description),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup description more button', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_description_more_button',
				'value' => esc_attr($color_popup_description_more_button),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup description more button on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_description_more_button_hover',
				'value' => esc_attr($color_popup_description_more_button_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup comments username', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_comments_username',
				'value' => esc_attr($color_popup_comments_username),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup comments username on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_comments_username_hover',
				'value' => esc_attr($color_popup_comments_username_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup comments passed time', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_comments_passed_time',
				'value' => esc_attr($color_popup_comments_passed_time),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup comments likes', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_comments_likes',
				'value' => esc_attr($color_popup_comments_likes),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup comments text', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_comments_text',
				'value' => esc_attr($color_popup_comments_text),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup controls', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_controls',
				'value' => esc_attr($color_popup_controls),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup controls on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_controls_hover',
				'value' => esc_attr($color_popup_controls_hover),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup mobile controls', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_controls_mobile',
				'value' => esc_attr($color_popup_controls_mobile),
				'group' => 'Colors'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Popup mobile controls background', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'color_popup_controls_mobile_bg',
				'value' => esc_attr($color_popup_controls_mobile_bg),
				'group' => 'Colors'
			),

			// ADS
			array(
				'type' => 'textfield',
				'heading' => __('AdSense client', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'ads_client',
				'value' => esc_attr($ads_client),
				'description' => __('Yottie supports AdSense Advertisement platform. Specify AdSense client (pubId) to turn it on.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'AdSense'
			),
			array(
				'type' => 'textfield',
				'heading' => __('AdSense content slot', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'ads_slots_content',
				'value' => esc_attr($ads_slots_content),
				'description' => __('Slot identifier for adv block in content (video gallery).', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'AdSense'
			),
			array(
				'type' => 'textfield',
				'heading' => __('AdSense popup slot', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'param_name' => 'ads_slots_popup',
				'value' => esc_attr($ads_slots_popup),
				'description' => __('Slot identifier for adv block in popup.', ELFSIGHT_YOTTIE_TEXTDOMAIN),
				'group' => 'AdSense'
			)
		)
   ));
}
add_action('vc_before_init', 'elfsight_yottie_vc');

?>
