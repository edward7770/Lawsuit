<?php 
	session_start();
	include_once('config/conn.php');
	
	////$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		////echo get_lang_msg('errorMessage');
		die;
	}
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	if(isset($_POST['data']))
		$postData = json_decode($_POST['data'], true); // assuming you are posting JSON data
	
	///print_r($postData['payslipDetails']);
	////exit;
	if(isset($postData['contractDetails']))
	{
		$dbo->beginTransaction();
		////print_r($postData['contractDetails']);
		
		///// check duplications
		/*
		foreach($postData['contractDetails'] as $val)
		{
			$where="";
			if($val['action']=="update" && empty($val['id']))
				$where=" and empContractId=:id";
			
			$qry="SELECT empId FROM tbl_emp_contracts WHERE isActive=1 AND empId=:empId";
			$stmt=$dbo->prepare($qry);
			$stmt->bindParam(":empId",$val['empId'],PDO::PARAM_INT);
			if($val['action']=='update')
				$stmt->bindParam(":id",$val['id'],PDO::PARAM_INT);
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
		$tbl_emp_contracts=0;
		$tbl_emp_contract_details=0;
		foreach($postData['contractDetails'] as $val)
		{
			if($val['action']=="add" && empty($val['id']))
			$qry="INSERT INTO tbl_emp_contracts (contractDate,empId,contractName,basicSalary,contractDateFrom,contractDateTo,isActive,createdBy)
					VALUES (:contractDate,:empId,:contractName,:basicSalary,:contractDateFrom,:contractDateTo,1,:createdBy)";
			else if($val['action']=="update" && !empty($val['id']))
			{
				include_once('notificationsInsert.php');
				$Notifications=InsertNotifications('contract', $val['id'],$val['contractDateTo'],"");
				if($Notifications!='inserted' && $Notifications!='noNeedToUpdate')
				{
					echo $Notifications;
					exit();
				}
				
				$id=$val['id'];
				$qry="UPDATE tbl_emp_contracts SET contractDate=:contractDate,empId=:empId, contractName=:contractName,basicSalary=:basicSalary,
				contractDateFrom=:contractDateFrom,contractDateTo=:contractDateTo,modifiedBy=:createdBy,modifiedDate=NOW() WHERE empContractId=:id";	
			}
			$stmt=$dbo->prepare($qry);
			$stmt->bindParam(":contractDate",$val['contractDate'],PDO::PARAM_STR);
			$stmt->bindParam(":empId",$val['empId'],PDO::PARAM_INT);
			$stmt->bindParam(":contractName",$val['contractName'],PDO::PARAM_STR);
			if(empty($val['basicSalary']))
				$stmt->bindParam(":basicSalary",$val['basicSalary'],PDO::PARAM_NULL);
			else 
				$stmt->bindParam(":basicSalary",$val['basicSalary'],PDO::PARAM_STR);
			$stmt->bindParam(":contractDateFrom",$val['contractDateFrom'],PDO::PARAM_STR);
			$stmt->bindParam(":contractDateTo",$val['contractDateTo'],PDO::PARAM_STR);
			$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
			if(isset($id))
			{
				$stmt->bindParam(":id",$val['id'],PDO::PARAM_INT);
			}
			
			if($stmt->execute())
			{
				$empContractId = $dbo->lastInsertId();
				$tbl_emp_contracts=1;
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				errorMessage($json =$errorInfo[2]);
			}
		}
		if($postData['payslipDetails'] and count($postData['payslipDetails'])>0)
		{
			if(isset($id))
				$empContractId=$id;
			foreach($postData['payslipDetails'] as $val)
			{
				$allowId=explode(',', $val['allowId']);
				$allowDeductId=$allowId[0];
				
				if(isset($id) && !empty($allowId[1]))
				{
					$query="UPDATE `tbl_emp_contract_details` d SET d.`amount`=:amount, d.`modifiedBy`=:createdBy, d.`modifiedDate`=now() WHERE d.`allowDeductId`=:allowDeductId AND d.`empContractId`=:empContractId";
					////echo $qry="UPDATE tbl_emp_contract_details SET amount=".$val['allowVal'].",modifiedBy=".$_SESSION['username'].", modifiedDate=NOW() WHERE empContractId=$empContractId AND allowDeductId=".$val['allowId'];
				}
				else
					$query="INSERT INTO `tbl_emp_contract_details`(`empContractId`,`allowDeductId`,`amount`,`isActive`,`createdBy`)
						VALUES (:empContractId,:allowDeductId,:amount,1,:createdBy)";
				
				$stmt=$dbo->prepare($query);
				if(empty($val['allowVal']))
					$stmt->bindParam(":amount",$val['allowVal'],PDO::PARAM_NULL);
				else
					$stmt->bindParam(":amount",$val['allowVal'],PDO::PARAM_STR);
				$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
				$stmt->bindParam(":empContractId",$empContractId,PDO::PARAM_INT);
				$stmt->bindParam(":allowDeductId",$allowDeductId,PDO::PARAM_INT);
				if($stmt->execute())
				{
					$tbl_emp_contract_details=1;
				}
				else 
				{
					$tbl_emp_contract_details=0;
					$errorInfo = $stmt->errorInfo();
					errorMessage($json =$errorInfo[2]);
				}
			}
		}
		else 
			$tbl_emp_contract_details=1;
		if($tbl_emp_contracts>0 && $tbl_emp_contract_details>0)
		{
			$dbo->commit();
			if(isset($id))
				echo get_lang_msg('modified_successfully')."1";
			else 
				echo get_lang_msg('added_successfully')."1";
		}
		else 
		{
			echo get_lang_msg('errorMessage');
			$dbo->rollBack();
		}
	}
	
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$dbo->beginTransaction();
		$tbl_emp_contracts=0;
		$tbl_emp_contract_details=0;
		$qry="UPDATE tbl_emp_contracts SET isActive =-1, modifiedBy = :modifiedBy,modifiedDate = now() WHERE empContractId = :id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$tbl_emp_contracts=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		$qry="UPDATE tbl_emp_contract_details SET isActive =-1, modifiedBy = :modifiedBy,modifiedDate = now() WHERE empContractId = :id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$tbl_emp_contract_details=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		if($tbl_emp_contracts>0 && $tbl_emp_contract_details>0)
		{
			$dbo->commit();
			echo get_lang_msg('deleted_successfully')."1";
		}
		else 
		{
			echo get_lang_msg('errorMessage');
			$dbo->rollBack();
		}
		
	}
	
?>