$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
	})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});
function addNew()
{
	var roleName=$('#roleName').val();
	var active=$("#active").is(":checked");
	var pageId=$("#pageId").val();
	
	if(roleName=='' || active=='false')
	{
		showMessage('Invalid Input');
		return;
	}
	var id=$('#id').val();
	if(id>0)
		var action="update";
	else 
		var action="add";
	$.ajax({
		type:"POST",
		url: "RoleDB.php",
		data: { action:action,roleName:roleName,pageId:pageId,active:active,id:id },
		success: function (data) {
			$("#modalForm")[0].reset();
			$('#addRoleModal').modal('toggle');
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
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		}
	}); 
}

$( document ).ready(function() {
    getData();
	$('#pageId').select2({
        dropdownParent: $('#addRoleModal')
	});
});

function getData()
{
	var myTable = $('#setData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "RoleData.php",
		success: function (data) {
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
function add()
{
	$("#modalForm")[0].reset();
	$('#id').val('0');
	$('#addRoleModal').modal('toggle');
	$('#role').val('').change();
}

function edit(id)
{
	$.ajax({
		type:"POST",
		url: "RoleDB.php",
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
					$('#id').val(this.roleId);
					$('#roleName').val(this.roleName);
					$('#pageId').val(this.roleDefaultPage).change();
					if(this.isActive==1)
						$('#active').prop('checked', true);
					else 
						$('#active').prop('checked', false);
				});
				$('#addRoleModal').modal('toggle');
			}
			else 
			{
				///alert();
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
		url: "RoleDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			$('#delete_modal').modal().hide();
			showMessage(data);
			getData();
		}
	});
}