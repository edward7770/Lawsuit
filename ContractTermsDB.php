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
		$qry="INSERT INTO tbl_tors(tors_en,tors_ar,isActive,createdBy)
			VALUES (:tors_en,:tors_ar,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":tors_ar",$_POST['termsAr'],PDO::PARAM_STR);
		$stmt->bindParam(":tors_en",$_POST['termsEn'],PDO::PARAM_STR);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			echo get_lang_msg('added_successfully');
			echo $dbo->lastInsertId();
			exit();
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
	if(isset($_POST['action']) && $_POST['action']=='edit' )
	{
		$qry="UPDATE tbl_tors set
			  tors_en = :tors_en,
			  tors_ar = :tors_ar,
			  modifiedBy = :modifiedBy,
			  modifiedDate = now()
			WHERE torsId = :torsId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":tors_ar",$_POST['termsAr'],PDO::PARAM_STR);
		$stmt->bindParam(":tors_en",$_POST['termsEn'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":torsId",$_POST['id'],PDO::PARAM_INT);
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