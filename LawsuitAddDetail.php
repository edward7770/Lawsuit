<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	/////print_r($_POST);
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
		
	$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	if($pageName=="LawsuitAddDetail")
		$pageName="LawsuitAdd";
	
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName`=:pageName"; 
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	///print_r($result);
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
	if(isset($_POST['mId']) && !empty($_POST['mId']))
	{
		$qry="SELECT ld.lsStagesId,ld.lsStateId, ld.lsSubject, ld.lsLocation,ld.`contractAr`,ld.`contractEn`,
			ld.`totalContractAmount`,ld.`taxValue`,ld.`notes`,ld.`amountContract`,
			GROUP_CONCAT(DISTINCT lol.`oppoLawyerId`) AS oppoLawyerId,
			GROUP_CONCAT(DISTINCT lo.`opponentId`) AS opponentId,ld.referenceNo 
			FROM `tbl_lawsuit_details` ld 
			INNER JOIN `tbl_lawsuit_oppolawyer` lol ON lol.`lsDetailsId`=ld.`lsDetailsId`
			INNER JOIN `tbl_lawsuit_opponents` lo ON lo.`lsDetailsId`=ld.`lsDetailsId`
			WHERE ld.`isActive`=1 AND ld.`lsMasterId`=:lsMasterId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId",$_POST['mId'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$resultLawsuit = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	
	
	
	
	////echo set_value("add_new_customer");
		
	
?>
	
<style>
	h6,.textColor {
	color:red
	}
	/*
    .blue-color {
        color:blue;
    }
    .teal-color {
        color:teal;
    }
     
    .yellow-color {
    color:yellow;
    }
    */
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
		<div class="row">
			<div class="col-sm-12">
				
				<!-- /Page Header -->	
				<div class="page-header">
					<div class="content-page-header">
						<h5><?php echo set_value('addLawsuit'); ?></h5>
					</div>
				</div>
			
					<div class="card mb-0">
						<div class="card-body pb-0">
							<div class="invoice-card-title">
								<h6><?php echo set_value('customerData'); ?> :</h6>
							</div>
							<form action="javascript:addMoreCustomer();">
								<div class="row">
									<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="customer class="form-label"><?php echo set_value("selectCustomer"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" id='customer'  >
											<option value=""><?php echo set_value("select"); ?></option>
											<?php echo include_once('dropdown_customer.php'); ?>
										</select>
									</div>
									</div>
									
									<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="customerType" class="form-label"><?php echo set_value("selectCustomerType"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" id='customerType' >
											<option value=""><?php echo set_value("select"); ?></option>
											<?php echo include_once('dropdown_customerType.php'); ?>
										</select>
									</div>
									</div>
									
									<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
									
										<label for="customerAjective" class="form-label"><?php echo set_value("selectCustomerAdjective"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" id='customerAjective' >
											<option value=""><?php echo set_value("select"); ?></option>
											<?php echo include_once('dropdown_customerAdjectives.php'); ?>
										</select>
									</div>
								</div>
								</div>

								
								<div class="row">
									<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="idCustomer" class="form-label"><?php echo set_value("idCustomer"); ?></label>
										<fieldset id="idCustomerFieldset">	
											<input type="file" class="form-control form-control-sm image_check" id="idCustomer">
										</fieldset>
										<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
									</div>
									
									</div>
									
									<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="nationalAddress" class="form-label"><?php echo set_value("nationalAddressPlaintiff"); ?></label>
										<fieldset id="nationalAddressFieldset">
											<input type="file" class="form-control form-control-sm image_check" id="nationalAddress">
										</fieldset>
										<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
									</div>

									</div>	
									
									<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
																		
										<label for="idDefendant" class="form-label"><?php echo set_value("idDefendant"); ?></label>
										<fieldset id="idDefendantFieldset">	
											<input type="file" class="form-control form-control-sm image_check" id="idDefendant">
										</fieldset>
										<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
									</div>
									</div>
									
									
									<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
									
										<label class="form-label"></label>
										<button class="btn btn-primary" type='submit'><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("addMoreCustomer"); ?></button>
									</div>
									</div>
								</div>
							</form>
							
							<div class="row">
								<div class="col-sm-12">
									<div class="card-table">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-center table-hover" id="customerTable" cellspacing="0">
													<thead class="thead-light">
														<tr>
															<th>#</th>
															<th><?php echo set_value('customer'); ?></th>
															<th><?php echo set_value('customers_types'); ?></th>
															<th><?php echo set_value('CustomerAdjectives'); ?></th>
															<th><?php echo set_value('idCustomer'); ?></th>
															<th><?php echo set_value('nationalAddressPlaintiff'); ?></th>
															<th><?php echo set_value('idDefendant'); ?></th>
															<!--<th><?php ///echo set_value('action'); ?></th> -->
														</tr>
													</thead>
													<tbody id='setData'>
													<?php
													if(isset($_POST['mId']) && !empty($_POST['mId']))
													{
															$qry="SELECT c.customerName, ct.typeName_en AS typeName, cad.adjectiveName_en AS adjectiveName, 
															lc.`idCustomerfilePath`,lc.`nationalAddfilePath` ,lc.`idDefendantfilePath` FROM `tbl_lawsuit_details` d 
															LEFT JOIN `tbl_lawsuit_customers` lc ON lc.`lsDetailsId`=d.`lsDetailsId`
															INNER JOIN `tbl_customers` c ON c.`customerId`=lc.`customerId`
															INNER JOIN `tbl_customertypes` ct ON ct.`custTypeId`=lc.`custTypeId`
															INNER JOIN `tbl_customeradjectives` cad ON cad.`custAdjectiveId`=lc.`custAdjectiveId`
															WHERE d.`isActive`=1 AND d.`lsMasterId`=:lsMasterId";
															$stmt=$dbo->prepare($qry);
															$stmt->bindParam(":lsMasterId",$_POST['mId'],PDO::PARAM_STR);
															if($stmt->execute())
															{
																$resultCustomer = $stmt->fetchAll(PDO::FETCH_ASSOC);
															}
															else 
															{
																$errorInfo = $stmt->errorInfo();
																exit($json =$errorInfo[2]);
															}
															$si=1;
															$btn_fileEmpty='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
															foreach($resultCustomer as $row)
															{ ?>
																<tr>	
																	<td><?php echo $si; ?> </td>
																	<td><?php echo $row['customerName']; ?> </td>
																	<td><?php echo $row['typeName']; ?> </td>
																	<td><?php echo $row['adjectiveName']; ?> </td>
																	<td><?php if(empty($row['idCustomerfilePath'])) echo $btn_fileEmpty; else { ?> <a href="<?php echo $row['idCustomerfilePath']; ?>" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a> <?php } ?> </td>
																	<td><?php if(empty($row['nationalAddfilePath'])) echo $btn_fileEmpty; else { ?> <a href="<?php echo $row['nationalAddfilePath']; ?>" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a> <?php } ?> </td>
																	<td><?php if(empty($row['idDefendantfilePath'])) echo $btn_fileEmpty; else { ?> <a href="<?php echo $row['idDefendantfilePath']; ?>" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a> <?php } ?> </td>
																	<!--<td><a href="#" class="btn-action-icon" onclick="DeleteRowFunctionCustomer(this)" ><span><i class="fe fe-trash-2 fa-2x red-color"></i></span></a></td> -->
																</tr>
															<?php 
															$si++;	
															}
														
												
													}
													?>	
													</tbody>
													
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<BR> 	
								</div>
						</div>
<br><br>

					<div class="card mb-0">
						<div class="card-body pb-0">
							<div class="invoice-card-title">
								<h6><?php echo set_value('opponentsData'); ?> </h6>
									<div class="row">
										<div class="col-lg-5 col-md-6 col-sm-12">
											<div class="form-group">
														<label for="opponent" class="form-label"><?php echo set_value("selectOpponent"); ?><span class="text-danger"> * </span></label>
														<select class="form-control js-example-basic-single form-small select" multiple="multiple" id='opponent'>
														</select>
											</div>
										</div>
										<div class="col-lg-1 col-md-6 col-sm-12  align-self-center">
											<a class="btn btn-primary form-plus-btn" data-bs-toggle="modal" data-bs-target="#opponentModal"><i class="fas fa-plus-circle"></i></a>										
										</div>
										
										<div class="col-lg-5 col-md-6 col-sm-12">
											<div class="form-group">
												<label for="lawyer" class="form-label"><?php echo set_value("opponentLawyer"); ?><span class="text-danger"> * </span></label>
												<select class="form-control js-example-basic-single form-small select" multiple="multiple" id='lawyer'>
												</select>
											</div>
										</div>
										<div class="col-lg-1 col-md-6 col-sm-12  align-self-center">
												<a class="btn btn-primary form-plus-btn" data-bs-toggle="modal" data-bs-target="#layerModal"><i class="fas fa-plus-circle"></i></a>
										</div>
									</div>
							</div>
							<BR>							
							<!-- <div class="row">
								<div class="col-md-10 textColor" >
									<?php echo set_value("infoModifiedLawsuit"); ?>
								</div>-->
							</div> 
							</div>
							
							<br><br>
							
							<div class="card mb-0">
						<div class="card-body pb-0">
							
							<div class="invoice-card-title">
								<h6><?php echo set_value('lawsuitContractData'); ?> </h6>
							</div>
							<div class="row">
									<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
								
									<label for="lawsuitsType" class="form-label"><?php echo set_value("lawsuits_Type"); ?><span class="text-danger"> * </span></label>
									<select class="form-control select" id='lawsuitsType'>
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_lawsuitType.php'); ?>
									</select>
								</div>
								</div>
								
											<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
								
									<label for="state" class="form-label"><?php echo set_value("state"); ?><span class="text-danger"> * </span></label>
									<select class="form-control select" id='state'>
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_state.php'); ?>
									</select>
								</div>
								</div>
								
											<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
									<label for="stage" class="form-label"><?php echo set_value("stage"); ?><span class="text-danger"> * </span></label>
									<select class="form-control select" id='stage'>
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_stage.php'); ?>
									</select>
								</div>
								</div>
								
								</div>
							<div class="row">
								
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
									<label for="subjectLawsuit" class="form-label"><?php echo set_value("subjectLawsuit"); ?></label>
									<input type="text" class="form-control form-control-sm" id="subjectLawsuit" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['lsSubject']; ?>" placeholder="<?php echo set_value("subjectLawsuit"); ?>">
								</div>
								</div>
								
								
									<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
								
									<label for="lawsuitLocation" class="form-label"><?php echo set_value("lawsuitLocation"); ?><span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="lawsuitLocation" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['lsLocation']; ?>" placeholder="<?php echo set_value("lawsuitLocation"); ?>">
								</div>
								</div>
								
								<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="referenceNo" class="form-label"><?php echo set_value("referenceNo"); ?></label>
									<input type="text" class="form-control form-control-sm" id="referenceNo" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['referenceNo']; ?>" placeholder="<?php echo set_value("referenceNo"); ?>" placeholder="<?php echo set_value("referenceNo"); ?>">
								</div>
							</div>
									
							</div>
						
							<div class="row">
							<div class="col-lg-3 col-md-6 col-sm-12">
									<div class="form-group">
								
									<label for="createdAt" class="form-label"><?php echo set_value("created_at"); ?><span class="text-danger"> * </span></label>
									<input type="date" class="form-control form-control-sm" id="createdAt" value="<?php if(isset($_POST['lsCreatedAt'])) echo $_POST['lsCreatedAt']; ?>" placeholder="<?php echo set_value("created_at"); ?>">
								</div>
								
							</div>
									<div class="col-lg-3 col-md-6 col-sm-12">
									<div class="form-group">
									<label for="amountContract" class="form-label"><?php echo set_value("amountContract"); ?></label>
									<input type="number" class="form-control form-control-sm" id="amountContract" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['amountContract']; ?>" placeholder="<?php echo set_value("amountContract"); ?>">
								</div>
								</div>
									<div class="col-lg-3 col-md-6 col-sm-12">
									<div class="form-group">
									<label for="taxValue" class="form-label"><?php echo set_value("taxValue"); ?>%</label>
									<input type="number" class="form-control form-control-sm" id="taxValue" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['taxValue']; ?>" placeholder="<?php echo set_value("taxValue"); ?>">
								</div>
								
								</div>
								
								<div class="col-lg-3 col-md-6 col-sm-12">
									<div class="form-group">
									<label for="contractAmountIncludingTax" class="form-label"><?php echo set_value("contractAmountIncludingTax"); ?>%</label>
									<input type="text" class="form-control form-control-sm" id="contractAmountIncludingTax" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['totalContractAmount']; ?>"  step="0.01" value="0.00" disabled>
								</div>
								</div>
								
							
							
							</div>
				
							</div>
							</div>
							
								<br><br>
						<div class="card mb-0">
							<div class="card-body pb-0">
								<div class="invoice-card-title">
									<h6><?php echo set_value('contractTerms'); ?> :</h6>
								</div>
							
								<div class="row"><h6><?php echo set_value('ContractTermsAr'); ?> :</h6>
									<div class="col-md-12">	
									   <textarea class="summernote form-control" id="ContractTermsAr" placeholder="Description"><?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['contractAr']; ?></textarea>
									</div>
								</div>
					
								<br/>
						
								<div class="invoice-card-title">
										<h6><?php echo set_value('contractTerms'); ?> :</h6>
								</div>
							
								<div class="row">
									<div class="col-md-12">	
										<h6><?php echo set_value('ContractTermsEn'); ?> :</h6>
											<textarea class="summernote form-control" id="ContractTermsEn" placeholder="Description"><?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['contractEn']; ?></textarea>
									</div>
								</div>
					
								<br>
						
							</div>
						</div>
							<br><br>
							<div class="card mb-0">
						<div class="card-body pb-0">
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<h6><?php echo set_value("notes"); ?></h6>
								<br>
								<textarea class="form-control" id="note" rows="3"><?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['notes']; ?></textarea>
							</div>
						</div>
					</div>
					
					</div>
					</div>
						<br><br>
					<div class="add-customer-btns text-end ">
						<a href="#" type="reset" class="btn customer-btn-cancel">Cancel</a>
						<a href="#" type="submit" id='add' class="btn customer-btn-save"><?php echo set_value('add'); ?></a>
					</div>
				<!--</form> -->
				
				<fieldset id="CustomerUploadedFiles" style='display:none'>
					<!--
					<input type="file" id="idCustomerImage1">
					<input type="file" id="nationalAddressImage1">
					<input type="file" id="idDefendantImage1">
					-->
					
				</fieldset>
					<input type="hidden" id="typeId" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['lsTypeId']; ?>">
					<input type="hidden" id="stateId" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['lsStagesId']; ?>">
					<input type="hidden" id="stageId" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['lsStateId']; ?>">
					<input type="hidden" id="opponentId" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['opponentId']; ?>">
					<input type="hidden" id="lawyerId" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['oppoLawyerId']; ?>">
				</div>
		</div>
	</div>
	
</div>


<!-- /Customer Modal -->

<!-- sample modal content -->
<div class="modal fade" id="opponentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form id='opponentForm' action='javascript:addOpponent();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value("createNewOponent"); ?></h4>
					
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentName" class="form-label"><?php echo set_value("opponentName"); ?><span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="opponentName" placeholder="<?php echo set_value("opponentName"); ?>" required>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentPhone" class="form-label"><?php echo set_value("opponentPhone"); ?><span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="opponentPhone" placeholder="<?php echo set_value("opponentPhone"); ?>" required>
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentNationality" class="form-label"><?php echo set_value("opponentNationality"); ?></label>
								<input type="text" class="form-control form-control-sm" id="opponentNationality" placeholder="<?php echo set_value("opponentNationality"); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentAddress" class="form-label"><?php echo set_value("opponentAddress"); ?></label>
								<input type="text" class="form-control form-control-sm" id="opponentAddress" placeholder="<?php echo set_value("opponentAddress"); ?>">
							</div>
						</div>
					</div>	
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
					<input type='hidden' id='id' />
				</div>
				</form>
				
			</div>
		</div>
	</div><!-- /.modal -->
	
<!-- sample modal content -->
<div class="modal fade" id="layerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form id='opponentForm' action='javascript:addLayer();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value("createNewOponent"); ?></h4>
					
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentLawyer" class="form-label"><?php echo set_value("opponentLawyer"); ?></label>
								<input type="text" class="form-control form-control-sm" id="opponentLawyer" placeholder="<?php echo set_value("opponentLawyer"); ?>" required>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentLawyerPhone" class="form-label"><?php echo set_value("opponentLawyerPhone"); ?></label>
								<input type="text" class="form-control form-control-sm" id="opponentLawyerPhone" placeholder="<?php echo set_value("opponentLawyerPhone"); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
					<input type='hidden' id='id' />
				</div>
				</form>
				
			</div>
		</div>
	</div><!-- /.modal -->
	
<?php include_once('MessageModalShow.php'); ?>

<!-- /Main Wrapper -->

<?php include_once('footer.php'); ?>
<script src="assets/js/custom/LawsuitAdd.js"> </script>
<script src="assets/js/custom/imageupload.js"> </script>

<script>
	$(document).ready(function() {
		getDropDown('dropdown_Opponent','opponent');
		getDropDown('dropdown_Lawyer','lawyer');
		$('#lawsuitsType').val($('#typeId').val());
		$('#lawsuitsType').select2({ });
		$('#state').val($('#stateId').val());
		$('#state').select2({ });
		$('#stage').val($('#stageId').val());
		$('#stage').select2({ });
		
		setTimeout(function(){
			opponentId=$('#opponentId').val().split(',');
			$('#opponent').val(opponentId).change();
			lawyerId=$('#lawyerId').val().split(',');
			$('#lawyer').val(lawyerId).change();
		},500);
		//$("#ContractTermsEn").summernote('code', ContractTermsEn);
		
	});
</script>


