<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	if(isset($_POST['getPaymentData'],$_POST['lsMId']))
	{
		$qry="CALL sp_get_LSPaymentDetailsNew(:lsMasterId,0)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$resultPayment = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode(['status'=>true, 'data'=>$resultPayment],JSON_INVALID_UTF8_SUBSTITUTE);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	
	}
	
	if(isset($_POST['getContractData'],$_POST['lsDId']))
	{
		$qry="SELECT d.`amountContract`, d.`taxValue`, d.`totalContractAmount` FROM `tbl_lawsuit_details` d WHERE d.`isActive`=1 AND d.`lsDetailsId`=:lsDetailsId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$resultPayment = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode(['status'=>true, 'data'=>$resultPayment],JSON_INVALID_UTF8_SUBSTITUTE);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	else if(isset($_POST['lsMId']) && count($_POST) === 1)
	{
		$qry="SELECT lsPaymentId, m.`ls_code`, s.lsStagesName_$language as lsStagesName , d.`lawsuitId`,
			DATE_FORMAT(paymentDate,'%d-%b-%y') paymentDate, pm.name_$language as paymentMode, amount, invoiceNumber, remarks,
			(CASE WHEN IFNULL(m.`isPaidAll`,0)=0 THEN 'Current Stage' ELSE 'Full Stages' END) 
			 paymentStatus_en,
			(CASE WHEN IFNULL(m.`isPaidAll`,0)=0 THEN 'مرحله واحده' ELSE 'مدفوع جميع المراحل' END) 
			 paymentStatus_ar 
			FROM tbl_lawsuit_payment l 
			LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=l.`lsMasterId`
			LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=l.`lsStageId`
			LEFT JOIN `tbl_lawsuit_details` d ON d.`lsMasterId`=m.`lsMasterId`
			LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=l.`paymentMode`
			WHERE l.isActive=1 AND m.`isActive`=1
			AND d.`lsMasterId`=:lsMasterId GROUP BY l.`lsPaymentId` ";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_INT);
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
		$countryName="countryName_".$language;
		////print_r($result);
		$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
		$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	
		foreach($result as $value)
		{ ?>
		<tr>
			<td class="d-flex align-items-center">
				<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['lsPaymentId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
				<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['lsPaymentId'].",'payment'"; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
				&nbsp; &nbsp; 
				<a href="#" class="btn-action-icon" onclick="printPaymentReceipt(<?php echo $value['lsPaymentId']; ?>);"><span><i class="fa fa-print"></i></span></a>

			</td>
			
			<td> <?php echo $serial; ?> </td>
			<td><?php echo $value['ls_code']; ?></td>
			<td><?php echo $value['lsStagesName']; ?></td>
			<td><?php echo $value['lawsuitId']; ?></td>
			<td><?php echo $value['invoiceNumber']; ?></td>
			<td><?php echo $value['paymentDate']; ?></td>
			<td><?php echo $value['paymentMode']; ?></td>
			<td><?php echo setAmountDecimal($value['amount']); ?></td>
			<td><?php echo $value['remarks']; ?></td>
			<td><?php echo $value['paymentStatus_'.$language]; ?></td>
			
		</tr>
		
		<?php 
			$serial++;
		}
	}
	
	else if(isset($_POST['lsMId'],$_POST['contractData']) && count($_POST) === 2)
	{
		$qry="SELECT c.`lsContractId`, c.`lsMasterId`,m.`ls_code`,`lsStageId`, s.lsStagesName_$language as lsStagesName,`amount`, `taxValue`, taxAmount, `totalAmount`, `contractEn`, `contractAr`, `contractFilePath`, c.`isActive` FROM `tbl_lawsuit_contract` c 
			LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=c.`lsMasterId`
			LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=c.`lsStageId`
			WHERE c.`isActive`=1 AND c.`lsMasterId`=:lsMasterId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_INT);
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
		$countryName="countryName_".$language;
		////print_r($result);
		$noImage='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
		foreach($result as $value)
		{ 
			if(empty($value['contractFilePath']))
				$contractFile=$noImage;
			else 
				$contractFile='<a href="'.$value['contractFilePath'].'" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>';
			?>
		<tr>
			<td class="d-flex align-items-center">
				<a href="#" class="btn-action-icon me-2" onclick="editContract(<?php echo $value['lsContractId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
				<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['lsContractId'].",'contract'"; ?>);"><span><i class="fe fe-trash-2"></i></span></a>&nbsp;&nbsp;
				<a href="#" class="btn-action-icon" onclick="printContracts(<?php echo $value['lsContractId'].",'".$value['ls_code']."'"; ?>);"><span><i class="fe fe-download"></i></span></a>
			</td>
			
			<td> <?php echo $serial; ?> </td>
			<td><?php echo $value['lsStagesName']; ?></td>
			<td><?php echo setAmountDecimal($value['amount']); ?></td>
			<td><?php echo setAmountDecimal($value['taxAmount']); ?></td>
			<td><?php echo setAmountDecimal($value['totalAmount']); ?></td>
			<td><?php echo $contractFile; ?></td>
		</tr>
		
		<?php 
			$serial++;
		}
	}

	
?>
