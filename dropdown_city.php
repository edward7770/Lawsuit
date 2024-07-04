<?php

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$qry="SELECT c.tbl_cityId, c.cityName_$language as city_name FROM `tbl_city` c WHERE c.`isActive`=1 limit 4";
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
		echo "<option value='".$val['tbl_cityId']."'>".$val['city_name']."</option>";
	}

?>