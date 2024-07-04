
$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
	})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});
	
function addCustomerType()
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
		url: "CustomerTypeDB.php",
		data: { action:action,nameAr:nameAr,nameEn:nameEn,id:id },
		success: function (data) {
				$("#customer_type")[0].reset();
				$('#add_customer_type').modal('toggle');
				toastr.success(data,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
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

$( document ).ready(function() {
    getData();
});

function getData()
{
	var myTable = $('#setData').DataTable();
	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "CustomerTypeData.php",
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
	////var o="rtl"===$("html").attr("data-textdirection")
	
	$("#customer_type")[0].reset();
	$('#id').val('0');
	$('#add_customer_type').modal('toggle');
}

function edit(id)
{
	$.ajax({
		type:"POST",
		url: "CustomerTypeDB.php",
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
					$('#id').val(this.custTypeId);
					$('#nameAr').val(this.typeName_ar);
					$('#nameEn').val(this.typeName_en);
					
				});
				$('#add_customer_type').modal('toggle');
			}
			else 
			{
				alert();
			}
		},
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
		url: "CustomerTypeDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			$('#delete_modal').modal().hide();
			var result = data.replace(/\D/g, '');	// get digits
			var dataMsg=data.replace(/[0-9]/g, '');
			if(result==1)
				toastr.success(dataMsg,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
			else 
				toastr.error(dataMsg,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
			getData();
		}	
	});
}