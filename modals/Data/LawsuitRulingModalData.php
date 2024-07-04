<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('../../config/conn.php');
	$qry="SELECT lsRulingId,lsDetailsId,appealdate,appealDetails,appealFilePath,createdDate FROM tbl_lawsuit_ruling WHERE isActive=1  AND lsDetailsId=:lsDetailsId";
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
				<?php if($_SESSION['customerId']<=0) { ?>
				<a href="#" class="btn-action-icon me-2" onclick="editRuling(<?php echo $row['lsRulingId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
				<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $row['lsRulingId']; ?>,'ruling');"><span><i class="fe fe-trash-2"></i></span></a>&nbsp;&nbsp;
				<?php } ?>
				<a href="javascript:showDetailModal('appeal',<?php echo $index; ?>);" class="btn-action-icon me-2"><span><i class="fa fa-eye"></i></span></a>
			</td>
			<td><?php echo $row['appealdate']; ?></td>
			<td> <?php if(!empty($row['appealFilePath'])) { ?>
			<a href="<?php echo $row['appealFilePath']; ?>" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>  
				<?php } else { ?>
					<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>
			<?php } ?>
			</td>
			<td><?php echo $row['createdDate']; ?></td>
		</tr>
		<input type='hidden' id='appealDetail<?php echo $index; ?>' value='<?php echo $row['appealDetails']; ?>' >
	<?php
	} ?>
	
		