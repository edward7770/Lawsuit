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
				<h5><?php echo set_value('employees'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" onclick="add()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('addemployees'); ?></a>
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
										<th>#</th>
										<th><?php echo set_value('action'); ?></th>
										<th><?php echo set_value('name_ar'); ?></th>
										<th><?php echo set_value('name_en'); ?></th>
										<th><?php echo set_value('category'); ?></th>
										
										<th><?php echo set_value('empNo'); ?></th>
										<th><?php echo set_value('joinDate'); ?></th>
										<th><?php echo set_value('telephone_no'); ?></th>
										<th><?php echo set_value('mobile_no'); ?></th>
										<th><?php echo set_value('dob'); ?></th>
										<th><?php echo set_value('gender'); ?></th>
										<th><?php echo set_value('nationality'); ?></th>
										<th><?php echo set_value('religion'); ?></th>
										<th><?php echo set_value('idNo'); ?></th>
										<th><?php echo set_value('issueDate'); ?></th>
										<th><?php echo set_value('expiryDate'); ?></th>
										<th><?php echo set_value('passportNo'); ?></th>
										<th><?php echo set_value('issueDate'); ?></th>
										<th><?php echo set_value('expiryDate'); ?></th>
										
										<th><?php echo set_value('email'); ?></th>
										<th><?php echo set_value('active'); ?></th>
										<th><?php echo set_value('created_at'); ?></th>
										<th><?php echo set_value('user'); ?></th>
									</tr>
								</thead>
								<tbody> </tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Page Wrapper -->
<input type='hidden' id="searchId" value="<?php if(isset($_POST['id'])) echo $_POST['id']; else "0"; ?>" />
<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('delete_CustomerType'); ?></h3>
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


</div>
<!-- /Main Wrapper -->

<!-- sample modal content -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form id='customer_type' action='javascript:addEmp();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("addUpdateEmployees"); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="catId" class="form-label"><?php echo set_value("category"); ?> <span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="catId" required>
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include('dropdown_empCategory.php'); ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="empNo" class="form-label"><?php echo set_value("empNo"); ?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="empNo" placeholder="<?php echo set_value("empNo"); ?>" required>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="joinDate" class="form-label"><?php echo set_value('joinDate'); ?><span class="text-danger"> * </span></label></label>
								<input type="date" class="form-control form-control-sm" id="joinDate" placeholder="<?php echo set_value("joinDate"); ?>" required onkeydown="return false;">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="nameAr" class="form-label"><?php echo set_value("name_ar"); ?> <span class="text-danger"> * </span></label></label>
								<input type="text" class="form-control form-control-sm" id="nameAr" placeholder="<?php echo set_value("name_ar"); ?>" >
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="nameEn" class="form-label"><?php echo set_value('name_en'); ?> <span class="text-danger"> * </span></label></label>
								<input type="text" class="form-control form-control-sm" id="nameEn" placeholder="<?php echo set_value("name_ar"); ?>" required>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="email" class="form-label"><?php echo set_value('email'); ?></label>
								<input type="email" class="form-control form-control-sm" id="email" placeholder="<?php echo set_value("email"); ?>" >
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="phone" class="form-label"><?php echo set_value("telephone_no"); ?> <span class="text-danger"> * </span></label></label>
								<input type="text" class="form-control form-control-sm" id="phone" placeholder="<?php echo set_value("telephone_no"); ?>" >
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="mobile" class="form-label"><?php echo set_value("mobile_no"); ?> <span class="text-danger"> * </span></label></label>
								<input type="text" class="form-control form-control-sm" id="mobile" placeholder="<?php echo set_value("mobile_no"); ?>" >
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="dob" class="form-label"><?php echo set_value('dob'); ?><span class="text-danger"> * </span></label></label>
								<input type="date" class="form-control form-control-sm" id="dob" placeholder="<?php echo set_value("dob"); ?>" required onkeydown="return false;">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="gender" class="form-label"><?php echo set_value("gender"); ?> <span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="gender" required>
									<option value=""> <?php echo set_value("select"); ?></option>
									<option value="male"> <?php echo set_value("male"); ?></option>
									<option value="female"> <?php echo set_value("female"); ?></option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="nationality" class="form-label"><?php echo set_value("nationality"); ?></label>
								<select class="form-control js-example-basic-single form-small select" id="nationality">
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_country.php'); ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">	
								<label for="religion" class="form-label"><?php echo set_value("religion"); ?> <span class="text-danger"> * </span></label></label>
								<input type="text" class="form-control form-control-sm" id="religion" placeholder="<?php echo set_value("religion"); ?>" >
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="idNo" class="form-label"><?php echo set_value("idNo"); ?> <span class="text-danger"> * </span></label></label>
								<input type="text" class="form-control form-control-sm" id="idNo" placeholder="<?php echo set_value("idNo"); ?>">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="issueDate" class="form-label"><?php echo set_value('issueDate'); ?><span class="text-danger"> * </span></label></label>
								<input type="date" class="form-control form-control-sm" id="issueDate" placeholder="<?php echo set_value("issueDate"); ?>" onkeydown="return false;">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="expiryDate" class="form-label"><?php echo set_value('expiryDate'); ?><span class="text-danger"> * </span></label></label>
								<input type="date" class="form-control form-control-sm" id="expiryDate" placeholder="<?php echo set_value("expiryDate"); ?>" onkeydown="return false;">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="passportNo" class="form-label"><?php echo set_value('passportNo'); ?><span class="text-danger"> * </span></label></label>
								<input type="text" class="form-control form-control-sm" id="passportNo" placeholder="<?php echo set_value("passportNo"); ?>">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="doiP" class="form-label"><?php echo set_value('issueDate'); ?><span class="text-danger"> * </span></label></label>
								<input type="date" class="form-control form-control-sm" id="doiP" placeholder="<?php echo set_value("issueDate"); ?>" onkeydown="return false;">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
							<div class="form-group">
								<label for="doexpP" class="form-label"><?php echo set_value('expiryDate'); ?><span class="text-danger"> * </span></label></label>
								<input type="date" class="form-control form-control-sm" id="doexpP" placeholder="<?php echo set_value("expiryDate"); ?>" onkeydown="return false;">
							</div>
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
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('submit'); ?>" />
				<input type='hidden' id='id' />
			</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->



<?php include_once('footer.php'); ?>
<script src='js_custom/employee.js'></script>