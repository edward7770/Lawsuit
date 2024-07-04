<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	WHERE r.menuid=11"; 
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
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

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('lawsuits'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-filters w-auto popup-toggle"><span class="me-2"><i class="fe fe-filter"></i></span>Filter </a>
						</li>
						
						
						<li>
							<div class="dropdown dropdown-action">
								<a href="#" class="btn-filters" data-bs-toggle="dropdown" aria-expanded="false"><span><i class="fe fe-download"></i></span></a>
								<div class="dropdown-menu dropdown-menu-end">
									<ul class="d-block">
										<li>
											<a class="d-flex align-items-center download-item" href="javascript:void(0);" download><i class="far fa-file-pdf me-2"></i>PDF</a>
										</li>
										<li>
											<a class="d-flex align-items-center download-item" href="javascript:void(0);" download><i class="far fa-file-text me-2"></i>CVS</a>
										</li>
									</ul>
								</div>
							</div>														
						</li>
						
						<li>
							<a class="btn btn-primary" onclick="add()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("add_new_customer"); ?></a>
						</li>
						<li>
							<a class="btn btn-success" onclick="customer_type_modal()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('add_new customer_type'); ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<div id="filter_inputs" class="card filter-card">
			<div class="card-body pb-0">
				<div class="row">
					<div class="col-sm-6 col-md-3">
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="form-group">
							<label>Email</label>
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="form-group">
							<label>Phone</label>
							<input type="text" class="form-control">
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Search Filter -->
		
		<div class="row">
			<div class="col-sm-12">
				<div class="card-table">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-center table-hover datatable">
								<thead class="thead-light">
									<tr>
										<th>#</th>
										<th><?php echo set_value('action'); ?></th>
										<th><?php echo set_value('customer'); ?></th>
										<th><?php echo set_value('state'); ?></th>
										<th><?php echo set_value('stage'); ?></th>
										<th><?php echo set_value('amount'); ?></th>
										<th><?php echo set_value('total_amount	'); ?></th>
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

<!-- Add Asset customer-->
<div class="toggle-sidebar">
	<div class="sidebar-layout-filter">
		<div class="sidebar-header">
			<h5>Filter</h5>
			<a href="#" class="sidebar-closes"><i class="fa-regular fa-circle-xmark"></i></a>
		</div>
		<div class="sidebar-body">						
			<form action="#" autocomplete="off">
				<!-- Customer -->
				<div class="accordion" id="accordionMain1">
					<div class="card-header-new" id="headingOne">
						<h6 class="filter-title">
							<a href="javascript:void(0);" class="w-100" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								Customer	
								<span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
							</a> 
						</h6>
					</div>
				</div>
				<!-- /Customer -->
				
				<!-- Select Date -->
				<div class="accordion" id="accordionMain2">
					<div class="card-header-new" id="headingTwo">
						<h6 class="filter-title">
							<a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
								Select Date	
								<span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
							</a> 
						</h6>
					</div>
					
					<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"  data-bs-parent="#accordionExample2">
						<div class="card-body-chat">
							<div class="form-group">
								<label class="form-control-label">From</label>
								<div class="cal-icon">
									<input type="email" class="form-control datetimepicker" placeholder="DD-MM-YYYY">
								</div>
							</div>
							<div class="form-group">
								<label class="form-control-label">To</label>
								<div class="cal-icon">
									<input type="email" class="form-control datetimepicker" placeholder="DD-MM-YYYY">
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Select Date -->
				
				<button type="submit" class="d-inline-flex align-items-center justify-content-center btn w-100 btn-primary">
					<span><img src="assets/img/icons/chart.svg" class="me-2" alt="Generate report"></span>Generate report
				</button>
			</form>
			
		</div>
	</div>
</div>	
<!--/Add Asset -->

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

<!-- /Customer Modal -->

<!-- /Delete Items Modal -->

<?php ////include_once('MessageModalShow.php'); ?>
<?php include_once('/modals/LawsuitMasterDetailModal.php'); ?>

</div>
<!-- /Main Wrapper -->

<!-- sample modal content -->

<?php include_once('footer.php'); ?>

<script src="js_custom/LawsuitMasterDetail.js"> </script>
<script>
$( document ).ready(function() {
    $('#LawsuitMasterDetailModal').modal('toggle');
});
</script>