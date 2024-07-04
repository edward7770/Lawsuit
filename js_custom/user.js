var selected=false;

$( document ).ready(function() {
    getData();
	////getDataCustomers();
	$('#role').select2({
        dropdownParent: $('#addModal')
	});
	$('#userType').select2({
        dropdownParent: $('#addModal')
	});
	$('#custId').select2({
        dropdownParent: $('#addModal')
	});
	$('#empId').select2({
        dropdownParent: $('#addModal')
	});
	
	$('#roleCustomer').select2({
        dropdownParent: $('#addModal')
	});
	$("#role option").each(function() {
		if($(this).text() == 'Customer' || $(this).text()== 'customer') {
			selected=true;
			$('#roleCustomer').append($('<option>', {
				value: this.value,
				text: this.text
			}));
		}                        
	});
});


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
	var userName=$('#userName').val();
	var fullName=$('#fullName').val();
	var passw=$('#passw').val();
	var userType=$('#userType').val();
	if(userType==1)
		var role=$('#roleCustomer').val();
	else 
		var role=$('#role').val();
	var active=$("#active").is(":checked");
	
	var custId=$('#custId').val();
	var empId=$('#empId').val();
	if(userName=='' || fullName=='' || passw=='' || userType=='' || role=='' || active=='false' || (custId=='' && empId==''))
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
		url: "UserDB.php",
		data: { action:action,userName:userName,fullName:fullName,passw:passw,role:role,
			active:active,id:id,userType:userType,custId:custId,empId:empId 
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
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		}
	}); 
}


function getData()
{
	var myTable = $('#setData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "UserData.php",
		data: {employee:1},
		success: function (data) {
			if (!$.trim(data) == '') {
                data = data.replace(/^\s*|\s*$/g, '');
                data = data.replace(/\\r\\n/gm, '');
                var expr = "</tr>\\s*<tr";
                var regEx = new RegExp(expr, "gm");
                var newRows = data.replace(regEx, "</tr><tr");
                $("#setData").DataTable().rows.add($(newRows)).draw();
			}
			getDataCustomers();
		}
	});
}

function getDataCustomers()
{
	var myTable = $('#setDataClients').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "UserData.php",
		data: {customers:1},
		success: function (data) {
			if (!$.trim(data) == '') {
                data = data.replace(/^\s*|\s*$/g, '');
                data = data.replace(/\\r\\n/gm, '');
                var expr = "</tr>\\s*<tr";
                var regEx = new RegExp(expr, "gm");
                var newRows = data.replace(regEx, "</tr><tr");
                $("#setDataClients").DataTable().rows.add($(newRows)).draw();
			}
		}
	});
}


function add()
{
	resetForm();
	$('#addModal').modal('toggle');
	$('#role').val('').change();
}

function edit(id)
{
	resetForm();
	$.ajax({
		type:"POST",
		url: "UserDB.php",
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
					$('#id').val(this.userId);
					$('#userName').val(this.userName);
					$('#fullName').val(this.fullName);
					$('#passw').val(this.password);
					$('#role').val(this.roleId).change();
					$('#userType').val(this.userType).change();
					setUserType(this.userType);
					if(this.custId>0)
					$('#custId').val(this.custId).change();
					if(this.empId>0)
					$('#empId').val(this.empId).change();
					if(this.isActive==1)
					$('#active').prop('checked', true);
					else 
					$('#active').prop('checked', false);
				});
				$('#addModal').modal('toggle');
			}
			else 
			{
				showMessage(jsonObject['data']);
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
		url: "UserDB.php",
		data: {action:'del',id:id},
		
		success: function (data) {
			$('#delete_modal').modal().hide();
			showMessage(data);
			getData();
		}
	});
}

$("body").on("change","#userType",function(){
	setUserType(this.value)
});

function setUserType(userType)
{
	if(userType==1)
	{
		if(selected) 
		{
			$('#divRole').hide();
			$('#divRoleCustomer').show();
			$('#role').prop('required',false);

		}
		$('#customerDiv').show();
		$('#lawyerDiv').hide();
	}
	else if(userType==2)
	{
		$('#divRole').show();
		$('#divRoleCustomer').hide();
		$('#role').prop('required',true);
		$('#customerDiv').hide();
		$('#lawyerDiv').show();
	}
	else 
	{
		$('#divRole').show();
		$('#divRoleCustomer').hide();
		$('#role').prop('required',true);
		$('#customerDiv').hide();
		$('#lawyerDiv').hide();
	}
}
document.querySelector("#fullName").addEventListener("keypress", function (evt) {
	if (String.fromCharCode(evt.which).match(/\d/g))
    {
        evt.preventDefault();
	}
});

function resetForm()
{
	$("#modalForm")[0].reset();
	$('#id').val('0');
	$('#custId').val('').change();
	$('#empId').val('').change();
	$('#userType').val('').change();
}
