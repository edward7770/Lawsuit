<?php 
	session_start();
	include_once('config/conn.php');
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	////print_r($_POST);
	///exit;
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	/*
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		$column="";
		if($_POST['action']=='update')
			$where=" AND currencyId<>:currencyId";
		if(!empty($_POST['nameAr']))
			$column=" name_ar=:name_ar OR ";
			
		$qry="SELECT currencyId FROM tbl_currency WHERE isActive=1 $where AND ($column name_en=:name_en)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":name_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":name_ar",$_POST['nameAr'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":currencyId",$_POST['id'],PDO::PARAM_INT);
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
	*/
	
	if(isset($_POST['action']) && $_POST['action']=='add')
	{
		$qry="SELECT GROUP_CONCAT(c.`currencyId`) AS currencyId FROM `tbl_currency` c WHERE c.`isActive`=1";
		$stmt=$dbo->prepare($qry);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		if($result && !empty($result[0]['currencyId']))
		{
			$isDelete=deleteDoc($dbo,$result[0]['currencyId']);
			if($isDelete==0)
				exit(errorMessage(get_lang_msg('errorMessage')));
		}
		
		$qry="INSERT INTO tbl_currency(name_en,name_ar,currencyText,isActive,createdBy) 
											VALUES(:name_en,:name_ar,:currencyText,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":name_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":name_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":currencyText",$_POST['currency'],PDO::PARAM_STR);
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
		$qry="SELECT l.`currencyId` as id, l.`name_ar`,l.`name_en`,currencyText,l.`createdBy`,l.`createdDate` FROM tbl_currency l WHERE l.`isActive`=1 AND l.`currencyId`=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_currency SET name_en=:name_en, name_ar=:name_ar,currencyText=:currencyText, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE currencyId=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":name_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":name_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":currencyText",$_POST['currency'],PDO::PARAM_STR);
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
		deleteDoc($dbo,$_POST['id']);
	}
	
	function deleteDoc($dbo,$currencyId)
	{
		if($_POST['action']=='add')
			$where=" WHERE currencyId IN($currencyId)";
		else 
			$where=" WHERE currencyId=:id";
		$qry="UPDATE tbl_currency SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() $where";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		if($_POST['action']=='del')
			$stmt->bindParam(":id",$currencyId,PDO::PARAM_INT);
		if($stmt->execute())
		{
			if($_POST['action']=='del')
			{
				echo get_lang_msg('deleted_successfully');
				exit('1');
			}
			else 
				return 1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
			return 0;
		}
	}
	
?>