<?php
/**
 * SYN_Shipping_UPS class.
 *
 * @extends SYN_Shipping_Method
 */
class SYN_Shipping_UPS extends SYN_Shipping_Method {
	
	public $uri = 'https://www.ups.com/ups.app/xml/Rate';
	
	public $tracking_uri = 'https://www.ups.com/ups.app/xml/Track';
	
	public $address_validation_uri		= 'https://onlinetools.ups.com/ups.app/xml/AV';
	public $street_level_validation_uri	= 'https://onlinetools.ups.com/ups.app/xml/XAV';
	
	private $pickuptypes = array(
		'01' => 'Daily pickup',
		'03' => 'Customer Counter',
		'06' => 'One Time Pickup',
		'07' => 'On Call Air',
		'19' => 'Letter Center',
		'20' => 'Air Service Center'
	);
	
	private $services = array(
		//Valid domestic values:
		'01' => 'Next Day Air',
		'14' => 'Next Day Air Early AM',
		'13' => 'Next Day Air Saver',
		'59' => '2nd Day Air AM',
		'02' => '2nd Day Air',
		'12' => '3 Day Select',
		'03' => 'Ground',
		
		//Valid international values:
		'11' => 'Standard',
		'07' => 'Express',
		'54' => 'Worldwide Express Plus',
		'08' => 'Worldwide Expedited',
		'65' => 'Saver'
	);
	
	private $request;
	
	protected $boxes;
	
	private $units;
	
	private $method_errors;
	
	public $custom_methods;
	
	private $restrictions;
	
	private $packages;
	
	/* New var for tracking */
	protected $tracking_url = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%s';
	
	protected $accept_regional_method_restriction = true;

	public function __construct() {
		global $woocommerce;
		
		$this->id = 'sups';
		$this->method_title = 'UPS';
		
		$this->units = $this->method_errors = $this->packages = array();

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		$this->enabled					= $this->get_var( 'enabled' );
		$this->title					= $this->get_var( 'title' );
		$this->debug					= $this->get_var( 'debug' );
		$this->availability				= $this->get_var( 'availability' );
		$this->countries				= $this->get_var( 'countries', array() );
		$this->origin_postalcode		= $this->get_var( 'origin_postalcode' );
		$this->origin_country_state		= $this->get_var( 'origin_country_state', esc_attr( get_option('woocommerce_default_country') ) );
		$this->pickup					= $this->get_var( 'pickup' );
		$this->access_license_number	= $this->get_var( 'access_license_number' );
		$this->user_id					= str_replace( '&', '&amp;', $this->get_var( 'user_id' ) );
		$this->password					= str_replace( '&', '&amp;', $this->get_var( 'password' ) );
		$this->shipper_number			= $this->get_var( 'shipper_number' );
		$this->negotiated      			= $this->get_var( 'negotiated' );
		$this->insurance      			= $this->get_var( 'insurance' );
		$this->residential      		= $this->get_var( 'residential' );
		$this->packing_method			= $this->get_var( 'packing_method' );
		$this->fee						= $this->get_var( 'fee' );
		$this->calculate_fee			= $this->get_var( 'calculate_fee' );
		$this->shipping_methods			= $this->get_var( 'shipping_methods', array() );
		$this->custom_methods			= $this->get_var( 'custom_methods', array() );
		$this->addresses				= $this->get_var( 'multiple_address', array() );
		$this->boxes					= $this->get_var( 'boxes', array() );
		
		if( empty( $this->addresses ) ){
		
			$country_state = $this->get_country_state( $this->origin_country_state );
			
			$this->addresses[] = array(
				'title'					=> $this->title,
				'origin_postalcode'		=> $this->origin_postalcode,
				'origin_country_state'	=> $this->origin_country_state,
				'origin_country'		=> $country_state[ 'country' ],
				'origin_state'			=> $country_state[ 'state' ],
				'enabled'				=> 1
			);
		
		}
		
		if( !empty( $this->addresses ) ){
			foreach( $this->addresses as $address ){
				if( $address[ 'origin_country_state' ] == 'PL' ){
					$this->services["82"] = "Today Standard (Poland)";
					$this->services["83"] = "Today Dedicated Courier (Poland)";
					$this->services["84"] = "Today Intercity (Poland)";
					$this->services["85"] = "Today Express (Poland)";
					$this->services["86"] = "Today Express Saver (Poland)";
					break;
				}
			}
		}
		
		foreach( $this->services as $method_key => $method_name ){
			
			if( !isset( $this->custom_methods[ $method_key ] ) ){
				
				$this->custom_methods[ $method_key ] = array(
					'name'							=> woocommerce_clean( $method_name ),
					'price_ajustment'				=> '',
					'regional_service_availability'	=> '',
					'zip_service_availability'		=> '',
					'enabled'						=> ( ( isset( $this->settings['shipping_methods'] ) && array_search( $method_key, $this->settings['shipping_methods'] ) !== false ) || !isset( $this->settings['shipping_methods'] ) ? '1' : '0' )
				);
				
			}
			
		}
		
		// Used for weight based packing only
		$this->max_weight = '150';

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'clear_transients' ) );
		parent::__construct();
	}
	
	public function is_enabled(){
		return $this->enabled;
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		global $woocommerce;

		$this->form_fields = array(
		    'enabled' => array(
				'title'		=> __('Enable/Disable', 'syn_ups'),
				'type'		=> 'checkbox',
				'label'		=> __('Enable UPS', 'syn_ups'),
				'default'	=> 'no'
		    ),
		    'title' => array(
				'title'			=> __('Method title', 'syn_ups'),
				'type'			=> 'text',
				'description'	=> __('Enter the title of the shipping method.', 'syn_ups'),
				'default'		=> __('UPS', 'syn_ups')
		    ),
		    'debug' => array(
				'title'			=> __('Debug Mode', 'syn_ups'),
				'label'			=> __('Enable Debug Mode', 'syn_ups'),
				'type'			=> 'checkbox',
				'description'	=> __('Output the response from UPS on the cart/checkout for debugging purposes.', 'syn_ups'),
				'default'		=> 'no'
		    ),
		    'availability'  => array(
				'title'           => __( 'Method Availability', 'syn_ups' ),
				'type'            => 'select',
				'default'         => 'all',
				'class'           => 'availability',
				'options'         => array(
					'all'            => __( 'All Countries', 'syn_ups' ),
					'specific'       => __( 'Specific Countries', 'syn_ups' )
				)
			),
			'countries'        => array(
				'title'           => __( 'Specific Countries', 'syn_ups' ),
				'type'            => 'multiselect',
				'class'           => 'chosen_select',
				'css'             => 'width: 450px;',
				'default'         => '',
				'options'         => $woocommerce->countries->get_allowed_countries()
			),
			'pickup'  => array(
				'title'           => __( 'Pickup type', 'syn_ups' ),
				'type'            => 'select',
				'default'         => '03',
				'options'         => $this->pickuptypes
			),
		    'addresses'           => array(
				'title'           => __( 'Addresses', 'syn_ups' ),
				'type'            => 'title',
				'description'     => __( 'Multiple address can be added, at least one address is required. Ex.: If you have multiple warehouse.', 'syn_ups' )
		    ),
		    'multiple_address' => array(
				'type'	=> 'multiple_address'
		    ),
		    'api'           => array(
				'title'           => __( 'API Settings', 'syn_ups' ),
				'type'            => 'title',
				'description'     => __( 'Your API access details', 'syn_ups' )
		    ),
		    'access_license_number' => array(
				'title'			=> __('Access Key', 'syn_ups'),
				'type'			=> 'text',
				'css'			=> 'width: 250px;',
				'description'	=> __('Your UPS Access Key', 'syn_ups'),
				'default'		=> ''
		    ),
		    'user_id' => array(
				'title'			=> __('User ID', 'syn_ups'),
				'type'			=> 'text',
				'css'			=> 'width: 250px;',
				'description'	=> __('Your UPS user ID', 'syn_ups'),
				'default'		=> ''
		    ),
		    'password' => array(
				'title'			=> __('Password', 'syn_ups'),
				'type'			=> 'text',
				'css'			=> 'width: 250px;',
				'description'	=> __('Your UPS password', 'syn_ups'),
				'default'		=> ''
		    ),
		    'shipper_number' => array(
				'title'			=> __('Account number', 'syn_ups'),
				'type'			=> 'text',
				'css'			=> 'width: 250px;',
				'description'	=> __('Your UPS Account Number, can be found <a href="https://www.ups.com/servlet/acctsummary?loc=en_CA">here</a>', 'syn_ups'),
				'default'		=> ''
		    ),		    
		    'negotiated'  => array(
				'title'           => __( 'Negotiated Rates', 'syn_ups' ),
				'label'           => __( 'Enable negotiated rates', 'syn_ups' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'description'     => __( 'Enable this if your shipping account has negotiated rates.', 'syn_ups' )
			),		    
		    'insurance'  => array(
				'title'           => __( 'Insurance', 'syn_ups' ),
				'label'           => __( 'Add insurance to quote', 'syn_ups' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				'description'     => __( 'Enable this if you want to add UPS insurance to your packages.', 'syn_ups' )
			),
		    'residential' => array(
				'title'			=> __('Residential address', 'syn_ups'),
				'label'			=> __('Ship to residential address', 'syn_ups'),
				'type'			=> 'checkbox',
				'description'	=> __('Enabling this option will tell UPS that you always ship to residential address. UPS usually add surcharge when shipping to residential address.', 'syn_ups'),
				'default'		=> 'yes'
		    ),
			'packing_method'  => array(
				'title'           => __( 'Parcel Packing Method', 'syn_ups' ),
				'type'            => 'select',
				'default'         => 'per_item',
				'class'           => 'packing_method',
				'options'         => array(
					'per_item'		=> __( 'Default: Pack items individually', 'syn_ups' ),
					'weight'		=> __( 'Weight of all items', 'syn_ups' ),
					'box_packing'	=> __( 'Box packing (Most accurate quotes)', 'syn_ups' )
				)
			),
		    'fee' => array(
				'title'			=> __('Handling Fee', 'syn_ups'),
				'type'			=> 'text',
				'description'	=> __('Fee excluding tax. Enter an amount, e.g. 2.50, or a percentage, e.g. 5%. Leave blank for no fee.', 'syn_ups'),
				'default'		=> '0'
		    ),
			'calculate_fee'  => array(
				'title'           => __( 'Calculate handling fee from', 'syn_ups' ),
				'type'            => 'select',
				'default'         => 'shipping',
				'class'           => 'packing_method',
				'options'         => array(
					'shipping'	=> __( 'Shipping rate', 'syn_ups' ),
					'subtotal'	=> __( 'Subtotal', 'syn_ups' )
				),
				'desc_tip'		=> __( 'Only applies when using a percentage in the handling fee.', 'syn_ups' )
			),
		    'custom_methods' => array(
				'type'	=> 'custom_methods'
		    ),
		    'boxes'	=> array(
				'type'	=> 'box_packing'
			)
		);
	}
	
	/**
	 * validate_address function. Used UPS address verification
	 *
	 * @access public
	 * @param mixed $address
	 * @return $address
	 */
	public function validate_address( $address ){
		global $woocommerce;
		
		if( $address['country'] != 'US' || ( empty( $address['country'] ) || empty( $address['postalcode'] ) ) )
			return $address;
		
		$is_street_level_validation = false;
		
		if( !empty( $address['address_1'] ) && !empty( $address['city'] ) ){
		
			$xml = file_get_contents( UPS_PATH.'/xml/street_level_validation.xml' );
			$is_street_level_validation = true;
			
		}else{
		
			$xml = file_get_contents( UPS_PATH.'/xml/address_validation.xml' );
			
		}
			
		$xml = str_replace( '#LICENSE#', $this->access_license_number, $xml);
		$xml = str_replace( '#ID#', $this->user_id, $xml);
		$xml = str_replace( '#PASSWORD#', $this->password, $xml);
		
		$xml = str_replace( '#TOADDRESS1#', $address['address_1'], $xml);
		$xml = str_replace( '#TOCITY#', $address['city'], $xml);
		$xml = str_replace( '#TOSTATE#', $address['province'], $xml);
		$xml = str_replace( '#TOPOSTALCODE#', $address['postalcode'], $xml);
		$xml = str_replace( '#TOCOUNTRYCODE#', $address['country'], $xml);
		
		$this->add_notice( sprintf( __( '%s: Address validation Send XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $xml ), true ) ) );
		
		$response = wp_remote_post( ( $is_street_level_validation ? $this->street_level_validation_uri : $this->address_validation_uri ),
    		array(
				'timeout'   => 70,
				'sslverify' => 0,
				'body'      => $xml
		    )
		);
		
		$this->add_notice( sprintf( __( '%s: Address validation Reponse XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $response['body'] ), true ) ) );
		
		$xml = simplexml_load_string( preg_replace( '/<\?xml.*\?>/', '', $response['body'] ) );
		
		if( !$xml )
			$this->add_notice( sprintf( __( '%s: Failed loading Address Validation Response XML', 'syn_ups' ), $this->method_title ) , 'error' );

		if ( $xml->Response->ResponseStatusCode == 1 ) {
			
			if( $is_street_level_validation ){
				
				if( is_array( $xml->AddressKeyFormat ) ){
				
					$first_rank = $xml->AddressKeyFormat[0];
					
				}else{
					
					$first_rank = $xml->AddressKeyFormat;
					
				}
				
				$address['address_1'] = $first_rank->AddressLine;
				$address['city'] = $first_rank->PoliticalDivision2;
				$address['province'] = $first_rank->PoliticalDivision1;
				$address['postalcode'] = $first_rank->PostcodePrimaryLow;
				
			}else{
				
				if( is_array( $xml->AddressValidationResult ) ){
				
					$first_rank = $xml->AddressValidationResult[0];
					
				}else{
					
					$first_rank = $xml->AddressValidationResult;
					
				}
				
				$address['city'] = $first_rank->Address->City;
				$address['province'] = $first_rank->Address->StateProvinceCode;
				$address['postalcode'] = $first_rank->PostalCodeLowEnd;
				
			}
			
		}else{
			
			$this->add_xml_error( $xml );
			
		}
		
		return $address;
	}

	/**
	 * Send request and retrieve the result.
	 */
	public function get_shipping_request( $package ) {
		global $woocommerce;
		
		$responses = $combined_responses = array();
		
		$service_option = '';
		
		$this->add_notice( sprintf( __( '%s: Enter shipping request function.', 'syn_ups' ), $this->method_title ) );
		
		$address = $this->validate_address( array(
			'address_1'		=> $package['destination']['address'],
			'address_2'		=> $package['destination']['address_2'],
			'city'			=> $package['destination']['city'],
			'province'		=> $package['destination']['state'],
			'country'		=> $package['destination']['country'],
			'postalcode'	=> $package['destination']['postcode']
		) );
		
		foreach( $this->addresses as $address_key => $origin_address ){
		
			//Shipping details and xml filling
			
			if( ! $origin_address[ 'enabled' ] )
				continue;
				
			$this->set_valid_units( $origin_address );
		
			$this->set_package_requests( $package, $address );
			
			$xml = file_get_contents( UPS_PATH.'/xml/request.xml' );
			
			$xml = str_replace( '#LICENSE#', $this->access_license_number, $xml);
			$xml = str_replace( '#ID#', $this->user_id, $xml);
			$xml = str_replace( '#PASSWORD#', $this->password, $xml);
			$xml = str_replace( '#PICKUPTYPE#', $this->pickup, $xml);
			$xml = str_replace( '#PICKUPTYPETEXT#', $this->pickuptypes[$this->pickup], $xml);
			$xml = str_replace( '#SHIPPERNUMBER#', $this->shipper_number, $xml);
			$xml = str_replace( '#POSTALCODE#', $origin_address[ 'origin_postalcode' ], $xml);
			$xml = str_replace( '#COUNTRYCODE#', $origin_address[ 'origin_country' ], $xml);
			
			$xml = str_replace( '#TOADDRESS1#', $address['address_1'], $xml);
			$xml = str_replace( '#TOADDRESS2#', $address['address_2'], $xml);
			$xml = str_replace( '#TOCITY#', $address['city'], $xml);
			$xml = str_replace( '#TOSTATE#', $address['province'], $xml);
			$xml = str_replace( '#TOPOSTALCODE#', $address['postalcode'], $xml);
			$xml = str_replace( '#TOCOUNTRYCODE#', $address['country'], $xml);
			
			$xml = str_replace( '#FROMSTATE#', $origin_address[ 'origin_state' ], $xml);
			
			if( $this->residential ){
				$xml = str_replace( '#SHIPTORESIDENTIAL#', '<ResidentialAddressIndicator></ResidentialAddressIndicator>', $xml );
			}else{
				$xml = str_replace( '#SHIPTORESIDENTIAL#', '', $xml );
			}
			
			if( $this->negotiated ){
				$service_option .= "<RateInformation><NegotiatedRatesIndicator /></RateInformation>";
			}
			
			//Get services options
			$xml = str_replace( '#SHIPMENTSERVICEOPTIONS#', $service_option, $xml);
			
			$b_xml = $xml;
			if( count( $this->packages ) == 1 && key( $this->packages ) == 0 ){
				
				//Get all packages
				$xml = str_replace( '#PACKAGE#', $this->packages[ 0 ], $xml);
				
				$responses2 = $this->check_generic_package( $xml, $origin_address, $address, $address_key );
				
				$responses = array_merge($responses, $responses2);
				
			}else if( count( $this->packages ) == 1 ){
				
				reset( $this->packages );
				$code = key( $this->packages );
				$service = $this->custom_methods[ $code ];
				
				if( ! $service[ 'enabled' ] || ! $this->service_available( $service, $address ) )
					continue;
			
				//Get all packages
				$xml = str_replace( '#PACKAGE#', $this->packages[ $code ], $xml);
				
				$xml = str_replace( '#SERVICECODE#', $code, $xml);
				
				$this->add_notice( sprintf( __( '%s: Send XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $xml ), true ) ) );
				
				$response = wp_remote_post( $this->uri,
		    		array(
						'timeout'   => 70,
						'sslverify' => 0,
						'body'      => $xml
				    )
				);
	
				$responses[$code . $address_key] = array(
					'code'				=> $code,
					'key'				=> $address_key,
					'body'				=> $response['body'],
					'title'				=> $origin_address[ 'title' ] . ' ' . $service[ 'name' ],
					'origin_country'	=> $origin_address[ 'origin_country' ],
					'to_country'		=> $address['country']
				);
				
				$this->add_notice( sprintf( __( '%s: Response XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $response['body'] ), true ) ) );
				
			}else{
				
				foreach( $this->packages as $code => $package ){
					
					$xml = $b_xml;
					
					if( $code == 0 ){
						//Get all packages
						$xml = str_replace( '#PACKAGE#', $this->packages[ 0 ], $xml);
						
						$responses = $this->check_generic_package( $xml, $origin_address, $address );
						continue;
					}
					
					$service = $this->custom_methods[ $code ];
					
					if( !$service['enabled'] || ! $this->service_available( $service, $address ) )
						continue;
					
					//Get all packages
					$xml = str_replace( '#PACKAGE#', $package, $xml);
					
					$xml = str_replace( '#SERVICECODE#', $code, $xml);
					
					$this->add_notice( sprintf( __( '%s: Send XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $xml ), true ) ) );
					
					$response = wp_remote_post( $this->uri,
			    		array(
							'timeout'   => 70,
							'sslverify' => 0,
							'body'      => $xml
					    )
					);
					
					$fixed_responses[ $code . $address_key ] = array(
						'code'				=> $code,
						'key'				=> $address_key,
						'body'				=> $response['body'],
						'title'				=> $origin_address[ 'title' ] . ' ' . $service[ 'name' ],
						'origin_country'	=> $origin_address[ 'origin_country' ],
						'to_country'		=> $address['country']
					);
					
					$this->add_notice( sprintf( __( '%s: Response XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $response['body'] ), true ) ) );
					
				}
				
			}
		}
		
		$estimates = array();
		if( !empty( $fixed_responses ) ){
			
			//Loop throught responses
			if( count($fixed_responses) >= 1 ){
				
				foreach( $fixed_responses as $code => $response ){
					
					$estimate = $this->get_estimate( $code, $response );
					
					if( $estimate !== false ){

						$estimates[ $estimate[ 'code' ] ] = $estimate;
					
					}
					
				}
				
			}
			
		}
		
		//Loop throught responses
		if( count($responses) >= 1 ){
			
			foreach( $responses as $code => $response ){
				
				$estimate = $this->get_estimate( $code, $response );
				
				if( $estimate === false )
					continue;
					
				if( !empty( $estimates ) ){
					
					$b_estimates = $estimates;
					if( isset( $b_estimates[ $code ] ) ){
						
						$b_estimates[ $code ][ 'cost' ] += $estimate[ 'cost' ];
						
					}else{
						
						$b_estimates[ $code ] = null;
						$b_estimates[ $code ] = $estimate;
						
					}
						
					$estimate = array(
						'code'	=> '99' . $code,
						'id' 	=> 'multiple_ups_methods_' . $estimate[ 'id' ],
						'label' => '',
						'cost' 	=> 0
					);
					foreach( $b_estimates as $b_estimate ){
						
						$estimate[ 'label' ] .= ( empty( $estimate[ 'label' ] ) ? '' : ', ' ) . $b_estimate[ 'label' ] . '(' . strip_tags( wc_price( $b_estimate[ 'cost' ] ) ) . ')';
						$estimate[ 'cost' ] += $b_estimate[ 'cost' ];
						
					}
					
				}
				
				$this->add_this_estimate( $estimate );
				
			}
			
		}else if( !empty( $estimates ) ){
			
			$estimate = array(
				'code'	=> '99' . $code,
				'id' 	=> 'multiple_ups_methods_' . $estimate[ 'id' ],
				'label' => '',
				'cost' 	=> 0
			);
			foreach( $estimates as $b_estimate ){
				
				$estimate[ 'label' ] .= ( empty( $estimate[ 'label' ] ) ? '' : ', ' ) . $b_estimate[ 'label' ] . '(' . strip_tags( wc_price( $b_estimate[ 'cost' ] ) ) . ')';
				$estimate[ 'cost' ] += $b_estimate[ 'cost' ];
				
			}
			$this->add_this_estimate($estimate);
			
		}
		
		if( empty( $this->rates ) ){
			
			$this->add_notice( sprintf( __( '%s: No rates returned - ensure you have defined product dimensions and weights.', 'syn_ups' ), $this->method_title ) );
			
		}else{
			
			$this->add_notice( sprintf( __( '%s: All was good!', 'syn_ups' ), $this->method_title ) );
			
		}
		
		$this->check_xml_errors();
		
		return !empty( $this->rates );
	}
	
	/**
	 * Send request and retrieve the result.
	 */
	public function get_tracking( $tracking_number = "" ) {
		global $woocommerce;
		
		$this->add_notice( sprintf( __( '%s: Enter tracking function.', 'syn_ups' ), $this->method_title ) );
		
		if( empty( $tracking_number ) )
			return false;
			
		$tracking = array(
			'delivered'			=> false,
			'status'			=> '',
			'scheduled_date'	=> '',
			'activity'			=> array()
		);
		
		$xml = file_get_contents( UPS_PATH . '/xml/tracking.xml' );
		
		$xml = str_replace( '#LICENSE#', $this->access_license_number, $xml);
		$xml = str_replace( '#ID#', $this->user_id, $xml );
		$xml = str_replace( '#PASSWORD#', $this->password, $xml );
		
		$xml = str_replace( '#TRACKINGNUMBER#', $tracking_number, $xml );
		
		$this->add_notice( sprintf( __( '%s: Send XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $xml ), true ) ) );
		
		$response = wp_remote_post( $this->tracking_uri,
    		array(
				'timeout'   => 70,
				'sslverify' => 0,
				'body'      => $xml
		    )
		);
		
		$this->add_notice( sprintf( __( '%s: Response XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $response['body'] ), true ) ) );
		
		$xml = simplexml_load_string( preg_replace( '/<\?xml.*\?>/', '', $response[ 'body' ] ) );
		
		if( !$xml )
			$this->add_notice( sprintf( __( '%s: Failed loading XML', 'syn_ups' ), $this->method_title ) , 'error' );
			
		if ( $xml->Response->ResponseStatusCode == 1 ) {
		
			if( isset( $xml->Shipment->ScheduledDeliveryDate ) ){
				$tracking[ 'scheduled_date' ] = strtotime( (string) $xml->Shipment->ScheduledDeliveryDate . ' ' . (string) $xml->Shipment->ScheduledDeliveryTime );
			}
		
			if( isset( $xml->Shipment->Package->Activity ) ){
				
				$key = 0;
				foreach( $xml->Shipment->Package->Activity as $activity ){
				
					$key++;
					
					if( $key == 1 ){
						$tracking[ 'delivered' ] = ( (string) $activity->Status->StatusCode->Code == 'FS' ? true : false );
						$tracking[ 'status' ] = (string) $activity->Status->StatusType->Description;
					}
					
					$tracking[ 'activity' ][] = array(
						'status'	=> (string) $activity->Status->StatusType->Description,
						'date'		=> strtotime( (string) $activity->Date . ' ' . (string) $activity->Time ),
						'location'	=> ( isset( $activity->ActivityLocation->Code ) ? ( (string) $activity->ActivityLocation->Address->City . ', ' . (string) $activity->ActivityLocation->Address->PostalCode . ', ' . (string) $activity->ActivityLocation->Address->CountryCode ) : ( (string) $activity->ActivityLocation->Address->City . ', ' . (string) $activity->ActivityLocation->Address->StateProvinceCode . ', ' . (string) $activity->ActivityLocation->Address->CountryCode ) )
					);
					
				}
				
			}
		
		}else{
			
			//$this->add_xml_error( $xml, $code, $this->custom_methods[ $code ][ 'name' ], $response[ 'origin_country' ], $response[ 'to_country' ] );
			
		}

		return $tracking;
	}
	
	private function get_estimate( $code, $response ){
		
		$xml = simplexml_load_string( preg_replace( '/<\?xml.*\?>/', '', $response[ 'body' ] ) );
	
		if( !$xml )
			$this->add_notice( sprintf( __( '%s: Failed loading XML', 'syn_ups' ), $this->method_title ) , 'error' );

		if ( $xml->Response->ResponseStatusCode == 1 ) {

			$service_name = $this->custom_methods[ $response[ 'code' ] ][ 'name' ];

			if ( $this->negotiated && isset( $xml->RatedShipment->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue ) )
				$rate_cost = (float) $xml->RatedShipment->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue;
			else
				$rate_cost = (float) $xml->RatedShipment->TotalCharges->MonetaryValue;

			$rate_id = $this->id . ':' . $response[ 'code' ] . ':' . $response[ 'key' ];
			$rate_name = ( empty( $response[ 'title' ] ) ? $this->title . ( empty( $this->title ) ? '' : ' ' ) : $response[ 'title' ] . ' ' );
			
			if( ! empty( $this->custom_methods[ $response[ 'code' ] ][ 'price_ajustment' ] ) ){
				$rate_cost = $rate_cost + $this->get_fee( $this->custom_methods[ $response[ 'code' ] ][ 'price_ajustment' ], $rate_cost );
			}
			
			return array(
				'code'	=> $code,
				'id' 	=> $rate_id,
				'label' => $rate_name,
				'cost' 	=> $rate_cost
			);
			
		}else{
			
			$this->add_xml_error( $xml, $code, $this->custom_methods[ $response[ 'code' ] ][ 'name' ], $response[ 'origin_country' ], $response[ 'to_country' ] );
			
			return false;
			
		}
		
	}
	
	private function check_generic_package( $xml, $origin_address, $address, $address_key = 0 ){
		
		$responses = array();
		
		$b_xml = $xml;
		foreach( $this->custom_methods as $code => $service ) {
				
			if( ! $service[ 'enabled' ] || ! $this->service_available( $service, $address ) )
				continue;
		
			$xml = $b_xml;
			//Loop throught services selected
			$xml = str_replace( '#SERVICECODE#', $code, $xml);
			
			$this->add_notice( sprintf( __( '%s: Send XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $xml ), true ) ) );
			
			$response = wp_remote_post( $this->uri,
	    		array(
					'timeout'   => 70,
					'sslverify' => 0,
					'body'      => $xml
			    )
			);

			$responses[$code . $address_key] = array(
				'code'				=> $code,
				'key'				=> $address_key,
				'body'				=> $response['body'],
				'title'				=> $origin_address[ 'title' ] . ' ' . $service[ 'name' ],
				'origin_country'	=> $origin_address[ 'origin_country' ],
				'to_country'		=> $address['country']
			);
			
			$this->add_notice( sprintf( __( '%s: Response XML: <pre class="syn-debug">%s</pre>', 'syn_ups' ), $this->method_title, print_r( htmlspecialchars( $response['body'] ), true ) ) );
		}
		
		return $responses;
		
	}
	
	private function add_xml_error( $xml, $method_id = 0, $method_name = '', $origin_country = '', $to_country = '' ){
	
		$code = (string) $xml->Response->Error->ErrorCode;
		
		if( !isset( $this->method_errors[ $code ] ) ){
			
			$this->method_errors[ $code ] = array(
				'code'				=> $xml->Response->Error->ErrorCode,
				'desc'				=> $xml->Response->Error->ErrorDescription,
				'method_id'			=> $method_id,
				'method_name'		=> $method_name,
				'origin_country'	=> $origin_country,
				'to_country'		=> $to_country
			);
			
		}
		
	}
	
	private function check_xml_errors(){
	
		global $woocommerce;
		
		if( empty( $this->method_errors ) || !$this->debug )
			return false;
			
		$show = false;
			
		foreach( $this->method_errors as $code => $error ){
			
			switch( $code ){
			
				case '111100':
					$this->add_notice( __( 'The UPS service "' . $error[ 'method_name' ] .  '" is unavailable for your origin, remove it from your selected shipping methods in WooCommerce->Settings->Shipping->UPS.', 'syn_ups' ) , 'error');
					$show = true;
					break;
					
				case '111210':
				case '111217':
					//$this->add_notice( __( 'The UPS service "' . $error[ 'method_name' ] .  '" is unavailable between ' . $woocommerce->countries->countries[ $error[ 'origin_country' ] ] . ' and ' . $woocommerce->countries->countries[ $error[ 'to_country' ] ] , 'syn_ups' ) , 'error');
					//$show = true;
					break;
				
				case '250003':
					$this->add_notice( __( 'The UPS Access License number "' . $this->access_license_number .  '" is invalid, please verify that you have enter it correctly.', 'syn_ups' ) , 'error');
					$show = true;
					break;
					
				default:
					$this->add_notice( __( 'Unknown UPS error<br />ErrorCode: ' . $error[ 'code' ] . '<br /> ErrorDescription: ' . $error[ 'desc' ], 'syn_ups' ) , 'error');
					$show = true;
					break;
				
			}
			
		}
		
		if( $show )
			$this->add_notice( __( '<br />NB: These error(s) does not mean that the plugin is not working correctly, sometimes some services can be available in one countries but not in another. If you never get a quote, please try to enable other methods to see if one is working.', 'syn_ups' ) , 'error');
		
	}
	
	private function add_this_estimate( $estimate ){
		global $woocommerce;
		
		unset($estimate['code']);

		if( ! empty( $this->fee ) ) {
			
			switch( $this->calculate_fee ){
				
				case 'shipping':
				default:
					$cost = $estimate[ 'cost' ];
					break;
					
				case 'subtotal':
					$cost = $woocommerce->cart->subtotal;
					break;
				
			}
			
			$estimate[ 'cost' ] = $estimate[ 'cost' ] + $this->get_fee( $this->fee, $cost );
			
		}
		
		$this->add_rate( $estimate );
	}

	public function admin_options() {
		global $woocommerce;
		?>
		<h3><?php _e('UPS', 'syn_ups'); ?></h3>
		<p><?php echo(sprintf(__('You must have an Access key, User ID, Password and Shipper Number to calculate UPS Shipping, <a href="%s" target="_blank">click here</a> to register an account with UPS.', 'syn_ups'), 'https://www.ups.com/upsdeveloperkit')); ?></p>
		<table class="form-table">
			<?php
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
			?>
		</table><!--/.form-table-->
		<?php
	}
	
    private function set_package_requests( $package, $address ) {

	    // Choose selected packing
    	switch ( $this->packing_method ) {
    		case 'per_item' :
	    	default :
	    		$this->per_item_shipping( $package, $address );
				break;
	    	case 'weight' :
	    		$this->weight_only_shipping( $package, $address );
				break;
	    	case 'box_packing':
	    		$this->box_shipping( $package, $address );
	    		break;
    	}
    	
    }
    
    private function set_valid_units( $address ){
    
    	global $woocommerce;
	    
	    switch( $address[ 'origin_country' ] ){
		    
		    case 'US':
		    	$units = array(
		    		'woo_weight'		=> 'lbs',
		    		'method_weight'		=> 'LBS',
		    		'woo_dimensions'	=> 'in',
		    		'method_dimensions'	=> 'IN'
		    	);
		    	break;
		    	
		    default:
		    	$units = array(
		    		'woo_weight'		=> 'kg',
		    		'method_weight'		=> 'KGS',
		    		'woo_dimensions'	=> 'cm',
		    		'method_dimensions'	=> 'CM'
		    	);
		    	break;
		    
	    }
	    
	    $this->units = $units;
	    
    }
    
    private function check_restrictions( $product_id, $address ){
	    
	    $ups_method_restriction = get_post_meta( $product_id, '_ups_method_restriction', true );
	    
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
		
		$enable = array();
    		
		if( $ups_method_restriction ){
    		
    		foreach( $ups_method_restriction[ 'restrictions' ] as $restrictions ){
	    		
	    		if( count( $restrictions[ 'country_restrictions' ] ) <= 0 || in_array( $address[ 'country' ] . ':' . $address[ 'province' ], $restrictions[ 'country_restrictions' ] ) || in_array( $address[ 'country' ], $restrictions[ 'country_restrictions' ] ) ){
		    		
		    		$enable = array_unique( array_merge( $enable, $restrictions[ 'method_restrictions' ] ) );
		    		
	    		}
	    		
    		}
    		
    		foreach( $this->custom_methods as $method_key => &$service ){
	    		
	    		if( $service[ 'enabled' ] && array_search( $method_key, $enable ) === false ){
		    		
		    		$service[ 'enabled' ] = 0;
		    		
	    		}
	    		
    		}
    		
		}
	    
    }
    
    /**
     * per_item_shipping function.
     *
     * @access private
     * @param mixed $package
     * @return void
     */
    private function per_item_shipping( $package, $address ) {
	    global $woocommerce;
		
		$this->packages = array();
		
    	// Get weight of order
    	foreach ( $package['contents'] as $item_id => $values ) {

    		if( !$values['data']->needs_shipping() ){
   				$this->add_notice( sprintf( __( '%s: Product #%d is virtual. Skipping.', 'syn_ups' ), $this->method_title, $values[ 'product_id' ] ) );
    			continue;
    		}

    		if( !$values['data']->get_weight() || !$values['data']->length || !$values['data']->height || !$values['data']->width ){
    			$this->add_notice( sprintf( __( '%s: Product <a href="%s" target="_blank">#%d</a> is missing dimensions and / or weight. Aborting quotes.', 'syn_ups' ), $this->method_title, get_edit_post_link( $values[ 'product_id' ] ), $values[ 'product_id' ] ), 'error' );
    			$this->packages = array();
	    		return;
    		}
    		
    		//$this->check_restrictions( $values[ 'product_id' ], $address );

			$dimensions = array( $values['data']->length, $values['data']->height, $values['data']->width );

			sort( $dimensions );
			
			$piece = file_get_contents(UPS_PATH.'/xml/package.xml');
			
			$piece = str_replace('#LENGTH#', number_format( woocommerce_get_dimension( $dimensions[2], $this->units['woo_dimensions'] ), 2, '.', ''), $piece);
			$piece = str_replace('#WIDTH#', number_format( woocommerce_get_dimension( $dimensions[1], $this->units['woo_dimensions'] ), 2, '.', ''), $piece);
			$piece = str_replace('#HEIGHT#', number_format( woocommerce_get_dimension( $dimensions[0], $this->units['woo_dimensions'] ), 2, '.', ''), $piece);
			
			$weight = woocommerce_get_weight( $values['data']->get_weight(), $this->units['woo_weight'] );
			
			$piece = str_replace('#WEIGHT#', ( round( $weight, 1 ) < 0.1 ) ? 0.1 : round( $weight, 1 ), $piece);
			
			$piece = str_replace('#WEIGHTUNIT#', $this->units['method_weight'], $piece);
			$piece = str_replace('#DIMENSIONUNIT#', $this->units['method_dimensions'], $piece);
			
			if( $this->insurance ){
				$package_option = "<PackageServiceOptions>
										<InsuredValue>
											<CurrencyCode>" . get_woocommerce_currency() . "</CurrencyCode>
											<MonetaryValue>" . round( $values['line_subtotal'], 2) . "</MonetaryValue>
										</InsuredValue>
									</PackageServiceOptions>";
									
				$piece = str_replace( '#PACKAGESERVICEOPTIONS#', $package_option, $piece);
			}else{
				$piece = str_replace( '#PACKAGESERVICEOPTIONS#', '', $piece);
			}
			
			for ( $i = 0; $i < $values[ 'quantity' ] ; $i++){
				
				if( !isset( $this->packages[ 0 ] ) )
					$this->packages[ 0 ] = null;
			
				$this->packages[ 0 ] .= $piece;
				
			}
			
    	}
    	
    }
    
    /**
     * weight_only_shipping function.
     *
     * @access private
     * @param mixed $package
     * @return void
     */
    private function weight_only_shipping( $package, $address ) {
    	
    	global $woocommerce;
    	
    	$weight = $total_value = 0;
    	$this->packages = array();

    	// Get weight of order
    	foreach ( $package['contents'] as $item_id => $values ) {

    		if( !$values['data']->needs_shipping() ){
   				$this->add_notice( sprintf( __( '%s: Product #%d is virtual. Skipping.', 'syn_ups' ), $this->method_title, $values[ 'product_id' ] ) );
    			continue;
    		}

    		if( !$values['data']->get_weight() ){
    			$this->add_notice( sprintf( __( '%s: Product <a href="%s" target="_blank">#%d</a> is missing weight. Aborting quotes.', 'syn_ups' ), $this->method_title, get_edit_post_link( $values[ 'product_id' ] ), $values[ 'product_id' ] ), 'error' );
	    		return;
    		}
    		
    		//$this->check_restrictions( $values[ 'product_id' ], $address );

			$weight += $values['data']->get_weight() * $values['quantity'];
			$total_value += $values['line_subtotal'] * $values['quantity'];
			
    	}
    	
    	$weight = woocommerce_get_weight( $weight, $this->units['woo_weight'] );
    	$weight = round( $weight, 1 ) < 0.1 ? 0.1 : round( $weight, 1 );
    	
    	while( $weight > 0 ){
	    	
	    	$piece_weight = $weight > $this->max_weight ? $this->max_weight : $weight;
	    	$weight -= $this->max_weight;
	    	
	    	$piece = file_get_contents(UPS_PATH.'/xml/package_weight.xml');
		
			$piece = str_replace('#WEIGHT#', $piece_weight, $piece);
			
			$piece = str_replace('#WEIGHTUNIT#', $this->units['method_weight'], $piece);
			
			if( $this->insurance ){
				$package_option = "<PackageServiceOptions>
										<InsuredValue>
											<CurrencyCode>" . get_woocommerce_currency() . "</CurrencyCode>
											<MonetaryValue>" . round( $total_value, 2) . "</MonetaryValue>
										</InsuredValue>
									</PackageServiceOptions>";
									
				$piece = str_replace( '#PACKAGESERVICEOPTIONS#', $package_option, $piece);
			}else{
				$piece = str_replace( '#PACKAGESERVICEOPTIONS#', '', $piece);
			}
			
			if( !isset( $this->packages[ 0 ] ) )
				$this->packages[ 0 ] = null;
		
			$this->packages[ 0 ] .= $piece;
	    	
    	}
	    
    }
    
    /**
     * box_shipping function.
     *
     * @access private
     * @param mixed $package
     * @return void
     */
    private function box_shipping( $package, $address ) {
	    global $woocommerce;
	    
	    $this->packages = array();
	    
	    // Check for restrictions
    	foreach ( $package['contents'] as $item_id => $values ) {
    		
    		//$this->check_restrictions( $values[ 'product_id' ], $address );
			
    	}

		$box_packages = $this->box_shipping_packages( $package );
		
		if( $box_packages === false )
			return;

    	// Get weight of order
    	foreach ( $box_packages as $key => $box_package ) {
			
			$piece = file_get_contents( UPS_PATH . '/xml/package.xml' );
			
			$piece = str_replace('#LENGTH#', number_format( woocommerce_get_dimension( $box_package->length, $this->units['woo_dimensions'] ), 2, '.', ''), $piece);
			$piece = str_replace('#WIDTH#', number_format( woocommerce_get_dimension( $box_package->width, $this->units['woo_dimensions'] ), 2, '.', ''), $piece);
			$piece = str_replace('#HEIGHT#', number_format( woocommerce_get_dimension( $box_package->height, $this->units['woo_dimensions'] ), 2, '.', ''), $piece);
			
			$weight = woocommerce_get_weight( $box_package->weight, $this->units['woo_weight'] );
			$piece = str_replace('#WEIGHT#', ( round( $weight, 1 ) < 0.1 ) ? 0.1 : round( $weight, 1 ), $piece);
			
			$piece = str_replace('#WEIGHTUNIT#', $this->units['method_weight'], $piece);
			$piece = str_replace('#DIMENSIONUNIT#', $this->units['method_dimensions'], $piece);
			
			if( $this->insurance ){
				$package_option = "<PackageServiceOptions>
										<InsuredValue>
											<CurrencyCode>" . get_woocommerce_currency() . "</CurrencyCode>
											<MonetaryValue>" . round( $box_package->value, 2) . "</MonetaryValue>
										</InsuredValue>
									</PackageServiceOptions>";
									
				$piece = str_replace( '#PACKAGESERVICEOPTIONS#', $package_option, $piece);
			}else{
				$piece = str_replace( '#PACKAGESERVICEOPTIONS#', '', $piece);
			}
			
			if( !isset( $this->packages[ 0 ] ) )
				$this->packages[ 0 ] = null;
		
			$this->packages[ 0 ] .= $piece;
			
    	}
    	
    }
    
    public function generate_single_select_country_html() {
		global $woocommerce;

		$country_state = $this->get_country_state( $this->origin_country_state );
		
		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="origin_country"><?php _e( 'Origin Country', 'syn_ups' ); ?></label>
			</th>
            <td class="forminp"><select name="woocommerce_ups_origin_country_state" id="woocommerce_ups_origin_country_state" style="width: 250px;" data-placeholder="<?php _e('Choose a country&hellip;', 'woocommerce'); ?>" title="Country" class="chosen_select">
	        	<?php echo $woocommerce->countries->country_dropdown_options( $country_state[ 'country' ], $country_state[ 'state' ] ); ?>
	        </select> <span class="description"><?php _e( 'Enter your origin country.', 'syn_ups' ) ?></span>
       		</td>
       	</tr>
		<?php
		return ob_get_clean();
	}
    
    public function validate_single_select_country_field( $key ) {
		
		if ( isset( $_POST['woocommerce_ups_origin_country_state'] ) )
			return $_POST['woocommerce_ups_origin_country_state'];
		return '';		
	}
	
	public function generate_multiple_address_html ( $key, $data ) {
    	global $woocommerce;
    	
    	ob_start();
		?>
		<tr valign="top" id="method_options">
			<td class="forminp" colspan="2">
				<table class="syn_ups_address widefat">
					<thead>
						<tr>
							<th scope="col" id="cb" class="manage-column column-cb check-column" style="padding: 11px 0px 0px 0px;"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th>
							<th><?php _e( 'Address title', 'syn_ups' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('The address title will replace the main method title. Ex.: Main title is UPS but one address is UPS from MD, shipping method will show UPS from MD Ground', 'syn_ups'); ?>">[?]</span></th>
			
							<th><?php _e( 'Origin Post Code', 'syn_ups' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('The origin post code of the address.', 'syn_ups'); ?>">[?]</span></th>
			
							<th><?php _e( 'Origin Country', 'syn_ups' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('The origin country of the address.', 'syn_ups'); ?>">[?]</span></th>
			
							<th style="width:8%;"><?php _e( 'Enabled', 'syn_ups' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('Enable this address', 'syn_ups'); ?>">[?]</span></th>
			
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th scope="col" class="manage-column column-cb check-column" style="padding: 15px 0px 0px 0px;"><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></th>
							<th colspan="4">
								<a href="#" class="button plus insert">Insert row</a>
								<a href="#" class="button minus remove">Remove selected row(s)</a>
							</th>
						</tr>
					</tfoot>
					<tbody>
					
					<?php if( !empty( $this->addresses ) ){ ?>
					
					<?php foreach( $this->addresses as $key => $address ){ ?>
					
						<tr>
						
							<th scope="row" class="check-column" style="padding:13px 0px 0px 0px;" align="center">
								<input type="checkbox" class="address_rows" value="1" />
							</th>
						
							<td class="address_title">
								<input type="text" name="address_title[<?php echo($key+1); ?>]" data-name="address_title[{0}]" value="<?php echo esc_attr( $address['title'] ) ?>" />
							</td>
	
							<td class="address_origin_postalcode">
								<input type="text" name="address_origin_postalcode[<?php echo($key+1); ?>]" data-name="address_origin_postalcode[{0}]" value="<?php echo esc_attr( $address['origin_postalcode'] ) ?>" />
							</td>
	
							<td class="address_origin_country_state" style="overflow:visible;" valign="middle">
								<select name="woocommerce_sups_origin_country_state[<?php echo($key+1); ?>]" data-name="woocommerce_sups_origin_country_state[{0}]" id="woocommerce_sups_origin_country_state_<?php echo($key+1); ?>" data-placeholder="<?php _e('Choose a country&hellip;', 'woocommerce'); ?>" title="Country" class="chosen_select2 select">
						        	<?php echo $woocommerce->countries->country_dropdown_options( $address['origin_country'], $address['origin_state'] ); ?>
						        </select>
							</td>
	
							<td class="method_enabled" style="width:8%;" align="center">
								<input type="checkbox" class="checkbox" name="address_enabled[<?php echo($key+1); ?>]" data-name="address_enabled[{0}]" value="1" style="width:auto;"<?php echo( $address['enabled'] ? ' checked="checked"' : '' ); ?> />
							</td>
	
						</tr>
					
					<?php } ?>
					
					<?php } ?>

					</tbody>
				</table>
				
				<table id="address_row" style="display:none;">
				
					<tbody>
						<tr>
						
							<th scope="row" class="check-column" style="padding:13px 0px 0px 0px;">
								<input type="checkbox" class="address_rows" value="1" />
							</th>
						
							<td class="address_title">
								<input type="text" name="address_title[{0}]" data-name="address_title[{0}]" value="" />
							</td>
	
							<td class="address_origin_postalcode">
								<input type="text" name="address_origin_postalcode[{0}]" data- name="address_origin_postalcode[{0}]" value="" />
							</td>
	
							<td class="address_origin_country_state" style="overflow:visible;" valign="middle">
								<select name="woocommerce_sups_origin_country_state[{0}]" data-name="woocommerce_sups_origin_country_state[{0}]" id="woocommerce_sups_origin_country_state_{0}" data-placeholder="<?php _e('Choose a country&hellip;', 'woocommerce'); ?>" title="Country" class="chosen_select2 select">
						        	<?php echo $woocommerce->countries->country_dropdown_options(); ?>
						        </select>
							</td>
	
							<td class="method_enabled" style="width:8%;" align="center">
								<input type="checkbox" class="checkbox" name="address_enabled[{0}]" data-name="address_enabled[{0}]" value="1" style="width:auto;" />
							</td>
	
						</tr>
					</tbody>
					
				</table>
				
				<script type="text/javascript">
					jQuery( function() {
			
						var addresses = <?php echo( count( $this->addresses ) ); ?>;
			
						jQuery('.syn_ups_address .remove').click(function() {
							var $tbody = jQuery('.syn_ups_address').find('tbody');
							if ( $tbody.find('.address_rows:checked').size() > 0 ) {
								
								jQuery(".syn_ups_address .address_rows:checked").each(function(){
									
									jQuery(this).parents("tr:first").remove();
									
								});
								
							} else {
								alert('No row(s) selected');
							}
							return false;
						});
			
						jQuery('.syn_ups_address .insert').click(function() {
							var $tbody = jQuery('.syn_ups_address').find('tbody');
							addresses++;
							var code = jQuery("#address_row tbody").html();
							code = code.format(addresses);
			
							jQuery('.syn_ups_address tbody').append(code);
							
							jQuery('.syn_ups_address tbody .chosen_select2').chosen();
							
							return false;
						});
						
						jQuery('.syn_ups_address tbody .chosen_select2').chosen();
					});
				
					String.prototype.format = function() {
						var args = arguments;
						return this.replace(/{(\d+)}/g, function(match, number) { 
							return typeof args[number] != 'undefined' ? args[number] : match ;
						});
					};
				
				</script>
			</td>
		</tr>
		<?php
		
		return ob_get_clean();
		
    }
    
    public function validate_multiple_address_field( $key ) {
    
		$addresses = array();
		
		if( !empty( $_POST['address_title'] ) ){
			
			foreach( $_POST['address_title'] as $key => $address_title ){
			
				if( $key == '{0}' )
					continue;
				
				$country_state = $this->get_country_state( $_POST[ 'woocommerce_sups_origin_country_state' ][ $key ] );
				
				$addresses[] = array(
					'title'					=> woocommerce_clean( $address_title ),
					'origin_postalcode'		=> woocommerce_clean( $_POST[ 'address_origin_postalcode' ][ $key ] ),
					'origin_country_state'	=> woocommerce_clean( $_POST[ 'woocommerce_sups_origin_country_state' ][ $key ] ),
					'origin_country'		=> $country_state[ 'country' ],
					'origin_state'			=> $country_state[ 'state' ],
					'enabled'				=> isset( $_POST[ 'address_enabled' ][ $key ] ) ? 1 : 0
				);
				
			}
			
		}

		return $addresses;
		
	}
    
}
	
?>