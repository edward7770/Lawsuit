<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	$pageName2="Lawsuit";
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

	$qry_client="SELECT customerId as id, customerName_$language as val FROM tbl_customers c WHERE c.isActive=1";
	$stmt_client=$dbo->prepare($qry_client);
	//$stmt_client->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if($stmt_client->execute())
	{
		$result_client = $stmt_client->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt_client->errorInfo();
		exit($json =$errorInfo[2]);
	}
	
	/////include('get4setCurrency.php');
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
				<h5><?php echo set_value('client_report'); ?></h5>
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

		<?php /*
		<div class="row">
			<div class="col-xl-2 col-lg-4 col-sm-6 col-12">
				<div class="card inovices-card	">
					<div class="card-body">
						<div class="dash-widget-header mb-0">
							<div class="dash-count">
								<div class="dash-title"><?php echo set_value('monthly'); ?></div>
								<div class="dash-counts">
									<p class="mb-0" id="totalAmountMonthly"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-2 col-lg-4 col-sm-6 col-12">
				<div class="card inovices-card">
					<div class="card-body">
						<div class="dash-widget-header mb-0">
							<div class="dash-count">
								<div class="dash-title"><?php echo set_value('today'); ?></div>
								<div class="dash-counts">
									<p class="mb-0" id="totalAmountToday"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xl-2 col-lg-4 col-sm-6 col-12">
				<div class="card inovices-card	">
					<div class="card-body">
						<div class="dash-widget-header mb-0">
							<div class="dash-count">
								<div class="dash-title"><?php echo set_value('monthly'); ?></div>
								<div class="dash-counts">
									<p class="mb-0" id="dueAmountMonthly"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-2 col-lg-4 col-sm-6 col-12">
				
				<div class="card inovices-card">
					<div class="card-body">
						<div class="dash-widget-header mb-0">
							<div class="dash-count">
								<div class="dash-title"><?php echo set_value('today'); ?></div>
								<div class="dash-counts">
									<p class="mb-0" id="dueAmountToday"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		*/ ?>
		 <form action="javascript:search();"> 
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="date" class="form-label" style="color: #878A99;"><?php echo set_value('from'); ?><span class="text-danger"> * </span></label>
                        <input type="date" class="form-control form-control-sm" id="from_date" placeholder="dd/mm/yyyy" required onkeydown="return false;">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="date" class="form-label" style="color: #878A99;"><?php echo set_value('to'); ?><span class="text-danger"> * </span></label>
                        <input type="date" class="form-control form-control-sm" id="to_date" placeholder="dd/mm/yyyy" required onkeydown="return false;">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="stage" class="form-label"><?php echo set_value("customer"); ?></label>
                    <select class="js-example-basic-single form-small select" id='client_select'>
                        <option value=""><?php echo set_value("select"); ?></option>
						<?php
							foreach($result_client as $val)
							{
								echo "<option value='".$val['val']."'>".$val['val']."</option>";
							}
						?>
                    </select>
                </div>
                <div class="col-lg-1 col-md-6 col-sm-12">
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
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
						<div class="table-responsive" id='setData_payment'>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Page Wrapper -->

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

<?php include_once('modals/LawsuitPaymentMasterDetailModal.php'); ?>


<?php //// include_once('MessageModalShow.php'); ?>

<!-- /Main Wrapper -->

<!-- sample modal content -->


<?php include_once('footer.php'); 
?>

<script src="js_custom/ClientReport.js"> </script>
<script>
	$( document ).ready(function() {
		////$('#newStage_modal').modal('toggle');
		//// $('#LawsuitMasterDetailModal').modal('toggle');
	});
</script>