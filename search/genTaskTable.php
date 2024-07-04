<?php 
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
?>

<div class="card-header">
	<div class="d-flex justify-content-between align-items-center">
		<h5 class="card-title"><?php echo set_value('generalTask'); ?></h5>
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
										<th><?php echo set_value('taskName'); ?></th>
										<th><?php echo set_value('taskAssignedTo'); ?></th>
										<th><?php echo set_value('taskstartDate'); ?></th>
										<th><?php echo set_value('taskDueDate'); ?></th>
										<th><?php echo set_value('taskPriority'); ?></th>
										<th><?php echo set_value('taskStatus'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if(isset($txtSearch) && !empty($txtSearch))
									{
										$custId="-1";
										///$qry="CALL sp_getLawsuitDetails_Search('".$language."',-1,'".$txtSearch."') ";
										$qry="CALL sp_getGeneralTask_Search(:lang,:custId,:txtSearch)";
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
										$serial=0;
										foreach($resultSearch as $i=> $value)
										{
											$serial++;
											?>
										<tr>
											<td> <?php echo $serial; ?> </td>
											<td><a href="javascript:showSearch(<?php echo $value['taskId'].",'task.php'"; ?>);" class="btn-action-icon me-2"><span><i class="fe fe-eye"></i></span></a> </td>
											<td><?php echo $value['taskName']; ?></td>
											<td><?php echo $value['empName']; ?></td>
											<td><?php echo $value['startDate']; ?></td>
											<td><?php echo $value['dueDate']; ?></td>
											<td><?php echo $value['taskpriorityName']; ?></td>
											<td><?php echo $value['taskStatusName']; ?></td>
										</tr>
										<?php 
											
										}
									}
						?>
					</tbody>
					</table>
					</div>
					<input type='hidden' id='lsCountGenTask' value="<?php if(isset($serial)) echo $serial; else echo "0"; ?>" >
				</div>
			</div>
		</div>
	</div>
</div>


