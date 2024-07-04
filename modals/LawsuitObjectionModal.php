<div class="modal fade" id="LawsuitObjectionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='modalFormObjection' action='javascript:addObjection();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("createObjection"); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-3">
						<div class="mb-4">
							<div class="form-group">
								<label for="nameObjection" class="form-label"><?php echo set_value("name");?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="nameObjection" placeholder="<?php echo set_value("name"); ?>" required >
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group">
							<label for="dateObjection" class="form-label"><?php echo set_value("endDate"); ?> <span class="text-danger"> * </span></label>
							<input type="date" class="form-control form-control-sm" id="dateObjection" required onkeydown="return false;">
						</div>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group">
							<label for="fileObjection" class="form-label"><?php echo set_value("attached"); ?> <span class="text-danger"> * </span></label>
							<fieldset id="fileObjectionFieldset">
								<input type="file" class="form-control form-control-sm image_check" id="fileObjection" placeholder="<?php echo set_value("attached"); ?>">
							</fieldset>
							<span><?php echo set_value("uploadMaximumLimit"); ?></span>
						</div>
						</div>
					</div>
					
				</div>
				
			
			
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
					<div class="">
						<label for="objectionNotes" class="form-label"><?php echo set_value("notes"); ?></label>
						<textarea class="form-control summernote" id="objectionNotes"></textarea>
					</div>
					</div>
				</div>
			</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
				<input type="hidden" id="objectionId" value="0">
			</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->