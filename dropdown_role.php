<?php 
	if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	include_once('config/conn.php');
	$where="";
	if($superAdminId!=$_SESSION['roleId'])
		$where=" AND r.`roleId`<>1";
	$qry="SELECT r.roleId as id , r.roleName as val FROM tbl_role r WHERE r.isActive=1 $where";
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