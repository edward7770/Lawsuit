
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	
	$where="";
	if(isset($_POST['searchId']) && !empty($_POST['searchId']))
	{
		$where=" AND c.consId=:id";
	}
	
	$qry="SELECT consId, cus.customerName_$language as customerName, e.empName_$language as empName ,title,contractDate,amount, tax,taxAmount, totalAmount, notes_$language as notes,isPaid FROM tbl_consultations c 
	LEFT JOIN tbl_customers cus ON cus.customerId=c.customerId
	LEFT JOIN tbl_employees e ON e.empId=c.lawyerId
	WHERE c.isActive=1 $where";
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
	$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	
	////print_r($result);
	foreach($result as $index=> $value)
	{ ?>
	<tr>
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['consId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['consId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>&nbsp;&nbsp;
			<?php /* (a href="#" class="btn-action-icon" onclick="payment(<?php echo $value['consId']; ?>);"><span><i class="fe fe-dollar-sign"></i></span></a>&nbsp;&nbsp; */ ?>
			<a href="javascript:showDetailModal('note',<?php echo $index; ?>);" class="btn-action-icon me-2"><span><i class="fe fe-eye"></i></span></a> 
			<input type='hidden' id='note<?php echo $index; ?>' value='<?php echo $value['notes']; ?>' >
		</td>
		
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value['customerName']; ?></td>
		<td><?php echo $value['empName']; ?></td>
		<td><?php echo $value['title']; ?></td>
		<td><?php echo $value['contractDate']; ?></td>
		<td><?php echo setAmountDecimal($value['amount']); ?></td>
		<td><?php echo setAmountDecimal($value['taxAmount']); ?></td>
		<td><?php echo setAmountDecimal($value['totalAmount']); ?></td>
		<td><?php if($value['isPaid']) echo $checkButton; else echo $crossButton; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
