
$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});

function addLocation()
{
	var nameEn=$('#nameEn').val();
	var nameAr=$('#nameAr').val();
	var id=$('#id').val();
	if(nameEn=='' && nameAr=='')
	{
		alert('Invalid Input');
		return;
	}
	if(id>0)
	var action="update";
	else 
	var action="add";
	$.ajax({
		type:"POST",
		url: "LocationDB.php",
		data: { action:action,nameAr:nameAr,nameEn:nameEn,id:id },
		success: function (data) {
			console.log(data);
			$("#form")[0].reset();
			$('#addModal').modal('toggle');
			showMessage(data);
			getData();
		},
		error: function (jqXHR, exception) {
			errorMessage(jqXHR, exception)
		}
		
	}); 
}

$( document ).ready(function() {
    getData();
});

function getData()
{
	var myTable = $('#setData').DataTable();
	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "LocationData.php",
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
			errorMessage(jqXHR, exception)
		}
	});
}
function add()
{
	////var o="rtl"===$("html").attr("data-textdirection")
	
	$("#form")[0].reset();
	$('#id').val('0');
	$('#addModal').modal('toggle');
}

function edit(id)
{
	$.ajax({
		type:"POST",
		url: "LocationDB.php",
		data: {
			action:'getData',id:id
		},
		success: function (data) {
			$('#id').val(id);
			/////alert(JSON.stringify(data));
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#id').val(this.id);
					$('#nameAr').val(this.name_ar);
					$('#nameEn').val(this.name_en);
					
				});
				$('#addModal').modal('toggle');
			}
			else 
			{
				showMessage('Something went wrong');
			}
		},
		error: function (jqXHR, exception) {
			errorMessage(jqXHR, exception)
		}
	});
}
function delModal(id)
{
	$('#delete_modal').modal('toggle');
	$('#del_button').val(id);
}
var o="rtl"===$("html").attr("data-textdirection");
function del()
{
	var id=$('#del_button').val();
	$.ajax({
		type:"POST",
		url: "LocationDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			$('#delete_modal').modal().hide();
			///var result = data.replace(/\D/g, '');	// get digits
			////var dataMsg=data.replace(/[0-9]/g, '');
			////if(result==1)
			showMessage(data);
			getData();
		},
		error: function (jqXHR, exception) {
			errorMessage(jqXHR, exception)
		}
	});
}

function errorMessage(jqXHR, exception) {
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