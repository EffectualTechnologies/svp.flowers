<?php
class UberCXAddrVal{

	//Name of the settings arrays
	protected $option_name = 'ubercx_addr_settings';  //this is what gets saved
	protected $option_group = 'ubercx_addr_settings'; //our settings group
	protected $section_name = 'ubercx_settings_section1';

	public $enabled;		//is it enabled?


	//The constructor
	public function __construct(){
		add_action( 'admin_menu', array( $this, 'add_to_menu' ),99 );
		add_action('admin_init', array($this, 'init_settings_fields'));
		//inject our custom eror html/jscript into the checkout page
		add_action('woocommerce_checkout_before_customer_details',array(&$this, 'add_error_html'),12);
		//lets hook into the checkout function
		add_action('woocommerce_after_checkout_validation', array(&$this, 'validate_address'));
		//Handles the ajax call
		add_action('wp_ajax_ubercx_get_error', array(&$this, 'get_error'));
                add_action('wp_ajax_nopriv_ubercx_get_error', array(&$this, 'get_error'));
	}
	
	function get_error(){
		$trans = get_transient('ubercx_addr_val');
		//if we have no transient - it means there was no error!
		if($trans==false){
			wp_send_json( null );
		}
		//delete the transient
		delete_transient('ubercx_addr_val');
		//otherwise we have some error!
		wp_send_json( $trans );
	}

	function add_error_html() {
		$html = "
	<div id='ubercx_addr_correction' class='' style='display: none;'>
		<h3>There appears to be a problem with the address. Please correct or select one below.</h3>
		<div id='ubercx_orig_addr'>
			<div id='ubercx_addr_radio' class='ubercx-addr-radio'></div>	
			<div style='display: none;' id='ubercx_orig_placeholder'></div>						
		</div>		
	</div>
	";
		echo $html;
		echo '<div id="ubercx_error_placeholder"></div>';
		
		//add the ajax url var
		$html = '
			<script type="text/javascript">
			var ajaxurl = "' . admin_url('admin-ajax.php') . '";
			</script>				
		';
	
		echo $html;
	}
		
	/**
	 * This puts us on the woo menu
	 */
	public function add_to_menu() {

		$this->page = add_submenu_page(
			'woocommerce',
		__( 'snapCX Address Validation', UBERCX_ADDR_DOMAIN ),
		__( 'snapCX Address Validation', UBERCX_ADDR_DOMAIN ),
			'manage_woocommerce',
			'UBERCX_ADDR_MENU',
		array( $this, 'render_settings' )
		);
	}

	/**
	 ** This initialises the settings field
	 */
	function init_settings_fields(){
			
		register_setting(
				'ubercx_option_group', // Option group
				'ubercx_option_name', // Option name
				array( $this, 'sanitize')
		);

		add_settings_section(
				'ubercx_section_setting_id', // ID in the html
		__('snapCX Address Validation Settings', UBERCX_ADDR_DOMAIN), // Title
		array($this, 'print_section_info'), // Callback
				'ubercx-setting-admin' // Page
		);

		add_settings_field(
				'enable',
		__( 'Enable/Disable', UBERCX_ADDR_DOMAIN ),
		array($this, 'render_enable_setting'),
				'ubercx-setting-admin',
				'ubercx_section_setting_id'
				);

				add_settings_field(
				'user_key',
				__( 'User Key', UBERCX_ADDR_DOMAIN ),
				array($this, 'render_user_key'),
				'ubercx-setting-admin',
				'ubercx_section_setting_id'
				);
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
		//print 'Enter your settings below:';
	}

	/**
	 * This renders the main page for the plugin - all the front-end fun happens here!!
	 */
	public function render_settings(){
?>

<div class="wrap">
<?php screen_icon(); ?>
	<h2>snapCX Settings</h2>
	<p><i>Enter your snapCX User Key here. If you do not have one, <a target="_blank"  href="https://snapcx.io/signup?utm_source=wordpress&utm_medium=plugin&utm_campaign=avs">sign up for a FREE snapCX account here</a> NO credit card required</i></p>
	
	<form method="post" action="options.php">
	<?php
	// This prints out all hidden setting fields
	settings_fields('ubercx_option_group');
	do_settings_sections('ubercx-setting-admin');
	submit_button();
	?>
	</form>
</div>

	<?php
	}

	//*************************************
	//Functions to render the fields
	//*************************************
	function render_enable_setting(  ) {
		$options = get_option( 'ubercx_option_name' );
		//is our option set
		if(isset($options['enable']) && $options['enable'] == 'checked'){
			$checked = "checked='checked'";
		} else {
			$checked = '';
		}
?>

	<input type='checkbox' name='ubercx_option_name[enable]'<?php echo $checked ?>'>

<?php
	}

	function render_user_key(  ) {
		$options = get_option( 'ubercx_option_name' );
		//is our option set
		isset($options['user_key']) ? $val=$options['user_key'] : $val='';
?>
<input
	type='text' id='user_key' name='ubercx_option_name[user_key]' size="45" 
	value='<?php echo $val ?>'><span> <a target="_blank" href="https://snapcx.io/signup?utm_source=wordpress&utm_medium=plugin&utm_campaign=avs">Get your User Key (open a FREE account)</a></span>
<?php
	}

	/**
	 *
	 * Sanitize the form input
	 */
	public function sanitize($input){
		//is the enabled button checked?
		if(isset($input['enable'])){
			$input['enable'] = "checked";
		} else {
			$input['enable'] = "";
		}

		return $input;
	}


	/**
	 *
	 * Lets do the validation!
	 */
	public function validate_address( $data=''){

		//check if the plugin is enabled
		$opt = get_option('ubercx_option_name');
		$enabled = $opt['enable'];
		
		if($enabled != 'checked'){
			return;
		}
		
		//need to check we are a US addr otherwise get the heck out of Dodge...
		if($data['ship_to_different_address'] == true){
			if($data['shipping_country'] != 'US') {
					return;
			}
		} else {
			if($data['billing_country'] != "US"){
				return;
			}
		}
		
		//ok if we have a 'which_to_use' it means the user has selected one - which means we validated already
		//Lets see if they have changed any data, if they have we need to revalidate!!!
		if(isset($_POST['ubercx_which_to_use'])){
			
			//ok lets see if any of the fields are dirty
			
			//which one did they select
			$selected = $_POST['ubercx_which_to_use'];
			
			// create the hidden id used in the html so we can check if it is dirty
			if($selected != 'orig'){
				$selected = "corrected_" . $selected;
			} 
			
			//collect the fields from the hidden fields in post
			$addr1 = $_POST['ubercx_addr_' . $selected . '_addr1'];
			$addr2 = $_POST['ubercx_addr_' . $selected . '_addr2'];
			$city = $_POST['ubercx_addr_' . $selected . '_city'];
			$state = $_POST['ubercx_addr_' . $selected . '_state'];
			$zip = $_POST['ubercx_addr_' . $selected . '_zip'];

			//Now compare them to the form to see if it is dirty

			//Billing or shipping addr?
			$dirty = false;
			
			if($data['ship_to_different_address'] == true){
				($data['shipping_address_1'] == $addr1) ? $dirty=$dirty : $dirty=true;
				($data['shipping_address_2'] == $addr2) ? $dirty=$dirty : $dirty=true;
				($data['shipping_city'] == $city) ? $dirty=$dirty : $dirty=true;
				($data['shipping_state'] == $state) ? $dirty=$dirty : $dirty=true;
				($data['shipping_postcode'] == $zip) ? $dirty=$dirty : $dirty=true;
				($data['shipping_country'] == 'US') ? $dirty=$dirty : $dirty=true;
			} else {
				($data['billing_address_1'] == $addr1) ? $dirty=$dirty: $dirty=true;
				($data['billing_address_2'] == $addr2) ? $dirty=$dirty: $dirty=true;
				($data['billing_city'] == $city) ? $dirty=$dirty: $dirty=true;
				($data['billing_state'] == $state) ? $dirty=$dirty: $dirty=true;
				($data['billing_postcode'] == $zip) ? $dirty=$dirty : $dirty=true;
				($data['billing_country'] == 'US') ? $dirty=$dirty: $dirty=true;
			}				
			
			//if clean then lets just return the data and we are good to go
			if(!$dirty){
				
				//TODO for now we return nothing so the order doesnt process
				//faking error on clean!
				//wc_add_notice( __( 'Everything is good but I dont want to submit!', UBERCX_ADDR_DOMAIN ), 'error' );
				
				//return;
				
				
				return $data;
			} 
		}
		
		//so either it is dirty or it is the first time thru - either way validate the address!
		//now check if the user opted to use the corrected addr
		
		//get the user key
		$user_key = $opt['user_key'];
		
		//lets get the address, ship to billing?
		
		if($data['ship_to_different_address'] == true){
			//use the shipping address
			$first_name = $data['shipping_first_name'];
			$last_name = $data['shipping_last_name'];
			$address_1 = $data['shipping_address_1'];
			$address_2 = $data['shipping_address_2'];
			$city = $data['shipping_city'];
			$state = $data['shipping_state'];
			$zip = $data['shipping_postcode'];
			$country =  $data['shipping_country'];
		} else {
			//otherwise use the billing addres
			$first_name = $data['billing_first_name'];
			$last_name = $data['billing_last_name'];
			$address_1 = $data['billing_address_1'];
			$address_2 = $data['billing_address_2'];
			$city = $data['billing_city'];
			$state = $data['billing_state'];
			$zip = $data['billing_postcode'];
			$country =  $data['billing_country'];
		}
		
		
		//ok now lets call our API
		$api_url  =  $this->uc_getApiUrl();
		
			//TODO need zip here
		  //TODO request_id needs to be generated dynamically. Possibly using storeaddress_timestamp	
			//TODO Add address2, only if available.
		$requestId = 'WooCommerce_' . time();
		$url = $api_url.'?request_id='.$requestId.'&street='.urlencode($address_1).'&secondary='.urlencode($address_2).'&state='.urlencode($state).'&city='.urlencode($city).'&zipcode='.urlencode($zip);    
			// Start cURL
			$curl = curl_init();
			// Headers
			$headers = array();
			$headers[] = 'user_key:'.$user_key;
			//$headers[] = 'Accept: application/json';
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $curl, CURLOPT_HEADER, false);
			
			// Get response
			$response = curl_exec($curl);
			
			// Get HTTP status code
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			//TODO put status check for "200". 
			// Close cURL
			curl_close($curl);
			
			// Return response from server
			if($response!=''){
				$response = json_decode($response);	
			} else {
				return $data;
			}
			
			//Always store the original addr in the transient
			$transient = array();
			$transient['orig'] = array();
			$transient['orig']['addr1'] = $address_1;
			$transient['orig']['addr2'] = $address_2;
			$transient['orig']['city'] = $city;
			$transient['orig']['state'] = $state;
			$transient['orig']['zip'] = $zip;
			

			//ok lets deal with the response and see what we got - be cautious and handle
			//everything from blank onwards!
			//TODO check for status == 200 
			if(is_object($response)
			&& isset( $response->header)
			&& isset( $response->header->status) 
			&& $response->header->status == 'SUCCESS'){
				//we should have something returned
				
				
				//lets see what kind of response we got...
				//The response code is hidden in the array, so first make sure
				//it exists then switch, am assuming the first one is the same for all
				//TODO check, if addressRecord is not null and has length > 0
				if(isset($response->addressRecord[0])
				&& isset($response->addressRecord[0]->addressSummary)
				&& isset($response->addressRecord[0]->addressSummary->matchCode)){
					//ok we have some kind of response, lets populate the transient
					
					switch($response->addressRecord[0]->addressSummary->matchCode){
						case 'AVS_01':
							//so the service is funky and validates not including zip
							//so if the response we get has a different zip we need to create a corrected address
							if(  $response->addressRecord[0]->address[0]->zipCode != $zip){
								wc_add_notice( __( 'There is a problem with your zip code, please check', UBERCX_ADDR_DOMAIN ), 'error' );
								//loop thru the matching addrs
								$transient['corrected'] = array();
									
								for($i=0; $i<count($response->addressRecord[0]->address); $i++){
								
									//save on typing store in temp!!!!
									$temp = $response->addressRecord[0]->address[$i];
								
									$transient['corrected'][$i]['addr1'] =  is_null($temp->addressLine1) ? "" : $temp->addressLine1 ;
									$transient['corrected'][$i]['addr2'] = is_null($temp->addressLine2) ? "" : $temp->addressLine2 ;
									$transient['corrected'][$i]['city'] = is_null($temp->city) ? "" : $temp->city ;
									$transient['corrected'][$i]['state'] = is_null($temp->state) ? "" : $temp->state;
									$transient['corrected'][$i]['zip'] = is_null($temp->zipCode) ? "" : $temp->zipCode;
								}
								
								break;	
							}
							
							return $data;
							break;
							
						case 'AVS_02':
							//OK we should get a bunch of returned addr's - lets
							//add them to the transient
							wc_add_notice( __( 'There appears to be an error in your address', UBERCX_ADDR_DOMAIN ), 'error' );
							
							//loop thru the matching addrs
							$transient['corrected'] = array();
							
							for($i=0; $i<count($response->addressRecord[0]->address); $i++){
								
								//save on typing store in temp!!!!
								$temp = $response->addressRecord[0]->address[$i];
								
								$transient['corrected'][$i]['addr1'] =  is_null($temp->addressLine1) ? "" : $temp->addressLine1 ;
								$transient['corrected'][$i]['addr2'] = is_null($temp->addressLine2) ? "" : $temp->addressLine2 ;
								$transient['corrected'][$i]['city'] = is_null($temp->city) ? "" : $temp->city ;
								$transient['corrected'][$i]['state'] = is_null($temp->state) ? "" : $temp->state;
								$transient['corrected'][$i]['zip'] = is_null($temp->zipCode) ? "" : $temp->zipCode;
							}
							break;
							
						case 'AVS_03':
							//we just show the original
							//but it is invalid!!!! Need to make sure the user corrects it
							wc_add_notice( __( 'There is a problem with your address - please check below', UBERCX_ADDR_DOMAIN ), 'error' );
							break;
							
						default:
							//unknown return code, have to just go with the addr
							return $data;
					}
				} else {
					
					//no match code found so something went wrong, lets just let it go thru and go with user addr
					return $data;
				}
				
				
				
			} else {
				//TODO, put some notification. Like re-confirm user, if you want to use this shipping address.
				//nothing was returned from the API call or something went wrong, just go with orig
				return $data;
			}
			
			
			//ok lets see if we can force a reload!!!
			//WC()->session->set('reload_checkout', true);

			set_transient('ubercx_addr_val', $transient);
			
			return $data;
	}

	public function validate_settings(){

	}
	
		/**
	 * Function to get end-point of API
	 * 
	 * @since 1.0.0
	 */
	function uc_getApiUrl(){
		if(file_exists(plugin_dir_path( __FILE__ ).'config.txt')){
			$response = file_get_contents(plugin_dir_path( __FILE__ ).'config.txt');
			$response = json_decode($response);
			if(!empty($response)){
				return $response->api_endpoint;
			}
		} 
	}
	
}

?>
