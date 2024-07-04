var empIds=[];
var empContractIds=[];
$("body").on("change",".emp",function(){
	if (!empIds.includes(this.id))
	{
		empIds.push(this.id);
		empContractIds.push(this.value);
	}
	else 
	{	
		var index = empIds.indexOf(this.id);
		if (index > -1)
		{
			empIds.splice(index, 1);
			empContractIds.splice(index, 1);
		}
	}
});

$("body").on("click","#btnPayrollGen",function(){
	var table = $('#setData').DataTable();
	var length = table.page.info().recordsTotal;
	if(length<1)
	{
		showMessage("Please check atleast one employee to Generate Payroll");
		return;
	}
	if(empIds.length>0 && empContractIds.length>0)
	{
		generatePayroll();
	}
	else 
	{
		showMessage("No Changes are saved b/c you have not change anything");
	}
});

function generatePayroll()
{
	var month=$('#month').val();
	var year=$("#year").val();
	var type=$("#type").val();
	//////alert(month+" "+year+" "+type);
	$.ajax({
		type:"POST",
		url: "PayrollDB.php",
		data: { action:'genPayroll', month:month, year:year, type:type,empids:empIds.toString(),
			empContractIds:empContractIds.toString() 
		},
		success: function (data) {
			console.log(data);
			var result = data.replace(/\D/g, '');	
			/////var data=response.replace(/[0-9]/g, '');
			if(result==1)
			{
				showMessage(data);
				var table = $('#setData').DataTable();
				table
				.clear()
				.draw();
				empIds=[];
				empContractIds=[];
			}
			else 
			showMessage(data);
		},
		error: function (jqXHR, exception) {
			errorFunction(jqXHR, exception)
		}
	});
}