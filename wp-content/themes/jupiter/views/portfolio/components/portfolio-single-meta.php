<?php
global $mk_options;

 if ( $mk_options['single_portfolio_cats'] == 'true' ) : ?>
		<span class="portfolio-single-cat"><?php echo implode( ', ', mk_get_custom_tax(get_the_id(), 'portfolio', true) ); ?></span>
<?php endif; ?>


<?php if ( $mk_options['single_portfolio_social'] == 'true' && get_post_meta( $post->ID, '_portfolio_social', true ) != 'false' ) : ?>

		<div class="single-social-section portfolio-social-share">
		    <div class="mk-love-holder"><?php echo mk_love_this(); ?></div>

		    <div class="blog-share-container">
		        <div class="blog-single-share mk-toggle-trigger"><i class="mk-moon-share-2"></i></div>

		        <ul class="single-share-box mk-box-to-trigger">
		            <li><a class="facebook-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-facebook"></i></a></li>
		            <li><a class="twitter-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-moon-twitter"></i></a></li>
		            <li><a class="googleplus-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-googleplus"></i></a></li>
		            <li><a class="linkedin-share" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-linkedin"></i></a></li>
		            <li><a class="pinterest-share" data-image="<?php echo $image_src_array[0]; ?>" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo get_permalink(); ?>" href="#"><i class="mk-jupiter-icon-simple-pinterest"></i></a></li>
		        </ul>
		    </div>
		</div>

<?php endif; ?>
<div class="clearboth"></div>