<?php 
	session_start();
	include_once('config/conn.php');
	////print_r($_POST);
	///exit;
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	///$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		echo get_lang_msg('errorMessage');
		die;
	}
	
	if(isset($_POST['action']) && $_POST['action']=='add' )
	{
		$qry="INSERT INTO tbl_consultations(customerId,title,contractDate,amount,tax,taxAmount,totalAmount,notes_ar,notes_en,lawyerId,isActive,createdBy)
			VALUES (:customerId,:title,:contractDate,:amount,:tax,:taxAmount,:totalAmount,:notes_ar,:notes_en,:lawyerId,1,:createdBy);)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":customerId",$_POST['custId'],PDO::PARAM_INT);
		$stmt->bindParam(":title",$_POST['title'],PDO::PARAM_STR);
		$stmt->bindParam(":contractDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":tax",$_POST['taxValue'],PDO::PARAM_STR);
		$stmt->bindParam(":totalAmount",$_POST['taxPer'],PDO::PARAM_STR);
		$stmt->bindParam(":taxAmount",$_POST['taxValueAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":notes_ar",$_POST['termsAr'],PDO::PARAM_STR);
		$stmt->bindParam(":notes_en",$_POST['termsEn'],PDO::PARAM_STR);
		$stmt->bindParam(":lawyerId",$_POST['lawyerId'],PDO::PARAM_INT);
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
	if(isset($_POST['action']) && $_POST['action']=='edit' )
	{
		$qry="UPDATE tbl_consultations set
			  customerId = :customerId,
			  title = :title,
			  contractDate = :contractDate,
			  amount = :amount,
			  tax = :tax,
			  taxAmount = :taxAmount,
			  totalAmount = :totalAmount,
			  notes_ar = :notes_ar,
			  notes_en = :notes_en,
			  lawyerId = :lawyerId,
			  modifiedBy = :modifiedBy,
			  modifiedDate = now()
			WHERE consId = :consId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":customerId",$_POST['custId'],PDO::PARAM_INT);
		$stmt->bindParam(":title",$_POST['title'],PDO::PARAM_STR);
		$stmt->bindParam(":contractDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":tax",$_POST['taxValue'],PDO::PARAM_STR);
		$stmt->bindParam(":taxAmount",$_POST['taxValueAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":totalAmount",$_POST['taxPer'],PDO::PARAM_STR);
		$stmt->bindParam(":notes_ar",$_POST['termsAr'],PDO::PARAM_STR);
		$stmt->bindParam(":notes_en",$_POST['termsEn'],PDO::PARAM_STR);
		$stmt->bindParam(":lawyerId",$_POST['lawyerId'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":consId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_consultations SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE consId=:consId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":consId",$_POST['id'],PDO::PARAM_INT);
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