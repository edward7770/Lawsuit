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
	
	
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND roleId<>:roleId ";
			
		$qry="SELECT r.roleId FROM tbl_role r WHERE r.isActive<>-1 AND r.roleName=:roleName $where";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":roleName",$_POST['roleName'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":roleId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="INSERT INTO tbl_role (roleName,roleDefaultPage,isActive,createdBy)
							VALUES(:roleName,:roleDefaultPage,:isActive,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":roleName",$_POST['roleName'],PDO::PARAM_STR);
		$stmt->bindParam(":roleDefaultPage",$_POST['pageId'],PDO::PARAM_INT);
		$stmt->bindParam(":isActive",$_POST['active'],PDO::PARAM_INT);
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
		$qry="SELECT roleId, roleName,roleDefaultPage, isActive FROM tbl_role r WHERE isActive<>-1 AND roleId=:roleId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":roleId",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//print_r($result);
			echo json_encode(['status'=>true, 'data'=>$result],JSON_INVALID_UTF8_SUBSTITUTE);

		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}

	}
	
	if(isset($_POST['action']) && $_POST['action']=='update' )
	{
		$qry="UPDATE tbl_role SET roleName=:roleName,roleDefaultPage=:roleDefaultPage,isActive=:isActive, modifiedDate=NOW(), modifiedBy=:modifiedBy WHERE roleId=:roleId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":roleName",$_POST['roleName'],PDO::PARAM_STR);
		$stmt->bindParam(":roleDefaultPage",$_POST['pageId'],PDO::PARAM_INT);
		$stmt->bindParam(":isActive",$_POST['active'],PDO::PARAM_INT);
		$stmt->bindParam(":roleId",$_POST['role'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":roleId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_role SET isActive=-1, modifiedDate=NOW(), modifiedBy=:modifiedBy WHERE roleId=:roleId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":roleId",$_POST['id'],PDO::PARAM_INT);
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