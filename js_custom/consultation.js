$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
	})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});

var searchId=0;
$(function() {
	searchId=$('#searchId').val();
	getData();
});

function getData()
{
	var myTable = $('#setData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "consultationData.php",
		data: {searchId:searchId},
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
		}
	});
}

function showDetailModal(val,row)
{
	$('#modelBody').html($('#note'+row).val());
	$('#msg_detailModal').modal('toggle');
}
function edit(id)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "consultationAdd.php");

	// Generate a unique name for the window
	var windowName = "formresultLS" + id;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "id");
	hiddenField.setAttribute("value", id);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);

	form.submit();
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
		url: "consultationAddDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			getData();
			$('#delete_modal').modal().hide();
			showMessage(data);
		}
	});
}

function payment(id)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "consultationPayment.php");

	// Generate a unique name for the window
	var windowName = "formresultCon" + id;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "id");
	hiddenField.setAttribute("value", id);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);

	form.submit();
}