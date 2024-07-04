<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	
	if(isset($_POST['getPaymentData'],$_POST['id']))
	{
		////if(!$_POST['id']) $_POST['id']=0;
		$qry="CALL sp_get_ConsultationPaymentDetails(:id)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
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
	
	if(isset($_POST['getContractData'],$_POST['consultId']))
	{
		$qry="SELECT amount, tax, totalAmount FROM tbl_consultations WHERE isActive=1 AND consId=:consId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":consId",$_POST['consultId'],PDO::PARAM_INT);
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
	else if(isset($_POST['id']) && count($_POST) === 1)
	{
		$qry="SELECT consPaymentId,paymentDate, paymentMode, amount, remarks FROM tbl_consultation_payment l WHERE isActive=1 AND l.consultationId=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
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
				<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['consPaymentId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
				<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['consPaymentId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
			</td>
			<td> <?php echo $serial; ?> </td>
			<td><?php echo $value['paymentDate']; ?></td>
			<td><?php echo $value['paymentMode']; ?></td>
			<td><?php echo $value['amount']; ?></td>
			<td><?php echo $value['remarks']; ?></td>
			<td><?php ///if($value['isPaid']) echo $checkButton; else echo $crossButton; ?></td>
			
		</tr>
		
		<?php 
			$serial++;
		}
	}
	
?>
