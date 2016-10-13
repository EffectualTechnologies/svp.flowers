<?php
/*
	Plugin Name: WooMedia WooCommerce UPS
	Plugin URI: http://www.woomedia.info/en/plugins/ups-shipping-method-for-woocommerce
	Description: Automatic Shipping Calculation using the UPS Shipping API for WooCommerce
	Version: 2.2.0
	Author: WooMedia Inc.
	Author URI: http://www.woomedia.info
	Requires at least: 4.0
	Tested up to: 4.4.1
	
	Copyright: © 2012-2015 WooMedia Inc.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

require_once("syn-includes/syn-functions.php");
require_once("syn-shipping/syn-functions.php");

function syn_ups_update_init(){
	$syn_update = new SYN_Auto_Update( get_plugin_data(__FILE__), plugin_basename( __FILE__ ), '4495975', 'FW8LYpxrj8jh0jkgdso1vP4NV' );
}
add_action('admin_init', 'syn_ups_update_init', 11);

define('UPS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ));

function syn_ups_activate(){
	syn_clear_transients( 'sups' );
}
register_activation_hook( __FILE__, 'syn_ups_activate' );

/**
 * Localisation
 */
load_plugin_textdomain( 'syn_ups', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Check if WooCommerce is active
 */
if ( is_woo_enabled() ) {
	
	update_option( 'urestactivate', true );
	$activate_restrictions = get_option( 'urestactivate', false );
	$shipping_debug = get_option( 'shipping_debug', false );

	/**
	 * syn_ups_init function.
	 *
	 * @access public
	 * @return void
	 */
	function syn_ups_init() {
		if ( ! class_exists( 'SYN_Shipping_UPS' ) )
			include_once( 'classes/class-syn-shipping-ups.php' );
			
		$met = new SYN_Shipping_UPS();
		if( $met->debug && $met->is_enabled() ){
			wp_register_style( 'syn-debug', plugins_url( 'assets/css/debug.css', __FILE__ ) );
			wp_enqueue_style( 'syn-debug' );
		}
		
		add_action('admin_enqueue_scripts', 'syn_ups_admin_scripts');
	}

	add_action( 'woocommerce_shipping_init', 'syn_ups_init' );
	add_action( 'init', 'syn_ups_init', 1 );
	
	function syn_ups_admin_scripts(){
		
		wp_enqueue_script('ups_product_script', plugins_url('synmedia-woocommerce-shipping-ups/assets/js/jquery.ups.product.js'), array('jquery'), '1.0');
		
	}

	/**
	 * syn_ups_add_method function.
	 *
	 * @access public
	 * @param mixed $methods
	 * @return void
	 */
	function syn_ups_add_method( $methods ) {
		$methods[] = 'SYN_Shipping_UPS';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'syn_ups_add_method' );

	/**
	 * Display a notice ...
	 * @return void
	 */
	function syn_ups_notices() {
	
		global $woocommerce;
	
		$missings = array();
	
		if ( ! class_exists( 'SYN_Shipping_UPS' ) )
			include_once( 'classes/class-syn-shipping-ups.php' );
	
		$ups = new SYN_Shipping_UPS();
		
		if( !$ups->has_enabled_address() ){
		
			$missings[] = "Origin address";
			
		}
		
		if( empty($ups->access_license_number) ){
		
			$missings[] = "Access number";
			
		}
		
		if( empty($ups->user_id) ){
		
			$missings[] = "User ID";
			
		}
		
		if( empty($ups->password) ){
		
			$missings[] = "Password";
			
		}
		
		if( empty($ups->shipper_number) ){
		
			$missings[] = "Shipper number";
			
		}
		
		if( empty( $missings ) )
			return false;
		
		$url = self_admin_url( 'admin.php?page=' . ( version_compare($woocommerce->version, '2.1.0') >= 0 ? 'wc-settings' : 'woocommerce_settings' ) . '&tab=shipping&section=syn_shipping_ups' );

		$message = sprintf( __( 'UPS error, some fields are missing: %s' , 'syn_ups' ), implode( ", ", $missings ) );

		echo '<div class="error fade"><p><a href="' . $url . '">' . $message . '</a></p></div>' . "\n";
	
	}

	add_action( 'admin_notices', 'syn_ups_notices' );
	
	/**
	 * Show action links on the plugin screen
	 */
	function syn_ups_action_links( $links ) {
		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=' . ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ? 'wc-settings' : 'woocommerce_settings' ) . '&tab=shipping&section=syn_shipping_ups' ) . '">' . __( 'Settings', 'syn_ups' ) . '</a>'
		), $links );
	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'syn_ups_action_links' );
	
	function syn_ups_woocommerce_product_options_shipping(){
		
		global $post, $woocommerce;
		$thepostid = $post->ID;
		
		if ( ! class_exists( 'SYN_Shipping_UPS' ) )
			include_once( 'classes/class-syn-shipping-ups.php' );
	
		$ups = new SYN_Shipping_UPS();
		
		echo '</div>';
		echo '<div class="options_group">';

		// UPS method Restrictions
		$ups_method_restriction = get_post_meta( $thepostid, '_ups_method_restriction', true );
		
		if( $ups_method_restriction && ! isset( $ups_method_restriction[ 'new_version' ] ) ){
			
			$ups_method_restriction = array(
				'new_version'	=> true,
				'restrictions'	=> array(
					array(
						'method_restrictions'	=> $ups_method_restriction,
						'country_restrictions'	=> array()
					)
				)
			);
			
		}
		
		if( ! $ups_method_restriction )
			$ups_method_restriction = array(
				'new_version'	=> true,
				'restrictions'	=> array(
					array(
						'method_restrictions'	=> array(),
						'country_restrictions'	=> array()
					)
				)
			);
		?>
		
		<div id="ups_restrictions">
		<?php foreach( $ups_method_restriction[ 'restrictions' ] as $k => $restriction ){ ?>
		
		<div class="ups_restriction">
		<p class="form-field dimensions_field"><label for="product_ups_method_restriction"><?php _e( 'UPS restriction', 'syn_ups' ); ?></label> 
		<select name="product_ups_method_restriction[<?php echo $k; ?>][]" id="product_ups_method_restriction_<?php echo $k; ?>" class="short multiselect chosen_select method_restriction_select" multiple="multiple" placeholder="Choose a location or leave empty for all...">
			<?php if( !empty( $ups->custom_methods ) ){ ?>
			<?php foreach( $ups->custom_methods as $method_key => $service ){ ?>
			<option value="<?php echo( $method_key ); ?>"<?php if( array_search( $method_key, $restriction[ 'method_restrictions' ] ) !== false ) echo( ' selected="selected"' ); ?>><?php echo esc_attr( $service['name'] ) ?></option>
			<?php } ?>
			<?php } ?>
		</select>
		<select name="product_ups_method_restriction_country[<?php echo $k; ?>][]" id="product_ups_method_restriction_country_<?php echo $k; ?>" class="short multiselect chosen_select country_restriction_select" multiple="multiple">
		<?php
		
		if ( isset( $woocommerce->countries->countries ) && ! empty( $woocommerce->countries->countries ) ){
		
			foreach ( $woocommerce->countries->countries as $key => $country ){
			
				if ( $states = $woocommerce->countries->get_states( $key ) ){
					
					echo '<optgroup label="' . esc_attr( $country ) . '">';
					
					foreach ( $states as $state_key => $state_value ){
						
						echo '<option value="' . esc_attr( $key ) . ':' . $state_key . '"' . ( in_array( esc_attr( $key ) . ':' . $state_key, $restriction[ 'country_restrictions' ] ) ? ' selected="selected"' : '' ) . '>';
						
						echo $country . ' &mdash; ' . $state_value . '</option>';
						
					}
					
					echo '</optgroup>';
					
				} else {
					
					echo '<option value="' . $key . '"' . ( in_array( esc_attr( $key ), $restriction[ 'country_restrictions' ] ) ? ' selected="selected"' : '' ) . '>' . $country . '</option>';
					
				}
			
			}
		
		}
		
		?>
		</select>
		</div>
		<?php } ?>
		</div>
		
		
		<img class="help_tip" data-tip="<?php esc_attr_e( 'Restrict UPS shipping method for this product. Only this method can be used.', 'syn_ups' ); ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" style="display: none;" />
		</p>
		
		<div id="ups_restriction_template" style="display: none;">
			<div class="ups_restriction">
			<p class="form-field dimensions_field"><label for="product_ups_method_restriction"><?php _e( 'UPS restriction', 'syn_ups' ); ?></label> 
		<select name="product_ups_method_restriction[#k#][]" id="product_ups_method_restriction_#k#" class="short multiselect method_restriction_select" multiple="multiple">
			<?php if( !empty( $ups->custom_methods ) ){ ?>
			<?php foreach( $ups->custom_methods as $method_key => $service ){ ?>
			<option value="<?php echo( $method_key ); ?>"><?php echo esc_attr( $service['name'] ) ?></option>
			<?php } ?>
			<?php } ?>
		</select>
		<select name="product_ups_method_restriction_country[#k#][]" id="product_ups_method_restriction_country_#k#" class="short multiselect country_restriction_select" multiple="multiple">
		<?php
		
		if ( isset( $woocommerce->countries->countries ) && ! empty( $woocommerce->countries->countries ) ){
		
			foreach ( $woocommerce->countries->countries as $key => $country ){
			
				if ( $states = $woocommerce->countries->get_states( $key ) ){
					
					echo '<optgroup label="' . esc_attr( $country ) . '">';
					
					foreach ( $states as $state_key => $state_value ){
						
						echo '<option value="' . esc_attr( $key ) . ':' . $state_key . '">';
						
						echo $country . ' &mdash; ' . $state_value . '</option>';
						
					}
					
					echo '</optgroup>';
					
				} else {
					
					echo '<option value="' . $key . '">' . $country . '</option>';
					
				}
			
			}
		
		}
		
		?>
		</select>
			</p>
			</div>
		</div>
		
		<p class="toolbar">
			<button type="button" class="button button-primary add_ups_restriction" style="float:right;">Add</button>
		</p>
		<?php
		
	}
	
	if( $activate_restrictions )
		add_action( 'woocommerce_product_options_shipping', 'syn_ups_woocommerce_product_options_shipping' );
	
	
	function syn_ups_process_product_meta( $post_id ){
		
		$ups_method_restriction = array(
			'new_version'	=> true,
			'restrictions'	=> array()
		);
		
		if( isset( $_POST[ 'product_ups_method_restriction' ] ) && count( $_POST[ 'product_ups_method_restriction' ] ) > 0 ){
			foreach( $_POST[ 'product_ups_method_restriction' ] as $key => $restrictions ){
				$ups_method_restriction[ 'restrictions' ][] = array(
					'method_restrictions'	=> $restrictions,
					'country_restrictions'	=> isset( $_POST[ 'product_ups_method_restriction_country' ][ $key ] ) ? $_POST[ 'product_ups_method_restriction_country' ][ $key ] : array()
				);
			}
		}
		
		update_post_meta( $post_id, '_ups_method_restriction', $ups_method_restriction );
		
	}
	
	if( $activate_restrictions ){
		add_action( 'woocommerce_process_product_meta_simple', 'syn_ups_process_product_meta' );
		add_action( 'woocommerce_process_product_meta_variable', 'syn_ups_process_product_meta' );
	}
	
	function syn_ups_woocommerce_product_options_shipping_warehouse(){
		
		global $post;
		$thepostid = $post->ID;
		
		if ( ! class_exists( 'SYN_Shipping_UPS' ) )
			include_once( 'classes/class-syn-shipping-ups.php' );
	
		$ups = new SYN_Shipping_UPS();
		
		echo '</div>';
		echo '<div class="options_group">';

		// UPS method Restrictions
		$ups_warehouses = get_post_meta( $thepostid, '_ups_warehouses', true );
		
		if( ! $ups_warehouses )
			$ups_warehouses = array();

		?><p class="form-field dimensions_field"><label for="product_ups_warehouse"><?php _e( 'Warehouse', 'syn_ups' ); ?></label> 
		<select name="product_ups_warehouse[]" id="product_ups_warehouse" class="short multiselect chosen_select" multiple="multiple">
			<?php if( !empty( $ups->addresses ) ){ ?>
			<?php foreach( $ups->addresses as $address_key => $address ){ ?>
			<option value="<?php echo( $address_key ); ?>"<?php if( array_search( $address_key, $ups_warehouses ) !== false ) echo( ' selected="selected"' ); ?>><?php echo esc_attr( $address['title'] ) ?></option>
			<?php } ?>
			<?php } ?>
		</select>
		 <img class="help_tip" data-tip="<?php esc_attr_e( 'Select which warehouse has this product.', 'syn_ups' ); ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" /></p><?php
		
	}
	
	if( $shipping_debug )
		add_action( 'woocommerce_product_options_shipping', 'syn_ups_woocommerce_product_options_shipping_warehouse' );
	
	
	function syn_ups_process_product_meta_warehouse( $post_id ){
		
		/*
$ups_method_restriction = isset( $_POST['product_ups_method_restriction'] ) ? $_POST['product_ups_method_restriction'] : array();
		update_post_meta( $post_id, '_ups_method_restriction', $ups_method_restriction );
*/
		
	}
	
	if( $shipping_debug ){
		add_action( 'woocommerce_process_product_meta_simple', 'syn_ups_process_product_meta_warehouse' );
		add_action( 'woocommerce_process_product_meta_variable', 'syn_ups_process_product_meta_warehouse' );
	}

}

?>