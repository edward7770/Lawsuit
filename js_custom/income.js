$( document ).ready(function() {
	getCurrency();
	getData();
	$('#catId').select2({
        dropdownParent: $('#addModal')
	});
	$('#subCatId').select2({
		dropdownParent: $('#addModal')
	});
	$('#lawyerId').select2({
		dropdownParent: $('#addModal')
	});
	getSubCategory('0');
	
	$("#subCatId").hide();
	
});	
$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});
var currency="";
function getCurrency()
{
	$.ajax({
		type:"POST",
		url: "get4setCurrency.php",
		data:{ getCurrency:1 },
		async: false, 
		success: function (data){
			if(data=="0")
			showMessage(data);
			else 
			currency=data;
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception);
		}
	});
}

function getData()
{
	var myTable = $('#setData').DataTable();
	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "IncomeData.php",
		data:{ getData:'1' },
		success: function (data) {
			////console.log(data);
			if (!$.trim(data) == '') {
				data = data.replace(/^\s*|\s*$/g, '');
				data = data.replace(/\\r\\n/gm, '');
				var expr = "</tr>\\s*<tr";
				var regEx = new RegExp(expr, "gm");
				var newRows = data.replace(regEx, "</tr><tr");
				$("#setData").DataTable().rows.add($(newRows)).draw();
			}
			getIncome();
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception);
		}
	});
}

$("body").on("click","#addButton",function(){
	$("#form")[0].reset();
	$('#id').val("0");
	$('#addModal').modal('toggle');
	$('#catId').val('').change();
	$('#subCatId').val('').change();
	$('#lawyerId').val('').change();
	$('#receivedBy').val('').change();
	$("#divSubCatId").hide();
});

function add()
{
	var catId=$('#catId').val();
		var subCatId=$('#subCatId').val();
	if(catId==2) subCatId="0";
	///var subCatId=$('#subCatId').val();
	///var lawyerId=$('#lawyerId').val();
	////if(catId==2) { subCatId="0"; lawyerId="0"; }
	var amount=$('#amount').val();
	var tax=$('#taxValue').val();
	var taxAmount=$('#taxValueAmount').val();
	var totAmount=$('#amountWithTax').val();
	var desc=$('#description').val();
	var date=$('#date').val();
	var receivedBy=$('#receivedBy').val();
	var id=$('#id').val();
	////if(!catId || subCatId=='' || lawyerId=='' || !date || !receivedBy || !amount || !totAmount || !tax || !id)
	if(!catId || !date || !receivedBy || !amount || !totAmount || !tax || !id)
	{
		showMessage('Invalid Input');
		return;
	}
	if(id>0)
	var action="edit";
	else 
	var action="add";
	///////subCatId:subCatId,lawyerId:lawyerId
	$.ajax({
		type:"POST",
		url: "IncomeDB.php",
		data: {
			action:action,id:id,catId:catId, subCatId:subCatId,date:date,
			receivedBy:receivedBy,amount:amount,tax:tax, taxAmount:taxAmount,totAmount:totAmount,desc:desc
		},
		success: function (data) {
			console.log(data);
			$('#addModal').modal('toggle');
			showMessage(data);
			getData();
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception)
		}
	}); 
}

function edit(id)
{
	$.ajax({
		type:"POST",
		url: "IncomeDB.php",
		data: {
			action:'getData',id:id
		},
		success: function (data) {
			console.log(data);
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#id').val(this.id);
					$('#date').val(this.incomeDate);
					///$('#subCatId').val(this.subCatId).change();
					///$('#lawyerId').val(this.lawyerId).change();
					$('#receivedBy').val(this.incomeReceivedBy).change();
					$('#amount').val(this.amount);
					$('#description').val(this.description);
					$('#catId').val(this.catId).change();
					$('#taxValue').val(this.taxValue);
					$('#taxValueAmount').val(this.taxValueAmount);
					////window.setTimeout( getSubCategory(this.subCatId), 5000 ); // 5 seconds
					/////window.setTimeout( getLawyer(this.lawyerId), 5000 ); // 5 seconds
					CalculateTax();
					window.setTimeout( getSubCategory(this.subCatId), 5000 ); // 5 seconds
				});
				$('#addModal').modal('toggle');
			}
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception)
		}
	});
}
function delModal(id)
{
	$('#delete_modal').modal('toggle');
	$('#del_button').val(id);
}
function del()
{
	var id=$('#del_button').val();
	$.ajax({
		type:"POST",
		url: "IncomeDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			getData();
			showMessage(data);
			$('#msg_modal').modal('toggle');
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception)
		}
	});
}

/////$("#catId").change(function(event) {console.log(event,event.target,event.currentTarget,event.srcElement)
/*
	$(document).on('change', '#catId', function (e) {
	var catId=$('#catId').val();
	if(catId==2)
	{
	$('#subCatId').prop("required", false);
	$('#lawyerId').prop("required", false);
	////$('#subCatId').find('option').not(':first').remove();
	////$('#lawyerId').find('option').not(':first').remove();
	$('#div_customer').hide();
	$('#div_lawsuitLawyer').hide();
	
	}
	else 
	{
	$('#div_customer').show();
	$('#div_lawsuitLawyer').show();
	$('#subCatId').prop("required", true);
	$('#lawyerId').prop("required", true);
	}
	});
	
	function getSubCategory(subCatId)
	{
	var getSelect=$('#getSelect').val();
	/////var catId=$('#catId').val();
	$.ajax({
	type:"POST",
	url: "dropdown_customer.php",
	data: { getSelect:getSelect },
	success: function (data) {
	////console.log(data);
	$('#subCatId').html(data);
	},
	complete: function (data) {
	if(subCatId>0)
	$('#subCatId').val(subCatId).change();
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
	}
	});
	}
	
	function getLawyer(lawyerId)
	{
	var getSelect=$('#getSelect').val();
	/////var catId=$('#catId').val();
	$.ajax({
	type:"POST",
	url: "dropdown_employee.php",
	data: { getSelect:getSelect },
	success: function (data) {
	////console.log(data);
	$('#lawyerId').html(data);
	},
	complete: function (data) {
	if(lawyerId>0)
	$('#lawyerId').val(lawyerId).change();
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
	}
	});
	}
*/

$('#amount').on('input', function() {
	CalculateTax();
});
$('#taxValue').change(function() {
	CalculateTax();
});

function CalculateTax()
{
	var amount=parseFloat($('#amount').val());
	if(!amount)
	return $('#amountWithTax').val(0);
	var taxValue=parseFloat($('#taxValue').val());
	taxValue=parseFloat(taxValue/100);
	taxValue=(taxValue*amount);  //toFixed(2)
	$('#amountWithTax').val( parseFloat(taxValue+amount).toFixed(3));
	$('#taxValueAmount').val(parseFloat(taxValue).toFixed(3));
}

$(document).on('change', '#catId', function (e) {
    
	var catId=$('#catId').val();
	if(catId==2)
	{
		$('#subCatId').prop("required", false);
		/////$('#subCatId').find('option').not(':first').remove();
		$("#divSubCatId").hide();
	}
	else 
	{
		$('#subCatId').prop("required", true);
		$("#divSubCatId").show();
		////getSubCategory('0');
	}
});

function getIncome()
{
	$.ajax({
		type:"POST",
		url: "IncomeData.php",
		data: { getIncome:1 },
		success: function (data){
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#totalIncome').html(parseFloat(this.totalIncomeAmount).toFixed(3)+" "+currency);
					$('#monthlyIncome').html(parseFloat(this.monthlyIncomeAmount).toFixed(3)+" "+currency);
					$('#todayIncome').html(parseFloat(this.todayIncomeAmount).toFixed(3)+" "+currency);
				});
			}
			else 
			{
				$('#totalIncome').html(parseFloat('0').toFixed(3)+" "+currency);
				$('#monthlyIncome').html(parseFloat('0').toFixed(3)+" "+currency);
				$('#todayIncome').html(parseFloat('0').toFixed(3)+" "+currency);
			}
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception)
		}
	});
}


function errorShow(jqXHR, exception) {
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
}

function getSubCategory(subCatId)
{
	var getSelect=$('#getSelect').val();
	/////var catId=$('#catId').val();
	$.ajax({
		type:"POST",
		url: "dropdown_LSCode.php",
		data: { getSelect:getSelect },
		success: function (data) {
			////console.log(data);
			$('#subCatId').html(data);
		},
		complete: function (data) {
			if(subCatId>0)
			$('#subCatId').val(subCatId).change();
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception)
		}
	});
}