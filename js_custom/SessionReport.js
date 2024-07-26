
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

function search()
{
    getData();
}

function getData()
{
	var myTable = $('#example').DataTable();
 	var rows = myTable.rows().remove().draw();

	var from=$('#from_date').val();
	var to=$('#to_date').val();

	$.ajax({
		type:"POST",
		url: "SessionReportData.php",
		data:{ from :from, to: to },
		success: function (data) {
			$('#setData_session').html(data);
			$('div.dataTables_filter').css('float', 'right');
			// $('div.dataTables_filter').css('position', 'absolute');
			// $('div.dataTables_filter').css('right', '0px');
			var csvButton = '<a href="SessionExcelReport.php?from=' + from +'&to=' + to + '" class="table-btn-action-icon""><span><i class="fa fa-file-csv"></i></span></a>';
			var printButton = '<a href="#" class="table-btn-action-icon" onclick="printInvoice();"><span><i class="fa fa-print"></i></span></a>';
			$(printButton).insertAfter("#example_filter")
			$(csvButton).insertAfter("#example_filter")
			
			getPayment();
			
		},
		error: function (jqXHR, exception) {
			errorShow(jqXHR, exception);
		}
	});
}


function printInvoice() {
    // Make AJAX call to get invoice content
	var from=$('#from_date').val();
	var to=$('#to_date').val();

    $.ajax({
        type: "POST",
        url: "SessionReportPrint.php",
        data: { from: from, to: to },
        success: function(data) {
			var printWindow = window.open("", "_blank");
            printWindow.document.write("<html><head><title>&nbsp;</title>");

			printWindow.document.write(`
				<style type="text/css" media="print">

				</style>
			`);
			
			printWindow.document.write("</head><body>");

            // Write the received content to the print window
            printWindow.document.write(data);

            printWindow.document.write("</body></html>");
            printWindow.document.close();

            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 1000); // Adjust the delay as needed
        }
    });
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