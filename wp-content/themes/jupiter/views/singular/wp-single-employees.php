<?php
global $post,
$mk_options;


if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<?php 
	$style = get_post_meta( $post->ID, '_employees_single_layout', true );
	$header_hero_skin = get_post_meta( $post->ID, '_header_hero_skin', true );
	$header_hero_bg_color = get_post_meta( $post->ID, '_header_hero_bg_color', true );
	$header_hero_bg_image = get_post_meta( $post->ID, '_header_hero_bg_image', true );

	if ($style != 'style3') {
		$image_width = 270;
		$image_height = 270;	
	}else {
		$image_width = 150;
		$image_height = 150;
	}
?>

<?php

if (!function_exists('mk_employees_meta_information')) {
    function mk_employees_meta_information()
    {
        $facebook = get_post_meta( get_the_ID(), '_facebook', true );
        $linkedin = get_post_meta( get_the_ID(), '_linkedin', true );
        $twitter = get_post_meta( get_the_ID(), '_twitter', true );
        $email = get_post_meta( get_the_ID(), '_email', true );
        $googleplus = get_post_meta( get_the_ID(), '_googleplus', true );


        $output = '<span class="employees_meta"><span class="team-member team-member-name s_meta a_align-center a_display-block a_margin-top-40 a_font-weight-bold a_color-333 a_font-22">'.get_the_title().'</span>';
        $output .= '<span class="team-member team-member-position s_meta a_align-center a_display-block a_margin-top-15 a_font-weight-normal a_color-777 a_font-14">'.get_post_meta( get_the_ID(), '_position', true ).'</span>';
        $output .= '<ul class="mk-employeee-networks s_meta">';
        if (!empty($email)) {
            $output.= '<li><a target="_blank" href="mailto:' . antispambot($email) . '" title="' . __('Get In Touch With', 'mk_framework') . ' ' . the_title_attribute(array('echo' => false)) . '"><i class="mk-icon-envelope"></i></a></li>';
        }
        if (!empty($facebook)) {
            $output.= '<li><a target="_blank" href="' . $facebook . '" title="' . the_title_attribute(array('echo' => false)) . ' ' . __('On', 'mk_framework') . ' Facebook"><i class="mk-moon-facebook"></i></a></li>';
        }
        if (!empty($twitter)) {
            $output.= '<li><a target="_blank" href="' . $twitter . '" title="' . the_title_attribute(array('echo' => false)) . ' ' . __('On', 'mk_framework') . ' Twitter"><i class="mk-moon-twitter"></i></a></li>';
        }
        if (!empty($googleplus)) {
            $output.= '<li><a target="_blank" href="' . $googleplus . '" title="' . the_title_attribute(array('echo' => false)) . ' ' . __('On', 'mk_framework') . ' Google Plus"><i class="mk-moon-google-plus"></i></a></li>';
        }
        if (!empty($linkedin)) {
            $output.= '<li><a target="_blank" href="' . $linkedin . '" title="' . the_title_attribute(array('echo' => false)) . ' ' . __('On', 'mk_framework') . ' Linked In"><i class="mk-jupiter-icon-simple-linkedin"></i></a></li>';
        }
        $output.= '</ul></span>';

        echo $output;
    }
}



?>

	<?php if ($style == 'style1'): ?>
				<div class="mk-grid">
				<div class="single-employee-sidebar a_display-inline-block a_float-left">
					<?php mk_get_view('global', 'featured-image', false, ['post_type'=> 'employees', 'width' => $image_width, 'height' => $image_height]); ?>
					<?php mk_employees_meta_information(); ?>
				</div>
				<div class="single-employee-content">
					<?php the_content(); ?>
				</div>
				</div>
	<?php elseif ($style == 'style2'): ?>
				<div class="mk-grid">
				<div class="single-employee-sidebar a_display-inline-block a_float-left">
					<?php mk_get_view('global', 'featured-image', false, ['post_type'=> 'employees', 'width' => $image_width, 'height' => $image_height]); ?>
				</div>
				<div class="single-employee-content">
					<?php mk_employees_meta_information(); ?>
					<?php the_content(); ?>
				</div>
				</div>
	<?php else: ?>
		<div class="single-employee-hero-title a_align-center a_margin-bottom-20 skin-<?php echo $header_hero_skin ?>"
				style="
				background-color:<?php echo $header_hero_bg_color ?>; 
				background-image:url(<?php echo $header_hero_bg_image ?>); 
				background-size: cover; background-position: center center;
				">
			<?php mk_get_view('global', 'featured-image', false, ['post_type'=> 'employees', 'width' => $image_width, 'height' => $image_height]); ?>
			<?php mk_employees_meta_information(); ?>
		</div>
			<div class="mk-grid">
				<div class="single-employee-content">
					<?php the_content(); ?>
				</div>
			</div>
	<?php endif ?>
<?php endwhile;?>