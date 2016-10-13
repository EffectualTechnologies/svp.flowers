<?php
    $phpinfo =  pathinfo( __FILE__ );
	$path = $phpinfo['dirname'];
	include( $path . '/config.php' );

?>   

<div class="widget_contact_info mk-contactinfo-shortcode a_margin-bottom-20" <?php echo get_schema_markup('person'); ?>>

	<?php mk_get_view('global', 'shortcode-heading', false, ['title' => $title]); ?>

	<ul>
		<?php if(!empty( $person)) { ?>
			<li><i class="mk-moon-user-7"></i><span itemprop="name"><?php echo $person; ?></span></li>
		<?php } ?>

		<?php if(!empty( $company)) { ?>
			<li><i class="mk-moon-office"></i><span itemprop="jobTitle"><?php echo $company; ?></span></li>
		<?php } ?>

		<?php if(!empty( $address)) { ?>
			<li><i class="mk-icon-home"></i><span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress"><?php echo $address; ?></span></li>
		<?php } ?>

		<?php if(!empty( $phone)) { ?>
			<li><i class="mk-icon-phone"></i><span><?php echo $phone; ?></span></li>
		<?php } ?>

		<?php if(!empty( $fax)) { ?>
			<li><i class="mk-icon-print"></i><span><?php echo $fax; ?></li></span>
		<?php } ?>

		<?php if(!empty( $email)) { ?>
			<li><i class="mk-icon-envelope"></i><span itemprop="email"><a itemprop="email" href="mailto:<?php echo antispambot( $email ); ?>"><?php echo antispambot( $email ); ?></a></span></li>
		<?php } ?>

		<?php if(!empty( $website)) { ?>
			<li><i class="mk-icon-globe"></i><span><a href="<?php echo $website; ?>" itemprop="url"><?php echo $website; ?></a></span></li>
		<?php } ?>

		<?php if(!empty( $skype)) { ?>
			<li><i class="mk-moon-skype"></i><span><a href="skype:<?php echo $skype; ?>?call"><?php echo $skype; ?></a></span></li>
		<?php } ?>
	</ul>
</div>
