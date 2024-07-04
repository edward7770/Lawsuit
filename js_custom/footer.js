function lang(lang)
{
	var condition = navigator.onLine ? "online" : "offline";
	if( condition == 'offline' ){
		showMessage('No Internet / Network Connection, please reconnect and try again');
		return;
	}
	var getLan=($('#lang').val()).trim();
	if(getLan==lang)
	{
		return false;
	}
	$.ajax({
		type:"POST",
		url: "config/config.php",
		data: {lang:lang},
		
		beforeSend: function()
		{
			$("#ajax_loader").show();
			$('#login').prop("disabled", true);
		},
		success: function (data) {
			////var datta=data.replace(/\D/g, "");   //Return only numbers from string
			location.reload();
		},
		error: function (jqXHR, exception) {
			if (jqXHR.status === 0) {
				showMessage("Not connect.\n Verify Network");
				} else if (jqXHR.status == 404) {
				showMessage("Requested page not found. [404]");
				} else if (jqXHR.status == 500) {
				showMessage("Internal Server Error [500]");
				} else if (exception === 'parsererror') {
				showMessage("Requested JSON parse failed.");
				} else if (exception === 'timeout') {
				showMessage("Time out error.");
				} else if (exception === 'abort') {
				showMessage("Ajax request aborted");
			}
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		},
		complete: function (jqXHR, exception) {
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		}
	}); 
}
function setSearch(btnId) {
	/////var txtSearch=$("#txtSearch").val();
	var txtSearch=document.getElementById(btnId).value;
	if(!txtSearch || txtSearch=="")
	{
		return;
	}
	
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "Search.php");
	
	// Generate a unique name for the window
	var windowName = "formresult_"+txtSearch;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "txtSearch");
	hiddenField.setAttribute("value", txtSearch);
	hiddenField.setAttribute("hidden", "hidden");
	form.setAttribute("target", "_self"); // Open on same window
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	//////window.open('test.html', windowName);
	form.submit();
};

function showLSSearch(lsMId,lsDId,collapsedId,collapsedData)
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
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "id");
	hiddenField.setAttribute("value", collapsedId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "data");
	hiddenField.setAttribute("value", collapsedData);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	window.open('test.html', windowName);
	form.submit();
}

function showSearch(id,pageURL)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", pageURL);
	
	// Generate a unique name for the window
	var windowName = "formresult_"
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "id");
	hiddenField.setAttribute("value", id);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	window.open('test.html', windowName);
	form.submit();
}

$(document).ready(function() {
    // Create the HTML for the search form
    var searchFormHTML = `
        <div class="nav-item has-arrow dropdown-heads mobile-view position-relative w-100">
            <div class="search-mobile">
                <form id="searchFormMobile" action="javascript:setSearch('txtSearchMobile')">
                    <input type="text" id="txtSearchMobile" placeholder="Search here" required>
                    <button class="btn"><img src="assets/img/icons/search.svg" alt="img"></button>
                </form>
            </div>
        </div>
    `;
    
    // Insert the search form HTML before the specified div
    $(searchFormHTML).insertBefore('.content.container-fluid');
});


/*
$(document).ready(function() {
  // Find the menu item with the corresponding link
  var menuItem = $('ul li a[href="Lawsuit"]').parent();
alert(JSON.stringify(menuItem));
  // Add the 'active' class to the menu item
  menuItem.addClass('subdrop');
});
/*
	$("form").submit(function (e) {
	return false;
});

$(document).ready(function() {
	alert();
  // Expand the 'Applications' submenu
  $(".submenu").addClass("menu-open");

  // Add the 'active' class to the 'Calendar' page link
  $("a[href='/customer.php']").addClass("active");
});
*/
	
/*
$(document).ready(function() {
    // Get the current page URL
    ////var currentPageUrl = window.location.href;
    var currentPageUrl = "http://localhost:8080/lawsuit/User.php";

    // Find the corresponding link in the menu
    $('#sidebar-menu a').each(function() {
        var menuItemUrl = $(this).attr('href');
        // Check if the current page URL contains the menu item URL
        if (currentPageUrl.includes(menuItemUrl)) {
            // Add the 'active' class to the parent li element
            $(this).closest('li').addClass('subdrop');
            
            // If you want to expand submenu items, you can trigger the click on the parent submenu
            $(this).closest('.submenu').find('.subdrop').click();
        }
    });
});
*/
/*
$(document).ready(function() {
    // Get the current page URL
    var currentPageUrl = window.location.href;

    // Find the corresponding link in the menu
    $('#sidebar-menu a').each(function() {
        var menuItemUrl = $(this).attr('href');

        // Check if the current page URL contains the menu item URL
        if (currentPageUrl.includes(menuItemUrl)) {
            // Add the 'active' class to the parent li element
            ////$(this).parents('li').addClass('subdrop');

            // If you want to expand submenu items, you can trigger the click on the parent submenu
            $(this).parents('.submenu').find('.subdrop').click();
        }
    });
});

$(document).ready(function() {
    // Get the current page URL
    var currentUrl = window.location.href;

    // Check if the URL contains "/customer.php"
    if (currentUrl.indexOf("/customer.php") !== -1) {
        // Add the "subdrop" class and "style" attribute to the parent li
        $('li.submenu:has(a[href$="/customer.php"])').addClass('subdrop').children('ul').css('display', 'block');
    }
});*/
