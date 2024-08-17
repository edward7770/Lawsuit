<?php 
	session_start();
	include_once('config/conn.php');
	////print_r($_POST);
	///exit;
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND o.`opponentId`<>:opponentId ";
			
		$qry="SELECT o.`opponentId` FROM `tbl_opponents` o WHERE o.isActive=1 $where AND 
				(o.`oppoName_ar`=:opponentName_ar AND o.`oppoName_en`=:opponentName_en AND o.`oppoContact`=:oppLayerContact)";
		$stmt=$dbo->prepare($qry);
		// $stmt->bindParam(":oppoLayerName",$_POST['opponentName'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentName_ar",$_POST['opponentName_ar'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentName_en",$_POST['opponentName_en'],PDO::PARAM_STR);
		$stmt->bindParam(":oppLayerContact",$_POST['opponentPhone'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":opponentId",$_POST['id'],PDO::PARAM_STR);
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
			exit(get_lang_msg('record_already_exists'));
	}
	
	if(isset($_POST['action']) && $_POST['action']=='add')
	{
		$qry="INSERT INTO `tbl_opponents` (oppoName_ar,oppoName_en,oppoNationality,oppoContact,oppoAddress,isActive,createdBy) 
				VALUES (:opponentName_ar,:opponentName_en,:opponentNationality,:opponentPhone,:opponentAddress,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":opponentName_ar",$_POST['opponentName_ar'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentName_en",$_POST['opponentName_en'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentNationality",$_POST['opponentNationality'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentPhone",$_POST['opponentPhone'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentAddress",$_POST['opponentAddress'],PDO::PARAM_STR);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			echo get_lang_msg('added_successfully');
			exit('1');
		}
		else 
		{
			///$errorInfo = $stmt->errorInfo();
			///exit($json =$errorInfo[2]);
			get_lang_msg('errorMessage');
			exit('0');
		}
	}
	if(isset($_POST['action']) && $_POST['action']=='getData' )
	{
		$qry="SELECT o.`opponentId`  as id, o.`oppoName_ar`, o.`oppoName_en`, o.`oppoAddress`, 
		o.`oppoContact` ,o.`oppoNationality` FROM tbl_opponents o  WHERE o.`isActive`=1 AND o.`opponentId`=:id";
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
		$qry="UPDATE tbl_opponents SET oppoName_ar=:opponentName_ar, oppoName_en=:opponentName_en, oppoContact=:opponentPhone, oppoAddress=:opponentAddress, 
		oppoNationality=:opponentNationality,modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE isActive=1 AND opponentId=:opponentId";
		$stmt=$dbo->prepare($qry);
		// $stmt->bindParam(":opponentName",$_POST['opponentName'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentName_ar",$_POST['opponentName_ar'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentName_en",$_POST['opponentName_en'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentNationality",$_POST['opponentNationality'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentPhone",$_POST['opponentPhone'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentAddress",$_POST['opponentAddress'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="SELECT lo.`opponentId` FROM tbl_lawsuit_opponents lo 
			INNER JOIN tbl_opponents o ON o.`opponentId`=lo.`opponentId`
			WHERE lo.`isActive`=1 AND lo.`opponentId`=:opponentId LIMIT 1";
		$stmt=$dbo->prepare($qry);	
		$stmt->bindParam(":opponentId",$_POST['id'],PDO::PARAM_INT);
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
	
		$qry="UPDATE tbl_opponents SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE isActive=1 and opponentId=:opponentId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":opponentId",$_POST['id'],PDO::PARAM_INT);
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