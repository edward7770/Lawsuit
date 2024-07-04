<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	if(isset($_POST['getExpenseData'],$_POST['lsDId']))
	{
		$qry="CALL sp_get_LSExpenseDetails(:lsDetailsId)";
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
	else if(isset($_POST['lsDId']) && count($_POST) === 1)
	{
		$qry="SELECT e.lsExpenseId, c.expCatName_$language AS expCatName, s.subExpCatName_$language AS  subExpCatName, e.expenseDate, e.expenseMode, e.amount, e.remarks
			FROM tbl_lawsuit_expense e 
			LEFT JOIN tbl_expense_category c ON e.expCatId=c.expCatId
			LEFT JOIN tbl_expense_subcategory s ON e.subExpCatId=s.subExpCatId
			WHERE e.isActive=1 AND e.`lsDetailsId`=:lsDetailsId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
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
		////$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
		////$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	
		foreach($result as $value)
		{ ?>
		<tr>
			<td class="d-flex align-items-center">
				<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['lsExpenseId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
				<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['lsExpenseId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
			</td>
			<td> <?php echo $serial; ?> </td>
			<td><?php echo $value['expCatName']; ?></td>
			<td><?php echo $value['subExpCatName']; ?></td>
			<td><?php echo $value['expenseDate']; ?></td>
			<td><?php echo $value['expenseMode']; ?></td>
			<td><?php echo $value['amount']; ?></td>
			<td><?php echo $value['remarks']; ?></td>
			<td><?php ///if($value['isPaid']) echo $checkButton; else echo $crossButton; ?></td>
			
		</tr>
		
		<?php 
			$serial++;
		}
	}
	
?>
