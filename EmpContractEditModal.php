<?php 
	session_start();
	include_once('config/conn.php');
	
	$language=$_SESSION['lang'];
	$pageName ='EmpContract';
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
		$qry="SELECT c.`empContractId`,c.`empId`, c.`contractDate`, c.`contractName`, c.`basicSalary`, c.`contractDateFrom`, c.`contractDateTo` 
		FROM `tbl_emp_contracts` c 
		WHERE c.`isActive`=1 AND c.`empContractId`=:id";
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
			$empContractId=$row['empContractId'];
			$empId=$row['empId'];
			$contractDate=$row['contractDate'];
			$contractName=$row['contractName'];
			$basicSalary=$row['basicSalary'];
			$contractDateFrom=$row['contractDateFrom'];
			$contractDateTo=$row['contractDateTo'];
		}
	}
	
?>
<div class="row">
	<div class="col-md-3">
		<div class="mb-4">
			<div class="form-group ">
				<label for="contractDate" class="form-label"><?php echo set_value('ContractDate'); ?> <span class="text-danger"> * </span></label>
				<input type="date" class="form-control" id="contractDate" required onkeydown="return false;" value="<?php if(isset($contractDate)) echo $contractDate; ?>" >
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="form-group">
			<label for="emp" class="form-label"><?php echo set_value('employeeName'); ?></label>
			<select class="form-control js-example-basic-single form-small select" id='empId' required <?php if(!empty($_POST['id'])) echo "disabled"; ?> >
				<option value=""><?php echo set_value("select"); ?></option>
				<?php echo include_once('dropdown_employee.php'); ?>
			</select>
		</div>

	</div>
	
	<div class="col-md-4">
		<div class="form-group">
			<label for="contractName" class="form-label"><?php echo set_value('contractName'); ?></label>
			<input type="text" class="form-control" id="contractName" placeholder="<?php echo set_value('contractName'); ?>" value="<?php if(isset($contractName)) echo $contractName; ?>">
		</div>
	</div>
	
</div>

<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label for="basicSalary" class="form-label"> <?php echo set_value('basicSalary'); ?></label>
			<input type="number" class="form-control" id="basicSalary" placeholder="<?php echo set_value('basicSalary'); ?>" value="<?php if(isset($basicSalary)) echo $basicSalary; ?>">
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="mb-4">
			<div class="form-group ">
				<label for="contractDateFrom" class="form-label"><?php echo set_value('contractDateFrom'); ?> <span class="text-danger"> * </span></label>
				<input type="date" class="form-control" id="contractDateFrom" required onkeydown="return false;" value="<?php if(isset($contractDateFrom)) echo $contractDateFrom; ?>">
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="mb-4">
			<div class="form-group ">
				<label for="contractDateTo" class="form-label"><?php echo set_value('contractDateTo'); ?><span class="text-danger"> * </span></label>
				<input type="date" class="form-control" id="contractDateTo" required onkeydown="return false;" value="<?php if(isset($contractDateTo)) echo $contractDateTo; ?>">
			</div>
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
									if(empty($_POST['id']))
										$qry="SELECT a.`allowDeductId`, a.allowDeductName_$language as name FROM `tbl_allow_deduct` a WHERE a.`type`=1"; 
									else if(!empty($_POST['id']))
									$qry="SELECT d.`empContractDetailsId`,a.`allowDeductId`, a.allowDeductName_$language as name, d.`amount` FROM `tbl_allow_deduct` a 
										LEFT JOIN tbl_emp_contract_details d ON d.`allowDeductId`=a.`allowDeductId` AND d.`empContractId`=:id
									WHERE a.`type`=1"; 
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
										<td id="<?php if(empty($row['empContractDetailsId'])) echo $row['allowDeductId']; else echo $row['allowDeductId'].",1";; ?>" ><input type="number" class="form-control" id="txtAllAmount<?php echo $si; ?>" value="<?php if(isset($row['amount'])) echo $row['amount']; ?>"></td>
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
									if(empty($_POST['id']))
										$qry="SELECT a.`allowDeductId`, a.allowDeductName_$language as name FROM `tbl_allow_deduct` a WHERE a.`type`=0"; 
									else if(!empty($_POST['id']))
									$qry="SELECT d.`empContractDetailsId`,a.`allowDeductId`, a.allowDeductName_$language as name, d.`amount` FROM `tbl_allow_deduct` a 
										LEFT JOIN tbl_emp_contract_details d ON d.`allowDeductId`=a.`allowDeductId` AND d.`empContractId`=:id
									WHERE a.`type`=0"; 
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
										<td id="<?php if(empty($row['empContractDetailsId'])) echo $row['allowDeductId']; else echo $row['allowDeductId'].",1"; ?>"><input type="number" class="form-control" id="txtdedAmount<?php echo $si; ?>" value="<?php if(isset($row['amount'])) echo $row['amount']; ?>"></td>
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
<input type='hidden' id='id' value="<?php if(isset($empContractId)) echo $empContractId; else echo 0 ?>" />
<?php if(isset($empId))
{
	echo "<script>$('#empId').val($empId).change(); </script>";
}
	
	
