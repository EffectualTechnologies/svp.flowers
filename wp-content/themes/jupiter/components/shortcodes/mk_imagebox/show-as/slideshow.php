<div class="mk-imagebox">       
 
	<?php if($view_params['scroll_nav'] == 'true') { ?>
        <div class="swiper-navigation mk-swipe-slideshow-nav-<?php echo $view_params['id']; ?>">
            <a class="mk-swiper-prev swiper-arrows" data-direction="prev"><i class="mk-jupiter-icon-arrow-left"></i></a>
            <a class="mk-swiper-next swiper-arrows" data-direction="next"><i class="mk-jupiter-icon-arrow-right"></i></a>
        </div>
    <?php } ?>

    <div id="mk-imagebox-<?php echo $view_params['id']; ?>" class="mk-swiper-container slide-style <?php echo $view_params['el_class']; ?>   js-el" 
		data-mk-component='SwipeSlideshow'
		data-swipeSlideshow-config='{
			"effect" : "slide",
			"slide" : ".mk-slider-holder > div",
			"slidesPerView" : "<?php echo  $view_params['per_view'] ?>",
			"displayTime" : "<?php echo  $view_params['slideshow_speed'] ?>",
			"transitionTime" : "<?php echo$view_params['animation_speed'] ?>",
			"nav" : ".mk-swipe-slideshow-nav-<?php echo $view_params['id']; ?>",
			"hasNav" : true,
			"fluidHeight" : true }'>

        <div class="mk-swiper-wrapper mk-slider-holder"><?php echo wpb_js_remove_wpautop( $view_params['content'], true ); ?></div>
       
    </div>
</div>