<input type='hidden' id='lang' value='<?php echo $language; ?> ' />

<!-- jQuery -->
<script src="assets/js/jquery-3.6.3.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

<!-- Datepicker Core JS -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>

<!-- Feather Icon JS -->
<script src="assets/js/feather.min.js"></script>
<?php if(basename($_SERVER['PHP_SELF'])=='LawsuitDetailPayment.php' || 
	////basename($_SERVER['PHP_SELF'])=='LawsuitEdit.php' ||
	basename($_SERVER['PHP_SELF'])=='LawsuitDetail.php' ||
	basename($_SERVER['PHP_SELF'])=='consultationAdd.php' ||
	basename($_SERVER['PHP_SELF'])=='ContractTerms.php' ||
	basename($_SERVER['PHP_SELF'])=='task.php') { ?>
	<!-- Summernote JS -->
	<script src="assets/plugins/summernote/summernote-lite.min.js"></script>
<?php } ?>
<!-- Chart JS -->
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>

<!-- Select 2 -->
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/plugins/select2/js/custom-select.js"></script>

<!-- multiselect JS -->
<script src="assets/js/jquery-ui.min.js"></script>

<script src="assets/plugins/fullcalendar/fullcalendar.min.js"></script>
<!--<script src="assets/plugins/fullcalendar/jquery.fullcalendar.js"></script> -->

<!-- Datatables JS -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>

<!-- Slimscroll JS -->
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Mask JS -->
<script src="assets/plugins/toastr/toastr.min.js"></script>
<script src="assets/plugins/toastr/toastr.js"></script>


<!-- Custom JS -->
<script src="assets/js/script.js"></script>
<script src="js_custom/showToastrMessage.js"> </script>
<script src="js_custom/footer.js"> </script>
</body>
</html>