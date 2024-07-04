<?php 
	session_start();
	include_once('config/conn.php');
	
	$language=$_SESSION['lang'];
	
	$pageName ='Payroll';
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName`=:pageName"; 
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	function set_value($val)
	{
		foreach($GLOBALS['result'] as $value)
		{
			if(trim($value['phrase'])==trim($val))
			{
				return $value['VALUE'];
				break;
			}
		}
	}
	
	if(!empty($_POST['id']))
	{
		$qry="SELECT p.`payrollId`, p.`contractId`,p.`basicSalary`, p.`empId`, e.empName_$language as empName  FROM tbl_payroll p 
		LEFT JOIN tbl_employees e ON e.`empId`=p.`empId`
		WHERE p.`isActive`=1 AND p.`payrollId`=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$resultContract = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		foreach($resultContract as $row)
		{
			$payrollId=$row['payrollId'];
			$empId=$row['empId'];
			$empName=$row['empName'];
			$basicSalary=$row['basicSalary'];
		}
	}
	
?>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label for="emp" class="form-label"><?php echo set_value('employeeName'); ?></label>
			<input type="text" class="form-control" value="<?php if(isset($empName)) echo $empName; ?>" disabled>
			<input type="hidden" id="empId" value="<?php if(isset($empId)) echo $empId; ?>" >
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label for="basicSalary" class="form-label"> <?php echo set_value('basicSalary'); ?></label>
			<input type="text" class="form-control" value="<?php if(isset($basicSalary)) echo $basicSalary; ?>" disabled>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card-body">
			<ul class="nav nav-tabs nav-justified" role="tablist">
				<li class="nav-item" role="presentation"><a class="nav-link active" href="#basic-justified-tab1" data-bs-toggle="tab" aria-selected="true" role="tab"><?php echo set_value('allowance'); ?></a></li>
				<li class="nav-item" role="presentation"><a class="nav-link" href="#basic-justified-tab2" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><?php echo set_value('deduction'); ?></a></li>
				
			</ul>
			<div class="tab-content">
				<div class="tab-pane show active" id="basic-justified-tab1" role="tabpanel">
					<div class="table-responsive" >
						<table class="table table-center table-hover" id="tblAllowance">
							<thead class="thead-light">
								<tr>
									<th>#</th>
									<th><?php echo set_value('AllowanceHead'); ?></th>
									<th><?php echo set_value('amount'); ?></th>
								</tr>
							</thead>
							<tbody> 
								<?php 
									if(!empty($_POST['id']))
									$qry="SELECT d.`payrollDetailId`, a.`allowDeductId`, a.allowDeductName_$language as name, d.`amount` FROM `tbl_allow_deduct` a
									LEFT JOIN `tbl_payroll_details` d ON d.`allowDeductId`=a.`allowDeductId` AND d.`payrollId`=:id
									WHERE a.`isActive`=1 AND a.`type`=1";	
									$stmt=$dbo->prepare($qry);
									if(!empty($_POST['id']))
										$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
									if($stmt->execute())
									{
										$resultModal = $stmt->fetchAll(PDO::FETCH_ASSOC);
									}
									else 
									{
										$errorInfo = $stmt->errorInfo();
										exit($json =$errorInfo[2]);
									}
									$si=1;
									foreach($resultModal as $row)
									{ ?>
									<tr>
										<td><?php echo $si; ?></td>
										<td><?php echo $row['name']; ?></td>
										<td id="<?php if(empty($row['payrollDetailId'])) echo $row['allowDeductId']; else echo $row['allowDeductId'].",1"; ?>" ><input type="number" class="form-control" id="txtAllAmount<?php echo $si; ?>" value="<?php if(isset($row['amount'])) echo $row['amount']; ?>"></td>
									</tr>
								<?php $si++; } ?>
								
							</tbody>
						</table>
					</div>
					
					
				</div>
				
				<div class="tab-pane" id="basic-justified-tab2" role="tabpanel">
					<div class="table-responsive">
						<table class="table table-center table-hover" id="tblDeductions">
							<thead class="thead-light">
								<tr>
									<th>#</th>
									<th><?php echo set_value('deductionHead'); ?></th>
									<th><?php echo set_value('amount'); ?></th>
								</tr>
							</thead>
							<tbody> 
								<?php 
									$qry="SELECT d.`payrollDetailId`, a.`allowDeductId`, a.allowDeductName_$language as name, d.`amount` FROM `tbl_allow_deduct` a
									LEFT JOIN `tbl_payroll_details` d ON d.`allowDeductId`=a.`allowDeductId` AND d.`payrollId`=:id
									WHERE a.`isActive`=1 AND a.`type`=0";
									$stmt=$dbo->prepare($qry);
									if(!empty($_POST['id']))
										$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
									if($stmt->execute())
									{
										$resultModal = $stmt->fetchAll(PDO::FETCH_ASSOC);
									}
									else 
									{
										$errorInfo = $stmt->errorInfo();
										exit($json =$errorInfo[2]);
									}
									$si=1;
									foreach($resultModal as $row)
									{ ?>
									<tr>
										<td><?php echo $si; ?></td>
										<td><?php echo $row['name']; ?></td>
										<td id="<?php if(empty($row['payrollDetailId'])) echo $row['allowDeductId']; else echo $row['allowDeductId'].",1"; ?>"><input type="number" class="form-control" id="txtdedAmount<?php echo $si; ?>" value="<?php if(isset($row['amount'])) echo $row['amount']; ?>"></td>
									</tr>
									<?php $si++; 
									} ?>
							</tbody>
						</table>
					</div>
					
				</div>
				
			</div>
		</div>
	</div>
</div>
<input type='hidden' id='id' value="<?php if(isset($payrollId)) echo $payrollId; else echo 0; ?>" />