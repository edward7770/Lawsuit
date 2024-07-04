<?php 
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
?>

<div class="card-header">
	<div class="d-flex justify-content-between align-items-center">
		<h5 class="card-title"><?php echo set_value('employees'); ?></h5>
	</div>
</div>
<div class="card-body">
	
	<div class="row">
		<div class="col-sm-12">
			<div class="card-table">
				<div class="card-body">
					<div class="table-responsive">
						<table id="lawsuitTable" class="table table-center table-hover datatable">
							<thead class="thead-light">
								<tr>
										<th>#</th>
										<th><?php echo set_value('action'); ?></th>
										<th><?php echo set_value('name_ar'); ?></th>
										<th><?php echo set_value('name_en'); ?></th>
										<th><?php echo set_value('category'); ?></th>
										<th><?php echo set_value('empNo'); ?></th>
										<th><?php echo set_value('joinDate'); ?></th>
										<th><?php echo set_value('telephone_no'); ?></th>
										<th><?php echo set_value('mobile_no'); ?></th>
										<th><?php echo set_value('dob'); ?></th>
										<th><?php echo set_value('gender'); ?></th>
										<th><?php echo set_value('nationality'); ?></th>
										<th><?php echo set_value('religion'); ?></th>
										<th><?php echo set_value('idNo'); ?></th>
										<th><?php echo set_value('issueDate'); ?></th>
										<th><?php echo set_value('expiryDate'); ?></th>
										<th><?php echo set_value('passportNo'); ?></th>
										<th><?php echo set_value('issueDate'); ?></th>
										<th><?php echo set_value('expiryDate'); ?></th>
										<th><?php echo set_value('email'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if(isset($txtSearch) && !empty($txtSearch))
									{
										$custId="-1";
										///$qry="CALL sp_getLawsuitDetails_Search('".$language."',-1,'".$txtSearch."') ";
										$qry="CALL sp_getEmployees_Search(:lang,:custId,:txtSearch)";
										$stmt=$dbo->prepare($qry);
										$stmt->bindParam(":lang",$language,PDO::PARAM_STR);
										$stmt->bindParam(":custId",$custId,PDO::PARAM_STR);
										$stmt->bindParam(":txtSearch",$txtSearch,PDO::PARAM_STR);
										if($stmt->execute())
										{
											$resultSearch = $stmt->fetchAll(PDO::FETCH_ASSOC);
										}
										else 
										{
											$errorInfo = $stmt->errorInfo();
											exit($json =$errorInfo[2]);
										}
										///exit;
										$serial=1;
										foreach($resultSearch as $i=> $value)
										{ 
										?>
										<tr>
											<td> <?php echo $serial; ?> </td>
											<td><a href="javascript:showSearch(<?php echo $value['empId'].",'employees.php'"; ?>);" class="btn-action-icon me-2"><span><i class="fe fe-eye"></i></span></a> </td>
											<td><?php echo $value['empName_ar']; ?></td>
											<td><?php echo $value['empName_en']; ?></td>
											<td><?php echo $value['categoryName']; ?></td>
											<td><?php echo $value['empNo']; ?></td>
											<td><?php echo $value['joinDate']; ?></td>
											<td><?php echo $value['phoneNo']; ?></td>
											<td><?php echo $value['mobileNo']; ?></td>
											<td><?php echo $value['dob']; ?></td>
											<td><?php echo $value['gender']; ?></td>
											<td><?php echo $value['countryName']; ?></td>
											<td><?php echo $value['religion']; ?></td>
											<td><?php echo $value['idNo']; ?></td>
											<td><?php echo $value['issueDate']; ?></td>
											<td><?php echo $value['expiryDate']; ?></td>
											<td><?php echo $value['passportNo']; ?></td>
											<td><?php echo $value['expiryDatePassNo']; ?></td>
											<td><?php echo $value['expiryDatePassNo']; ?></td>
											<td><?php echo $value['email']; ?></td>
										</tr>
										<?php 
											$serial++;
										}
										$serial--;
									}
						?>
					</tbody>
					</table>
					</div>
					<input type='hidden' id='lsCountEmp' value="<?php if(isset($serial)) echo $serial; else echo "0"; ?>" >
					
				</div>
			</div>
		</div>
	</div>
</div>


