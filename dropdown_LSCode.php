<?php 
	include_once('config/conn.php');
	$qry="SELECT l.`lsMasterId` as id, l.`ls_code` as val FROM `tbl_lawsuit_master` l WHERE l.`isActive`=1";
	$stmt=$dbo->prepare($qry);
	if($stmt->execute())
	{
		$resultSubCat = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	if(isset($_POST['getSelect']))
		echo '<option value="">'.$_POST['getSelect'].'</option>';
		
	
	foreach($resultSubCat as $val)
	{
		echo "<option value='".$val['id']."'>".$val['val']."</option>";
	}
?>