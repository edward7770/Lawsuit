<?php

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$qry="SELECT n.`nationalityId`, n.`nationalityName` FROM `tbl_nationality` n WHERE n.`isActive`=1";
	$stmt=$dbo->prepare($qry);

	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result_nationality = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	foreach($result_nationality as $val)
	{
		echo "<option value='".$val['nationalityId']."'>".$val['nationalityName']."</option>";
	}

?>