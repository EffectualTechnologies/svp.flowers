<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortocde UI
 *
 * This is the code for the pop up editor, which shows up when an user clicks
 * on the woo social login icon within the WordPress editor.
 *
 * @package WooCommerce - Social Login
 * @since 1.1.1
 * 
 **/

?>

<div class="woo-slg-popup-content">

	<div class="woo-slg-header">
		<div class="woo-slg-header-title"><?php _e( 'Add A Social Login Shortcode', 'wooslg' );?></div>
		<div class="woo-slg-popup-close"><a href="javascript:void(0);" class="woo-slg-close-button"><img src="<?php echo WOO_SLG_IMG_URL;?>/tb-close.png" alt="<?php _e( 'Close', 'wooslg' );?>" /></a></div>
	</div>
	
	<div class="woo-slg-popup">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php _e( 'Select A Shortcode', 'wooslg' );?></label>		
					</th>
					<td>
						<select id="woo_slg_shortcodes">				
							<option value="woo_social_login"><?php _e( 'Social Login', 'wooslg' );?></option>
						</select>		
					</td>
				</tr>
			</tbody>
		</table>
		
		<div id="woo_slg_login_options" class="woo-slg-shortcodes-options">
		
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="woo_slg_title"><?php _e( 'Social Login Title:', 'wooslg' );?></label>		
						</th>
						<td>
							<input type="text" id="woo_slg_title" class="regular-text" value="<?php _e( 'Prefer to Login with Social Media', 'wooslg' );?>" /><br/>
							<span class="description"><?php _e( 'Enter a social login title.', 'wooslg' );?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woo_slg_redirect_url"><?php _e( 'Redirect URL:', 'wooslg' );?></label>		
						</th>
						<td>
							<input type="text" id="woo_slg_redirect_url" class="regular-text" value="" /><br/>
							<span class="description"><?php _e( 'Enter a redirect URL for users after they login with social media. The URL must start with', 'wooslg' ).' http://';?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woo_slg_show_on_page"><?php _e( 'Show Only on Page / Post:', 'wooslg' );?></label>		
						</th>
						<td>
							<input type="checkbox" id="woo_slg_show_on_page" value="1" /><br />
							<span class="description"><?php _e( 'Check this box if you want to show social login buttons only on inner page of posts and pages.', 'wooslg' );?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="woo_slg_enable_expand_collapse"><?php _e( 'Expand/Collapse Buttons:', 'wooslg' );?></label>		
						</th>
						<td>
							<select id="woo_slg_enable_expand_collapse" name="woo_slg_enable_expand_collapse">
								<option value=""><?php _e('None','wooslg');?></option>
								<option value="collapse"><?php _e('Collapse','wooslg');?></option>
								<option value="expand"><?php _e('Expand','wooslg');?></option>
							</select>
							<br />
							<span class="description"><?php _e( 'Here you can select how to show the social login buttons.', 'wooslg' );?></span>
						</td>
					</tr>
				</tbody>
			</table>
			
		</div><!--woo_slg_login_options-->
		
		<div id="woo_slg_insert_container" >
			<input type="button" class="button-secondary" id="woo_slg_insert_shortcode" value="<?php _e( 'Insert Shortcode', 'wooslg' ); ?>">
		</div>
		
	</div><!--.woo-slg-popup-->
	
</div><!--.woo-slg-popup-content-->
<div class="woo-slg-popup-overlay"></div>