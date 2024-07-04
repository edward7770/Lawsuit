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
	if(isset($_POST['showAsMenuItem']))
	{
		if($_POST['showAsMenuItem']=='true')
			$_POST['showAsMenuItem']=1;
		else 
			$_POST['showAsMenuItem']=0;
	}
	
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
			$where=" AND webpageId<>:webpageId ";
		$qry="SELECT webpageId FROM tbl_webpages WHERE isActive<>-1 AND 
		webpageDisplayname_en=:pageNameEn AND parentWebpageId=:parentWebpageId $where";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":parentWebpageId",$_POST['parentId'],PDO::PARAM_INT);
		////$stmt->bindParam(":pageNameAr",$_POST['pageNameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":pageNameEn",$_POST['pageNameEn'],PDO::PARAM_STR);
		if($_POST['action']=='update')
		{
			
			$stmt->bindParam(":webpageId",$_POST['id'],PDO::PARAM_INT);
		}
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
	
	if(isset($_POST['action']) && $_POST['action']=='add')
	{
		$qry="INSERT INTO tbl_webpages (webpageDisplayname_en,webpageDisplayname_ar,url,icon,parentWebpageId,menuOrderby,isActive,isParent,isShownOnMenu,createdBy)
				VALUES (:webpageDisplayname_en,:webpageDisplayname_ar,:url,:icon,:parentWebpageId,:menuOrderby,:isActive,:isParent,:isShownOnMenu,:createdBy);";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":webpageDisplayname_en",$_POST['pageNameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":webpageDisplayname_ar",$_POST['pageNameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":url",$_POST['url'],PDO::PARAM_STR);
		$stmt->bindParam(":icon",$_POST['icon'],PDO::PARAM_INT);
		$stmt->bindParam(":parentWebpageId",$_POST['parentId'],PDO::PARAM_INT);
		$stmt->bindParam(":menuOrderby",$_POST['orderBy'],PDO::PARAM_STR);
		$stmt->bindParam(":isActive",$_POST['active'],PDO::PARAM_INT);
		$stmt->bindParam(":isParent",$_POST['showAsMenuItem'],PDO::PARAM_INT);
		$stmt->bindParam(":isShownOnMenu",$_POST['showAsMenuItem'],PDO::PARAM_INT);
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
		$qry="SELECT webpageId,webpageDisplayname_en,webpageDisplayname_ar,url,icon,parentWebpageId,menuOrderby,isActive,isParent,isShownOnMenu FROM tbl_webpages
				WHERE isActive<>-1 AND webpageId=:webpageId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":webpageId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_webpages
			SET webpageDisplayname_en = :webpageDisplayname_en,
			  webpageDisplayname_ar = :webpageDisplayname_ar,
			  url = :url,
			  icon=:icon,
			  parentWebpageId = :parentWebpageId,
			  menuOrderby = :menuOrderby,
			  isActive = :isActive,
			  isParent = :isParent,
			  isShownOnMenu = :isShownOnMenu,
			  modifiedDate = now(),
			  modifiedBy = :modifiedBy
			WHERE webpageId =:webpageId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":webpageDisplayname_en",$_POST['pageNameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":webpageDisplayname_ar",$_POST['pageNameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":url",$_POST['url'],PDO::PARAM_STR);
		$stmt->bindParam(":icon",$_POST['icon'],PDO::PARAM_INT);
		$stmt->bindParam(":parentWebpageId",$_POST['parentId'],PDO::PARAM_INT);
		$stmt->bindParam(":menuOrderby",$_POST['orderBy'],PDO::PARAM_STR);
		$stmt->bindParam(":isActive",$_POST['active'],PDO::PARAM_INT);
		$stmt->bindParam(":isParent",$_POST['showAsMenuItem'],PDO::PARAM_INT);
		$stmt->bindParam(":isShownOnMenu",$_POST['showAsMenuItem'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":webpageId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_webpages SET isActive =-1, modifiedDate = now(), modifiedBy=:modifiedBy
			WHERE webpageId=:webpageId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":webpageId",$_POST['id'],PDO::PARAM_INT);
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