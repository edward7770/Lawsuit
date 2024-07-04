<style>
	/*
	.modal-content {
		width: 150%;
	}
	*/
	.modal-lg, .modal-xl {
	--bs-modal-width: 100%;
	}
</style>

<div class="modal fade" id="LawsuitMasterDetailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog">
		<div class="modal-content">
			<form id='form' action='javascript:addCustomer();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("lawsuitDetails"); ?></h4>
				
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				
				<div class="row">
			<div class="col-sm-12">
				<div class="card-table">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-center table-hover datatable" id="LawsuitMasterDetailModalData">
								<thead class="thead-light">
									<tr>
										<th>#</th>
										<th><?php echo set_value('action'); ?></th>
										<th><?php echo set_value('customer'); ?></th>
										<th><?php echo wordwrap(set_value('lawsuits_Type'),8,"<br>");?></th>
										<th><?php echo set_value('state'); ?></th>
										<th><?php echo set_value('stage'); ?></th>
										<th><?php echo wordwrap(set_value('opponentName'),8,"<br>");?></th>
										<th><?php echo wordwrap(set_value('opponentLawyer'),8,"<br>");?></th>
										<th><?php echo set_value('amount'); ?></th>
										<th><?php echo set_value('taxValue'); ?></th>
										<th><?php echo set_value('total_amount'); ?></th>
										<th><?php echo wordwrap(set_value('paidAmount'),8,"<br>");?></th>
										<th><?php echo set_value('dues'); ?></th>
										<th><?php echo set_value('paidStatus'); ?></th>
										
									</tr>
								</thead>
								<tbody> </tbody>
				
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
			
			
		</div>
	</div>
</div><!-- /.modal -->