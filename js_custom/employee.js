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
	$('#catId').select2({
        dropdownParent: $('#addModal')
	});
	$('#gender').select2({
        dropdownParent: $('#addModal')
	});
	$('#nationality').select2({
        dropdownParent: $('#addModal')
	});
});
function addEmp()
{
	var nameEn=$('#nameEn').val();
	var nameAr=$('#nameAr').val();
	var email=$('#email').val();
	var catId=$('#catId').val();
	
	var empNo=$('#empNo').val();
	var joinDate=$('#joinDate').val();
	var phone=$('#phone').val();
	var mobile=$('#mobile').val();
	var dob=$('#dob').val();
	var gender=$('#gender').val();
	var nationality=$('#nationality').val();
	var religion=$('#religion').val();
	var idNo=$('#idNo').val();
	var issueDate=$('#issueDate').val();
	var expiryDate=$('#expiryDate').val();
	var passportNo=$('#passportNo').val();
	var dateIssPass=$('#doiP').val();
	var dateExpPass=$('#doexpP').val();
	var id=$('#id').val();
	var active=$("#active").is(":checked");
	if(nameEn=='' && nameAr=='')
	{
		$('#addModal').modal('toggle');
		showMessage('Alteast Enter Employee Name in English or Arabic');
		return;
	}
	if(id>0)
		var action="update";
	else 
		var action="add";
	
	$.ajax({
		type:"POST",
		url: "HR/employeesDB.php",
		data: { action:action,nameAr:nameAr,nameEn:nameEn,email:email,catId:catId,id:id,active:active,
				empNo:empNo,joinDate:joinDate,phone:phone,mobile:mobile,dob:dob,gender:gender,
				nation:nationality,religion:religion,idNo:idNo,dateIssId:issueDate,dateExpId:expiryDate,
				passNo:passportNo,dateIssPass:dateIssPass,dateExpPass:dateExpPass
			},
		success: function (data) {
			////console.log(data);
			$('#addModal').modal('toggle');
			showMessage(data);
			getData();
		},
		error: function (jqXHR, exception) {
				///console.log(jqXHR);	
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
			$('#submit').prop("disabled", false);
		}
	}); 
}

function getData()
{
	var myTable = $('#setData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "HR/employeesData.php",
		data: {searchId:searchId},
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
	$('#id').val("0");
	$('#addModal').modal('toggle');
	
}

function edit(id)
{
	$.ajax({
		type:"POST",
		url: "HR/employeesDB.php",
		data: {
				action:'getData',id:id
		},
		success: function (data) {
			//console.log(data);
			/////alert(JSON.stringify(data));
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#id').val(this.id);
					$('#nameAr').val(this.empName_ar);
					$('#nameEn').val(this.empName_en);
					$('#email').val(this.email);
					$('#catId').val(this.empCatId).change();
					$('#empNo').val(this.empNo);
					$('#joinDate').val(this.joinDate);
					$('#phone').val(this.phoneNo);
					$('#mobile').val(this.mobileNo);
					$('#dob').val(this.dob);
					$('#gender').val(this.gender).change();
					$('#nationality').val(this.nationalityId).change();
					$('#religion').val(this.religion);
					$('#idNo').val(this.idNo);
					$('#issueDate').val(this.issueDate);
					$('#expiryDate').val(this.expiryDate);
					$('#passportNo').val(this.passportNo);
					$('#doiP').val(this.issueDatePassNo);
					$('#doexpP').val(this.expiryDatePassNo);
	
					if(this.isActive==1)
						$('#active').prop('checked', true);
					else 
						$('#active').prop('checked', false);
					
				});
				$('#addModal').modal('toggle');
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
		url: "HR/employeesDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			$('#delete_modal').modal().hide();
			showMessage(data);
			getData();
		}
	});
}
function resetForm()
{
	$("#customer_type")[0].reset();
	$('#catId').val('').change();
	$('#gender').val('').change();
	$('#nationality').val('').change();
}