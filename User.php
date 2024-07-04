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
				<h5><?php echo set_value('user'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" onclick="add();" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('addNewUser'); ?></a>
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
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item" role="presentation"><a class="nav-link active" href="#basictab1" data-bs-toggle="tab" aria-selected="true" role="tab"><?php echo set_value('employees'); ?> </a></li>
							<li class="nav-item" role="presentation"><a class="nav-link" href="#basictab2" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1"><?php echo set_value('customers'); ?></a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active show" id="basictab1" role="tabpanel">
								<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='setData'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th>#</th>
												<th><?php echo set_value('userName'); ?></th>
												<th><?php echo set_value('fullName'); ?></th>
												<th><?php echo set_value('userType'); ?></th>
												<th><?php echo set_value('category'); ?></th>
												<th><?php echo set_value('employeeName'); ?></th>
												<th><?php echo set_value('active'); ?></th>
												<th><?php echo set_value('role'); ?></th>
											</tr>	
										</thead>
										<tbody > </tbody>
										
									</table>
								</div>
							</div>
							<div class="tab-pane" id="basictab2" role="tabpanel">
									<div class="table-responsive">
									<table class="table table-center table-hover datatable" id='setDataClients'>
										<thead class="thead-light">
											<tr>
												<th><?php echo set_value('action'); ?></th>
												<th>#</th>
												<th><?php echo set_value('userName'); ?></th>
												<th><?php echo set_value('fullName'); ?></th>
												<th><?php echo set_value('userType'); ?></th>
												<th><?php echo set_value('category'); ?></th>
												<th><?php echo set_value('employeeName'); ?></th>
												<th><?php echo set_value('active'); ?></th>
												<th><?php echo set_value('role'); ?></th>
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
					<h3><?php echo set_value('deleteUser'); ?></h3>
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
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form id='modalForm' action='javascript:addNew();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value("addNewUser"); ?></h4>
					
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-6">
							<div class="mb-4">
								<label for="userName" class="form-label"><?php echo set_value("userName"); ?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="userName" placeholder="<?php echo set_value("userName"); ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-4">
								<label for="fullName" class="form-label"><?php echo set_value("fullName"); ?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="fullName" placeholder="<?php echo set_value("fullName"); ?>" required >
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-4">
								<label for="passw" class="form-label"><?php echo set_value('password'); ?> <span class="text-danger"> * </span></label>
								<input type="password" class="form-control form-control-sm" id="passw" placeholder="<?php echo set_value("password"); ?>" required>
							</div>
						</div>
						<div class="col-md-6" id='divRole'>
							<div class="mb-4">
								<label for="role" class="form-label"><?php echo set_value("role"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="role" required>
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_role.php'); ?>
								</select>
							</div>
						</div>
						
						<div class="col-md-6" id='divRoleCustomer' style='display:none'>
							<div class="mb-4">
								<label for="roleCustomer" class="form-label"><?php echo set_value("role"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="roleCustomer">
									
								</select>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="mb-4">
								<label for="userType" class="form-label"><?php echo set_value("userType"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="userType" required>
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_userType.php'); ?>
								</select>
							</div>
						</div>
						
						<div class="col-md-6" id="customerDiv" style="display: none;">
							<div class="mb-4">
								<label for="custId" class="form-label"><?php echo set_value("customer"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="custId">
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_customer.php'); ?>
								</select>
							</div>
						</div>
						
						<div class="col-md-6" id="lawyerDiv" style="display: none;">
							<div class="mb-4">
								<label for="empId" class="form-label"><?php echo set_value("employees"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="empId">
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_employee.php'); ?>
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
	<script src="js_custom/user.js"> </script>									