
<!-- Select2 CSS -->
<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
<!-- Main CSS -->
<link rel="stylesheet" href="assets/css/style.css">
<?php
	
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	WHERE r.menuid in(8)"; 
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
	/////basename($_SERVER['PHP_SELF']

?>

<div class="row">
	<div class="col-md-3">
		<label for="customer<?php echo $_POST['rowId']; ?> class="form-label"><?php echo set_value("selectCustomer"); ?>*</label>
		<select class="form-control js-example-basic-single form-small select" id='customer<?php echo $_POST['rowId']; ?>'>
			<?php echo include_once('dropdown_customer.php'); ?>
		</select>
	</div>
	<div class="col-md-3">
		<label for="customerType<?php echo $_POST['rowId']; ?>" class="form-label"><?php echo set_value("selectCustomerType"); ?>*</label>
		<select class="form-control js-example-basic-single form-small select" id='customerType<?php echo $_POST['rowId']; ?>'>
			<?php echo include_once('dropdown_customerType.php'); ?>
		</select>
	</div>
	<div class="col-md-3">
		<label for="customerAjective<?php echo $_POST['rowId']; ?>" class="form-label"><?php echo set_value("selectCustomerAdjective"); ?>*</label>
		<select class="form-control js-example-basic-single form-small select" id='customerAjective<?php echo $_POST['rowId']; ?>'>
			<?php echo include_once('dropdown_customerAdjectives.php'); ?>
		</select>
	</div>
	<div class="col-md-3">
		<label for="idCustomer<?php echo $_POST['rowId']; ?>" class="form-label"><?php echo set_value("idCustomer"); ?></label>
		<input type="file" class="form-control form-control-sm" id="idCustomer<?php echo $_POST['rowId']; ?>">
		<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
	</div>
</div>	
<div class="row">
	<div class="col-md-3">
		<label for="nationalAddress<?php echo $_POST['rowId']; ?>" class="form-label"><?php echo set_value("nationalAddressPlaintiff"); ?></label>
		<input type="file" class="form-control form-control-sm" id="nationalAddress<?php echo $_POST['rowId']; ?>">
		<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
	</div>
	
	<div class="col-md-3">
		<label for="idDefendant<?php echo $_POST['rowId']; ?>" class="form-label"><?php echo set_value("idDefendant"); ?></label>
		<input type="file" class="form-control form-control-sm" id="idDefendant<?php echo $_POST['rowId']; ?>">
		<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
	</div>
	<div class="col-md-3">
		<label class="form-label"><?php echo set_value("delete_Customer"); ?></label>
		<button class="btn btn-danger remove"><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("delete_Customer"); ?></button>
	</div>
	
</div> 
<script>




</script>