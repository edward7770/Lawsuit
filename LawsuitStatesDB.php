<?php 
	session_start();
	include_once('config/conn.php');
	
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		///echo $errorInfo;
		echo get_lang_msg('errorMessage');
		die;
	}
	
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND lsStateId<>:lsStateId ";
			
		$qry="SELECT lsStateId FROM tbl_lawsuit_states WHERE isActive=1 $where AND (lsStateName_ar=:lsStateName_ar OR lsStateName_en=:lsStateName_en)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsStateName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":lsStateName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":lsStateId",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		if($result)
			exit(get_lang_msg('record_already_exists'));
	}
	if(isset($_POST['action']) && $_POST['action']=='add' )
	{
		$qry="INSERT INTO tbl_lawsuit_states(lsStateName_en,lsStateName_ar,lscolor,isActive,createdBy) 
											VALUES(:lsStateName_en,:lsStateName_ar,:lscolor,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsStateName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":lsStateName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":lscolor",$_POST['lscolor'],PDO::PARAM_STR);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			echo get_lang_msg('added_successfully');
			exit('1');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
	if(isset($_POST['action']) && $_POST['action']=='getData' )
	{
		$qry="SELECT * FROM tbl_lawsuit_states WHERE isActive=1 AND lsStateId=:lsStateId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsStateId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_lawsuit_states SET lsStateName_en=:lsStateName_en, lsStateName_ar=:lsStateName_ar, lscolor=:lscolor, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE lsStateId=:lsStateId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsStateName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":lsStateName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":lscolor",$_POST['lscolor'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsStateId",$_POST['id'],PDO::PARAM_INT);
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
	}
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$qry="UPDATE tbl_lawsuit_states SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE lsStateId=:lsStateId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsStateId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('deleted_successfully');
			exit('1');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
	
?>