<?php 
	session_start();
	include_once('config/conn.php');
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	////$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	/*
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND lsStateId<>:lsStateId ";
			
		$qry="SELECT lsStateId FROM tbl_lawsuit_states WHERE isActive=1 $where AND (lsStateName_ar=:lsStateName_ar OR lsStateName_en=:lsStateName_en)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsStateName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":lsStateName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":lsStateId",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
		if($result)
			exit(get_lang_msg('record_already_exists'));
	}
	*/
	if(isset($_POST['action']) && $_POST['action']=='add' )
	{
		$qry="INSERT INTO tbl_consultation_payment(consultationId,paymentDate,paymentMode,amount,remarks,isActive, createdBy)
			VALUES (:consultationId,:paymentDate,:paymentMode,:amount,:remarks,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":consultationId",$_POST['consultId'],PDO::PARAM_STR);
		$stmt->bindParam(":paymentDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":paymentMode",$_POST['mode'],PDO::PARAM_STR);
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
		$qry="SELECT consPaymentId as id, paymentDate as date, paymentMode as mode, amount, remarks FROM tbl_consultation_payment l WHERE isActive=1 and consPaymentId=:consPaymentId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":consPaymentId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_consultation_payment SET paymentDate=:paymentDate, paymentMode=:paymentMode, amount=:amount, remarks=:remarks, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE consPaymentId=:consPaymentId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":paymentDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":paymentMode",$_POST['mode'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":remarks",$_POST['remarks'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":consPaymentId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_consultation_payment SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE consPaymentId=:consPaymentId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":consPaymentId",$_POST['id'],PDO::PARAM_INT);
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
	if(isset($_POST['action']) && $_POST['action']=='updateContract')
	{
		$qry="UPDATE tbl_consultations set amount=:amount, tax=:tax, totalAmount=:totalAmount,modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE isActive=1 AND consId=:consId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":amount",$_POST['amountContract'],PDO::PARAM_INT);
		$stmt->bindParam(":tax",$_POST['taxValue'],PDO::PARAM_INT);
		$stmt->bindParam(":totalAmount",$_POST['totContAmount'],PDO::PARAM_INT);
		$stmt->bindParam(":consId",$_POST['consultId'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
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
	
	if(isset($_POST['paidStatus']))
	{
		$qry="UPDATE tbl_consultations set isPaid=:isPaid,isPaidBy=:modifiedBy, isPaidDateTime=NOW() WHERE isActive=1 AND consId=:consId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":isPaid",$_POST['paidStatus'],PDO::PARAM_INT);
		$stmt->bindParam(":consId",$_POST['consultId'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
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
	
?>