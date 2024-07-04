<?php 
	
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	WHERE r.menuid in(1,8)"; 
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
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
	////echo set_value("add_new_customer");
include_once('headerOld.php'); 	
?>

<style>
	h6,.textColor {
		color:red
	}
</style>

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="row">
			<div class="col-sm-12">
			
				<!-- /Page Header -->	
				<div class="page-header">
				<div class="content-page-header">
					<h5><?php echo set_value('addLawsuit'); ?></h5>
					<div class="list-btn">
						<ul class="filter-list">
							<li>
								<a class="btn btn-filters w-auto popup-toggle"><span class="me-2"><i class="fe fe-filter"></i></span>Filter </a>
							</li>
							
							
							<li>
								<div class="dropdown dropdown-action">
									<a href="#" class="btn-filters" data-bs-toggle="dropdown" aria-expanded="false"><span><i class="fe fe-download"></i></span></a>
									<div class="dropdown-menu dropdown-menu-end">
										<ul class="d-block">
											<li>
												<a class="d-flex align-items-center download-item" href="javascript:void(0);" download><i class="far fa-file-pdf me-2"></i>PDF</a>
											</li>
											<li>
												<a class="d-flex align-items-center download-item" href="javascript:void(0);" download><i class="far fa-file-text me-2"></i>CVS</a>
											</li>
										</ul>
									</div>
								</div>														
							</li>
							
							<li>
								<a class="btn btn-primary" onclick="add()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("add_new_customer"); ?></a>
							</li>
							
						</ul>
					</div>
				</div>
			</div>
			<!-- /Page Header -->
			
					
					
					
					
					<div class="card mb-0">
						<div class="card-body pb-0">
							<div class="invoice-card-title">
								<h6><?php echo set_value('customerData'); ?> :</h6>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label for="customer" class="form-label"><?php echo set_value("selectCustomer"); ?>*</label>
									<select class="form-control select" multiple="multiple" id='customer'>
										<?php echo include_once('dropdown_customer.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="customerType" class="form-label"><?php echo set_value("selectCustomerType"); ?>*</label>
									<select class="form-control select" multiple="multiple" id='customerType'>
										<?php echo include_once('dropdown_customerType.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="customerAjective" class="form-label"><?php echo set_value("selectCustomerAdjective"); ?>*</label>
									<select class="form-control select" multiple="multiple" id='customerAjective'>
										<?php echo include_once('dropdown_customerAdjectives.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="idCustomer" class="form-label"><?php echo set_value("idCustomer"); ?></label>
									<input type="file" class="form-control form-control-sm" id="idCustomer">
									<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
								</div>
							</div>	
							<div class="row">
								<div class="col-md-3">
									<label for="nationalAddress" class="form-label"><?php echo set_value("nationalAddressPlaintiff"); ?></label>
									<input type="file" class="form-control form-control-sm" id="nationalAddress">
									<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
								</div>
								
								<div class="col-md-3">
									<label for="idDefendant" class="form-label"><?php echo set_value("idDefendant"); ?></label>
									<input type="file" class="form-control form-control-sm" id="idDefendant">
									<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
								</div>
								
							</div> <br><br>
							<div class="invoice-card-title">
								<h6><?php echo set_value('opponentsData'); ?> </h6>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label for="opponentName" class="form-label"><?php echo set_value("opponentName"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentName" placeholder="<?php echo set_value("opponentName"); ?>">
								</div>
								<div class="col-md-3">
									<label for="opponentPhone" class="form-label"><?php echo set_value("opponentPhone"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentPhone" placeholder="<?php echo set_value("opponentPhone"); ?>">
								</div>
								<div class="col-md-3">
									<label for="opponentNationality" class="form-label"><?php echo set_value("opponentNationality"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentNationality" placeholder="<?php echo set_value("opponentNationality"); ?>">
								</div>
								<div class="col-md-3">
									<label for="opponentAddress" class="form-label"><?php echo set_value("opponentAddress"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentAddress" placeholder="<?php echo set_value("opponentAddress"); ?>">
								</div>
							
							</div>
							<br/>
							<div class="row">
								<div class="col-md-3">
									<label for="opponentLawyer" class="form-label"><?php echo set_value("opponentLawyer"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentLawyer" placeholder="<?php echo set_value("opponentLawyer"); ?>">
								</div>
								<div class="col-md-3">
									<label for="opponentLawyerPhone" class="form-label"><?php echo set_value("opponentLawyerPhone"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentLawyerPhone" placeholder="<?php echo set_value("opponentLawyerPhone"); ?>">
								</div>
							</div>
							<br/>
							<div class="row">
								<div class="col-md-10 textColor" >
									<?php echo set_value("infoModifiedLawsuit"); ?>
								</div>
							</div>
							
							<br/>
							<div class="invoice-card-title">
								<h6><?php echo set_value('lawsuitContractData'); ?> </h6>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label for="lawsuitsType" class="form-label"><?php echo set_value("lawsuits_Type"); ?></label>
									<select class="form-control select" multiple="multiple" id='lawsuitsType'>
										<?php echo include_once('dropdown_lawsuitType.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="opponentLawyer" class="form-label"><?php echo set_value("opponentLawyer"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentLawyer" placeholder="<?php echo set_value("opponentLawyer"); ?>">
								</div>
								<div class="col-md-6">
									<label for="subjectLawsuit" class="form-label"><?php echo set_value("subjectLawsuit"); ?></label>
									<input type="text" class="form-control form-control-sm" id="subjectLawsuit" placeholder="<?php echo set_value("subjectLawsuit"); ?>">
								</div>
							</div>
							<br/>
							<div class="row">
								<div class="col-md-3">
									<label for="stage" class="form-label"><?php echo set_value("stage"); ?>*</label>
									<select class="form-control select" multiple="multiple" id='stage'>
										<?php echo include_once('dropdown_stage.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="state" class="form-label"><?php echo set_value("state"); ?>*</label>
									<select class="form-control select" multiple="multiple" id='state'>
										<?php echo include_once('dropdown_state.php'); ?>
									</select>
								</div>
								<br/>
								<div class="col-md-3">
									<label for="lawsuitLocation" class="form-label"><?php echo set_value("lawsuitLocation"); ?>*</label>
									<input type="text" class="form-control form-control-sm" id="lawsuitLocation" placeholder="<?php echo set_value("lawsuitLocation"); ?>">
								</div>
								<br/>
								<div class="col-md-3">
									<label for="createdAt" class="form-label"><?php echo set_value("created_at"); ?>*</label>
									<input type="date" class="form-control form-control-sm" id="createdAt" placeholder="<?php echo set_value("created_at"); ?>">
								</div>
							
							</div>
							<br/><br/>
							<div class="row">
								<div class="col-md-3">
									<label for="amountContract" class="form-label"><?php echo set_value("amountContract"); ?></label>
									<input type="text" class="form-control form-control-sm" id="amountContract" placeholder="<?php echo set_value("amountContract"); ?>">
								</div>
								<div class="col-md-3">
									<label for="contractAmountIncludingTax" class="form-label"><?php echo set_value("contractAmountIncludingTax"); ?>%</label>
									<input type="text" class="form-control form-control-sm" id="contractAmountIncludingTax" placeholder="<?php echo set_value("contractAmountIncludingTax"); ?>">
								</div>
								<div class="col-md-3">
									<label for="taxValue" class="form-label"><?php echo set_value("taxValue"); ?>%</label>
									<input type="text" class="form-control form-control-sm" id="taxValue" placeholder="<?php echo set_value("taxValue"); ?>">
								</div>
								<div class="col-md-3">
									<label for="percent" class="form-label"><?php echo set_value("percent"); ?>%</label>
									<input type="text" class="form-control form-control-sm" id="percent" placeholder="<?php echo set_value("percent"); ?>">
								</div>
							</div>
							<br/><br/><br/>
							
							<div class="invoice-card-title">
								<h6><?php echo set_value('contractTerms'); ?> :</h6>
							</div>
							</div>
							</div>
							<div class="row">
								<!-- Editor -->
								<div class="col-md-12">	
									<div class="card">
										<div class="card-body">
											<span class='textColor'><?php echo set_value('ContractTermsAr'); ?> </span>
											<div id="summernote"></div>
										</div>
									</div>
								</div>
								<!-- /Editor -->
							</div>
							<div class="row">
								<!-- Editor -->
								<div class="col-md-12">	
									<div class="card">
										<div class="card-body">
											<span class='textColor'><?php echo set_value('ContractTermsEn'); ?> </span>
											<div id="summernote1"></div>
										</div>
									</div>
								</div>
								<!-- /Editor -->
							</div>
						</div>
					</div>
				</div>
					
				</div>
			</div>
		</div>
		
		
	</div>
</div>
<!-- /Page Wrapper -->

<!-- /Customer Modal -->

<?php include_once('CustomerModal.php'); ?>

<?php include_once('MessageModalShow.php'); ?>
</div>
<!-- /Main Wrapper -->
	
<?php include_once('footerOld.php'); ?>
<script src="assets/js/custom/Customer.js"> </script>
