
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	include_once('languageActions.php');
	
	///$qry="CALL sp_getLawsuitDetails('".$language."',".$_SESSION['customerId'].") ";
	$qry="CALL sp_getLawsuitDetails('".$language."',".$_SESSION['customerId'].",".$_POST['type'].",".$_POST['state'].",".$_POST['stage'].") ";
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
	$typeName="lsTypeName_".$language;
	$stateName="lsStateName_".$language;
	$stagesName="lsStagesName_".$language;
	$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	
	$view=set_value('view');
	$newStage=set_value('newStage');
	$edit= set_value('edit');
	$printContracts=set_value('printContracts');
	$history=set_value('history');
	$delete=set_value('delete'); 
	/*
	function isPaid($lsMasterId,$lsDetailsId,$ls_code,$isPaid)
	{
		if($isPaid)
		echo '<div class="dropdown dropdown-action">
		<a href="#" class=" btn-action-icon " data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
		<div class="dropdown-menu dropdown-menu-end">
		<ul>
		<li>
		<a class="dropdown-item" href="javascript:viewLSDetails('.$lsMasterId.",".$lsDetailsId.');"><i class="far fa-eye me-2"></i>'.$GLOBALS['view'].'</a>
		</li>
		<li>
		<a class="dropdown-item" href="javascript:newStage('.$lsMasterId.",'".$ls_code.');"><i class="far fa-edit me-2"></i>'.$GLOBALS['newStage'].'</a>
		</li>
		<li>
		<a class="dropdown-item" href="javascript:viewLSEdit('.$lsMasterId.",'".$lsDetailsId.');"><i class="far fa-eye me-2"></i>'.$GLOBALS['edit'].'</a>
		</li>
		<li>
		<a class="dropdown-item" href="javascript:printContracts('.$lsDetailsId.');"><i class="far fa-edit me-2"></i>'.$GLOBALS['printContracts'].'</a>
		</li>
		<li>
		<a class="dropdown-item" href="javascript:viewDetails('.$lsMasterId.');"><i class="far fa-eye me-2"></i>'.$GLOBALS['history'].'</a>
		</li>
		<li>
		<a class="dropdown-item" href="javascript:del('.$lsMasterId.');" data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="far fa-trash-alt me-2"></i>'.$GLOBALS['delete'].'</a>
		</li>
		</ul>
		</div>
		</div>';
		else echo "";
	}
	<td class="d-flex align-items-center"> 
	*/
	////print_r($result);
	foreach($result as $i=> $value)
	{ 
		$isPaid=$value['isPaid'];
		 /*
		<li>
			<a class="dropdown-item" href="javascript:printContracts(<?php echo $value['lsMasterId']; ?>);"><i class="far fa-edit me-2"></i><?php echo $printContracts; ?></a>
		</li>
		<li>
								<a class="dropdown-item" onclick="delModal(<?php echo $value['lsDetailsId']; ?>);" data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="far fa-trash-alt me-2"></i><?php echo $delete; ?></a>
							</li>
		
		*/ 
		
	?>
	<tr>
		<td> 
			<?php if($isPaid) { ?>
				<div class="dropdown dropdown-action">
						
					<a href="#" class=" btn-action-icon" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
					
					<div class="dropdown-menu dropdown-menu-end">
					
						<ul>
							
							<li>
								<a class="dropdown-item" href="javascript:viewLSDetails(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);"><i class="far fa-eye me-2"></i><?php echo $view; ?></a>
							</li>
							
							<?php if($_SESSION['customerId']<0) { ?>
							<li>
								<a class="dropdown-item" href="javascript:newStage(<?php echo $value['lsMasterId'].",".$value['lsDetailsId'].",'".$value['ls_code']."'"; ?>);"><i class="far fa-edit me-2"></i><?php echo $newStage; ?></a>
							</li>
							<li>
								<a class="dropdown-item" href="javascript:viewLSEdit(<?php echo $value['lsMasterId'].",'".$value['lsDetailsId']; ?>');"><i class="far fa-eye me-2"></i><?php echo $edit; ?></a>
							</li>
							
							
							<?php } ?>
							<li>
								<a class="dropdown-item" href="javascript:viewDetails(<?php echo $value['lsMasterId']; ?>);"><i class="far fa-eye me-2"></i><?php echo $history; ?></a>
							</li>
						</ul>
						
					</div>
					
				</div>
			<?php } ?>
		</td>
		<td> <?php echo $serial; ?> </td>
		<td> <a <?php if($isPaid) { ?>href="javascript:viewLSDetails(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);" <?php } ?> ><?php echo $value['ls_code']; ?>  </a> </td>
		<td><?php echo $value['referenceNo']; ?></td>
		<td><?php echo $value['lawsuitId']; ?></td>
		
		<td><?php  
		        
		        if(!empty($value['lsDate']))
		        {
		        $displayDate="displayDate_$language";
		        echo  $displayDate( $value['lsDate']);
		        }
		        
		        ?>
		 </td>
		
		<td> <?php echo $value['customerName']; ?> </td>
		<td> <?php echo $value['oppoName']; ?> </td>
		<td> <?php echo $value['location']; ?> </td>
		<td> <?php echo $value['empName_'.$language]; ?> </td>
		<td><?php echo $value[$typeName]; ?></td>
		<!--<td style="background-color:<?php ///echo $value['lsColor']; ?>"><?php ///echo $value[$stateName]; ?></td> -->
		<td style="color:<?php echo $value['lsColor']; ?>"><?php echo $value[$stateName]; ?></td>
		<td><?php echo $value[$stagesName]; ?></td>
		<td><?php echo $value['noofStages']; ?></td>
		<td><?php if($value['isPaid']) echo $checkButton; else echo $crossButton; ?></td>
		
		</tr>
		
		<?php 
		$serial++;
	}
?>
