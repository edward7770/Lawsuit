var action="add";
var setId=0;
$(document).ready(function() {
	// this changes the scrolling behavior to "smooth"
	window.scrollTo({ top: 0, behavior: 'smooth' });
	
	setId=$("#setId").val(); 
	if(setId>0)
	{
		$("#setId").val(setId);
		action="edit";
	}
});

$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#add').prop("disabled", true);
})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#add').prop("disabled", false);		
});

function add()
{
	///debugger;
	var termsEn=$("#ContractTermsEn").summernote('code');
	var termsAr=$("#ContractTermsAr").summernote('code');
	setId=$("#setId").val();
	$.ajax({
		type:"POST",
		url: 'ContractTermsDB.php',
		data: { termsEn:termsEn,termsAr:termsAr,action:action,id:setId },
		success: function (data) {
			var result = data.replace(/\D/g, '');
			if(result>0)
			{
				action="edit";
				$("#setId").val(result);
			}
			/////console.log(data);
			showMessage(data);
			///if(action=="add")
			////resetForm();
			/*
			setTimeout(
			  function() 
			  {
				window.location.replace("consultation.php");
			  }, 1200);
			*/
		},
		error: function (jqXHR, exception) {
			if (jqXHR.status === 0) {
				showMessage("Not connect.\n Verify Network");
				} else if (jqXHR.status == 404) {
				showMessage("Requested page not found. [404]");
				} else if (jqXHR.status == 500) {
				showMessage("Internal Server Error [500]");
				} else if (exception === 'parsererror') {
				showMessage("Requested JSON parse failed.");
				} else if (exception === 'timeout') {
				showMessage("Time out error.");
				} else if (exception === 'abort') {
				showMessage("Ajax request aborted");
			}
			$("#ajax_loader").hide();
			$('#add').prop("disabled", false);
		},
	}); 
}

$("body").on("click","#add",function(){
	////document.forms[0].submit();
	$('#form2').submit()
});


