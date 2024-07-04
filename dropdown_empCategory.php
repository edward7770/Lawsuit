<?php

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
		
	if($language=='ar')
		$qry="SELECT c.empCatid as Id, c.categoryName_ar as val FROM tbl_emp_category c WHERE c.isActive=1";
	else	
		$qry="SELECT c.empCatid as Id, c.categoryName as val FROM tbl_emp_category c WHERE c.isActive=1";
	
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
	foreach($result_city as $val)
	{
		echo "<option value='".$val['Id']."'>".$val['val']."</option>";
	}

?>