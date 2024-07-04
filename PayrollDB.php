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
	if(isset($_POST['action']) && ($_POST['action']=='genPayroll'))
	{
		if(isset($_POST['month'],$_POST['year'],$_POST['type'],$_POST['empids'],$_POST['empContractIds']))
		{
			$empContractIds_array=explode (",", $_POST['empContractIds']);
			$empids_array=explode (",", $_POST['empids']);
			if(empty($_POST['month']) || empty($_POST['year']) || empty($_POST['year']) || !$empContractIds_array || !$empids_array)
			{
				exit("0 month, year or type not found");
			}
			$dbo->beginTransaction();
			$tbl_payroll=0;
			$tbl_payroll_details=0;
			foreach($empids_array as $index=> $emp_row)
			{
				$qry="INSERT INTO tbl_payroll(contractId,empId,month,year,basicSalary,isActive,createdBy)
				SELECT c.empContractId, c.empId,:month,:year ,IFNULL(c.basicSalary,0),1, :createdBy FROM tbl_emp_contracts c
				WHERE c.empContractId=:empContractId AND c.empId=:empId";
				$stmt=$dbo->prepare($qry);
				$stmt->bindParam(":month",$_POST['month'],PDO::PARAM_INT);
				$stmt->bindParam(":year",$_POST['year'],PDO::PARAM_INT);
				$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
				$stmt->bindParam(":empContractId",$empContractIds_array[$index],PDO::PARAM_INT);
				$stmt->bindParam(":empId",$emp_row,PDO::PARAM_INT);
				
				if($stmt->execute())
				{
					$payrollId = $dbo->lastInsertId();
					$tbl_payroll=1;
				}
				else 
				{
					$tbl_payroll=0;
					$errorInfo = $stmt->errorInfo();
					errorMessage($json =$errorInfo[2]);
				}
				if($payrollId>0)
				{
					$qry="INSERT INTO tbl_payroll_details(payrollId,allowDeductId,amount,isActive,createdBy)
					SELECT $payrollId, d.allowDeductId,IFNULL(d.amount,0),1,:createdBy FROM tbl_emp_contract_details d 
					LEFT JOIN tbl_emp_contracts c ON c.empContractId=d.empContractId
					WHERE d.isActive=1 AND d.empContractId=$empContractIds_array[$index]";
					$stmt=$dbo->prepare($qry);
					$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
					////$stmt->bindParam(":empContractId",$empContractIds_array[$index],PDO::PARAM_INT);
					if($stmt->execute())
					{
						$payrollDetailId = $dbo->lastInsertId();
						$tbl_payroll_details=1;
					}
					else 
					{
						$tbl_payroll_details=0;
						$errorInfo = $stmt->errorInfo();
						errorMessage($json =$errorInfo[2]);
					}
					if($payrollDetailId<=0)
					{
						$tbl_payroll_details=0;
						break;
					}
				}
				else 
				{
					$tbl_payroll=0;
					break;
				}
			}
			
			if($tbl_payroll>0 && $tbl_payroll_details>0)
			{
				$dbo->commit();
				echo get_lang_msg('added_successfully')."1";
			}
			else 
			{
				echo get_lang_msg('errorMessage');
				$dbo->rollBack();
				echo "tbl_payroll=".$tbl_payroll.",tbl_payroll_details=".$tbl_payroll_details;
			}
		}
	}
	
	if(isset($_POST['action']) && ($_POST['action']=='postPayroll'))
	{
		if(isset($_POST['empids'],$_POST['payrollIds'],$_POST['isPostUnPost']))
		{
			$payrollIds_array=explode (",", $_POST['payrollIds']);
			$empids_array=explode (",", $_POST['empids']);
			if(!$payrollIds_array || !$empids_array || !($_POST['isPostUnPost']==0 || $_POST['isPostUnPost']==1))
			{
				exit("Invalid parameters");
			}
			$dbo->beginTransaction();
			$tbl_payroll=0;
			foreach($empids_array as $index=> $emp_row)
			{
				$qry="UPDATE `tbl_payroll` p SET p.`isPost`=:isPost, 
				p.`postBy`=:postBy, p.`postDateTime`=NOW() 
				WHERE p.`empId`=:empId AND p.`payrollId`=:payrollId";
				$stmt=$dbo->prepare($qry);
				$stmt->bindParam(":isPost",$_POST['isPostUnPost'],PDO::PARAM_STR);
				$stmt->bindParam(":postBy",$_SESSION['username'],PDO::PARAM_STR);
				$stmt->bindParam(":payrollId",$payrollIds_array[$index],PDO::PARAM_INT);
				$stmt->bindParam(":empId",$emp_row,PDO::PARAM_INT);
				
				if($stmt->execute())
				{
					$tbl_payroll=1;
				}
				else 
				{
					$tbl_payroll=0;
					$errorInfo = $stmt->errorInfo();
					errorMessage($json =$errorInfo[2]);
				}
				
			}
			if($tbl_payroll>0)
			{
				$dbo->commit();
				echo get_lang_msg('added_successfully')."1";
			}
			else 
			{
				echo get_lang_msg('errorMessage');
				$dbo->rollBack();
				echo "tbl_payroll=".$tbl_payroll.",tbl_payroll_details=".$tbl_payroll_details;
			}
		}
	}
	
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$qry="UPDATE tbl_payroll SET isActive =-1, modifiedBy = :modifiedBy,modifiedDate = now() WHERE payrollId = :id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('deleted_successfully')."1";
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
?>