<?php 
	session_start();
	include_once('config/conn.php');
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	if(isset($_POST['active']))
	{
		if($_POST['active']=='true')
			$_POST['active']=1;
		else 
			$_POST['active']=0;
	}
	$isLawyer=-1;
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		if($_POST['action']=='getData')
		{
			echo json_encode(['status'=>false,'data'=>get_lang_msg('errorMessage')]);
		}
		else
		{
			///echo $errorInfo;
			echo get_lang_msg('errorMessage');
			die;
		}
	}
	
	if($_POST['action']!='getData' && $_POST['action']!='del')
	{
		if(empty($_POST['userName']) || empty($_POST['fullName']) || empty($_POST['passw']) || empty($_POST['userType']) || empty($_POST['role']) || empty($_POST['active']) || (empty($_POST['custId']) && empty($_POST['empId'])))
		{
			errorMessage('-1 Invalid Input');
		}
		
		if($_POST['userType']==1)
		{
			/////$_POST['custId']=$_POST['custId']; $isLawyer=-1;
			$_POST['empId']=-1;
		}
		else if($_POST['userType']==2)
		{
			$_POST['custId']=-1;
			$qry="SELECT e.`empCatId` FROM `tbl_employees` e WHERE e.`empId`=:empId";
			$stmt=$dbo->prepare($qry);
			$stmt->bindParam(":empId",$_POST['empId'],PDO::PARAM_STR);
			if($stmt->execute())
			{
				$resultEmp = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				errorMessage($json =$errorInfo[2]);
			}
			if($resultEmp && $resultEmp[0]['empCatId']==1)
			{
				$isLawyer=1;
			}
		}
	}
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND userId<>:userId ";
			
		$qry="SELECT userId FROM tbl_user u WHERE isActive<>-1 AND userName=:userName $where";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":userName",$_POST['userName'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":userId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="INSERT INTO tbl_user (userName,fullName,`password`,isActive,roleId,userType,empId,customerId,isLawyer,createdBy)
									VALUES(:userName,:fullName,:password,:isActive,:roleId,:userType,:empId,:customerId,:isLawyer,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":userName",$_POST['userName'],PDO::PARAM_STR);
		$stmt->bindParam(":fullName",$_POST['fullName'],PDO::PARAM_STR);
		$stmt->bindParam(":password",$_POST['passw'],PDO::PARAM_STR);
		$stmt->bindParam(":isActive",$_POST['active'],PDO::PARAM_INT);
		$stmt->bindParam(":roleId",$_POST['role'],PDO::PARAM_INT);
		$stmt->bindParam(":userType",$_POST['userType'],PDO::PARAM_INT);
		$stmt->bindParam(":empId",$_POST['empId'],PDO::PARAM_INT);
		$stmt->bindParam(":customerId",$_POST['custId'],PDO::PARAM_INT);
		$stmt->bindParam(":isLawyer",$isLawyer,PDO::PARAM_INT);
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
		$qry="SELECT u.userId,u.userName,u.fullName,u.password,u.isActive, u.roleId,userType,customerId AS custId, empId FROM tbl_user u
			WHERE u.isActive<>-1 AND u.userId=:userId";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":userId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_user SET userName=:userName,fullName=:fullName,`password`=:passw,
		isActive=:isActive,roleId=:roleId,userType=:userType,empId=:empId,
		customerId=:customerId,isLawyer=:isLawyer,modifiedDate=now(),modifiedBy=:modifiedBy 
		WHERE userId=:userId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":userName",$_POST['userName'],PDO::PARAM_STR);
		$stmt->bindParam(":fullName",$_POST['fullName'],PDO::PARAM_STR);
		$stmt->bindParam(":passw",$_POST['passw'],PDO::PARAM_STR);
		$stmt->bindParam(":isActive",$_POST['active'],PDO::PARAM_INT);
		$stmt->bindParam(":roleId",$_POST['role'],PDO::PARAM_INT);
		$stmt->bindParam(":userType",$_POST['userType'],PDO::PARAM_INT);
		$stmt->bindParam(":isLawyer",$isLawyer,PDO::PARAM_INT);
		$stmt->bindParam(":customerId",$_POST['custId'],PDO::PARAM_INT);
		$stmt->bindParam(":empId",$_POST['empId'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":userId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_user SET isActive=-1,modifiedDate=NOW(),modifiedBy=:modifiedBy WHERE userId=:userId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":userId",$_POST['id'],PDO::PARAM_INT);
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