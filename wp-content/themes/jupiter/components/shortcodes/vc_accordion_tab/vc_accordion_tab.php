<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

?>

<div class="mk-accordion-single">
	
	<div class="mk-accordion-tab"><?php echo $icon; ?><span><?php echo $title; ?></span></div>

	<div class="mk-accordion-pane">
		<?php echo wpb_js_remove_wpautop($content, true); ?>
		<div class="clearboth"></div>
	</div>

</div>