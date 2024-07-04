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
		$resultLan = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	function set_value($val)
	{
		foreach($GLOBALS['resultLan'] as $value)
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
				<h5><?php echo set_value('role'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" onclick="add();" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('addNewRole'); ?></a>
						</li>
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
		<div class="row">
			<div class="col-sm-12">
				<div class="card-table">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-center table-hover datatable" id='setData'>
								<thead class="thead-light">
									<tr>
										<th><?php echo set_value('action'); ?></th>
										<th>#</th>
										<th><?php echo set_value('roleName'); ?></th>
										<th><?php echo set_value('defaultPage'); ?></th>
										<th><?php echo set_value('active'); ?></th>
									</tr>
								</thead>
								<tbody > </tbody>
								
							</table>
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
					<h3><?php echo set_value('delteRole'); ?></h3>
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

</div>
<!-- /Main Wrapper -->

<!-- sample modal content -->
<div class="modal fade" id="addRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form id='modalForm' action='javascript:addNew();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("addNewRole"); ?></h4>
				
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-12">
						<div class="mb-4">
							<label for="roleName" class="form-label"><?php echo set_value("roleName"); ?> <span class="text-danger"> * </span></label>
							<input type="text" class="form-control form-control-sm" id="roleName" placeholder="<?php echo set_value("roleName"); ?>" required>
						</div>
					</div>
					<div class="col-md-12">
						<div class="mb-4">
							<label for="pageId" class="form-label"><?php echo set_value("defaultPage"); ?> <span class="text-danger"> * </span></label>
							<select class="form-control js-example-basic-single form-small select" id="pageId">
								<option value=""> <?php echo set_value("select"); ?></option>
								<?php echo include('dropdown_pageMenu.php'); ?>
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="mb-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="active" checked>
								<label class="form-check-label" for="active"><span class="text-danger"> * </span>
									<?php echo set_value("active"); ?>
								</label>
							</div>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
					<input type='hidden' id='id' value="0" />
				</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->



<?php include_once('footer.php'); ?>
<script src="js_custom/role.js"> </script>