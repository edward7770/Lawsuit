<?php 
	session_start();
	include_once('config/conn.php');
	////print_r($_POST);
	///exit;
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		///echo $errorInfo;
		echo get_lang_msg('errorMessage');
		die;
	}
	
	if(isset($_POST['action']) && $_POST['action']=='Add' )
	{
		$_POST['userName']="null";
		$_POST['password']="null";
		////$language=$_SESSION['lang'];
		
		$qry="INSERT INTO `tbl_customers` VALUES (DEFAULT,:clientTypeId,
			:customerName_ar,:customerName_en,:idPassportNo,:crNo,:cityId,:address,:postBox,:mobileNo,
			:customerEmail,:nationalityId,:username,:password,:endDateAgency,:agencyFilePath,:agencyOrgFileName,
			:agencyFileName,:notes,1,:createdBy,DEFAULT,NULL,NULL)";
			
		$agencyFilePath="";
		$agencyOrgFileName="";
		$agencyFileName="test";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":clientTypeId",$_POST['custTypeId'],PDO::PARAM_INT);
		$stmt->bindParam(":customerName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":customerName_en",$_POST['nameEn'],PDO::PARAM_STR);
		/////$stmt->bindParam(":companyName",$_POST['companyName'],PDO::PARAM_STR);
		$stmt->bindParam(":idPassportNo",$_POST['passportNo'],PDO::PARAM_INT);
		$stmt->bindParam(":crNo",$_POST['crNo'],PDO::PARAM_INT);
		$stmt->bindParam(":cityId",$_POST['city'],PDO::PARAM_INT);
		$stmt->bindParam(":address",$_POST['address'],PDO::PARAM_STR);
		$stmt->bindParam(":postBox",$_POST['postBox'],PDO::PARAM_STR);
		/////$stmt->bindParam(":telephoneNo",$_POST['telephoneNo'],PDO::PARAM_INT);
		if(empty($_POST['mobileNo']))
			$stmt->bindParam(":mobileNo",$_POST['mobileNo'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":mobileNo",$_POST['mobileNo'],PDO::PARAM_STR);
		$stmt->bindParam(":customerEmail",$_POST['email'],PDO::PARAM_STR);
		$stmt->bindParam(":nationalityId",$_POST['nationality'],PDO::PARAM_INT);
		$stmt->bindParam(":username",$_POST['userName'],PDO::PARAM_NULL);
		$stmt->bindParam(":password",$_POST['password'],PDO::PARAM_NULL);
		if(empty($_POST['endDate']))
			$stmt->bindParam(":endDateAgency",$_POST['endDate'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":endDateAgency",$_POST['endDate'],PDO::PARAM_STR);
		$stmt->bindParam(":agencyFilePath",$agencyFilePath,PDO::PARAM_STR);
		$stmt->bindParam(":agencyOrgFileName",$agencyOrgFileName,PDO::PARAM_STR);
		$stmt->bindParam(":agencyFileName",$agencyFileName,PDO::PARAM_STR);
		
		$stmt->bindParam(":notes",$_POST['note'],PDO::PARAM_STR);
		
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
		$qry="SELECT * FROM tbl_customers c WHERE c.`isActive`<>-1 AND c.`customerId`=:customerId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":customerId",$_POST['id'],PDO::PARAM_STR);
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
	
	if(isset($_POST['action']) && $_POST['action']=='edit' )
	{
		//////username=:username,`password`=:password,
		$qry="UPDATE tbl_customers SET custTypeId=:custTypeId, customerName_en=:customerName_en,customerName_ar=:customerName_ar,idPassportNo=:idPassportNo,crNo=:crNo,
		cityId=:cityId,address=:address,postBox=:postBox,mobileNo=:mobileNo,
		customerEmail=:customerEmail,nationalityId=:nationalityId,
		endDateAgency=:endDateAgency,notes=:notes,modifiedBy=:modifiedBy, modifiedDate=NOW()
		WHERE customerId=:customerId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":custTypeId",$_POST['custTypeId'],PDO::PARAM_INT);
		$stmt->bindParam(":customerName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":customerName_en",$_POST['nameEn'],PDO::PARAM_STR);
		////$stmt->bindParam(":companyName",$_POST['companyName'],PDO::PARAM_STR);
		$stmt->bindParam(":idPassportNo",$_POST['passportNo'],PDO::PARAM_INT);
		$stmt->bindParam(":crNo",$_POST['crNo'],PDO::PARAM_INT);
		$stmt->bindParam(":cityId",$_POST['city'],PDO::PARAM_INT);
		$stmt->bindParam(":address",$_POST['address'],PDO::PARAM_STR);
		$stmt->bindParam(":postBox",$_POST['postBox'],PDO::PARAM_STR);
		//////$stmt->bindParam(":telephoneNo",$_POST['telephoneNo'],PDO::PARAM_INT);
		if(empty($_POST['mobileNo']))
			$stmt->bindParam(":mobileNo",$_POST['mobileNo'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":mobileNo",$_POST['mobileNo'],PDO::PARAM_STR);	
		$stmt->bindParam(":customerEmail",$_POST['email'],PDO::PARAM_STR);
		$stmt->bindParam(":nationalityId",$_POST['nationality'],PDO::PARAM_INT);
		////$stmt->bindParam(":username",$_POST['userName'],PDO::PARAM_STR);
		/////$stmt->bindParam(":password",$_POST['password'],PDO::PARAM_STR);
		if(empty($_POST['endDate']))
			$stmt->bindParam(":endDateAgency",$_POST['endDate'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":endDateAgency",$_POST['endDate'],PDO::PARAM_STR);
		///$stmt->bindParam(":agencyFilePath",$agencyFilePath,PDO::PARAM_STR);
		///$stmt->bindParam(":agencyOrgFileName",$agencyOrgFileName,PDO::PARAM_STR);
		///$stmt->bindParam(":agencyFileName",$agencyFileName,PDO::PARAM_STR);
		$stmt->bindParam(":notes",$_POST['note'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":customerId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_customers SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE customerId=:customerId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":customerId",$_POST['id'],PDO::PARAM_INT);
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