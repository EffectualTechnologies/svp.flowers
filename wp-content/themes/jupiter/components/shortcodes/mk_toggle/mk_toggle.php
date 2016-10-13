<?php
$path = pathinfo(__FILE__) ['dirname'];

include ($path . '/config.php');
$id = Mk_Static_Files::shortcode_id();

$output = '';

if(!empty( $icon )) {
    $icon = (strpos($icon, 'mk-') !== FALSE) ? ( $icon ) : ( 'mk-'.$icon );    
} else {
	$icon = '';
}
?>

<div id="mk-toggle-<?php echo $id;?>" class="mk-toggle <?php echo $style;?>-style <?php echo $el_class;?>">
	<?php if( $icon && $style != 'simple' ) : ?>
		<span class="mk-toggle-title">
			<i class="<?php echo $icon?>"></i>
			<span><?php echo $title; ?></span>
		</span>
	<?php else : ?>
		<span class="mk-toggle-title">
			<?php echo $title; ?>
		</span>
	<?php endif; ?>

	<div class="mk-toggle-pane">
		<?php echo wpb_js_remove_wpautop( do_shortcode( trim( $content ) ), true ); ?>
	</div>
</div>