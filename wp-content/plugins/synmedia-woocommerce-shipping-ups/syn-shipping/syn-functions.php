<?php
/*
	* SYN functions.
	* Version: 1.1.4
*/

if ( ! function_exists( 'syn_woocommerce_loaded' ) ){
	function syn_woocommerce_loaded(){
		require_once( 'class-syn-shipping-method.php' );
	}
	add_action( 'woocommerce_init', 'syn_woocommerce_loaded', 1 );
}

/**
 * Localisation
 */
load_plugin_textdomain( 'syn_shipping', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/' );

if( !function_exists('syn_add_tracking') ){

	function syn_add_tracking( $carrier_id, $carrier_name, $tracking_url, $class_name = "" ){
	
		global $syn_carriers;
		
		if( isset( $syn_carriers[ $carrier_id ] ) )
			return false;
		
		if( ! is_array( $syn_carriers ) )
			$syn_carriers = array();
			
		$syn_carriers[ $carrier_id ] = array(
			'carrier_id'	=> $carrier_id,
			'carrier_name'	=> $carrier_name,
			'tracking_url'	=> $tracking_url,
			'class_name'	=> $class_name
		);
		
	}
	
	/**
	 * Clear shipping transient
	 *
	 * @access public
	 */
	function syn_clear_transients( $method_id ) {
		global $wpdb;

		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_shipping_" . $method_id . "_%') OR `option_name` LIKE ('_transient_wc_ship_%')" );
	}
	
	/**
	 * Add the meta box for shipment info on the order page
	 *
	 * @access public
	 */
	function syn_add_meta_box_tracking() {
		
		add_meta_box( 'syn-shipment-tracking', __('Package Tracking', 'syn_shipping'), 'syn_meta_box_tracking', 'shop_order', 'side', 'high');
		
	}
	add_action( 'add_meta_boxes', 'syn_add_meta_box_tracking' );
	
	function syn_meta_box_tracking() {
		
		global $woocommerce, $post, $syn_carriers;
		
		$order = new WC_Order( $post->ID );
		$methods = $order->get_shipping_methods();
		
		$default_carrier = '';
		if( !empty( $methods ) ){
			foreach( $methods as $method ){
				if( strpos($method['method_id'], ':') !== false ){
					$method_id = explode(':', $method['method_id']);
					$default_carrier = $method_id[0];
					break;
				}
			}
		}
		
		$tracking_events	= get_post_meta( $post->ID, '_tracking_events', true );
		$tracking_number	= get_post_meta( $post->ID, '_tracking_number', true );
		$tracking_carrier	= get_post_meta( $post->ID, '_tracking_carrier', true );
		$date_shipped		= get_post_meta( $post->ID, '_tracking_date_shipped', true );
		
		if( ! $date_shipped || empty( $date_shipped ) )
			$date_shipped		= get_post_meta( $post->ID, '_date_shipped', true );
		
		if( ! $tracking_number )
			$tracking_number = '';
			
		if( ! $tracking_carrier )
			$tracking_carrier = $default_carrier;
			
		if( ! $date_shipped )
			$date_shipped = time();
		
		if( ! is_array( $tracking_number ) )
			$tracking_number = array( $tracking_number );
			
		if( ! is_array( $tracking_carrier ) )
			$tracking_carrier = array( $tracking_carrier );
			
		if( ! is_array( $date_shipped ) )
			$date_shipped = array( $date_shipped );
		
		$carriers = array();
		foreach( $syn_carriers as $carrier_id => $carrier ){
			$carriers[ $carrier_id ] = $carrier[ 'carrier_name' ];
		}
		
		?>
		<style>
			.tracking_carrier{
				clear:both;
			}
			#syn-shipment-tracking .tracking_group p.first{
				float: left;
				width: 49%;
				clear: left;
				margin-top:0px;
			}
			#syn-shipment-tracking .tracking_group p.last{
				float: right;
				width: 49%;
				clear: none;
				margin-top:0px;
			}
			#syn-shipment-tracking h3.hndle{
				border-bottom: 1px solid #DDD;
			}
			#syn-shipment-tracking div.inside{
				margin: 0px;
				padding:0px;
			}
			.tracking_carrier label{
				font-size: 1em;
				display: block;
				font-weight: 600;
				margin: 0!important;
				vertical-align: middle;
				width: 100%;
			}
			.tracking_carrier select,
			.tracking_carrier input.allwidth{
				width: 100%;
			}
			.tracking_link{
				font-weight: bold;
			}
			h4#add_tracking {
				margin: 0!important;
			}
			.tracking_group{
				border-top: 1px solid #DDD;
				border-bottom: 1px solid #DFDFDF;
				padding: 10px 12px;
				position:relative;
			}
			.tracking_container{
				border-top: 1px solid #eee;
				background: #f8f8f8;
			}
			.tracking_container > .tracking_carrier:first-child{
				margin-top:0px;
			}
			.tracking_link_container{
				padding: 10px 12px;
			}
			#poststuff #syn-shipment-tracking .tracking_group a.delete_tracking_row {
				text-indent: -9999px;
				height: 1em;
				width: 1em;
				display: none;
				position: absolute;
				top: -.5em;
				right: -.5em;
				font-size: 1.4em;
			}
			#poststuff #syn-shipment-tracking .tracking_group a.delete_tracking_row:before{
				font-family:WooCommerce;
				speak:none;
				font-weight:400;
				font-variant:normal;
				text-transform:none;
				line-height:1;
				-webkit-font-smoothing:antialiased;
				margin:0;
				text-indent:0;
				position:absolute;
				top:0;
				left:0;
				width:100%;
				height:100%;
				text-align:center;
				content:"\e013";
				color:#fff;
				background-color:#000;
				-webkit-border-radius:100%;
				border-radius:100%;
				box-shadow:0 1px 2px rgba(0,0,0,.2);
			}
			#poststuff #syn-shipment-tracking .tracking_group:hover a.delete_tracking_row{
				display: block;
			}
			#poststuff #syn-shipment-tracking .tracking_group a.delete_tracking_row:hover{
				box-shadow:0 1px 2px rgba(0,0,0,0);
			}
			a.view_progress{
				clear: both;
			}
		</style>
		<script>
			var nbTracking = <?php echo( count( $tracking_number ) ); ?>;
			jQuery(function(){
				jQuery( ".tracking_group .tracking_carrier" ).prop( "name", "syn_tracking_carrier[]" );
				jQuery("#order_status").change(function(){
					if( jQuery(this).val() == 'completed' && jQuery('#date_shipped').val() == '' ){
						var dNow = new Date();
						var localdate = dNow.getFullYear() + '-' + ("0" + (dNow.getMonth() + 1)).slice(-2) + '-' + ("0" + dNow.getDate()).slice(-2);
						jQuery('#date_shipped').val( localdate );
					}
				});
				jQuery('#syn-shipment-tracking').on('click','a.add_tracking_number',function(){
					nbTracking++;
					row = jQuery(this).data( 'row' );
					row = row.replace( new RegExp('{i}', 'g'), nbTracking );
					if( jQuery(".tracking_group").length > 0 ){
						jQuery(".tracking_group:last").after( '<div class="tracking_group tracking_container">' + row + '</div>' );
					}else{
						jQuery("#syn-shipment-tracking .inside").after( '<div class="tracking_group tracking_container">' + row + '</div>' );
					}
					jQuery( ".date-picker-field" ).datepicker({
						dateFormat: "yy-mm-dd",
						numberOfMonths: 1,
						showButtonPanel: true,
					});
					jQuery( ".tracking_group .tracking_carrier" ).prop( "name", "syn_tracking_carrier[]" );
					return false;
				});
				jQuery('#syn-shipment-tracking').on('click','a.delete_tracking_row',function(){
					$row = jQuery(this).closest('.tracking_group');
			
					var row_id = $row.attr( 'data-order_item_id' );
			
					if ( row_id ) {
						$row.append('<input type="hidden" name="delete_order_item_id[]" value="' + row_id + '" />').hide();
					} else {
						$row.remove();
					}
			
					return false;
				});
			});
		</script>
		<?php
		
		foreach( $tracking_number as $key => $track_number ){
			
			echo('<div class="tracking_group tracking_container">');
		
			woocommerce_wp_select( array(
				'id'			=> 'syn_tracking_carrier_' . $key,
				'name'			=> 'syn_tracking_carrier[]',
				'label'			=> __( 'Carrier', 'syn_shipping' ),
				'class'			=> 'allwidth tracking_carrier',
				'wrapper_class'	=> 'tracking_carrier first',
				'options'		=> $carriers,
				'value'			=> $tracking_carrier[ $key ]
			) );
			
			woocommerce_wp_text_input( array(
				'id' 			=> 'date_shipped_' . $key,
				'name'			=> 'syn_date_shipped[]',
				'label' 		=> __('Date shipped:', 'syn_shipping'),
				'placeholder' 	=> 'YYYY-MM-DD',
				'description' 	=> '',
				'class'			=> 'date-picker-field allwidth',
				'wrapper_class'	=> 'tracking_carrier last',
				'value'			=> ! empty( $date_shipped[ $key ] ) ? date( 'Y-m-d', $date_shipped[ $key ] ) : ''
			) );
			
			woocommerce_wp_text_input( array(
				'id' 			=> 'tracking_number_' . $key,
				'name' 			=> 'syn_tracking_number[]',
				'label' 		=> __('Tracking number:', 'syn_shipping'),
				'class'			=> 'allwidth',
				'wrapper_class'	=> 'tracking_carrier',
				'placeholder' 	=> '',
				'description' 	=> '',
				'value'			=> $tracking_number[ $key ]
			) );
			
			if ( ! empty( $tracking_number[ $key ] ) && isset( $syn_carriers[ $tracking_carrier[ $key ] ] ) ){
				$link = sprintf( $syn_carriers[ $tracking_carrier[ $key ] ][ 'tracking_url' ], $tracking_number[ $key ] );
				echo( sprintf( '<a href="%s" target="_blank" class="tracking_link">' .__('Track this shipment', 'syn_shipping') . '</a>', $link ) );
			}
			
			/*
if( isset( $tracking_events[ $tracking_number[ $key ] ] ) && ! empty( $tracking_events[ $tracking_number[ $key ] ] ) ){
				if( $tracking_events[ $tracking_number[ $key ] ][ 'delivered' ] )
					echo( '<span style="float:right;">DELIVERED</span>' );
			}
*/
			
			echo('<a href="#" class="delete_tracking_row">×</a><div class="clear"></div></div>');
			
		}
		
		ob_start();
		woocommerce_wp_select( array(
			'id'			=> 'syn_tracking_carrier_{i}',
			'name'			=> 'syn_tracking_carrier[]',
			'label'			=> __( 'Carrier', 'syn_shipping' ),
			'class'			=> 'allwidth tracking_carrier',
			'wrapper_class'	=> 'tracking_carrier first',
			'options'		=> $carriers,
			'value'			=> $default_carrier
		) );
		
		woocommerce_wp_text_input( array(
			'id' 			=> 'syn_date_shipped_{i}',
			'name'			=> 'syn_date_shipped[]',
			'label' 		=> __('Date shipped:', 'syn_shipping'),
			'placeholder' 	=> 'YYYY-MM-DD',
			'description' 	=> '',
			'class'			=> 'date-picker-field allwidth',
			'wrapper_class'	=> 'tracking_carrier last',
			'value'			=> date( 'Y-m-d' )
		) );
		
		woocommerce_wp_text_input( array(
			'id' 			=> 'tracking_number_{i}',
			'name' 			=> 'syn_tracking_number[]',
			'label' 		=> __('Tracking number:', 'syn_shipping'),
			'class'			=> 'allwidth',
			'wrapper_class'	=> 'tracking_carrier',
			'placeholder' 	=> '',
			'description' 	=> '',
			'value'			=> ''
		) );
		echo('<a href="#" class="delete_tracking_row">×</a><div class="clear"></div>');
		$template = htmlentities( ob_get_clean() );
		
		echo('<div class="tracking_link_container"><h4 id="add_tracking"><a href="#" class="add_tracking_number" data-row="' . $template . '">+ ' . __( 'Add tracking number', 'syn_shipping' ) . ' <span class="tips" data-tip="' . __( 'You can add multiple tracking for this order.', 'syn_shipping' ) . '">[?]</span></a></a></h4></div>');
		
	}
	
	/**
	 * Order Save
	 *
	 * Function for processing and storing all order.
	 */
	function syn_save_meta_box_tracking( $post_id, $post ) {
		if ( isset( $_POST['syn_tracking_number'] ) ) {
		
			$hasnumber = false;
			$tracking_carrier	= $_POST['syn_tracking_carrier'];
			$tracking_number	= $_POST['syn_tracking_number'];
			$date_shipped		= $_POST['syn_date_shipped'];
			
			if( ! empty( $tracking_carrier ) && is_array( $tracking_carrier ) ){
				foreach( $tracking_carrier as &$carrier ){
					$carrier = woocommerce_clean( $carrier );
				}
			}else if( ! empty( $tracking_carrier ) && ! is_array( $tracking_carrier ) ){
				$tracking_carrier = array( woocommerce_clean( $tracking_carrier ) );
			}
			
			if( ! empty( $tracking_number ) && is_array( $tracking_number ) ){
				foreach( $tracking_number as &$number ){
					$number = woocommerce_clean( $number );
					
					if( ! empty( $number ) )
						$hasnumber = true;
					
				}
			}else if( ! empty( $tracking_number ) && ! is_array( $tracking_number ) ){
				$tracking_number = array( woocommerce_clean( $tracking_number ) );
			}
			
			if( ! empty( $date_shipped ) && is_array( $date_shipped ) ){
				foreach( $date_shipped as &$date ){
					$date = strtotime( woocommerce_clean( $date ) );
				}
			}else if( ! empty( $date_shipped ) && ! is_array( $date_shipped ) ){
				$date_shipped = array( strtotime( woocommerce_clean( $date_shipped ) ) );
			}

			if( $hasnumber ){
				
				// Update order data
				update_post_meta( $post_id, '_tracking_carrier', $tracking_carrier );
				update_post_meta( $post_id, '_tracking_number', $tracking_number );
				update_post_meta( $post_id, '_tracking_date_shipped', $date_shipped );
				
				syn_update_tracking( $post_id );
				
			}
			
		}else{
		
			delete_post_meta( $post_id, '_tracking_carrier' );
			delete_post_meta( $post_id, '_tracking_number' );
			delete_post_meta( $post_id, '_tracking_date_shipped' );
			delete_post_meta( $post_id, '_date_shipped' );
			delete_post_meta( $post_id, '_tracking_events' );
			
		}
	}
	add_action( 'woocommerce_process_shop_order_meta', 'syn_save_meta_box_tracking', 0, 2 );
	
	/**
	 * Display Shipment info in the frontend (order view/tracking page).
	 *
	 * @access public
	 */
	function syn_display_tracking_info( $order_id, $for_email = false ) {
		global $syn_carriers;

		$tracking_carrier	= get_post_meta( $order_id, '_tracking_carrier', true );
		$tracking_number	= get_post_meta( $order_id, '_tracking_number', true );
		$date_shipped		= get_post_meta( $order_id, '_tracking_date_shipped', true );
		
		if( ! $tracking_number || empty( $tracking_number ) )
			return;
			
		if( empty( $date_shipped ) )
			$date_shipped		= get_post_meta( $order_id, '_date_shipped', true );
		
		if( ! is_array( $tracking_number ) )
			$tracking_number = array( $tracking_number );
			
		if( ! is_array( $tracking_carrier ) )
			$tracking_carrier = array( $tracking_carrier );
			
		if( ! is_array( $date_shipped ) )
			$date_shipped = array( $date_shipped );
			
		foreach( $tracking_number as $key => $tracking_num ){
			
			if ( ! isset( $syn_carriers[ $tracking_carrier[ $key ] ] ) )
				return;
				
			if ( ! empty( $date_shipped[ $key ] ) )
				$date_shipped[ $key ] = ' ' . sprintf( __( 'on %s', 'syn_shipping' ), date_i18n( __( 'l jS F Y', 'syn_shipping'), $date_shipped[ $key ] ) );
			
			$text = ( count( $tracking_number ) > 1 ? __( 'Part of your order', 'syn_shipping' ) : __( 'Your order', 'syn_shipping' ) );
			
			$tracking_provider = $syn_carriers[ $tracking_carrier[ $key ] ][ 'carrier_name' ];
	
			$tracking_provider = ' ' . __('via', 'syn_shipping') . ' <strong>' . $tracking_provider . '</strong>';
	
			$tracking_link = '';
			if( $tracking_number[ $key ] && isset( $syn_carriers[ $tracking_carrier[ $key ] ] ) ){
	
				$link_format = $syn_carriers[ $tracking_carrier[ $key ] ][ 'tracking_url' ];
		
				if ( $link_format ) {
					$link = sprintf( $link_format, $tracking_number[ $key ] );
					$tracking_link = sprintf( '<a href="%s" target="_blank">' .__('Track your package', 'syn_shipping') . '</a>', $link );
				}
				$tracking_link = sprintf( __( '<br />Tracking number: %s<br />%s', 'syn_shipping' ), $tracking_number[ $key ], $tracking_link );
			}
	
			echo wpautop( sprintf( __( '%s was shipped%s%s.%s', 'syn_shipping' ), $text, $date_shipped[ $key ], $tracking_provider, $tracking_link ) );
			
		}

	}
	add_action( 'woocommerce_view_order', 'syn_display_tracking_info' );
	
	/**
	 * Display shipment info in customer emails.
	 *
	 * @access public
	 * @return void
	 */
	function syn_email_display_tracking( $order ) {
		syn_display_tracking_info( $order->id, true );
	}
	add_action( 'woocommerce_email_before_order_table', 'syn_email_display_tracking' );
	
	function syn_admin_enqueue_scripts(){
		
		global $woocommerce;
		if (version_compare($woocommerce->version, '2.1.0') >= 0) {
			wp_register_style('syn_shipping', plugin_dir_url( dirname( dirname( __FILE__ ) ) . '/syn-shipping/assets/css/admin.css' ) . 'admin.css', array(), '1.0.1');
			wp_enqueue_style('syn_shipping');
		}
		
		wp_register_script('syn_shipping', plugin_dir_url( dirname( dirname( __FILE__ ) ) . '/syn-shipping/assets/js/admin.js' ) . 'admin.js', array('jquery'));
		wp_enqueue_script('syn_shipping');

	}
	add_action('admin_enqueue_scripts', 'syn_admin_enqueue_scripts');
	
	function syn_woocommerce_admin_order_actions( $actions, $the_order ){
		
		global $woocommerce, $syn_carriers;

		$tracking_carrier	= get_post_meta( $the_order->id, '_tracking_carrier', true );
		$tracking_number	= get_post_meta( $the_order->id, '_tracking_number', true );
		$date_shipped		= get_post_meta( $the_order->id, '_tracking_date_shipped', true );
		$tracking_events	= get_post_meta( $the_order->id, '_tracking_events', true );
		
		if ( ! $tracking_number || ! $tracking_carrier )
			return $actions;
			
		if( empty( $date_shipped ) )
			$date_shipped		= get_post_meta( $the_order->id, '_date_shipped', true );
		
		if( ! is_array( $tracking_number ) )
			$tracking_number = array( $tracking_number );
			
		if( ! is_array( $tracking_carrier ) )
			$tracking_carrier = array( $tracking_carrier );
			
		if( ! is_array( $date_shipped ) )
			$date_shipped = array( $date_shipped );
			
		foreach( $tracking_number as $key => $tracking_num ){
			
			if ( ! empty( $date_shipped[ $key ] ) )
				$date_shipped[ $key ] = ' ' . sprintf( __( 'on %s', 'syn_shipping' ), date_i18n( __( 'l jS F Y', 'syn_shipping'), $date_shipped[ $key ] ) );
			
			$text = ( count( $tracking_number ) > 1 ? ' #' . ( $key + 1 ) : '' );
			$text_admin = sprintf( __( ' from %s: %s', 'syn_shipping' ), $syn_carriers[ $tracking_carrier[ $key ] ][ 'carrier_name' ], $tracking_num );
			$link = sprintf( $syn_carriers[ $tracking_carrier[ $key ] ][ 'tracking_url' ], $tracking_number[ $key ] );
			$delivered = ( isset( $tracking_events[ $tracking_num ][ 'delivered' ] ) && $tracking_events[ $tracking_num ][ 'delivered' ] ? true : false );
			
			$actions[ 'track_' . $key ] = array(
				'url' 		=> $link,
				'name' 		=> is_admin() ? sprintf( __( 'Track Package shipped%s%s', 'syn_shipping' ), $date_shipped[ $key ], $text_admin ) : sprintf( __( 'Tracking%s', 'syn_shipping' ), $text ),
				'action' 	=> ( $delivered ? 'track_shipment' : 'track_shipment' ),
				'image_url'	=> plugin_dir_url( dirname( dirname( __FILE__ ) ) . '/syn-shipping/assets/images/icons/track.png' ) . ( $delivered ? 'track.png' : 'track.png' )
			);
		}
		
		return $actions;
		
	}
	add_filter( 'woocommerce_admin_order_actions', 'syn_woocommerce_admin_order_actions', 1, 2 );
	add_filter( 'woocommerce_my_account_my_orders_actions', 'syn_woocommerce_admin_order_actions', 1, 2 );
	
	/**
	 * On an early action hook, check if the hook is scheduled - if not, schedule it.
	 */
	function prefix_setup_schedule() {
		if ( ! wp_next_scheduled( 'syn_scheduled_tracking' ) ) {
			wp_schedule_event( time(), 'hourly', 'syn_scheduled_tracking');
		}
	}
	//add_action( 'wp', 'prefix_setup_schedule' );
	
	/**
	 * On the scheduled action hook, run a function.
	 */
	function syn_update_tracking( $post_id = null ) {
		
		global $wpdb, $syn_carriers;
		
		if( empty( $post_id ) ){
			
			$posts = $wpdb->get_results( "SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key` = '_tracking_carrier'" );
			
		}else{
			
			$post = new stdClass();
			$post->post_id = $post_id;
			$posts = array( $post );
			
		}
		
		if( empty( $posts ) )
			return;
			
		foreach( $posts as $post ){
			
			$trackings			= array();
			$tracking_carriers	= get_post_meta( $post->post_id, '_tracking_carrier', true );
			$tracking_numbers	= get_post_meta( $post->post_id, '_tracking_number', true );
			$tracking_events	= get_post_meta( $post->post_id, '_tracking_events', true );
			
			if( ! is_array( $tracking_numbers ) )
				$tracking_numbers = array( $tracking_numbers );
				
			if( ! is_array( $tracking_carriers ) )
				$tracking_carriers = array( $tracking_carriers );
				
			if( ! is_array( $tracking_events ) )
				$tracking_events = array();
			
			foreach( $tracking_numbers as $key => $tracking_number ){
				
				if( ! isset( $syn_carriers[ $tracking_carriers[ $key ] ] ) || empty( $tracking_number ) ){
					continue;
				}
				
				if( isset( $tracking_events[ $tracking_number ][ 'delivered' ] ) && $tracking_events[ $tracking_number ][ 'delivered' ] ){
					$trackings[ $tracking_number ] = $tracking_events[ $tracking_number ];
					continue;
				}
					
				$carrier = new $syn_carriers[ $tracking_carriers[ $key ] ][ 'class_name' ]();
				
				if( ! method_exists( $carrier, 'get_tracking' ) )
					continue;
				
				$trackings[ $tracking_number ] = $carrier->get_tracking( $tracking_number );
				
			}
			
			update_post_meta( $post->post_id, '_tracking_events', $trackings );
			
		}
		
	}
	add_action( 'syn_scheduled_tracking', 'syn_update_tracking' );
	
}

?>