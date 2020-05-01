//http://www.mkyong.com/jquery/add-maxlength-on-textarea-using-jquery/
$(document).ready( function () {

	maxLength = $("textarea#motivation").attr("data_maxlength");
	  $("textarea#motivation").bind("keyup change", function(){checkMaxLength(this.id,  maxLength); } )

	});

	function checkMaxLength(textareaID, maxLength){

	  currentLengthInTextarea = $("#"+textareaID).val().length;
	  $(remainingLengthTempId).text(parseInt(maxLength) - parseInt(currentLengthInTextarea));

	if (currentLengthInTextarea > (maxLength)) { 

		// Trim the field current length over the maxlength.
		$("textarea#motivation").val($("textarea#motivation").val().slice(0, maxLength));
		$(remainingLengthTempId).text(0);

	}
}
