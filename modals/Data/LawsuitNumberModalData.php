<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('../../config/conn.php');
	$qry="SELECT lsNumberId,lsDetailsId,numberName,numberValue,notes,createdDate FROM `tbl_lawsuit_numbers` WHERE isActive=1 AND lsDetailsId=:lsDetailsId";	
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":lsDetailsId",$_POST['id'],PDO::PARAM_INT);
	if($stmt->execute())
	{
		$resultSessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	foreach($resultSessions as $index=> $row)
	{
	?>
	<tr>
		<td class="d-flex align-items-center">
			<?php if($_SESSION['customerId']<=0)
			{ ?>
			<a href="#" class="btn-action-icon me-2" onclick="editNumber(<?php echo $row['lsNumberId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $row['lsNumberId']; ?>,'number');"><span><i class="fe fe-trash-2"></i></span></a>&nbsp;&nbsp;
			<?php
			}
			?>
			<a href="javascript:showDetailModal('number',<?php echo $index; ?>);" class="btn-action-icon me-2"><span><i class="fa fa-eye"></i></span></a>
		</td>
		<td><?php echo $row['numberName']; ?></td>
		<td><?php echo $row['numberValue']; ?></td>
		<td><?php echo $row['createdDate']; ?></td>
	</tr>
	<input type='hidden' id='numberDetail<?php echo $index; ?>' value='<?php echo $row['notes']; ?>' >
	<?php
	} ?>
	
		