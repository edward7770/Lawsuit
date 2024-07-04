<div class="modal fade" id="LawsuitTaskModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='modalTaskForm' action='javascript:addTask();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("createLSTask"); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="mb-4">
								<label for="name" class="form-label"><?php echo set_value("taskName"); ?>  <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="name" placeholder="<?php echo set_value("taskName"); ?>" required>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="taskDescription" class="form-label"><?php echo set_value("taskDescription"); ?></label>
							<textarea class="form-control summernote" id="taskDescription"></textarea>
						</div>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<div class="mb-4">
								<label for="taskAssignedTo" class="form-label"><?php echo set_value("taskAssignedTo"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="taskAssignedTo" required>
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_employee.php'); ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group ">
							<label for="taskstartDate" class="form-label"><?php echo set_value("taskstartDate"); ?> <span class="text-danger"> * </span></label>
							<input type="date" class="form-control" id="taskstartDate" placeholder="Select Date" required onkeydown="return false;">
						</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group ">
							<label for="taskDueDate" class="form-label"><?php echo set_value("taskDueDate"); ?> <span class="text-danger"> * </span></label>
							<input type="date" class="form-control" id="taskDueDate" placeholder="Select Date" required onkeydown="return false;">
						</div>
						</div>
					</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
				<input type="hidden" id="taskId" value="0" />
			</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->