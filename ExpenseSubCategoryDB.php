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
			$where=" AND subExpCatId<>:subExpCatId ";
			
		$qry="SELECT subExpCatId FROM tbl_expense_subcategory
			WHERE isActive=1 AND (subExpCatName_ar=:nameAr OR subExpCatName_en=:nameEn) AND expCatId=:catId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":nameEn",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":nameAr",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":catId",$_POST['catId'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":subExpCatId",$_POST['id'],PDO::PARAM_STR);
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

		$qry="INSERT INTO tbl_expense_subcategory(expCatId,subExpCatName_en,subExpCatName_ar,isActive,createdBy) 
											VALUES(:expCatId,:subExpCatName_en,:subExpCatName_ar,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":subExpCatName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":subExpCatName_ar",$_POST['nameAr'],PDO::PARAM_STR);
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
		$qry="SELECT subExpCatId,expCatId as catId,subExpCatName_ar,subExpCatName_en FROM tbl_expense_subcategory WHERE isActive=1 AND subExpCatId=:subExpCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":subExpCatId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_expense_subcategory SET expCatId:expCatId, subExpCatName_ar=:nameAr, subExpCatName_en=:nameEn, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE subExpCatId=:subExpCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":nameEn",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":nameAr",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":subExpCatId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_expense_subcategory SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE subExpCatId=:subExpCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":subExpCatId",$_POST['id'],PDO::PARAM_INT);
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