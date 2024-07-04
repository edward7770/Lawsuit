
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	include_once('languageActions.php');
	$qry="SELECT l.lsMasterId,t.lsTypeName_$language, s.lsStateName_$language, s.lsColor,
	sg.lsStagesName_$language, l.lsSubject, l.lsLocation, l.lsCreatedAt
	FROM tbl_lawsuit_master l 
	LEFT JOIN tbl_lawsuit_type t ON t.lsTypeId=l.lsTypeId
	LEFT JOIN tbl_lawsuit_states s ON s.lsStateId=l.lsStateId
	LEFT JOIN tbl_lawsuit_stages sg ON sg.lsStagesId=l.lsStagesId
	WHERE l.isActive=1 ORDER BY lsCreatedAt DESC";
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
	$serial=1;
	$typeName="lsTypeName_".$language;
	$stateName="lsStateName_".$language;
	$stagesName="lsStagesName_".$language;
	////print_r($result);
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
							<a class="dropdown-item" href="javascript:action(<?php echo $value['lsMasterId'].",'".$value['lsCreatedAt']; ?>','view');"><i class="far fa-eye me-2"></i><?php echo set_value('view'); ?></a>
						</li>
						<li>
							<a class="dropdown-item" href="javascript:action(<?php echo $value['lsMasterId']; ?>,'edit');"><i class="far fa-edit me-2"></i><?php echo set_value('edit'); ?></a>
						</li>
						<li>
							<a class="dropdown-item" href="javascript:action(<?php echo $value['lsMasterId']; ?>,'del');" data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="far fa-trash-alt me-2"></i><?php echo set_value('delete'); ?></a>
						</li>
			
					</ul>
				</div>
			</div>
		</td>
		<td><?php echo $value[$typeName]; ?></td>
		<td style="background-color:<?php echo $value['lsColor']; ?>"><?php echo $value[$stateName]; ?></td>
		<td><?php echo $value[$stagesName]; ?></td>
		<td><?php echo $value['lsSubject']; ?></td>
		<td><?php echo $value['lsLocation']; ?></td>
		<td><?php echo $value['lsCreatedAt']; ?></td>
		
	</tr>
	
	<?php 
		$serial++;
	}
?>
