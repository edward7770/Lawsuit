		<input type='hidden' id='lang' value='<?php echo $language; ?> ' />
		
		<!-- jQuery -->
		<script src="assets/js/jquery-3.6.3.min.js"></script>
		
		<!-- Bootstrap Core JS -->
		<script src="assets/js/bootstrap.bundle.min.js"></script>
		
		<!-- Feather Icon JS -->
		<script src="assets/js/feather.min.js"></script>
		
		<!-- Slimscroll JS -->
		<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<?php if(basename($_SERVER['PHP_SELF'])=='LawsuitAdd.php') { ?>
		<!-- Summernote JS -->
		<script src="assets/plugins/summernote/summernote-bs4.min.js"></script>
		<?php } ?>
		<!-- Chart JS -->
		<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
		<script src="assets/plugins/apexchart/chart-data.js"></script>
		
		<!-- Select 2 -->
		<script src="assets/plugins/select2/js/select2.min.js"></script>
		<script src="assets/plugins/select2/js/custom-select.js"></script>
		<!-- Custom JS -->
		<!-- Custom JS -->
		<script src="assets/js/jquery-ui.min.js"></script>
		<script src="assets/js/script.js"></script>

	</body>
</html>

<script>
	function lang(lang)
	{
		var condition = navigator.onLine ? "online" : "offline";
		if( condition == 'offline' ){
    	alert('No Internet / Network Connection, please reconnect and try again');
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
		}
	}); 
	}
	/*
		if(lang=='en')
		{
			$('link[href$="assets/css/bootstrap.min.css"]').attr('href','assets-rtl/css/bootstrap.rtl.min.css');
			$('link[href$="assets/plugins/fontawesome/css/fontawesome.min.css"]').attr('href','assets-rtl/plugins/fontawesome/css/fontawesome.min.css');
			$('link[href$="assets/plugins/fontawesome/css/all.min.css"]').attr('href','assets-rtl/plugins/fontawesome/css/all.min.css');
			$('link[href$="assets/plugins/feather/feather.css"]').attr('href','assets-rtl/plugins/feather/feather.css');
			$('link[href$="assets-rtl/css/bootstrap-datetimepicker.min.css"]').attr('href','assets/css/bootstrap-datetimepicker.min.css');
			$('link[href$="assets/plugins/datatables/datatables.min.css"]').attr('href','assets-rtl/plugins/datatables/datatables.min.css');
			$('link[href$="assets/css/style.css"]').attr('href','assets-rtl/css/style.css');
		}
		else if(lang=='ar')
		{
			$('link[href$="assets-rtl/css/bootstrap.rtl.min.css"]').attr('href','assets/css/bootstrap.min.css');
			$('link[href$="assets/plugins/fontawesome/css/fontawesome.min.css"]').attr('href','assets-rtl/plugins/fontawesome/css/fontawesome.min.css');
			$('link[href$="assets-rtl/plugins/fontawesome/css/fontawesome.min.css"]').attr('href','assets/plugins/fontawesome/css/all.min.css');
			$('link[href$="assets-rtl/plugins/feather/feather.css"]').attr('href','assets/plugins/feather/feather.css');
			$('link[href$="assets-rtl/css/bootstrap-datetimepicker.min.css"]').attr('href','assets/css/bootstrap-datetimepicker.min.css');
			$('link[href$="assets-rtl/plugins/datatables/datatables.min.css"]').attr('href','assets/plugins/datatables/datatables.min.css');
			$('link[href$="assets-rtl/css/style.css"]').attr('href','assets/css/style.css');
		}
	}
	*/

</script>