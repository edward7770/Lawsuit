
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
	/////$_POST['mId']=4;
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
	$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	
	/////$countryName="countryName_".$language;
	////print_r($result);
	foreach($result as $value)
	{ 
	$isPaid=$value['isPaid'];
	?>
	<tr>
		<td> <?php echo $serial; ?> </td>
		<td>
			<?php if($isPaid) { ?>
			<div class="dropdown dropdown-action">
				<a href="#" class=" btn-action-icon " data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
				<div class="dropdown-menu dropdown-menu-end">
				
					<ul>
						<li>
							<a class="dropdown-item" href="javascript:viewLSDetailsPayment(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);""><i class="far fa-edit me-2"></i><?php echo set_value('payment'); ?></a>
						</li>
					</ul>
				
				</div>
			</div>
			<?php } ?>
		</td>
		<td><?php echo $value['custName']; ?></td>
		<td><?php echo $value['lsTypeName_en']; ?></td>
		<td style="color:<?php echo $value['lsColor']; ?>"><?php echo $value['lsStateName_en']; ?></td>
		<td><?php echo $value['lsStagesName_en']; ?></td>
		<td><?php echo $value['OpponentsName']; ?></td>
		<td><?php echo $value['oppoLawyerName']; ?></td>
		<td><?php echo $value['amountContract']; ?></td>
		<td><?php echo $value['taxValue']; ?></td>
		<td><?php echo $value['totalContractAmount']; ?></td>
		<td><?php echo $value['paidAmount']; ?></td>
		<td><?php echo $value['totalDues']; ?></td>
		<td><?php if($value['isPaid']) echo $checkButton; else echo $crossButton; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
