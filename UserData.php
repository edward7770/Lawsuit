
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	
	$where="";
	if($superAdminId!=$_SESSION['roleId'])
	$where=" AND r.`roleId`<>1";
	if(isset($_POST['employee']))
	{
		$where.=" AND u.`userType`=2";
	}
	else 
	$where.=" AND u.`userType`=1";
	
	$qry="SELECT u.userId,u.userName,u.fullName,u.isActive, r.`roleName`, IF(u.`userType`=1,'Customer','Employee') AS userType 
	,IF(e.`empCatId`=1,'Lawyer','') AS userCategory, e.empName_$language as empName FROM `tbl_user` u
	LEFT JOIN tbl_role r ON r.`roleId`=u.`roleId`
	LEFT JOIN tbl_employees e ON e.`empId`=u.`empId`
	WHERE u.isActive<>-1 $where";
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
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
	$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	foreach($result as $value)
	{ ?>
	<tr>
		
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['userId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['userId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value['userName']; ?></td>
		<td><?php echo $value['fullName']; ?></td>
		<td><?php echo $value['userType']; ?></td>
		<td><?php echo $value['userCategory']; ?></td>
		<td><?php echo $value['empName']; ?></td>
		<td><?php if($value['isActive']) echo $checkButton; else echo $crossButton; ?></td>
		<td><?php echo $value['roleName']; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
