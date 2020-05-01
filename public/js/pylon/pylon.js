$(function() {
	$( "#pylonCalendar tr" ).click(function(){
		if(!$(this).hasClass("trSelected")){
			$("#pylonCalendar tr:odd").hide();
			$(".trSelected").removeClass("trSelected");
			$(this).addClass("trSelected");
			$(this).next('tr:hidden').show();
		}
	});

});

