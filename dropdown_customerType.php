<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$where="";
	if(isset($_POST['custId'])) $where=" AND custTypeId=(SELECT custTypeId FROM `tbl_customers` c WHERE c.`customerId`=:custTypeId)";
		
	$qry="SELECT custTypeId as id,typeName_$language as val FROM `tbl_customertypes` WHERE isActive=1".$where;
	$stmt=$dbo->prepare($qry);
	if(isset($_POST['custId']))
	$stmt->bindParam(":custTypeId",$_POST['custId'],PDO::PARAM_INT);
	if($stmt->execute())
	{
		$result_country = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	foreach($result_country as $val)
	{
		echo "<option value='".$val['id']."'>".$val['val']."</option>";
	}
	
?>