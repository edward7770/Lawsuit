<div class="modal fade" id="LawsuitVetoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='modalFormVeto' action='javascript:addVeto();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value("createVeto"); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="nameVeto" class="form-label"><?php echo set_value("name");?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="nameVeto" placeholder="<?php echo set_value("name"); ?>" required >
									
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-4">
							<div class="form-group">
								<label for="dateVeto" class="form-label"><?php echo set_value("endDate"); ?> <span class="text-danger"> * </span></label>
								<input type="date" class="form-control form-control-sm" id="dateVeto" required onkeydown="return false;">
							</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-4">
							<div class="form-group">
								<label for="fileImageVeto" class="form-label"><?php echo set_value("attached"); ?> <span class="text-danger"> * </span></label>
								<fieldset id="fileImageVetoFieldset">
									<input type="file" class="form-control form-control-sm image_check" id="fileImageVeto" placeholder="<?php echo set_value("attached"); ?>">
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
							<label for="vetoNotes" class="form-label"><?php echo set_value("notes"); ?></label>
							<textarea class="form-control summernote" id="vetoNotes"></textarea>
						</div>
						</div>
					</div>
				</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
					<input type="hidden" id="vetoId" value="0">
				</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->