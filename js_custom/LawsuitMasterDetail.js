$( document ).ready(function() {
    getData();
});
$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#add').prop("disabled", true);
})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#add').prop("disabled", false);		
});


function getData()
{
	$.ajax({
		type:"POST",
		url: "LawsuitMasterDetailData.php.php",
		success: function (data) {
			$('#setData').html(data);
		}
	});
}

function action(mId,lsCreatedAt,actionName)
{
	if(actionName=='view')
	{
		viewDetails(mId,lsCreatedAt);
	}
	
}
function viewDetails(mId,lsCreatedAt)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitMasterDetail.php");

	// Generate a unique name for the window
	var windowName = "formresult_" + mId;
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