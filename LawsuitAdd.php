<?php 
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
				
				<?php 
					$top=20;
					$left=50;
					include_once('loader.php'); 
				?>
				<div class="card mb-0">
					<div class="card-header">
						<h6 class="card-title"><?php echo set_value('customerData'); ?></h6>
					</div>
					<div class="card-body pb-0">
						
						<!--		
							<div class="invoice-card-title">
							<h6><?php //////echo set_value('customerData'); ?> :</h6>
						</div> --->
						<form action="javascript:addMoreCustomer();">
							
							<div class="row">
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="customer class="form-label"><?php echo set_value("selectCustomer"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" id='customer' required >
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
												<tbody id='setData'> </tbody>
												
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
								<div class="col-lg-7 col-md-12 col-sm-12">
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
							<div class="col-sm-12">
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
												<tbody id='setDataOpponent'> </tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						
					</div>
				</div>
				
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
									<select class="js-example-basic-single form-small select" id='lawsuitsType'>
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_lawsuitType.php'); ?>
									</select>
								</div>
							</div>
							
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									
									<label for="state" class="form-label"><?php echo set_value("state"); ?><span class="text-danger"> * </span></label>
									<select class="js-example-basic-single form-small select" id='state'>
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_state.php'); ?>
									</select>
								</div>
							</div>
							
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="stage" class="form-label"><?php echo set_value("stage"); ?><span class="text-danger"> * </span></label>
									<select class="js-example-basic-single form-small select" id='stage'>
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
									<input type="text" class="form-control form-control-sm" id="subjectLawsuit" placeholder="<?php echo set_value("subjectLawsuit"); ?>">
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="lawsuitLocation" class="form-label"><?php echo set_value("lawsuitLocation"); ?></label>
									<?php /*<input type="text" class="form-control form-control-sm" id="lawsuitLocation" placeholder="<?php echo set_value("lawsuitLocation"); ?>"> */ ?>
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
									<input type="text" class="form-control form-control-sm" id="referenceNo" placeholder="<?php echo set_value("referenceNo"); ?>">
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="lawsuitId" class="form-label"><?php echo set_value("lawsuitId"); ?></label>
									<input type="text" class="form-control form-control-sm" id="lawsuitId" placeholder="<?php echo set_value("lawsuitId"); ?>">
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div class="form-group">
									<label for="lsDate" class="form-label"><?php echo set_value('lawsuitDate'); ?><span class="text-danger"> * </span></label>
									<input type="date" class="form-control form-control-sm" id="lsDate" placeholder="dd/mm/yyyy" required onkeydown="return false;">
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
										<h6><?php //echo set_value("notes"); ?></h6>
									<br> -->
									<textarea class="form-control" id="note" rows="3"></textarea>
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
				
				<?php if(isset($_POST['lsMId']) && !empty($_POST['lsMId'])) { ?>
					<input type="hidden" id='lsMId' value="<?php echo $_POST['lsMId']; ?>" />
				<?php } ?>
				
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
					<h4 class="modal-title"><?php echo set_value("createNewOponentLawyer"); ?></h4>
					
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

<?php //// include_once('MessageModalShow.php'); ?>

<!-- /Main Wrapper -->

<?php include_once('footer.php'); ?>
<script>
	$(document).ready(function() {
		// this changes the scrolling behavior to "smooth"
		window.scrollTo({ top: 0, behavior: 'smooth' });
	});
</script>
<script src="js_custom/LawsuitAdd.js"> </script>