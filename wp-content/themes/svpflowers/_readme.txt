/////
update file: /wp-content/themes/svpflowers/views/header/global/secondary-menu-burger-icon.php


/////

In /jupiter/components/shortcodes/mk_button/mk_button.php

replace 

<span class="mk-button--text"><?php echo do_shortcode( strip_tags( $content ) );?></span>

with

<span class="mk-button--text"><?php echo do_shortcode( $content );?></span>


////

In /jupiter/components/shortcodes/mk_portfolio/components/title.php

replace

<h3 class="the-title"><?php echo $link_before; the_title(); echo $link_after; ?></h3><div class="clearboth"></div>

with

<h2><?php echo the_title(); ?></h2><p><?php the_content(); ?></p><div class="clearboth"></div>


////

In /jupiter/framework/metaboxes/metabox-portfolios.php

Set 'Show Featured Image' to false

Set 'Manage Page Elements' default to 'no-title'

Set 'Stick Template?' to true


////

In /jupiter/framework/metaboxes/metabox-skinning.php

Set 'Override Global Settings' to true

Set 'theme_toolbar_toggle' to false

Set 'Header Styles' default to 3

Set 'Transparent Header' default to true

Set 'Enable Transparent Header Skin' default to false