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
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		$column="";
		if($_POST['action']=='update')
			$where=" AND lslocationId<>:lslocationId";
		if(!empty($_POST['nameAr']))
			$column=" name_ar=:name_ar OR ";
			
		$qry="SELECT lslocationId FROM tbl_lawsuit_location WHERE isActive=1 $where AND ($column name_en=:name_en)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":name_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":name_ar",$_POST['nameAr'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":lslocationId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="INSERT INTO tbl_lawsuit_location(name_en,name_ar,isActive,createdBy) 
											VALUES(:name_en,:name_ar,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":name_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":name_ar",$_POST['nameAr'],PDO::PARAM_STR);
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
		$qry="SELECT l.`lslocationId` as id, l.`name_ar`,l.`name_en`,l.`createdBy`,l.`createdDate` FROM tbl_lawsuit_location l WHERE l.`isActive`=1 AND l.`lslocationId`=:id";
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
		$qry="UPDATE tbl_lawsuit_location SET name_en=:name_en, name_ar=:name_ar, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE lslocationId=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":name_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":name_ar",$_POST['nameAr'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_lawsuit_location SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE lslocationId=:id";
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