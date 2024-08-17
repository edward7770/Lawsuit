
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	$qry="SELECT o.`opponentId`, o.`oppoName_ar`, o.`oppoName_en`, o.`oppoAddress`, o.`oppoContact` ,n.`nationalityName`
			FROM tbl_opponents o 
			LEFT JOIN tbl_nationality n ON n.`nationalityId`=o.`oppoNationality`
			WHERE o.`isActive`=1";
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
	////print_r($result);
	foreach($result as $value)
	{ ?>
	<tr>
		<td> <?php echo $serial; ?> </td>
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['opponentId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['opponentId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td><?php echo $value['oppoName_'.$language]; ?></td>
		<td><?php echo $value['oppoContact']; ?></td>
		<td><?php echo $value['nationalityName']; ?></td>
		<td><?php echo $value['oppoAddress']; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
