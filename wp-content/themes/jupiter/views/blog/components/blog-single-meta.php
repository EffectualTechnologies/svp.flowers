<?php

/**
 * template part for blog single meta single.php. views/blog/components
 *
 * @author      Artbees
 * @package     jupiter/views
 * @version     5.0.0
 */

if(mk_get_blog_single_style() == 'bold') return false;

global $mk_options;


if($mk_options['blog_single_title'] == 'true') : ?>
	<?php if($mk_options['blog_single_title'] == 'true') : ?>
			<h2 class="blog-single-title"><?php the_title(); ?></h2>
	<?php endif; ?>
<?php endif; ?>


<?php if($mk_options['single_meta_section'] == 'true' && get_post_meta( $post->ID, '_disable_meta', true ) != 'false') : ?>
<div class="blog-single-meta">
	<div class="mk-blog-author"><?php _e('By', 'mk_framework'); ?> <?php the_author_posts_link(); ?></div>
		<time class="mk-post-date" datetime="<?php the_date('Y-m-d') ?>">
			<?php _e('Posted', 'mk_framework'); ?> <a href="<?php echo get_month_link( get_the_time( "Y" ), get_the_time( "m" ) ); ?>"><?php echo get_the_date(); ?></a>
		</time>
		<div class="mk-post-cat"> <?php _e('In', 'mk_framework'); ?> <?php the_category( ', ' ) ?></div>
</div>
<?php endif; ?>



<div class="single-social-section">

	<div class="mk-love-holder"><?php echo mk_love_this(); ?></div>

	<?php
	if($mk_options['blog_single_comments'] == 'true') :
			if ( get_post_meta( $post->ID, '_disable_comments', true ) != 'false' ) { ?>
		<a href="<?php echo get_permalink(); ?>#comments" class="blog-modern-comment"><i class="mk-moon-bubble-9"></i><span> <?php echo comments_number( '0', '1', '%'); ?></span></a><?php
			}
		endif;
	?>

	<?php if($mk_options['single_blog_social'] == 'true' ) : ?>
	<div class="blog-share-container">
		<div class="blog-single-share mk-toggle-trigger"><i class="mk-moon-share-2"></i></div>
		<ul class="single-share-box mk-box-to-trigger">
			<li><a class="facebook-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-facebook"></i></a></li>
			<li><a class="twitter-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-moon-twitter"></i></a></li>
			<li><a class="googleplus-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-googleplus"></i></a></li>
			<li><a class="pinterest-share" data-image="<?php echo $image_src_array[0]; ?>" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-pinterest"></i></a></li>
			<li><a class="linkedin-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-linkedin"></i></a></li>
		</ul>
	</div>
	<?php endif; ?>

	<a class="mk-blog-print" onClick="window.print()" href="#" title="<?php _e('Print', 'mk_framework'); ?>"><i class="mk-moon-print-3"></i></a>
<div class="clearboth"></div>
</div>