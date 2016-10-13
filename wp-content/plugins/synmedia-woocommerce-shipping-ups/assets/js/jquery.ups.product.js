jQuery(function($){
	
	$(".add_ups_restriction").on("click", function(){
		
		var ind = $("#ups_restrictions .ups_restriction").length;
		var templ = $("#ups_restriction_template").html();
		var find = '#k#';
		var re = new RegExp(find, 'g');
		templ = templ.replace(re, ind);
		$("#ups_restrictions").append(templ);
		$("#ups_restrictions .method_restriction_select:last, #ups_restrictions .country_restriction_select:last").select2();
		
	});
	
});