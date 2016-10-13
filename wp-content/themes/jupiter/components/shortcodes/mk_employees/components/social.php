<?php 
$facebook = get_post_meta(get_the_ID() , '_facebook', true);
$linkedin = get_post_meta(get_the_ID() , '_linkedin', true);
$twitter = get_post_meta(get_the_ID() , '_twitter', true);
$email = get_post_meta(get_the_ID() , '_email', true);
$googleplus = get_post_meta(get_the_ID() , '_googleplus', true);
$instagram = get_post_meta(get_the_ID() , '_instagram', true);
?>

<ul class="mk-employeee-networks">
	<?php if (!empty($email)) { ?>
		<li><a target="_blank" href="mailto:<?php echo antispambot($email); ?>" title="<?php _e('Get In Touch With', 'mk_framework'); ?> <?php the_title_attribute(); ?>"><i class="mk-icon-envelope"></i></a></li>
	<?php } 
	if (!empty($facebook)) {  ?>
		<li><a target="_blank" href="<?php echo $facebook; ?>" title="<?php the_title_attribute(); ?> <?php _e('On', 'mk_framework'); ?> Facebook"><i class="mk-moon-facebook"></i></a></li>
	<?php }
	if (!empty($twitter)) {  ?>
		<li><a target="_blank" href="<?php echo $twitter; ?>" title="<?php the_title_attribute(); ?> <?php _e('On', 'mk_framework'); ?> Twitter"><i class="mk-moon-twitter"></i></a></li>
	<?php }
	if (!empty($googleplus)) {  ?>
		<li><a target="_blank" href="<?php echo $googleplus; ?>" title="<?php the_title_attribute(); ?> <?php _e('On', 'mk_framework'); ?> Google Plus"><i class="mk-moon-google-plus"></i></a></li>
	<?php }
	if (!empty($linkedin)) {  ?>
		<li><a target="_blank" href="<?php echo $linkedin; ?>" title="<?php the_title_attribute(); ?> <?php _e('On', 'mk_framework'); ?> Linked In"><i class="mk-jupiter-icon-simple-linkedin"></i></a></li>
	<?php }
	if (!empty($instagram)) {  ?>
		<li><a target="_blank" href="<?php echo $instagram; ?>" title="<?php the_title_attribute(); ?> <?php _e('On', 'mk_framework'); ?> Instagram"><i class="mk-icon-instagram"></i></a></li>
	<?php }  ?>
</ul>