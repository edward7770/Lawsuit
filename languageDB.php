<?php 
	session_start();
	include_once('config/conn.php');
	////print_r($_POST);
	///exit;
	if(isset($_POST['active']))
	{
		if($_POST['active']=='true')
			$_POST['active']=1;
		else 
			$_POST['active']=0;
	}
	
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		///echo $errorInfo;
		echo get_lang_msg('errorMessage');
		die;
	}
	
	
	if(isset($_POST['action']) && $_POST['action']=='getData' )
	{
		$qry="CALL sp_getLanguageDetail(:menuId,:languageId)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":menuId",$_POST['mId'],PDO::PARAM_STR);
		$stmt->bindParam(":languageId",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//print_r($result);
			echo json_encode(['status'=>true, 'data'=>$result],JSON_INVALID_UTF8_SUBSTITUTE);

		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}

	}
	
	if(isset($_POST['action']) && $_POST['action']=='update' )
	{
		$qry="UPDATE language SET en=:en,ar=:ar,modifiedDate=now(),modifiedBy=:modifiedBy 
		WHERE id=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('modified_successfully');
			exit('1');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		die();
	}
?>