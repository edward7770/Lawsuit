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

function getData()
{
	$.ajax({
		type:"POST",
		url: "LawsuitData.php",
		success: function (data) {
			$('#setData').html(data);
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

function newStage(lsMId,lsCode)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitAdd.php");

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
	$.ajax({
		type:"POST",
		url: "LawsuitMasterModalData.php",
		data: { mId:mId },
		
		success: function (data) {
			$('#LawsuitMasterDetailModalData').html(data);
			////$('#add_customer').modal('toggle');
			$('#LawsuitMasterDetailModal').modal('toggle');
		}
	});
}



function viewLSAddDetails(mId,lsCreatedAt)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitAddDetail.php");

	// Generate a unique name for the window
	var windowName = "formresultLS" + mId;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "mId");
	hiddenField.setAttribute("value", mId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsCreatedAt");
	hiddenField.setAttribute("value", lsCreatedAt);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);

	form.submit();
}