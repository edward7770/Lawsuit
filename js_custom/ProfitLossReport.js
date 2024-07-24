
$( document ).ready(function() {
	getCurrency();
    // getData();
    // getExpenseData();
    // getIncomeData();
});
$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);
});

function search()
{
    getData();
    getExpenseData();
    getIncomeData();
}

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
	var myTable = $('#example').DataTable();
 	var rows = myTable.rows().remove().draw();

	var from=$('#from_date').val();
	var to=$('#to_date').val();
	var payment_status=$('#payment_status_select').val();

	$.ajax({
		type:"POST",
		url: "PaymentData.php",
		data:{ from :from, to: to, payment_status: payment_status },
		success: function (data) {
			$('#setData_payment').html(data);
			$('div.dataTables_filter').css('position', 'absolute');
			$('div.dataTables_filter').css('right', '0px');
			var csvButton = '<a href="ProfitLossExcelReport.php?from=' + from +'&to=' + to +'&payment_status=' + payment_status + '" class="table-btn-action-icon""><span><i class="fa fa-file-csv"></i></span></a>';
			var printButton = '<a href="#" class="table-btn-action-icon" onclick="printInvoice('+ $("#lsMId").val() + ',' + $("#lsDId").val() +');"><span><i class="fa fa-print"></i></span></a>';
			$(printButton).insertAfter("#example_filter")
			$(csvButton).insertAfter("#example_filter")
			
			getPayment();
			
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception);
		}
	});
}

function getExpenseData()
{
    // var myTable = $('#setExpenseData').DataTable();
    // var rows = myTable.rows().remove().draw();
	var from=$('#from_date').val();
	var to=$('#to_date').val();
    $.ajax({
        type:"POST",
        url: "ExpenseData.php",
        data:{ from :from, to: to },
        success: function (data) {
            // if (!$.trim(data) == '') {
            //     data = data.replace(/^\s*|\s*$/g, '');
            //     data = data.replace(/\\r\\n/gm, '');
            //     var expr = "</tr>\\s*<tr";
            //     var regEx = new RegExp(expr, "gm");
            //     var newRows = data.replace(regEx, "</tr><tr");
            //     $("#setExpenseData").DataTable().rows.add($(newRows)).draw();
            // }
			$('#setData_expense').html(data);
        }
    });
}

function getIncomeData()
{
	// var myTable = $('#setIncomeData').DataTable();
	// var rows = myTable.rows().remove().draw();
	var from=$('#from_date').val();
	var to=$('#to_date').val();
	$.ajax({
		type:"POST",
		url: "IncomeData.php",
		data:{ from :from, to: to },
		success: function (data) {
			// if (!$.trim(data) == '') {
			// 	data = data.replace(/^\s*|\s*$/g, '');
			// 	data = data.replace(/\\r\\n/gm, '');
			// 	var expr = "</tr>\\s*<tr";
			// 	var regEx = new RegExp(expr, "gm");
			// 	var newRows = data.replace(regEx, "</tr><tr");
			// 	$("#setIncomeData").DataTable().rows.add($(newRows)).draw();
			// }
			$('#setData_income').html(data);
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception);
		}
	});
}



function viewLSDetails(lsMId,lsDId)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitDetail.php");
	
	// Generate a unique name for the window
	var windowName = "formresult_" + lsMId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsMId");
	hiddenField.setAttribute("value", lsMId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsDId");
	hiddenField.setAttribute("value", lsDId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);
	
	form.submit();
}
function viewLSDetailsPayment(lsMId,lsDId)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitDetailPayment.php");
	
	// Generate a unique name for the window
	var windowName = "formresult_" + lsMId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsMId");
	hiddenField.setAttribute("value", lsMId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsDId");
	hiddenField.setAttribute("value", lsDId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);
	
	form.submit();
}

function newStage(lsMId,lsCode)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitAdd.php");
	
	// Generate a unique name for the window
	var windowName = "formresult_" + lsMId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsMId");
	hiddenField.setAttribute("value", lsMId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsCode");
	hiddenField.setAttribute("value", lsCode);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);
	
	form.submit();
}

function viewDetails(mId)
{
	var myTable = $('#LawsuitMasterDetailModalData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "LawsuitPaymentMasterModalData.php",
		data: { mId:mId },
		
		success: function (data) {
			console.log(data);
			////return;
			if (!$.trim(data) == '') {
                data = data.replace(/^\s*|\s*$/g, '');
                data = data.replace(/\\r\\n/gm, '');
                var expr = "</tr>\\s*<tr";
                var regEx = new RegExp(expr, "gm");
                var newRows = data.replace(regEx, "</tr><tr");
                $("#LawsuitMasterDetailModalData").DataTable().rows.add($(newRows)).draw();
			}
			////$('#add_customer').modal('toggle');
			$('#LawsuitMasterDetailModal').modal('toggle');
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception);
		}
	});
}



function viewLSEdit(lsMId,lsDId)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitEdit.php");
	
	// Generate a unique name for the window
	var windowName = "formresultLS" + lsDId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsMId");
	hiddenField.setAttribute("value", lsMId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsDId");
	hiddenField.setAttribute("value", lsDId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);
	
	form.submit();
}
var o="rtl"===$("html").attr("data-textdirection");

function viewLSDetailsExpense(lsDId)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitDetailExpense.php");
	
	// Generate a unique name for the window
	var windowName = "formresult_" + lsDId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsDId");
	hiddenField.setAttribute("value", lsDId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);
	
	form.submit();
}


function getPayment()
{
	$.ajax({
		type:"POST",
		url: "PaymentData.php",
		data: { getPayment:1 },
		success: function (data){
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				// data_array = jsonObject['data'];
				// console.log(data_array);
				// jQuery.each(data_array, function() {
				// $('#totalAmount').html(parseFloat(this.totalCasesAmount).toFixed(3)+" "+currency);
				// $('#paidAmount').html(parseFloat(this.totalPayment).toFixed(3)+" "+currency);
				// $('#dueAmount').html(parseFloat(this.outstandingAmount).toFixed(3)+" "+currency);
				// ///$('#totalAmountMonthly').html(parseFloat(this.monthlyCasesAmount).toFixed(3)+" "+currency);
				// ///$('#totalAmountToday').html(parseFloat(this.todayCasesAmount).toFixed(3)+" "+currency);
				// ///$('#dueAmountMonthly').html(parseFloat(this.monthlyPayment).toFixed(3)+" "+currency);
				// ///$('#dueAmountToday').html(parseFloat(this.dailyPayment).toFixed(3)+" "+currency);
				// });
				$('#totalAmount').html((jsonObject.totalCasesAmount).toFixed(3)+" "+currency);
				$('#paidAmount').html((jsonObject.totalPayment).toFixed(3)+" "+currency);
				$('#dueAmount').html((jsonObject.outstandingAmount).toFixed(3)+" "+currency);
			}
			else 
			{
				$('#totalIncome').html('0');
				$('#monthlyIncome').html('0');
				$('#todayIncome').html('0');
			}
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception);
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