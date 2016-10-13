jQuery(document).ready( function($) {	
	
	// login with facebook
	$( document ).on( 'click', 'a.woo-slg-social-login-facebook', function() {
		
		var object = $(this);
		var errorel = $(this).parents('.woo-slg-social-container').find('.woo-slg-login-error');
		
		errorel.hide();
		errorel.html('');
		
		if( WOOSlg.fberror == '1' ) {
			errorel.show();
			errorel.html( WOOSlg.fberrormsg );
			return false;
		} else {
			
			FB.login(function(response) {
				//alert(response.status);
			  if (response.status === 'connected') {
			  	//creat user to site
			  	woo_slg_social_connect( 'facebook', object );
			  }
			}, {scope:'email'});
		}
	});
	
	// login with google+
	$( document ).on( 'click', 'a.woo-slg-social-login-googleplus', function() {
		
		var object = $(this);
		var errorel = $(this).parents('.woo-slg-social-container').find('.woo-slg-login-error');
		
		errorel.hide();
		errorel.html('');
		
		if( WOOSlg.gperror == '1' ) {
			errorel.show();
			errorel.html( WOOSlg.gperrormsg );
			return false;
		} else {
			
			var googleurl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-gp-redirect-url').val();
			
			if(googleurl == '') {
				alert( WOOSlg.urlerror );
				return false;
			}
			
			var googleLogin = window.open(googleurl, "google_login", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var gTimer = setInterval(function () { //set interval for executing the code to popup
				try {
					if (googleLogin.location.hostname == window.location.hostname) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(gTimer);
						googleLogin.close();
						woo_slg_social_connect( 'googleplus', object );
					}
				} catch (e) {}
			}, 500);
		}
	});
	
	// login with linkedin
	$( document ).on( 'click', 'a.woo-slg-social-login-linkedin', function(){
		
		var object = $(this);
		var errorel = $(this).parents('.woo-slg-social-container').find('.woo-slg-login-error');
		
		errorel.hide();
		errorel.html('');
		
		if( WOOSlg.lierror == '1' ) {
			errorel.show();
			errorel.html( WOOSlg.lierrormsg );
			return false;
		} else {
			
			var linkedinurl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-li-redirect-url').val();
			
			if(linkedinurl == '') {
				alert( WOOSlg.urlerror );
				return false;
			}
			
			var linkedinLogin = window.open(linkedinurl, "linkedin", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var lTimer = setInterval(function () { //set interval for executing the code to popup
				try {
					if (linkedinLogin.location.hostname == window.location.hostname) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(lTimer);
						linkedinLogin.close();
						woo_slg_social_connect( 'linkedin', object );
					}
				} catch (e) {}
			}, 300);
		}
	});
	
	// login with twitter
	$( document ).on( 'click', 'a.woo-slg-social-login-twitter', function() {
		
		var object = $(this);
		var errorel = $(this).parents('.woo-slg-social-container').find('.woo-slg-login-error');
		//var redirect_url = $(this).parents('.woo-slg-social-container').find('.woo-slg-redirect-url').val();
		var parents = $(this).parents( 'div.woo-slg-social-container' );
		var appendurl = '';
		
		//check button is clicked form widget
		if( parents.hasClass('woo-slg-widget-content') ) {
			appendurl = '&container=widget';
		}
		
		errorel.hide();
		errorel.html('');
		
		if( WOOSlg.twerror == '1' ) {
			errorel.show();
			errorel.html( WOOSlg.twerrormsg );
			return false;
		} else {
			
			var twitterurl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-tw-redirect-url').val();
			if( twitterurl == '' ) {
				alert( WOOSlg.urlerror );
				return false;
			}
			
			var twLogin = window.open(twitterurl, "twitter_login", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var tTimer = setInterval(function () { //set interval for executing the code to popup
				try {
					if ( twLogin.location.hostname == window.location.hostname ) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(tTimer);
						twLogin.close();
						if( WOOSlg.userid != '' ) {
							woo_slg_social_connect( 'twitter', object );
						} else {
							window.parent.location = WOOSlg.socialloginredirect+appendurl;
						}
					}
				} catch (e) {}
			}, 300);
		}
	});
	
	// login with yahoo
	$( document ).on( 'click', 'a.woo-slg-social-login-yahoo', function() {
		
		var object = $(this);
		var errorel = $(this).parents('.woo-slg-social-container').find('.woo-slg-login-error');
		
		errorel.hide();
		errorel.html('');
		
		if( WOOSlg.yherror == '1' ) {
			errorel.show();
			errorel.html( WOOSlg.yherrormsg );
			return false;
		} else {
			
			var yahoourl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-yh-redirect-url').val();
			
			if(yahoourl == '') {
				alert( WOOSlg.urlerror );
				return false;
			}
			var yhLogin = window.open(yahoourl, "yahoo_login", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var yTimer = setInterval(function () { //set interval for executing the code to popup
				try {
					if (yhLogin.location.hostname == window.location.hostname) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(yTimer);
						yhLogin.close();
						woo_slg_social_connect( 'yahoo', object );
					}
				} catch (e) {}
			}, 300);
		}
	});
	
	// login with foursquare
	$( document ).on( 'click', 'a.woo-slg-social-login-foursquare', function() {
		
		var object = $(this);
		var errorel = $(this).parents('.woo-slg-social-container').find('.woo-slg-login-error');
		
		errorel.hide();
		errorel.html('');
		
		if( WOOSlg.fserror == '1' ) {
			errorel.show();
			errorel.html( WOOSlg.fserrormsg );
			return false;
		} else {
			
			var foursquareurl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-fs-redirect-url').val();
			
			if(foursquareurl == '') {
				alert( WOOSlg.urlerror );
				return false;
			}
			var fsLogin = window.open(foursquareurl, "foursquare_login", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var fsTimer = setInterval(function () { //set interval for executing the code to popup
				try {
					if (fsLogin.location.hostname == window.location.hostname) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(fsTimer);
						fsLogin.close();
						woo_slg_social_connect( 'foursquare', object );
					}
				} catch (e) {}
			}, 300);
		}
	});
	
	// login with windows live
	$( document ).on( 'click', 'a.woo-slg-social-login-windowslive', function() {
		
		var object = $(this);
		var errorel = $(this).parents('.woo-slg-social-container').find('.woo-slg-login-error');
		
		errorel.hide();
		errorel.html('');
		
		if( WOOSlg.wlerror == '1' ) {
			errorel.show();
			errorel.html( WOOSlg.wlerrormsg );
			return false;
		} else {
			
			var windowsliveurl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-wl-redirect-url').val();
			
			if(windowsliveurl == '') {
				alert( WOOSlg.urlerror );
				return false;
			}
			var wlLogin = window.open(windowsliveurl, "windowslive_login", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var wlTimer = setInterval(function () { //set interval for executing the code to popup
				try {
					if (wlLogin.location.hostname == window.location.hostname) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(wlTimer);
						wlLogin.close();
						woo_slg_social_connect( 'windowslive', object );
					}
				} catch (e) {}
			}, 300);
		}
	});
	
	// login with VK.com
	$( document ).on( 'click', 'a.woo-slg-social-login-vk', function(){
		
		var object = $(this);
		var errorel = $(this).parents('.woo-slg-social-container').find('.woo-slg-login-error');
		
		errorel.hide();
		errorel.html('');
		
		if( WOOSlg.vkerror == '1' ) {
			errorel.show();
			errorel.html( WOOSlg.vkerrormsg );
			return false;
		} else {
			
			var vkurl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-vk-redirect-url').val();
			
			if(vkurl == '') {
				alert( WOOSlg.urlerror );
				return false;
			}
			
			var vkLogin = window.open(vkurl, "vk_login", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var vkTimer = setInterval(function () { //set interval for executing the code to popup
				try {
					if (vkLogin.location.hostname == window.location.hostname) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(vkTimer);
						vkLogin.close();
						woo_slg_social_connect( 'vk', object );
					}
				} catch (e) {}
			}, 300);
		}
	});
	
	// login with Instagram
	$( document ).on( 'click', 'a.woo-slg-social-login-instagram', function(){
		
		var object = $(this);
		var errorel = $(this).parents('.woo-slg-social-container').find('.woo-slg-login-error');
		
		errorel.hide();
		errorel.html('');
		
		if( WOOSlg.insterror == '1' ) {
			errorel.show();
			errorel.html( WOOSlg.insterrormsg );
			return false;
		} else {
			
			var instagramurl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-inst-redirect-url').val();
			
			if(instagramurl == '') {
				alert( WOOSlg.urlerror );
				return false;
			}
			
			var instLogin = window.open(instagramurl, "instagram_login", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var instTimer = setInterval(function () { //set interval for executing the code to popup
				try {
					if (instLogin.location.hostname == window.location.hostname) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(instTimer);
						instLogin.close();
						woo_slg_social_connect( 'instagram', object );
					}
				} catch (e) {}
			}, 300);
		}
	});
	
	// Social login toggle on checkout, shortcode page
	//$('.woo-slg-social-container-checkout').hide();
	$(document).on('click', '.woo-slg-show-social-login', function() {
		$('.woo-slg-social-container-checkout').slideToggle();
	});
	
	// Social login toggle on widget area
	$(document).on('click', '.woo-slg-show-social-login-widget', function() {
		$('.woo-slg-social-container-widget').slideToggle();
	});
	
	// Social login toggle on widget area
	$(document).on('click', '.woo-slg-login-page .woo-slg-show-social-login', function() {
		$("html, body").animate({ scrollTop: $(document).height() }, "slow");
	});
	
	//My Account Show Link Buttons "woo-slg-show-link"
	$( document ).on( 'click', '.woo-slg-show-link', function() {
		$( '.woo-slg-show-link' ).hide();
		$( '.woo-slg-profile-link-container' ).show();
	});
	
	// login with paypal
	$( document ).on( 'click', 'a.woo-slg-social-login-paypal', function() {
		
		var object	= $( this );
		var errorel	= $( this ).parents( '.woo-slg-social-container' ).find( '.woo-slg-login-error' );
		
		errorel.hide();
		errorel.html( '' );
		
		if( WOOSlg.paypalerror == '1' ) {
			
			errorel.show();
			errorel.html( WOOSlg.paypalerrormsg );
			return false;
			
		} else {		
			
			var paypalurl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-paypal-redirect-url').val();			
			if(paypalurl == '') {
				alert( WOOSlg.urlerror );
				return false;
			}
			var paypalLogin = window.open( paypalurl, "paypal_login", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var paypalTimer = setInterval(function () { //set interval for executing the code to popup
				try { 
					if (paypalLogin.location.hostname == window.location.hostname) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(paypalTimer);
						paypalLogin.close();
						woo_slg_social_connect( 'paypal', object );
					}
				} catch (e) {}
			}, 300);			
		}
	});
	
	
	// login with amazon
	$( document ).on( 'click', 'a.woo-slg-social-login-amazon', function() {
		
		var object	= $( this );
		var errorel	= $( this ).parents( '.woo-slg-social-container' ).find( '.woo-slg-login-error' );
		
		errorel.hide();
		errorel.html( '' );
		
		if( WOOSlg.amazonerror == '1' ) {
			
			errorel.show();
			errorel.html( WOOSlg.amazonerrormsg );
			return false;
			
		} else {		
			
			var amazonurl = $(this).closest('.woo-slg-social-container').find('.woo-slg-social-amazon-redirect-url').val();			
			if(amazonurl == '') {
				alert( WOOSlg.urlerror );
				return false;
			}
			var amazonLogin = window.open(amazonurl, "amazon_login", "scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,height=400,width=600");
			var amazonTimer = setInterval(function () { //set interval for executing the code to popup
				try { 
					if (amazonLogin.location.hostname == window.location.hostname) { //if login domain host name and window location hostname is equal then it will go ahead
						clearInterval(amazonTimer);
						amazonLogin.close();
						woo_slg_social_connect( 'amazon', object );
					}
				} catch (e) {}
			}, 300);			
		}
	});
	
});

// Social Connect Process
function woo_slg_social_connect( type, object ) {
	
	var data = { 
					action	:	'woo_slg_social_login',
					type	:	type
				};
	
	//show loader
	jQuery('.woo-slg-login-loader').show();
	jQuery('.woo-slg-social-wrap').hide();
	
	jQuery.post( WOOSlg.ajaxurl,data,function(response) {		
		
		// hide loader
		jQuery('.woo-slg-login-loader').hide();
		jQuery('.woo-slg-social-wrap').show();
		
		var redirect_url = object.parents('.woo-slg-social-container').find('.woo-slg-redirect-url').val();
		if( response != '' ) {
			
			var result = jQuery.parseJSON( response );
			
			if( redirect_url != '' ) {
				
				window.location = redirect_url;
				
			} else {
				
				//if user created successfully then reload the page
				window.location.reload();
			}
		}
	});
}

