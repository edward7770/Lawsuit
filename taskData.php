<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	$where="";
	if(isset($_POST['searchId']) && !empty($_POST['searchId']))
	{
		$where.=" AND t.taskId=:id";
	}
	if(isset($_POST['startDate']) && !empty($_POST['startDate']))
	{
		$where.=" AND (t.startDate BETWEEN :startDate AND :startDate )";
	}
	
	if(isset($_POST['dueDate']) && !empty($_POST['dueDate']))
	{
		$where.=" AND ( t.dueDate BETWEEN :dueDate AND :dueDate )";
	}
	
	if(isset($_POST['priority']) && !empty($_POST['priority']))
	{
		$where.=" AND (t.`priorityId`=:priority) ";
	}
	
	if(isset($_POST['taskStatus']) && !empty($_POST['taskStatus']))
	{
		$where.=" AND ( t.`statusId`=:taskStatus) ";
	}
	
	$qry="SELECT t.taskId,t.taskName,t.taskDescription,e.empName_$language as empName ,t.startDate,t.dueDate,p.taskpriorityName_$language as taskpriorityName ,s.taskStatusName_$language as taskStatusName  FROM tbl_task t 
	LEFT JOIN tbl_task_priority p ON p.taskpriorityId=t.priorityId
	LEFT JOIN tbl_task_status s ON s.taskStatusId=t.statusId
	LEFT JOIN tbl_employees e ON e.empId=t.assignedToId
	WHERE t.`isActive`=1 $where";
	$stmt=$dbo->prepare($qry);
	if(isset($_POST['searchId']) && !empty($_POST['searchId']))
		$stmt->bindParam(":id",$_POST['searchId'],PDO::PARAM_INT);
	
	if(isset($_POST['startDate']) && !empty($_POST['startDate']))
	{
		$stmt->bindParam(":startDate",$_POST['startDate'],PDO::PARAM_STR);
	}
	
	if(isset($_POST['dueDate']) && !empty($_POST['startDate']))
	{
		$stmt->bindParam(":dueDate",$_POST['dueDate'],PDO::PARAM_STR);
	}
	
	if(isset($_POST['priority']) && !empty($_POST['priority']))
	{
		$stmt->bindParam(":priority",$_POST['priority'],PDO::PARAM_INT);
	}
	
	if(isset($_POST['taskStatus']) && !empty($_POST['taskStatus']))
	{
		$stmt->bindParam(":taskStatus",$_POST['taskStatus'],PDO::PARAM_INT);
	}
	
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	$serial=1;
	////$countryName="countryName_".$language;
	////print_r($result);
	///$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	///$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	foreach($result as $index=> $value)
	{ ?>
	<tr>
		
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['taskId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['taskId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a> &nbsp;&nbsp;
			<a href="javascript:showDetailModal('desc',<?php echo $index; ?>);" class="btn-action-icon me-2"><span><i class="fa fa-eye"></i></span></a>
			<input type='hidden' id='desc<?php echo $index; ?>' value='<?php echo $value['taskDescription']; ?>' >
		</td>
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value['taskName']; ?></td>
		<td><?php echo $value['empName']; ?></td>
		<td><?php echo $value['startDate']; ?></td>
		<td><?php echo $value['dueDate']; ?></td>
		<td><?php echo $value['taskpriorityName']; ?></td>
		<td><?php echo $value['taskStatusName']; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
