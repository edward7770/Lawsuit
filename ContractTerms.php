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
	$qry="SELECT torsId, tors_en, tors_ar FROM tbl_tors c 
	WHERE c.isActive=1";
	
	$stmt=$dbo->prepare($qry);
	if($stmt->execute())
	{
		$resultEdit = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultEdit as $row)
		{
			$torsId=$row['torsId'];
			$tors_en=$row['tors_en'];
			$tors_ar=$row['tors_ar'];
		}
		/////print_r($resultEdit);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	
	
?>
<style>
	h6,.textColor {
	color:red
	}
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="row">
			<div class="col-sm-12">
				
				<!-- /Page Header -->	
				<div class="page-header">
					<div class="content-page-header">
						<h5><?php echo set_value('contractTerms'); ?></h5>
					</div>
				</div>
				
				<?php 
					include_once('loader.php'); 
					////autocomplete="off" in form
				?>
				<form id="form2" action="javascript:add();" method="post" >	
					<div class="card mb-0">
						<div class="card-body pb-0">
							
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item" role="presentation"><a class="nav-link active" href="#basictab1" data-bs-toggle="tab" aria-selected="true" role="tab"><?php echo set_value('contractDetails_en'); ?> </a></li>
								<li class="nav-item" role="presentation"><a class="nav-link" href="#basictab2" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1"><?php echo set_value('contractDetails_ar'); ?></a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active show" id="basictab1" role="tabpanel">
									<div class="invoice-card-title">
										<h6><?php echo set_value('contractDetails_en'); ?> :</h6>
									</div>
									
									
									<div class="row">
										<div class="col-md-12">	
											<textarea class="summernote form-control" id="ContractTermsEn" placeholder="<?php echo set_value('contractDetails_en'); ?>" required><?php if(isset($tors_en)) echo $tors_en; ?></textarea>
										</div>
									</div>
							</div> 
							<div class="tab-pane" id="basictab2" role="tabpanel">
									
									<div class="invoice-card-title">
										<h6><?php echo set_value('contractDetails_ar'); ?> :</h6>
									</div>
									
									<div class="row">
										<div class="col-md-12">	
											<textarea class="summernote form-control" id="ContractTermsAr" placeholder="<?php echo set_value('contractDetails_en'); ?>" required><?php if(isset($tors_ar)) echo $tors_ar; ?></textarea>
										</div>
									</div>
								</div>
									<br>
									<br>
									<div class="add-customer-btns text-end text-center">
										<a href="#" type="submit" id='add' class="btn customer-btn-save"><?php echo set_value('submit'); ?></a>
									</div>
									<input type=hidden id="setId" value="<?php if(isset($torsId)) echo $torsId; ?>"  />
									<br>
						</div>
					</div>
				</form>	
				<!--</form> -->
			</div>
		</div>
		
	</div>
	
	<?php include_once('footer.php'); ?>
<script src="js_custom/ContractTerms.js"> </script>