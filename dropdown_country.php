<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$qry="SELECT c.`countryId`, c.countryName_".$language." as countryName FROM `tbl_country` c WHERE c.`isActive`=1";
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
		echo "<option value='".$val['countryId']."'>".$val['countryName']."</option>";
	}
	
?>