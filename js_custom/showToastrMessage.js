var o="rtl"===$("html").attr("data-textdirection");
/////var h=$("#position-bottom-center");
function showMessage(data)
{
	var result = data.replace(/\D/g, '');	// get digits
	var dataMsg=data.replace(/[0-9]/g, '');
	if(result==1)
		toastr.success(dataMsg,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
	else 
		toastr.error(dataMsg,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
}

function showMessageFull(dataMsg)
{
	toastr.error(dataMsg,"",{closeButton:!0,tapToDismiss:!1,rtl:o});
}