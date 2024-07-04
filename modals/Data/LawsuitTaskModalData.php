<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('../../config/conn.php');
	$pageName ="LawsuitDetail";
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
	////print_r($result);
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
	$qry="SELECT t.lawsuitTaskId,t.taskName,t.taskDescription,e.empName_en AS empName ,t.startDate,t.dueDate,t.createdDate FROM tbl_lawsuit_task t 
		LEFT JOIN tbl_employees e ON e.empId=t.assignedToId
		WHERE t.`isActive`=1 AND t.`lsDetailsId`=:lsDetailsId";	
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":lsDetailsId",$_POST['id'],PDO::PARAM_INT);
	if($stmt->execute())
	{
		$resultSessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	
	foreach($resultSessions as $index=> $row)
	{
		
	?>
	<div class="row primary" id="showHide">
		<div class="col-md-12">
			<div id="accordion" class="custom-faq">
				<div class="card mb-1">
				
					<h6 class="accordion-faq m-0">
						<a class="text-dark collapsed" data-bs-toggle="collapse" href="#headingTask<?php echo $row['lawsuitTaskId'] ?>" aria-expanded="false">
							
							<i class="far fa-clock mr-1 text-danger"></i>
							<?php echo $index+1; echo "-".$row['taskName']."/".displayDate($row['startDate']); ?>
						</a>
					</h5>
					<div id="headingTask<?php echo $row['lawsuitTaskId']; ?>" class="collapse" aria-labelledby="heading<?php echo $row['lawsuitTaskId'] ?>" data-bs-parent="#accordion">
						<div class="card-body">
							
							<div class="table-responsive">
								<table class="table table-center table-hover datatable">
									<thead class="thead-light">
										<tr>
											<th><?php echo set_value('action'); ?></th>
											<th><?php echo set_value('taskName'); ?></th>
											<th><?php echo set_value('taskAssignedTo'); ?></th>
											<th><?php echo set_value('taskstartDate'); ?></th>
											<th><?php echo set_value('taskDueDate'); ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="d-flex align-items-center">
											<?php if($_SESSION['customerId']<=0)
											{ ?>
												<a href="#" class="btn-action-icon me-2" onclick="editTask(<?php echo $row['lawsuitTaskId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
												<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $row['lawsuitTaskId']; ?>,'task');"><span><i class="fe fe-trash-2"></i></span></a>&nbsp;&nbsp;
												<?php /* <a href="#" class="btn-action-icon me-2" onclick="email(<?php echo $row['lawsuitTaskId']; ?>);"><span><i class="fa fa-envelope"></i></span></a> */ ?>
											<?php
											} ?>
												<a href="#" class="btn-action-icon me-2" data-bs-toggle="modal" data-bs-target="#msg_modal<?php echo $row['lawsuitTaskId']; ?>" ><span><i class="fe fe-eye"></i></span></a>
											</td>
											<td><?php echo $row['taskName']; ?></td>
											<td><?php echo $row['empName']; ?></td>
											<td><?php echo displayDate($row['startDate']); ?></td>
											<td><?php echo $row['dueDate']; ?></td>
										</tr>
									</tbody>
								</table>
								<!-- Message Items Modal -->
								<div class="modal custom-modal fade" id="msg_modal<?php echo $row['lawsuitTaskId']; ?>" role="dialog">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="standard-modalLabel"><?php echo set_value('taskDescription'); ?></h4>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<?php echo $row['taskDescription']; ?>
											</div>
										</div>
									</div>
								</div>
								<!-- /Message Items Modal -->
								
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div> 
	</div> 
	<?php } ?>
   