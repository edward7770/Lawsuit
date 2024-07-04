
$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#add').prop("disabled", true);
})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#add').prop("disabled", false);		
});
var searchId=0;
$(function() {
	searchId=$('#searchId').val();
	getData();
});
function add()
{
	var nameAr=$('#nameAr').val();
	var nameEn=$('#nameEn').val();
	var desc=$('#desc').val();
	var id=$("#id").val();
	var action="Add";
	var fileImage=$('#fileImage')[0].files[0];
	if(id>0)
	{ action="Edit"; fileImage=1; }
	
	if(!nameAr || !nameEn || !fileImage)
	{
		showMessage('please fill all required fields');
		return;
	}
	var docDetails = {
		"action":action,
		"id":id,
		"nameAr":$('#nameAr').val(),
		"nameEn":$('#nameEn').val(),
		"desc":$('#desc').val(),
	};
	var formData = new FormData();
	formData.append('docDetails', JSON.stringify(docDetails));
	formData.append('fileImage', $('#fileImage')[0].files[0]); 
	
	$.ajax({
		type:"POST",
		url: "DocumentDB.php",
		processData: false,
		contentType: false,
		data: formData,
		success: function (data) {
			console.log(data);
			var result = data.replace(/\D/g, '');	
			/////var data=response.replace(/[0-9]/g, '');
			if(result==1)
			{
				/*
				resetForm();
				if(id>0) 
				{
					setTimeout(function(){
						window.close();
					},3000); 
				}
				*/
				showMessage(data);
				setTimeout(
					function() 
					{
					window.location.replace("Document.php");
					}, 500);
			}
		},
		error: function (jqXHR, exception) {
			errorFun(jqXHR, exception);
		}
	}); 
}
function resetForm()
{
	$('#docForm')[0].reset();
}
function getData()
{
	var myTable = $('#setData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "DocumentData.php",
		data: {searchId:searchId},
		success: function (data) {
			if (!$.trim(data) == '') {
                data = data.replace(/^\s*|\s*$/g, '');
                data = data.replace(/\\r\\n/gm, '');
                var expr = "</tr>\\s*<tr";
                var regEx = new RegExp(expr, "gm");
                var newRows = data.replace(regEx, "</tr><tr");
                $("#setData").DataTable().rows.add($(newRows)).draw();
			}
		},
		error: function (jqXHR, exception) {
			errorFun(jqXHR, exception);
		}
	});
}

function edit(id)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "DocumentAdd.php");
	
	// Generate a unique name for the window
	var windowName = "formresult_" + id;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "id");
	hiddenField.setAttribute("value", id);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
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
		url: "DocumentDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			////console.log(data);
			getData();
			$('#delete_modal').modal().hide();
			showMessage(data);
		},
		error: function (jqXHR, exception) {
			errorFun(jqXHR, exception);
		}
	});
}

function errorFun(jqXHR, exception) {
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
