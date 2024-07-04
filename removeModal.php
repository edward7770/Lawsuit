<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('delete_Customer'); ?></h3>
					<p><?php echo set_value('areYouSureWantTodelete?'); ?></p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<!--
						<div class="col-6">
							<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-danger" id="del_button" onclick="del();"><?php echo set_value('delete'); ?></button>
						</div> -->
						<div class="col-6">
							<div class="profile-picture">
								<div class="img-upload">
									<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-remove" id="del_button" onclick="del();" style='min-width:168px'><?php echo set_value('delete'); ?></button>
								</div>										
							</div>
						</div>
						
						<div class="col-6">
							<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary paid-cancel-btn"><?php echo set_value("close"); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Delete Items Modal -->