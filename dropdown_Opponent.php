<?php

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$qry="SELECT opponentId as id, oppoName_ar, oppoName_en FROM tbl_opponents WHERE isActive=1";
	$stmt=$dbo->prepare($qry);
	
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result_city = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	///if(!isset($_POST['selected']))
	///	$_POST['selected']="";
	////$selectedOponentArray = explode (",", $_POST['selected']);
	if(isset($_POST['showSelect']))
	echo '<option value="">'.$_POST['showSelect'].'</option>';
	foreach($result_city as $val)
	{
		///$selected='';
		///if (in_array($val['id'], $selectedOponentArray))
		////$selected='selected';
			
		echo "<option value='".$val['id']."'>".$val['oppoName_'.$language]."</option>";
	}
	
?>