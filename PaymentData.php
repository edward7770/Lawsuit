<?php
include_once('config/conn.php');

if (isset($_POST['getData'])) {
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language = $_SESSION['lang'];

	include_once('languageActions.php');

	$qry = "CALL sp_getLawsuitDetails('" . $language . "'," . $_SESSION['customerId'] . ",-1,-1,-1)";
	$stmt = $dbo->prepare($qry);
	////$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if ($stmt->execute()) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$errorInfo = $stmt->errorInfo();
		exit($json = $errorInfo[2]);
	}
	///exit;
	$serial = 1;
	$typeName = "lsTypeName_" . $language;
	$stateName = "lsStateName_" . $language;
	$stagesName = "lsStagesName_" . $language;
	$payment = set_value('payment');
	$expense = set_value('expense');
	$history = set_value('history');
	$checkButton = '<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton = '<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	////<td class="d-flex align-items-center">
	////print_r($result);

	$NotPaid = set_value('NotPaid');
	$FullPaid = set_value('FullPaid');
	$OutStanding = set_value('OutStanding');
	$paymentStatusVal = "";

	$invoiceDate = date('n/j/Y');

	include('get4setCurrency.php');

	foreach ($result as $value) {
		if ($value['paymentStatus'] == 'FullPaid') {
			$paymentStatus = "bg-success-light";
			$paymentStatusVal = $FullPaid;
		} else if ($value['paymentStatus'] == 'NotPaid') {
			$paymentStatus = "bg-danger-light";
			$paymentStatusVal = $NotPaid;
		} else if ($value['paymentStatus'] == 'OutStanding') {
			$paymentStatus = "bg-warning-light";
			$paymentStatusVal = $OutStanding;
		} else
			$paymentStatus = "";


?>
		<tr>
			<td class="d-flex"><?php /*
			<div class="dropdown dropdown-action">
				<a href="#" class=" btn-action-icon " data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
				
					<div class="dropdown-menu dropdown-menu-end">
					
					<ul>
						<li>
							<a class="dropdown-item" href="javascript:viewLSDetailsPayment(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);""><i class="far fa-edit me-2"></i><?php echo $payment; ?></a>
						</li>
						
						<li>
							<a class="dropdown-item" href="javascript:viewLSDetailsExpense(<?php echo $value['lsDetailsId']; ?>);""><i class="far fa-edit me-2"></i><?php echo $expense; ?></a>
						</li> 
						<li>
							<a class="dropdown-item" href="javascript:viewDetails(<?php echo $value['lsMasterId']; ?>);"><i class="far fa-eye me-2"></i><?php echo $history; ?></a>
						</li>
						
					</ul>
					
				</div>
			</div>
			*/ ?>
				<a href='javascript:viewLSDetailsPayment(
							"<?php echo $value['lsMasterId']; ?>",
							"<?php echo $value['lsDetailsId']; ?>",
							"<?php echo $value['lawsuitInvoiceNumber']; ?>",
							"<?php echo $value['ls_code']; ?>"
						);' class="btn-action-icon me-2"><span><?php echo $currencyText; ?></span></a>
				<?php
				if (isset($_POST['getLawsuitInvoice'])) {
				?>
					<a href='javascript:printInvoiceModal(
							"<?php echo $value['ls_code']; ?>",
							"<?php echo $value['referenceNo']; ?>",
							"<?php echo $value['lawsuitId']; ?>",
							"<?php echo $value['lsTypeName_' . $language]; ?>",
							"<?php echo $value['lsStateName_' . $language]; ?>",
							"<?php echo $value['lsStagesName_' . $language]; ?>",
							"<?php echo $value['lsDetailsId']; ?>",
							"<?php echo $value['lsMasterId']; ?>",
							"<?php echo $value['lawsuitInvoiceNumber']; ?>"
						);' class="btn-action-icon"><span><i class="fa fa-file-invoice"></i></span>
					</a>
					&nbsp;&nbsp;
					<a href='javascript:printLawsuitInvoice(
							"<?php echo $value['lsMasterId']; ?>",
							"<?php echo $value['lsDetailsId']; ?>",
							"<?php echo $value['lawsuitInvoiceNumber']; ?>",
							"<?php echo $value['ls_code']; ?>"
						);' class="btn-action-icon"><span><i class="fa fa-print"></i></span></a>
				<?php
				}
				?>
			</td>
			<td> <?php echo $serial; ?> </td>

			<td> <?php /* <a href="javascript:viewLSDetails(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);" ><?php echo $value['ls_code']; ?>  </a> */ echo $value['ls_code']; ?> </td>
			<td> <?php echo $value['customerName']; ?> </td>
			<td> <?php echo $value['empName_' . $language]; ?> </td>
			<td><?php echo $value[$typeName]; ?></td>
			<!--<td style="background-color:<?php ///echo $value['lsColor']; 
											?>"><?php ///echo $value[$stateName]; 
												?></td> -->
			<td style="color:<?php echo $value['lsColor']; ?>"><?php echo $value[$stateName]; ?></td>
			<td><?php echo $value[$stagesName]; ?></td>
			<td><?php echo $value['noofStages']; ?></td>
			<td><?php if ($value['isPaid']) echo $checkButton;
				else echo $crossButton; ?></td>
			<td><?php echo setAmountDecimal($value['totalAmount']); ?></td>
			<td><?php echo setAmountDecimal($value['paymentAmount']); ?></td>
			<td><?php echo setAmountDecimal($value['dueAmount']); ?></td>
			<td> <span class="badge badge-pill <?php echo $paymentStatus; ?>"><?php echo $paymentStatusVal; ?></span> </td>

		</tr>

	<?php
		$serial++;
	}
} else if (isset($_POST['getPayment'])) {
	// $stmt=$dbo->prepare("CALL sp_getPaymentDetails()");
	// if($stmt->execute())
	// {
	// 	$result= $stmt->fetchAll(PDO::FETCH_ASSOC);
	// }
	// else 
	// {
	// 	$errorInfo = $stmt->errorInfo();
	// 	exit($json =$errorInfo[2]);
	// }
	// if($result)
	// 	echo json_encode(['status'=>true, 'data'=>$result],JSON_INVALID_UTF8_SUBSTITUTE);
	// else 
	// 	echo json_encode(['status'=>false],JSON_INVALID_UTF8_SUBSTITUTE);

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language = $_SESSION['lang'];

	include_once('languageActions.php');

	$qry = "CALL sp_getLawsuitDetails('" . $language . "'," . $_SESSION['customerId'] . ",-1,-1,-1)";
	$stmt = $dbo->prepare($qry);
	////$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if ($stmt->execute()) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$errorInfo = $stmt->errorInfo();
		exit($json = $errorInfo[2]);
	}

	$totalCasesAmount = 0;
	$totalPayment = 0;
	$outstandingAmount = 0;

	foreach ($result as $value) {
		$totalCasesAmount += $value['totalAmount'];
		$totalPayment += $value['paymentAmount'];
		$outstandingAmount += $value['dueAmount'];
	}

	echo json_encode(['status' => true, 'totalCasesAmount' => $totalCasesAmount, 'totalPayment' => $totalPayment, 'outstandingAmount' => $outstandingAmount], JSON_INVALID_UTF8_SUBSTITUTE);
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


	?>
	<table class="table table-center table-hover datatable" id="example">
		<thead class="thead-light">
			<tr>
				<!-- <th><?php echo set_value('action'); ?></th> -->
				<th>#</th>
				<th><?php echo set_value('lsMasterCode'); ?></th>
				<th><?php echo set_value('customer'); ?></th>
				<th><?php echo set_value('lawsuitLawyer'); ?></th>
				<th><?php echo set_value('lawsuits_Type'); ?></th>
				<th><?php echo set_value('state'); ?></th>
				<th><?php echo set_value('stage'); ?></th>
				<th><?php echo set_value('noOfStages'); ?></th>
				<th><?php echo set_value('paidStatus'); ?></th>
				<th><?php echo set_value('totalAmount'); ?></th>
				<th><?php echo set_value('paidAmount'); ?></th>
				<th><?php echo set_value('dueAmount'); ?></th>
				<th><?php echo set_value('paymentDate'); ?></th>
				<th><?php echo set_value('paymentStatus'); ?></th>
			</tr>
		</thead>
		<tbody id='setData'>

			<?php

			////$qry="CALL sp_getLawsuitDetails('".$language."',".$_SESSION['customerId'].",".$_POST['type'].",".$_POST['state'].",".$_POST['stage'].") ";
			$qry_payment = "CALL sp_getLawsuitDetails('" . $language . "'," . $_SESSION['customerId'] . ",-1,-1,-1)";
			$stmt_payment = $dbo->prepare($qry_payment);
			//$stmt_payment->bindParam(":to_date",$to_date,PDO::PARAM_STR);
			if ($stmt_payment->execute()) {
				$result_payment = $stmt_payment->fetchAll(PDO::FETCH_ASSOC);
				$stmt_payment->closeCursor();
			} else {
				$errorInfo = $stmt_payment->errorInfo();
				exit($json = $errorInfo[2]);
			}

			///exit;
			$serial = 1;
			$typeName = "lsTypeName_" . $language;
			$stateName = "lsStateName_" . $language;
			$stagesName = "lsStagesName_" . $language;
			$payment = set_value('payment');
			$expense = set_value('expense');
			$history = set_value('history');
			$checkButton = '<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
			$crossButton = '<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
			////<td class="d-flex align-items-center">
			////print_r($result);

			$NotPaid = set_value('NotPaid');
			$FullPaid = set_value('FullPaid');
			$OutStanding = set_value('OutStanding');
			$paymentStatusVal = "";

			include('get4setCurrency.php');
			// print_r($result_payment);
			foreach ($result_payment as $i => $value) {
				if ($value['paymentStatus'] == 'FullPaid') {
					$paymentStatus = "bg-success-light";
					$paymentStatusVal = $FullPaid;
				} else if ($value['paymentStatus'] == 'NotPaid') {
					$paymentStatus = "bg-danger-light";
					$paymentStatusVal = $NotPaid;
				} else if ($value['paymentStatus'] == 'OutStanding') {
					$paymentStatus = "bg-warning-light";
					$paymentStatusVal = $OutStanding;
				} else
					$paymentStatus = "";

				$qry_payment_details = "SELECT lsPaymentId, m.`ls_code`, s.lsStagesName_$language as lsStagesName , d.`lawsuitId`,
				paymentDate, pm.name_$language as paymentMode, amount, invoiceNumber, remarks
				FROM tbl_lawsuit_payment l 
				LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=l.`lsMasterId`
				LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=l.`lsStageId`
				LEFT JOIN `tbl_lawsuit_details` d ON d.`lsMasterId`=m.`lsMasterId`
				LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=l.`paymentMode`
				WHERE l.`isActive`=1 AND m.`isActive`=1
				AND d.`lsMasterId`=:lsMasterId 
				ORDER BY l.`paymentDate` DESC
				LIMIT 1";

				$stmt_payment_details = $dbo->prepare($qry_payment_details);
				$stmt_payment_details->bindParam(":lsMasterId", $value['lsMasterId'], PDO::PARAM_INT);
				if ($stmt_payment_details->execute()) {
					$result_payment_details = $stmt_payment_details->fetchAll(PDO::FETCH_ASSOC);
					if (count($result_payment_details) > 0) {
						if (new DateTime($_POST['from']) <= new DateTime($result_payment_details[0]['paymentDate']) && new DateTime($result_payment_details[0]['paymentDate']) <= new DateTime($_POST['to'])) {
							if (isset($_POST['client'])) {
								if ($_POST['client'] == '' || $_POST['client'] == $value['customerName']) {
			?>
									<tr>
										<td> <?php echo $serial; ?> </td>
										<td> <?php /* <a href="javascript:viewLSDetails(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);" ><?php echo $value['ls_code']; ?>  </a> */ echo $value['ls_code']; ?> </td>
										<td> <?php echo $value['customerName']; ?> </td>
										<td> <?php echo $value['empName_' . $language]; ?> </td>
										<td><?php echo $value[$typeName]; ?></td>
										<!--<td style="background-color:<?php ///echo $value['lsColor']; 
																		?>"><?php ///echo $value[$stateName]; 
																			?></td> -->
										<td style="color:<?php echo $value['lsColor']; ?>"><?php echo $value[$stateName]; ?></td>
										<td><?php echo $value[$stagesName]; ?></td>
										<td><?php echo $value['noofStages']; ?></td>
										<td><?php if ($value['isPaid']) echo $checkButton;
											else echo $crossButton; ?></td>
										<td><?php echo setAmountDecimal($value['totalAmount']); ?></td>
										<td><?php echo setAmountDecimal($value['paymentAmount']); ?></td>
										<td><?php echo setAmountDecimal($value['dueAmount']); ?></td>
										<td><?php echo $result_payment_details[0]['paymentDate']; ?></td>
										<td> <span class="badge badge-pill <?php echo $paymentStatus; ?>"><?php echo $paymentStatusVal; ?></span> </td>

									</tr>
								<?php
									$serial++;
								}
							} else {
								?>
								<tr>
									<td> <?php echo $serial; ?> </td>
									<td> <?php /* <a href="javascript:viewLSDetails(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);" ><?php echo $value['ls_code']; ?>  </a> */ echo $value['ls_code']; ?> </td>
									<td> <?php echo $value['customerName']; ?> </td>
									<td> <?php echo $value['empName_' . $language]; ?> </td>
									<td><?php echo $value[$typeName]; ?></td>
									<!--<td style="background-color:<?php ///echo $value['lsColor']; 
																	?>"><?php ///echo $value[$stateName]; 
																		?></td> -->
									<td style="color:<?php echo $value['lsColor']; ?>"><?php echo $value[$stateName]; ?></td>
									<td><?php echo $value[$stagesName]; ?></td>
									<td><?php echo $value['noofStages']; ?></td>
									<td><?php if ($value['isPaid']) echo $checkButton;
										else echo $crossButton; ?></td>
									<td><?php echo setAmountDecimal($value['totalAmount']); ?></td>
									<td><?php echo setAmountDecimal($value['paymentAmount']); ?></td>
									<td><?php echo setAmountDecimal($value['dueAmount']); ?></td>
									<td><?php echo $result_payment_details[0]['paymentDate']; ?></td>
									<td> <span class="badge badge-pill <?php echo $paymentStatus; ?>"><?php echo $paymentStatusVal; ?></span> </td>

								</tr>
							<?php
								$serial++;
							}
							?>

			<?php
						}
					}
					$stmt_payment_details->closeCursor();
				} else {
					$errorInfo = $stmt_payment_details->errorInfo();
					exit($json = $errorInfo[2]);
				}
			}
			?>
		</tbody>
	</table>
	<script>
		$('#setData_payment .datatable').DataTable({
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