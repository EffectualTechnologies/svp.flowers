<?php

/**
 * Settings class
 */
if ( ! class_exists( 'WooCommerce_Facebook_Checkout_Settings' ) ) {

	class WooCommerce_Facebook_Checkout_Settings {
	
		public $tab_name;
		public $hidden_submit;
		
		/**
		 * Constructor
		 */
		public function __construct() {			
			$this->tab_name = 'woocommerce-facebook-checkout';
			$this->hidden_submit = WooCommerce_Facebook_Checkout::$plugin_prefix . 'submit';
		}

		/**
		 * Load the class
		 */
		public function load() {
			add_action( 'admin_init', array( $this, 'load_hooks' ) );
		}

		/**
		 * Load the admin hooks
		 */
		public function load_hooks() {	
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ) );
			add_action( 'woocommerce_settings_tabs_' . $this->tab_name, array( $this, 'create_settings_page' ) );
			add_action( 'woocommerce_update_options_' . $this->tab_name, array( $this, 'save_settings_page' ) );
		}

		
		/**
		 * Add a tab to the settings page
		 */
		public function add_settings_tab($tabs) {
			$tabs[$this->tab_name] = __( 'Facebook Login', 'woocommerce-facebook-checkout' );
			
			return $tabs;
		}


		/**
		 * Create the settings page content
		 */
		public function create_settings_page() {
			$fbcheckout_enabled 		= get_option( WooCommerce_Facebook_Checkout::$plugin_prefix.'enable_fbcheckout' );
			$checked_value1 = '';
			
			if($fbcheckout_enabled)
				$checked_value1 = 'checked="checked"';
			
			?>
			<div id="icon-options-general" class="icon32"></div>
			<h3><?php _e( 'WooCommerce Facebook Login Checkout', 'woocommerce-facebook-checkout' ); ?></h3>
			
			
			<table width="90%" cellspacing="2">
			<tr>
				<td width="70%">
					<table class="widefat fixed" cellspacing="0">
							<thead>
								<th width="30%">Option</th>
								<th>Setting</th>
							</thead>
							<tbody>
								<tr>
									<td><?php _e( 'Enable Facebook Checkout', 'woocommerce-facebook-checkout' ); ?></td>
									<td>
										<input name="<?php echo WooCommerce_Facebook_Checkout::$plugin_prefix; ?>enable_fbcheckout" type="hidden" value="0" />
										<input name="<?php echo WooCommerce_Facebook_Checkout::$plugin_prefix; ?>enable_fbcheckout" id="<?php echo WooCommerce_Facebook_Checkout::$plugin_prefix; ?>enable_fbcheckout" type="checkbox" value="1" <?php echo $checked_value1; ?>  /> <?php _e( 'Yes', 'woocommerce-facebook-checkout' ); ?>
										<br /><br />
									</td>
								</tr>
								<tr>
									<td><?php _e( 'Facebook App ID', 'woocommerce-facebook-checkout' ); ?><br /><span style="color:#ccc;">(required field if you enabled the extension)</span></td>
									<td><input type="text" name="<?php echo WooCommerce_Facebook_Checkout::$plugin_prefix; ?>app_id" id="<?php echo WooCommerce_Facebook_Checkout::$plugin_prefix; ?>app_id" style="min-width:300px;" value="<?php echo wp_kses_stripslashes( get_option( WooCommerce_Facebook_Checkout::$plugin_prefix . 'app_id' ) ); ?>" /><br /><br /></td>
								</tr>
								<tr>
									<td><?php _e( 'Facebook App Secret', 'woocommerce-facebook-checkout' ); ?></td>
									<td><input type="text" name="<?php echo WooCommerce_Facebook_Checkout::$plugin_prefix; ?>app_secret" id="<?php echo WooCommerce_Facebook_Checkout::$plugin_prefix; ?>app_secret" style="min-width:300px;" value="<?php echo wp_kses_stripslashes( get_option( WooCommerce_Facebook_Checkout::$plugin_prefix . 'app_secret' ) ); ?>" /><br /><br /></td>
								</tr>
								<tr>
									<td colspan=2">
										<br /><br />
									</td>
								</tr>
							
							</tbody>
					</table>
					
				</td>
				
				<td width="30%" style="background:#ececec;padding:10px 5px;">
					<p><b>WooCommerce Facebook Login Checkout</b> is a premium WooCommerce plugin developed by <a href="http://www.terrytsang.com" target="_blank" title="Terry Tsang - a php and symfony developer">Terry Tsang</a> that aims to implement Facebook Login so that new customers can sign in woocommerce site and checkout by using their Facebook account.</p>
					
					<h3>Get More Extensions</h3>
					
					<p>Vist <a href="http://www.terrytsang.com/shop" target="_blank" title="Premium &amp; Free Extensions/Plugins for E-Commerce by Terry Tsang">My Shop</a> to get more free and premium extensions/plugins for your ecommerce platform.</p>
					
					<h3>Thank you for your support!</h3>
				</td>
				
			</tr>
			<tr><td colspan="2"><input type="hidden" name="<?php echo WooCommerce_Facebook_Checkout::$plugin_prefix; ?>submit" value="submitted"></td></tr>
						
			</table>
			
			
			<br />
			<?php
		}
		
		/**
		 * Get the content for an option
		 */
		public function get_setting( $name ) {
			return get_option( WooCommerce_Facebook_Checkout::$plugin_prefix . $name );
		}
		
		/**
		 * Save all settings
		 */
		public function save_settings_page() {
			if ( isset( $_POST[ $this->hidden_submit ] ) && $_POST[ $this->hidden_submit ] == 'submitted' ) {
				
				foreach ( $_POST as $key => $value ) {
					if ( $key != $this->hidden_submit && strpos( $key, WooCommerce_Facebook_Checkout::$plugin_prefix ) !== false ) {
						if ( get_option( $key ) != $value ) {
							update_option( $key, $value );
						}
					}
				}

			}
		}
	
	}
	
}

?>