<?php 
include_once('config/conn.php');

if(isset($_POST['getData']))
{
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	
	include_once('languageActions.php');
	
	$qry="CALL sp_getLawsuitDetails('".$language."',".$_SESSION['customerId'].",-1,-1,-1)";
	$stmt=$dbo->prepare($qry);
	////$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	///exit;
	$serial=1;
	$typeName="lsTypeName_".$language;
	$stateName="lsStateName_".$language;
	$stagesName="lsStagesName_".$language;
	$payment=set_value('payment');
	$expense=set_value('expense');
	$history=set_value('history');
	$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	////<td class="d-flex align-items-center">
	////print_r($result);
	
	$NotPaid=set_value('NotPaid');
	$FullPaid=set_value('FullPaid');
	$OutStanding=set_value('OutStanding');
	$paymentStatusVal="";
	
	include('get4setCurrency.php');
	
	foreach($result as $value)
	{
		if($value['paymentStatus']=='FullPaid')
		{
			$paymentStatus="bg-success-light";
			$paymentStatusVal=$FullPaid;
		}
		else if($value['paymentStatus']=='NotPaid')
		{
			$paymentStatus="bg-danger-light";
			$paymentStatusVal=$NotPaid;
		}
		else if($value['paymentStatus']=='OutStanding')
		{
			$paymentStatus="bg-warning-light";
			$paymentStatusVal=$OutStanding;
		}
		else 
			$paymentStatus="";
		
		
	?>
	<tr>
		<td><?php /*
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
			<a href="javascript:viewLSDetailsPayment(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);" class="btn-action-icon me-2"><span><?php echo $currencyText; ?></span></a>
			
			
		</td>
		<td> <?php echo $serial; ?> </td>
		
		<td> <?php /* <a href="javascript:viewLSDetails(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);" ><?php echo $value['ls_code']; ?>  </a> */ echo $value['ls_code']; ?> </td>
		<td> <?php echo $value['customerName']; ?> </td>
		<td> <?php echo $value['empName_'.$language]; ?> </td>
		<td><?php echo $value[$typeName]; ?></td>
		<!--<td style="background-color:<?php ///echo $value['lsColor']; ?>"><?php ///echo $value[$stateName]; ?></td> -->
		<td style="color:<?php echo $value['lsColor']; ?>"><?php echo $value[$stateName]; ?></td>
		<td><?php echo $value[$stagesName]; ?></td>
		<td><?php echo $value['noofStages']; ?></td>
		<td><?php if($value['isPaid']) echo $checkButton; else echo $crossButton; ?></td>
		<td><?php echo setAmountDecimal($value['totalAmount']); ?></td>
		<td><?php echo setAmountDecimal($value['paymentAmount']); ?></td>
		<td><?php echo setAmountDecimal($value['dueAmount']); ?></td>
		<td> <span class="badge badge-pill <?php echo $paymentStatus; ?>"><?php echo $paymentStatusVal; ?></span> </td>
		
	</tr>
	
	<?php 
		$serial++;
	}
}
else if(isset($_POST['getPayment']))
{
	$stmt=$dbo->prepare("CALL sp_getPaymentDetails()");
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
