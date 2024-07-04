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
			$where=" AND assetCatId<>:assetCatId ";
			
		$qry="SELECT assetCatId FROM tbl_asset_category WHERE isActive=1 $where AND (assetCatName_ar=:assetCatName_ar OR assetCatName_en=:assetCatName_en)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":assetCatName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":assetCatName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":assetCatId",$_POST['id'],PDO::PARAM_STR);
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

		$qry="INSERT INTO tbl_asset_category(assetCatName_en,assetCatName_ar,isActive,createdBy) 
											VALUES(:assetCatName_en,:assetCatName_ar,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":assetCatName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":assetCatName_ar",$_POST['nameAr'],PDO::PARAM_STR);
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
		$qry="SELECT assetCatId as id,assetCatName_en,assetCatName_ar,createdDate,createdBy FROM tbl_asset_category WHERE isActive=1 AND assetCatId=:assetCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":assetCatId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_asset_category SET assetCatName_en=:assetCatName_en, assetCatName_ar=:assetCatName_ar, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE assetCatId=:assetCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":assetCatName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":assetCatName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":assetCatId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_asset_category SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE assetCatId=:assetCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":assetCatId",$_POST['id'],PDO::PARAM_INT);
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