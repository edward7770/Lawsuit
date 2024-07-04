<div class="card-header">
	<div class="d-flex justify-content-between align-items-center">
		<h5 class="card-title"><?php echo set_value('lawsuitSessionTask'); ?></h5>
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
										<th><?php echo set_value('taskstartDate'); ?></th>
										<th><?php echo set_value('taskDueDate'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if(isset($txtSearch) && !empty($txtSearch))
									{
										$custId="-1";
										$qry="CALL sp_getSessionsTask_Search(:lang,:custId,:txtSearch)";
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
										foreach($resultSearch as $row)
										{ 
											$serial++;
										?>
										<tr>
											<td> <?php echo $serial; ?> </td>
											<td><a href="javascript:showLSSearch(<?php echo $row['lsMasterId'].",".$row['lsDetailsId'].",".$row['lawsuitTaskId'].",'task'"; ?>);" class="btn-action-icon me-2"><span><i class="fe fe-eye"></i></span></a> </td>
											<td><?php echo $row['taskName']; ?></td>
											<td><?php echo $row['startDate']; ?></td>
											<td><?php echo $row['dueDate']; ?></td>
										</tr>
										<?php 
											
										}
									}
						?>
					</tbody>
					</table>
					</div>
					<input type='hidden' id='lsCountLSTask' value="<?php if(isset($serial)) echo $serial; else echo "0"; ?>" >
					
				</div>
			</div>
		</div>
	</div>
</div>


