<?php 
	session_start();
	include_once('config/conn.php');
	////print_r($_POST);
	///exit;
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND oppoLawyerId<>:oppoLawyerId ";
			
		$qry="SELECT oppoLawyerId FROM tbl_opponentlawyer WHERE isActive=1 $where AND 
				(oppoLawyerName=:oppoLawyerName AND oppoLawyerContact=:oppoLawyerContact)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":oppoLawyerName",$_POST['opponentLawyer'],PDO::PARAM_STR);
		$stmt->bindParam(":oppoLawyerContact",$_POST['opponentLawyerPhone'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":oppoLawyerId",$_POST['id'],PDO::PARAM_STR);
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
	
	if(isset($_POST['action']) && $_POST['action']=='add')
	{
		print_r($_POST);
		exit;
		$qry="INSERT INTO tbl_opponentlawyer (oppoLawyerName,oppoLawyerContact,isActive,createdBy) 
				VALUES (:oppoLawyerName,:oppoLawyerContact,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":oppoLawyerName",$_POST['opponentLawyer'],PDO::PARAM_STR);
		$stmt->bindParam(":oppoLawyerContact",$_POST['opponentLawyerPhone'],PDO::PARAM_STR);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			echo get_lang_msg('added_successfully');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	if(isset($_POST['action']) && $_POST['action']=='getData' )
	{
		$qry="SELECT * FROM tbl_customertypes ct WHERE ct.isActive=1 AND ct.custTypeId=:custTypeId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":custTypeId",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//print_r($result);
			echo json_encode(['status'=>true, 'data'=>$result],JSON_INVALID_UTF8_SUBSTITUTE);

		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}

	}
	
	if(isset($_POST['action']) && $_POST['action']=='update' )
	{
		$qry="UPDATE tbl_customertypes SET typeName_en=:typeName_en, typeName_ar=:typeName_ar, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE custTypeId=:custTypeId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":typeName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":typeName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":custTypeId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('modified_successfully');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$qry="UPDATE tbl_customertypes SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE custTypeId=:custTypeId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":custTypeId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('deleted_successfully');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	
?>