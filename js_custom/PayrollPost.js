var empIdsp=[];
var payrollIds=[];
$("body").on("change",".empPayroll",function(){
	if (!empIdsp.includes(this.id))
	{
		empIdsp.push(this.id);
		payrollIds.push(this.value);
	}
	else 
	{	
		var index = empIdsp.indexOf(this.id);
		if (index > -1)
		{
			empIdsp.splice(index, 1);
			payrollIds.splice(index, 1);
		}
	}
});

$("body").on("click","#btnPostPayroll",function(){
	PostUnPostPayroll(1);
});
$("body").on("click","#btnUnPostPayroll",function(){
	PostUnPostPayroll(0);
});
function PostUnPostPayroll(isPostUnPost)
{
	var table = $('#setDataGenerated').DataTable();
	var length = table.page.info().recordsTotal;
	if(length<1)
	{
		showMessage("Please check atleast one employee to post / save Payroll");
		return;
	}
	if(empIdsp.length>0 && payrollIds.length>0)
	{
		postPayroll(isPostUnPost);
	}
	else 
	{
		/////alert(empIdsp + "  "+ payrollIds)
		showMessage("No Changes are saved b/c you have not change anything");
	}
}

function postPayroll(isPostUnPost)
{
	$.ajax({
		type:"POST",
		url: "PayrollDB.php",
		data: { action:'postPayroll',isPostUnPost:isPostUnPost ,empids:empIdsp.toString(),payrollIds:payrollIds.toString() 
		},
		success: function (data) {
			console.log(data);
			var result = data.replace(/\D/g, '');	
			/////var data=response.replace(/[0-9]/g, '');
			if(result==1)
			{
				showMessage(data);
				empIdsp=[];
				payrollIds=[];
			}
			else 
			showMessage(data);
		},
		error: function (jqXHR, exception) {
			errorFunction(jqXHR, exception)
		}
	}); 
}