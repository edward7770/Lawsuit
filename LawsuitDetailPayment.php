<?php
	if(!isset($_POST['lsDId']) || empty($_POST['lsDId']))
	exit('<script>window.location.replace("Lawsuit.php")</script>');
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName`=:pageName"; 
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}

	$qry_detail="call LawsuitDetailsData(:lsDetailId)"; 
	$stmt_detail=$dbo->prepare($qry_detail);
	$stmt_detail->bindParam(":lsDetailId",$_POST['lsDId'],PDO::PARAM_INT);
	if($stmt_detail->execute())
	{
		$resultLawsuitDetails = $stmt_detail->fetchAll(PDO::FETCH_ASSOC);
		$customerArray=explode (",", $resultLawsuitDetails[0]['custName']);
		$opponentArray=explode (",", $resultLawsuitDetails[0]['OpponentsName']);
		$stmt_detail->closeCursor();
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
	
?>
<style>
	.modal-lg, .modal-xl {
	--bs-modal-width: 72%;
	}
	#print{
		/* width:50%; */
		display: block;
	}
	
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">
		
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header ">
				<h5> <?php echo set_value('addNewPayment'); ?></h5>
				
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			
			<div class="col-md-3">
				<?php include_once('getLawsuitDetailsData.php'); ?>
			</div>
			
			<div class="col-md-9">
				
				<div class="row">
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
				<?php 
					$top=10;
					$left=50;
					include_once('loader.php'); 
				?>
				
				<div class="row mt-3">
					<div class="col-lg-4 col-sm-6 col-12">
						<a class="btn btn-primary w-100" id='addButton' ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i> <?php echo set_value("addNewPayment"); ?></a>
					</div>
					
					
					<div class="col-lg-4 col-sm-6 col-12">
						<a class="btn btn-success w-100" id='UpdatePayment' ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('addNewContractAmount'); ?></a>
					</div>
					
					<div class="col-lg-4 col-sm-6 col-12">
						<a class="btn btn-danger w-100" id='DeleteLawsuit' ><i class="fa fa-minus-circle me-2" aria-hidden="true"></i><?php echo set_value('deleteLawsuit'); ?></a>
					</div>

					<!-- <div class="col-lg-3 col-sm-6 col-12">
						<a class="btn btn-info" id='' ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('deleteLawsuit'); ?></a>
					</div> -->
					
					<?php /*
						<div class="col-lg-4 col-sm-6 col-12">
						<div class="form-group booking-option">
						<label><?php echo set_value('paidStatus'); ?></label>
						<a class="btn">
						<div class="status-toggle">
						
						<input id="paidStatus" class="check" type="checkbox">
						<label for="paidStatus" class="checktoggle checkbox-bg">checkbox</label>
						</div>
						</a>
						</div>
						</div>
						
						<div class="col-lg-4 col-sm-6 col-12">
						<div class="form-group booking-option">
						<label><?php echo set_value('ispaidStatusAll'); ?></label>
						<a class="btn">
						<div class="status-toggle">
						
						<input id="ispaidStatusAll" class="check" type="checkbox">
						<label for="ispaidStatusAll" class="checktoggle checkbox-bg">checkbox</label>
						</div>
						</a>
						</div>
						</div>
						
					*/ ?>
					
					
				</div>
				<br/>
				<br/>
				<div class="row">
					<div class="col-sm-12">
						<div class="card-table"> 
							<div class="card-body">
								<ul class="nav nav-tabs nav-justified" role="tablist">
									<li class="nav-item" role="presentation"><a class="nav-link active" href="#basictab1" data-bs-toggle="tab" aria-selected="true" role="tab"><?php echo set_value('paymentDetails'); ?> </a></li>
									<li class="nav-item" role="presentation"><a class="nav-link" href="#basictab2" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1"><?php echo set_value('contractDetails'); ?></a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active show" id="basictab1" role="tabpanel">
										<div class="table-responsive">
											<table class="table table-stripped table-hover datatable" id='setData'>
												<thead class="thead-light">
													<tr>
														<!-- class="text-end"-->
														<th><?php echo set_value('action'); ?></th>
														<th>#</th>
														<th><?php echo set_value('lsMasterCode'); ?></th>
														<th><?php echo set_value('stage'); ?></th>
														<th><?php echo set_value('lawsuitId'); ?></th>
														<th><?php echo set_value('invoiceNumber'); ?></th>
														<th><?php echo set_value('paymentDate'); ?></th>
														<th><?php echo set_value('paymentMode'); ?></th>												   
														<th><?php echo set_value('paidAmount'); ?></th>
														<th><?php echo set_value('remarks'); ?></th>
														<th><?php echo set_value('paidStatus'); ?></th>
														
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane" id="basictab2" role="tabpanel">
										<table class="table table-stripped table-hover datatable" id='setDataContract'>
											<thead class="thead-light">
												<tr>
													<th><?php echo set_value('action'); ?></th>
													<th>#</th>
													<th><?php echo set_value('stage'); ?></th>
													<th><?php echo set_value('paymentAmount'); ?></th>
													<th><?php echo set_value('taxValueAmount'); ?></th>
													<th><?php echo set_value('contractAmountIncludingTax'); ?></th>												   
													<th><?php echo set_value('contractFile'); ?></th>												   
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>	
								
								<input type='hidden' id="lsDId" value="<?php echo $_POST['lsDId']; ?>" >
								<input type='hidden' id="lsMId" value="<?php echo $_POST['lsMId']; ?>" >
							</div>
						</div>
					</div>
					
				</div>	
			</div>		
		</div>		
		
		
	</div>
</div>
<!-- /Page Wrapper -->

<!-- Modal -->
<div class="modal fade" id="LawsuitPaymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='form' action='javascript:add();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value('createPayment'); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="date" class="form-label"><?php echo set_value('stage'); ?><span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id='lsStage' required>
										<option value=""> <?php echo set_value("select"); ?></option>
										<?php include('dropdown_LawsuitStages.php'); ?>
									</select>
								</div>
							</div>
						</div>
						
						
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="date" class="form-label"><?php echo set_value('paymentDate'); ?><span class="text-danger"> * </span></label>
									<input type="date" class="form-control form-control-sm" id="date" placeholder="dd/mm/yyyy" required onkeydown="return false;">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="mode" class="form-label"><?php echo set_value('paymentMode'); ?> <span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id='mode' required>
										<option value=""> <?php echo set_value("select"); ?></option>
										<?php include('dropdown_PaymentMode.php'); ?>
									</select>
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="amount" class="form-label"><?php echo set_value('paymentAmount'); ?> <span class="text-danger"> * </span></label>
									<input type="number" class="form-control form-control-sm" id="amount" placeholder="<?php echo set_value('paymentAmount'); ?>" required>
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="invoiceNumber" class="form-label"><?php echo set_value('invoiceNumber'); ?></label>
									<input type="text" class="form-control form-control-sm" id="invoiceNumber"  placeholder="<?php echo set_value('invoiceNumber'); ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label class="d-block"><?php echo set_value('paymentPaidFor'); ?><span class="text-danger"> * </span></label>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="paidStatus" id="paidStatus" value="1">
								<label class="form-check-label" for="paidStatus"><?php echo set_value('paidAmount'); ?></label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="paidStatus" id="ispaidStatusAll" value="2">
								<label class="form-check-label" for="ispaidStatusAll"><?php echo set_value('ispaidStatusAll'); ?></label>
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="mb-4">
								<div class="form-group">
									<label for="remarks" class="form-label"><?php echo set_value('remarks'); ?></label>
									<input type="text" class="form-control form-control-sm" id="remarks" placeholder="<?php echo set_value('remarks'); ?>">
								</div>
							</div>
						</div>
						
					</div>
					
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value('close'); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('addPayment'); ?>" />
					<input type='hidden' value='0' id='id'>
				</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->

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
									<input type="date" class="form-control form-control-sm" id="form_invoice_date" value="<?php echo date('Y-m-d'); ?>" required onkeydown="return false;">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="lawsuit_code" class="form-label"><?php echo set_value('lsMasterCode'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="lawsuit_code" value="<?php echo $resultLawsuitDetails[0]['ls_code']; ?>" disabled>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="lawsuit_reference_no" class="form-label"><?php echo set_value('referenceNo'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="lawsuit_reference_no" value="<?php if(empty($resultLawsuitDetails[0]['referenceNo'])) echo "-"; else echo $resultLawsuitDetails[0]['referenceNo']; ?>" disabled>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="lawsuit_number" class="form-label"><?php echo set_value('lawsuitId'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="lawsuit_number" value="<?php if(empty($resultLawsuitDetails[0]['lawsuitId'])) echo "-"; else echo $resultLawsuitDetails[0]['lawsuitId']; ?>" disabled>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="type" class="form-label"><?php echo set_value('type'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="type" value="<?php echo $resultLawsuitDetails[0]['lsTypeName_'.$language]; ?>" disabled>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="state" class="form-label"><?php echo set_value('state'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="state" value="<?php echo $resultLawsuitDetails[0]['lsStateName_'.$language]; ?>" disabled>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="mb-4">
								<div class="form-group">
									<label for="stage" class="form-label"><?php echo set_value('stage'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="stage" value="<?php echo $resultLawsuitDetails[0]['lsStagesName_'.$language]; ?>" disabled>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value('close'); ?></button>&nbsp; 
					<input class="btn btn-primary" type="button" onclick="saveInvoice()" value="<?php echo set_value('save'); ?>" />
					<input class="btn btn-primary" style="margin-left: 5px;" type="submit" id='submit' value="<?php echo set_value('print'); ?>" />
					<input type='hidden' value='0' id='id'>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="LawsuitAmountModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='formContract' action='javascript:updateContract();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value('editContractAmount'); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="stage" class="form-label"><?php echo set_value('stage'); ?><span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id='contract_stage' required>
										<option value=""> <?php echo set_value("select"); ?></option>
										<?php include('dropdown_LawsuitStagesContract.php'); ?>
									</select>
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="amountContract" class="form-label"><?php echo set_value('paymentAmount'); ?> <span class="text-danger"> * </span></label>
									<input type="number" class="form-control form-control-sm" id="amountContract" placeholder="<?php echo set_value('paymentAmount'); ?>" step="0.001" required>
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="taxValue" class="form-label"><?php echo set_value("taxValue"); ?></label>
									<input type="number" class="form-control form-control-sm" id="taxValue" step="0.001" value="5">
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="taxValueAmount" class="form-label"><?php echo set_value("taxValueAmount"); ?></label>
									<input type="number" class="form-control form-control-sm" id="taxValueAmount" disabled step="0.001">
								</div>
							</div>
						</div>
						
					</div>
					<div class="row">	
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="contractAmountIncludingTax" class="form-label"><?php echo set_value("contractAmountIncludingTax"); ?>%</label>
									<input type="text" class="form-control form-control-sm" id="contractAmountIncludingTax" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['totalContractAmount']; ?>"  step="0.001" value="0.00" disabled>
								</div>
							</div>
						</div>
						
						<div class="col-md-4" id='divIdCopy'>
							<div class="mb-3">
								<div class="form-group">
									<label for="idCopy" class="form-label"><?php echo set_value("contractFile"); ?></label>
									<fieldset id="contractFileFieldset">	
										<input type="file" class="form-control form-control-sm image_check" id="contractFile">
									</fieldset>
									<span><?php echo set_value("uploadMaximumLimit"); ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-2 col-lg-2 col-sm-2">
							<label for="search" class="form-label">&nbsp;</label>
							<button type="button" class="btn btn-primary form-control" id='print'>
								<?php echo set_value("printContract"); ?>
							</button>
						</div>
					</div>
					<?php 
					/*	
						$qry="SELECT torsId, tors_en, tors_ar FROM tbl_tors c WHERE c.isActive=1";
						$stmt=$dbo->prepare($qry);
						if($stmt->execute())
						{
							$resultEdit = $stmt->fetchAll(PDO::FETCH_ASSOC);
							foreach($resultEdit as $row)
							{
								////$torsId=$row['torsId'];
								$tors_en=$row['tors_en'];
								$tors_ar=$row['tors_ar'];
							}
							/////print_r($resultEdit);
						}
						else 
						{
							$errorInfo = $stmt->errorInfo();
							exit($json =$errorInfo[2]);
						}
						/*
							<input type="hidden" id="tors_en" value="<?php if(isset($tors_en)) echo $tors_en; ?>"  />
							<input type="hidden" id="tors_ar" value="<?php if(isset($tors_ar)) echo $tors_ar; ?>"  />
						*/
						
					?>
					
					<div class="row">
						<div class="col-md-12">
							<div class="card-body pb-0">
								<ul class="nav nav-tabs nav-justified" role="tablist">
									<li class="nav-item" role="presentation"><a class="nav-link active" href="#basictab1Contract" data-bs-toggle="tab" aria-selected="true" role="tab"><?php echo set_value('ContractTermsAr'); ?></a></li>
									<li class="nav-item" role="presentation"><a class="nav-link" href="#basictab2Contract" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1"><?php echo set_value('ContractTermsEn'); ?></a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active show" id="basictab1Contract" role="tabpanel">
										<textarea class="summernote form-control" id="ContractTermsAr" placeholder="Description"><?php if(isset($tors_ar)) echo $tors_ar; ?></textarea>
										<div id="printContent" style="display: none;"></div>
									</div>
									<div class="tab-pane" id="basictab2Contract" role="tabpanel">
										<textarea class="summernote form-control" id="ContractTermsEn" placeholder="Description"><?php if(isset($tors_en)) echo $tors_en; ?></textarea>
									</div>
									
								</div>
							</div>
						</div>
						
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value('close'); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
					<input type='hidden' value='0' id='idContract'>
				</div>
			</form>
			
			<input type='hidden' value="<?php echo $_SESSION['invoice_no']; ?>" id='invoice_number'>
			<input type='hidden' value="<?php echo date('Y-m-d'); ?>" id='invoice_date'>
		</div>
	</div>
</div><!-- /.modal -->

<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('deletePayment'); ?></h3>
					<p><?php echo set_value('areYouSureWantTodelete?'); ?></p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary paid-continue-btn" id="del_button" onclick="del();"><?php echo set_value('delete'); ?></button>
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

<?php ///// include_once('MessageModalShow.php'); ?>
<?php include_once('footer.php');
	include_once('generateHTML_docZip.php');
?>
<!-- <script src="js_custom/LawsuitDetailPaymentInvoice.js"></script> -->
<script src="js_custom/LawsuitDetailPayment.js"></script>
<script src="js_custom/imageupload.js"> </script>	