<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
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
	
	
?>
<style>
	.table-responsive .dropdown,
	.table-responsive .btn-group,
	.table-responsive .btn-group-vertical {
    position: static;
	}
</style>	
<style>
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
				<h5><?php echo set_value('lawsuits'); ?></h5>
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
		<form action="javascript:search();"> 
			<div class="row">
				
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="form-group">
						
						<label for="lawsuitsType" class="form-label"><?php echo set_value("lawsuits_Type"); ?><span class="text-danger"> * </span></label>
						<select class="js-example-basic-single form-small select" id='lawsuitsType'>
							<option value="-1"><?php echo set_value("select"); ?></option>
							<?php echo include_once('dropdown_lawsuitType.php'); ?>
						</select>
					</div>
				</div>
				
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="form-group">
						
						<label for="state" class="form-label"><?php echo set_value("state"); ?><span class="text-danger"> * </span></label>
						<select class="js-example-basic-single form-small select" id='state'>
							<option value="-1"><?php echo set_value("select"); ?></option>
							<?php echo include_once('dropdown_state.php'); ?>
						</select>
					</div>
				</div>
				
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="stage" class="form-label"><?php echo set_value("stage"); ?><span class="text-danger"> * </span></label>
						<select class="js-example-basic-single form-small select" id='stage'>
							<option value="-1"><?php echo set_value("select"); ?></option>
							<?php echo include_once('dropdown_stage.php'); ?>
						</select>
					</div>
				</div>
				
				<div class="col-md-2 col-lg-2 col-sm-2">
					<label for="search" class="form-label">&nbsp;</label>
					<button type="submit" class="btn btn-primary form-control" id='search'>
						<?php echo set_value("search"); ?>
					</button>
				</div>
			</div>
		</form>
		
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
									<th><?php echo set_value('referenceNo'); ?></th>
									<th><?php echo set_value('lawsuitId'); ?></th>
									<th><?php echo set_value('lawsuitDate'); ?></th>
									<th><?php echo set_value('customer'); ?></th>
									<th><?php echo set_value('opponentName'); ?></th>
									<th><?php echo set_value('lawsuitLocation'); ?></th>
									<th><?php echo set_value('lawsuitLawyer'); ?></th>
									<th><?php echo set_value('lawsuits_Type'); ?></th>
									<th><?php echo set_value('state'); ?></th>
									<th><?php echo set_value('stage'); ?></th>
									<th><?php echo set_value('stagesnumber'); ?></th>
								<th><?php echo set_value('paidStatus'); ?></th>
								<!--<th><?php ///echo set_value('created_at'); ?></th> --->
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
								</div>
								<!-- /Page Wrapper -->
								
								
								<!-- /Customer Modal -->
								
								<!-- Delete Items Modal -->
								<div class="modal custom-modal fade" id="delete_modal" role="dialog">
								<div class="modal-dialog modal-dialog-centered modal-md">
								<div class="modal-content">
								<div class="modal-body">
								<div class="form-header">
								<h3><?php echo set_value('deleteLawsuit'); ?></h3>
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
								
								
								<!-- Delete Items Modal -->
								<div class="modal custom-modal fade" id="delete_modalStage" role="dialog">
								<div class="modal-dialog modal-dialog-centered modal-md">
								<div class="modal-content">
								<div class="modal-body">
								<div class="form-header">
								<h3><?php echo set_value('deleteLawsuitStage'); ?></h3>
								<p><?php echo set_value('areYouSureWantTodelete?'); ?></p>
								</div>
								<div class="modal-btn delete-action">
								<div class="row">
								<div class="col-6">
								<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary paid-continue-btn" id="del_buttonStage" onclick="delStage();"><?php echo set_value('delete'); ?></button>
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
								
								
								<?php include_once('modals/LawsuitMasterDetailModal.php'); ?>
								
								
								<?php //// include_once('MessageModalShow.php'); ?>
								
								<!-- /Main Wrapper -->
								
								<!-- sample modal content -->
								
								
								<?php include_once('footer.php'); 
								include_once('generateHTML_docZip.php');
								?>
								
								<script src="js_custom/Lawsuit.js"> </script>
								<script>
								$( document ).ready(function() {
								////$('#newStage_modal').modal('toggle');
								//// $('#LawsuitMasterDetailModal').modal('toggle');
								});
								</script>								