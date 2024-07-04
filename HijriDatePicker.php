<?php 
include_once('header.php'); 
////include_once('config/conn.php');

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" /> 
<link href="assets/plugins/hijri-date-picker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" /> 
<!-- Page Wrapper -->
			<div class="page-wrapper">
				<div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="content-page-header ">
							<h5><?php ///echo set_value("lawsuitDetails"); ?>Bootstrap Hijri Date Picker Examples</h5>
							
						</div>
					</div>
					<div class="row">
							<div class="col-md-12">
								<div class="card bg-white">
									<div class="card-body">
										
										<div class="row">
											<div class="col-lg-3 col-md-6 col-sm-12">
												<div class="form-group">
													<label for="createdAt" class="form-label"><?php //echo set_value("created_at"); ?><span class="text-danger"> * </span></label>
													<input type="text" class="form-control hijri-date-input" id="createdAt" placeholder="<?php ///echo set_value("created_at"); ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
										
<?php include_once('footer.php'); ?>
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script> -->
    <script src="assets/plugins/hijri-date-picker/js/bootstrap-hijri-datepicker.min.js"></script>
    <script type="text/javascript">

        $(function () {
            $(".hijri-date-input").hijriDatePicker();
			
        });

    </script>

 <script type="text/javascript">

</script>