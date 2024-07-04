$( document ).ready(function() {
		getData();
		$('#catId').select2({
        dropdownParent: $('#addModal')
		});
		$('#subCatId').select2({
			dropdownParent: $('#addModal')
		});
		
	});	
	$(document).ajaxStart(function() {
		$("#ajax_loader").show();
		$('#submit').prop("disabled", true);
	})
	
	.ajaxStop(function() {
		$("#ajax_loader").hide();
		$('#submit').prop("disabled", false);		
	});
	
	function getData()
	{
		var myTable = $('#setData').DataTable();
		var rows = myTable.rows().remove().draw();
		$.ajax({
			type:"POST",
			url: "AssetData.php",
			success: function (data) {
				///console.log(data);
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
	
	$("body").on("click","#addButton",function(){
		$("#form")[0].reset();
		$('#id').val("0");
		$('#addModal').modal('toggle');
		$('#catId').val('').change();
		$('#subCatId').val('').change();
	});
	
	function add()
	{
		var catId=$('#catId').val();
		var subCatId=$('#subCatId').val();
		var supl=$('#supplier').val();
		var amount=$('#amount').val();
		var tax=$('#taxValue').val();
		var taxAmount=$('#taxValueAmount').val();
		var totAmount=$('#amountWithTax').val();
		var quantity=$('#quantity').val();
		var location=$('#location').val();
		var remarks=$('#remarks').val();
		var date=$('#date').val();
		var deprRate=$('#deprRate').val();
		
		var id=$('#id').val();
		if(!catId || !subCatId || !supl || !date || !deprRate || !amount || !totAmount || !quantity || !tax || !taxAmount || !id)
		{
			showMessage('Invalid Input');
			return;
		}
		if(id>0)
		var action="edit";
		else 
		var action="add";
		
		$.ajax({
			type:"POST",
			url: "AssetDB.php",
			data: {
				action:action,id:id,catId:catId,subCatId:subCatId,date:date,deprRate:deprRate,
				amount:amount,location:location,quantity:quantity,remarks:remarks,supl:supl,tax:tax, taxAmount:taxAmount,totAmount:totAmount
			},
			success: function (data) {
				console.log(data);
				$('#addModal').modal('toggle');
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
			}
		}); 
	}
	
	function edit(id)
	{
		$.ajax({
			type:"POST",
			url: "AssetDB.php",
			data: {
				action:'getData',id:id
			},
			success: function (data) {
				////console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#id').val(this.id);
						$('#date').val(this.assetDate);
						$('#deprRate').val(this.deprRate);
						$('#amount').val(this.amount);
						$('#location').val(this.location);
						$('#quantity').val(this.quantity);
						$('#remarks').val(this.remarks);
						$('#catId').val(this.catId).change();
						getSubCategory(this.subCatId)
						$('#supplier').val(this.supplier);
						$('#taxValue').val(this.taxValue);
						$('#taxValueAmount').val(this.taxValueAmount);
						CalculateTax();
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
			url: "AssetDB.php",
			data: {action:'del',id:id},
			success: function (data) {
				getData();
				showMessage(data);
				$('#msg_modal').modal('toggle');
			}
		});
	}

/////$("#catId").change(function(event) {console.log(event,event.target,event.currentTarget,event.srcElement)

$(document).on('change', '#catId', function (e) {
	getSubCategory('0');
});

function getSubCategory(setSubCatId)
{
	var getSelect=$('#getSelect').val();
	var catId=$('#catId').val();
	$.ajax({
		type:"POST",
		url: "dropdown_assetSubCategory.php",
		data: { catId:catId,getSelect:getSelect },
		success: function (data) {
			console.log(data);
			$('#subCatId').html(data);
		},
		complete: function (data) {
			if(setSubCatId>0)
			$('#subCatId').val(setSubCatId).change();
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


$('#amount').on('input', function() {
		CalculateTax();
});
$('#taxValue').change(function() {
	CalculateTax();
});

function CalculateTax()
{
	var amount=parseFloat($('#amount').val());
	if(!amount)
		return $('#amountWithTax').val(0);
	var taxValue=parseFloat($('#taxValue').val());
	 taxValue=parseFloat(taxValue/100);
	 taxValue=(taxValue*amount);  //toFixed(2)
	$('#amountWithTax').val( parseFloat(taxValue+amount).toFixed(3));
	$('#taxValueAmount').val(parseFloat(taxValue).toFixed(3));
	 
}