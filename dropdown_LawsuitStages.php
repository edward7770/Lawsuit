<?php 
	if(isset($_POST['lsMId']) && !empty($_POST['lsMId']))
	{
		include_once('config/conn.php');
		$qry="CALL sp_getAllLSDetailIds_FullStages(:lsMId,:language);";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMId",$_POST['lsMId'],PDO::PARAM_INT);
		$stmt->bindParam(":language",$language,PDO::PARAM_STR);
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
			echo "<option value='".$val['lsStagesId'].",".$val['lsDetailsId']."'>".$val['lsStagesName']."</option>";
		}
	}
	
?>