<?php
global $mk_options;

if ($mk_options['single_blog_social'] == 'true'){ 
?>
<span class="blog-share-container">
    <span class="mk-blog-share mk-toggle-trigger"><i class="mk-moon-share-2"></i></span>
    <ul class="blog-social-share mk-box-to-trigger">
	    <li><a class="facebook-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-facebook"></i></a></li>
	    <li><a class="twitter-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-twitter"></i></a></li>
	    <li><a class="googleplus-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-googleplus"></i></a></li>
	    <li><a class="pinterest-share" data-image="<?php echo wp_get_attachment_image_src(get_post_thumbnail_id() , 'full', true) [0]; ?>" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-pinterest"></i></a></li>
	    <li><a class="linkedin-share" data-desc="<?php echo esc_attr(get_the_excerpt()); ?>" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-linkedin"></i></a></li>
    </ul>
</span>
<?php    
}