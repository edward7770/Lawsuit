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
<div class="modal fade" id="LawsuitImageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='modalCreateImage' action='javascript:createImage();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("createImage"); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-6">
						<div class="mb-4">
							<div class="form-group">
								<label><?php echo set_value("name");?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="nameImage" placeholder="<?php echo set_value("name"); ?>" required>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-4">
						<div class="form-group">
							<label for="fileImage" class="form-label"><?php echo set_value("dateSession"); ?> <span class="text-danger"> * </span></label>
							<fieldset id="fileImageFieldset">
								<input type="file" class="form-control form-control-sm image_check" id="fileImage" placeholder="<?php echo set_value("dateSession"); ?>">
							</fieldset>
							<span><?php echo set_value("uploadMaximumLimit"); ?></span>
						</div>
						</div>
					</div>
					
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
				<input type="hidden" id="imageId" value="0" />
			</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->