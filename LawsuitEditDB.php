<?php 
	session_start();
	include_once('config/conn.php');
	date_default_timezone_set("Asia/Karachi");
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	if(isset($_POST['customerDetails']))
		$postData = json_decode($_POST['customerDetails'], true); // assuming you are posting JSON data
	
	////print_r($_FILES);
	///print_r($_POST);
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	
	if(isset($_POST['action']) && $_POST['action']=='delCustomer')
	{
		$qry="UPDATE tbl_lawsuit_customers c SET c.isActive=-1, c.idCustomerfilePath=NULL,c.idDefendantfilePath=NULL , c.nationalAddfilePath=NULL WHERE c.lsCustomerId=:lsCustomerId AND c.lsDetailsId=:lsDetailsId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsCustomerId",$_POST['id'],PDO::PARAM_INT);
		$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('deleted_successfully');
			exit("1");
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
	if(isset($postData['action']) && $postData['action']=='update')
	{
		$qry="SELECT m.lsMasterId FROM tbl_lawsuit_master m 
			INNER JOIN tbl_lawsuit_details d ON d.lsMasterId=m.lsMasterId AND d.isActive=1
			WHERE m.isActive=1 AND m.lsMasterId=:lsMasterId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId",$postData['lsMId'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		if(!$result)
		exit('Invalid update request');

		$lsMasterId=0;
		$lsDetailsId=0;
		$tbl_lawsuit_master=0;
		$tbl_lawsuit_details=0;
		$tbl_lawsuit_opponents=0;
		$tbl_lawsuit_opponentsUpdate=0;
		$tbl_lawsuit_oppolawyer=0;
		$tbl_lawsuit_oppolawyerUpdate=0;
		$tbl_lawsuit_customers=0;
		$tbl_lawsuit_lawyer=0;
		
		
		//tbl_lawsuit_master
		$ls_code="";
		
		$dbo->beginTransaction();
		
		////,lsCreatedAt /////:lsCreatedAt,
		$qry="Update tbl_lawsuit_master set lsTypeId=:lsTypeId,lsStagesId=:lsStagesId,lsStateId=:lsStateId,lsSubject=:lsSubject,lslocationId=:lsLocation,modifiedBy=:modifiedBy,modifiedDate=now() where isActive=1 and lsMasterId=:lsMasterId";
		$stmt=$dbo->prepare($qry);
		///$stmt->bindParam(":ls_code",$ls_code,PDO::PARAM_STR);
		$stmt->bindParam(":lsTypeId",$postData['lawsuitTypeId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsStagesId",$postData['stageId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsStateId",$postData['stateId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsSubject",$postData['lawsuitSubject'],PDO::PARAM_STR);
		$stmt->bindParam(":lsLocation",$postData['lawsuitLoc'],PDO::PARAM_STR);
		/*
		if(empty($postData['createdAt']))
			$stmt->bindParam(":lsCreatedAt",$postData['createdAt'],PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":lsCreatedAt",$postData['createdAt'],PDO::PARAM_STR);
		*/
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsMasterId",$postData['lsMId'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$tbl_lawsuit_master=true;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		$qry="update tbl_lawsuit_details set lsStagesId=:lsStagesId,lsStateId=:lsStateId,
		lsTypeId=:lsTypeId,lsSubject=:lsSubject,lslocationId=:lsLocation,
		referenceNo=:referenceNo,lawsuitId=:lawsuitId,lsDate=:lsDate,contractEn=:contractEn,contractAr=:contractAr,notes=:notes,modifiedBy=:modifiedBy 
		where lsDetailsId=:lsDetailsId and lsMasterId=:lsMasterId"; 
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsStagesId",$postData['stageId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsStateId",$postData['stateId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsTypeId",$postData['lawsuitTypeId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsSubject",$postData['lawsuitSubject'],PDO::PARAM_STR);
		$stmt->bindParam(":lsLocation",$postData['lawsuitLoc'],PDO::PARAM_STR);
		$stmt->bindParam(":referenceNo",$postData['referenceNo'],PDO::PARAM_STR);
		$stmt->bindParam(":lawsuitId",$postData['lawsuitId'],PDO::PARAM_STR);
		$stmt->bindParam(":lsDate",$postData['lsDate'],PDO::PARAM_STR);
		
		$stmt->bindParam(":contractEn",$postData['termEn'],PDO::PARAM_STR);
		$stmt->bindParam(":contractAr",$postData['termAr'],PDO::PARAM_STR);
		$stmt->bindParam(":notes",$postData['note'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsMasterId",$postData['lsMId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsDetailsId",$postData['lsDId'],PDO::PARAM_INT);
		
		if($stmt->execute())
		{
			$tbl_lawsuit_details=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
			
		}
		
		$qry="update tbl_lawsuit_opponents set isActive=-1,modifiedBy=:modifiedBy,modifiedDate=now() where isActive=1 and lsDetailsId=:lsDetailsId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$postData['lsDId'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$tbl_lawsuit_opponentsUpdate=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		$opponentIdsArray = explode (",", $postData['opponentIds']);
		$lawsuit_opponentsArray=[];
		foreach($opponentIdsArray as $row)
		{
			$opponentId=$conn -> real_escape_string($row);
			array_push($lawsuit_opponentsArray, "(".$postData['lsDId'].",".$opponentId.",1,'".$_SESSION['username']."')");
		}
		$lawsuit_opponentsValues=implode (",", $lawsuit_opponentsArray);
		$qry="INSERT INTO tbl_lawsuit_opponents(lsDetailsId,opponentId,isActive,createdBy) VALUES";
		///echo $qry.$lawsuit_opponentsValues;
		$stmt=$dbo->prepare($qry.$lawsuit_opponentsValues);
		if($stmt->execute())
		{
			$tbl_lawsuit_opponents=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		$tbl_lawsuit_oppolawyerUpdate=0;
		
		$qry="update tbl_lawsuit_oppolawyer set isActive=-1,modifiedBy=:modifiedBy,modifiedDate=now() where isActive=1 and lsDetailsId=:lsDetailsId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$postData['lsDId'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$tbl_lawsuit_oppolawyerUpdate=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		$opponentLawyerIdsArray = explode (",", $postData['opponentLawyerIds']);
		$lawsuit_oppolawyerArray=[];
		foreach($opponentLawyerIdsArray as $row)
		{
			$oppoLawyerId=$conn -> real_escape_string($row);
			array_push($lawsuit_oppolawyerArray, "(".$postData['lsDId'].",".$oppoLawyerId.",1,'".$_SESSION['username']."')");
		}
		$lawsuit_oppolawyerValues=implode (",", $lawsuit_oppolawyerArray);
		$qry="INSERT INTO tbl_lawsuit_oppolawyer(`lsDetailsId`,`oppoLawyerId`,`isActive`,`createdBy`) VALUES";
		////echo $qry.$lawsuit_oppolawyerValues;
		$stmt=$dbo->prepare($qry.$lawsuit_oppolawyerValues);
		if($stmt->execute())
		{
			//$lsDetailsId = $dbo->lastInsertId();
			$tbl_lawsuit_oppolawyer=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		if(count($postData['customerDetails'])>0)
		{
			$tbl_lawsuit_master_values_arr=[];
			foreach($postData['customerDetails'] as $row)
			{
				$customerId = $conn -> real_escape_string($row['cId']);
				$custTypeId = $conn -> real_escape_string($row['typeId']);
				$custAdjectiveId = $conn -> real_escape_string($row['adjecId']);
				$idCustomerfilePath=$conn -> real_escape_string(@$row['idCustomerImage']);
				$nationalAddfilePath=$conn -> real_escape_string(@$row['nationalAddressImage']);
				$idDefendantfilePath=$conn -> real_escape_string(@$row['idDefendantImage']);
				array_push($tbl_lawsuit_master_values_arr, "(".$postData['lsDId'].",".$customerId.",".$custTypeId.",".$custAdjectiveId.",'".$idCustomerfilePath."','".$nationalAddfilePath."','".$idDefendantfilePath."',1,'".$_SESSION['username']."')");
			}
			$tbl_lawsuit_master_values=implode (",", $tbl_lawsuit_master_values_arr);
			$qry="INSERT INTO tbl_lawsuit_customers (lsDetailsId,customerId,custTypeId,custAdjectiveId,idCustomerfilePath,nationalAddfilePath,idDefendantfilePath,isActive,createdBy) VALUES";
			$stmt=$dbo->prepare($qry.$tbl_lawsuit_master_values);
			if($stmt->execute())
			{
				$tbl_lawsuit_customers=1;
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				errorMessage($json =$errorInfo[2]);
			}
		}
		else 
			$tbl_lawsuit_customers=1;
		
		/////tbl_lawsuit_lawyer
		$qry="update tbl_lawsuit_lawyer set empId=:empId,modifiedBy=:modifiedBy, modifiedDate=now() where isActive=1 and lsDetailId=:lsDetailId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailId",$postData['lsDId'],PDO::PARAM_INT);
		$stmt->bindParam(":empId",$postData['lawsuitLawyer'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$tbl_lawsuit_lawyer=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		if($tbl_lawsuit_master>0 && $tbl_lawsuit_details>0 && $tbl_lawsuit_oppolawyer>0 && $tbl_lawsuit_opponents>0 && $tbl_lawsuit_customers>0 && $tbl_lawsuit_lawyer>0 && $tbl_lawsuit_oppolawyerUpdate>0 && $tbl_lawsuit_opponentsUpdate>0)
		{
			$dbo->commit();
			
			echo get_lang_msg('modified_successfully')."1";
		}
		else 
		{
			$dbo->rollBack();
			deleteDirectory($postData['customerDetails']);
			echo "tbl_lawsuit_master=".$tbl_lawsuit_master."tbl_lawsuit_details=".$tbl_lawsuit_details="tbl_lawsuit_oppolawyer=".$tbl_lawsuit_oppolawyer="tbl_lawsuit_opponents=".$tbl_lawsuit_opponents="tbl_lawsuit_customers=".$tbl_lawsuit_customers;
		}
	}
?>