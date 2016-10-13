<?php
/*
	* SYN_Shipping_Method class.
	* Version: 1.1.5
	* @extends WC_Shipping_Method
*/
class SYN_Shipping_Method extends WC_Shipping_Method {

	public $weight_unit			= "";
	public $dimension_unit		= "";
	
	public $use_for_tracking	= false;

	public function __construct(){

		$this->weight_unit		= strtolower( get_option( 'woocommerce_weight_unit' ) );
		$this->dimension_unit	= strtolower( get_option( 'woocommerce_dimension_unit' ) );
		
		if( $this->can_track() )
			syn_add_tracking( $this->id, $this->method_title, $this->tracking_url, get_class( $this ) );
		
	}
	
	protected function can_track(){
		return ! empty( $this->tracking_url );
	}
	
	protected function is_general_shipping_page(){
		return isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'wc-settings' && isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == 'shipping' && ( !isset( $_GET[ 'section' ] ) || ( isset( $_GET[ 'section' ] ) && empty( $_GET[ 'section' ] ) ) );
	}
	
	protected function get_var( $key, $empty_value = null ){
		
		$option = '';
		
		if( is_null( $empty_value ) )
			$empty_value = $this->get_field_default( $key );
		
		if( is_bool( $empty_value ) && ! ( $key == 'enabled' && $this->is_general_shipping_page() ) ){

			$option = isset( $this->settings[ $key ] ) ? ( $this->settings[ $key ] == 'yes' ? true : false ) : $empty_value;

		}else{
			
			$option = parent::get_option( $key, $empty_value );
			
		}
		
		return $option;
		
	}
	
	protected function get_field_default( $key ){

		return isset( $this->form_fields[ $key ] ) ? ( $this->form_fields[ $key ][ 'type' ] == 'checkbox' ? ( $this->form_fields[ $key ][ 'default' ] == 'yes' ? true : false ) : $this->form_fields[ $key ][ 'default' ] ) : null;
		
	}
	
	protected function get_country_state( $country_state ){
		if( strstr( $country_state, ':' ) ){
			return array(
				'country'	=> current( explode( ':', $country_state ) ),
				'state'		=> ( ( $pos = strrpos( $country_state, ':' ) ) === false ) ? '' : substr( $country_state, $pos + 1 )
			);
		}else{
			return array(
				'country'	=> $country_state,
				'state'		=> '*'
			);
		}
	}
	
	/**
	 * Get shipping quotes based on change of shipping address
	 * 
	 * @method calculate_shipping
	 * @abstract setup shipping rate for each selected shipping option
	 */
	public function calculate_shipping( $package ){
		global $woocommerce;
		
		$this->add_notice( sprintf( __( '%s: Enter calculate shipping function.', 'syn_shipping' ), $this->method_title ) );
		
		if( empty( $package[ 'destination' ][ 'country' ] ) || empty( $package[ 'destination' ][ 'postcode' ] ) ){
			
			$this->add_notice( sprintf( __( '%s: No country or Zip / postcode provided. Abording.', 'syn_shipping' ), $this->method_title ) );
			return;
			
		}
		
		if( !$this->has_enabled_address() ){
		
			$this->add_notice( sprintf( __( '%s: No origin address enabled. Abording.', 'syn_shipping' ), $this->method_title ), 'error' );
			return;
			
		}
		
		if( !$this->has_enabled_methods() ){
		
			$this->add_notice( sprintf( __( '%s: No shipping method selected.', 'syn_shipping' ), $this->method_title ), 'error' );
			return;
			
		}
		
		$this->get_shipping_request( $package );
		
	}
	
	/**
	 * clear_transients function.
	 *
	 * @access public
	 * @return void
	 */
	public function clear_transients() {
		syn_clear_transients( $this->id );
	}
	
	/**
	 * add_notice function
	 * Provide a nice way to handle add_message change in Woo 2.1
	 */
	protected function add_notice( $message, $notice_type = 'success' ){
		global $woocommerce;
		
		if( ! $this->debug || ! $this->enabled || ! is_cart() )
			return false;
		
		if (version_compare($woocommerce->version, '2.1.0') >= 0) {
		    wc_add_notice( $message, $notice_type );
		}else{
			switch( $notice_type ){
				case 'success':
					$woocommerce->add_message( $message );
					break;
				case 'error':
					$woocommerce->add_error( $message );
					break;
			}
		}
		
	}
	
	/**
	 * Shipping method available condition:
	 * 1. Enabled
	 * 2. Dest country is in the list
	 * 
	 * @global type $woocommerce
	 * @return type 
	 */
	public function is_available( $package ) {
		global $woocommerce;
		
		if( ! $this->is_enabled() )
			return false;
			
		// Country availability
		switch ( $this->availability ) {
			case 'specific' :
			case 'including' :
				$ship_to_countries = array_intersect( $this->countries, array_keys( ( method_exists( $woocommerce->countries, 'get_shipping_countries' ) ? $woocommerce->countries->get_shipping_countries() : $woocommerce->countries->get_allowed_countries() ) ) );
			break;
			case 'excluding' :
				$ship_to_countries = array_diff( $this->countries, array_keys( ( method_exists( $woocommerce->countries, 'get_shipping_countries' ) ? $woocommerce->countries->get_shipping_countries() : $woocommerce->countries->get_allowed_countries() ) ) );
			break;
			default :
				$ship_to_countries = array_keys( ( method_exists( $woocommerce->countries, 'get_shipping_countries' ) ? $woocommerce->countries->get_shipping_countries() : $woocommerce->countries->get_allowed_countries() ) );
			break;
		}

		if ( ! in_array( $package['destination']['country'], $ship_to_countries ) )
			return false;

		return true;
	}
	
	public function has_enabled_methods(){
		
		$enabled = false;
		
		if( !empty( $this->custom_methods ) ){
		
			foreach( $this->custom_methods as $method_key => $service ){
				
				if( $service['enabled'] ){

					$enabled = true;
					break;
					
				}
				
			}
			
		}
		
		return $enabled;
		
	}
	
	public function has_enabled_address(){
		
		$enabled = false;
		
		if( isset( $this->addresses ) && !empty( $this->addresses ) ){
		
			foreach( $this->addresses as $address_key => $address ){
				
				if( $address['enabled'] ){

					$enabled = true;
					break;
					
				}
				
			}
			
		}else if( !isset( $this->addresses ) && isset( $this->origin_postalcode ) && !empty( $this->origin_postalcode ) ){
			$enabled = true;
		}
		
		return $enabled;
		
	}
	
	protected function box_shipping_packages( $package ) {
	    global $woocommerce;
	    
	    if ( ! class_exists( 'SYN_Packing' ) )
			require_once( 'packing/class-syn-packing.php' );

	    $requests = array();

	    $boxpack = new SYN_Packing();

	    // Define boxes
	    if ( ! empty( $this->boxes ) ) {
			foreach ( $this->boxes as $box ) {
				$newbox = $boxpack->add_box( $box );
			}
		}

		// Add items
		foreach ( $package['contents'] as $item_id => $values ) {

    		if ( ! $values['data']->needs_shipping() ) {
    			$this->add_notice( sprintf( __( 'Product #%d is virtual. Skipping.', 'syn_shipping' ), $values[ 'product_id' ] ) );
    			continue;
    		}
    		
    		if( !$values['data']->get_weight() || !$values['data']->length || !$values['data']->height || !$values['data']->width ){
    			$this->add_notice( sprintf( __( 'Product <a href="%s" target="_blank">#%d</a> is missing dimensions and / or weight. Aborting %s quotes.', 'syn_shipping' ), get_edit_post_link( $values[ 'product_id' ] ), $values[ 'product_id' ], $this->method_title ), 'error' );
	    		return false;
    		}

			$dimensions = array( $values['data']->length, $values['data']->height, $values['data']->width );

			for ( $i = 0; $i < $values['quantity']; $i ++ ) {
				$boxpack->add_item(
					number_format( woocommerce_get_dimension( $dimensions[2], $this->dimension_unit ), 2, '.', ''),
					number_format( woocommerce_get_dimension( $dimensions[1], $this->dimension_unit ), 2, '.', ''),
					number_format( woocommerce_get_dimension( $dimensions[0], $this->dimension_unit ), 2, '.', ''),
					number_format( woocommerce_get_weight( $values['data']->get_weight(), $this->weight_unit ), 2, '.', ''),
					$values['data']->get_price()
				);
			}
		}

		// Pack it
		$boxpack->pack();

		// Return packages
		return $boxpack->get_packages();
    }
    
    /**
	 * generate_box_packing_html function.
	 *
	 * @access public
	 * @return void
	 */
	public function generate_box_packing_html() {
		ob_start();
		?>
		<tr valign="top" id="packing_options">
			<th scope="row" class="titledesc"><?php _e( 'Custom Boxes', 'syn_shipping' ); ?></th>
			<td class="forminp">
				<style type="text/css">
					.shipping_boxes td, .shipping_services td {
						vertical-align: middle;
						padding: 4px 7px;
					}
					.shipping_boxes th, .shipping_services th {

					}
					.shipping_boxes td input {
						margin-right: 4px;
					}
					.shipping_boxes tbody td input[type="checkbox"]{
						margin-right:0px;
					}
					.shipping_boxes .check-column {
						vertical-align: middle;
						text-align: left;
						padding: 0 7px;
					}
					.shipping_boxes tbody .check-column {
						text-align: center;
					}
					table.shipping_boxes tbody td input[type=text] {
						width: 60%;
					}
					.shipping_services th.sort {
						width: 16px;
						padding: 0 16px;
					}
					.shipping_services td.sort {
						cursor: move;
						width: 16px;
						padding: 0 16px;
						cursor: move;
						background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAAHUlEQVQYV2O8f//+fwY8gJGgAny6QXKETRgEVgAAXxAVsa5Xr3QAAAAASUVORK5CYII=) no-repeat center;					}
				</style>
				<table class="shipping_boxes wc_tax_rates widefat">
					<thead>
						<tr>
							<th class="check-column"><input type="checkbox" /></th>
							<th><?php _e( 'Outer Length', 'syn_shipping' ); ?></th>
							<th><?php _e( 'Outer Width', 'syn_shipping' ); ?></th>
							<th><?php _e( 'Outer Height', 'syn_shipping' ); ?></th>
							<th><?php _e( 'Inner Length', 'syn_shipping' ); ?></th>
							<th><?php _e( 'Inner Width', 'syn_shipping' ); ?></th>
							<th><?php _e( 'Inner Height', 'syn_shipping' ); ?></th>
							<th><?php _e( 'Box Weight', 'syn_shipping' ); ?></th>
							<th><?php _e( 'Max Weight', 'syn_shipping' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="3">
								<a href="#" class="button plus insert"><?php _e( 'Add Box', 'syn_shipping' ); ?></a>
								<a href="#" class="button minus remove"><?php _e( 'Remove selected box(es)', 'syn_shipping' ); ?></a>
							</th>
							<th colspan="6">
								<small class="description"><?php echo( sprintf( __( 'Items will be packed into these boxes based on item dimensions and volume. Outer dimensions will be passed to %s, whereas inner dimensions will be used for packing. Items not fitting into boxes will be packed individually.', 'syn_shipping' ), $this->method_title ) ); ?></small>
							</th>
						</tr>
					</tfoot>
					<tbody id="rates">
						<?php
							if ( $this->boxes && ! empty( $this->boxes ) ) {
								foreach ( $this->boxes as $key => $box ) {
									?>
									<tr>
										<td class="check-column"><input type="checkbox" /></td>
										<td><input type="text" size="5" name="boxes_outer_length[<?php echo $key; ?>]" value="<?php echo esc_attr( $box['outer_length'] ); ?>" /><?php echo $this->dimension_unit; ?></td>
										<td><input type="text" size="5" name="boxes_outer_width[<?php echo $key; ?>]" value="<?php echo esc_attr( $box['outer_width'] ); ?>" /><?php echo $this->dimension_unit; ?></td>
										<td><input type="text" size="5" name="boxes_outer_height[<?php echo $key; ?>]" value="<?php echo esc_attr( $box['outer_height'] ); ?>" /><?php echo $this->dimension_unit; ?></td>
										<td><input type="text" size="5" name="boxes_inner_length[<?php echo $key; ?>]" value="<?php echo esc_attr( $box['inner_length'] ); ?>" /><?php echo $this->dimension_unit; ?></td>
										<td><input type="text" size="5" name="boxes_inner_width[<?php echo $key; ?>]" value="<?php echo esc_attr( $box['inner_width'] ); ?>" /><?php echo $this->dimension_unit; ?></td>
										<td><input type="text" size="5" name="boxes_inner_height[<?php echo $key; ?>]" value="<?php echo esc_attr( $box['inner_height'] ); ?>" /><?php echo $this->dimension_unit; ?></td>
										<td><input type="text" size="5" name="boxes_box_weight[<?php echo $key; ?>]" value="<?php echo esc_attr( $box['box_weight'] ); ?>" /><?php echo $this->weight_unit; ?></td>
										<td><input type="text" size="5" name="boxes_max_weight[<?php echo $key; ?>]" value="<?php echo esc_attr( $box['max_weight'] ); ?>" /><?php echo $this->weight_unit; ?></td>
									</tr>
									<?php
								}
							}
						?>
					</tbody>
				</table>
				<script type="text/javascript">

					jQuery(window).load(function(){

						jQuery('.shipping_boxes .insert').click( function() {
							var $tbody = jQuery('.shipping_boxes').find('tbody');
							var size = $tbody.find('tr').size();
							var code = '<tr class="new">\
									<td class="check-column"><input type="checkbox" /></td>\
									<td><input type="text" size="5" name="boxes_outer_length[' + size + ']" /><?php echo $this->dimension_unit; ?></td>\
									<td><input type="text" size="5" name="boxes_outer_width[' + size + ']" /><?php echo $this->dimension_unit; ?></td>\
									<td><input type="text" size="5" name="boxes_outer_height[' + size + ']" /><?php echo $this->dimension_unit; ?></td>\
									<td><input type="text" size="5" name="boxes_inner_length[' + size + ']" /><?php echo $this->dimension_unit; ?></td>\
									<td><input type="text" size="5" name="boxes_inner_width[' + size + ']" /><?php echo $this->dimension_unit; ?></td>\
									<td><input type="text" size="5" name="boxes_inner_height[' + size + ']" /><?php echo $this->dimension_unit; ?></td>\
									<td><input type="text" size="5" name="boxes_box_weight[' + size + ']" /><?php echo $this->weight_unit; ?></td>\
									<td><input type="text" size="5" name="boxes_max_weight[' + size + ']" /><?php echo $this->weight_unit; ?></td>\
								</tr>';

							$tbody.append( code );

							return false;
						} );

						jQuery('.shipping_boxes .remove').click(function() {
							var $tbody = jQuery('.shipping_boxes').find('tbody');

							$tbody.find('.check-column input:checked').each(function() {
								jQuery(this).closest('tr').hide().find('input').val('');
							});

							return false;
						});

						// Ordering
						jQuery('.shipping_services tbody').sortable({
							items:'tr',
							cursor:'move',
							axis:'y',
							handle: '.sort',
							scrollSensitivity:40,
							forcePlaceholderSize: true,
							helper: 'clone',
							opacity: 0.65,
							placeholder: 'wc-metabox-sortable-placeholder',
							start:function(event,ui){
								ui.item.css('baclbsround-color','#f6f6f6');
							},
							stop:function(event,ui){
								ui.item.removeAttr('style');
								shipping_services_row_indexes();
							}
						});

						function shipping_services_row_indexes() {
							jQuery('.shipping_services tbody tr').each(function(index, el){
								jQuery('input.order', el).val( parseInt( jQuery(el).index('.shipping_services tr') ) );
							});
						};

					});

				</script>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}
	
	/**
	 * validate_box_packing_field function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function validate_box_packing_field( $key ) {

		$boxes = array();

		if ( isset( $_POST['boxes_outer_length'] ) ) {
			$boxes_outer_length = $_POST['boxes_outer_length'];
			$boxes_outer_width  = $_POST['boxes_outer_width'];
			$boxes_outer_height = $_POST['boxes_outer_height'];
			$boxes_inner_length = $_POST['boxes_inner_length'];
			$boxes_inner_width  = $_POST['boxes_inner_width'];
			$boxes_inner_height = $_POST['boxes_inner_height'];
			$boxes_box_weight   = $_POST['boxes_box_weight'];
			$boxes_max_weight   = $_POST['boxes_max_weight'];


			for ( $i = 0; $i < sizeof( $boxes_outer_length ); $i ++ ) {

				if ( $boxes_outer_length[ $i ] && $boxes_outer_width[ $i ] && $boxes_outer_height[ $i ] && $boxes_inner_length[ $i ] && $boxes_inner_width[ $i ] && $boxes_inner_height[ $i ] ) {

					$boxes[] = array(
						'outer_length' => floatval( $boxes_outer_length[ $i ] ),
						'outer_width'  => floatval( $boxes_outer_width[ $i ] ),
						'outer_height' => floatval( $boxes_outer_height[ $i ] ),
						'inner_length' => floatval( $boxes_inner_length[ $i ] ),
						'inner_width'  => floatval( $boxes_inner_width[ $i ] ),
						'inner_height' => floatval( $boxes_inner_height[ $i ] ),
						'box_weight'   => floatval( $boxes_box_weight[ $i ] ),
						'max_weight'   => floatval( $boxes_max_weight[ $i ] ),
					);

				}

			}

		}

		return $boxes;
	}
	
	public function generate_custom_methods_html ( $key, $data ) {
    	global $woocommerce;
    	
    	ob_start();
		?>
		<tr valign="top" id="method_options">
			<th scope="row" class="titledesc"><?php _e( 'Services', 'syn_shipping' ); ?></th>
			<td class="forminp">
				<table class="wc_tax_rates widefat">
					<thead>
						<tr>
							<th class="sort">&nbsp;</th>
			
							<th><?php _e( 'Method name', 'syn_shipping' ); ?>&nbsp;<img class="help_tip" data-tip="<?php _e('The method name that will be shown on the cart and checkout.', 'syn_shipping'); ?>" src="<?php echo( esc_url( $woocommerce->plugin_url() ) )?>/assets/images/help.png" height="16" width="16" /></th>
			
							<th style="width:15%;"><?php _e( 'Price adjustment', 'syn_shipping' ); ?>&nbsp;<img class="help_tip" data-tip="<?php _e('Surcharge for this method, enter either a fixed amount (Ex.: 3.34) or a % amount (Ex.: 3.34%)', 'syn_shipping'); ?>" src="<?php echo( esc_url( $woocommerce->plugin_url() ) )?>/assets/images/help.png" height="16" width="16" /></th>
							
							<?php if( isset( $this->accept_regional_method_restriction ) ){ ?>
							<th><?php _e( 'Service by region', 'syn_shipping' ); ?>&nbsp;<img class="help_tip" data-tip="<?php _e('You can select a regional service availability here. It will be checked after the general Method availability. Leave empty to enable all region.', 'syn_shipping'); ?>" src="<?php echo( esc_url( $woocommerce->plugin_url() ) )?>/assets/images/help.png" height="16" width="16" /></th>
							<th><?php _e( 'Service by zip/postal code', 'syn_shipping' ); ?>&nbsp;<img class="help_tip" data-tip="<?php _e('Enter one or multiple zip code to accept. You can also enter the starting aprt of the zip/postal code. Ex.: 12 will accept all zip code starting with 12, (12508,12518,12520). Each zip/postal code must be separated with a pipe : |', 'syn_shipping'); ?>" src="<?php echo( esc_url( $woocommerce->plugin_url() ) )?>/assets/images/help.png" height="16" width="16" /></th>
							<?php } ?>
			
							<th style="width:8%;"><?php _e( 'Enabled', 'syn_shipping' ); ?>&nbsp;<img class="help_tip" data-tip="<?php _e('Enable this shipping method or not', 'syn_shipping'); ?>" src="<?php echo( esc_url( $woocommerce->plugin_url() ) )?>/assets/images/help.png" height="16" width="16" /></th>
			
						</tr>
					</thead>
					<tbody id="methods">
					
					<?php if( !empty( $this->custom_methods ) ){ ?>
					
					<?php foreach( $this->custom_methods as $method_key => $service ){ ?>
					
						<tr>
							<td class="sort"><input type="hidden" class="order_shipping_method" name="order_shipping_method[]" value="<?php echo( $method_key ); ?>" /><input type="hidden" name="method_comments[]" value="<?php echo( isset( $service[ 'comments' ] ) ? $service[ 'comments' ] : '' ); ?>" /></td>
	
							<td class="method_name">
								<input type="text" name="method_name[]" value="<?php echo esc_attr( $service['name'] ) ?>" />
								<?php if( isset( $service[ 'comments' ] ) && !empty( $service[ 'comments' ] ) ){ ?><img class="help_tip" data-tip="<?php echo( $service[ 'comments' ] ); ?>" src="<?php echo( esc_url( $woocommerce->plugin_url() ) )?>/assets/images/help.png" height="16" width="16" /><?php } ?>
							</td>
	
							<td class="price_ajustment">
								<input type="text" name="method_price_ajustment[]" value="<?php echo esc_attr( $service['price_ajustment'] ) ?>" placeholder="<?php _e( 'Ex.: 3.34 or 3.34%', 'syn_shipping' ); ?>" />
							</td>
							
							<?php if( isset( $this->accept_regional_method_restriction ) ){ ?>
							<td class="regional_service_availability">
								<select name="regional_service_availability_<?php echo $method_key; ?>[]" multiple="multiple" class="multiselect chosen_select">
								<?php
								
								if ( isset( $woocommerce->countries->countries ) && ! empty( $woocommerce->countries->countries ) ){
								
									foreach ( $woocommerce->countries->countries as $key => $country ){
									
										if ( $states = $woocommerce->countries->get_states( $key ) ){
											
											echo '<optgroup label="' . esc_attr( $country ) . '">';
											
											foreach ( $states as $state_key => $state_value ){
												
												echo '<option value="' . esc_attr( $key ) . ':' . $state_key . '"' . ( isset( $service[ 'regional_service_availability' ] ) && is_array( $service[ 'regional_service_availability' ] ) && in_array( esc_attr( $key ) . ':' . $state_key, $service[ 'regional_service_availability' ] ) ? ' selected="selected"' : '' ) . '>';
												
												echo $country . ' &mdash; ' . $state_value . '</option>';
												
											}
											
											echo '</optgroup>';
											
										} else {
											
											echo '<option value="' . $key . '"' . ( isset( $service[ 'regional_service_availability' ] ) && is_array( $service[ 'regional_service_availability' ] ) && in_array( esc_attr( $key ), $service[ 'regional_service_availability' ] ) ? ' selected="selected"' : '' ) . '>' . $country . '</option>';
											
										}
									
									}
								
								}
								
								?>
								</select>
							</td>
							<td class="zip_service_availability">
								<input type="text" name="zip_service_availability[]" value="<?php echo esc_attr( isset( $service[ 'zip_service_availability' ] ) ? $service[ 'zip_service_availability' ] : '' ) ?>" placeholder="<?php _e( 'Ex.: 12518|13519 or 12|13', 'syn_shipping' ); ?>" />
							</td>
							<?php } ?>
	
							<td class="method_enabled" style="width:8%;" align="center">
								<input type="checkbox" class="checkbox" name="method_enabled_<?php echo($method_key); ?>" value="1" style="width:auto;"<?php echo( $service['enabled'] ? ' checked="checked"' : '' ); ?> />
							</td>
	
						</tr>
					
					<?php } ?>
					
					<?php } ?>

					</tbody>
				</table>
				<style>
					#methods td{
						box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
					}
					#methods input{
						box-shadow: none;
						-webkit-box-shadow: none;
					}
					.woocommerce .form-table #method_options table img.help_tip{
						margin:0px;
						float: none;
					}
					.method_name{
						position:relative;
					}
					.woocommerce .form-table #method_options table #methods img.help_tip{
						position:absolute;
						top:6px;
						right:6px;
					}
					table.wc_tax_rates td.regional_service_availability{
						overflow:visible;
						vertical-align: top;
					}
					table.wc_tax_rates td.regional_service_availability .chosen-container{
						margin-bottom: 0px;
						width:100% !important;
					}
					table.wc_tax_rates td.regional_service_availability .chosen-container .chosen-choices{
						border:0px;
					}
				</style>
				<script type="text/javascript">
					jQuery( function() {
						jQuery('.wc_tax_rates tbody').sortable({
							items:'tr',
							handle:'.sort',
							cursor:'move',
							axis:'y',
							scrollSensitivity:40,
							forcePlaceholderSize: true,
							helper: 'clone',
							opacity: 0.65,
							placeholder: 'wc-metabox-sortable-placeholder',
							start:function(event,ui){
								ui.item.css('background-color','#f6f6f6');
							},
							stop:function(event,ui){
								ui.item.removeAttr('style');
							}
						});
					});
				</script>
			</td>
		</tr>
		<?php
		
		return ob_get_clean();
		
    }
    
    public function validate_custom_methods_field( $key ) {
    
		$custom_methods = array();
		
		if( ! empty( $_POST[ 'order_shipping_method' ] ) ){
			
			foreach( $_POST[ 'order_shipping_method' ] as $key => $method_key ){
				
				$zip_service_availability = '';
				$temp_zip = woocommerce_clean( isset( $_POST[ 'zip_service_availability' ][ $key ] ) ? $_POST[ 'zip_service_availability' ][ $key ] : '' );
				
				if( ! empty( $temp_zip ) ){
					
					$zip_service_availability = array();
					$temp_zip = explode( "|", $temp_zip );
					
					foreach( $temp_zip as $zip ){
						$zip_service_availability[] = woocommerce_clean( $zip );
					}
					
					$zip_service_availability = implode("|", $zip_service_availability);
					
				}
				
				$custom_methods[ $method_key ] = array(
					'name'							=> woocommerce_clean( $_POST[ 'method_name' ][ $key ] ),
					'price_ajustment'				=> woocommerce_clean( $_POST[ 'method_price_ajustment' ][ $key ] ),
					'regional_service_availability'	=> isset( $_POST[ 'regional_service_availability_' . str_replace( '.', '_', $method_key ) ] ) ? $_POST[ 'regional_service_availability_' . str_replace( '.', '_', $method_key ) ] : '',
					'zip_service_availability'		=> $zip_service_availability,
					'enabled'						=> isset( $_POST[ 'method_enabled_' . str_replace( '.', '_', $method_key ) ] ) ? 1 : 0,
					'comments'						=> isset( $_POST[ 'method_comments' ][ $key ] ) ? $_POST[ 'method_comments' ][ $key ] : ''
				);
				
			}
			
		}

		return $custom_methods;
		
	}
	
	public function service_available( $service, $address ){
		
		global $woocommerce;
		
		if( ! isset( $service[ 'regional_service_availability' ] ) && ! isset( $service[ 'zip_service_availability' ] ) )
			return true;
		
		if( empty( $service[ 'regional_service_availability' ] ) && empty( $service[ 'zip_service_availability' ] ) )
			return true;
		
		if( is_array( $service[ 'regional_service_availability' ] ) && in_array( $address[ 'country' ] . ( $woocommerce->countries->get_states( $address[ 'country' ] ) ? ':' . $address[ 'province' ] : '' ), $service[ 'regional_service_availability' ] ) ){
			return true;
		}
		
		if( ! empty( $service[ 'zip_service_availability' ] ) && preg_match( "/^(" . $service[ 'zip_service_availability' ] . ")/", $address[ 'postalcode' ] ) ){
			return true;
		}
		
		return false;
		
	}

}
	
?>