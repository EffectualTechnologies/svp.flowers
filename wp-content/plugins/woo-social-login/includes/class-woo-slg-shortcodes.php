<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcodes Class
 *
 * Handles shortcodes functionality of plugin
 *
 * @package WooCommerce - Social Login
 * @since 1.1.0
 */
class WOO_Slg_Shortcodes {
	
	var $model,$render;
	
	function __construct(){
		
		global $woo_slg_render,$woo_slg_model;
		
		$this->render = $woo_slg_render;
		$this->model = $woo_slg_model;
		
	}
	
	/**
	 * Show All Social Login Buttons
	 * 
	 * Handles to show all social login buttons on the viewing page
	 * whereever user put shortcode
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.1.0
	 */
	
	public function woo_slg_social_login( $atts, $content ) {
	
		global $woo_slg_options, $post;
		
		extract( shortcode_atts( array(	
			'title'				=>	'',
	    	'redirect_url'		=>	'',
	    	'showonpage'		=>	false,
	    	'expand_collapse'	=>	'',
		), $atts ) );
		
		$showbuttons = true;
		
		// if show only on inners pages is set and current page is not inner page 
		if( !empty( $showonpage ) &&  !is_singular() ) { $showbuttons = false; }
		
		//check show social buttons or not
		if( $showbuttons ) {
			
			//check user is logged in to site or not and any single social login button is enable or not
			if( !is_user_logged_in() && woo_slg_check_social_enable() ) {
				
				// login heading from setting page
				$login_heading = isset( $woo_slg_options['woo_slg_login_heading'] ) ? $woo_slg_options['woo_slg_login_heading'] : '';
				//  check title first from shortcode
				$login_heading = !empty( $title ) ? $title : $login_heading;
				
				// get redirect url from settings 
				$defaulturl = isset( $woo_slg_options['woo_slg_redirect_url'] ) && !empty( $woo_slg_options['woo_slg_redirect_url'] ) 
									? $woo_slg_options['woo_slg_redirect_url'] : woo_slg_get_current_page_url();
				
				//redirect url for shortcode
				$defaulturl = isset( $redirect_url ) && !empty( $redirect_url ) ? $redirect_url : $defaulturl; 
				
				//session create for access token & secrets		
				$_SESSION['woo_slg_stcd_redirect_url'] = $defaulturl;

				// get html for all social login buttons
				ob_start();

				$expand_collapse_class	= '';
				$expand_collapse_enable = false;

				if( trim( $expand_collapse ) != '' ) {

					$expand_collapse_class	= $expand_collapse == "collapse" ? ' woo-slg-hide' : '';
					$expand_collapse_enable = true;
				}

				if( $expand_collapse_enable ) {

					echo '<p class="woo-slg-info">'. __($login_heading, 'wooslg'). 
							' <a href="javascript:void(0);" class="woo-slg-show-social-login">'.
								 __( 'Click here to login', 'wooslg' ).
							'</a>
						  </p>';
					
					$expand_collapse_class	.= ' woo-slg-social-container-checkout';
				}

				echo '<fieldset id="woo_slg_social_login" class="woo-slg-social-container'. $expand_collapse_class .'">';
				if( !empty($login_heading) && $expand_collapse_enable == false ) {
					echo '<span><legend>'. $login_heading.'</legend></span>';
				}
				
				$this->render->woo_slg_social_login_inner_buttons( $redirect_url );
				
				echo '</fieldset><!--#woo_slg_social_login-->';
				
				$content .= ob_get_clean();
			}
		}
		return $content;
	}
	
	/**
	 * Adding Hooks
	 * 
	 * Adding hooks for calling shortcodes.
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.1.0
	 */
	public function add_hooks() {
		
		//add shortcode to show all social login buttons
		add_shortcode( 'woo_social_login', array( $this, 'woo_slg_social_login' ) );
	}
}
?>