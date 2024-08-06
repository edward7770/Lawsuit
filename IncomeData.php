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
	$qry="SELECT incomeId,incomeTypeId,i.lsMasterId,m.`ls_code`,l.empName_$language AS receivedBy,description,amount,taxValue,taxAmount,totalIncomeAmount,incomeDate,invoiceNumber
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
			&nbsp; &nbsp; 
			<a href="#" class="btn-action-icon" onclick="printIncomeReceipt(<?php echo $value['incomeId']; ?>);"><span><i class="fa fa-print"></i></span></a>
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
		<td><?php echo $value['invoiceNumber']; ?></td>
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
} else {
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	/////include_once('languageActions.php');
	$language = $_SESSION['lang'];
	$pageName = "Lawsuit";
	$pageName2 = "LawsuitDetail";
	$qry = "SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName,:pageName2)";
	$stmt = $dbo->prepare($qry);
	$stmt->bindParam(":pageName", $pageName, PDO::PARAM_STR);
	$stmt->bindParam(":pageName2", $pageName2, PDO::PARAM_STR);
	if ($stmt->execute()) {
		$resultPhrase = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
	} else {
		$errorInfo = $stmt->errorInfo();
		exit($json = $errorInfo[2]);
	}
	////print_r($result);
	function set_value($val)
	{
		foreach ($GLOBALS['resultPhrase'] as $value) {
			if (trim($value['phrase']) == trim($val)) {
				return $value['VALUE'];
				break;
			}
		}
	}

	$qry_income="SELECT incomeId,incomeTypeId,i.lsMasterId,m.`ls_code`,l.empName_$language AS receivedBy,description,amount,taxValue,taxAmount,totalIncomeAmount,incomeDate
	FROM tbl_income i
	LEFT JOIN `tbl_employees` l ON l.`empId`=i.`incomeReceivedBy`
	LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=i.`lsMasterId`
	WHERE i.`isActive`=1";
	$stmt_income=$dbo->prepare($qry_income);
	/////$stmt_income->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
	if($stmt_income->execute())
	{
		$result_income = $stmt_income->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt_income->errorInfo();
		exit($json =$errorInfo[2]);
	}
	$serial=1;
	?>
	<h6><?php echo set_value('income'); ?></h6>
	<table class="table table-center table-hover datatable" id='setIncomeData'>
		<thead class="thead-light">
			<tr>
				<th>#</th>
				<th><?php echo set_value('incomeType'); ?></th>
				<th><?php echo set_value('lsMasterCode'); ?></th>
				<th><?php echo set_value('description'); ?></th>
				<th><?php echo set_value('amount'); ?></th>
				<th><?php echo set_value('taxValueAmount'); ?></th>
				<th><?php echo set_value('amountWithTax'); ?></th>
				<th><?php echo set_value('incomeDate'); ?></th>
				<th><?php echo set_value('receivedBy'); ?></th>
			</tr>
		</thead>
		<tbody id='setData'> 
	<?php
		foreach ($result_income as $value) {
			if (new DateTime($_POST['from']) <= new DateTime($value['incomeDate']) && new DateTime($value['incomeDate']) <= new DateTime($_POST['to'])) {
			?>
				<tr>
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
	?>
		</tbody>
	</table>
	<script>
		$('#setData_income .datatable').DataTable({
			"bFilter": true,
			"destroy": true,
			"sDom": 'fBtlpi',
			"ordering": true,
			"order": [],

			"language": {
				search: '<i class="fas fa-search"></i>',
				searchPlaceholder: "Search",
				sLengthMenu: '_MENU_',
				paginate: {
					next: 'Next <i class=" fa fa-angle-double-right ms-2"></i>',
					previous: '<i class="fa fa-angle-double-left me-2"></i> Previous'
				},
			},
			initComplete: (settings, json) => {
				$('.dataTables_filter').appendTo('#tableSearch');
				$('.dataTables_filter').appendTo('.search-input');
				$('.dt-buttons').css('display', 'none');
			},
		});
	</script>
<?php
}
?>
