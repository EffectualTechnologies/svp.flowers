<?php
/**
* template part for header toolbar contacts. views/header/toolbar
*
* @author 	Artbees
* @package 	jupiter/views
* @version 	5.0.0
*/

global $mk_options;

if (!empty($mk_options['header_toolbar_phone'])) { ?>

	<span class="header-toolbar-contact"><i class="mk-moon-phone-3"></i><a href="tel:<?php echo str_replace(' ', '', str_replace('(0)', '', stripslashes($mk_options['header_toolbar_phone']))); ?>"><?php echo stripslashes($mk_options['header_toolbar_phone']); ?></a></span>

<?php 
}
if (!empty($mk_options['header_toolbar_email'])) { ?>

    <span class="header-toolbar-contact">
    	<i class="mk-moon-envelop"></i>
    	<a href="mailto:<?php echo antispambot($mk_options['header_toolbar_email']); ?>"><?php echo antispambot($mk_options['header_toolbar_email']); ?></a>
    </span>

<?php 
}
