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
	
	$qry="SELECT lsSessionId,lsDetailsId,sessionName,sessionDate,sessionHijriDate,sessionTime,sessionPlace,sessionDetails
	FROM tbl_lawsuit_sessions WHERE isActive=1 AND lsDetailsId=:lsDetailsId";	
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":lsDetailsId",$_POST['id'],PDO::PARAM_STR);
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
	<div class="row primary">
		<div class="col-md-12">
			<div id="accordion" class="custom-faq">
				<div class="card mb-1">
				
					<h6 class="accordion-faq m-0">
						<a class="text-dark collapsed" data-bs-toggle="collapse" href="#headingSession<?php echo $row['lsSessionId'] ?>" aria-expanded="false">
							
							<i class="far fa-clock mr-1 text-danger"></i>
							<?php echo $index+1; echo "-".$row['sessionName']."/".$row['sessionHijriDate'];  ?>
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
											<th><?php echo set_value('dateHijri'); ?></th>
											<th><?php echo set_value('timeSession'); ?></th>
											<th><?php echo set_value('placeSession'); ?></th>
											<th><?php echo set_value('sessionDetails'); ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="d-flex align-items-center">
												<a href="#" class="btn-action-icon me-2" onclick="editSession(<?php echo $row['lsSessionId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
												<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $row['lsSessionId']; ?>,'session');"><span><i class="fe fe-trash-2"></i></span></a>
											</td>
											<td> <?php echo $row['sessionDate']; ?> </td>
											<td> <?php echo $row['sessionHijriDate']; ?> </td>
											<td> <?php echo $row['sessionTime']; ?> </td>
											<td> <?php echo $row['sessionPlace']; ?> </td>
											<td> <?php echo $row['sessionDetails']; ?> </td>
										</tr>
									</tbody>
								</table>
							</div>
							
							
						</div>
					</div>
				</div>
				
			</div>
		</div> 
	</div> 
	<!-- end row --> 
	<?php 
		/*
			
			<div class="row">
			<div class="col-md-3">
			<div class="alert alert-primary" role="alert">
			<strong>Session Date</strong> You should check in on some of those fields below.
			
			</div>
			
			</div>
			
			<div class="col-md-3">
			<div class="alert alert-primary" role="alert">
			<strong>Holy guacamole!</strong> You should check in on some of those fields below.
			
			</div>
			
			</div>
			
			</div>
		*/
	} ?>
	
		