<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$pageName = "Lawsuit";
	///$pageName2="LawsuitMasterDetail";
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName)"; 
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
	///$stmt->bindParam(":pageName2",$pageName2,PDO::PARAM_STR);
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
				<div class="col-lg-2 col-md-4 col-sm-12">
					<div class="form-group">
						<label for="reportType" class="form-label"><?php echo set_value("reportType"); ?><span class="text-danger"> * </span></label>
						<select class="js-example-basic-single form-small select" id='reportType'>
							<option value="-1"><?php echo set_value("select"); ?></option>
							<option value="summary"><?php echo set_value("summary"); ?></option>
							<option value="detailed"><?php echo set_value("detailed"); ?></option>
							
						</select>
					</div>
				</div>
				
				<div class="col-lg-1 col-md-4 col-sm-12">
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
						<div class="table-responsive" id='setData'>
								
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Page Wrapper -->



<!-- /Main Wrapper -->

<!-- sample modal content -->


<?php include_once('footer.php'); 
	///include_once('generateHTML_docZip.php');
?>

<script src="js_custom/LawsuitReport.js"> </script>
<script>
	$( document ).ready(function() {
		////$('#newStage_modal').modal('toggle');
		//// $('#LawsuitMasterDetailModal').modal('toggle');
	});
</script>							
