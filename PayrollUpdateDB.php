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
	if(isset($postData['payrollDetails']))
	{
		foreach($postData['payrollDetails'] as $val)
		{
			if($val['action']=="update" && !empty($val['id']))
			{
				$id=$val['id'];
			}
		}
		$tbl_payroll_details=0;
		$dbo->beginTransaction();
		if($postData['payslipDetails'] and count($postData['payslipDetails'])>0)
		{
			if(isset($id))
				$payrollId=$id;
			foreach($postData['payslipDetails'] as $val)
			{
				$allowId=explode(',', $val['allowId']);
				$allowDeductId=$allowId[0];
				
				if(isset($id) && !empty($allowId[1]))
				{
					$query="UPDATE tbl_payroll_details SET amount=:amount, modifiedBy=:createdBy, modifiedDate=now() WHERE allowDeductId=:allowDeductId AND payrollId=:payrollId";
				}
				else
					$query="INSERT INTO `tbl_payroll_details`(`payrollId`,`allowDeductId`,`amount`,`isActive`,`createdBy`)
						VALUES (:payrollId,:allowDeductId,:amount,1,:createdBy)";
				$stmt=$dbo->prepare($query);
				$stmt->bindParam(":amount",$val['allowVal'],PDO::PARAM_STR);
				$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
				$stmt->bindParam(":payrollId",$payrollId,PDO::PARAM_INT);
				$stmt->bindParam(":allowDeductId",$allowDeductId,PDO::PARAM_INT);
				if($stmt->execute())
				{
					$tbl_payroll_details=1;
				}
				else 
				{
					$tbl_payroll_details=0;
					$errorInfo = $stmt->errorInfo();
					errorMessage($json =$errorInfo[2]);
				}
			}
		}
		if($tbl_payroll_details>0)
		{
			$dbo->commit();
			echo get_lang_msg('modified_successfully')."1";
		}
		else 
		{
			echo get_lang_msg('errorMessage');
			$dbo->rollBack();
		}
		
	}
	
	else if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$dbo->beginTransaction();
		$tbl_payroll=0;
		$tbl_payroll_details=0;
		$qry="UPDATE tbl_payroll SET isActive =-1, modifiedBy = :modifiedBy,modifiedDate = now() WHERE payrollId=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$tbl_payroll=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		$qry="UPDATE tbl_payroll_details SET isActive =-1, modifiedBy = :modifiedBy,modifiedDate = now() WHERE payrollId= :id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$tbl_payroll_details=1;
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		if($tbl_payroll>0 && $tbl_payroll_details>0)
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