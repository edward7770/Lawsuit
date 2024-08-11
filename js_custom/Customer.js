$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$("#ajax_loaderModal").show();
	$('#submit').prop("disabled", true);
	})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$("#ajax_loaderModal").hide();
	$('#submit').prop("disabled", false);		
});
var searchId=0;
$( document ).ready(function() {
	searchId=$('#searchId').val();
	getData();
	
	$('#city').select2({
        dropdownParent: $('#add_customer')
	});
	
	$('#nationality').select2({
        dropdownParent: $('#add_customer')
	});
	
	$('#custTypeId').select2({
        dropdownParent: $('#add_customer')
	});
	var url =window.location.href;
	var params = new URL(url).searchParams;
	if (params.has("add")) {
	  add();
	}
});
function addCustomer()
{
	var id=$('#id').val();
	if(id>0)
		var action="edit";
	else 
		var action="Add";
	var custTypeId=$('#custTypeId').val();

	var customerData = {
		"action":action,
		"id":id,
		"nameAr":$('#nameAr').val(),
		"nameEn":$('#nameEn').val(),
		"custTypeId":custTypeId,
		"passportNo":$('#passportNo').val(),
		"vatNumber":$('#vatNumber').val(),
		"crNo":$('#crNo').val(),
		"city":$('#city').val(),
		"address":$('#address').val(),
		"postBox":$('#postBox').val(),
		"mobileNo":$('#mobileNo').val(),
		"email":$('#email').val(),
		"nationality":$('#nationality').val(),
		"endDate":$('#endDate').val(),
		"note":$('textarea#note').val()
	};
	var formData = new FormData();
	formData.append('customerData', JSON.stringify(customerData));
	
	var agency=$('#agency')[0].files[0];
	if(agency) formData.append('agency', agency);
	var crCopy=$('#crCopy')[0].files[0];
	var idCopy=$('#idCopy')[0].files[0];
	if(custTypeId==1 && crCopy)
	{
		formData.append('crCopy', $('#crCopy')[0].files[0]); 
	}
	else if(custTypeId==2 && idCopy)
	{		
		formData.append('idCopy', $('#idCopy')[0].files[0]);
	}
	///alert(JSON.stringify(formData));
	//////console.log(formData);
	$.ajax({
		type:"POST",
		url: "CustomerDB.php",
		processData: false,
		contentType: false,
		data: formData,
		/*
		beforeSend: function() {
             $("#ajax_loaderModal").show();
             $('#submit').prop("disabled", true);
        },
        async: false, */
		success: function (data) {
		    $("#ajax_loaderModal").hide(); 
		    $('#submit').prop("disabled", false);
			console.log(data);
			$('#add_customer').modal('toggle');
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
			$("#ajax_loaderModal").hide(); 
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
		url: "CustomerData.php",
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
	$("#form")[0].reset();
	$('#city').val('').change();
	$('#nationality').val('').change();
	$('#id').val("0");
	$('#add_customer').modal('toggle');
	$('#city').val('');
	$('#country').val('');
	///$('#submit').val('Add');
}

function edit(id)
{
	$.ajax({
		type:"POST",
		url: "CustomerDB.php",
		data: {
				action:'getData',id:id
		},
		
		success: function (data) {
			const jsonObject = JSON.parse(data);
			
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#id').val(this.customerId);
					$('#custTypeId').val(this.custTypeId).change();
					$('#nameAr').val(this.customerName_ar);
					$('#nameEn').val(this.customerName_en);
					$('#passportNo').val(this.idPassportNo);
					$('#crNo').val(this.crNo);
					$('#city').val(this.cityId).change();
					$('#address').val(this.address);
					$('#postBox').val(this.postBox);
					$('#mobileNo').val(this.mobileNo);
					$('#email').val(this.customerEmail);
					$('#nationality').val(this.nationalityId).change();
					$('#userName').val(this.username);
					$('#password').val(this.password);
					$('#endDate').val(this.endDateAgency);
					////$('#agency').val();
					$('textarea#note').val(this.notes);
					////$('#submit').val('Update');
					
				});
				$('#add_customer').modal('toggle');
				$('#city').select2({
					dropdownParent: $('#add_customer')
				});
				
				$('#nationality').select2({
					dropdownParent: $('#add_customer')
				});
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
		url: "CustomerDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			getData();
			$('#delete_modal').modal().hide();
			showMessage(data);
		}
	});
}

function customer_type_modal()
{
	$("#customer_type")[0].reset();
	//$('#id').val("0");
	$('#add_customer_type').modal('toggle');
}

function addCustomerType()
{
	var nameEn=$('#nameEn').val();
	var nameAr=$('#nameAr').val();
	if(nameEn=='' && nameAr=='')
	{
		showMessage("Invalid Input");
		return;
	}
	
	$.ajax({
		type:"POST",
		url: "CustomerTypeDB.php",
		data: { action:'add',nameAr:nameAr,nameEn:nameEn },
		success: function (data) {
			getData();
			$("#customer_type")[0].reset();
			$('#add_customer_type').modal('toggle');
			showMessage(data);
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
document.querySelector(".name").addEventListener("keypress", function (evt) {
	if (String.fromCharCode(evt.which).match(/\d/g))
    {
        evt.preventDefault();
    }
});

$("body").on("change","#custTypeId",function(){
	if(this.value==1)
	{
		$('#passportNo').prop('required',false);
		$('#divPassportNo').hide();
		$('#crNo').prop('required',true);
		$('#divCrNo').show();
		$('#divCrCopy').show();
		$('#divIdCopy').hide();
		$('#divVatNumber').show();
	}
	else if(this.value==2)
	{
		$('#passportNo').prop('required',true);
		$('#divPassportNo').show();
		$('#crNo').prop('required',false);
		$('#divCrNo').hide();
		$('#divCrCopy').hide();
		$('#divIdCopy').show();
		$('#divVatNumber').hide();
	}
	else 
	{
		$('#divCrNo').hide();
		$('#divCrCopy').hide();
		$('#divIdCopy').hide();
		$('#divPassportNo').hide();
		$('#divVatNumber').hide();
	}
});