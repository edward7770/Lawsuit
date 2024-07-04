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
});


$('#amountContract').on('input', function() {
	CalculateTax();
});
$('#taxValue').change(function() {
	CalculateTax();
});

function CalculateTax()
{
	var amountContract=parseFloat($('#amountContract').val());
	if(!amountContract)
		return $('#contractAmountIncludingTax').val(0);
	var taxValue=parseFloat($('#taxValue').val());
	 taxValue=parseFloat(taxValue/100);
	 taxValue=(taxValue*amountContract);  //toFixed(2)
	 $('#contractAmountIncludingTax').val(taxValue+amountContract);
}

function addMoreCustomer()
{
		///debugger;
		var customer=$("#customer option:selected").text();
		var customerId=$("#customer").val();
		var customerTypeId=$("#customerType").val();
		var customerType=$("#customerType option:selected").text();
		var customerAjectiveId=$("#customerAjective").val();
		var customerAjective=$("#customerAjective option:selected").text();
		var idCustomer="-";
		var nationalAddress="-";
		var idDefendant="-";
		var rowCount = $("#customerTable tr").length;
		var idCustomerInput = document.getElementById('idCustomer'); 
		if (idCustomerInput.files.length == 0) {
			idCustomer="-"
			// file input is empty
			} else {
			var addnew ='<input type="file" id="idCustomerImage'+rowCount+'">';
			$('#CustomerUploadedFiles').append(addnew);
			idCustomer=idCustomerInput.files.item(0).name;
			// file is selected
		}
		var nationalAddressInput = document.getElementById('nationalAddress'); 
		if (nationalAddressInput.files.length>0) {
			var addnew ='<input type="file" id="nationalAddressImage'+rowCount+'">';
			$('#CustomerUploadedFiles').append(addnew);
			var nationalAddress=nationalAddressInput.files.item(0).name;
		}
		var idDefendantInput = document.getElementById('idDefendant'); 
		if (idDefendantInput.files.length>0) {
			var addnew ='<input type="file" id="idDefendantImage'+rowCount+'">';
			$('#CustomerUploadedFiles').append(addnew);
			var idDefendant=idDefendantInput.files.item(0).name;
		}
		if(customerId=="" || customerTypeId=="" || customerAjectiveId=="")
		{
			showMessage("Fill all required fields");
			return;
		}
		var duplicate_row=false;
		var serialNO=$("#customerTable tr").length;
		
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
							if(customer==c.textContent) 		duplicate_customer=true;
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
							message.innerText = "This Customer is Already exists";
							$('#msg_modal').modal('toggle');
							duplicate_row=true;
							/////throw BreakException;
						}
					});
					
				}
			});
			var btn_fileUpload='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
			var btn_fileEmpty='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
			var idCustomerButton="";
			var nationalAddressButton="";
			var idDefendantButton="";
			
			if(idCustomer=="-")
				idCustomerButton=btn_fileEmpty;
			else 
				idCustomerButton=btn_fileUpload;
			
			if(nationalAddress=="-")
				nationalAddressButton=btn_fileEmpty;
			else 
				nationalAddressButton=btn_fileUpload;
			
			if(idDefendant=="-")
				idDefendantButton=btn_fileEmpty;
			else 
				idDefendantButton=btn_fileUpload;
			
			if(!duplicate_row)
			{
				var table = document.getElementById("customerTable");
				var row = table.insertRow();
				var cell_serialNO = row.insertCell();
				var cell_customer = row.insertCell();
				var cell_customerType = row.insertCell();
				var cell_customerAjective = row.insertCell();
				var cell_idCustomer = row.insertCell();
				
				var cell_nationalAddress = row.insertCell();
				var cell_idDefendant=row.insertCell();
				var btn_delete=row.insertCell();
				
				cell_serialNO.innerHTML = serialNO
				cell_customer.innerHTML = customer
				cell_customer.id = customerId
				cell_customerType.innerHTML = customerType
				cell_customerType.id = customerTypeId
				cell_customerAjective.innerHTML = customerAjective
				cell_customerAjective.id = customerAjectiveId
				cell_idCustomer.innerHTML = idCustomerButton
				cell_idCustomer.id = rowCount
				cell_nationalAddress.innerHTML = nationalAddressButton
				cell_nationalAddress.id = rowCount
				cell_idDefendant.innerHTML = idDefendantButton
				cell_idDefendant.id = rowCount
				btn_delete.innerHTML = '<a href="#" class="btn-action-icon" onclick="DeleteRowFunctionCustomer(this)" ><span><i class="fe fe-trash-2 fa-2x red-color"></i></span></a>';
				
			}
			else 
			{
			return;
			}
		}
		
		$("#customer").val("");
		$('#customer').select2({ });
		$("#customerType").val("");
		$('#customerType').select2({ });
		$("#customerAjective").val("");
		$('#customerAjective').select2({ });
		if(idCustomer!="-")
		{
			$('#idCustomerImage'+rowCount)[0].files=$('#idCustomer')[0].files;

			jQuery('#idCustomer').remove();
			var addnew ='<input type="file" class="form-control form-control-sm image_check" id="idCustomer">';
			$('#idCustomerFieldset').append(addnew);
			///var $el = $('#idCustomer');
			///$el.wrap('<form>').closest('form').get(0).reset();
			///$el.unwrap();
		}
		if(nationalAddress!="-")
		{
			$('#nationalAddressImage'+rowCount)[0].files=$('#nationalAddress')[0].files;
			jQuery('#nationalAddress').remove();
			var addnew ='<input type="file" class="form-control form-control-sm image_check" id="nationalAddress">';
			$('#nationalAddressFieldset').append(addnew);
			
			///$('#nationalAddress').val('');
		}
		if(idDefendant!="-")
		{
			$('#idDefendantImage'+rowCount)[0].files=$('#idDefendant')[0].files;
			jQuery('#idDefendant').remove();
			var addnew ='<input type="file" class="form-control form-control-sm image_check" id="idDefendant">';
			$('#idDefendantFieldset').append(addnew);
			///$('#idDefendant').val('');
		}
	}
	
	window.DeleteRowFunctionCustomer = function DeleteRowFunctionOtherEdu(o) {
		var d = o.parentNode.parentNode.rowIndex;  // get row index
		d=d+1;
		var p=o.parentNode.parentNode;
		p.parentNode.removeChild(p);
		///jQuery('#idCustomerImage'+d).remove();
		///jQuery('#nationalAddressImage'+d).remove();
		///jQuery('#idDefendantImage'+d).remove();
		updateSerialNumbers();
	}
	
	function updateSerialNumbers() {
	  var table = document.getElementById("customerTable");
	  var rows = table.rows;

	  // Start from 2nd row (index 1) because the first row is the table header
	  for (var i = 1; i < rows.length; i++) {
		rows[i].cells[0].innerHTML = i;
	  }
	}
	
	$(document).ready(function(){
	});
function addOpponent()
{
	var opponentName=$('#opponentName').val();
	var opponentPhone=$('#opponentPhone').val();
	var opponentNationality=$('#opponentNationality').val();
	var opponentAddress=$('#opponentAddress').val();
	
	$.ajax({
		type:"POST",
		url: "opponentDB.php",
		data: 
		{
			action:'add',
			opponentName:opponentName,
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
		}
	});
}
function getDropDown(type,selected)
{
	var sel=$('#'+selected).val();
	$.ajax({
		type:"POST",
		url: type+'.php',
		////data: { ////selected:sel },
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
			getDropDown('dropdown_Lawyer','lawyer');
			$('#layerModal').modal('toggle');
			showMessage(data);
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
		
		var opponent=$('#opponent').val();
		if(opponent=="")
		{
			showMessage("Please Select Opponent");
			return;
		}
		var opponentLawyer=$('#lawyer').val();
		if(opponentLawyer=="")
		{
			showMessage("Please Select Lawyer");
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
		
		var lawSuitData = {
			"customerDetails":[],
			"action":"add",
			"opponentIds":opponent.toString(),
			"opponentLawyerIds":opponentLawyer.toString(),
			"lawsuitTypeId":lawsuitsType.toString(),
			"lawsuitSubject":$('#subjectLawsuit').val(),
			"stageId":stage.toString(),
			"stateId":state.toString(),
			"lawsuitLawyer":lawsuitLawyer,
			"lawsuitLoc":$('#lawsuitLocation').val(),
			/////"createdAt":$('#createdAt').val(),
			"amount":$('#amountContract').val(),
			"tax":$('#contractAmountIncludingTax').val(),
			"taxVal":$('#taxValue').val(),
			"referenceNo":$('#referenceNo').val(),
			"termAr":$('#ContractTermsAr').val(),
			"termEn":$('#ContractTermsEn').val(),
			"note":$('#note').val()	
		};
		
		var formData = new FormData();
		var btn_fileEmpty='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
		$('#customerTable tr').each(function (index, tr) {
			//get td of each row and insert it into cols array
			if(index>0)
			{
				var cId; var typeId; var adjecId; var idCustomerImage; var nationalAddressImage; var idDefendantImage;
				$(this).find('td').each(function (colIndex, c) {
					if(colIndex==1) cId=c.id;
					if(colIndex==2) typeId=c.id;
					if(colIndex==3) adjecId=c.id;
					if(colIndex==4 && $(this).html()!=btn_fileEmpty) { idCustomerImage="idCustomer"+c.id; formData.append('idCustomer'+c.id, $('#idCustomerImage'+c.id)[0].files[0]);  } 
					if(colIndex==5 && $(this).html()!=btn_fileEmpty) { nationalAddressImage='nationalAddress'+c.id;  formData.append('nationalAddress'+c.id, $('#nationalAddressImage'+c.id)[0].files[0]); }
					if(colIndex==6 && $(this).html()!=btn_fileEmpty) { idDefendantImage='idDefendant'+c.id; formData.append('idDefendant'+c.id, $('#idDefendantImage'+c.id)[0].files[0]);}
				});
				
				var pushList_CustomerData = {
					"cId": cId, "typeId": typeId, "adjecId": adjecId, "idCustomerImage": idCustomerImage , "nationalAddressImage":nationalAddressImage,"idDefendantImage":idDefendantImage
				};
				lawSuitData.customerDetails.push(pushList_CustomerData);
				var pushList_CustomerData = {
					"cId": "", "typeId": "", "adjectives": "", "idCustomerImage":"", "nationalAddressImage":"","idDefendantImage":""
				};
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
			url: 'LawsuitAddDB.php',
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
				var result = response.replace(/\D/g, '');	
				var data=response.replace(/[0-9]/g, '');
				if(result==1)
				{
					resetForm();
					///message.innerText = data;
					///$('#msg_modal').modal('toggle');
					toastr.success(data,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
				}
				else {
					///message.innerText =response;
					///$('#msg_modal').modal('toggle');
					toastr.error(data,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
				}
				// Handle success response
				console.log(response);
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
		
		$('#subjectLawsuit').val('');
		$('#lawsuitLocation').val('');
		$('#createdAt').val('');
		$('#amountContract').val('');
		$('#contractAmountIncludingTax').val('');
		$('#taxValue').val('');
		$('#referenceNo').val('');
		$("#ContractTermsEn").summernote('code', '');
		$("#ContractTermsAr").summernote('code', '');
		$('#note').val('');	
		var node= document.getElementById("CustomerUploadedFiles");
		node.querySelectorAll('*').forEach(n => n.remove());
	}

	