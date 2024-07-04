<?php
	session_start();
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$qry="SELECT assetCatId as id, assetCatName_$language as val FROM tbl_asset_category c WHERE isActive=1";
	$stmt=$dbo->prepare($qry);
	if($stmt->execute())
	{
		$resultCat = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	if(isset($_POST['getSelect']))
	echo '<option value="">'.$_POST['getSelect'].'</option>';
	
	foreach($resultCat as $val)
	{
		echo "<option value='".$val['id']."'>".$val['val']."</option>";
	}
?>