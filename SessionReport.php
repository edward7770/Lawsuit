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
<div class="page-wrapper" <?php if ($language === 'ar') echo 'style="direction: rtl"'; else echo 'style="direction: ltr"';  ?>>
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('session_report'); ?></h5>
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
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="date" class="form-label" style="color: #878A99;"><?php echo set_value('from'); ?><span class="text-danger"> * </span></label>
                        <input type="date" class="form-control form-control-sm" id="from_date" placeholder="dd/mm/yyyy" required onkeydown="return false;">
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="date" class="form-label" style="color: #878A99;"><?php echo set_value('to'); ?><span class="text-danger"> * </span></label>
                        <input type="date" class="form-control form-control-sm" id="to_date" placeholder="dd/mm/yyyy" required onkeydown="return false;">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <label for="search" class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control" id='search'>
                        <?php echo set_value("search"); ?>
                    </button>
                </div>
            </div>
        </form>
		
		<div class="row mt-4">
			<div class="col-sm-12">
				<div class="card-table">
					<div class="card-body">
						<div class="table-responsive" id='setData_session'>
                            
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

<script src="js_custom/SessionReport.js"> </script>
<script>
	$( document ).ready(function() {
		////$('#newStage_modal').modal('toggle');
		//// $('#LawsuitMasterDetailModal').modal('toggle');
	});
</script>							
