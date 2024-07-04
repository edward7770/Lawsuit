<?php 
session_start();
include_once('config/conn.php');
////$qry="call sp_getSessionDetailDasbhoard()";
$qry="call sp_getSessionDetailDasbhoardNew()";
$stmt=$dbo->prepare($qry);
/////$stmt->bindParam(":lsDetailsId",$_POST[''],PDO::PARAM_STR);
if($stmt->execute())
{
	$resultSessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode(['status'=>true, 'data'=>$resultSessions],JSON_INVALID_UTF8_SUBSTITUTE);
}
else 
{
	$errorInfo = $stmt->errorInfo();
	exit($json =$errorInfo[2]);
}

?>
	