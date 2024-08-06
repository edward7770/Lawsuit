<?php
include_once('config/conn.php');
if (isset($_POST['getData'])) {
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	$language = $_SESSION['lang'];
	$qry = "SELECT expenseId,e.`expCatId` AS catId,m.`ls_code`,expenseDate,supplier,invoiceNumber, pm.name_$language AS expenseMode,amount,taxValue, taxAmount,totalExpAmount,remarks
	FROM tbl_expense e
	LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=e.`lsMasterId`
	LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=e.`expenseMode` AND pm.`isActive`=1
	WHERE e.`isActive`=1";
	$stmt = $dbo->prepare($qry);
	/////$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
	if ($stmt->execute()) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$errorInfo = $stmt->errorInfo();
		exit($json = $errorInfo[2]);
	}
	$serial = 1;

	foreach ($result as $value) { ?>
		<tr>
			<td class="d-flex align-items-center">
				<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['expenseId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
				<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['expenseId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
				&nbsp; &nbsp; 
				<a href="#" class="btn-action-icon" onclick="printExpenseReceipt(<?php echo $value['expenseId']; ?>);"><span><i class="fa fa-print"></i></span></a>
			</td>
			<td> <?php echo $serial; ?> </td>
			<td><?php if ($value['catId'] == 1) echo 'Lawsuit';
				else echo 'General Expense'; ?></td>
			<td><?php echo $value['ls_code']; ?></td>
			<td><?php echo $value['supplier']; ?></td>
			<td><?php echo number_format((float)$value['amount'], $decimalplace); ?></td>
			<td><?php echo number_format((float)$value['taxAmount'], $decimalplace); ?></td>
			<td><?php echo number_format((float)$value['totalExpAmount'], $decimalplace); ?></td>
			<td><?php echo $value['expenseDate']; ?></td>
			<td><?php echo $value['expenseMode']; ?></td>
			<td><?php echo $value['invoiceNumber']; ?></td>
			<td><?php echo $value['remarks']; ?></td>
		</tr>

	<?php
		$serial++;
	}
} else if (isset($_POST['getExpense'])) {
	$stmt = $dbo->prepare("CALL sp_getExpenseDetails()");
	if ($stmt->execute()) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$errorInfo = $stmt->errorInfo();
		exit($json = $errorInfo[2]);
	}
	if ($result)
		echo json_encode(['status' => true, 'data' => $result], JSON_INVALID_UTF8_SUBSTITUTE);
	else
		echo json_encode(['status' => false], JSON_INVALID_UTF8_SUBSTITUTE);
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

	$qry_expense = "SELECT expenseId,e.`expCatId` AS catId,m.`ls_code`,expenseDate,supplier,invoiceNumber, pm.name_$language AS expenseMode,amount,taxValue, taxAmount,totalExpAmount,remarks
	FROM tbl_expense e
	LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=e.`lsMasterId`
	LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=e.`expenseMode` AND pm.`isActive`=1
	WHERE e.`isActive`=1";
	$stmt_expense = $dbo->prepare($qry_expense);
	/////$stmt_expense->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
	if ($stmt_expense->execute()) {
		$result_expense = $stmt_expense->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$errorInfo = $stmt_expense->errorInfo();
		exit($json = $errorInfo[2]);
	}
	$serial = 1;
	?>
	<h6><?php echo set_value('expense'); ?></h6>
	<table class="table table-center table-hover datatable" id='setExpenseData'>
		<thead class="thead-light">
			<tr>
				<th>#</th>
				<th><?php echo set_value('expenseCategory'); ?></th>
				<th><?php echo set_value('lsMasterCode'); ?></th>
				<th><?php echo set_value('supplier'); ?></th>
				<th><?php echo set_value('expenseAmount'); ?></th>
				<th><?php echo set_value('taxValueAmount'); ?></th>
				<th><?php echo set_value('amountWithTax'); ?></th>
				<th><?php echo set_value('expenseDate'); ?></th>
				<th><?php echo set_value('expenseMode'); ?></th>
				<th><?php echo set_value('remarks'); ?></th>
			</tr>
		</thead>
		<tbody id='setData'>
			<?php

			foreach ($result_expense as $value) {
				if (new DateTime($_POST['from']) <= new DateTime($value['expenseDate']) && new DateTime($value['expenseDate']) <= new DateTime($_POST['to'])) { ?>
					<tr>
						<td> <?php echo $serial; ?> </td>
						<td><?php if ($value['catId'] == 1) echo 'Lawsuit';
							else echo 'General Expense'; ?></td>
						<td><?php echo $value['ls_code']; ?></td>
						<td><?php echo $value['supplier']; ?></td>
						<td><?php echo number_format((float)$value['amount'], $decimalplace); ?></td>
						<td><?php echo number_format((float)$value['taxAmount'], $decimalplace); ?></td>
						<td><?php echo number_format((float)$value['totalExpAmount'], $decimalplace); ?></td>
						<td><?php echo $value['expenseDate']; ?></td>
						<td><?php echo $value['expenseMode']; ?></td>
						<td><?php echo $value['remarks']; ?></td>
					</tr>

			<?php
					$serial++;
				}
			} ?>
		</tbody>
	</table>
	<script>
		$('#setData_expense .datatable').DataTable({
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