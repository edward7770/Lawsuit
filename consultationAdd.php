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
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && !empty($_POST['id']))
	{
		$qry="SELECT consId, customerId, lawyerId,title,contractDate,amount, tax, taxAmount, totalAmount, notes_ar, notes_en FROM tbl_consultations c 
		WHERE c.isActive=1 AND consId=:consId";
	
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":consId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$resultEdit = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($resultEdit as $row)
			{
				$custId=$row['customerId'];
				$title=$row['title'];
				$lawyId=$row['lawyerId'];
				$Date=$row['contractDate'];
				$amount=$row['amount'];
				$tax=$row['tax'];
				$taxAmount=$row['taxAmount'];
				$totalAmount=$row['totalAmount'];
				$notes_ar=$row['notes_ar'];
				$notes_en=$row['notes_en'];
				$setId=$row['consId'];
			}
			/////print_r($resultEdit);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
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
						<h5><?php echo set_value('addNewConsultation'); ?></h5>
					</div>
				</div>
				
				<?php 
					include_once('loader.php'); 
					////autocomplete="off" in form
				?>
				<form id="form2" action="javascript:add();" method="post" >	
				<div class="card mb-0">
					<div class="card-body pb-0">
						
							<div class="row">
								<div class="col-lg-3 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="customer" class="form-label"><?php echo set_value("customer"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" id='customer' >
											<option value=""><?php echo set_value("select"); ?></option>
											<?php echo include_once('dropdown_customer.php'); ?>
										</select>
										<?php if(isset($custId)) echo '<input type="hidden" id="setCustId" value="'.$custId.'" />'; ?>
									</div>
								</div>
								<div class="col-lg-3 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="lawsuitLawyer" class="form-label"><?php echo set_value("lawsuitLawyer"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" id='lawsuitLawyer'>
											<option value=""><?php echo set_value("select"); ?></option>
											<?php echo include_once('dropdown_lawsuitLawyer.php'); ?>
										</select>
										<?php if(isset($lawyId)) echo '<input type="hidden" id="setLawyId" value="'.$lawyId.'" />'; ?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="title" class="form-label"><?php echo set_value("titleConsultation"); ?><span class="text-danger"> * </span></label>
										<input type="text" class="form-control form-control-sm" id="title" value="<?php if(isset($title)) echo $title; ?>" placeholder="<?php echo set_value("titleConsultation"); ?>" >
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="date" class="form-label"><?php echo set_value("consultationContractDate"); ?><span class="text-danger"> * </span></label>
										<input type="date" class="form-control form-control-sm" id="date" value="<?php if(isset($Date)) echo $Date; ?>" placeholder="<?php echo set_value("ContractDate"); ?>">
									</div>
								</div>
								
								<div class="col-lg-2 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="amountContract" class="form-label"><?php echo set_value("consultationAmountContract"); ?></label>
										<input type="number" class="form-control form-control-sm" id="amountContract" value="<?php if(isset($amount)) echo $amount; ?>" placeholder="<?php echo set_value("amountContract"); ?>">
									</div>
								</div>
								<div class="col-lg-2 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="taxValue" class="form-label"><?php echo set_value("taxValue"); ?></label>
										<input type="number" class="form-control form-control-sm" id="taxValue" value="<?php if(isset($tax) && !empty($tax)) echo $tax; else echo '5'; ?>" placeholder="<?php echo set_value("taxValue"); ?>">
									</div>
								</div>
							    <div class="col-lg-2 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="taxValueAmount" class="form-label"><?php echo set_value("taxValueAmount"); ?></label>
										<input type="number" class="form-control form-control-sm" id="taxValueAmount" disabled value="<?php if(isset($taxAmount) && !empty($taxAmount)) echo $taxAmount; else echo '0'; ?>" placeholder="<?php echo set_value("taxValueAmount"); ?>">
									</div>
								</div>
								
								<div class="col-lg-3 col-md-6 col-sm-12">
									<div class="form-group">
										<label for="ContAmountinclTax" class="form-label"><?php echo set_value("ContAmountinclTax"); ?>%</label>
										<input type="text" class="form-control form-control-sm" id="ContAmountinclTax" step="0.01" value="<?php if(isset($totalAmount)) echo $totalAmount; else '0.00'; ?>" disabled>
									</div>
								</div>
							</div>
							<div class="invoice-card-title">
								<h6><?php echo set_value('contractDetails_en'); ?> :</h6>
							</div>
							
							
							<div class="row">
								<div class="col-md-12">	
								<textarea class="summernote form-control" id="ContractTermsEn" placeholder="Description"><?php if(isset($notes_ar)) echo $notes_ar; ?></textarea>
								</div>
							</div>
							
							<br/>
							
							<div class="invoice-card-title">
								<h6><?php echo set_value('contractDetails_ar'); ?> :</h6>
							</div>
							
							<div class="row">
								<div class="col-md-12">	
									<textarea class="summernote form-control" id="ContractTermsAr" placeholder="Description"><?php if(isset($notes_en)) echo $notes_en; ?></textarea>
								</div>
							</div>
							<br>
							<br>
							<div class="add-customer-btns text-end text-center">
								<a href="#" type="reset" class="btn customer-btn-cancel">Cancel</a>
								<a href="#" type="submit" id='add' class="btn customer-btn-save"><?php echo set_value('add'); ?></a>
							</div>
							<?php if(isset($setId) && !empty(isset($setId)) )  echo '<input type=hidden id="setId" value="'.$setId.'"/>'; ?>
						<br>
					</div>
				</div>
				</form>	
				<!--</form> -->
			</div>
		</div>
		
	</div>
	
	<?php include_once('footer.php'); ?>
	<script src="js_custom/consultationAdd.js"> </script>