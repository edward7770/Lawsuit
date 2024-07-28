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
<style>
	/*
	.modal-content {
	width: 150%;
	}
	*/
	.modal-lg, .modal-xl {
	--bs-modal-width: 55%;
	}
	#search{
		width:50%;
		display: block;
	}
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('payroll'); ?></h5>
			</div>
		</div>
		<!-- /Page Header -->
		
		<?php 
			$top=10;
			$left=50;
			include_once('loader.php'); 
		?><form action="javascript:search();"> 
			<div class="row">
				
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="form-group">
						<div class="mb-4">
							<label for="month" class="form-label"><?php echo set_value('month'); ?>  <span class="text-danger"> * </span></label>
							<select class="form-control js-example-basic-single form-small" id="month" required>
								<option value=""><?php echo set_value('select'); ?></option>
								<?php include_once('dropdown_month.php'); ?>
							</select>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="form-group">
						<div class="mb-4">
							<label for="year" class="form-label"><?php echo set_value('year'); ?>  <span class="text-danger"> * </span></label>
							<select class="form-control js-example-basic-single  form-small select" id="year" required>
								<option value=""><?php echo set_value('select'); ?></option>
								<?php include_once('dropdown_year.php'); ?>
							</select>
						</div>
					</div>
				</div>
				
				<div class="col-lg-3 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="type" class="form-label"><?php echo set_value("payrollType"); ?><span class="text-danger"> * </span></label>
						<select class="form-control js-example-basic-single form-small" id='type' required>
							<option value=""><?php echo set_value("select"); ?></option>
							<option value="Remaining"><?php echo set_value("remaining"); ?></option>
							<option value="Generated"><?php echo set_value("generated"); ?></option>
						</select>
					</div>
				</div>
				<?php /*
				<div class="col-md-3">
					<div class="form-group">
						<div class="mb-4">
							<label for="name" class="form-label"><?php echo set_value("employeeCategory"); ?><span class="text-danger"> * </span></label>
							<select class="form-control js-example-basic-single form-small" >
								<option value=""> <?php echo set_value("select"); ?></option>
								<?php echo include('dropdown_empCategory.php'); ?>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<br>
					<input class="btn btn-primary" type="submit" id='search' value="<?php echo set_value("search"); ?>" />
				</div>
				
				*/ ?>
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
						<div class="table-responsive" id="divtbl" style="display:none;">
							<table class="table table-center table-hover datatable" id='setData' style="display:none">
								<thead class="thead-light">
									<tr>
										<th>
											<label class="custom_check">
												<input type="checkbox" name="">
												<span class="checkmark"></span> 
											</label>
											&nbsp; <?php echo set_value("select4GeneratePayroll"); ?>
										</th>
										<th>#</th>
										<th><?php echo set_value("employeeName"); ?></th>
										<th><?php echo set_value("employeecategory"); ?></th>
									</tr>
								</thead>
								<tbody> 
									
								</tbody>
							</table>
						</div>
						<div class="table-responsive" id="divtblGen" style="display:none;">
							<table class="table table-center table-hover datatable" id='setDataGenerated' style="display:none">
								<thead class="thead-light">
									<tr>
										<th>Action</th>
										<th>#</th>
										<?php /*
										<th>
											<label class="custom_check">
												<input type="checkbox" name="">
												<span class="checkmark"></span> 
											</label> <?php echo set_value("select4PostUnpostPayroll"); ?>
										</th> */ ?>
										<th><?php echo set_value("employeeName"); ?></th>
										<th><?php echo set_value("employeecategory"); ?></th>
										<th><?php echo set_value("basicSalary"); ?></th>
										<th><?php echo set_value("allowance"); ?></th>
										<th><?php echo set_value("grossSalary"); ?></th>
										<th><?php echo set_value("deduction"); ?></th>
										<th><?php echo set_value("netPayment"); ?></th>
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
		<br/>
		<div class="row">
				<div class="col-md-3" id="divPayrollGen" style="display:none;">
					<div class="list-btn">
						<ul class="filter-list">
							<li>
								<a class="btn btn-primary" id='btnPayrollGen' ><?php echo set_value("generatePayroll"); ?></a>
							</li>
						</ul>
					</div>
				</div>
				<?php /*
				<div class="col-md-6" id="divPostPayroll" style="display:none;">
					<div class="list-btn">
						<ul class="filter-list">
							<li>
								<a class="btn btn-primary" id="btnPostPayroll" > <?php echo set_value("postPayroll"); ?></a>
							</li>
							<li>
								<a class="btn btn-primary" id="btnUnPostPayroll" ><?php echo set_value("unPostPayroll"); ?></a>
							</li>
						</ul>
					</div>
				</div>
				*/ ?>
				
			</div>
		<!--
		<div class="add-customer-btns text-end ">
			<a href="#" type="submit" id='add' class="btn customer-btn-save"><?php ////echo set_value('add'); ?></a>
		</div> -->
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

<!-- sample modal content -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form id='modalForm' action='javascript:addNew();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value('editPayroll'); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4" id="loadModalData">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value('close'); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="Submit" />
				</div>
			</form>
		</div>
	</div>
</div>
</div><!-- /.modal -->
<?php include_once('footer.php'); ?>
<!-- DataTables Buttons CSS and JS -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script> -->

<script src="js_custom/Payroll.js"> </script>
<script src="js_custom/PayrollGenerate.js"> </script>
<script src="js_custom/PayrollPost.js"> </script>
<!-- <script src="js_custom/dataTableButtons.js"> </script> -->
