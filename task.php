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
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('taskList'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" onclick="add();" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('addTask'); ?></a>
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
			<form action="javascript:search();"> 
			<div class="row">
				
				<div class="col-lg-2 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="taskstartDateSearch" class="form-label"><?php echo set_value("taskstartDate"); ?> <span class="text-danger"> * </span></label>
							<input type="date" class="form-control" id="taskstartDateSearch" placeholder="Select Date" onkeydown="return false;">
					</div>
				</div>
				
				<div class="col-lg-2 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="taskDueDateSearch" class="form-label"><?php echo set_value("taskDueDate"); ?> <span class="text-danger"> * </span></label>
							<input type="date" class="form-control" id="taskDueDateSearch" placeholder="Select Date" onkeydown="return false;">
					</div>
				</div>
				
				
				<div class="col-lg-2 col-md-6 col-sm-12">
					<div class="form-group">
						
						<label for="taskPrioritySearch" class="form-label"><?php echo set_value("taskPriority"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="taskPrioritySearch">
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include('dropdown_taskPriority.php'); ?>
								</select>

					</div>
				</div>
				
				<div class="col-lg-2 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="taskStatusSearch" class="form-label"><?php echo set_value("taskStatus"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="taskStatusSearch">
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include('dropdown_taskStatus.php'); ?>
								</select>
					</div>
				</div>
				
				<div class="col-md-1 col-lg-2 col-sm-2">
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
							<table class="table table-center table-hover datatable" id='setData'>
								<thead class="thead-light">
									<tr>
										<th><?php echo set_value('action'); ?></th>
										<th>#</th>
										<th><?php echo set_value('taskName'); ?></th>
										<th><?php echo set_value('taskAssignedTo'); ?></th>
										<th><?php echo set_value('taskstartDate'); ?></th>
										<th><?php echo set_value('taskDueDate'); ?></th>
										<th><?php echo set_value('taskPriority'); ?></th>
										<th><?php echo set_value('taskStatus'); ?></th>
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
<input type='hidden' id="searchId" value="<?php if(isset($_POST['id'])) echo $_POST['id']; else "0"; ?>" />
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
	
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form id='modalForm' action='javascript:addNew();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("addTask"); ?></h4>
				
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="mb-4">
								<label for="name" class="form-label"><?php echo set_value("taskName"); ?>  <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="name" placeholder="<?php echo set_value("taskName"); ?>" required>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="taskDescription" class="form-label"><?php echo set_value("taskDescription"); ?></label>
							<textarea class="form-control summernote" id="taskDescription"></textarea>
						</div>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<div class="mb-4">
								<label for="taskAssignedTo" class="form-label"><?php echo set_value("taskAssignedTo"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="taskAssignedTo" required>
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_employee.php'); ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group ">
							<label for="taskstartDate" class="form-label"><?php echo set_value("taskstartDate"); ?> <span class="text-danger"> * </span></label>
							<input type="date" class="form-control" id="taskstartDate" placeholder="Select Date" required onkeydown="return false;">
						</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-4">
						<div class="form-group ">
							<label for="taskDueDate" class="form-label"><?php echo set_value("taskDueDate"); ?> <span class="text-danger"> * </span></label>
							<input type="date" class="form-control" id="taskDueDate" placeholder="Select Date" required onkeydown="return false;">
						</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<div class="mb-4">
								<label for="taskPriority" class="form-label"><?php echo set_value("taskPriority"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="taskPriority" required>
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include('dropdown_taskPriority.php'); ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<div class="mb-4">
								<label for="taskStatus" class="form-label"><?php echo set_value("taskStatus"); ?><span class="text-danger"> * </span></label>
								<select class="form-control js-example-basic-single form-small select" id="taskStatus" required>
									<option value=""> <?php echo set_value("select"); ?></option>
									<?php echo include('dropdown_taskStatus.php'); ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('submit'); ?>" />
					<input type='hidden' id='id' value="0" />
				</div>
			</form>
			
		</div>
	</div>
	</div>
</div><!-- /.modal -->

<!-- Message Items Modal -->
<div class="modal custom-modal fade" id="msg_detailModal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="standard-modalLabel"><?php echo set_value("taskDescription"); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="modelBody">
				
			</div>
		</div>
	</div>
</div>
<!-- /Message Items Modal -->

<?php include_once('footer.php'); ?>
<script src="js_custom/task.js"> </script>