
$( document ).ready(function() {
    getData();
});
$(document).ajaxStart(function() {
	$("#ajax_loader").show();
		$('#submit').prop("disabled", true);
	})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});

function search()
{
	getData();
}

function getData()
{
	var type=$('#lawsuitsType').val();
	var state=$('#state').val();
	var stage=$('#stage').val();
	
	var myTable = $('#example').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "LawsuitData.php",
		data: { type:type, state:state, stage:stage },
		success: function (data) {
			/////console.log(data);
			////$('#setData').html(data);
			if (!$.trim(data) == '') {
                data = data.replace(/^\s*|\s*$/g, '');
                data = data.replace(/\\r\\n/gm, '');
                var expr = "</tr>\\s*<tr";
                var regEx = new RegExp(expr, "gm");
                var newRows = data.replace(regEx, "</tr><tr");
                $("#example").DataTable().rows.add($(newRows)).draw();
             }
		}
	});
}

function viewLSDetails(lsMId,lsDId)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitDetail.php");

	// Generate a unique name for the window
	var windowName = "formresult_" + lsMId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsMId");
	hiddenField.setAttribute("value", lsMId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsDId");
	hiddenField.setAttribute("value", lsDId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);

	form.submit();
}
function viewLSDetailsPayment(lsMId,lsDId)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitDetailPayment.php");

	// Generate a unique name for the window
	var windowName = "formresult_" + lsMId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsMId");
	hiddenField.setAttribute("value", lsMId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsDId");
	hiddenField.setAttribute("value", lsDId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);

	form.submit();
}

function newStage(lsMId,lsDId,lsCode)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitEdit.php");
	
	// Generate a unique name for the window
	var windowName = "formresult_" + lsMId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsMId");
	hiddenField.setAttribute("value", lsMId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsDId");
	hiddenField.setAttribute("value", lsDId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsCode");
	hiddenField.setAttribute("value", lsCode);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);

	form.submit();
}

function viewDetails(mId)
{
	var myTable = $('#LawsuitMasterDetailModalData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "LawsuitMasterModalData.php",
		data: { mId:mId },
		success: function (data) {
			//////$('#LawsuitMasterDetailModalData').html(data);
			if (!$.trim(data) == '') {
                data = data.replace(/^\s*|\s*$/g, '');
                data = data.replace(/\\r\\n/gm, '');
                var expr = "</tr>\\s*<tr";
                var regEx = new RegExp(expr, "gm");
                var newRows = data.replace(regEx, "</tr><tr");
                $("#LawsuitMasterDetailModalData").DataTable().rows.add($(newRows)).draw();
             }
			////$('#add_customer').modal('toggle');
			$('#LawsuitMasterDetailModal').modal('toggle');
		}
	});
}

function viewLSEdit(lsMId,lsDId)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitEdit.php");

	// Generate a unique name for the window
	var windowName = "formresultLS" + lsDId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsMId");
	hiddenField.setAttribute("value", lsMId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsDId");
	hiddenField.setAttribute("value", lsDId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);

	form.submit();
}
var o="rtl"===$("html").attr("data-textdirection");
function printContracts(lsMId)
{
	$.ajax({
		type:"POST",
		url: "getContractData.php",
		////data: { lsDId:lsDId },
		data: { lsMId:lsMId },
		success: function (data) {
			////console.log(data);
			htmlTextArray=[];
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function(index,item) {
					if(item['contractEn'])
						htmlTextArray.push(item['contractEn']);
					if(item['contractAr'])
						htmlTextArray.push(item['contractAr']);
				});
				if(htmlTextArray.length>0)
				{
					var fileName="contract";
					generateHTML_docZip(htmlTextArray[0],htmlTextArray[1],fileName)	
				}
				else 
				{
					toastr.error("Empty Contract","",{closeButton:!0,tapToDismiss:!1,rtl:o});
				}
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
	////alert(id);
	$.ajax({
		type:"POST",
		url: "LawsuitDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			////console.log(data);
			getData();
			$('#delete_modal').modal().hide();
			showMessage(data);
		}
	});
}

function delModalStage(id)
{
	$('#delete_modalStage').modal('toggle');
	$('#del_buttonStage').val(id);
}
function delStage()
{
	////var id=$('#del_buttonStage').val();
	var id=$('#del_buttonStage').val().split(",");
	var lsMId=id[0];
	var lsDId=id[1];
	
	$.ajax({
		type:"POST",
		url: "LawsuitDB.php",
		data: {action:'del',lsMId:lsMId,lsDId:lsDId},
		success: function (data) {
			////console.log(data);
			getData();
			$('#delete_modal').modal().hide();
			showMessage(data);
		}
	});
}