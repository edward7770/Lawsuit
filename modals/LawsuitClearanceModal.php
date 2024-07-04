<div class="modal fade" id="LawsuitClearanceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='modalFormClearance' action='javascript:addClearance();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("addClearanceForm"); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-6">
						<div class="mb-4">
							<div class="form-group">
								<label for="nameClearance" class="form-label"><?php echo set_value("name");?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="nameClearance" placeholder="<?php echo set_value("name"); ?>" required >
								
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-4">
						<div class="form-group">
							<label for="fileClearance" class="form-label"><?php echo set_value("attached"); ?> <span class="text-danger"> * </span></label>
							<input type="file" class="form-control form-control-sm" id="fileClearance" placeholder="<?php echo set_value("attached"); ?>">
							<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
						</div>
						</div>
					</div>
					
				</div>
				
			
			
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					<div class="">
						<label for="NoteClearance" class="form-label"><?php echo set_value("notes"); ?></label>
						<textarea class="form-control summernote" id="NoteClearance"></textarea>
					</div>
					</div>
				</div>
			</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
				<input type="hidden" id="clearenceId" value="0">
			</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->