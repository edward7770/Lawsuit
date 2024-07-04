$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
	})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});
var searchId=0;
$( document ).ready(function() {
	searchId=$('#searchId').val();
    
    getData();
	
	$('#taskPriority').select2({
        dropdownParent: $('#addModal')
	});
	$('#taskStatus').select2({
        dropdownParent: $('#addModal')
	});
	$('#taskAssignedTo').select2({
        dropdownParent: $('#addModal')
	});
	var url =window.location.href;
	var params = new URL(url).searchParams;
	if (params.has("add")) {
	  add();
	}
});
function addNew()
{
	var name=$('#name').val();
	var taskDesc=$("#taskDescription").summernote('code');
	var assigTo=$('#taskAssignedTo').val();
	var startDate=$("#taskstartDate").val();
	var dueDate=$('#taskDueDate').val();
	var priority=$('#taskPriority').val();
	var taskStatus=$('#taskStatus').val();
	var id=$('#id').val();
	if(id>0)
		var action="update";
	else 
		var action="add";
	
	$.ajax({
		type:"POST",
		url: "taskDB.php",
		data: { action:action,name:name,taskDesc:taskDesc,assigTo:assigTo,
				startDate:startDate,dueDate:dueDate,priority:priority,taskStatus:taskStatus,id:id, 
			},
		success: function (data) {
			console.log(data);
			$("#modalForm")[0].reset();
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

function getData()
{
	var startDate=$("#taskstartDateSearch").val();
	var dueDate=$('#taskDueDateSearch').val();
	var priority=$('#taskPrioritySearch').val();
	var taskStatus=$('#taskStatusSearch').val();
	
	var myTable = $('#setData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "taskData.php",
		data: {searchId:searchId, startDate:startDate,dueDate:dueDate,priority:priority,taskStatus:taskStatus },
		success: function (data) {
			console.log(data);
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
	resetForm();
	$('#addModal').modal('toggle');
}

function edit(id)
{
	resetForm();
	$.ajax({
		type:"POST",
		url: "taskDB.php",
		data: {
				action:'getData',id:id
		},
		success: function (data) {
			console.log(data);
			$('#id').val(id);
			/////alert(JSON.stringify(data));
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#id').val(this.id);
					$('#name').val(this.taskName);
					$("#taskDescription").summernote('code', this.taskDescription);
					$('#taskstartDate').val(this.startDate).change();
					$('#taskDueDate').val(this.dueDate).change();
					if(this.assignedToId>0)
						$('#taskAssignedTo').val(this.assignedToId).change();
					if(this.priorityId>0)
						$('#taskPriority').val(this.priorityId).change();
					if(this.statusId>0)
						$('#taskStatus').val(this.statusId).change();
				});
				$('#addModal').modal('toggle');
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
		url: "taskDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			console.log(data);
			$('#delete_modal').modal().hide();
			showMessage(data);
			getData();
		}
	});
}
/*
document.querySelector("#fullName").addEventListener("keypress", function (evt) {
	if (String.fromCharCode(evt.which).match(/\d/g))
    {
        evt.preventDefault();
    }
});
*/
function resetForm()
{
	$('#id').val('0');
	$("#modalForm")[0].reset();
	$('#taskStatus').val('').change();
	$('#taskPriority').val('').change();
	$('#taskAssignedTo').val('').change();
	$("#taskDescription").summernote('code', '');
}

function showDetailModal(val,row)
{
	$('#modelBody').html($('#desc'+row).val());
	$('#msg_detailModal').modal('toggle');
}


function search()
{
	getData();
}