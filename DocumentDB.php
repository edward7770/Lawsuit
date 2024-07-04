<?php 
	session_start();
	include_once('config/conn.php');
	///print_r($_FILES);
	////exit;
	
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	if(isset($_POST['docDetails']))
	{
		$postData = json_decode($_POST['docDetails'], true); // assuming you are posting JSON data
		////print_r($postData);
		/////exit;
	}
	
	include_once('documentDBFiles.php');
	$file_path='uploadFiles/';
	
	if((isset($postData['action']) && $postData['action']=='Edit') || (isset($_POST['action']) && $_POST['action']=='del'))
	{
		if(isset($postData))
			$id=$postData['id'];
		else 
			$id=$_POST['id'];
		
		$qry="SELECT docFilePath,docFileName FROM tbl_docs WHERE isActive=1 AND docsId=:id";
		$stmt=$dbo->prepare($qry);
		
		$stmt->bindParam(":id",$id,PDO::PARAM_INT);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if($result)
			{
				$filePath=$result[0]['docFilePath'];
				$fileName=$result[0]['docFileName'];
				
				if(isset($_FILES['fileImage']))
					deleteFileImage($filePath,$fileName);
				if(isset($_POST['action']) && $_POST['action']=='del')
					deleteFileImage($filePath,$fileName);
			}
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
	
	if(isset($_FILES['fileImage']))
	{
		$filePath=$file_path."docFiles/";
		check_file("fileImage");
		$fileName=set_file_name("fileImage","docFiles");
		upload_file('fileImage',$filePath,$fileName);
	}
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	
	if(isset($_POST['docDetails'],$postData['action']) && ($postData['action']=='Add' || $postData['action']=='Edit'))
	{
		$qry="INSERT INTO tbl_docs(docName_en,docName_ar,docDescription,docFilePath,docFileName,isActive,createdBy)
				VALUES (:docName_en,:docName_ar,:docDescription,:docFilePath,:docFileName,1,:createdBy)";
		
		if($postData['action']=='Edit')
		$qry="UPDATE tbl_docs SET docName_en=:docName_en,docName_ar=:docName_ar, docDescription=:docDescription,docFilePath=:docFilePath,
			docFileName=:docFileName, modifiedBy=:createdBy, modifiedDate=NOW() WHERE docsId=:id";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":docName_en",$postData['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":docName_ar",$postData['nameAr'],PDO::PARAM_STR);
		if(empty($postData['desc']))
			$stmt->bindParam(":docDescription",$postData['desc'],PDO::PARAM_NULL);
		else
			$stmt->bindParam(":docDescription",$postData['desc'],PDO::PARAM_STR);
		if(empty($filePath))
			$stmt->bindParam(":docFilePath",$filePath,PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":docFilePath",$filePath,PDO::PARAM_STR);
		if(empty($fileName))
			$stmt->bindParam(":docFileName",$fileName,PDO::PARAM_NULL);
		else 
			$stmt->bindParam(":docFileName",$fileName,PDO::PARAM_STR);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		
		if($postData['action']=='Edit')
			$stmt->bindParam(":id",$postData['id'],PDO::PARAM_STR);
		
		if($stmt->execute())
		{
			if($postData['action']=='Edit')
				echo get_lang_msg('modified_successfully');
			else 
				echo get_lang_msg('added_successfully');
			exit('1');
		}
		else 
		{
			deleteFileImage($filePath,$fileName);
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
	/*
	if(isset($_POST['action']) && $_POST['action']=='getData' )
	{
		$qry="SELECT * FROM tbl_customers c WHERE c.`isActive`<>-1 AND c.`customerId`=:customerId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":customerId",$_POST['id'],PDO::PARAM_STR);
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
	*/
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$qry="UPDATE tbl_docs SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE docsId=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
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