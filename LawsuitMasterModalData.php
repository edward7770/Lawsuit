
<?php 
	include_once('config/conn.php');
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	WHERE r.menuid in (11)"; 
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$resultLan = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	////print_r($result);
	function set_value($val)
	{
		foreach($GLOBALS['resultLan'] as $value)
		{
			if(trim($value['phrase'])==trim($val))
			{
				return $value['VALUE'];
				break;
			}
		}
		
	}
	
	$qry="CALL LawsuitMasterDetaiModallData(:lsMaster)";
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":lsMaster",$_POST['mId'],PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	$serial=1;
	/////$countryName="countryName_".$language;
	////print_r($result);
	$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	
	foreach($result as $value)
	{ ?>
	<tr>
		<td> <?php echo $serial; ?> </td>
		<td class="d-flex align-items-center">
			<div class="dropdown dropdown-action">
				<a href="#" class=" btn-action-icon " data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
				<div class="dropdown-menu dropdown-menu-end">
					<ul>
						<li>
							<a class="dropdown-item" href="javascript:viewLSDetails(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);"><i class="far fa-eye me-2"></i><?php echo set_value('view'); ?></a>
						</li>
						<?php if($_SESSION['customerId']<0) { ?>
						<li>
							<a class="dropdown-item" href="javascript:viewLSEdit(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);"><i class="far fa-eye me-2"></i><?php echo set_value('edit'); ?></a>
						</li>
						<?php /*
						<li>
							<a class="dropdown-item" href="#"><i class="far fa-edit me-2"></i><?php echo set_value('printContracts'); ?></a>
						</li>
						
						<li>
							<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_modalStage"><i class="far fa-trash-alt me-2"></i><?php echo set_value('delete'); ?></a>
						</li>
						*/ ?>
						
						<li>
							<a class="dropdown-item" onclick="delModalStage('<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>');" data-bs-toggle="modal" data-bs-target="#delete_modalStage"><i class="far fa-trash-alt me-2"></i><?php echo set_value('delete'); ?></a>
						</li>
						
						
						<?php } ?>
			
					</ul>
				</div>
			</div>
		</td>
		<td><?php echo $value['custName']; ?></td>
		<td><?php echo $value['lsTypeName_en']; ?></td>
		<td style="color:<?php echo $value['lsColor']; ?>"><?php echo $value['lsStateName_en']; ?></td>
		<td><?php echo $value['lsStagesName_en']; ?></td>
		<td><?php echo $value['OpponentsName']; ?></td>
		<td><?php echo $value['oppoLawyerName']; ?></td>
		
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
		 

		
		<td><?php if($value['isPaid']) echo $checkButton; else echo $crossButton; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
