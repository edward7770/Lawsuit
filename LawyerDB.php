<?php 
	session_start();
	include_once('config/conn.php');
	////print_r($_POST);
	///exit;
	////$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
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
		$qry="INSERT INTO tbl_opponentlawyer (oppoLawyerName,oppoLawyerContact,isActive,createdBy) 
				VALUES (:oppoLawyerName,:oppoLawyerContact,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":oppoLawyerName",$_POST['opponentLawyer'],PDO::PARAM_STR);
		$stmt->bindParam(":oppoLawyerContact",$_POST['opponentLawyerPhone'],PDO::PARAM_STR);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			echo get_lang_msg('added_successfully');
			exit('1');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	if(isset($_POST['action']) && $_POST['action']=='getData' )
	{
		$qry="SELECT l.`oppoLawyerId` as id, l.`oppoLawyerName`, l.`oppoLawyerContact` 
		FROM `tbl_opponentlawyer` l WHERE l.`isActive`=1 AND l.`oppoLawyerId`=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//print_r($result);
			echo json_encode(['status'=>true, 'data'=>$result],JSON_INVALID_UTF8_SUBSTITUTE);

		}
		else 
		{
			///$errorInfo = $stmt->errorInfo();
			///exit($json =$errorInfo[2]);
			get_lang_msg('errorMessage');
			exit('0');
		}

	}
	
	if(isset($_POST['action']) && $_POST['action']=='update' )
	{
		$qry="UPDATE tbl_opponentlawyer SET oppoLawyerName=:oppoLawyerName, oppoLawyerContact=:oppoLawyerContact,
		modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE isActive=1 AND oppoLawyerId=:oppoLawyerId";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":oppoLawyerName",$_POST['opponentLawyer'],PDO::PARAM_STR);
		$stmt->bindParam(":oppoLawyerContact",$_POST['opponentLawyerPhone'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":oppoLawyerId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('modified_successfully');
			exit('1');
		}
		else 
		{
			///$errorInfo = $stmt->errorInfo();
			////	exit($json =$errorInfo[2]);
			get_lang_msg('errorMessage');
			exit('0');
		}
	}
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$qry="SELECT l.`oppoLawyerId` FROM `tbl_opponentlawyer` l 
			INNER JOIN `tbl_lawsuit_oppolawyer` ol ON ol.`lsoppoLawyerId`=l.`oppoLawyerId`
			WHERE l.`isActive`=1 AND l.`oppoLawyerId`=:oppoLawyerId LIMIT 1";
		$stmt=$dbo->prepare($qry);	
		$stmt->bindParam(":oppoLawyerId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			///$errorInfo = $stmt->errorInfo();
			///exit($json =$errorInfo[2]);
			get_lang_msg('errorMessage');
			exit('0');
		}
		if($result)
			exit('Can not delete b/c it is assocaited with Lawsuit');
	
		$qry="UPDATE tbl_opponentlawyer SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE isActive=1 and oppoLawyerId=:oppoLawyerId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":oppoLawyerId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('deleted_successfully');
			exit('1');
		}
		else 
		{
			////$errorInfo = $stmt->errorInfo();
			////exit($json =$errorInfo[2]);
			get_lang_msg('errorMessage');
			exit('0');
		}
	}
	
?>