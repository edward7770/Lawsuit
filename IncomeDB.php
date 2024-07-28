<?php 
	session_start();
	include_once('config/conn.php');
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	if(isset($_POST['action']) && $_POST['action']=='add' )
	{
		/*
		$qry="INSERT INTO tbl_income(incomeTypeId,customerId,lawyerId,description,incomeDate,amount,taxValue,totalIncomeAmount,incomeReceivedBy,isActive,createdDate,createdBy) 
				VALUES(:incomeTypeId,:customerId,:lawyerId,:description,:incomeDate,:amount,:taxValue,:totalIncomeAmount,:incomeReceivedBy,1,now(),:createdBy)";
		*/
		$qry="INSERT INTO tbl_income(incomeTypeId,lsMasterId,description,incomeDate,amount,taxValue,taxAmount,totalIncomeAmount,incomeReceivedBy,isActive,createdDate,createdBy) 
				VALUES(:incomeTypeId, :lsMasterId, :description,:incomeDate,:amount,:taxValue,:taxValueAmount,:totalIncomeAmount,:incomeReceivedBy,1,now(),:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":incomeTypeId",$_POST['catId'],PDO::PARAM_INT);
				$stmt->bindParam(":lsMasterId",$_POST['subCatId'],PDO::PARAM_INT);
		///$stmt->bindParam(":customerId",$_POST['subCatId'],PDO::PARAM_INT);
		////$stmt->bindParam(":lawyerId",$_POST['lawyerId'],PDO::PARAM_INT);
		$stmt->bindParam(":description",$_POST['desc'],PDO::PARAM_STR);  
		$stmt->bindParam(":incomeDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValue",$_POST['tax'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValueAmount",$_POST['taxAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":totalIncomeAmount",$_POST['totAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":incomeReceivedBy",$_POST['receivedBy'],PDO::PARAM_INT);
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
		////$qry="SELECT incomeId AS id,incomeTypeId as catId,customerId AS subCatId,lawyerId,incomeDate,incomeReceivedBy,amount,taxValue,totalIncomeAmount,description FROM tbl_income WHERE isActive=1 AND incomeId=:incomeId";
		$qry="SELECT incomeId AS id,incomeTypeId as catId, lsMasterId as subCatId,  incomeDate,incomeReceivedBy,amount,taxValue,taxAmount,totalIncomeAmount,description FROM tbl_income WHERE isActive=1 AND incomeId=:incomeId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":incomeId",$_POST['id'],PDO::PARAM_STR);
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
		////$qry="UPDATE tbl_income SET incomeTypeId=:incomeTypeId, customerId=:customerId,lawyerId=:lawyerId,description=:description, amount=:amount, taxValue=:taxValue,totalIncomeAmount=:totalIncomeAmount, incomeDate=:incomeDate, incomeReceivedBy=:incomeReceivedBy,  modifiedDate=NOW(), modifiedBy=:modifiedBy
		$qry="UPDATE tbl_income SET incomeTypeId=:incomeTypeId,lsMasterId=:lsMasterId,description=:description, amount=:amount, taxValue=:taxValue, taxAmount=:taxValueAmount,  totalIncomeAmount=:totalIncomeAmount, incomeDate=:incomeDate, incomeReceivedBy=:incomeReceivedBy,  modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE incomeId=:incomeId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":incomeTypeId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsMasterId",$_POST['subCatId'],PDO::PARAM_INT);
		///$stmt->bindParam(":customerId",$_POST['subCatId'],PDO::PARAM_INT);
		///$stmt->bindParam(":lawyerId",$_POST['lawyerId'],PDO::PARAM_INT);
		$stmt->bindParam(":description",$_POST['desc'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValue",$_POST['tax'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValueAmount",$_POST['taxAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":totalIncomeAmount",$_POST['totAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":incomeDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":incomeReceivedBy",$_POST['receivedBy'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":incomeId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_income SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE incomeId=:incomeId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":incomeId",$_POST['id'],PDO::PARAM_INT);
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