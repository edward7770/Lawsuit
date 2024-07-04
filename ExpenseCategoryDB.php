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
			$where=" AND expCatId<>:expCatId ";
			
		$qry="SELECT expCatId FROM tbl_expense_category WHERE isActive=1 $where AND (expCatName_ar=:expCatName_ar OR expCatName_en=:expCatName_en)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expCatName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":expCatName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":expCatId",$_POST['id'],PDO::PARAM_STR);
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

		$qry="INSERT INTO tbl_expense_category(expCatName_en,expCatName_ar,isActive,createdBy) 
											VALUES(:expCatName_en,:expCatName_ar,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expCatName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":expCatName_ar",$_POST['nameAr'],PDO::PARAM_STR);
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
		$qry="SELECT c.`expCatId` AS id, c.`expCatName_ar`, c.`expCatName_en`,c.`isActive` FROM tbl_expense_category c WHERE c.isActive=1 AND expCatId=:expCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expCatId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_expense_category  SET expCatName_en=:expCatName_en, expCatName_ar=:expCatName_ar, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE expCatId=:expCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expCatName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":expCatName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":expCatId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_expense_category SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE expCatId=:expCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":expCatId",$_POST['id'],PDO::PARAM_INT);
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