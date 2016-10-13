 

jQuery(document).ready(function() {

	//bind to the event that woo fires when there is an error
	jQuery( document.body ).bind('checkout_error', function(event, myname, myval){
			console.log('fired');
		
		
		//call ajax
		var data = {
			'action': 'ubercx_get_error',
			'whatever': '1234'
		};

		
		jQuery.ajax({
			type:		'POST',
			url:		ajaxurl,
			data:		data,
			dataType:	'JSON',
			success:	function( response ){
				console.log(response);
				
				//make sue we at leas have an original address
				if( typeof response.orig === 'undefined'){
					//no original sent back lets just exit
					return;
				}
				
				//TODO - we need to react to the error code here and put out slightly different error messages
				
				//clear out any previous data in our error area
				jQuery('#ubercx_addr_radio').empty();
				
				
				//lets populate the address info...
				
				//first the original
				//radio button
				addr = ((response.orig.addr1 =="") ? "" : response.orig.addr1 + ", ");
				addr += ((response.orig.addr2 =="") ? "" : response.orig.addr2 + ", ");	
				addr += ((response.orig.city =="") ? "" : response.orig.city + ", ");
				addr += ((response.orig.state =="") ? "" : response.orig.state + ", ");
				addr += ((response.orig.zip =="") ? "" : response.orig.zip);
				
				jQuery('#ubercx_addr_radio').append('<div class="ubercx-addr-radio">');
				jQuery('#ubercx_addr_radio').append('<input type="radio" name="ubercx_which_to_use" id="ubercx_radio_orig" value="orig" checked>');
				jQuery('#ubercx_addr_radio').append('<label for="ubercx_radio_orig"><b> Use Original: </b>' + addr + '</label>');
				jQuery('#ubercx_addr_radio').append('</div>');
				
				//The hidden fields that get posted back to our plugin
				jQuery('#ubercx_addr_radio').append("<div style='display: hidden;'>");
				jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_orig_addr1' id='ubercx_addr_orig_addr1' value='" + response.orig.addr1 + "'>");
				jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_orig_addr2' id='ubercx_addr_orig_addr2' value='" + response.orig.addr2 + "'>");
				jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_orig_city'' id='ubercx_addr_orig_city' value='"  + response.orig.city + "'>");
				jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_orig_state' id='ubercx_addr_orig_state' value='" + response.orig.state + "'>");
				jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_orig_zip' id='ubercx_addr_orig_zip' value='" + response.orig.zip + "'>");
				jQuery('#ubercx_addr_radio').append("</div>");

				
				//do we have any corrected addresses?
				if( typeof(response.corrected) !== 'undefined' && response.corrected.length > 0){
					for (var i = 0; i < response.corrected.length; i++) {
						
						addr = ((response.corrected[i].addr1 =="") ? "" : response.corrected[i].addr1 + ", ");
						addr += ((response.corrected[i].addr2 =="") ? "" : response.corrected[i].addr2 + ", ");	
						addr += ((response.corrected[i].city =="") ? "" : response.corrected[i].city + ", ");
						addr += ((response.corrected[i].state =="") ? "" : response.corrected[i].state + ", ");
						addr += ((response.corrected[i].zip =="") ? "" : response.corrected[i].zip);
						
						jQuery('#ubercx_addr_radio').append('<div class="ubercx-addr-radio">');
						jQuery('#ubercx_addr_radio').append('<input type="radio" name="ubercx_which_to_use" id="ubercx_radio_' + i + '" value="' + i + '">');
						jQuery('#ubercx_addr_radio').append('<label for="ubercx_radio_' + i + '"><b> Suggestion: </b>' + addr + '</label>');
						jQuery('#ubercx_addr_radio').append('</div>');
					

						//The hidden fields that get posted back to our plugin
						jQuery('#ubercx_addr_radio').append("<div style='display: hidden;'>");
						jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_corrected_" + i + "_addr1' id='ubercx_addr_corrected_" + i + "_addr1' value='" + response.corrected[i].addr1 + "'>");
						jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_corrected_" + i + "_addr2' id='ubercx_addr_corrected_" + i + "_addr2' value='" + response.corrected[i].addr2 + "'>");
						jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_corrected_" + i + "_city'' id='ubercx_addr_corrected_" + i + "_city' value='"  + response.corrected[i].city + "'>");
						jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_corrected_" + i + "_state' id='ubercx_addr_corrected_" + i + "_state' value='" + response.corrected[i].state + "'>");
						jQuery('#ubercx_addr_radio').append("<input type='hidden' name='ubercx_addr_corrected_" + i + "_zip' id='ubercx_addr_corrected_" + i + "_zip' value='" + response.corrected[i].zip + "'>");
						jQuery('#ubercx_addr_radio').append("</div>");

					}
				}
				
				//un-hide the display
				jQuery('#ubercx_addr_correction').show();
				
				//capture radio button changes
				jQuery('input[type=radio][name=ubercx_which_to_use]').change(function(){
					ubercx_radio_changed(this);
				});
				
			},
			error:		function( jqXHR, textStatus, errorThrown ){
				console.log('error' + textStatus + errorThrown);
			}
			
			
		});
		
	});

		
		
		
		
		
		
	//Handle the radio button change
	function ubercx_radio_changed(item){
		
		console.log('got a change')

		//TODO we need to work out how to select the correct state here....
		
		
		//lets copy the data into the appropriate fields
		if(item.value=='orig'){
			//go with orig values
			addr1 = jQuery('#ubercx_addr_orig_addr1').val();
			addr2 = jQuery('#ubercx_addr_orig_addr2').val();
			city = jQuery('#ubercx_addr_orig_city').val();
			state = jQuery('#ubercx_addr_orig_state').val();
			zip = jQuery('#ubercx_addr_orig_zip').val();
			
		} else {
			//it is one of the corrected fields
			key = item.value;
			addr1 = jQuery('#ubercx_addr_corrected_' + key + '_addr1').val();
			addr2 = jQuery('#ubercx_addr_corrected_' + key + '_addr2').val();
			city = jQuery('#ubercx_addr_corrected_' + key + '_city').val();
			state = jQuery('#ubercx_addr_corrected_' + key + '_state').val();
			zip = jQuery('#ubercx_addr_corrected_' + key + '_zip').val();
		}

		//OK are we shipping to different addr?
		if(jQuery('input[name=ship_to_different_address]').is(':checked') ){
			//shipping to different addr
			jQuery('#shipping_address_1').val(addr1);
			jQuery('#shipping_address_2').val(addr2);
			jQuery('#shipping_city').val(city);
			jQuery('#shipping_state').val(state);
			jQuery('#shipping_postcode').val(zip);
		} else {
			//shipping to billing
			jQuery('#billing_address_1').val(addr1);
			jQuery('#billing_address_2').val(addr2);
			jQuery('#billing_city').val(city);
			jQuery('#billing_state').val(state);
			jQuery('#billing_postcode').val(zip);

			//always update the ship to in case they select it!
			jQuery('#shipping_address_1').val(addr1);
			jQuery('#shipping_address_2').val(addr2);
			jQuery('#shipping_city').val(city);
			jQuery('#shipping_state').val(state);
			jQuery('#shipping_postcode').val(zip);
				
		}
		

	}

});






