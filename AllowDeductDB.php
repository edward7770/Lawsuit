<?php 
	session_start();
	include_once('config/conn.php');
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		$column="";
		if(!empty($_POST['nameAr']))
			$column=" allowDeductName_ar=:allowDeductName_ar OR ";
		if($_POST['action']=='update')
			$where=" AND allowDeductId<>:allowDeductId ";
			
		$qry="SELECT allowDeductId FROM tbl_allow_deduct WHERE isActive=1 $where AND ($column allowDeductName_en=:allowDeductName_en)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":allowDeductName_en",$_POST['nameEn'],PDO::PARAM_STR);
		if(!empty($_POST['nameAr']))
			$stmt->bindParam(":allowDeductName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":allowDeductId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="INSERT INTO tbl_allow_deduct(allowDeductName_en,allowDeductName_ar,type,isActive,createdBy) 
											VALUES(:allowDeductName_en,:allowDeductName_ar,:type,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":allowDeductName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":allowDeductName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":type",$_POST['type'],PDO::PARAM_INT);
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
		$qry="SELECT a.`allowDeductId` AS id, a.`allowDeductName_ar` AS name_ar, a.`allowDeductName_en` AS name_en, a.`type` FROM `tbl_allow_deduct` a WHERE a.`isActive`=1 AND allowDeductId=:id;";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_allow_deduct SET allowDeductName_en=:nameEn, allowDeductName_ar=:nameAr,type=:type, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE allowDeductId=:id";
		$stmt=$dbo->prepare($qry);
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":nameEn",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":nameAr",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":type",$_POST['type'],PDO::PARAM_INT);
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
	}
	
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$qry="UPDATE tbl_allow_deduct SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE allowDeductId=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
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