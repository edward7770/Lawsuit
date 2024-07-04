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
			$where=" AND subAssetCatId<>:subAssetCatId ";
			
		$qry="SELECT subAssetCatId FROM tbl_asset_subcategory
			WHERE isActive=1 AND (subAssetCatName_ar=:nameAr OR subAssetCatName_en=:nameEn) AND assetCatId=:catId $where";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":nameEn",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":nameAr",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":catId",$_POST['catId'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":subAssetCatId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="INSERT INTO tbl_asset_subcategory(assetCatId,subAssetCatName_en,subAssetCatName_ar,isActive,createdBy) 
				VALUES(:assetCatId,:subAssetCatName_en,:subAssetCatName_ar,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":assetCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":subAssetCatName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":subAssetCatName_ar",$_POST['nameAr'],PDO::PARAM_STR);
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
		$qry="SELECT subAssetCatId as id,assetCatId as catId,subAssetCatName_ar,subAssetCatName_en FROM tbl_asset_subcategory WHERE isActive=1 AND subAssetCatId=:subAssetCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":subAssetCatId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_asset_subcategory SET AssetCatId=:AssetCatId, subAssetCatName_ar=:nameAr, subAssetCatName_en=:nameEn, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE subAssetCatId=:subAssetCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":AssetCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":nameEn",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":nameAr",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":subAssetCatId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_asset_subcategory SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE subAssetCatId=:subAssetCatId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":subAssetCatId",$_POST['id'],PDO::PARAM_INT);
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