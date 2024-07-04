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
        ///console.log(data);
        // Append rows to the DataTable
        $('#setData').html(data); // Assuming data contains correct HTML rows with rowspan
    }
});

}
