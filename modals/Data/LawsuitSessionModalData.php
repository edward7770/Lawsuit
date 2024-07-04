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
	
	$qry="SELECT lsSessionId,lsDetailsId,sessionName,sessionDetails,sessionDate,sessionTime,sessionPlace
	FROM tbl_lawsuit_sessions WHERE isActive=1 AND lsDetailsId=:lsDetailsId";	
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
						<a class="text-dark collapsed" data-bs-toggle="collapse" href="#headingSession<?php echo $row['lsSessionId'] ?>" aria-expanded="false">
							
							<i class="far fa-clock mr-1 text-danger"></i>
							<?php echo $index+1; echo "-".$row['sessionName']."/".displayDate($row['sessionDate']);  ?>
						</a>
					</h5>
					<div id="headingSession<?php echo $row['lsSessionId']; ?>" class="collapse" aria-labelledby="heading<?php echo $row['lsSessionId'] ?>" data-bs-parent="#accordion">
						<div class="card-body">
							
							<div class="table-responsive">
								<table class="table table-center table-hover datatable">
									<thead class="thead-light">
										<tr>
											<th><?php echo set_value('action'); ?></th>
											<th><?php echo set_value('dateSession'); ?></th>
											<!--<th><?php ////echo set_value('dateHijri'); ?></th> -->
											<th><?php echo set_value('timeSession'); ?></th>
											<th><?php echo set_value('placeSession'); ?></th>
											
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="d-flex align-items-center">
											<?php if($_SESSION['customerId']<=0)
											{ ?>
												<a href="#" class="btn-action-icon me-2" onclick="editSession(<?php echo $row['lsSessionId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
												<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $row['lsSessionId']; ?>,'session');"><span><i class="fe fe-trash-2"></i></span></a>&nbsp;&nbsp;
												<a href="#" class="btn-action-icon me-2" onclick="email(<?php echo $row['lsSessionId']; ?>);"><span><i class="fa fa-envelope"></i></span></a>
											<?php
											} ?>
												<a href="#" class="btn-action-icon me-2" data-bs-toggle="modal" data-bs-target="#msg_modal<?php echo $row['lsSessionId']; ?>" ><span><i class="fe fe-eye"></i></span></a>
											</td>
											<td> <?php echo displayDate($row['sessionDate']); ?> </td>
											<!--<td> <?php ///echo $row['sessionHijriDate']; ?> </td> -->
											<td> <?php echo $row['sessionTime']; ?> </td>
											<td> <?php echo $row['sessionPlace']; ?> </td>
											
										</tr>
									</tbody>
								</table>
								<!-- Message Items Modal -->
								<div class="modal custom-modal fade" id="msg_modal<?php echo $row['lsSessionId']; ?>" role="dialog">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="standard-modalLabel"><?php echo set_value('sessionDetails'); ?></h4>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<?php echo $row['sessionDetails']; ?>
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
   