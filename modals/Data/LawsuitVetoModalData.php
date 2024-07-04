<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('../../config/conn.php');
	$qry="SELECT lsVetolistId,vlDetails,vlName,endDate,vlFilePath,createdDate FROM tbl_lawsuit_vetolist WHERE isActive=1 AND lsDetailsId=:lsDetailsId";
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
					<a href="#" class="btn-action-icon me-2" onclick="editVeto(<?php echo $row['lsVetolistId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
					<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $row['lsVetolistId']; ?>,'veto');"><span><i class="fe fe-trash-2"></i></span></a>&nbsp;&nbsp;
				<?php } ?>
				<a href="javascript:showDetailModal('veto',<?php echo $index; ?>);" class="btn-action-icon me-2"><span><i class="fe fe-eye"></i></span></a>
			</td>
			<td><?php echo $row['vlName']; ?></td>
			<td><?php echo $row['endDate']; ?></td>
			<td> <?php if(!empty($row['vlFilePath'])) { ?>
			<a href="<?php echo $row['vlFilePath']; ?>" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>  
			<?php } else { ?>
			<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>
			<?php } ?>
			</td>
			<td><?php echo $row['createdDate']; ?></td>
		</tr>
		<input type='hidden' id='VetoDetail<?php echo $index; ?>' value='<?php echo $row['vlDetails']; ?>' >
	<?php
	} ?>
	
		