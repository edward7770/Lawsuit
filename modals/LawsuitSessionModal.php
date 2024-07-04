<div class="modal fade" id="LawsuitSessionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='modalFormSession' action='javascript:addSession();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("createSession"); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group">
							<label for="nameSession" class="form-label"><?php echo set_value("nameReasonSession"); ?> <!--<span class="text-danger"> * </span> --></label>
							<input type="text" class="form-control form-control-sm" id="nameSession" placeholder="<?php echo set_value("nameReasonSession"); ?>" >
						</div>
						</div>
						
					</div>
					<div class="col-md-3">
						<div class="mb-4">
						<div class="form-group ">
						
							<label for="dateSession" class="form-label"><?php echo set_value("dateSession"); ?> <span class="text-danger"> * </span></label>
							<input type="date" class="form-control" id="dateSession" placeholder="Select Date" onkeydown="return false;">
						</div>
						</div>
					</div>
					<?php /*
					<div class="col-md-3">
						<div class="mb-4">
						<div class="form-group">
							<label for="dateHSession" class="form-label"><?php echo set_value("dateHijri"); ?></label>
							<div class="cal-icon cal-icon-info">
								<input type="text" class="form-control form-control-sm hijri-date-input" id="dateHSession" placeholder="<?php echo set_value("dateHijri"); ?>" onkeydown="return false;">
							</div>
						</div>
						</div>
					</div>
					*/ ?>
					<div class="col-md-3">
						<div class="mb-4">
						<div class="form-group">
							<label for="timeSession" class="form-label"><?php echo set_value("timeSession"); ?></label>
							<input type="time" class="form-control form-control-sm" id="timeSession" placeholder="<?php echo set_value("timeSession"); ?>" onkeydown="return false;">
						</div>
						</div>
					</div>
				<?php /* 
				</div>
				<div class="row"> 
					<div class="col-md-3">
						<div class="mb-3">
						<div class="form-group">
							<label for="placeSession" class="form-label"><?php echo set_value("placeSession"); ?> </label>
							<input type="text" class="form-control" id="placeSession" placeholder="<?php echo set_value("placeSession"); ?>" >
						</div>
						</div>
					</div>*/ ?>
				</div>
				<div class="row">
					<div class="col-md-12">
					<div class="form-group">
						<div class="">
							<label for="sessionDetails" class="form-label"><?php echo set_value("sessionDetails"); ?></label>
							<textarea class="form-control summernote" id="sessionDetails"></textarea>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
				<input type="hidden" id="SessionId" value="0" />
			</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->