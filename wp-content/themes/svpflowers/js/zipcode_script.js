// ID of the Google Spreadsheet
var spreadsheetID = "1EsIvqxMKNrU8QNCzVm9GdTkxUQVsCOIIIPJxUsO-qmc";
// Make sure it is public or set to Anyone with link can view 
var url = "https://spreadsheets.google.com/feeds/list/" + spreadsheetID + "/od6/public/values?alt=json";
var ziplist = {};

jQuery.getJSON(url, function(data) {
 var entry = data.feed.entry ;
 jQuery(entry).each(function(){
   ziplist["z" +this.gsx$zip.$t] = true;
 });
});

jQuery(document).ready(function($) {
	$("#zip-lookup").submit(function(e){
		e.preventDefault();
		var zip = $("#zipcode").val(), info = ziplist["z"+zip];
		if(info){
			$(".nozipmatch-content").hide(0);
			$(".zipmatch-content").fadeIn(100, function(){
				$('html, body').animate({
        			scrollTop: $(".zipmatch-content").offset().top
    			}, 500);
			});
			
		}
		else{
			$(".zipmatch-content").hide(0);
			$(".nozipmatch-content").fadeIn(100, function(){
				$('html, body').animate({
        			scrollTop: $(".nozipmatch-content").offset().top
    			}, 500);
			});			
		}
	});
});