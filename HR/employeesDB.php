<?php 
	session_start();
	include_once('../config/conn.php');
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	////print_r($_POST);
	///exit;
	if(isset($_POST['active']))
	{
		if($_POST['active']=='true')
			$_POST['active']=1;
		else 
			$_POST['active']=0;
		/////print_r($_POST);
	}
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		////echo get_lang_msg('errorMessage');
		die;
	}
	
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if(!empty($_POST['passNo'])) $where.="AND passportNo=:passportNo ";
		else
		{
			if(!empty($_POST['idNo'])) $where.="AND idNo=:idNo";
			else $where.=" AND (empName_ar=:empName_ar OR empName_en=:empName_en)";
		}
		if($_POST['action']=='update')
			$where.=" AND empId<>:empId ";
		
		$qry="SELECT empId FROM tbl_employees WHERE isActive<>-1 $where";
		$stmt=$dbo->prepare($qry);
		if(!empty($_POST['passNo']))
			$stmt->bindParam(":passportNo",$_POST['passNo'],PDO::PARAM_STR);
		else
		{
			if(!empty($_POST['idNo']))
				$stmt->bindParam(":idNo",$_POST['idNo'],PDO::PARAM_STR);
			else 
			{
				$stmt->bindParam(":empName_en",$_POST['nameEn'],PDO::PARAM_STR);
				$stmt->bindParam(":empName_ar",$_POST['nameAr'],PDO::PARAM_STR);
			}
		}
		if($_POST['action']=='update')
			$stmt->bindParam(":empId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="INSERT INTO tbl_employees
            (empCatId,empName_en,empName_ar,empNo,joinDate,
             phoneNo,mobileNo,dob,nationalityId,religion,gender,
             idNo,expiryDate,issueDate,passportNo,issueDatePassNo,
             expiryDatePassNo,email,isActive,createdBy
			)
		VALUES
			(:empCatId,:empName_en,:empName_ar,:empNo,:joinDate,
			:phoneNo,:mobileNo,:dob,:nationalityId,:religion,:gender,
			:idNo,:expiryDate,:issueDate,:passportNo,:issueDatePassNo,
			:expiryDatePassNo,:email,:isActive,:createdBy
			)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":empCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":empName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":empName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		
		$stmt->bindParam(":empNo",$_POST['empNo'],PDO::PARAM_STR);
		$stmt->bindParam(":joinDate",$_POST['joinDate'],PDO::PARAM_STR);
		$stmt->bindParam(":phoneNo",$_POST['phone'],PDO::PARAM_STR);
		$stmt->bindParam(":mobileNo",$_POST['mobile'],PDO::PARAM_STR);
		$stmt->bindParam(":dob",$_POST['dob'],PDO::PARAM_STR);
		$stmt->bindParam(":nationalityId",$_POST['nation'],PDO::PARAM_INT);
		$stmt->bindParam(":religion",$_POST['religion'],PDO::PARAM_STR);
		$stmt->bindParam(":gender",$_POST['gender'],PDO::PARAM_STR);
		$stmt->bindParam(":idNo",$_POST['idNo'],PDO::PARAM_STR);
		$stmt->bindParam(":idNo",$_POST['idNo'],PDO::PARAM_STR);
		$stmt->bindParam(":issueDate",$_POST['dateIssId'],PDO::PARAM_STR);
		$stmt->bindParam(":expiryDate",$_POST['dateExpId'],PDO::PARAM_STR);
		$stmt->bindParam(":passportNo",$_POST['passNo'],PDO::PARAM_STR);
		$stmt->bindParam(":issueDatePassNo",$_POST['dateIssPass'],PDO::PARAM_STR);
		$stmt->bindParam(":expiryDatePassNo",$_POST['dateExpPass'],PDO::PARAM_STR);
		
		$stmt->bindParam(":email",$_POST['email'],PDO::PARAM_STR);
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
		$qry="SELECT empId as id,empCatId,empName_en,empName_ar,empNo,joinDate,phoneNo,mobileNo,dob,nationalityId,religion,gender,
				idNo,expiryDate,issueDate,passportNo,issueDatePassNo,expiryDatePassNo,email,isActive FROM tbl_employees e WHERE isActive<>-1 AND empId=:empId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":empId",$_POST['id'],PDO::PARAM_STR);
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
		include_once('../notificationsInsert.php');
		$Notifications=InsertNotifications('employee', $_POST['id'],$_POST['dateExpId'],$_POST['dateExpPass']);
		if($Notifications!='inserted' && $Notifications!='noNeedToUpdate')
		{
			echo $Notifications;
			exit();
		}
		$qry="UPDATE tbl_employees SET empCatId=:empCatId,
			empName_en=:empName_en, empName_ar=:empName_ar,empNo=:empNo,
			joinDate=:joinDate,phoneNo=:phoneNo,mobileNo=:mobileNo,dob=:dob,
			nationalityId=:nationalityId,religion=:religion,gender=:gender,
            idNo=:idNo,expiryDate=:expiryDate,issueDate=:issueDate,
			passportNo=:passportNo,issueDatePassNo=:issueDatePassNo,
            expiryDatePassNo=:expiryDatePassNo,email=:email,
			isActive=:isActive, modifiedDate=NOW(), modifiedBy=:modifiedBy
			WHERE empId=:empId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":empCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":empName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":empName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":empNo",$_POST['empNo'],PDO::PARAM_STR);
		$stmt->bindParam(":joinDate",$_POST['joinDate'],PDO::PARAM_STR);
		$stmt->bindParam(":phoneNo",$_POST['phone'],PDO::PARAM_STR);
		$stmt->bindParam(":mobileNo",$_POST['mobile'],PDO::PARAM_STR);
		$stmt->bindParam(":dob",$_POST['dob'],PDO::PARAM_STR);
		$stmt->bindParam(":nationalityId",$_POST['nation'],PDO::PARAM_INT);
		$stmt->bindParam(":religion",$_POST['religion'],PDO::PARAM_STR);
		$stmt->bindParam(":gender",$_POST['gender'],PDO::PARAM_STR);
		$stmt->bindParam(":idNo",$_POST['idNo'],PDO::PARAM_STR);
		$stmt->bindParam(":idNo",$_POST['idNo'],PDO::PARAM_STR);
		$stmt->bindParam(":issueDate",$_POST['dateIssId'],PDO::PARAM_STR);
		$stmt->bindParam(":expiryDate",$_POST['dateExpId'],PDO::PARAM_STR);
		$stmt->bindParam(":passportNo",$_POST['passNo'],PDO::PARAM_STR);
		$stmt->bindParam(":issueDatePassNo",$_POST['dateIssPass'],PDO::PARAM_STR);
		$stmt->bindParam(":expiryDatePassNo",$_POST['dateExpPass'],PDO::PARAM_STR);
		$stmt->bindParam(":email",$_POST['email'],PDO::PARAM_STR);
		$stmt->bindParam(":isActive",$_POST['active'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":empId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_employees SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE empId=:empId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":empId",$_POST['id'],PDO::PARAM_INT);
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