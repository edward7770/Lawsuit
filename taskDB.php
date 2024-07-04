<?php 
	session_start();
	include_once('config/conn.php');
	
	////$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		////echo get_lang_msg('errorMessage');
		die;
	}
	
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND t.taskId<>:taskId";
			
		$qry="SELECT t.taskId FROM tbl_task t WHERE t.isActive=1 AND t.taskName=:taskName $where";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":taskName",$_POST['name'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":taskId",$_POST['id'],PDO::PARAM_INT);
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
	
	if(isset($_POST['action']) && $_POST['action']=='add')
	{
		$qry="INSERT INTO tbl_task (taskName,taskDescription,assignedToId,startDate,dueDate,priorityId,statusId,isActive,createdBy)
		VALUES (:taskName,:taskDescription,:assignedToId,:startDate,:dueDate,:priorityId,:statusId,1,:createdBy);";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":taskName",$_POST['name'],PDO::PARAM_STR);
		$stmt->bindParam(":taskDescription",$_POST['taskDesc'],PDO::PARAM_STR);
		$stmt->bindParam(":assignedToId",$_POST['assigTo'],PDO::PARAM_INT);
		$stmt->bindParam(":startDate",$_POST['startDate'],PDO::PARAM_STR);
		$stmt->bindParam(":dueDate",$_POST['dueDate'],PDO::PARAM_STR);
		$stmt->bindParam(":priorityId",$_POST['priority'],PDO::PARAM_INT);
		$stmt->bindParam(":statusId",$_POST['taskStatus'],PDO::PARAM_INT);
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
		$qry="SELECT taskId as id,taskName,taskDescription,assignedToId,startDate,dueDate,priorityId,statusId FROM tbl_task WHERE isActive=1 AND taskId=:taskId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":taskId",$_POST['id'],PDO::PARAM_STR);
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
		$qry="UPDATE tbl_task 
		SET taskName = :taskName,
		  taskDescription = :taskDescription,
		  assignedToId = :assignedToId,
		  startDate = :startDate,
		  dueDate = :dueDate,
		  priorityId = :priorityId,
		  statusId = :statusId,
		  modifiedBy = :modifiedBy,
		  modifiedDate = now()
		WHERE taskId = :taskId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":taskName",$_POST['name'],PDO::PARAM_STR);
		$stmt->bindParam(":taskDescription",$_POST['taskDesc'],PDO::PARAM_STR);
		$stmt->bindParam(":assignedToId",$_POST['assigTo'],PDO::PARAM_INT);
		$stmt->bindParam(":startDate",$_POST['startDate'],PDO::PARAM_STR);
		$stmt->bindParam(":dueDate",$_POST['dueDate'],PDO::PARAM_STR);
		$stmt->bindParam(":priorityId",$_POST['priority'],PDO::PARAM_INT);
		$stmt->bindParam(":statusId",$_POST['taskStatus'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":taskId",$_POST['id'],PDO::PARAM_INT);
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
		$qry="UPDATE tbl_task SET isActive =-1, modifiedBy = :modifiedBy,modifiedDate = now() WHERE taskId = :taskId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":taskId",$_POST['id'],PDO::PARAM_INT);
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