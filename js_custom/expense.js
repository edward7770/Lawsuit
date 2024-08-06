	$( document ).ready(function() {
		getCurrency();
		getData();
		$('#catId').select2({
        dropdownParent: $('#addModal')
		});
		$('#subCatId').select2({
			dropdownParent: $('#addModal')
		});
		$('#mode').select2({
			dropdownParent: $('#addModal')
		});
		getSubCategory('0');
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
			url: "ExpenseData.php",
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
				getExpense();
			}
		});
	}
	
	$("body").on("click","#addButton",function(){
		$("#form")[0].reset();
		$('#id').val("0");
		$('#addModal').modal('toggle');
		$('#catId').val('').change();
		$('#subCatId').val('').change();
		$('#mode').val('').change();
	});
	
	function add()
	{
		var catId=$('#catId').val();
		var subCatId=$('#subCatId').val();
		if(catId==2) subCatId="0";
		var supl=$('#supplier').val();
		var amount=$('#amount').val();
		var tax=$('#taxValue').val();
		var taxAmount=$('#taxValueAmount').val();
		var totAmount=$('#amountWithTax').val();
		var remarks=$('#remarks').val();
		var invoiceNumber=$('#invoiceNumber').val();
		var date=$('#date').val();
		var mode=$('#mode').val();
		
		var id=$('#id').val();
		if(!catId || subCatId=='' || !supl || !date || !mode || !amount || !totAmount || !tax || !id)
		{
			showMessage('Invalid Input');
			return;
		}
		if(id>0)
		var action="edit";
		else 
		var action="add";
		
		$.ajax({
			type:"POST",
			url: "ExpenseDB.php",
			data: {
				action:action,id:id,catId:catId,subCatId:subCatId,date:date,mode:mode,
				amount:amount,remarks:remarks,supl:supl,tax:tax,taxAmount:taxAmount,totAmount:totAmount,invoiceNumber:invoiceNumber
			},
			success: function (data) {
				console.log(data);
				$('#addModal').modal('toggle');
				showMessage(data);
				getData();
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
	
	function edit(id)
	{
		$.ajax({
			type:"POST",
			url: "ExpenseDB.php",
			data: {
				action:'getData',id:id
			},
			success: function (data) {
				////console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#id').val(this.id);
						$('#date').val(this.expenseDate);
						$('#mode').val(this.expenseMode).change();
						$('#amount').val(this.amount);
						$('#remarks').val(this.remarks);
						$('#catId').val(this.catId).change();
						window.setTimeout( $('#supplier').val(this.supplier), 5000 ); // 5 seconds
						$('#taxValue').val(this.taxValue);
						$('#taxValueAmount').val(this.taxValueAmount);
						$('#invoiceNumber').val(this.invoiceNumber);
						
						CalculateTax();
						window.setTimeout( getSubCategory(this.subCatId), 5000 ); // 5 seconds
					});
					$('#addModal').modal('toggle');
				}
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
			url: "	ExpenseDB.php",
			data: {action:'del',id:id},
			success: function (data) {
				getData();
				showMessage(data);
				$('#msg_modal').modal('toggle');
			}
		});
	}
	
/////$("#catId").change(function(event) {console.log(event,event.target,event.currentTarget,event.srcElement)

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
function getExpense()
{
	$.ajax({
		type:"POST",
		url: "ExpenseData.php",
		data: { getExpense:1 },
		success: function (data){
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#totalExpense').html(parseFloat(this.totalExpense).toFixed(3)+" "+currency);
					$('#monthlyExpense').html(parseFloat(this.monthlyExpense).toFixed(3)+" "+currency);
					$('#todayExpense').html(parseFloat(this.todayExpense).toFixed(3)+" "+currency);
				});
			}
			else 
			{
				$('#totalExpense').html('0');
				$('#monthlyExpense').html('0');
				$('#todayExpense').html('0');
			}
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

function printExpenseReceipt(expenseId) {
	$.ajax({
	  type: "POST",
	  url: "ExpenseReceiptPrint.php",
	  data: {
		expenseId: expenseId
	  },
	  success: function (data) {
		// Open a new window for printing
		var printWindow = window.open("", "_blank");
		printWindow.document.write(
		  "<html><head><title>&nbsp;</title></head><body>"
		);
  
		// Write the received content to the print window
		printWindow.document.write(data);
  
		printWindow.document.write("</body></html>");
		printWindow.document.close();
  
		// Print the content after a short delay
		setTimeout(function () {
		  printWindow.print();
		  printWindow.close();
		}, 1000);
	  },
	});
  }