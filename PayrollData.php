<?php 
	session_start();
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	
	
	if(isset($_POST['type']) && $_POST['type']=="Remaining")
	{
		$qry="call sp_get_remaining_emp_payroll(:month,:year)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":month",$_POST['month'],PDO::PARAM_INT);
		$stmt->bindParam(":year",$_POST['year'],PDO::PARAM_INT);
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
		////$countryName="countryName_".$language;
		////print_r($result);
		///$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
		///$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
		foreach($result as $index=> $value)
		{ ?>
		<tr>
			<td><label class="custom_check">
				<input class="emp" type="checkbox" id="<?php echo $value['empId']; ?>" value="<?php echo $value['empContractId']; ?>">
				<span class="checkmark"></span> 
			</label>
			</td>
			<td> <?php echo $serial; ?> </td>
			<td><?php echo $value['empName_'.$language]; ?></td>
			<td><?php echo $value['categoryName']; ?></td>
		</tr>
		
		<?php 
			$serial++;
		}
	}
	else if(isset($_POST['type']) && $_POST['type']=="Generated")
	{
		$qry="call sp_get_generatedPayroll(:month,:year)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":month",$_POST['month'],PDO::PARAM_INT);
		$stmt->bindParam(":year",$_POST['year'],PDO::PARAM_INT);
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
		foreach($result as $index=> $value)
		{ 
			if(empty($value['isPost']))
				$checked="";
			else $checked="checked";
			?>
		<tr>
			<td class="d-flex align-items-center">
				<?php /* <a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['payrollId']; ?>);"><span><i class="fe fe-edit"></i></span></a> */ ?>
				<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['payrollId'].",".$value['payrollId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
			</td>
			<td> <?php echo $serial; ?> </td>
			<?php /*
			<td><label class="custom_check">
				<input class="empPayroll" type="checkbox" id="<?php echo $value['empId']; ?>" value="<?php echo $value['payrollId']; ?>" <?php echo $checked; ?>>
				<span class="checkmark"></span> 
			</label>
			</td> */ ?>
			
			<td><?php echo $value['empName_'.$language]; ?></td>
			<td><?php echo $value['categoryName']; ?></td>
			<td><?php echo setAmountDecimal($value['basicSalary']); ?></td>
			<td><?php echo setAmountDecimal($value['allow']); ?></td>
			<td><?php echo setAmountDecimal($value['basicSalary']+$value['allow']); ?></td>
			<td><?php echo setAmountDecimal($value['deduct']); ?></td>
			<td><?php echo setAmountDecimal(($value['basicSalary']+$value['allow'])-$value['deduct']); ?></td>
		</tr>
		
		<?php 
			$serial++;
		}
	}
?>