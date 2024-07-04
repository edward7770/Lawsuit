<?php 
	session_start();
	include_once('config/conn.php');
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		///echo $errorInfo;
		echo get_lang_msg('errorMessage');
		die;
	}
	
	if(isset($_POST['getData']) && $_POST['id']>0)
	{
		if(isset($_POST['sessionData']))
			////$qry="SELECT lsSessionId as id, lsDetailsId, sessionName, sessionDate, sessionTime, sessionPlace, sessionDetails FROM tbl_lawsuit_sessions WHERE isActive=1 AND lsSessionId=:id";
			$qry="SELECT lsSessionId as id, lsDetailsId, sessionName, sessionDate, sessionTime, sessionDetails FROM tbl_lawsuit_sessions WHERE isActive=1 AND lsSessionId=:id";
		else if(isset($_POST['lsTaskData']))
			$qry="SELECT lawsuitTaskId AS id,`taskName`,`taskDescription`,`assignedToId`,`startDate`,`dueDate` FROM tbl_lawsuit_task t WHERE t.`isActive`=1 and lawsuitTaskId=:id";
		else if(isset($_POST['imageData']))
			$qry="SELECT lsImageId AS id,imageName FROM tbl_lawsuit_images WHERE isActive=1 AND lsImageId=:id";
		else if(isset($_POST['paperData']))
			$qry="SELECT lsPaperId as id,lsDetailsId,paperName,paperPath,paperDetails FROM tbl_lawsuit_papers WHERE isActive=1 AND lsPaperId=:id";
		else if(isset($_POST['numberData']))
			$qry="SELECT lsNumberId as id,lsDetailsId,numberName,numberValue,notes FROM tbl_lawsuit_numbers WHERE isActive=1 AND lsNumberId=:id";
		else if(isset($_POST['rulingData']))
			$qry="SELECT lsRulingId as id,lsDetailsId,appealdate,appealDetails FROM tbl_lawsuit_ruling WHERE isActive=1 AND lsRulingId=:id";
		else if(isset($_POST['objectionData']))
			$qry="SELECT lsObjectionsId AS id ,objectName,endDate,objectFilePath,objectDetails FROM tbl_lawsuit_objections WHERE isActive=1 AND lsObjectionsId=:id";
		else if(isset($_POST['vetoData']))
			$qry="SELECT lsVetolistId as id,vlName,endDate,vlDetails FROM tbl_lawsuit_vetolist WHERE isActive=1 AND lsVetolistId=:id";
		else if(isset($_POST['clearanceData']))
			$qry="SELECT lsClearanceformId as id,cfName,cfDetails FROM tbl_lawsuit_clearanceform WHERE isActive=1 AND lsClearanceformId=:id";
		
		if(!isset($qry))
			exit('invalid Paramerts');
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode(['status'=>true, 'data'=>$result],JSON_INVALID_UTF8_SUBSTITUTE);
		}
		else 
		{
			////$errorInfo = $stmt->errorInfo();
			////exit($json =$errorInfo[2]);
			echo json_encode(['status'=>false, 'data'=>'0'.get_lang_msg('errorMessage')],JSON_INVALID_UTF8_SUBSTITUTE);
		}
		die();
	}
	
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	if(isset($_FILES) && ! empty($_FILES))
	{
		$file_path='uploadFiles/';
		include_once('LawsuitDetailFilesAction.php');
	}
	/*
	if(isset($_POST['dateHSession']) && !empty($_POST['dateHSession']))
	{
		$_POST['dateHSession'] = date("j-n-Y", strtotime($_POST['dateHSession']));
		////$date = DateTime::createFromFormat('j/n/Y', $_POST['dateHSession']);
		////$_POST['dateHSession'] = $date->format('d-m-Y');
	}
	*/
	if(isset($_POST['action']) && $_POST['action']=='addSession')
	{
		/* 
		$qry="INSERT INTO tbl_lawsuit_sessions
            (lsDetailsId, sessionName,sessionDate,sessionTime,sessionPlace,
             sessionDetails,isActive,createdBy)
		VALUES (:lsDetailsId,:sessionName,:sessionDate,:sessionTime,
				:sessionPlace,:sessionDetails,1,:createdBy)";	
		*/
		$qry="INSERT INTO tbl_lawsuit_sessions
            (lsDetailsId, sessionName,sessionDate,sessionTime,
             sessionDetails,isActive,createdBy)
		VALUES (:lsDetailsId,:sessionName,:sessionDate,:sessionTime,
				:sessionDetails,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":sessionName",$_POST['nameSession'],PDO::PARAM_STR);
		$stmt->bindParam(":sessionDate",$_POST['dateSession'],PDO::PARAM_STR);
		$stmt->bindParam(":sessionTime",$_POST['timeSession'],PDO::PARAM_STR);
		////$stmt->bindParam(":sessionPlace",$_POST['placeSession'],PDO::PARAM_STR);
		$stmt->bindParam(":sessionDetails",$_POST['sessionDetails'],PDO::PARAM_STR);
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
		die();
	}
	
	if(isset($_POST['action']) && $_POST['action']=='addTask')
	{
		$qry="INSERT INTO tbl_lawsuit_task (lsDetailsId,taskName,taskDescription,assignedToId,startDate,dueDate,isActive,createdBy)
		VALUES (:lsDetailsId,:taskName,:taskDescription,:assignedToId,:startDate,:dueDate,1,:createdBy);";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":taskName",$_POST['name'],PDO::PARAM_STR);
		$stmt->bindParam(":taskDescription",$_POST['taskDesc'],PDO::PARAM_STR);
		$stmt->bindParam(":assignedToId",$_POST['assigTo'],PDO::PARAM_INT);
		$stmt->bindParam(":startDate",$_POST['startDate'],PDO::PARAM_STR);
		$stmt->bindParam(":dueDate",$_POST['dueDate'],PDO::PARAM_STR);
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
	
	if(isset($_POST['action']) && $_POST['action']=='editSession')
	{
		/* 
			$qry="update tbl_lawsuit_sessions set lsDetailsId=:lsDetailsId,sessionName=:sessionName,
		sessionDate=:sessionDate,sessionTime=:sessionTime,
		sessionPlace=:sessionPlace,sessionDetails=:sessionDetails,modifiedBy=:modifiedBy,
		modifiedDate=now() where lsSessionId=:lsSessionId";
			
		*/

		$qry="update tbl_lawsuit_sessions set lsDetailsId=:lsDetailsId,sessionName=:sessionName,
		sessionDate=:sessionDate,sessionTime=:sessionTime,sessionDetails=:sessionDetails,
		modifiedBy=:modifiedBy,modifiedDate=now() where lsSessionId=:lsSessionId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":sessionName",$_POST['nameSession'],PDO::PARAM_STR);
		$stmt->bindParam(":sessionDate",$_POST['dateSession'],PDO::PARAM_STR);
		$stmt->bindParam(":sessionTime",$_POST['timeSession'],PDO::PARAM_STR);
		/////$stmt->bindParam(":sessionPlace",$_POST['placeSession'],PDO::PARAM_STR);
		$stmt->bindParam(":sessionDetails",$_POST['sessionDetails'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsSessionId",$_POST['id'],PDO::PARAM_INT);
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
	
	if(isset($_POST['action']) && $_POST['action']=='addImage')
	{
		
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailImage';
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$column=",imagePath";
			$values=",:imagePath";
			$filePath=$filePath.$filename;
		}
		else 
		{
			$column="";
			$values="";
		}
		$qry="INSERT INTO tbl_lawsuit_images (lsDetailsId,imageName $column,isActive,createdBy)
				VALUES (:lsDetailsId,:imageName $values,1,:createdBy)";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":imageName",$_POST['nameImage'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":imagePath",$filePath,PDO::PARAM_STR);
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
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='editTask')
	{
		$qry="UPDATE tbl_lawsuit_task 
		SET taskName = :taskName,
		  taskDescription = :taskDescription,
		  assignedToId = :assignedToId,
		  startDate = :startDate,
		  dueDate = :dueDate,
		  modifiedBy = :modifiedBy,
		  modifiedDate = now()
		WHERE lawsuitTaskId = :lawsuitTaskId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":taskName",$_POST['name'],PDO::PARAM_STR);
		$stmt->bindParam(":taskDescription",$_POST['taskDesc'],PDO::PARAM_STR);
		$stmt->bindParam(":assignedToId",$_POST['assigTo'],PDO::PARAM_INT);
		$stmt->bindParam(":startDate",$_POST['startDate'],PDO::PARAM_STR);
		$stmt->bindParam(":dueDate",$_POST['dueDate'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lawsuitTaskId",$_POST['id'],PDO::PARAM_INT);
		/////$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
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
	
	if(isset($_POST['action']) && $_POST['action']=='editImage')
	{
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailImage';
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
			$column=" ,imagePath=:imagePath";
			
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$filePath=$filePath.$filename;
		}
		else 
			$column="";
		$qry="UPDATE tbl_lawsuit_images SET imageName=:imageName $column,modifiedBy=:modifiedBy,
				modifiedDate=NOW() WHERE lsImageId=:lsImageId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":imageName",$_POST['nameImage'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":imagePath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsImageId",$_POST['id'],PDO::PARAM_INT);
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
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='addPaper')
	{
		
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailPaper';
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$column=",paperPath";
			$values=",:paperPath";
			$filePath=$filePath.$filename;
		}
		else 
		{
			$column="";
			$values="";
		}
		$qry="INSERT INTO tbl_lawsuit_papers (lsDetailsId, paperName $column,paperDetails,isActive,createdBy)
		values (:lsDetailsId,:paperName $values,:paperDetails,1,:createdBy);)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":paperName",$_POST['namePaper'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":paperPath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":paperDetails",$_POST['paperDetails'],PDO::PARAM_STR);
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
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='editPaper')
	{
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailPaper';
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
			$column=" ,paperPath=:paperPath";
			
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$filePath=$filePath.$filename;
		}
		else 
			$column="";
		$qry="UPDATE tbl_lawsuit_papers SET lsDetailsId=:lsDetailsId, paperName=:paperName,paperDetails=:paperDetails $column,modifiedBy=:modifiedBy,
				modifiedDate=NOW() WHERE lsPaperId=:lsPaperId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":paperName",$_POST['namePaper'],PDO::PARAM_STR);
		
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":paperPath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":paperDetails",$_POST['paperDetails'],PDO::PARAM_STR);
		$stmt->bindParam(":lsPaperId",$_POST['id'],PDO::PARAM_INT);
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
		die();
	}
	
	if(isset($_POST['action']) && $_POST['action']=='addNumber')
	{
		$qry="INSERT INTO tbl_lawsuit_numbers
            (lsDetailsId,numberName,numberValue,notes,isActive,createdBy)
		VALUES (:lsDetailsId,:numberName,:numberValue,:notes,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":numberName",$_POST['nameNumber'],PDO::PARAM_STR);
		$stmt->bindParam(":numberValue",$_POST['nameValue'],PDO::PARAM_STR);
		$stmt->bindParam(":notes",$_POST['notes'],PDO::PARAM_STR);
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
		die();
	}
	
	if(isset($_POST['action']) && $_POST['action']=='editNumber')
	{
		$qry="update tbl_lawsuit_numbers set lsDetailsId=:lsDetailsId,numberName=:numberName,
		numberValue=:numberValue,notes=:notes,modifiedBy=:modifiedBy,modifiedDate=now() where lsNumberId=:lsNumberId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":numberName",$_POST['nameNumber'],PDO::PARAM_STR);
		$stmt->bindParam(":numberValue",$_POST['nameValue'],PDO::PARAM_STR);
		$stmt->bindParam(":notes",$_POST['notes'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsNumberId",$_POST['id'],PDO::PARAM_INT);
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
	if(isset($_POST['action']) && $_POST['action']=='addRuling')
	{
		
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailApeal';
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$filePath=$filePath.$filename;
		}
		else 
			$filePath="";
		$qry="INSERT INTO tbl_lawsuit_ruling(lsDetailsId,appealdate,appealFilePath,appealDetails,isActive,createdBy)
		values (:lsDetailsId,:appealdate,:appealFilePath,:appealDetails,1,:createdBy)";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":appealdate",$_POST['rulingDate'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":appealFilePath",$filePath,PDO::PARAM_STR);
		else 
			$stmt->bindParam(":appealFilePath",$filePath,PDO::PARAM_NULL);
		$stmt->bindParam(":appealDetails",$_POST['rulingDetails'],PDO::PARAM_STR);
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
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='editRuling')
	{
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailApeal';
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
			$column=" ,appealFilePath=:appealFilePath";
			
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$filePath=$filePath.$filename;
		}
		else 
			$column="";
		$qry="UPDATE tbl_lawsuit_ruling SET lsDetailsId=:lsDetailsId, 
		appealDate=:appealDate,appealDetails=:appealDetails $column,modifiedBy=:modifiedBy,
				modifiedDate=NOW() WHERE lsRulingId=:lsRulingId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":appealDate",$_POST['rulingDate'],PDO::PARAM_STR);
		
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":paperPath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":appealDetails",$_POST['rulingDetails'],PDO::PARAM_STR);
		$stmt->bindParam(":lsRulingId",$_POST['id'],PDO::PARAM_INT);
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
		die();
	}
	
	if(isset($_POST['action']) && $_POST['action']=='addObjection')
	{
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailObjection';
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$filePath=$filePath.$filename;
			$column=",objectFilePath";
			$values=",:objectFilePath";
		}
		else 
		{
			$column="";
			$values="";
		}
		$qry="INSERT INTO tbl_lawsuit_objections(lsDetailsId,objectName,endDate $column,objectDetails,isActive,createdBy)
		values (:lsDetailsId,:objectName,:endDate $values,:objectDetails,1,:createdBy)";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":objectName",$_POST['nameObjection'],PDO::PARAM_STR);
		$stmt->bindParam(":endDate",$_POST['dateObjection'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":objectFilePath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":objectDetails",$_POST['objectionNotes'],PDO::PARAM_STR);
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
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='editObjection')
	{
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailObjection';
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
			$column=" ,objectFilePath=:objectFilePath";
			
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$filePath=$filePath.$filename;
		}
		else 
			$column="";
		$qry="UPDATE tbl_lawsuit_objections SET lsDetailsId=:lsDetailsId, 
		objectName=:objectName,endDate=:endDate,objectDetails=:objectDetails $column,modifiedBy=:modifiedBy,
				modifiedDate=NOW() WHERE lsObjectionsId=:lsObjectionsId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":objectName",$_POST['nameObjection'],PDO::PARAM_STR);
		$stmt->bindParam(":endDate",$_POST['dateObjection'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":objectFilePath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":objectDetails",$_POST['objectionNotes'],PDO::PARAM_STR);
		
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsObjectionsId",$_POST['id'],PDO::PARAM_INT);
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
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='addVeto')
	{
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailVeto';
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$filePath=$filePath.$filename;
			$column=",vlFilePath";
			$values=",:vlFilePath";
		}
		else 
		{
			$column="";
			$values="";
		}
		$qry="INSERT INTO tbl_lawsuit_vetolist(lsDetailsId,vlName,endDate,vlDetails $column,isActive,createdBy)
		values (:lsDetailsId,:vlName,:endDate,:vlDetails $values,1,:createdBy)";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":vlName",$_POST['nameVeto'],PDO::PARAM_STR);
		$stmt->bindParam(":endDate",$_POST['dateVeto'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":vlFilePath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":vlDetails",$_POST['vetoNotes'],PDO::PARAM_STR);
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
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='editVeto')
	{
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailVeto';
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
			$column=" ,vlFilePath=:vlFilePath";
			
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$filePath=$filePath.$filename;
		}
		else 
			$column="";
		$qry="UPDATE tbl_lawsuit_vetolist SET lsDetailsId=:lsDetailsId, 
		vlName=:vlName,endDate=:endDate,vlDetails=:vlDetails $column,modifiedBy=:modifiedBy,
				modifiedDate=NOW() WHERE lsVetolistId=:lsVetolistId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":vlName",$_POST['nameVeto'],PDO::PARAM_STR);
		$stmt->bindParam(":endDate",$_POST['dateVeto'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":vlFilePath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":vlDetails",$_POST['vetoNotes'],PDO::PARAM_STR);
		
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsVetolistId",$_POST['id'],PDO::PARAM_INT);
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
		die();
	}
	
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		if($_POST['op']=='session')
			$qry="UPDATE tbl_lawsuit_sessions SET isActive=-1, modifiedBy=:modifiedBy,modifiedDate= NOW() WHERE lsSessionId = :id";
		if($_POST['op']=='task')
			$qry="UPDATE tbl_lawsuit_task SET isActive=-1, modifiedBy=:modifiedBy,modifiedDate= NOW() WHERE lawsuitTaskId = :id";
		
		if($_POST['op']=='image')
		{
			$qry="UPDATE tbl_lawsuit_images SET isActive=-1,modifiedBy=:modifiedBy,modifiedDate=NOW() WHERE lsImageId=:id";
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
		}
		if($_POST['op']=='paper')
		{
			$qry="UPDATE tbl_lawsuit_papers SET isActive=-1,modifiedBy=:modifiedBy,modifiedDate=NOW() WHERE lsPaperId=:id";
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
		}
		if($_POST['op']=='number')
			$qry="UPDATE tbl_lawsuit_numbers SET isActive=-1, modifiedBy=:modifiedBy,modifiedDate= NOW() WHERE lsNumberId= :id";
		
		if($_POST['op']=='ruling')
		{
			$qry="UPDATE tbl_lawsuit_ruling SET isActive=-1,modifiedBy=:modifiedBy,modifiedDate=NOW() WHERE lsRulingId=:id";
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
		}
		if($_POST['op']=='objection')
		{
			$qry="UPDATE tbl_lawsuit_objections SET isActive=-1,modifiedBy=:modifiedBy,modifiedDate=NOW() WHERE lsObjectionsId=:id";
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
		}
		
		if($_POST['op']=='veto')
		{
			$qry="UPDATE tbl_lawsuit_vetolist SET isActive=-1,modifiedBy=:modifiedBy,modifiedDate=NOW() WHERE lsVetolistId=:id";
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
		}
		if($_POST['op']=='clearance')
		{
			$qry="UPDATE tbl_lawsuit_clearanceform SET isActive=-1,modifiedBy=:modifiedBy,modifiedDate=NOW() WHERE lsClearanceformId=:id";
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
		}
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
	
	if(isset($_POST['action']) && $_POST['action']=='addClearance')
	{
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailClearance';
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$column=",cfFilePath";
			$values=",:cfFilePath";
			$filePath=$filePath.$filename;
		}
		else 
		{
			$column="";
			$values="";
		}
		$qry="INSERT INTO tbl_lawsuit_clearanceform (lsDetailsId,cfName,cfDetails $column,isActive,createdBy)
		values (:lsDetailsId,:cfName,:cfDetails $values,1,:createdBy);)";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":cfName",$_POST['nameClearance'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":cfFilePath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":cfDetails",$_POST['NoteClearance'],PDO::PARAM_STR);
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
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='editClearance')
	{
		if(isset($_FILES) && ! empty($_FILES))
		{
			$fileDirName='lsDetailClearance';
			$magePath=getImagePath($dbo);
			if(file_exists($magePath))
				unlink($magePath);
			$column=" ,cfFilePath=:cfFilePath";
			
			$filePath=$file_path.$fileDirName.'/';
			check_file($fileDirName,$filePath);
			$filename=set_file_name($fileDirName);
			upload_file($fileDirName,$filePath,$filename);
			$filePath=$filePath.$filename;
		}
		else 
			$column="";
		$qry="UPDATE tbl_lawsuit_clearanceform SET lsDetailsId=:lsDetailsId, cfName=:cfName,cfDetails=:cfDetails $column,modifiedBy=:modifiedBy,
				modifiedDate=NOW() WHERE lsClearanceformId=:lsClearanceformId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['dId'],PDO::PARAM_INT);
		$stmt->bindParam(":cfName",$_POST['nameClearance'],PDO::PARAM_STR);
		if(isset($_FILES) && ! empty($_FILES))
			$stmt->bindParam(":cfFilePath",$filePath,PDO::PARAM_STR);
		$stmt->bindParam(":cfDetails",$_POST['NoteClearance'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":lsClearanceformId",$_POST['id'],PDO::PARAM_INT);
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
		die();
	}
	function getImagePath($dbo)
	{
		if(isset($_POST['action']) && ($_POST['action']=='editImage')
				|| (isset($_POST['op']) && $_POST['action']=='del')
		)
			$qry="SELECT imagePath as imagePath FROM tbl_lawsuit_images WHERE isActive=1 AND lsImageId=:id";
		if(isset($_POST['action']) && ($_POST['action']=='editPaper')
				|| (isset($_POST['op']) && $_POST['action']=='del')
		)
			$qry="SELECT paperPath as imagePath FROM tbl_lawsuit_papers WHERE isActive=1 AND lsPaperId=:id";
		if(isset($_POST['action']) && ($_POST['action']=='editRuling')
				|| (isset($_POST['op']) && $_POST['op']=='ruling')
		)
			$qry="SELECT appealFilePath as imagePath FROM tbl_lawsuit_ruling WHERE isActive=1 AND lsRulingId=:id";
		
		if(isset($_POST['action']) && ($_POST['action']=='editObjection')
				|| (isset($_POST['op']) && $_POST['op']=='objection')
		)
			$qry="SELECT objectFilePath as imagePath FROM tbl_lawsuit_objections WHERE isActive=1 AND lsObjectionsId=:id";
		
		if(isset($_POST['action']) && ($_POST['action']=='editVeto')
				|| (isset($_POST['op']) && $_POST['op']=='veto')
		)
			$qry="SELECT vlFilePath as imagePath FROM tbl_lawsuit_vetolist WHERE isActive=1 AND lsVetolistId=:id";
		
		if(isset($_POST['action']) && ($_POST['action']=='editClearance')
				|| (isset($_POST['op']) && $_POST['op']=='clearance')
		)
			$qry="SELECT cfFilePath as imagePath FROM tbl_lawsuit_clearanceform WHERE isActive=1 AND lsClearanceformId=:id";
		
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result[0]['imagePath'];
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	
?>