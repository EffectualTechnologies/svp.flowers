<?php

/**
 * template part for header start tour module. views/header/master
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */

global $mk_options;

if (empty($mk_options['header_start_tour_text'])) return false;

?>

<a href="<?php echo $mk_options['header_start_tour_page']; ?>" class="mk-header-start-tour add-header-height">
    <?php echo $mk_options['header_start_tour_text']; ?>
    <i class="mk-icon-caret-right"></i>
</a>
