<?php
/**
 * template part for footer navigate to top module. views/footer
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */



global $mk_options;

if ($mk_options['go_to_top'] != 'false') { 
?>

<a href="#top-of-page" class="mk-go-top  js-smooth-scroll js-bottom-corner-btn js-bottom-corner-btn--back">
	<i class="mk-icon-chevron-up"></i>
</a>

<?php 

}