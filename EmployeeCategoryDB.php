<?php 
	session_start();
	include_once('config/conn.php');
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		//echo get_lang_msg('errorMessage');
		die;
	}
	//print_r($_POST);
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND empCatid<>:empCatid ";
			
		$qry="SELECT empCatid FROM tbl_emp_category WHERE isActive=1 $where AND (categoryName=:categoryName and categoryName_ar=:categoryName_ar)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":categoryName",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":categoryName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":empCatid",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		if($result)
			exit(get_lang_msg('record_already_exists'));
	}
	if(isset($_POST['action']) && $_POST['action']=='add' )
	{
		$qry="INSERT INTO tbl_emp_category(categoryName,categoryName_ar,isActive,createdBy) 
											VALUES(:categoryName,:categoryName_ar,1,:createdBy)";
	
	//echo $qry;
	$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":categoryName",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":categoryName_ar",$_POST['nameAr'],PDO::PARAM_STR);
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
		$qry="SELECT * FROM tbl_emp_category WHERE isActive=1 AND empCatid=:empCatid";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":empCatid",$_POST['id'],PDO::PARAM_STR);
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
	
	if(isset($_POST['action']) && $_POST['action']=='update' )
	{
		$qry="UPDATE tbl_emp_category SET categoryName=:categoryName, categoryName_ar=:categoryName_ar, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE empCatid=:empCatid";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":categoryName",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":categoryName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":empCatid",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_emp_category SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE empCatid=:empCatid";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":empCatid",$_POST['id'],PDO::PARAM_INT);
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