$( document ).ready(function() {
    ////getData();
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
}

function getData()
{
  	var type=$('#lawsuitsType').val();
	var state=$('#state').val();
	var stage=$('#stage').val();
	var reportType=$('#reportType').val();
	if(reportType<=0)
	{
		showMessage('Please select Report Type');
		return;
	}
	/*
	var myTable = $('#example').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "LawsuitReportData.php",
		data: { type:type, state:state, stage:stage, reportType:reportType },
		success: function (data) {
			if(data=='reportType')
				return;
			console.log(data);
			////$('#setData').html(data);
			if (!$.trim(data) == '') {
			    data = data.replace(/^\s*|\s*$/g, '');
                data = data.replace(/\\r\\n/gm, '');
                var expr = "</tr>\\s*<tr";
                var regEx = new RegExp(expr, "gm");
                var newRows = data.replace(regEx, "</tr><tr");
                $("#example").DataTable().rows.add($(newRows)).draw();
             }
		}
	});
	*/

	$.ajax({
		type: "POST",
		url: "LawsuitReportData.php",
		data: { type: type, state: state, stage: stage, reportType: reportType },
		success: function(data) {
			console.log(data);
			///console.log(data);
			// Append rows to the DataTable
			$('#setData').html(data); // Assuming data contains correct HTML rows with rowspan
		}
	});

}


function printLawsuitReport() {
	var type=$('#lawsuitsType').val();
	var state=$('#state').val();
	var stage=$('#stage').val();
	var reportType=$('#reportType').val();

	$.ajax({
        type: "POST",
        url: "LawsuitReportPrint.php",
        data: { type: type, state: state, stage: stage, reportType: reportType },
        success: function(data) {
            // Open a new window for printing
            var printWindow = window.open("", "_blank");
            printWindow.document.write("<html><head><title>&nbsp;</title>");

			printWindow.document.write(`
				<style type="text/css" media="print">
					body {
						-webkit-print-orientation: landscape;
						orientation: landscape;
					  }
					@media print {
						.reference_no_record {
							width: 200px !important;
						}
						body {
							width: 100%;
							-webkit-print-color-adjust: exact;
						}

					}
					
					.reference_no_record {
						width: 200px !important;
					}
				</style>
			`);
			
			printWindow.document.write("</head><body>");

            // Write the received content to the print window
            printWindow.document.write(data);

            printWindow.document.write("</body></html>");
            printWindow.document.close();

            // Print the content after a short delay
            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 1000); // Adjust the delay as needed
        }
    });
}

function printExcelLawsuitReport() {
	var type=$('#lawsuitsType').val();
	var state=$('#state').val();
	var stage=$('#stage').val();
	var reportType=$('#reportType').val();

	$.ajax({
        type: "POST",
        url: "LawsuitExcelPrint.php",
        data: { type: type, state: state, stage: stage, reportType: reportType },
        success: function(data) {
            console.log('123123123');
        }
    });
}