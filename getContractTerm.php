<?php 
if(isset($_POST['getContractTerm']))
{
	include_once('config/conn.php');
	$qry="SELECT torsId, tors_en, tors_ar FROM tbl_tors c WHERE c.isActive=1";
	$stmt=$dbo->prepare($qry);
	if($stmt->execute())
	{
		$resultContractTerm = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode(['status'=>true, 'data'=>$resultContractTerm],JSON_INVALID_UTF8_SUBSTITUTE);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
}
?>