
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	////$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	$pageName = "Document";
	
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
	if(isset($_POST['id']) && !empty($_POST['id']))
	{
		$qry="SELECT docName_en,docName_ar,docDescription, docFilePath,docFileName FROM tbl_docs WHERE isActive=1 AND docsId=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$resultDoc = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
		if($resultDoc)
		{
			foreach($resultDoc as $row)
			{
				$docName_en=$row['docName_en'];
				$docName_ar=$row['docName_ar'];
				$docDescription=$row['docDescription'];
				$docFilePath=$row['docFilePath'];
				$docFileName=$row['docFileName'];
			}
		}
		else 
			echo '<script>window.close();</script>';
	}
	
	
?>


<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="row">
			<div class="col-sm-12">
				<!-- /Page Header -->	
				<div class="page-header">
					<div class="content-page-header">
						<h5><?php if(isset($docName_en)) echo set_value('editDocument'); else  echo set_value('addDocument'); ?></h5>
					</div>
				</div>
				
				<?php 
					///$top=10;
					///$left=50;
					include_once('loader.php'); 
				?>
				<div class="card mb-0">
					<div class="card-body pb-0">
						<form id="docForm" action="javascript:add();">
							<div class="row">
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label><?php echo set_value("documentName_ar");?> <span class="text-danger"> * </span></label>
										<input type="text" class="form-control form-control-sm name" id="nameAr" placeholder="<?php echo set_value("documentName_ar"); ?>" value="<?php if(isset($docName_ar)) echo $docName_ar; ?>" required >
									</div>
								</div>
								
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label><?php echo set_value("documentName_en");?> <span class="text-danger"> * </span></label>
										<input type="text" class="form-control form-control-sm name" id="nameEn" placeholder="<?php echo set_value("documentName_en"); ?>" value="<?php if(isset($docName_en)) echo $docName_en; ?>" required>
									</div>
								</div>
								
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label><?php echo set_value("description");?> <span class="text-danger"> * </span></label>
										<input type="text" class="form-control form-control-sm name" id="desc" placeholder="<?php echo set_value("description"); ?>" value="<?php if(isset($docDescription)) echo $docDescription; ?>">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-lg-4 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="fileImage" class="form-label"><?php echo set_value("fileUpload"); ?></label>
										<fieldset id="fileImageFieldset">	
											<input type="file" class="form-control form-control-sm image_check" id="fileImage" <?php if(!isset($_POST['id'])) echo "required"; ?>>
										</fieldset>
										<span><?php echo set_value("uploadMaximumLimit4Doc"); ?></span>
									</div>
								</div>
								
								<?php
									if(isset($docFilePath,$docFileName))
									{ ?>
									<div class="col-lg-4 col-md-6 col-sm-12">
										<div class="form-group">
											<label for="fileImage" class="form-label"><?php echo set_value("fileUploaded"); ?></label>
											<fieldset>	
												<a href="<?php echo $docFilePath.$docFileName; ?>" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>
											</fieldset>		
										</div>
									</div>
									<?php 
									}
								?>
								
							</div>
							
							<div class="add-customer-btns text-end ">
								<button type="submit" id='add' class="btn customer-btn-save"><?php echo set_value('upload'); ?> </button>
								<input type="hidden" id="id" value="<?php if(isset($_POST['id'])) echo $_POST['id']; else echo 0; ?>" />
							</div>
							
						</form> <!--</form> -->
						<br><br>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Main Wrapper -->
<?php include_once('footer.php'); ?>
<script src="js_custom/imageuploadDoc.js"> </script>
<script src="js_custom/document.js"> </script>

