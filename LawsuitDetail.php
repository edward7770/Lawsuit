<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	if(!isset($_POST['lsMId'],$_POST['lsDId']))
		exit('<script>window.location.replace("Lawsuit.php")</script>');
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	
	$qry="SELECT l.phrase, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN tbl_pagemenu m ON m.pageId=r.`menuId`
	WHERE m.pageName=:pageName"; 
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
	
?>
<style>
	.red-color {
    color: red;
	}
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">
		
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value("lawsuitDetails"); ?></h5>
			</div>
		</div>
		<!-- /Page Header -->
		<?php 
			$top=10;
			$left=60;
			include_once('loader.php'); 
		?>
		<div class="row">
			<div class="col-md-3">
				<?php include_once('getLawsuitDetailsData.php'); ?>
			</div>
			<div class="col-md-9">
				<div class="card bg-white">
					
					<div class="card-body">
						<ul class="nav nav-tabs nav-tabs-bottom nav-justified">
							<li class="nav-item"><a class="nav-link active" href="#tabSessions" data-bs-toggle="tab"><?php echo set_value("sessions"); ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tabTask" data-bs-toggle="tab"><?php echo set_value("lawsuitSessionTask"); ?></a></li>
							<?php /*
							<li class="nav-item"><a class="nav-link" href="#tabImages" data-bs-toggle="tab"><?php echo set_value("images"); ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tabPapers" data-bs-toggle="tab"><?php echo set_value("papers"); ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tabNumbers" data-bs-toggle="tab"><?php echo set_value("numbers"); ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tabRuling" data-bs-toggle="tab"><?php echo set_value("ruling"); ?> </a></li>
							<li class="nav-item"><a class="nav-link" href="#tabObjections" data-bs-toggle="tab"><?php echo set_value("objections"); ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tabVetoList" data-bs-toggle="tab"><?php echo set_value("vetoList"); ?></a></li>
							*/ ?>
							<?php if($_SESSION['customerId']<0) { ?> <li class="nav-item"><a class="nav-link" href="#tabClearance" data-bs-toggle="tab"><?php echo set_value("clearanceForm"); ?></a></li> <?php } ?>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tabSessions">
								<?php if($_SESSION['customerId']<0) { ?>
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='sessionButton'><?php echo set_value("createSession"); ?></button>
									</div>
								</div>
								<?php include_once('modals/LawsuitSessionModal.php'); ?> 
								<?php } ?>
								<br/>
								<div id="LawsuitSessionModalData"> </div>
							</div>
							<div class="tab-pane" id="tabImages">
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='createImageButton'><?php echo set_value("createImage"); ?></button>
									</div>
								</div>
								<?php include_once('modals/LawsuitImageModal.php'); ?> 
								<br/>
								<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='imageTabel'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th><?php echo set_value('fileName'); ?></th>
												<th><?php echo set_value('images'); ?></th>
												<th><?php echo set_value('created_at'); ?></th>
											</tr>
										</thead>
										
										<tbody class="thead-light" id='LawsuitImageModalData'>
											
										</tbody>
									</table>
								</div>
							</div>
							
							<div class="tab-pane" id="tabTask">
								<?php if($_SESSION['customerId']<0) { ?>
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='createTaskButton'><?php echo set_value("createTask"); ?></button>
									</div>
								</div>
								<?php } include_once('modals/LawsuitTaskModal.php'); ?>  
								<br/>
								<div id='LawsuitTaskModalData'>
								</div>
							</div>
							
							<div class="tab-pane" id="tabPapers">
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='paperButton'><?php echo set_value("createPaper"); ?></button>
									</div>
								</div>
								
								<?php include_once('modals/LawsuitPaperModal.php'); ?> 
								<br/>
								
								<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='paperTabel'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th><?php echo set_value('paperName'); ?></th>
												<th><?php echo set_value('images'); ?></th>
												<th><?php echo set_value('created_at'); ?></th>
											</tr>
										</thead>
										
										<tbody class="thead-light" id='LawsuitPaperModalData'>
											
										</tbody>
									</table>
								</div>
							</div>
							
							
							
							<div class="tab-pane" id="tabPapers">
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='paperButton'><?php echo set_value("createPaper"); ?></button>
									</div>
								</div>
								
								<?php include_once('modals/LawsuitPaperModal.php'); ?> 
								<br/>
								
								<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='paperTabel'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th><?php echo set_value('paperName'); ?></th>
												<th><?php echo set_value('images'); ?></th>
												<th><?php echo set_value('created_at'); ?></th>
											</tr>
										</thead>
										
										<tbody class="thead-light" id='LawsuitPaperModalData'>
											
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane" id="tabNumbers">
								<?php if($_SESSION['customerId']<0) { ?>
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='numberButton'><?php echo set_value("createNumber"); ?></button>
									</div>
								</div>
								<?php include_once('modals/LawsuitNumberModal.php'); ?> 
								<?php } ?>
								
								<br/>
								<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='numberTabel'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th><?php echo set_value('name'); ?></th>
												<th><?php echo set_value('number'); ?></th>
												<th><?php echo set_value('created_at'); ?></th>
											</tr>
										</thead>
										<tbody class="thead-light" id='LawsuitNumberModalData'>
										</tbody>
									</table>
								</div>
								
							</div>
							<div class="tab-pane" id="tabRuling">
								<?php if($_SESSION['customerId']<0) { ?>
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='rulingButton'><?php echo set_value("addRulingLawsuit"); ?></button>
									</div>
								</div>
								<?php include_once('modals/LawsuitRulingModal.php'); ?>
								<?php } ?>
								<br/>
								<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='rulingTabel'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th><?php echo set_value('appealDate'); ?></th>
												<th><?php echo set_value('images'); ?></th>
												<th><?php echo set_value('created_at'); ?></th>
											</tr>
										</thead>
										
										<tbody class="thead-light" id='LawsuitRulingModalData'>
											
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane" id="tabVetoList">
								<?php if($_SESSION['customerId']<0) { ?>
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='vetoButton'><?php echo set_value("createVeto"); ?></button>
									</div>
								</div>
								<?php include_once('modals/LawsuitVetoModal.php'); ?> 
								<?php } ?>
								<br/>
								<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='vetoTabel'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th><?php echo set_value('name'); ?></th>
												<th><?php echo set_value('endDate'); ?></th>
												<th><?php echo set_value('images'); ?></th>
												<th><?php echo set_value('created_at'); ?></th>
											</tr>
										</thead>
										
										<tbody class="thead-light" id='LawsuitVetoModalData'>
											
										</tbody>
									</table>
								</div>
								
								
								
							</div>
							<div class="tab-pane" id="tabObjections">
								<?php if($_SESSION['customerId']<0) { ?>
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='objectionButton'><?php echo set_value("createObjection"); ?></button>
									</div>
								</div>
								<?php include_once('modals/LawsuitObjectionModal.php'); ?> 
								<?php } ?>
								<br/>
								<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='objectionTabel'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th><?php echo set_value('name'); ?></th>
												<th><?php echo set_value('endDate'); ?></th>
												<th><?php echo set_value('images'); ?></th>
												<th><?php echo set_value('created_at'); ?></th>
											</tr>
										</thead>
										<tbody class="thead-light" id='LawsuitObjectionModalData'>
										</tbody>
									</table>
								</div>
								
							</div>
							<div class="tab-pane" id="tabClearance">
								<?php if($_SESSION['customerId']<0) { ?>
								<div class="row">
									<div class="col-3">
										<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary" id='CFormButton'><?php echo set_value("addClearanceForm"); ?></button>
									</div>
								</div>
								<?php include_once('modals/LawsuitClearanceModal.php'); ?> 
								<?php } ?>
								<br/>
								<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='clearanceTabel'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th><?php echo set_value('name'); ?></th>
												<th><?php echo set_value('images'); ?></th>
												<th><?php echo set_value('created_at'); ?></th>
											</tr>
										</thead>
										
										<tbody class="thead-light" id='LawsuitClearanceModalData'>
											
										</tbody>
									</table>
								</div>
								
							</div>
							<input type='hidden' id='mId' value='<?php if(isset($_POST['lsMId'])) echo $_POST['lsMId']; ?>' >
							<input type='hidden' id='dId' value='<?php if(isset($_POST['lsDId'])) echo @$_POST['lsDId']; ?>' >
							<input type='hidden' id='collapsedId' value='<?php if(isset($_POST['id'])) echo $_POST['id']; ?>' >
							<input type='hidden' id='collapsedData' value='<?php if(isset($_POST['data'])) echo $_POST['data']; ?>' >
							
							<input type='hidden' id='set_valuePaperDetails' value='<?php if(isset($_POST['paperDetails'])) echo set_value('paperDetails'); ?>' >
							<input type='hidden' id='notesDetail' value='<?php if(isset($_POST['notes'])) echo set_value('notes'); ?>' >
							<input type='hidden' id='set_valueAppealDetails' value='<?php if(isset($_POST['detailsRulingLawsuit'])) echo set_value('detailsRulingLawsuit'); ?>' >
							<?php /*
							<!--
							<input type='hidden' id='set_valueRulingDetails' value='<?php ///set_value('sessionDetails'); ?>' >
							<input type='hidden' id='set_valuePaperDetails' value='<?php //set_value('paperDetails'); ?>' >
							<input type='hidden' id='set_valuePaperDetails' value='<?php ///set_value('paperDetails'); ?>' >
							-->
							*/ ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Page Wrapper -->

<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php //echo set_value('delete_CustomerType'); ?></h3>
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

<?php include_once('modals/LawsuitDetailModal.php'); ?>
<?php include_once('footer.php');?>
<script src="assets/plugins/hijri-date-picker/js/bootstrap-hijri-datepicker.min.js"></script>
<script src="js_custom/LawsuitDetail.js"> </script>
<script src="js_custom/imageupload.js"> </script>
<script type="text/javascript">

</script>	

