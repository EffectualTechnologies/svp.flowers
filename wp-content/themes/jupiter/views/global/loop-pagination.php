<?php

$maxPages = $view_params['r']->max_num_pages;
// No need to load pagination if all posts are shown and no more posts to load.
if($maxPages <= 1) return false;

// Classic Pagination
if ($view_params['pagination_style'] == 1) {
		?>
		<div class="mk-pagination mk-grid js-el clearfix" data-number-pages="8" data-max-pages="<?php echo $maxPages; ?>" data-mk-component="Pagination">
			
				<a href="#" class="mk-pagination-previous pagination-arrows is-vis-hidden js-pagination-prev"></a>
				<div class="mk-pagination-inner">
					<?php for($i = 0; $i < $maxPages && $i < 10; $i++) { ?>
						<a class="page-number js-pagination-page <?php if($i == 0) echo 'current-page'; ?>" href="#" data-page-id="<?php echo $i + 1; ?>">
							<?php 
								if($i == 9 && $maxPages > 10) echo '...';
								else echo $i + 1; 
							?>
						</a>
					<?php } ?>
				</div>	
				<a href="#" class="mk-pagination-next pagination-arrows js-pagination-next"></a>

				<div class="mk-total-pages">
					<?php _e('page', 'mk_framework'); ?>
					<span class="pagination-current-page js-current-page">1</span>
					<?php _e('of', 'mk_framework'); ?>
					<span class="pagination-max-pages"><?php echo $maxPages; ?></span>
				</div>

		</div>
		<?php 
} 
// Pagination with load more button
else if ($view_params['pagination_style'] == 2) { ?>
<div class="js-loadmore-holder clearfix">
	<a id="mk_load_more_button" class="mk-loadmore-button js-loadmore-button clearfix" href="javascript:;">
		
		<div class="mk-loading-indicator">
			<div class="mk-loading-indicator__inner">
				<div class="mk-loading-indicator__icon"></div>
				<img style="height:100%; width:auto;" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">
			</div>
		</div>
		
		<i class="mk-moon-arrow-down-4"></i>
		<?php _e('Load More', 'mk_framework'); ?>
	</a>
</div>	
<?php } 

// Loading on scroll indicator
else if ($view_params['pagination_style'] == 3) { ?>
	<div class="load-more-scroll js-load-more-scroll"></div>
<?php }
