<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	/////print_r($_POST);
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	if($pageName=="LawsuitEdit")
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
	if(isset($_POST['lsMId'],$_POST['lsDId']) && !empty($_POST['lsMId']))
	{
		$columns="";
		if(!isset($_POST['lsCode']))
		{
			$columns="ld.lsStagesId,ld.lsTypeId,ld.lsStateId,";
		}
		$qry="SELECT $columns ld.lslocationId,ld.notes,ld.referenceNo , l.empId, ld.lawsuitId, ld.lsDate
		FROM tbl_lawsuit_details ld 
		INNER JOIN tbl_lawsuit_lawyer l ON l.lsDetailId=ld.lsDetailsId AND l.isActive=1
		WHERE ld.isActive=1 AND ld.lsMasterId=:lsMasterId AND ld.lsDetailsId=:lsDetailsId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$resultLawsuit = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
		////print_r($resultLawsuit);
	}
	else 
	{
		exit('<script>window.location.replace("Lawsuit.php")</script>');
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
						<h5><?php if(isset($_POST['lsCode'])) echo set_value('addNewLawsuitStage'); else echo set_value('editLawsuit'); ?></h5>
					</div>
				</div>
				
				<div class="card mb-0">
					<div class="card-header">
						<h6 class="card-title"><?php echo set_value('customerData'); ?></h6>
					</div>
					<div class="card-body pb-0">
						<form action="javascript:addMoreCustomer();">
							<?php if(isset($_POST['lsCode']) && !empty($_POST['lsCode'])) {
								$lsCode=1;
								?>
								<div class="row">
									<div class="col-lg-4 col-md-6 col-sm-12">
										<div class="form-group">
											<label for="customer class="form-label"><?php echo set_value("lsMasterCode"); ?></label>
											<input type="text" class="form-control form-control-sm" id="lsMasterCode" value="<?php echo $_POST['lsCode']; ?>" disabled>
										</div>
									</div>
								</div>
							<?php } ?>
						
							<div class="row">
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="customer class="form-label"><?php echo set_value("selectCustomer"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" id='customer' required>
											<option value=""><?php echo set_value("select"); ?></option>
											<?php echo include_once('dropdown_customer.php'); ?>
										</select>
									</div>
								</div>
								
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="customerType" class="form-label"><?php echo set_value("selectCustomerType"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" id='customerType' disabled>
											<option value=""><?php echo set_value("select"); ?></option>
										</select>
									</div>
								</div>
								
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										
										<label for="customerAjective" class="form-label"><?php echo set_value("selectCustomerAdjective"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" id='customerAjective' required>
											<option value=""><?php echo set_value("select"); ?></option>
											<?php echo include_once('dropdown_customerAdjectives.php'); ?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="row">
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
														<th><?php echo set_value('action'); ?></th>
													</tr>
												</thead>
												<tbody id='setData'>
													<?php
														if(isset($_POST['lsMId']) && !empty($_POST['lsMId']))
														{
															/////-- ,lc.idCustomerfilePath,lc.nationalAddfilePath ,lc.idDefendantfilePath 
															
															$qry="SELECT lc.lsCustomerId,lc.customerId ,c.customerName_en AS customerName ,lc.custTypeId, ct.typeName_en AS typeName, lc.custAdjectiveId,cad.adjectiveName_en AS adjectiveName
															FROM tbl_lawsuit_details d 
															LEFT JOIN tbl_lawsuit_customers lc ON lc.lsDetailsId=d.lsDetailsId
															INNER JOIN tbl_customers c ON c.customerId=lc.customerId 
															INNER JOIN tbl_customertypes ct ON ct.custTypeId=lc.custTypeId
															INNER JOIN tbl_customeradjectives cad ON cad.custAdjectiveId=lc.custAdjectiveId
															WHERE d.isActive=1 and lc.isActive=1 AND d.lsDetailsId=:lsDetailsId";
															$stmt=$dbo->prepare($qry);
															$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
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
															$btn_fileEmpty='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-1x red-color"></i></span></a>';
															foreach($resultCustomer as $row)
															{ ?>
															<tr>	
																<td id="<?php if(!isset($lsCode)) echo $si; else echo "0"; ?>"><?php echo $si; ?></td>
																<td id="<?php echo $row['customerId']; ?>"><?php echo trim($row['customerName']); ?></td>
																<td id="<?php echo $row['custTypeId']; ?>"><?php echo trim($row['typeName']); ?></td>
																<td id="<?php echo $row['custAdjectiveId']; ?>"><?php echo trim($row['adjectiveName']); ?></td>
																<td><a href="#" class="btn-action-icon" onclick="DeleteRowFunctionCustomerDB(this,<?php echo $row['lsCustomerId'].",".$_POST['lsDId']; ?>)" ><span><i class="fe fe-trash-2 fa-1x red-color"></i></span></a></td>
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
					<div class="card-header">
						<h6 class="card-title"><?php echo set_value('opponentsData'); ?></h6>
					</div>
					
										<div class="card-body pb-0">
				
				
					<form action="javascript:addMoreOpponent();">
					 <div class="row">
										<div class="col-lg-7 col-md-6 col-sm-12">
											<div class="form-group">
												<label for="opponent" class="form-label"><?php echo set_value("selectOpponent"); ?><span class="text-danger"> * </span></label>
												<select class="form-control js-example-basic-single form-small select" id='opponent' required>
												</select>
												<input type="hidden" id="showSelect" value='<?php echo set_value("select"); ?>' >
												
											</div>
										</div>
										<div class="col-lg-5 col-md-6 col-sm-12  align-self-center">
											<div class="form-group">
												<label for="opponent" class="form-label" style="visibility:hidden">test </label>
											<br>
											<a class="btn btn-primary form-plus-btn" data-bs-toggle="modal" data-bs-target="#opponentModal"><i class="fas fa-plus-circle"></i><?php echo set_value("addNewOpponent"); ?></a>										
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label"></label>
												<button class="btn btn-primary" type='submit'><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("addMoreOpponent"); ?></button>
											</div>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="col-md-12">
										<div class="card-table">
											<div class="card-body">
												<div class="table-responsive">
													<table class="table table-center table-hover" id="opponentTable" cellspacing="0">
														<thead class="thead-light">
															<tr>
																<th>#</th>
																<th><?php echo set_value('selectOpponent'); ?></th>
																<th><?php echo set_value('action'); ?></th>
															</tr>
														</thead>
														<tbody id='setDataOpponent'> 
															
															<?php 
																if(isset($_POST['lsMId']) && !empty($_POST['lsMId']))
																{
																	$qry="SELECT lo.`opponentId`, o.oppoName_$language as oppoName FROM tbl_lawsuit_opponents lo 
																		LEFT JOIN `tbl_opponents` o ON o.`opponentId`=lo.`opponentId`
																		WHERE lo.`isActive`=1 AND o.`isActive`=1 AND lo.`lsDetailsId`=:lsDetailsId";
																	$stmt=$dbo->prepare($qry);
																	$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_STR);
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
																	$btn_fileEmpty='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-1x red-color"></i></span></a>';
																	foreach($resultCustomer as $row)
																	{ ?>
																	<tr>	
																		<td id="<?php echo $si; ?>"><?php echo $si; ?> </td>
																		<td id="<?php echo $row['opponentId']; ?>"><?php echo $row['oppoName']; ?> </td>
																		<td><a href="#" class="btn-action-icon" onclick="DeleteRowFunctionOpponent(this);" ><span><i class="fe fe-trash-2 fa-1x red-color"></i></span></a></td>
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
							
								<form action="javascript:addMoreOpponentLawyer();">
									<div class="row">
										<div class="col-lg-7 col-md-6 col-sm-12">
											<div class="form-group">
												<label for="lawyer" class="form-label"><?php echo set_value("opponentLawyer"); ?><span class="text-danger"> * </span></label>
												<select class="form-control js-example-basic-single form-small select" id='lawyer' required>
												</select>
											</div>
										</div>
										<div class="col-lg-5 col-md-6 col-sm-12  align-self-center">
											<div class="form-group">
												<label for="opponent" class="form-label" style="visibility:hidden">test </label>
												<br>
												<a class="btn btn-primary form-plus-btn" data-bs-toggle="modal" data-bs-target="#layerModal"><i class="fas fa-plus-circle"></i><?php echo set_value("addNewOpponentLawyer"); ?></a>										
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label"></label>
												<button class="btn btn-primary" type='submit'><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("addMoreOpponentLawyer"); ?></button>
											</div>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="col-md-12">
										<div class="card-table">
											<div class="card-body">
												<div class="table-responsive">
													<table class="table table-center table-hover" id="opponentLawyerTable" cellspacing="0">
														<thead class="thead-light">
															<tr>
																<th>#</th>
																<th><?php echo set_value('opponentLawyer'); ?></th>
																<th><?php echo set_value('action'); ?></th>
															</tr>
														</thead>
														<tbody id='setDataOpponent'>
															<?php 
																if(isset($_POST['lsMId']) && !empty($_POST['lsMId']))
																{
																	$qry="SELECT lo.`oppoLawyerId`, ol.`oppoLawyerName` FROM tbl_lawsuit_oppolawyer lo 
																		LEFT JOIN `tbl_opponentlawyer` ol ON ol.`oppoLawyerId`=lo.`oppoLawyerId`
																		WHERE lo.`isActive`=1 AND ol.`isActive`=1 AND lo.`lsDetailsId`=:lsDetailsId";
																	$stmt=$dbo->prepare($qry);
																	$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_STR);
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
																	$btn_fileEmpty='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-1x red-color"></i></span></a>';
																	foreach($resultCustomer as $row)
																	{ ?>
																	<tr>	
																		<td id="<?php echo $si; ?>"><?php echo $si; ?> </td>
																		<td id="<?php echo $row['oppoLawyerId']; ?>"><?php echo $row['oppoLawyerName']; ?> </td>
																		<td><a href="#" class="btn-action-icon" onclick="DeleteRowFunctionOpponentLawyer(this);" ><span><i class="fe fe-trash-2 fa-1x red-color"></i></span></a></td>
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
				
				</div>
				</div>
				<!-- end -->
				
			
				<br><br>
				
				<div class="card mb-0">
					<div class="card-header">
						<h6 class="card-title"><?php echo set_value('lawsuitContractData'); ?></h6>
					</div>
					<div class="card-body pb-0">
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
									<label for="lawsuitLawyer" class="form-label"><?php echo set_value("lawsuitLawyer"); ?><span class="text-danger"> * </span></label>
									<select class="js-example-basic-single form-small select" id='lawsuitLawyer'>
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_lawsuitLawyer.php'); ?>
									</select>
								</div>
							</div>
							
							
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="subjectLawsuit" class="form-label"><?php echo set_value("subjectLawsuit"); ?></label>
									<input type="text" class="form-control form-control-sm" id="subjectLawsuit" value="<?php if(isset($resultLawsuit,$resultLawsuit[0]['lsSubject'])) echo $resultLawsuit[0]['lsSubject']; ?>" placeholder="<?php echo set_value("subjectLawsuit"); ?>">
								</div>
							</div>
							
							
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="lawsuitLocation" class="form-label"><?php echo set_value("lawsuitLocation"); ?></label>
									<?php /*<input type="text" class="form-control form-control-sm" id="lawsuitLocation" value="<?php if(isset($resultLawsuit,$resultLawsuit[0]['lslocationId'])) echo $resultLawsuit[0]['lslocationId']; ?>" placeholder="<?php echo set_value("lawsuitLocation"); ?>"> */ ?>
									<select class="js-example-basic-single form-small select" id='lawsuitLocation'>
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_LSLocation.php'); ?>
									</select>
								</div>
							</div>
						</div>	
						<div class="row">
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="referenceNo" class="form-label"><?php echo set_value("referenceNo"); ?></label>
									<input type="text" class="form-control form-control-sm" id="referenceNo" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['referenceNo']; ?>" placeholder="<?php echo set_value("referenceNo"); ?>" placeholder="<?php echo set_value("referenceNo"); ?>">
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="lawsuitId" class="form-label"><?php echo set_value("lawsuitId"); ?></label>
									<input type="text" class="form-control form-control-sm" id="lawsuitId" value="<?php if(isset($resultLawsuit,$resultLawsuit[0]['lawsuitId'])) echo $resultLawsuit[0]['lawsuitId']; ?>" placeholder="<?php echo set_value("lawsuitId"); ?>" placeholder="<?php echo set_value("lawsuitId"); ?>">
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="lsDate" class="form-label"><?php echo set_value('lawsuitDate'); ?><span class="text-danger"> * </span></label>
									<input type="date" class="form-control form-control-sm" value="<?php if(isset($resultLawsuit,$resultLawsuit[0]['lsDate'])) echo $resultLawsuit[0]['lsDate']; ?>" id="lsDate" placeholder="dd/mm/yyyy" required onkeydown="return false;">
								</div>
							</div>
						</div>
						
					</div>
				</div>
				
				<br><br>
				
				<div class="card mb-0">
					<div class="card-header">
						<h6 class="card-title"><?php echo set_value('notes'); ?></h6>
					</div>
					<div class="card-body pb-0">
							
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<!--
									<h6><?php ///echo set_value("notes"); ?></h6>
									<br> -->
									<textarea class="form-control" id="note" rows="3"><?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['notes']; ?></textarea>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<br><br>
				<div class="add-customer-btns text-end ">
					<a href="#" type="reset" class="btn customer-btn-cancel">Cancel</a>
					<a href="#" type="submit" id='add' class="btn customer-btn-save"><?php echo set_value('update'); ?></a>
				</div>
				<!--</form> -->
				<?php if(!isset($lsCode))
				{ ?>
				<input type="hidden" id="typeId" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['lsTypeId']; ?>">
				<input type="hidden" id="stateId" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['lsStateId']; ?>">
				<input type="hidden" id="stageId" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['lsStagesId']; ?>">
				<input type="hidden" id="empid" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['empId']; ?>">
				<input type="hidden" id="lsLocId" value="<?php if(isset($resultLawsuit,$resultLawsuit[0]['lslocationId'])) echo $resultLawsuit[0]['lslocationId']; ?>" />
				<?php } ?>
				<input type="hidden" id="lsDId" value="<?php echo $_POST['lsDId'] ?>">
				<input type="hidden" id="lsMId" value="<?php echo $_POST['lsMId'] ?>">
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
			<form id='OLawyerForm' action='javascript:addLayer();'>
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

<?php ////include_once('MessageModalShow.php'); ?>

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
						<div class="col-6">
							<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary paid-continue-btn" id="del_button" onclick="delCustomer();"><?php echo set_value('delete'); ?></button>
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
<!-- /Main Wrapper -->

<?php include_once('footer.php'); ?>
<script src="js_custom/LawsuitAdd.js"> </script>
<script src="js_custom/LawsuitEdit.js"> </script>

