<?php if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once ('header.php');
include_once ('config/conn.php');
$language = $_SESSION['lang'];

$totalLawsuits = 0;
$totalCustomers = 0;
$totalIncome = 0;
$totalDues = 0;

////$qry=" CALL sp_get_DashboardBoxesData(".$_SESSION['gUserId'].",".$_SESSION['customerId'].",".$_SESSION['lawyerId'].")"; 
$qry = " CALL sp_get_DashboardBoxesData(" . $_SESSION['customerId'] . ")";
$stmt = $dbo->prepare($qry);
if ($stmt->execute()) {
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($result as $val) {
		$totalLawsuits = $val['totalLawsuits'];
		$totalCustomers = $val['totalCustomers'];
		;
		$openCases = $val['openCases'];
		;
		$closedCases = $val['closedCases'];
		;
	}
} else {
	$errorInfo = $stmt->errorInfo();
	exit($json = $errorInfo[2]);
}

$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
$qry = "SELECT l.`phrase`, $language AS VALUE FROM `language` l
LEFT JOIN languagepageref r ON r.languageid=l.`id`
INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
WHERE m.`pageName`=:pageName";
$stmt = $dbo->prepare($qry);
$stmt->bindParam(":pageName", $pageName, PDO::PARAM_STR);
if ($stmt->execute()) {
	$resultLan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
	$errorInfo = $stmt->errorInfo();
	exit($json = $errorInfo[2]);
}
////print_r($resultLan);
function set_value($val)
{
	foreach ($GLOBALS['resultLan'] as $value) {
		if (trim($value['phrase']) == trim($val)) {
			return $value['VALUE'];
			break;
		}
	}
}

?>

<style type="text/css">
	/*
	#cal {
	height: 300px;
	overflow: auto;
	}
	.fc-day-grid-event {
	/* Decrease the height of each row */
	height: 1em;
	}
	*/
	.fc-basic-view .fc-body .fc-row {
	min-height: 3em; /* or any desired height */
	}
	
	.fixTable{
	
	 height: 500px;
 overflow: auto;
	}
</style>

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5> <?php echo set_value('dashboard'); ?></h5>
			</div>
		</div>
		<!-- /Page Header -->
		
		
		<div class="row">

			<div class="col-lg-12 col-md-12">
				<div class="card bg-white">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12 col-sm-12 col-12">
								<div style="padding-top:10px" class="row">
									<div class="col-lg-3 col-sm-3 col-12">
										<div class="bg-info-light">
											<div class="">
												<div class="dash-widget-header" onclick="window.open('Lawsuit.php', '_blank'); return false;" style="cursor:pointer;">
													<span class="inovices-widget-icon ">
														<img src="assets/img/icons/receipt-item.svg" alt="">
													</span>
													<div class="dash-count"  >
														<div class="dash-title"><?php echo set_value('cases'); ?></div>
														<div class="dash-counts">
															<p><?php echo $totalLawsuits; ?></p>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
									
									
									<div class="col-lg-3 col-sm-3 col-12">
										<div class="bg-green-light">
											<div class="">
												<div class="dash-widget-header" onclick="window.open('Customer.php', '_blank'); return false;" style="cursor:pointer;">
													<span class="inovices-widget-icon ">
														<img src="assets/img/icons/message-edit.svg" alt="">
													</span>
													<div class="dash-count">
														<div class="dash-title"><?php echo set_value('dashCustomers'); ?></div>
														<div class="dash-counts">
															<p><?php echo $totalCustomers; ?></p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<br>
									<div class="col-lg-3 col-sm-3 col-12">
										
										<div style="background-color:#fccaff" onclick="window.open('#', '_blank'); return false;" style="cursor:pointer;">
											<div class="">
												<div class="dash-widget-header">
													<span class="inovices-widget-icon">
														<img src="assets/img/icons/archive-book.svg" alt="">
													</span>
													<div class="dash-count">
														<div class="dash-title"><?php echo set_value('openCases'); ?></div>
														<div class="dash-counts">
															<p><?php echo $openCases; ?></p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="col-lg-3 col-sm-3 col-12">
										
										<div class="bg-warning-light" onclick="window.open('#', '_blank'); return false;" style="cursor:pointer;">
											<div class="">
												<div class="dash-widget-header">
													<span class="inovices-widget-icon ">
														<img src="assets/img/icons/archive-book.svg" alt="">
													</span>
													<div class="dash-count">
														<div class="dash-title"><?php echo set_value('closedCases'); ?></div>
														<div class="dash-counts">
															<p><?php echo $closedCases; ?></p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								
								
							</div>
						</div>
						<div class="row mt-3">
							
							<div class="col-lg-12 col-sm-12 col-12">
								<div id="calendar"></div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			<?php

			$qry = "CALL sp_dashboard_getLawsuitDetails('" . $language . "') ";
			$stmt = $dbo->prepare($qry);
			//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
			if ($stmt->execute()) {
				$resultLSDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$errorInfo = $stmt->errorInfo();
				exit($json = $errorInfo[2]);
			}


			$qry = "CALL sp_getSessionDetailDasbhoard() ";
			$stmt = $dbo->prepare($qry);
			//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
			if ($stmt->execute()) {
				$resultLSSessionDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$errorInfo = $stmt->errorInfo();
				exit($json = $errorInfo[2]);
			}

			$qry = " CALL sp_getTaskDetailsDasbhoard(); ";
			$stmt = $dbo->prepare($qry);
			//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
			if ($stmt->execute()) {
				$resultTaskDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$errorInfo = $stmt->errorInfo();
				exit($json = $errorInfo[2]);
			}

			$qry = " CALL sp_getConsultationDetailsDasbhoard(); ";
			$stmt = $dbo->prepare($qry);
			//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
			if ($stmt->execute()) {
				$resultConsultationDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$errorInfo = $stmt->errorInfo();
				exit($json = $errorInfo[2]);
			}




			?>
			
			</div>	
			
			<div class="row">
				<div class="col-lg-6 col-sm-6 col-12">
					<div class="card">
						<div class="card-header" style="background-color:#ffeec3">
							<div class="row align-center">
								<div class="col">
									<h5 class="card-title"><?php echo set_value('recentCases'); ?></h5>
								</div>
							</div>
						</div>
						<div class="card-body fixTable">
							<div class="table-responsive">
								<table class="table table-stripped table-hover">
									<thead class="thead-light">
										<tr>
											<th><?php echo set_value('lsMasterCode'); ?></th>
											<th><?php echo set_value('type'); ?></th>
											<th><?php echo set_value('stage'); ?></th>
											<th><?php echo set_value('stage'); ?></th>
											<th><?php echo set_value('subject'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($resultLSDetails as $row) { ?>
												<tr>
													<td><a href="javascript:viewLSDetails(<?php echo $row['lsMasterId'] . "," . $row['lsDetailsId']; ?>);" > <?php echo $row['ls_code']; ?></a></td>
													<td> <?php echo $row['lsTypeName_' . $language]; ?></td>
													<td> <?php echo $row['lsStagesName_' . $language]; ?></td>
													<td> <?php echo $row['lsStateName_' . $language]; ?></td>
													<td><?php echo $row['lsSubject']; ?></td>
												
												</tr>
											
											<?php
										}
										?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-sm-12 col-12">
					<div class="card">
						<div class="card-header" style="background-color:#d1f3ff" >
							<div class="row align-center">
								<div class="col">
									<h5 class="card-title"><?php echo set_value('recentSessions'); ?></h5>
								</div>
							</div>
						</div>
						<div class="card-body fixTable">
							<div class="table-responsive">
								
								<table class="table table-stripped table-hover">
									<thead class="thead-light">
										<tr>
											<th><?php echo set_value('lsMasterCode'); ?></th>
											<th><?php echo set_value('date'); ?></th>
											<th><?php echo set_value('sessions'); ?></th>
											<th><?php echo set_value('place'); ?></th>
										</tr>
									</thead>
									<tbody>
										
										<?php
										foreach ($resultLSSessionDetails as $row) { ?>
												<tr>
													<td><a href="javascript:viewLawsuitDetail(<?php echo $row['lsMId'] . "," . $row['lsDId'] . "," . $row['id']; ?>);"> <?php echo $row['ls_code']; ?></a></td>
													<td> <?php echo $row['sessionDateNew']; ?></td>
													<td> <?php echo $row['sessionName']; ?></td>
													<td> <?php echo $row['sessionPlace']; ?></td>
												
												</tr>
											
											<?php
										}
										?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>	
			
			
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<div class="card">
						<div class="card-header" style="background-color:#fccaff">
							<div class="row align-center">
								<div class="col">
									<h5 class="card-title"><?php echo set_value('task'); ?></h5>
								</div>
							</div>
						</div>
						<div class="card-body fixTable">
							
							<div class="table-responsive">
								
								<table class="table table-stripped table-hover">
									<thead class="thead-light">
										<tr>
											<th><?php echo set_value('name'); ?></th>
											<th><?php echo set_value('priority'); ?></th>
											<th><?php echo set_value('status'); ?></th>
											<th><?php echo set_value('assignedTo'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($resultTaskDetails as $row) { ?>
												<tr>
													<td><a href="#"> <?php echo $row['taskName']; ?></a></td>
													<td> <?php echo $row['taskPriority']; ?></td>
													<td> <?php echo $row['taskStatus']; ?></td>
													<td><?php echo $row['assignedTo']; ?></td>
												</tr>
											
											<?php
										}
										?>
										
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="card">
						<div class="card-header" style="background-color:#E1FFED" >
							<div class="row align-center">
								<div class="col">
									<h5 class="card-title"><?php echo set_value('consultations'); ?></h5>
								</div>
							</div>
						</div>
						<div class="card-body fixTable">
							<div class="table-responsive">
								
								<table class="table table-stripped table-hover">
									<thead class="thead-light">
										<tr>
											<th><?php echo set_value('date'); ?></th>
											<th><?php echo set_value('client'); ?></th>
											<th><?php echo set_value('SubjectTitle'); ?></th>
										</tr>
									</thead>
									<tbody>
									
									<?php
									foreach ($resultConsultationDetails as $row) { ?>
												<tr>
													<td><a href="#"> <?php echo $row['contractDate']; ?></a></td>
													<td> <?php echo $row['custName']; ?></td>
													<td> <?php echo $row['title']; ?></td>
												</tr>
											
											<?php
									}
									?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>	
			
			
		</div>
	</div>
	<!-- /Page Wrapper -->
	<?php include_once ('footer.php'); ?>
	<script src="js_custom/dashboard.js"></script>
	<script src="js_custom/Lawsuit.js"> </script>
