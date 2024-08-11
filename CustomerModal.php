<style>
	/*
	.modal-content {
		width: 150%;
	}
	*/
	.modal-lg, .modal-xl {
	--bs-modal-width: 72%;
	}
</style>

<!--<div id="add_customer" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" style="display: none;"> -->
<div class="modal fade" id="add_customer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form id='form' action='javascript:addCustomer();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("add_new_customer"); ?></h4>
				
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
			    
			    	
<?php ///display:none
if(isset($top,$top))
	$style="position: fixed; left: $left%; top: $top%; display:none;";
else 
	$style="position: fixed; left: 50%; top: 10%; display:none"
?>
<div id='ajax_loaderModal' style='<?php echo $style; ?>'>
	<button class="btn btn-primary" type="button" disabled>
		<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
		Loading...
	</button>  
</div> 
			    
				<div class="row">
					<div class="col-md-4">
						<div class="mb-3">
							<div class="form-group">
								<label for="custTypeId" class="form-label"><?php echo set_value("customers_types"); ?> <span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="custTypeId" required>
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_customerType.php'); ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label><?php echo set_value("name_ar");?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm name" id="nameAr" placeholder="<?php echo set_value("name_ar"); ?>" required>
								<input type="hidden" id="id" value="0">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label><?php echo set_value("name_en");?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm name" id="nameEn" placeholder="<?php echo set_value("name_en"); ?>" required>
							</div>
						</div>
					</div>
				</div>
				<div class="row">	
					<div class="col-md-4" id='divPassportNo' style='display:none'>
						<div class="mb-4">
							<div class="form-group">
								<label for="passportNo" class="form-label"><?php echo set_value("passport_no"); ?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="passportNo" placeholder="<?php echo set_value("passport_no"); ?>" required>
							</div>
						</div>
					</div>
				
					<div class="col-md-4" id='divCrNo' style='display:none'>
						<div class="mb-4">
						<div class="form-group">
							<label for="crNo" class="form-label"><?php echo set_value("cr_no"); ?></label>
							<input type="text" class="form-control form-control-sm" id="crNo" placeholder="<?php echo set_value("cr_no"); ?>">
						</div>
						</div>
					</div>
				
					<div class="col-md-4" id='divCrCopy' style='display:none'>
						<div class="mb-3">
							<div class="form-group">
								<label for="crCopy" class="form-label"><?php echo set_value("crCopy"); ?></label>
								<fieldset id="crCopyFieldset">	
									<input type="file" class="form-control form-control-sm image_check" id="crCopy">
								</fieldset>
								<span><?php echo set_value("uploadMaximumLimit4Doc"); ?></span>
							</div>
						</div>
					</div>
					<div class="col-md-4" id='divIdCopy' style='display:none'>
						<div class="mb-3">
							<div class="form-group">
								<label for="idCopy" class="form-label"><?php echo set_value("idCopy"); ?></label>
								<fieldset id="idCopyFieldset">	
									<input type="file" class="form-control form-control-sm image_check" id="idCopy">
								</fieldset>
								<span><?php echo set_value("uploadMaximumLimit4Doc"); ?></span>
							</div>
						</div>
					</div>
					<div class="col-md-4" id='divVatNumber' style='display:none'>
						<div class="mb-3">
							<div class="form-group">
								<label for="vatNumber" class="form-label"><?php echo set_value("vat_number"); ?></label>
								<input type="text" class="form-control form-control-sm" id="vatNumber" placeholder="<?php echo set_value("vat_number"); ?>">
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">	
					<div class="col-md-3">
						<div class="mb-3">
						<div class="form-group">
							<label for="city" class="form-label"><?php echo set_value("city"); ?> <span class="text-danger"> * </span></label>
							<!--<input type="text" class="form-control" id="city" placeholder="<?php echo set_value("city"); ?>" required> -->
							<select class="form-control js-example-basic-single form-small select" id="city">
								<option value=""> <?php echo set_value("select_city"); ?></option>
								<?php echo include_once('dropdown_city.php'); ?>
							</select>
						</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
						<div class="form-group">
							<label for="address" class="form-label"><?php echo set_value("address"); ?> <span class="text-danger"> * </span></label>
							<input type="text" class="form-control form-control-sm" id="address" placeholder="<?php echo set_value("address"); ?>" required>
						</div>
					</div>
					</div>
					<div class="col-md-3">
						<div class="mb-4">
						<div class="form-group">
							<label for="postBox" class="form-label"><?php echo set_value("post_box"); ?></label>
							<input type="text" class="form-control form-control-sm" id="postBox" placeholder="<?php echo set_value("post_box"); ?>">
						</div>
					</div>
					</div>
				</div>
				
				<div class="row">
					
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group">
							<label for="mobileNo" class="form-label"><?php echo set_value("mobile_no"); ?></label>
							<input type="number" class="form-control form-control-sm" id="mobileNo" placeholder="<?php echo set_value("mobile_no"); ?>">
						</div>
					</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group">
							<label for="email" class="form-label"><?php echo set_value("email"); ?></label>
							<input type="email" class="form-control form-control-sm" id="email" placeholder="<?php echo set_value("email"); ?>">
						</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group">
							<label for="nationality" class="form-label"><?php echo set_value("nationality"); ?></label>
							<select class="form-control js-example-basic-single form-small select" id="nationality">
								<option value=""> <?php echo set_value("select_country"); ?></option>
								<?php echo include_once('dropdown_country.php'); ?>
							</select>
						</div>
						</div>
					</div>
				</div>
				<hr/>
				<br/>
				
				<div class="row">
					<div class="col-md-4">
						<div class="mb-3">
						<div class="form-group">
							<label for="endDate" class="form-label"><?php echo set_value("end_date_agency"); ?></label>
							<input type="date" class="form-control form-control-sm" id="endDate" placeholder="<?php echo set_value("end_date_agency"); ?>" onkeydown="return false;">
						</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-3">
							<div class="form-group">
								<label for="agency" class="form-label"><?php echo set_value("agency"); ?></label>
								<fieldset id="agencyFieldset">	
									<input type="file" class="form-control form-control-sm image_check" id="agency">
								</fieldset>
								<span><?php echo set_value("uploadMaximumLimit4Doc"); ?></span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
					<div class="form-group">
						<div class="">
							<label for="note" class="form-label"><?php echo set_value("notes"); ?></label>
							<textarea class="form-control form-control-sm" id="note"></textarea>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('addition'); ?>" />
			</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->