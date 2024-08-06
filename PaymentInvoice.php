<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$pageName = "LawsuitDetailPayment";
	$pageName2="LawsuitMasterDetail";
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName,:pageName2)"; 
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
	$stmt->bindParam(":pageName2",$pageName2,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	////print_r($result);
	function set_value($val)
	{
		foreach($GLOBALS['result'] as $value)
		{
			if(trim($value['phrase'])==trim($val))
			{
				return $value['VALUE'];
				break;
			}
		}
		
	}
	
	/////include('get4setCurrency.php');
?>
<style>
	.table-responsive .dropdown,
	.table-responsive .btn-group,
	.table-responsive .btn-group-vertical {
    position: static;
	}
</style>	
<style>
    .modal-lg, .modal-xl {
	--bs-modal-width: 72% !important;
	}
	#print{
		/* width:50%; */
		display: block;
	}
	.green-color {
	color:green;
    }
    .red-color {
	color:red;
    }
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('lawsuit_invoice'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<!--
							<li>
							<a class="btn btn-primary" onclick="add()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("add_new_customer"); ?></a>
							</li>
							<li>
							<a class="btn btn-success" onclick="customer_type_modal()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('add_new customer_type'); ?></a>
							</li> 
						-->
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		
		<?php 
			$top=10;
			$left=50;
			include_once('loader.php'); 
		?>
		
		<div class="row mt-4">
			<div class="col-lg-4 col-sm-6 col-12">
				<div class="bg-info-light">
					<div class="card-body">
						
						<div class="dash-widget-header">
							<span class="inovices-widget-icon ">
								<img src="assets/img/icons/receipt-item.svg" alt="">
							</span>
							<div class="dash-count">
								<div class="dash-title"><?php echo set_value('totalAmount'); ?></div>
								<div class="dash-counts">
									<p id="totalAmount"></p>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-sm-6 col-12">
				
				<div class="bg-green-light">
					<div class="card-body">
						<div class="dash-widget-header">
							<span class="inovices-widget-icon ">
								<img src="assets/img/icons/message-edit.svg" alt="">
							</span>
							<div class="dash-count">
								<div class="dash-title"><?php echo set_value('paidAmount'); ?></div>
								<div class="dash-counts">
									<p id="paidAmount"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			
			<div class="col-lg-4 col-sm-6 col-12">
				<div class="bg-warning-light">
					<div class="card-body">
						<div class="dash-widget-header">
							<span class="inovices-widget-icon ">
								<img src="assets/img/icons/archive-book.svg" alt="">
							</span>
							<div class="dash-count">
								<div class="dash-title"><?php echo set_value('dueAmount'); ?></div>
								<div class="dash-counts">
									<p id="dueAmount"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="card-table">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-center table-hover datatable" id="example">
								<thead class="thead-light">
									<tr>
										<th><?php echo set_value('action'); ?></th>
										<th>#</th>
										<th><?php echo set_value('lsMasterCode'); ?></th>
										<th><?php echo set_value('customer'); ?></th>
										<th><?php echo set_value('lawsuitLawyer'); ?></th>
										<th><?php echo set_value('lawsuits_Type'); ?></th>
										<th><?php echo set_value('state'); ?></th>
										<th><?php echo set_value('stage'); ?></th>
										<th><?php echo set_value('noOfStages'); ?></th>
										<th><?php echo set_value('paidStatus'); ?></th>
										<th><?php echo set_value('totalAmount'); ?></th>
										<th><?php echo set_value('paidAmount'); ?></th>
										<th><?php echo set_value('dueAmount'); ?></th>
										<th><?php echo set_value('paymentStatus'); ?></th>
									</tr>
								</thead>
								<tbody id='setData'> </tbody>
								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <input type='hidden' value="<?php echo $_SESSION['invoice_no']; ?>" id='invoice_number'>
    <input type='hidden' value="<?php echo date('Y-m-d'); ?>" id='invoice_date'>
</div>
<!-- /Page Wrapper -->

<!-- New Stage Items Modal -->
<div class="modal custom-modal fade" id="newStage_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('newStage'); ?></h3>
					<p><?php echo set_value('areYouSureWantToCreate'); ?>?</p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary paid-continue-btn" id="yesButton" onclick="newStage()"><?php echo set_value('yes'); ?></button>
						</div>
						<div class="col-6">
							<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary paid-cancel-btn"><?php echo set_value("no"); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Delete Items Modal -->

<div class="modal fade" id="LawsuitPrintModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='formLawsuitPrint' action='javascript:printInvoice();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value('lawsuit_invoice'); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="form_invoice_number" class="form-label"><?php echo set_value('invoice_number'); ?><span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="form_invoice_number" placeholder="<?php echo set_value('invoice_number'); ?>">
								</div>
							</div>
						</div>
						
						
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="form_invoice_date" class="form-label"><?php echo set_value('invoice_date'); ?><span class="text-danger"> * </span></label>
									<input type="date" class="form-control form-control-sm" id="form_invoice_date" required onkeydown="return false;">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="lawsuit_code" class="form-label"><?php echo set_value('lsMasterCode'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="lawsuit_code" disabled>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="lawsuit_reference_no" class="form-label"><?php echo set_value('referenceNo'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="lawsuit_reference_no" disabled>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="lawsuit_number" class="form-label"><?php echo set_value('lawsuitId'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="lawsuit_number" disabled>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="type" class="form-label"><?php echo set_value('type'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="type" disabled>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="state" class="form-label"><?php echo set_value('state'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="state" disabled>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="stage" class="form-label"><?php echo set_value('stage'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="stage" disabled>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value('close'); ?></button>&nbsp; 
					<input class="btn btn-primary" type="button" onclick="saveInvoice()" value="<?php echo set_value('save'); ?>" />
					<input class="btn btn-primary" type="submit" style="margin-left: 5px;" id='submit' value="<?php echo set_value('print'); ?>" />
					<input type='hidden' id='lawsuit_detail_id'>
                    <input type='hidden' id='lawsuit_master_id'>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include_once('modals/LawsuitPaymentMasterDetailModal.php'); ?>


<?php //// include_once('MessageModalShow.php'); ?>

<!-- /Main Wrapper -->

<!-- sample modal content -->


<?php include_once('footer.php'); 
?>

<script src="js_custom/PaymentInvoice.js"> </script>
<script>
	$( document ).ready(function() {
		////$('#newStage_modal').modal('toggle');
		//// $('#LawsuitMasterDetailModal').modal('toggle');
	});
</script>