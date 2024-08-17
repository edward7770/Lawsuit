<?php 
	session_start();
	include_once('config/conn.php');
	date_default_timezone_set("Asia/Karachi");
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	$postData = json_decode($_POST['customerDetails'], true); // assuming you are posting JSON data
	/////print_r($_POST);
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	if(isset($postData['action']) && $postData['action']=='add')
	{
		$lsMasterId=0;
		$lsDetailsId=0;
		$tbl_lawsuit_master=0;
		$tbl_lawsuit_masterUpdate=0;
		$tbl_lawsuit_details=0;
		$tbl_lawsuit_opponents=0;
		$tbl_lawsuit_oppolawyer=0;
		$tbl_lawsuit_customers=0;
		$tbl_lawsuit_lawyer=0;
		
		//tbl_lawsuit_master
		$ls_code="";
		$isPaid="";
		$dbo->beginTransaction();
		
		if(!isset($_POST['lsMId']))
		{
			////,lsCreatedAt /////:lsCreatedAt,
			$qry="INSERT INTO tbl_lawsuit_master (lsTypeId,lsStagesId,lsStateId,lsSubject,lslocationId,isActive,createdBy)
					VALUES (:lsTypeId,:lsStagesId,:lsStateId,:lsSubject,:lslocationId,1,:createdBy);";
			
			$stmt=$dbo->prepare($qry);
			///$stmt->bindParam(":ls_code",$ls_code,PDO::PARAM_STR);
			$stmt->bindParam(":lsTypeId",$postData['lawsuitTypeId'],PDO::PARAM_INT);
			$stmt->bindParam(":lsStagesId",$postData['stageId'],PDO::PARAM_INT);
			$stmt->bindParam(":lsStateId",$postData['stateId'],PDO::PARAM_INT);
			$stmt->bindParam(":lsSubject",$postData['lawsuitSubject'],PDO::PARAM_STR);
			$stmt->bindParam(":lslocationId",$postData['lawsuitLoc'],PDO::PARAM_STR);
			/*
			if(empty($postData['createdAt']))
				$stmt->bindParam(":lsCreatedAt",$postData['createdAt'],PDO::PARAM_NULL);
			else 
				$stmt->bindParam(":lsCreatedAt",$postData['createdAt'],PDO::PARAM_STR);
			*/
			$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
			if($stmt->execute())
			{
				$lsMasterId = $dbo->lastInsertId();
				$tbl_lawsuit_master=1;
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				errorMessage($json =$errorInfo[2]);
			}
		}
		else 
		{
			$lsMasterId =$_POST['lsMId'];
			
			$qry="SELECT m.lsMasterId FROM tbl_lawsuit_master m WHERE m.isActive=1 AND m.lsMasterId=:lsMasterId AND m.isPaidAll=1";
			$stmt=$dbo->prepare($qry);
			$stmt->bindParam(":lsMasterId",$lsMasterId,PDO::PARAM_INT);
			if($stmt->execute())
			{
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				errorMessage($json =$errorInfo[2]);
			}
			if($result) $isPaid=1;
		}
		
		$lsDetailCode="";
		$qry="INSERT INTO tbl_lawsuit_details(lsMasterId,lsDetailCode,lsStagesId,lsStateId,lsTypeId,lsSubject,lslocationId,referenceNo,lawsuitId,lsDate, notes,isActive,createdBy,isPaid) 
				values (:lsMasterId,:lsDetailCode,:lsStagesId,:lsStateId,:lsTypeId,:lsSubject,:lslocationId,:referenceNo,:lawsuitId,:lsDate,:notes,1,:createdBy,:isPaid)";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId",$lsMasterId,PDO::PARAM_INT);
		$stmt->bindParam(":lsDetailCode",$lsDetailCode,PDO::PARAM_STR);
		$stmt->bindParam(":lsStagesId",$postData['stageId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsStateId",$postData['stateId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsTypeId",$postData['lawsuitTypeId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsSubject",$postData['lawsuitSubject'],PDO::PARAM_STR);
		$stmt->bindParam(":lslocationId",$postData['lawsuitLoc'],PDO::PARAM_STR);
		$stmt->bindParam(":referenceNo",$postData['referenceNo'],PDO::PARAM_STR);
		$stmt->bindParam(":lawsuitId",$postData['lawsuitId'],PDO::PARAM_STR);
		$stmt->bindParam(":lsDate",$postData['lsDate'],PDO::PARAM_STR);
		$stmt->bindParam(":notes",$postData['note'],PDO::PARAM_STR);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		if(isset($_POST['lsMId']) && !empty($isPaid))
			$stmt->bindParam(":isPaid",$isPaid,PDO::PARAM_INT);
		else 
			$stmt->bindParam(":isPaid",$isPaid,PDO::PARAM_NULL);
			
		if($stmt->execute())
		{
			$lsDetailsId = $dbo->lastInsertId();
			$tbl_lawsuit_details=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		if(!isset($_POST['lsMId']))	
		{
			$last_ls_code = null;
			$last_number = 1;
			
			$qry_code = "SELECT * FROM tbl_lawsuit_master WHERE isActive=1 ORDER BY lsMasterId DESC LIMIT 1 OFFSET 1";
			$stmt_code = $dbo->prepare($qry_code);
			
			if ($stmt_code->execute()) {
				$last_result = $stmt_code->fetch(PDO::FETCH_ASSOC);
			
				if ($last_result) {
					$last_ls_code = $last_result['ls_code'];
				} else {
					echo "No records found.";
				}
			
				$stmt_code->closeCursor();
			} else {
				echo "Query failed to execute.";
			}
			
			if (!empty($last_ls_code)) {
				$numericPart = preg_replace('/[^0-9]/', '', $last_ls_code);
				$last_number = intval($numericPart) + 1;
			}

			$qry="UPDATE tbl_lawsuit_master m SET m.ls_code=CONCAT('LS-', LPAD($last_number,5,'0')), m.lsDetailId=$lsDetailsId WHERE m.lsMasterId=$lsMasterId";
			$stmt=$dbo->prepare($qry);
			if($stmt->execute())
			{
				$tbl_lawsuit_masterUpdate=1;
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				errorMessage($json =$errorInfo[2]);
			}
		}
		else 
		{
			$tbl_lawsuit_master=1;
			$qry=" UPDATE tbl_lawsuit_master m SET m.lsTypeId=:lsTypeId, m.lsStateId=:lsStateId, m.lsStagesId=:lsStagesId,m.lsDetailId=:lsDetailId,modifiedDate=now(),modifiedBy=:modifiedBy WHERE m.lsMasterId=:lsMasterId";
			$stmt=$dbo->prepare($qry);
			$stmt->bindParam(":lsTypeId",$postData['lawsuitTypeId'],PDO::PARAM_INT);
			$stmt->bindParam(":lsStateId",$postData['stateId'],PDO::PARAM_INT);
			$stmt->bindParam(":lsStagesId",$postData['stageId'],PDO::PARAM_INT);
			$stmt->bindParam(":lsDetailId",$lsDetailsId,PDO::PARAM_INT);
			$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
			$stmt->bindParam(":lsMasterId",$lsMasterId,PDO::PARAM_INT);
			if($stmt->execute())
			{
				$tbl_lawsuit_masterUpdate=1;
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				errorMessage($json =$errorInfo[2]);
			}
		}
		
		$opponentIdsArray = explode (",", $postData['opponentIds']);
		$lawsuit_opponentsArray=[];
		foreach($opponentIdsArray as $row)
		{
			$opponentId=$conn -> real_escape_string($row);
			array_push($lawsuit_opponentsArray, "(".$lsDetailsId.",".$opponentId.",1,'".$_SESSION['username']."')");
		}
		$lawsuit_opponentsValues=implode (",", $lawsuit_opponentsArray);
		$qry="INSERT INTO tbl_lawsuit_opponents(lsDetailsId,opponentId,isActive,createdBy) VALUES";
		/////echo $qry.$lawsuit_opponentsValues;
		$stmt=$dbo->prepare($qry.$lawsuit_opponentsValues);
		if($stmt->execute())
		{
			//$lsDetailsId = $dbo->lastInsertId();
			$tbl_lawsuit_opponents=1;
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
			array_push($lawsuit_oppolawyerArray, "(".$lsDetailsId.",".$oppoLawyerId.",1,'".$_SESSION['username']."')");
		}
		$lawsuit_oppolawyerValues=implode (",", $lawsuit_oppolawyerArray);
		/////echo $qry.$lawsuit_oppolawyerValues;
		$qry="INSERT INTO tbl_lawsuit_oppolawyer(`lsDetailsId`,`oppoLawyerId`,`isActive`,`createdBy`) VALUES";
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
				$lsDetailsId =$lsDetailsId;
				$customerId = $conn -> real_escape_string($row['cId']);
				$custTypeId = $conn -> real_escape_string($row['typeId']);
				$custAdjectiveId = $conn -> real_escape_string($row['adjecId']);
				array_push($tbl_lawsuit_master_values_arr, "(".$lsDetailsId.",".$customerId.",".$custTypeId.",".$custAdjectiveId.",1,'".$_SESSION['username']."')");
			}
			$tbl_lawsuit_master_values=implode (",", $tbl_lawsuit_master_values_arr);
			$qry="INSERT INTO tbl_lawsuit_customers (lsDetailsId,customerId,custTypeId,custAdjectiveId,isActive,createdBy) VALUES";
			/////echo $qry.$tbl_lawsuit_master_values;
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
		/////tbl_lawsuit_lawyer
		$qry="INSERT INTO tbl_lawsuit_lawyer(lsDetailId,empId,isActive,createdBy)      
		VALUES (:lsDetailId,:empId,1,:createdBy);";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailId",$lsDetailsId,PDO::PARAM_INT);
		$stmt->bindParam(":empId",$postData['lawsuitLawyer'],PDO::PARAM_INT);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$tbl_lawsuit_lawyer=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		if($tbl_lawsuit_master>0 && $tbl_lawsuit_masterUpdate>0 && $tbl_lawsuit_details>0 && $tbl_lawsuit_oppolawyer>0 && $tbl_lawsuit_opponents>0 && $tbl_lawsuit_customers>0 && $tbl_lawsuit_lawyer>0)
		{
			$dbo->commit();
			
			echo get_lang_msg('added_successfully')."1";
		}
		else 
		{
			echo get_lang_msg('errorMessage');
			$dbo->rollBack();
			//////echo "tbl_lawsuit_master=".$tbl_lawsuit_master.",tbl_lawsuit_masterUpdate=".$tbl_lawsuit_masterUpdate.",tbl_lawsuit_details=".$tbl_lawsuit_details.",tbl_lawsuit_oppolawyer=".$tbl_lawsuit_oppolawyer.",tbl_lawsuit_opponents=".$tbl_lawsuit_opponents.",tbl_lawsuit_customers=".$tbl_lawsuit_customers.",tbl_lawsuit_lawyer=".$tbl_lawsuit_lawyer;
		}
		
	}
?>