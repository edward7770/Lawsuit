
$(function() {
	///$("#divtbl").hide();
	/*
	$('#month').val('1').change();
	$("#year").val('2023').change();
	$("#type").val('Remaining').change();
	*/
});

$("body").on("change","#type",function(){
	$("#divPostPayroll").hide();
	$("#divPayrollGen").hide();
	$("#divtbl").hide();
	empIds=[];
	empContractIds=[];
	
	payrollId=[];
	empIdsPayroll=[];
});

/////$("body").on("click","#search",function()
function search()
{
	var type=$("#type").val();
	if(type=="Remaining")
	{
		getRemainingRecord();
	}
	else if(type=="Generated")
	{
		getGenRecord();
	}
	else 
	{
		$("#divPostPayroll").hide();
		$("#divPayrollGen").hide();
	}
}

function showHideActionButton()
{
	var type=$("#type").val();
	if(type=="Remaining")
	{
		var table = $('#setData').DataTable();
		var length = table.page.info().recordsTotal;
		if(length>0)
		if(length>0)
		{
			$("#divPostPayroll").hide();
			$("#divPayrollGen").show();
		}
	}
	/*
	else if(type=="Generated")
	{
		var table = $('#setDataGenerated').DataTable();
		var length = table.page.info().recordsTotal;
		if(length>0)
		{
			$("#divPayrollGen").hide();
			$("#divPostPayroll").show();
		}
	} */
	else 
	{
		$("#divPostPayroll").hide();
		//////$("#divPayrollGen").hide();
	}
	empIds=[];
	empContractIds=[];
	
	payrollId=[];
	empIdsPayroll=[];
}

function getRemainingRecord()
{
	$("#divtbl").show();
	$("#divtblGen").hide();
	$("#setData").show();
	$("#setDataGenerated").hide();
	var month=$('#month').val();
	var year=$("#year").val();
	var type=$("#type").val();
	//////alert(month+" "+year+" "+type);
	$.ajax({
		type:"POST",
		url: "PayrollData.php",
		data: { month:month, year:year, type:type },
		success: function (data) {
			///console.log(data);
			var myTable = $('#setData').DataTable();
			var rows = myTable.rows().remove().draw();
			if (!$.trim(data) == '') {
				data = data.replace(/^\s*|\s*$/g, '');
				data = data.replace(/\\r\\n/gm, '');
				var expr = "</tr>\\s*<tr";
				var regEx = new RegExp(expr, "gm");
				var newRows = data.replace(regEx, "</tr><tr");
				$("#setData").DataTable().rows.add($(newRows)).draw();
			}
			showHideActionButton();
		},
		error: function (jqXHR, exception) {
			errorFunction(jqXHR, exception)
		}
	}); 
}

function getGenRecord()
{
	$("#divtbl").hide();
	$("#divtblGen").show();
	
	$("#setData").hide();
	$("#setDataGenerated").show();
	
	var month=$('#month').val();
	var year=$("#year").val();
	var type=$("#type").val();
	//////alert(month+" "+year+" "+type);
	$.ajax({
		type:"POST",
		url: "PayrollData.php",
		data: { month:month, year:year, type:type },
		success: function (data) {
			///console.log(data);
			var myTable = $('#setDataGenerated').DataTable();
			var rows = myTable.rows().remove().draw();
			if (!$.trim(data) == '') {
				data = data.replace(/^\s*|\s*$/g, '');
				data = data.replace(/\\r\\n/gm, '');
				var expr = "</tr>\\s*<tr";
				var regEx = new RegExp(expr, "gm");
				var newRows = data.replace(regEx, "</tr><tr");
				$("#setDataGenerated").DataTable().rows.add($(newRows)).draw();
			}
			showHideActionButton();
			
		},
		error: function (jqXHR, exception) {
			errorFunction(jqXHR, exception)
		}
	}); 
}

function errorFunction(jqXHR, exception)
{
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
		url: "PayrollDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			////console.log(data);
			$('#delete_modal').modal().hide();
			showMessage(data);
			getGenRecord();
		}
	});
}

function loadModal(id)
{
	$.ajax({
		type:"POST",
		url: "PayrollEditModal.php",
		async: false, 
		data: { showData:1, id:id },
		success: function (data) {
			////console.log(data);
			$("#loadModalData").html(data);
		},
		error: function (jqXHR, exception) {
			errorFunction(jqXHR, exception);
		}
	}); 
}

function addNew()
{
	var empId=$('#empId').val();
	
	var id=$('#id').val();
	if(id>0)
	var action="update";
	else 
	var action="add";
	
	if(!action || empId<=0 || id<=0)
	{
		showMessage("Please Fill all required fields");
		return;
	}
	
	var PayrollData = {
		"payrollDetails":[],
		"payslipDetails":[],
	};
	
	var pushList_payrollDetails = {
		"action":action,
		"id":id,
		"empId":empId,
	};
	
	PayrollData.payrollDetails.push(pushList_payrollDetails);
	
	////tblDeductions
	var row=1;
	$('#tblAllowance tr').each(function (index, tr) {
		//get td of each row and insert it into cols array
		if(index>0)
		{
			$(this).find('td').each(function (colIndex, c) {
				var allowSalary=$('#txtAllAmount'+row).val();
				if(colIndex==2 && allowSalary!="")
				{
					var pushList_allowanceDetails = {
						"allowId": c.id, "allowVal": allowSalary, 
					};
					PayrollData.payslipDetails.push(pushList_allowanceDetails);
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
				if(colIndex==2 && allowSalary!="")
				{
					
					var pushList_allowanceDetails = {
						"allowId": c.id, "allowVal": allowSalary, 
					};
					PayrollData.payslipDetails.push(pushList_allowanceDetails);
					var pushList_allowanceDetails = {
						"allowId": "", "allowVal": ""
					};
					
				}
			});
			row++;
		}
		
	});
	////alert(JSON.stringify(ContractData.allowanceDetails));
	////alert(JSON.stringify(ContractData.payslipDetails));
	////console.log(ContractData);
	/////return;
	$.ajax({
		type:"POST",
		url: "PayrollUpdateDB.php",
		////data: { ContractData },
		////data: JSON.stringify({ ContractData }),
		data: {data:JSON.stringify(PayrollData)},
		success: function (data) {
			console.log(data);
			$('#addModal').modal('toggle');
			showMessage(data);
			getGenRecord();
		},
		error: function (jqXHR, exception) {
			errorFunction(jqXHR, exception);
		}
	}); 
}
