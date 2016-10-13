<?php

/**
 * template part for header toolbar date. views/header/toolbar
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */

global $mk_options;

if ($mk_options['enable_header_date'] != 'true') return false;
?>

<span class="mk-header-date"><i class="mk-moon-clock"></i><?php echo date_i18n("F j, Y"); ?></span>
