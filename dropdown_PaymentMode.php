<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$qry="SELECT paymentModeId as id,name_$language as val FROM `tbl_payment_mode` WHERE isActive=1";
	$stmt=$dbo->prepare($qry);
	
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result_city = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	/*
	if(!isset($_POST['selected']))
		$_POST['selected']="";
	$selectedOponentArray = explode (",", $_POST['selected']);
	*/
	if(isset($_POST['showSelect']))
	echo '<option value="">'.$_POST['showSelect'].'</option>';
	foreach($result_city as $val)
	{
		///$selected='';
		///if (in_array($val['id'], $selectedOponentArray))
		/////	$selected='selected';
			
		echo "<option value='".$val['id']."'>".$val['val']."</option>";
	}
	
?>