<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	
	$where="";
	if(isset($_POST['searchId']) && !empty($_POST['searchId']))
	{
		$where="1";
		$qry="call sp_getEmployeeContract(:id)";
	}
	else 
		$qry="call sp_getEmployeeContract(0)";
	
	$stmt=$dbo->prepare($qry);
	if(!empty($where))
		$stmt->bindParam(":id",$_POST['searchId'],PDO::PARAM_INT);
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
	$empName='empName_'.$language;
	foreach($result as $index=> $value)
	{ ?>
	<tr>
		
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['empContractId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['empContractId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a> &nbsp;&nbsp;
		</td>
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value[$empName]; ?></td>
		<td><?php echo $value['categoryName']; ?></td>
		<td><?php echo $value['contractName']; ?></td>
		<td><?php echo $value['contractDateFrom']; ?></td>
		<td><?php echo $value['contractDateTo']; ?></td>
		<td><?php echo setAmountDecimal($value['allowances']); ?></td>
		<td><?php echo setAmountDecimal($value['deduction']); ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
