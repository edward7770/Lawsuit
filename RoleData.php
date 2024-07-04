
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	
	$where="";
	if($superAdminId!=$_SESSION['roleId'])
		$where=" AND r.`roleId`<>1";
	
	$qry="SELECT r.roleId, r.roleName,p.pageName, r.isActive FROM tbl_role r 
		LEFT JOIN tbl_pagemenu p ON p.`pageId`=r.`roleDefaultPage`
		WHERE r.isActive<>-1 $where ORDER BY roleName";
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
	////exit;
	$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	foreach($result as $value)
	{ ?>
	<tr>
		
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['roleId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['roleId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value['roleName']; ?></td>
		<td><?php echo $value['pageName']; ?></td>
		<td><?php if($value['isActive']) echo $checkButton; else echo $crossButton; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
