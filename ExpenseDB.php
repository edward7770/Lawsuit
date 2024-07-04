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
		$qry="INSERT INTO tbl_expense(expCatId,lsMasterId,expenseDate,supplier,expenseMode,amount,taxValue,taxAmount,totalExpAmount,remarks,isActive,createdBy) 
											VALUES(:expCatId,:lsMasterId,:expenseDate,:supplier,:expenseMode,:amount,:taxValue,:taxValueAmount,:totalExpAmount,:remarks,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsMasterId",$_POST['subCatId'],PDO::PARAM_INT);
		$stmt->bindParam(":expenseDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":supplier",$_POST['supl'],PDO::PARAM_STR);
		$stmt->bindParam(":expenseMode",$_POST['mode'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValue",$_POST['tax'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValueAmount",$_POST['taxAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":totalExpAmount",$_POST['totAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":remarks",$_POST['remarks'],PDO::PARAM_STR);
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
		$qry="SELECT expenseId AS id,expCatId as catId,lsMasterId as subCatId,expenseDate,supplier,expenseMode,amount,taxValue,taxAmount,totalExpAmount,remarks FROM tbl_expense WHERE isActive=1 AND expenseId=:expenseId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expenseId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_expense SET expCatId=:expCatId,lsMasterId=:lsMasterId, expenseDate=:expenseDate, expenseMode=:expenseMode, amount=:amount, supplier=:supplier,taxValue=:taxValue,taxAmount=:taxValueAmount, totalExpAmount=:totalExpAmount, remarks=:remarks, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE expenseId=:expenseId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":lsMasterId",$_POST['subCatId'],PDO::PARAM_INT);
		$stmt->bindParam(":expenseDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":supplier",$_POST['supl'],PDO::PARAM_STR);
		$stmt->bindParam(":expenseMode",$_POST['mode'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValue",$_POST['tax'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValueAmount",$_POST['taxAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":totalExpAmount",$_POST['totAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":remarks",$_POST['remarks'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":expenseId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_expense SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE expenseId=:expenseId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":expenseId",$_POST['id'],PDO::PARAM_INT);
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