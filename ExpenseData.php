<?php
include_once('config/conn.php');
if(isset($_POST['getData']))
{
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
	$language=$_SESSION['lang'];
	$qry="SELECT expenseId,e.`expCatId` AS catId,m.`ls_code`,expenseDate,supplier, pm.name_$language AS expenseMode,amount,taxValue, taxAmount,totalExpAmount,remarks
	FROM tbl_expense e
	LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=e.`lsMasterId`
	LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=e.`expenseMode` AND pm.`isActive`=1
	WHERE e.`isActive`=1";
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
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['expenseId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['expenseId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td> <?php echo $serial; ?> </td>
		<td><?php if($value['catId']==1) echo 'Lawsuit'; else echo 'General Expense'; ?></td>
		<td><?php echo $value['ls_code']; ?></td>
		<td><?php echo $value['supplier']; ?></td>
		<td><?php echo number_format((float)$value['amount'],$decimalplace); ?></td>
		<td><?php echo number_format((float)$value['taxAmount'],$decimalplace); ?></td>
		<td><?php echo number_format((float)$value['totalExpAmount'],$decimalplace); ?></td>
		<td><?php echo $value['expenseDate']; ?></td>
		<td><?php echo $value['expenseMode']; ?></td>
		<td><?php echo $value['remarks']; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
}
else if(isset($_POST['getExpense']))
{
	$stmt=$dbo->prepare("CALL sp_getExpenseDetails()");
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
