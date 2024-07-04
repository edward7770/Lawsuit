<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	$qry="SELECT empId as id, empName_$language as val FROM tbl_employees WHERE isActive=1;";
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
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