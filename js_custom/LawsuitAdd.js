$(document).ready(function() {
	getDropDown('dropdown_Opponent','opponent');
	getDropDown('dropdown_Lawyer','lawyer');
	
});

$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#add').prop("disabled", true);
})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#add').prop("disabled", false);
	//////showMessage = "This Customer is Already exists";
});

function addMoreCustomer()
{
	///debugger;
	var customer=$.trim($("#customer option:selected").text());
	var customerId=$("#customer").val();
	var customerTypeId=$("#customerType").val();
	var customerType=$.trim($("#customerType option:selected").text());
	var customerAjectiveId=$("#customerAjective").val();
	var customerAjective=$.trim($("#customerAjective option:selected").text());
	var serialNO=$("#customerTable tr").length;
	var duplicate_row=false;
	if(customerId && customerTypeId && customerAjectiveId)
	{
		var language = $("#language").val();
		var duplicate_customer=false;
		var duplicate_customerType=false;
		var duplicate_customerAjectiveId=false;

		var duplicate_opponent = false;

		$('#opponentTable tr').each(function (index, opponentTr) {
			var opponent = $(opponentTr).find('td').eq(1).text().trim();
			if(opponent == customer) {
				duplicate_opponent = true;
			}
		})

		if(duplicate_opponent === false) {
			$('#customerTable tr').each(function (index, tr) {
				//get td of each row and insert it into cols array
				if(index>0)
				{
					$(this).find('td').each(function (colIndex, c) {
						//cols.push(c.textContent);
						if(colIndex==1)
						{
							if(customer==c.textContent) 				duplicate_customer=true;
						}
						if(colIndex==2)
						{
							if(customerType==c.textContent) 			duplicate_customerType=true;
						}
						if(colIndex==3)
						{
							if(customerAjective==c.textContent) 		duplicate_customerAjectiveId=true;
						}
						
						if(duplicate_customer && duplicate_customerType && duplicate_customerAjectiveId)
						{
							duplicate_row=true;
							/////return;
							/////throw BreakException;
						}
					});
					
				}
			});
			if(!duplicate_row)
			{
				var table = document.getElementById("customerTable");
				var row = table.insertRow();
				var cell_serialNO = row.insertCell();
				var cell_customer = row.insertCell();
				var cell_customerType = row.insertCell();
				var cell_customerAjective = row.insertCell();
				
				var btn_delete=row.insertCell();
				
				cell_serialNO.innerHTML = serialNO
				cell_serialNO.id = 0
				cell_customer.innerHTML = customer
				cell_customer.id = customerId
				cell_customerType.innerHTML = customerType
				cell_customerType.id = customerTypeId
				cell_customerAjective.innerHTML = customerAjective
				cell_customerAjective.id = customerAjectiveId
				btn_delete.innerHTML = '<a href="#" class="btn-action-icon" onclick="DeleteRowFunctionCustomer(this)" ><span><i class="fe fe-trash-2 fa-1x red-color"></i></span></a>';
				
			}
			else 
			{
				showMessage("This Customer is Already exists");
				return;
			}

			$("#customer").val("");
			$('#customer').select2({ });
			$("#customerType").val("");
			$('#customerType').select2({ });
			$("#customerAjective").val("");
			$('#customerAjective').select2({ });
		} else {
			if(language == 'en') {
				$('#customerAddConfirmDescription').html(customer + "is in opponent list. are you sure want to add?");
			} else {
				$('#customerAddConfirmDescription').html(customer + "متواجد في قائمة الخصوم. هل أنت متأكد أنك تريد الإضافة؟?");
			}

			$("#confirm_customer_add_modal").modal('toggle');

			return;
		}
	}
	else 
	{
		showMessage("please fill required fields");
		return;
	}
}

window.DeleteRowFunctionCustomer = function DeleteRowFunctionCustomer(o) {
	var d = o.parentNode.parentNode.rowIndex;  // get row index
	d=d+1;
	var p=o.parentNode.parentNode;
	p.parentNode.removeChild(p);
	///jQuery('#idCustomerImage'+d).remove();
	///jQuery('#nationalAddressImage'+d).remove();
	///jQuery('#idDefendantImage'+d).remove();
	updateSerialNumbers('customerTable');
}

function updateSerialNumbers(tableName) {
	console.log(tableName);
	var table = document.getElementById(tableName);
	var rows = table.rows;
	
	// Start from 2nd row (index 1) because the first row is the table header
	for (var i = 1; i < rows.length; i++) {
		rows[i].cells[0].innerHTML = i;
	}
}


function addOpponent()
{
	var opponentName_ar=$('#opponentName_ar').val();
	var opponentName_en=$('#opponentName_en').val();
	var opponentPhone=$('#opponentPhone').val();
	var opponentNationality=$('#opponentNationality').val();
	var opponentAddress=$('#opponentAddress').val();
	
	$.ajax({
		type:"POST",
		url: "OpponentDB.php",
		data: 
		{
			action:'add',
			opponentName_ar:opponentName_ar,
			opponentName_en:opponentName_en,
			opponentPhone:opponentPhone,
			opponentNationality:opponentNationality,
			opponentAddress:opponentAddress,
		},
		beforeSend: function()
		{
			$("#ajax_loader").show();
			/////$('#login').prop("disabled", true);
		},
		success: function (data) {
			getDropDown('dropdown_Opponent','opponent');
			$('#opponentModal').modal('toggle');
			showMessage(data);
			$('#opponentForm')[0].reset();
		}
	});
}
function getDropDown(type,selected)
{
	var sel=$('#'+selected).val();
	var showSelect=$('#showSelect').val();
	$.ajax({
		type:"POST",
		url: type+'.php',
		data: { showSelect:showSelect },
		beforeSend: function()
		{
			$("#ajax_loader").show();
			$('#login').prop("disabled", true);
		},
		
		success: function (data) {
			$('#'+selected).html(data);
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
		},
		complete: function (jqXHR, exception) {
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
			$('#'+selected).val(sel).change();
		}
	}); 
}

function addLayer()
{
	var opponentLawyer=$('#opponentLawyer').val();
	var opponentLawyerPhone=$('#opponentLawyerPhone').val();
	$.ajax({
		type:"POST",
		url: "LawyerDB.php",
		data: 
		{
			action:'add',
			opponentLawyer:opponentLawyer,
			opponentLawyerPhone:opponentLawyerPhone,
		},
		beforeSend: function()
		{
			$("#ajax_loader").show();
			/////$('#login').prop("disabled", true);
		},
		success: function (data) {
			/////console.log(data);
			getDropDown('dropdown_Lawyer','lawyer');
			$('#layerModal').modal('toggle');
			showMessage(data);
			document.getElementById("OLawyerForm").reset();
			$('#OLawyerForm')[0].reset();
		}
	});
	
}

$("body").on("click","#add",function(){
	var lsMId=$('#lsMId').val();
	var lsCode=$('#lsMasterCode').val();
	var rowCount = $("#customerTable tr").length;
	if(rowCount<=1)
	{
		showMessage("Please add Customer");
		return;
	} 
	
	var rowCountOpponent = $("#opponentTable tr").length;
	if(rowCountOpponent<=1)
	{
		showMessage("Please add Opponent");
		return;
	}
	var rowCountOpponent = $("#opponentLawyerTable tr").length;
	if(rowCountOpponent<=1)
	{
		showMessage("Please add Opponent Lawyer");
		return;
	}
	
	var lawsuitsType=$('#lawsuitsType').val();
	if(lawsuitsType=="")
	{
		showMessage("Please Select lawsuits Type");
		return;
	}
	var stage=$('#stage').val();
	if(stage=="")
	{
		showMessage("Please Select Stage");
		return;
	}
	
	var state=$('#state').val();
	if(state=="")
	{
		showMessage("Please select State");
		return;
	}
	var lawsuitLawyer=$('#lawsuitLawyer').val();
	if(lawsuitLawyer=="")
	{
		showMessage("Please Select lawsuit Lawyer");
		return;
	}
	
	var lawsuitLocation=$('#lawsuitLocation').val();
	if(lawsuitLocation=="")
	{
		showMessage("Please Enter lawsuit Location");
		return;
	}
	var opponent=[];
	
	$('#opponentTable tr').each(function (index, tr) {
		//get td of each row and insert it into cols array
		if(index>0)
		{
			$(this).find('td').each(function (colIndex, c) {
				if(colIndex==1) opponent.push(c.id);
			});
		}
	});
	var opponentLawyer=[];
	$('#opponentLawyerTable tr').each(function (index, tr) {
		//get td of each row and insert it into cols array
		if(index>0)
		{
			$(this).find('td').each(function (colIndex, c) {
				if(colIndex==1) opponentLawyer.push(c.id);
			});
		}
	});
	
	var url = new URL(window.location.href);
	var pageName = url.pathname.split('/').pop().replace('.php', '');
	
	if (typeof lsCode === 'undefined' && pageName=='LawsuitEdit') {
		var action='update';
	}
	else
	{
		var action='add';
		pageName="LawsuitAdd";
	}
	
	var lawSuitData = {
		"customerDetails":[],
		"action":action,
		"opponentIds":opponent.toString(),
		"opponentLawyerIds":opponentLawyer.toString(),
		"lawsuitTypeId":lawsuitsType.toString(),
		"lawsuitSubject":$('#subjectLawsuit').val(),
		"stageId":stage.toString(),
		"stateId":state.toString(),
		"lawsuitLawyer":lawsuitLawyer,
		"lawsuitLoc":$('#lawsuitLocation').val(),
		"referenceNo":$('#referenceNo').val(),
		"lawsuitId":$('#lawsuitId').val(),
		"lsDate":$('#lsDate').val(),
		"note":$('#note').val(),	
		"lsDId":$('#lsDId').val(),
		"lsMId":$('#lsMId').val()
	};
	var isPush=1;
	var formData = new FormData();
	var btn_fileEmpty='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	$('#customerTable tr').each(function (index, tr) {
		//get td of each row and insert it into cols array
		if(index>0)
		{
			var cId; var typeId; var adjecId;
			$(this).find('td').each(function (colIndex, c) {
				if(colIndex==0 && c.id>0)
				{
					isPush=0;
				}
				if(isPush==1)
				{
					if(colIndex==1) cId=c.id;
					if(colIndex==2) typeId=c.id;
					if(colIndex==3) adjecId=c.id;
				}
			});
			if(isPush==1)
			{
				var pushList_CustomerData = {
					"cId": cId, "typeId": typeId, "adjecId": adjecId
				};
				
				lawSuitData.customerDetails.push(pushList_CustomerData);
				var pushList_CustomerData = {
					"cId": "", "typeId": "", "adjectives": ""
				};
			}
			isPush=1;
		}
	});
	////alert(JSON.stringify(lawSuitData));
	formData.append('customerDetails', JSON.stringify(lawSuitData) );
	if ((typeof(lsMId) != 'undefined' && lsMId != null) && (typeof(lsCode) != 'undefined' && lsCode != null))
	{
		formData.append('lsMId',lsMId );
	}
	////console.log(lawSuitData);
	$.ajax({
		////url: 'LawsuitAddDB.php',
		url: pageName+'DB.php',
		type: 'POST',
		////data: {data:JSON.stringify(formData)},
		data: formData,
		processData: false,
		contentType: false,
		beforeSend: function()
		{
			$("#ajax_loader").show();
			$('#add').prop("disabled", true);
		},
		success: function(response) {
			console.log(response);
			var result = response.replace(/\D/g, '');	
			var data=response.replace(/[0-9]/g, '');
			if(result==1)
			{
				resetForm();
				////redirect page list
				///message.innerText = data;
				///$('#msg_modal').modal('toggle');
				toastr.success(data,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
				////2000=2 seconds
				setTimeout(() => { window.location.replace("Lawsuit.php"); }, 2000);
			}
			else {
				///message.innerText =response;
				///$('#msg_modal').modal('toggle');
				toastr.error(data,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
			}
			// Handle success response
			/////console.log(response);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			// Handle error response
			console.log(errorThrown);
		}
	});
	
});

function resetForm()
{	
	$("#customerTable").find("tr:gt(0)").remove();
	$("#opponentTable").find("tr:gt(0)").remove();
	$("#opponentLawyerTable").find("tr:gt(0)").remove();
	$("#opponent").val("");
	$('#opponent').select2({ });
	$("#lawyer").val("");
	$('#lawyer').select2({ });
	$("#lawsuitsType").val("");
	$('#lawsuitsType').select2({ });
	$("#state").val("");
	$('#state').select2({ });
	$("#stage").val("");
	$('#stage').select2({ });
	$("#lawsuitLawyer").val("");
	$('#lawsuitLawyer').select2({ });
	$('#subjectLawsuit').val('');
	$('#lawsuitLocation').val('');
	$('#lawsuitLocation').select2({ });
	$('#referenceNo').val('');
	//$("#ContractTermsEn").summernote('code', '');
	////$("#ContractTermsAr").summernote('code', '');
	$('#note').val('');	
	$('#lawsuitId').val('');	
	$('#lsDate').val('');	
	
}
$("body").on("change","#customer",function(){
	getCustomerType(this.value);
});
function getCustomerType(custId)
{
	$.ajax({
		type:"POST",
		url: 'dropdown_customerType.php',
		data: { custId:custId },
		success: function (data) {
			////console.log(data);
			$('#customerType').html(data);
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

function addMoreOpponent()
{
	var language = $("#language").val();
	console.log(language);
	///debugger;
	var opponent=$("#opponent option:selected").text().trim();
	var opponentId=$("#opponent").val();
	var serialNO=$("#opponentTable tr").length;
	var duplicate_row=false;
	if(opponentId)
	{
		
		var duplicate_customer = false;
		var customer_end_date_agency = '';

		$('#customerTable tr').each(function (index, customerTr) {
			var customer = $(customerTr).find('td').eq(1).text().trim();
			if(customer == opponent) {
				duplicate_customer = true;
				$('#customers_div div').each(function (index, customerDiv) {
					var customerDivText = $(customerDiv).text().trim();
					if(customerDivText == customer) {
						customer_end_date_agency = $(customerDiv).attr('data_enddateagency');
					}
				})
			}
		})
		var duplicate_opponent=false;
		if(!duplicate_customer) {
			$('#opponentTable tr').each(function (index, tr) {
				//get td of each row and insert it into cols array
				if(index>0)
				{
					$(this).find('td').each(function (colIndex, c) {
						//cols.push(c.textContent);
						if(colIndex==1)
						{
							if(opponent==c.textContent) 		duplicate_opponent=true;
						}
						if(duplicate_opponent)
						{
							duplicate_row=true;
							/////return;
							/////throw BreakException;
						}
					});
					
				}
			});
			if(!duplicate_row)
			{
				var table = document.getElementById("opponentTable");
				var row = table.insertRow();
				var cell_serialNO = row.insertCell();
				var cell_opponent = row.insertCell();
				
				var btn_delete=row.insertCell();
				
				cell_serialNO.innerHTML = serialNO
				cell_opponent.innerHTML = opponent
				cell_opponent.id = opponentId
				btn_delete.innerHTML = '<a href="#" class="btn-action-icon" onclick="DeleteRowFunctionOpponent(this)" ><span><i class="fe fe-trash-2 fa-1x red-color"></i></span></a>';
			}
			else 
			{
				showMessage("This opponent is Already exists");
				return;
			}

			$("#opponent").val("");
			$('#opponent').select2({ });
		} else {
			// var confirmAdd = confirm(opponent + "is in customer list. are you sure want to add?");
			// if (confirmAdd) {
			// }
			if(language == 'en') {
				$('#opponentAddConfirmDescription').html(opponent + " is in customer list and his end date agency is " + customer_end_date_agency + ". are you sure want to add?");
			} else {
				$('#opponentAddConfirmDescription').html(opponent + " is in customer list and his end date agency is " + customer_end_date_agency + ". هل أنت متأكد أنك تريد الإضافة؟?");
			}
			$("#opponent_customer_add_modal").modal('toggle');

			return;
		}
	}
	else 
	{
		showMessage("please fill required fields");
		return;
	}
}

function confirmCustomerAdd() {
	///debugger;
	var customer=$.trim($("#customer option:selected").text());
	var customerId=$("#customer").val();
	var customerTypeId=$("#customerType").val();
	var customerType=$.trim($("#customerType option:selected").text());
	var customerAjectiveId=$("#customerAjective").val();
	var customerAjective=$.trim($("#customerAjective option:selected").text());
	var serialNO=$("#customerTable tr").length;
	var duplicate_row=false;
	if(customerId && customerTypeId && customerAjectiveId)
	{
		var duplicate_customer=false;
		var duplicate_customerType=false;
		var duplicate_customerAjectiveId=false;

		$('#customerTable tr').each(function (index, tr) {
			//get td of each row and insert it into cols array
			if(index>0)
			{
				$(this).find('td').each(function (colIndex, c) {
					//cols.push(c.textContent);
					if(colIndex==1)
					{
						if(customer==c.textContent) 				duplicate_customer=true;
					}
					if(colIndex==2)
					{
						if(customerType==c.textContent) 			duplicate_customerType=true;
					}
					if(colIndex==3)
					{
						if(customerAjective==c.textContent) 		duplicate_customerAjectiveId=true;
					}
					
					if(duplicate_customer && duplicate_customerType && duplicate_customerAjectiveId)
					{
						duplicate_row=true;
						/////return;
						/////throw BreakException;
					}
				});
				
			}
		});
		if(!duplicate_row)
		{
			var table = document.getElementById("customerTable");
			var row = table.insertRow();
			var cell_serialNO = row.insertCell();
			var cell_customer = row.insertCell();
			var cell_customerType = row.insertCell();
			var cell_customerAjective = row.insertCell();
			
			var btn_delete=row.insertCell();
			
			cell_serialNO.innerHTML = serialNO
			cell_serialNO.id = 0
			cell_customer.innerHTML = customer
			cell_customer.id = customerId
			cell_customerType.innerHTML = customerType
			cell_customerType.id = customerTypeId
			cell_customerAjective.innerHTML = customerAjective
			cell_customerAjective.id = customerAjectiveId
			btn_delete.innerHTML = '<a href="#" class="btn-action-icon" onclick="DeleteRowFunctionCustomer(this)" ><span><i class="fe fe-trash-2 fa-1x red-color"></i></span></a>';
			
		}
		else 
		{
			showMessage("This Customer is Already exists");
			return;
		}

		$("#customer").val("");
		$('#customer').select2({ });
		$("#customerType").val("");
		$('#customerType').select2({ });
		$("#customerAjective").val("");
		$('#customerAjective').select2({ });
	}
	else 
	{
		showMessage("please fill required fields");
		return;
	}
}

function confirmOpponentAdd() {
	var opponent=$("#opponent option:selected").text();
	var opponentId=$("#opponent").val();
	var serialNO=$("#opponentTable tr").length;
	var duplicate_row=false;
	if(opponentId)
	{
		var duplicate_opponent=false;
		$('#opponentTable tr').each(function (index, tr) {
			if(index>0)
			{
				$(this).find('td').each(function (colIndex, c) {
					//cols.push(c.textContent);
					if(colIndex==1)
					{
						if(opponent==c.textContent) 		duplicate_opponent=true;
					}
					if(duplicate_opponent)
					{
						duplicate_row=true;
					}
				});
				
			}
		});
		if(!duplicate_row)
		{
			var table = document.getElementById("opponentTable");
			var row = table.insertRow();
			var cell_serialNO = row.insertCell();
			var cell_opponent = row.insertCell();
			
			var btn_delete=row.insertCell();
			
			cell_serialNO.innerHTML = serialNO
			cell_opponent.innerHTML = opponent
			cell_opponent.id = opponentId
			btn_delete.innerHTML = '<a href="#" class="btn-action-icon" onclick="DeleteRowFunctionOpponent(this)" ><span><i class="fe fe-trash-2 fa-1x red-color"></i></span></a>';
		}
		else 
		{
			showMessage("This opponent is Already exists");
			return;
		}
	}
	else 
	{
		showMessage("please fill required fields");
		return;
	}
	
	$("#opponent").val("");
	$('#opponent').select2({ });
}

window.DeleteRowFunctionOpponent = function DeleteRowFunctionOpponent(o) {
	var d = o.parentNode.parentNode.rowIndex;  // get row index
	d=d+1;
	var p=o.parentNode.parentNode;
	p.parentNode.removeChild(p);
	///jQuery('#idCustomerImage'+d).remove();
	///jQuery('#nationalAddressImage'+d).remove();
	///jQuery('#idDefendantImage'+d).remove();
	updateSerialNumbers('opponentTable');
}

function addMoreOpponentLawyer()
{
	///debugger;
	var lawyerId=$("#lawyer").val();
	var lawyer=$("#lawyer option:selected").text();
	var serialNO=$("#opponentLawyerTable tr").length;
	var duplicate_row=false;
	if(lawyerId)
	{
		var duplicate_lawyer=false;
		$('#opponentLawyerTable tr').each(function (index, tr) {
			//get td of each row and insert it into cols array
			if(index>0)
			{
				$(this).find('td').each(function (colIndex, c) {
					//cols.push(c.textContent);
					if(colIndex==1)
					{
						if(lawyer==c.textContent) 			duplicate_lawyer=true;
					}
					if( duplicate_lawyer)
					{
						duplicate_row=true;
						/////return;
						/////throw BreakException;
					}
				});
				
			}
		});
		if(!duplicate_row)
		{
			var table = document.getElementById("opponentLawyerTable");
			var row = table.insertRow();
			var cell_serialNO = row.insertCell();
			var cell_lawyer = row.insertCell();
			var btn_delete=row.insertCell();
			cell_serialNO.innerHTML = serialNO
			cell_lawyer.innerHTML = lawyer
			cell_lawyer.id = lawyerId
			btn_delete.innerHTML = '<a href="#" class="btn-action-icon" onclick="DeleteRowFunctionOpponentLawyer(this)" ><span><i class="fe fe-trash-2 fa-1x red-color"></i></span></a>';
		}
		else 
		{
			showMessage("This opponent Lawyer is Already exists");
			return;
		}
	}
	else 
	{
		showMessage("please fill required fields");
		return;
	}
	$("#lawyer").val("");
	$('#lawyer').select2({ });
}
window.DeleteRowFunctionOpponentLawyer = function DeleteRowFunctionOpponentLawyer(o) {
	var d = o.parentNode.parentNode.rowIndex;  // get row index
	d=d+1;
	var p=o.parentNode.parentNode;
	p.parentNode.removeChild(p);
	///jQuery('#idCustomerImage'+d).remove();
	///jQuery('#nationalAddressImage'+d).remove();
	///jQuery('#idDefendantImage'+d).remove();
	updateSerialNumbers('opponentLawyerTable');
}