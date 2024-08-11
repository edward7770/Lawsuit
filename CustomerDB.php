<?php 
	session_start();
	include_once('config/conn.php');
	////print_r($postData);
	////print_r($_FILES);
	////exit;
	
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	if(isset($_POST['customerData']))
	{
		$postData = json_decode($_POST['customerData'], true); // assuming you are posting JSON data
		////print_r($postData);
	}
	if(isset($_FILES['agency']) || isset($_FILES['crCopy']) || isset($_FILES['idCopy']))
	{
		$file_path='uploadFiles/';
		include_once('customerDBFiles.php');
	}
	
	if(isset($_FILES['agency']))
	{
		$agencyFilePath=$file_path."customerPowerOfAttorney/";
		check_file("agency");
		$agencyFileName=set_file_name("agency","customerPowerOfAttorney");
		upload_file('agency',$agencyFilePath,$agencyFileName);
	}
	
	if(isset($_FILES['crCopy']))
	{
		$crNoFilePath=$file_path."/customerCR/";
		check_file("crCopy");
		$crNoFileName=set_file_name("crCopy","customerCR");
		upload_file('crCopy',$crNoFilePath,$crNoFileName);
	}
	if(isset($_FILES['idCopy']))
	{
		$passportNoFilePath=$file_path."/idCustomer/";
		check_file("idCopy");
		$passportNoFileName=set_file_name("idCopy","idCustomer");
		upload_file('idCopy',$passportNoFilePath,$passportNoFileName);
	}
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	
	if(isset($postData['action']) && $postData['action']=='Add' )
	{
		////$language=$_SESSION['lang'];
		
		$qry="INSERT INTO `tbl_customers`(custTypeId,customerName_en,customerName_ar,
			idPassportNo,passportNoFilePath,passportNoFileName,
			crNo,crNoFilePath,crNoFileName,vatNumber,cityId,address,postBox,mobileNo,
			customerEmail,nationalityId,endDateAgency,agencyFilePath,
			agencyFileName,notes,isActive,createdBy)
		
			VALUES (:custTypeId,:customerName_en,:customerName_ar,
			:idPassportNo,:passportNoFilePath,:passportNoFileName,
			:crNo,:crNoFilePath,:crNoFileName,:vatNumber,:cityId,:address,:postBox,:mobileNo,
			:customerEmail,:nationalityId,:endDateAgency,:agencyFilePath,
			:agencyFileName,:notes,1,:createdBy)";
			
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":custTypeId",$postData['custTypeId'],PDO::PARAM_INT);
		$stmt->bindParam(":customerName_ar",$postData['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":customerName_en",$postData['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":idPassportNo",$postData['passportNo'],PDO::PARAM_STR);
		if(empty($postData['crNo']))
			$stmt->bindParam(":crNo",$postData['crNo'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":crNo",$postData['crNo'],PDO::PARAM_STR);
		$stmt->bindParam(":vatNumber",$postData['vatNumber'],PDO::PARAM_INT);
		$stmt->bindParam(":cityId",$postData['city'],PDO::PARAM_INT);
		$stmt->bindParam(":address",$postData['address'],PDO::PARAM_STR);
		$stmt->bindParam(":postBox",$postData['postBox'],PDO::PARAM_STR);
		if(empty($postData['mobileNo']))
			$stmt->bindParam(":mobileNo",$postData['mobileNo'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":mobileNo",$postData['mobileNo'],PDO::PARAM_STR);
		$stmt->bindParam(":customerEmail",$postData['email'],PDO::PARAM_STR);
		$stmt->bindParam(":nationalityId",$postData['nationality'],PDO::PARAM_STR);
		if(empty($postData['endDate']))
			$stmt->bindParam(":endDateAgency",$postData['endDate'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":endDateAgency",$postData['endDate'],PDO::PARAM_STR);
		$stmt->bindParam(":notes",$postData['note'],PDO::PARAM_STR);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		
		if(isset($passportNoFilePath))
			$stmt->bindParam(":passportNoFilePath",$passportNoFilePath,PDO::PARAM_STR);
		else 
			$stmt->bindParam(":passportNoFilePath",$passportNoFilePath,PDO::PARAM_NULL);
		if(isset($passportNoFileName))
			$stmt->bindParam(":passportNoFileName",$passportNoFileName,PDO::PARAM_STR);
		else 
			$stmt->bindParam(":passportNoFileName",$passportNoFileName,PDO::PARAM_NULL);
		
		if(isset($crNoFilePath))
			$stmt->bindParam(":crNoFilePath",$crNoFilePath,PDO::PARAM_STR);
		else 
			$stmt->bindParam(":crNoFilePath",$crNoFilePath,PDO::PARAM_NULL);
		if(isset($crNoFileName))
			$stmt->bindParam(":crNoFileName",$crNoFileName,PDO::PARAM_STR);
		else 
			$stmt->bindParam(":crNoFileName",$crNoFileName,PDO::PARAM_NULL);
		
		if(isset($agencyFileName))
			$stmt->bindParam(":agencyFileName",$agencyFileName,PDO::PARAM_STR);
		else 
			$stmt->bindParam(":agencyFileName",$agencyFileName,PDO::PARAM_NULL);
		if(isset($agencyFilePath))
			$stmt->bindParam(":agencyFilePath",$agencyFilePath,PDO::PARAM_STR);
		else
			$stmt->bindParam(":agencyFilePath",$agencyFilePath,PDO::PARAM_NULL);
		
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
	
	if(isset($postData['action']) && $postData['action']=='edit' )
	{
	    include_once('notificationsInsert.php');
		$Notifications=InsertNotifications('customer', $postData['id'],$postData['endDate'],"");
		if($Notifications!='inserted' && $Notifications!='noNeedToUpdate')
		{
			echo $Notifications;
			exit();
		}
		
		$column="";
		if(isset($passportNoFilePath,$passportNoFileName) && !empty($passportNoFilePath) && !empty($passportNoFileName))
			$column.=",passportNoFilePath:passportNoFilePath,passportNoFileName:passportNoFileName";
		
		if(isset($crNoFilePath,$crNoFileName) && !empty($crNoFilePath) && !empty($crNoFileName))
			$column.=",crNoFilePath:crNoFilePath,crNoFileName:crNoFileName";
		
		if(isset($agencyFilePath,$agencyFileName) && !empty($agencyFilePath) && !empty($agencyFileName))
			$column.=",agencyFilePath:agencyFilePath,agencyFileName:agencyFileName";
		
		$qry="UPDATE tbl_customers SET custTypeId=:custTypeId, 
		customerName_en=:customerName_en,customerName_ar=:customerName_ar,
		idPassportNo=:idPassportNo,crNo=:crNo,
		cityId=:cityId,address=:address,postBox=:postBox,mobileNo=:mobileNo,
		customerEmail=:customerEmail,nationalityId=:nationalityId $column,
		endDateAgency=:endDateAgency,notes=:notes,modifiedBy=:modifiedBy, modifiedDate=NOW()
		WHERE customerId=:customerId";
		$stmt=$dbo->prepare($qry);
		
		$stmt->bindParam(":custTypeId",$postData['custTypeId'],PDO::PARAM_INT);
		$stmt->bindParam(":customerName_ar",$postData['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":customerName_en",$postData['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":idPassportNo",$postData['passportNo'],PDO::PARAM_STR);
		if(empty($postData['crNo']))
			$stmt->bindParam(":crNo",$postData['crNo'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":crNo",$postData['crNo'],PDO::PARAM_STR);
		$stmt->bindParam(":cityId",$postData['city'],PDO::PARAM_INT);
		$stmt->bindParam(":address",$postData['address'],PDO::PARAM_STR);
		$stmt->bindParam(":postBox",$postData['postBox'],PDO::PARAM_STR);
		if(empty($postData['mobileNo']))
			$stmt->bindParam(":mobileNo",$postData['mobileNo'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":mobileNo",$postData['mobileNo'],PDO::PARAM_STR);
		$stmt->bindParam(":customerEmail",$postData['email'],PDO::PARAM_STR);
		$stmt->bindParam(":nationalityId",$postData['nationality'],PDO::PARAM_STR);
		if(empty($postData['endDate']))
			$stmt->bindParam(":endDateAgency",$postData['endDate'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":endDateAgency",$postData['endDate'],PDO::PARAM_STR);
		$stmt->bindParam(":notes",$postData['note'],PDO::PARAM_STR);
		
		if(isset($passportNoFilePath,$passportNoFileName) && !empty($passportNoFilePath) && !empty($passportNoFileName))
		{
			$stmt->bindParam(":passportNoFilePath",$passportNoFilePath,PDO::PARAM_STR);
			$stmt->bindParam(":passportNoFileName",$passportNoFileName,PDO::PARAM_STR);
		}
		if(isset($crNoFilePath,$crNoFileName) && !empty($crNoFilePath) && !empty($crNoFileName))
		{
			$stmt->bindParam(":crNoFilePath",$crNoFilePath,PDO::PARAM_STR);
			$stmt->bindParam(":crNoFileName",$crNoFileName,PDO::PARAM_STR);
		}
		if(isset($agencyFilePath,$agencyFileName) && !empty($agencyFilePath) && !empty($agencyFileName))
		{
			$stmt->bindParam(":agencyFileName",$agencyFileName,PDO::PARAM_STR);
			$stmt->bindParam(":agencyFilePath",$agencyFilePath,PDO::PARAM_STR);
		}
		
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":customerId",$postData['id'],PDO::PARAM_INT);
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