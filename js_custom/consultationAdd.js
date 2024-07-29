var action="add";
var setId=0;
$(document).ready(function() {
	// this changes the scrolling behavior to "smooth"
	window.scrollTo({ top: 0, behavior: 'smooth' });
	var setCustId=$("#setCustId").val(); if(setCustId) $("#customer").val(setCustId).change();
	var setLawyId=$("#setLawyId").val(); if(setLawyId) $("#lawsuitLawyer").val(setLawyId).change();
	
	setId=$("#setId").val(); 
	if(setId)
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


$('#amountContract').on('input', function() {
	CalculateTax();
});
$('#taxValue').change(function() {
	CalculateTax();
});

function CalculateTax()
{
	var amountContract=parseFloat($('#amountContract').val());
	if(!amountContract)
	return $('#ContAmountinclTax').val(0);
	var taxValue=parseFloat($('#taxValue').val());
	taxValue=parseFloat(taxValue/100);
	taxValue=(taxValue*amountContract);  //toFixed(2)
	$('#ContAmountinclTax').val(taxValue+amountContract);
	$('#taxValueAmount').val(taxValue);
	
	
}

function add()
{
	///debugger;
	var custId=$("#customer").val();
	var lawyerId=$("#lawsuitLawyer").val();
	if(!custId)
	{
		showMessage("Please select Customer");
		return;
	} 
	var title = $("#title").val(); 
	if(!title)
	{
		showMessage("Please Enter Title");
		return;
	}
	var date = $("#date").val(); 
	if(!date)
	{
		showMessage("Please Select Contract Date");
		return;
	}
	
	var amount = $("#amountContract").val(); 
	var taxValue = $("#taxValue").val(); 
	var taxPer = $("#ContAmountinclTax").val(); 
	var taxValueAmount = $("#taxValueAmount").val(); 
	var termsEn=$("#ContractTermsEn").summernote('code');
	var termsAr=$("#ContractTermsAr").summernote('code');
	$.ajax({
		type:"POST",
		url: 'consultationAddDB.php',
		data: { custId:custId,lawyerId:lawyerId,title:title,date:date,amount:amount,taxValue:taxValue,taxValueAmount:taxValueAmount,
				taxPer:taxPer,termsEn:termsEn,termsAr:termsAr,action:action,id:setId
			},
		success: function (data) {
			/////console.log(data);
			showMessage(data);
			///if(action=="add")
			////resetForm();
			setTimeout(
			  function() 
			  {
				window.location.replace("consultation.php");
			  }, 1200);
		},
		error: function (jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert("Not connect.\n Verify Network");
				} else if (jqXHR.status == 404) {
				alert("Requested page not found. [404]");
				} else if (jqXHR.status == 500) {
				alert("Internal Server Error [500]");
				} else if (exception === 'parsererror') {
				alert("Requested JSON parse failed.");
				} else if (exception === 'timeout') {
				alert("Time out error.");
				} else if (exception === 'abort') {
				alert("Ajax request aborted");
			}
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		},
	}); 
}

$("body").on("click","#add",function(){
	////document.forms[0].submit();
	$('#form2').submit()
});
function resetForm()
{
	$("#customer").val("");
	$('#customer').select2({ });
	$("#lawsuitLawyer").val("");
	$('#lawsuitLawyer').select2({ });
	
	$('#title').val('');
	$('#date').val('');
	$('#amountContract').val('');
	$('#ContAmountinclTax').val('');
	$('#taxValueAmount').val('');
	$('#taxValue').val('');
	$("#ContractTermsEn").summernote('code', '');
	$("#ContractTermsAr").summernote('code', '');
}

