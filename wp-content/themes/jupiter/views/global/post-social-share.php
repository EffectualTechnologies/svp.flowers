<?php $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true); ?>
<div class="social-share-container">
    <div class="social-share-trigger mk-toggle-trigger"><i class="mk-moon-share-2"></i></div>
    <ul class="single-share-box mk-box-to-trigger">
        <li><a class="facebook-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-facebook"></i></a></li>
        <li><a class="twitter-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-moon-twitter"></i></a></li>
        <li><a class="googleplus-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-googleplus"></i></a></li>
        <li><a class="pinterest-share" data-image="<?php echo $image_src_array[0]; ?>" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-pinterest"></i></a></li>
        <li><a class="linkedin-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-linkedin"></i></a></li>
    </ul>
</div>