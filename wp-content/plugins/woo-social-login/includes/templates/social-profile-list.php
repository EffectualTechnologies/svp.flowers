<?php
/**
 * Social Profile List Template
 * 
 * Handles to load social media connected list
 * 
 * Override this template by copying it to yourtheme/woo-social-login/woo-slg-social-profile-list.php
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */

global $woo_slg_model;

$model = $woo_slg_model;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="woo-slg-login-loader">
	<img src="<?php echo WOO_SLG_IMG_URL;?>/social-loader.gif" alt="<?php echo __( 'Social Loader', 'wooslg' );?>"/>
</div>
<div class="woo-social-login-profile woo-slg-social-wrap">
	<h2><?php
		echo __( 'My Social Login Accounts', 'wooslg' );
	?></h2><?php

	if ( $linked_profiles ) { ?>
		<p><?php
			echo $connected_link_heading;
			
			if( $can_link ) {?>
				
				<a class="woo-slg-show-link" href="javascript:void(0);"><?php echo $add_more_link; ?></a><?php 
				
			}?>
		</p>
		<table class="woo-social-login-linked-profiles">
			<thead>
				<tr>
					<th><?php echo __( 'Provider', 'wooslg' ); ?></th>
					<th><?php echo __( 'Account', 'wooslg' ); ?></th>
					<th><?php echo __( 'Last Login', 'wooslg' ); ?></th>
					<th><?php echo __( 'Unlink', 'wooslg' ); ?></th>
				</tr>
			</thead><?php

			foreach ( $linked_profiles as $profile => $value ) {

				$provider		= WOO_SLG_IMG_URL . "/" . $profile . "-provider.png";
				$provider_data	= $model->woo_slg_get_user_common_social_data( $value, $profile );
				?>
				
				<tr>
					<!-- Display provider image-->
					<td data-title="<?php __( 'Provider', 'wooslg' ); ?>">
						<img src="<?php echo $provider; ?>" >
					</td>
					<!-- Display account email id image-->
					<td data-title="<?php __( 'Account', 'wooslg' ); ?>"><?php
						echo !empty( $provider_data['email'] ) ? $provider_data['email'] : $provider_data['name'];
					?></td>
					<td><?php
						$login_timestamp	= woo_slg_get_social_last_login_timestamp( $user_id, $profile );
						
						if( !empty( $login_timestamp ) ) {
							printf( __( '%s @ %s', 'wooslg' ), date_i18n( wc_date_format(), $login_timestamp ), date_i18n( wc_time_format(), $login_timestamp ) );
						} else {
							echo __( 'Never', 'wooslg' );
						}
					?></td>
					<td><?php
						if( $profile != $primary_social ) {?>
							<!-- Display profile unlink url-->
							<a href="javascript:void(0);" class="button woo-slg-social-unlink-profile" id="<?php echo $profile;?>"><?php
								echo __( 'Unlink', 'wooslg' );
							?></a><?php 
						} else {
							echo '<strong>' . __( 'Primary', 'wooslg' ) . '</strong>';
						}
					?></td>
				</tr><?php
			}?>
			<tfoot>
				<tr>
					<th><?php echo __( 'Provider', 'wooslg' ); ?></th>
					<th><?php echo __( 'Account', 'wooslg' ); ?></th>
					<th><?php echo __( 'Last Login', 'wooslg' ); ?></th>
					<th><?php echo __( 'Unlink', 'wooslg' ); ?></th>
				</tr>
			</tfoot>
		</table><?php
	} else {?>

		<p><?php 
			echo $no_social_connected;
			
			if( $can_link ) {?>
				<a class="woo-slg-show-link" href="javascript:void(0);"><?php echo $connect_now_link; ?></a><?php 
			}?>
		</p><?php
	}?>

	<div class="woo-slg-profile-link-container" style="<?php if( $can_link ) { echo 'display:none;'; }?>"><?php
		// display social link buttons
		woo_slg_link_buttons();?>

	</div>
</div>