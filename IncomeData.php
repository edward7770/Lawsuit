<?php
include_once('config/conn.php');
if(isset($_POST['getData']))
{
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	/*
	$qry="SELECT incomeId,incomeTypeId,c.customerName_$language as customerName,l.empName_$language AS receivedBy,e.empName_$language AS lawyer,description,amount,taxValue,totalIncomeAmount,incomeDate
	FROM tbl_income i
	LEFT JOIN `tbl_employees` e ON e.`empId`=i.`lawyerId`
	LEFT JOIN tbl_customers c ON c.`customerId`=i.`customerId`
	LEFT JOIN `tbl_employees` l ON l.`empId`=i.`incomeReceivedBy`
	WHERE i.`isActive`=1";
	*/
	$qry="SELECT incomeId,incomeTypeId,i.lsMasterId,m.`ls_code`,l.empName_$language AS receivedBy,description,amount,taxValue,taxAmount,totalIncomeAmount,incomeDate
	FROM tbl_income i
	LEFT JOIN `tbl_employees` l ON l.`empId`=i.`incomeReceivedBy`
	LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=i.`lsMasterId`
	WHERE i.`isActive`=1";
	$stmt=$dbo->prepare($qry);
	/////$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
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
	
	foreach($result as $value)
	{ ?>
	<tr>
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['incomeId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['incomeId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
	<td> <?php echo $serial; ?> </td>
		<td><?php if($value['incomeTypeId']==1) echo 'Lawsuit'; else echo 'General'; ?></td>
		<td><?php echo $value['ls_code']; ?></td>
		<?php /*
		<td><?php echo $value['customerName']; ?></td>
		<td><?php echo $value['lawyer']; ?></td>
		*/ ?>
		<td><?php echo $value['description']; ?></td>
		<td><?php echo setAmountDecimal($value['amount']); ?></td>
		<td><?php echo setAmountDecimal($value['taxAmount']); ?></td>
		<td><?php echo setAmountDecimal($value['totalIncomeAmount']); ?></td>
		<td><?php echo $value['incomeDate']; ?></td>
		<td><?php echo $value['receivedBy']; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
}
else if(isset($_POST['getIncome']))
{
	$stmt=$dbo->prepare("CALL sp_getIncomeDetails()");
	if($stmt->execute())
	{
		$result= $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	if($result)
		echo json_encode(['status'=>true, 'data'=>$result],JSON_INVALID_UTF8_SUBSTITUTE);
	else 
		echo json_encode(['status'=>false],JSON_INVALID_UTF8_SUBSTITUTE);
}
?>
