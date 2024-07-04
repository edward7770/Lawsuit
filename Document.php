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
	/*
	.modal-content {
	width: 150%;
	}
	*/
	.modal-lg, .modal-xl {
	--bs-modal-width: 55%;
	}
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('documentList'); ?></h5>
			</div>
		</div>
		<!-- /Page Header -->
		
		<?php 
			$top=10;
			$left=50;
			include_once('loader.php'); 
		?>
		<div class="row">
			<div class="col-sm-12">
				<div class="card-table">
					<div class="card-body">
						<div class="table-responsive" id="divtbl">
							<table class="table table-center table-hover datatable" id='setData'>
								<thead class="thead-light">
									<tr>
										<th><?php echo set_value("action"); ?></th>
										<th>#</th>
										<th><?php echo set_value("documentName_ar"); ?></th>
										<th><?php echo set_value("documentName_en"); ?></th>
										<th><?php echo set_value("description"); ?></th>
										<th><?php echo set_value("fileUpload"); ?></th>
									</tr>
								</thead>
								<tbody> 
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input type='hidden' id="searchId" value="<?php if(isset($_POST['id'])) echo $_POST['id']; else "0"; ?>" />
	<!-- Delete Items Modal -->
	<div class="modal custom-modal fade" id="delete_modal" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-md">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-header">
						<h3><?php echo set_value('deleteDocument'); ?></h3>
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
	
	<?php include_once('footer.php'); ?>
	<script src="js_custom/document.js"> </script>