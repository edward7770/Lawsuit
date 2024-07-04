$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
	})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});



function addCustomer()
{
	var name=$('#name').val();
	var companyName=$('#companyName').val();
	var passportNo=$('#passportNo').val();
	var crNo=$('#crNo').val();
	var city=$('#city').val();
	var address=$('#address').val();
	var postBox=$('#postBox').val();
	var telephoneNo=$('#telephoneNo').val();
	var mobileNo=$('#mobileNo').val();
	var email=$('#email').val();
	var nationality=$('#nationality').val();
	var userName=$('#userName').val();
	var password=$('#password').val();
	var endDate=$('#endDate').val();
	var agency=$('#agency').val();
	var note=$('textarea#note').val();
	var id=$('#id').val();
	if(id>0)
		var action="edit";
	else 
		var action="Add";
	$.ajax({
		type:"POST",
		url: "CustomerDB.php",
		data: {
				action:action,id:id,name:name,companyName:companyName, passportNo:passportNo, crNo:crNo, city:city,address:address,
				postBox:postBox,telephoneNo:telephoneNo,mobileNo:mobileNo,email:email,nationality:nationality,
				userName:userName,password:password,endDate:endDate,agency:agency,note:note
		},
		success: function (data) {
			$('#add_customer').modal('toggle');
			message.innerText = data;
			$('#msg_modal').modal('toggle');
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
	
	$('#city').select2({
        dropdownParent: $('#add_customer')
	});
	
	$('#nationality').select2({
        dropdownParent: $('#add_customer')
	});
});

function getData()
{
	$.ajax({
		type:"POST",
		url: "CustomerData.php",
		success: function (data) {
			$('#setData').html(data);
		}
	});
}
function add()
{
	$("#form")[0].reset();
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
					$('#name').val(this.customerName);
					$('#companyName').val(this.companyName);
					$('#passportNo').val(this.idPassportNo);
					$('#crNo').val(this.crNo);
					$('#city').val(this.cityId);
					$('#address').val(this.address);
					$('#postBox').val(this.postBox);
					$('#telephoneNo').val(this.telephoneNo);
					$('#mobileNo').val(this.mobileNo);
					$('#email').val(this.customerEmail);
					$('#nationality').val(this.nationalityId);
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
			$('#delete_modal').modal('toggle');
			message.innerText = data;
			$('#msg_modal').modal('toggle');
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
		alert('Invalid Input');
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
			message.innerText = data;
			$('#msg_modal').modal('toggle');
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
