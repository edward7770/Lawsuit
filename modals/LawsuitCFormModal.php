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
<div class="modal fade" id="LawsuitCFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='modalFormPaper' action='javascript:addSession();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("addClearanceForm"); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-6">
						<div class="mb-4">
							<div class="form-group">
								<label for="nameCForm" class="form-label"><?php echo set_value("name");?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="nameCForm" placeholder="<?php echo set_value("name"); ?>" required >
								<input type="hidden" id="modalFormSessionId" value="0">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-4">
						<div class="form-group">
							<label for="fileImageCform" class="form-label"><?php echo set_value("attached"); ?> <span class="text-danger"> * </span></label>
							<input type="file" class="form-control form-control-sm" id="fileImageCform" placeholder="<?php echo set_value("attached"); ?>" required>
							<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
						</div>
						</div>
					</div>
					
				</div>
				
			
			
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					<div class="">
						<label for="NoteCForm" class="form-label"><?php echo set_value("sessionDetails"); ?></label>
						<textarea class="form-control summernote" id="NoteCForm"></textarea>
					</div>
					</div>
				</div>
			</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
			</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->