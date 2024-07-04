<?php 
	/*
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	*/
	include_once('../../config/conn.php');
	$qry="SELECT lsPaperId,lsDetailsId,paperName,paperDetails,paperPath,createdDate FROM tbl_lawsuit_papers WHERE isActive=1 AND lsDetailsId=:lsDetailsId";	
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
			<a href="#" class="btn-action-icon me-2" onclick="editPaper(<?php echo $row['lsPaperId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $row['lsPaperId']; ?>,'paper');"><span><i class="fe fe-trash-2"></i></span></a>&nbsp;&nbsp;
			<a href="javascript:showDetailModal('paper',<?php echo $index; ?>);" class="btn-action-icon me-2"><span> <i class="fe fe-eye"></i></span></a>
		</td>
		<td><?php echo $row['paperName']; ?></td>
		<td> <?php if(!empty($row['paperPath'])) { ?>
			<a href="<?php echo $row['paperPath']; ?>" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>  
		<?php } else { ?>
					<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>
		<?php } ?>
		</td>
		<td><?php echo $row['createdDate']; ?></td>
	</tr>
	<input type='hidden' id='appealDetail<?php echo $index; ?>' value='<?php echo $row['paperDetails']; ?>' >
	<?php
	} ?>
	
		