
<?php

	if(isset($_POST['reportType']) && $_POST['reportType']=="-1")
	{
		exit('reportType');
	}
	
if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	////$language=$_SESSION['lang'];
	include_once('config/conn.php');
	/////include_once('languageActions.php');
	
	$pageName = "Lawsuit";
	$pageName2="LawsuitDetail";
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName,:pageName2)"; 
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
	$stmt->bindParam(":pageName2",$pageName2,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$resultPhrase = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	////print_r($result);
	function set_value($val)
	{
		foreach($GLOBALS['resultPhrase'] as $value)
		{
			if(trim($value['phrase'])==trim($val))
			{
				return $value['VALUE'];
				break;
			}
		}
		
	}
	
	
	?>
	<table class="table table-center table-hover datatable" id="example">
		<thead class="thead-light">
			<tr>
				<th>#</th>
				<th><?php echo set_value('lsMasterCode'); ?></th>
				<th><?php echo set_value('referenceNo'); ?></th>
				<th><?php echo set_value('lawsuitId'); ?></th>
				<th><?php echo set_value('customer'); ?></th>
				<th><?php echo set_value('opponentName'); ?></th>
				<th><?php echo set_value('state'); ?></th>
				<th><?php echo set_value('stage'); ?></th>
				<th><?php echo set_value('lawsuitDate'); ?></th>
			</tr>
		</thead>
		<tbody>
							
	
	<?php 
	
	////$qry="CALL sp_getLawsuitDetails('".$language."',".$_SESSION['customerId'].",".$_POST['type'].",".$_POST['state'].",".$_POST['stage'].") ";
	$qry="CALL sp_getLawsuitDetails_Summary('".$language."',".$_POST['type'].",".$_POST['state'].",".$_POST['stage'].") ";
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	///exit;
	$serial=1;
	$lsDetailsId=0;
		////print_r($result);
		function getCountSessions($lsDetailsId)
		{
			$count=0;
			foreach($GLOBALS['result'] as $i=> $value)
			{
				if($lsDetailsId==$value['lsDetailsId_Session'])
					$count++;
			}
			return $count+2;
		}
		
		foreach($result as $i=> $value)
		{
			if($lsDetailsId!=$value['lsDetailsId'])
			{
					$lsDetailsId=$value['lsDetailsId'];
					$rowspan=0;
					if($_POST['reportType']=="detailed" && $value['lsDetailsId_Session']>0)
					{
						$rowspan=getCountSessions($lsDetailsId);
					}
				
				?>
				<tr>
					<td <?php if($rowspan>0) echo "rowspan='". $rowspan."'"; ?> > <?php echo $serial; ?> </td>
					<td <?php if($rowspan>0) echo "rowspan='". $rowspan."'"; ?> ><?php echo $value['ls_code']; ?></td>
					<td> <?php echo $value['empName_'.$language]; ?> </td>
					<td> <?php echo $value['ls_code']; ?> </td>
					<td> <?php echo $value['customerName']; ?> </td>
					<td <?php if($rowspan>0) echo "rowspan='". $rowspan."'"; ?> > <?php echo $value['oppoName']; ?> </td>
					<td <?php if($rowspan>0) echo "rowspan='". $rowspan."'"; ?> > <?php echo $value['lsStateName_'.$language]; ?> </td>
					<td <?php if($rowspan>0) echo "rowspan='". $rowspan."'"; ?> > <?php echo $value['lsStagesName_'.$language]; ?> </td>
					<td <?php if($rowspan>0) echo "rowspan='". $rowspan."'"; ?> > <?php
						if(!empty($value['lsDate']))
						{
							$displayDate="displayDate_$language";
							echo  $displayDate( $value['lsDate']);
						}
					?>
					</td>
				</tr>
					<?php 
						
						
						if($rowspan>0)
						{
						?>
							<tr>
							<td style='display:none'> </td>
							<td style='display:none'> </td>
							
							<th> <?php echo set_value('sessions'); ?></th>
							<th> <?php echo set_value('dateSession'); ?></th>
							<th> <?php echo set_value('timeSession'); ?></th>
							<td style='display:none'> </td>
							<td style='display:none'> </td>
							<td style='display:none'> </td>
							<td style='display:none'> </td>
							<?php 
								foreach($result as $i=> $innerValue)
								{
									if($lsDetailsId==$innerValue['lsDetailsId_Session'])
									{ ?>
										<tr>
											<td style='display:none'> </td>
											<td style='display:none'> </td>
											<td> <?php echo $innerValue['sessionName']; ?> </td>
											<td> <?php echo $innerValue['sessionDate']; ?></td>
											<td> <?php echo $innerValue['sessionTime']; ?></td>
											<td style='display:none'> </td>
											<td style='display:none'> </td>
											<td style='display:none'> </td>
											<td style='display:none'> </td>
										</tr>
									<?php 
									}
								}
						?>
						</tr>
						<?php 
						} ?>
						
				<?php 
				///break;
				$serial++;
			}
		}
		?>
		</tbody>
		</table>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
<script src="js_custom/dataTableButtons.js"> </script>
	