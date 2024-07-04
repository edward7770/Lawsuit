$(document).ready(function() {
	getDropDown('dropdown_Opponent','opponent');
	getDropDown('dropdown_Lawyer','lawyer');
	var lsCode=$('#lsMasterCode').val();
	if (typeof lsCode === 'undefined')
	{
		$('#lawsuitsType').val($('#typeId').val());
		$('#lawsuitsType').select2({ });
		$('#state').val($('#stateId').val());
		$('#state').select2({ });
		$('#stage').val($('#stageId').val());
		$('#stage').select2({ });
		$('#lawsuitLawyer').val($('#empid').val());
		$('#lawsuitLawyer').select2({ });
		
		$('#lawsuitLocation').val($('#lsLocId').val());
		$('#lawsuitLocation').select2({ });
	}
	else 
	{
		$('#state option').each(function() {
			var optionText = $(this).text().toLowerCase();
			if(optionText=='active' || optionText=='نشطة')
			{
				$('#state').val($(this).val()).change();
				return false;
			}
		});
	}
	
	//$("#ContractTermsEn").summernote('code', ContractTermsEn);
	window.scrollTo({ top: 0, behavior: 'smooth' });
	});

function delCustomer()
{
	delCustomerfrmTable.parentNode.removeChild(delCustomerfrmTable);
	updateSerialNumbers('customerTable');
	$.ajax({
		type:"POST",
		url: "LawsuitEditDB.php",
		data: {action:'delCustomer',id:cId,lsDId:lsDId},
		success: function (data) {
			console.log(data);
			$('#delete_modal').modal().hide();
			showMessage(data);
		}
	});
}
var delCustomerfrmTable;
var cId;
var lsDId;
window.DeleteRowFunctionCustomerDB = function DeleteRowFunctionOtherEdu(o, cid, lsDid) {
	$('#delete_modal').modal('toggle');
	delCustomerfrmTable=o.parentNode.parentNode;
	cId=cid;
	lsDId=lsDid;
}	