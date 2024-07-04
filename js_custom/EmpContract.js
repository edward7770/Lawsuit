var searchId=0;
$( document ).ready(function() {
	searchId=$('#searchId').val();
   getData();
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
	var contractDate=$('#contractDate').val();
	var empId=$('#empId').val();
	var contractName=$('#contractName').val();
	var basicSalary=$("#basicSalary").val();
	var contractDateFrom=$('#contractDateFrom').val();
	var contractDateTo=$('#contractDateTo').val();
	var id=$('#id').val();
	if(id>0)
		var action="update";
	else 
		var action="add";
	
	if(!action || !contractDate || !empId || !contractName || !basicSalary || !contractDateFrom || !contractDateTo)
	{
		showMessage("Please Fill all required fields");
		return;
	}
	
	var ContractData = {
		"contractDetails":[],
		"payslipDetails":[],
	};
	
	var pushList_contractDetails = {
		"action":action,
		"id":id,
		"contractDate":contractDate,
		"empId":empId,
		"contractName":contractName,
		"basicSalary":basicSalary,
		"contractDateFrom":contractDateFrom,
		"contractDateTo":contractDateTo, 
	};
	
	ContractData.contractDetails.push(pushList_contractDetails);
	
	////tblDeductions
	var row=1;
	$('#tblAllowance tr').each(function (index, tr) {
		//get td of each row and insert it into cols array
		if(index>0)
		{
			$(this).find('td').each(function (colIndex, c) {
				var allowSalary=$('#txtAllAmount'+row).val();
				if(colIndex==2 && (id>0 || allowSalary!=""))
				{
					var pushList_allowanceDetails = {
						"allowId": c.id, "allowVal": allowSalary, 
					};
					ContractData.payslipDetails.push(pushList_allowanceDetails);
					var pushList_allowanceDetails = {
						"allowId": "", "allowVal": ""
					};
					
				}
			});
			row++;
			}
	});
	
	var row=1;
	$('#tblDeductions tr').each(function (index, tr) {
		if(index>0)
		{
			$(this).find('td').each(function (colIndex, c) {
				var allowSalary=$('#txtdedAmount'+row).val();
				if(colIndex==2 && (id>0 ||  allowSalary!=""))
				{
					var pushList_allowanceDetails = {
						"allowId": c.id, "allowVal": allowSalary, 
					};
					ContractData.payslipDetails.push(pushList_allowanceDetails);
					var pushList_allowanceDetails = {
						"allowId": "", "allowVal": ""
					};
					
				}
			});
			row++;
		}
		
	});
	/////alert(JSON.stringify(ContractData.payslipDetails));
	////console.log(ContractData);
	/////return;
	$.ajax({
		type:"POST",
		url: "EmpContractDB.php",
		////data: { ContractData },
		////data: JSON.stringify({ ContractData }),
		data: {data:JSON.stringify(ContractData)},
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
	var myTable = $('#setData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "EmpContractData.php",
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
function add()
{	
	loadModal("0");
	$('#addModal').modal('toggle');
}

function edit(id)
{
	loadModal(id);
	$('#addModal').modal('toggle');
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
		url: "EmpContractDB.php",
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
	$('#empId').val('').change();
}

function showDetailModal(val,row)
{
	$('#modelBody').html($('#desc'+row).val());
	$('#msg_detailModal').modal('toggle');
}



function loadModal(id)
{
	$.ajax({
		type:"POST",
		url: "EmpContractEditModal.php",
		async: false, 
		data: { showData:1, id:id },
		success: function (data) {
			////console.log(data);
			$("#loadModalData").html(data);
			$('#empId').select2({
				dropdownParent: $('#addModal')
			});
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