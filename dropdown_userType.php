<?php 
	include_once('config/conn.php');
	$qry="SELECT userTypeId AS id, userType as val FROM user_type where isActive=1";
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