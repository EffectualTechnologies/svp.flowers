jQuery(document).ready( function($) {
	
	//sortable table
	jQuery('table.woo-slg-sortable tbody').sortable({
		items:'tr',
		cursor:'move',
		axis:'y',
		handle: 'td',
		scrollSensitivity:40,
		helper:function(e,ui){
			ui.children().each(function(){
				jQuery(this).width(jQuery(this).width());
			});
			ui.css('left', '0');
			return ui;
		},
		start:function(event,ui){
			ui.item.css('background-color','#f6f6f6');
		},
		stop:function(event,ui){
			ui.item.removeAttr('style');
		}
	});
	
	//Media Uploader
	$( document ).on( 'click', '.woo-slg-upload-file-button', function() {	
	
		var imgfield,showfield;
		imgfield = jQuery(this).prev('input').attr('id');
		showfield = jQuery(this).parents('td').find('.woo-vou-img-view');    			
		
		if(typeof wp == "undefined" || WooVouAdminSettings.new_media_ui != '1' ){// check for media uploader						
				
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	    	
			window.original_send_to_editor = window.send_to_editor;
			window.send_to_editor = function(html) {
				
				if(imgfield)  {
					
					var mediaurl = $('img',html).attr('src');
					$('#'+imgfield).val(mediaurl);
					showfield.html('<img src="'+mediaurl+'" />');
					tb_remove();
					imgfield = '';
					
				} else {
					
					window.original_send_to_editor(html);
					
				}
			};
	    	return false;
			  
		} else {
									
			var file_frame;
			//window.formfield = '';
			
			//new media uploader
			var button = jQuery(this);
	
			//window.formfield = jQuery(this).closest('.file-input-advanced');
		
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
				file_frame.open();
			  return;
			}
						
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				//title: button.data( 'uploader_title' ),
				/*button: {
					text: button.data( 'uploader_button_text' ),
				},*/
				multiple: false  // Set to true to allow multiple files to be selected
			});
	
			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};
	
		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');
	
		        // Initialize the views in our view object.
		        view.set(views);
		    });
	
			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {
	
				// Get selected size from media uploader
				var selected_size = $('.attachment-display-settings .size').val();
				
				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					
					// Selected attachment url from media uploader
					var attachment_url = attachment.sizes[selected_size].url;
					
					if(index == 0){
						// place first attachment in field
						$('#'+imgfield).val(attachment_url);
						showfield.html('<img src="'+attachment_url+'" />');
						
					} else{
						$('#'+imgfield).val(attachment_url);
						showfield.html('<img src="'+attachment_url+'" />');
					}
				});
			});
	
			// Finally, open the modal
			file_frame.open();			
		}
		
	});
	
	// Hide and show settings fields on change selections
	$(document).on( 'change', '.woo_slg_social_btn_change', function() {
		
		var this_obj = $( this );
		
		if( this_obj.val() == 0 ) {
			
			$('.woo_slg_social_btn_image').parents('tr').show();
			$('.woo_slg_social_btn_text').parents('tr').hide();
		}
		
		if( this_obj.val() == 1 ) {
			
			$('.woo_slg_social_btn_text').parents('tr').show();
			$('.woo_slg_social_btn_image').parents('tr').hide();
		}
	});
	
	// Check if available settings available hide and show related settings
	if( $( '.woo_slg_social_btn_change' ).length > 0 ) {
		$( '.woo_slg_social_btn_change:checked' ).change();
	}
	
}); // End of document ready function