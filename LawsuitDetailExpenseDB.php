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
		$qry="INSERT INTO tbl_lawsuit_expense(lsDetailsId,expCatId,subExpCatId,expenseDate,expenseMode,amount,remarks,isActive,createdBy) 
											VALUES(:lsDetailsId,:expCatId,:subExpCatId,:expenseDate,:expenseMode,:amount,:remarks,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_STR);
		$stmt->bindParam(":expCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":subExpCatId",$_POST['subCatId'],PDO::PARAM_INT);
		$stmt->bindParam(":expenseDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":expenseMode",$_POST['mode'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
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
		$qry="SELECT lsExpenseId as id,expCatId as catId ,subExpCatId as subCatId, expenseDate as date, expenseMode as mode, amount, remarks FROM tbl_lawsuit_expense l WHERE isActive=1 and lsExpenseId=:lsExpenseId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsExpenseId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_lawsuit_expense SET expCatId=:expCatId,subExpCatId=:subExpCatId, expenseDate=:expenseDate, expenseMode=:expenseMode, amount=:amount, remarks=:remarks, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE lsExpenseId=:lsExpenseId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":expCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":subExpCatId",$_POST['subCatId'],PDO::PARAM_INT);
		$stmt->bindParam(":expenseDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":expenseMode",$_POST['mode'],PDO::PARAM_STR);$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":remarks",$_POST['remarks'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsExpenseId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_lawsuit_expense SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE lsExpenseId=:lsExpenseId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsExpenseId",$_POST['id'],PDO::PARAM_INT);
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