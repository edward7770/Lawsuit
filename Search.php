<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
		$pageNameSearch = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
		
	$pageName = "Lawsuit";
	$pageName2="LawsuitMasterDetail";
	$pageName3="EmpContract";
	$pageName4="Customer";
	$pageName5="employees";
	$pageName6="LawsuitDetail";
	$pageName7="Document";
	$pageName8="consultationAdd";
	$pageName9="task";
	
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName,:pageName2,:pageName3,:pageName4,:pageName5,:pageName6,:pageName7,:pageName8,:pageName9, :pageNameSearch)"; 
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
	$stmt->bindParam(":pageName2",$pageName2,PDO::PARAM_STR);
	$stmt->bindParam(":pageName3",$pageName3,PDO::PARAM_STR);
	$stmt->bindParam(":pageName4",$pageName4,PDO::PARAM_STR);
	$stmt->bindParam(":pageName5",$pageName5,PDO::PARAM_STR);
	$stmt->bindParam(":pageName6",$pageName6,PDO::PARAM_STR);
	$stmt->bindParam(":pageName7",$pageName7,PDO::PARAM_STR);
	$stmt->bindParam(":pageName8",$pageName8,PDO::PARAM_STR);
	$stmt->bindParam(":pageName9",$pageName9,PDO::PARAM_STR);
		$stmt->bindParam(":pageNameSearch",$pageNameSearch,PDO::PARAM_STR);
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
	
	/*
	CALL `sp_getConsultation_Search`  ('en',-1,'test');
	CALL `sp_getCustomers_Search`('en', -1, 'noman');
	CALL `sp_getEmployees_Search`('en', -1, 'noman');
	CALL `sp_getFile_Search`('en',1,'task');
	CALL `sp_getGeneralTask_Search`('en',-1,'Aijaz Gul');
	CALL `sp_getLawsuitDetails_Search`('en',-1,'test');
	CALL `sp_getSessions_Search`('en',-1,'test');
	CALL `sp_getSessionsTask_Search`('en',-1,'test');
	
	*/
	if(isset($_POST['txtSearch'])) $txtSearch=$_POST['txtSearch'];
	else 
		echo 'script>window.close();</script>';
		////exit('script>window.close();</script>');
	include_once('header.php');
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
						<h5><?php echo set_value('searchResults'); ?> <span class="text-danger">"<?php if(isset($txtSearch)) echo $txtSearch; ?>"<span></h5>
						</div>
						</div>
						
						<?php 
							include_once('loader.php'); 
							////autocomplete="off" in form
						?>
						
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="card">
									<div class="card-body">
										
										<ul class="inbox-menu">
											<li>
												<a href="javascript:showLawsuit();" ><?php echo set_value('lawsuits'); ?> <span class="mail-count text-danger" id="lawsuit">(0)</span></a>
											</li>
											<li>
												<a href="javascript:showCustomer();" > <?php echo set_value('customer'); ?> <span class="mail-count text-danger" id="customer">(0)</span> </a>
											</li>
											<li>
												<a href="javascript:showEmployee();" > <?php echo set_value('employees'); ?> <span class="text-danger" id="employee">(0)</span> </a>
											</li>
											<li>
												<a href="javascript:showSession();" > <?php echo set_value('sessions'); ?> <span class="text-danger" id="session">(0)</span></a>
											</li>
											<li>
												<a href="javascript:showLSTask();" > <?php echo set_value('lawsuitSessionTask'); ?><span class="text-danger" id="LSTask">(0)</span></a>
											</li>
											<li>
												<a href="javascript:showDocument();" ><?php echo set_value('documentList'); ?><span class="text-danger" id="docFile">(0)</span> </a>
											</li>
											<li>
												<a href="javascript:showConsultation();"><?php echo set_value('consultationList'); ?><span class="text-danger" id="constant">(0)</span> </a>
											</li>
											<li>
												<a href="javascript:showGenTask();" > <?php echo set_value('generalTask'); ?><span class="text-danger" id="task">(0)</span> </a>
											</li>
											
											
										</ul>
									</div>
								</div>
							</div>
							
							<div class="col-lg-9 col-md-8">
								<div class="card bg-white">
									
									<div id="divLawsuit" >
										<?php include_once('search/lawsuitTable.php'); ?>
									</div>
									<div id="divCustomer" style="display:none">
										<?php include_once('search/customerTable.php'); ?>
									</div>
									<div id="divEmployee" style="display:none">
										<?php include_once('search/employeeTable.php'); ?>
									</div>
									<div id="divSession" style="display:none">
										<?php include_once('search/sessionTable.php'); ?>
									</div>
									
									<div id="divLSTask" style="display:none" >
										<?php include_once('search/lawsuitTaskTable.php'); ?>
									</div>
									
									<div id="divDocument" style="display:none">
										<?php include_once('search/documentTable.php'); ?>
									</div>
									
									<div id="divConsultation" style="display:none">
										<?php include_once('search/consultationTable.php'); ?>
									</div>
									
									<div id="divGenTask" style="display:none">
										<?php include_once('search/genTaskTable.php'); ?>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			
			<?php include_once('footer.php'); ?>
			<script src="js_custom/search.js"> </script>					