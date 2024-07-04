<?php
if(isset($_POST['contractId']))
{
	include_once('config/conn.php');
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	
	$qry="SELECT d.contractAr, d.contractEn FROM tbl_lawsuit_contract d WHERE d.isActive=1 
		AND d.`lsContractId`=:contractId";
	$stmt=$dbo->prepare($qry);
	///$stmt->bindParam(":lsDetailsId",$lsDetailsId,PDO::PARAM_INT);
	$stmt->bindParam(":contractId",$_POST['contractId'],PDO::PARAM_INT);
	if($stmt->execute())
	{
		$resultContract = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if($resultContract)
			echo json_encode(['status'=>true, 'data'=>$resultContract],JSON_INVALID_UTF8_SUBSTITUTE);
		else 
			echo json_encode(['status'=>false, 'data'=>'']);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
		///get_lang_msg('errorMessage');
		exit('0');
	}
	/////print_r($resultContract);
	
}
?>