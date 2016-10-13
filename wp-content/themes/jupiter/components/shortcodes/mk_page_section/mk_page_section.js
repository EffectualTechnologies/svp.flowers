// (function($) {
// 	'use strict';

// 	$('.js-blend-mode').each( blend );

// 	function blend() {
// 		var layer = this,
// 			style = layer.currentStyle || window.getComputedStyle(layer, false),
// 			img = style.backgroundImage,
// 			color = style.backgroundColor,
// 			mode = layer.getAttribute('data-blend'),
// 			canvas = layer.getElementsByTagName('canvas')[0],
// 			output = layer.querySelectorAll('.mk-blend-layer')[0],
// 			isLuminosity = (mode === 'luminosity');

// 		img = img.replace('url(', '').replace(')', '').replace(/"/g, '').replace(/'/g, ''); 
// 		color = color.replace('(', '').replace(')', '').replace('rgba', '').replace('rgb', '').split(',');
// 		mode = (mode === 'soft-light') ? 'softLight' : mode;
// 		mode = (mode === 'luminosity') ? 'overlay' : mode;


// 	    MK.core.loadDependencies([ MK.core.path.plugins + 'caman.js' ], function() {
 
// 	    	Caman(canvas, img, function () { 
// 	    		if(isLuminosity) this.greyscale();

// 	    		this.newLayer(function () {
// 			  		this.fillColor( rgb2hex( color ) );
//     				this.opacity( rgba2opacity( color ) * 100 );
// 			  		this.setBlendingMode( mode );
// 			  	});

// 				this.render(function() {
// 					var img = this.toBase64();
// 					output.style.backgroundImage = 'url(' + img + ')';
// 				});
// 			});

// 	    });
// 	}

// 	function rgb2hex(rgb) { // or rgba is also fine
// 	    function hex(x) {
// 	        return ("0" + parseInt(x).toString(16)).slice(-2);
// 	    }
// 	    return "#" + hex(rgb[0]) + hex(rgb[1]) + hex(rgb[2]);
// 	}

// 	function rgba2opacity(rgba) {
// 		var a = rgba[3].replace(' ', '');
// 		return Number(rgba[3]) || 1;
// 	}

// }(jQuery));


(function($) {
	'use strict';

	/* Page Section Intro Effects */
	/* -------------------------------------------------------------------- */

	function mk_section_intro_effects() {
	  if ( !MK.utils.isMobile() ) {
	    if($.exists('.mk-page-section.intro-true')) {

	      $('.mk-page-section.intro-true').each(function() {
	        var that = this;
	        MK.core.loadDependencies([ MK.core.path.plugins + 'jquery.sectiontrans.js', MK.core.path.plugins + 'tweenmax.js' ], function() {
	          var $this = $(that),
	              $pageCnt = $this.nextAll('div'),
	              windowHeight = $(window).height(),
	              effectName = $this.attr('data-intro-effect'),
	              $header = $('.mk-header');

	              var effect = {
	                    fade :    new TimelineLite({paused: true})
	                              .set($pageCnt, { opacity: 0, y: windowHeight * 0.3 })
	                              .to($this, 1, { opacity: 0, ease:Power2.easeInOut })
	                              .to($pageCnt, 1, { opacity: 1, y: 0, ease:Power2.easeInOut}, "-=.7")
	                              .set($this, { zIndex: '-1'}),

	                    zoom_out : new TimelineLite({paused: true})
	                              .set($pageCnt, { opacity: 0, y: windowHeight * 0.3})
	                              .to($this, 1.5, { opacity: .8, scale: 0.8, y: -windowHeight - 100, ease:Strong.easeInOut })
	                              .to($pageCnt, 1.5, { opacity: 1, y:  0, ease:Strong.easeInOut}, "-=1.3"),

	                    shuffle : new TimelineLite({paused: true})
	                              .to($this, 1.5, { y: -windowHeight/2, ease:Strong.easeInOut })
	                              .to($this.nextAll('div').first(), 1.5, { paddingTop: windowHeight/2, ease:Strong.easeInOut }, "-=1.3")
	              }
	      

	          $this.sectiontrans({
	            effect : effectName,
	          });

	          if($this.hasClass('shuffled')) {
	            TweenLite.set($this, { y: -windowHeight/2 });
	            TweenLite.set($this.nextAll('div').first(), { paddingTop: windowHeight/2 });
	          }

	          $('body').on('page_intro', function() {
	            MK.utils.scroll.disable();
	            $(this).data('intro', true);
	            effect[effectName].play();
	            setTimeout(function() {
	              $header.addClass('pre-sticky');
	              $header.addClass('a-sticky');
	              $('.mk-header-padding-wrapper').addClass('enable-padding');
	              $('body').data('intro', false);
	              if(effectName === 'shuffle') $this.addClass('shuffled');
	            }, 1000);

	            setTimeout(MK.utils.scroll.enable, 1500);
	          });

	          $('body').on('page_outro', function() {
	            MK.utils.scroll.disable();
	            $(this).data('intro', true);
	            effect[effectName].reverse();
	            setTimeout(function() {
	              $header.removeClass('pre-sticky');
	              $header.removeClass('a-sticky');
	              $('.mk-header-padding-wrapper').removeClass('enable-padding');
	              $('body').data('intro', false);
	              if($this.hasClass('shuffled')) $this.removeClass('shuffled');
	            }, 1000);
	            
	            setTimeout(MK.utils.scroll.enable, 1500);
	          });
	        });
	      });
	    }
	  } else {
	    $('.mk-page-section.intro-true').each(function() {
	      $(this).attr('data-intro-effect', '');
	    });
	  }
	}

	mk_section_intro_effects();

    var debounceResize = null;
    $(window).on("resize", function() {
        if( debounceResize !== null ) { clearTimeout( debounceResize ); }
        debounceResize = setTimeout( mk_section_intro_effects, 300 );
    });



}(jQuery));