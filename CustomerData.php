	
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	
	$where="";
	if(isset($_POST['searchId']) && !empty($_POST['searchId']))
	{
		$where=" AND c.`customerId`=:id";
	}
	
	$qry="SELECT customerId,c.custTypeId,customerName_ar,customerName_en,idPassportNo, CONCAT(c.passportNoFilePath, c.passportNoFileName) AS passportCopy,crNo, 
	CONCAT(c.crNoFilePath,c.crNoFileName) AS crCopy,
	if(c.custTypeId=1,crNo,idPassportNo) as crNoIdPassportNo,
    if(c.custTypeId=1,CONCAT(c.crNoFilePath,c.crNoFileName),CONCAT(c.passportNoFilePath, c.passportNoFileName)) as crPassportFile,
	cityId,address,postBox,mobileNo,customerEmail,
	nationalityId,endDateAgency,CONCAT(agencyFilePath,agencyFileName) AS agencyCopy,notes, c.createdBy, c.createdDate 
	,ci.cityName_$language, co.countryName_$language,ct.typeName_$language
	FROM tbl_customers c 
	LEFT JOIN `tbl_city` ci ON ci.`tbl_cityId`=c.`cityId`
	LEFT JOIN `tbl_country` co ON co.`countryId`=c.`nationalityId`
	LEFT JOIN tbl_customertypes ct ON ct.`custTypeId`=c.custTypeId
	WHERE c.`isActive`=1 $where";
		
	$stmt=$dbo->prepare($qry);
	if(!empty($where))
		$stmt->bindParam(":id",$_POST['searchId'],PDO::PARAM_INT);
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
	$countryName="countryName_".$language;
	$noImage='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	////print_r($result);
	foreach($result as $value)
	{
		if($value['custTypeId']==1 && !empty($value['crNo']))
		{
			if(empty($value['crCopy']))
				$crCopy=$noImage;
			else 
				$crCopy='<a href="'.$value['crCopy'].'" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>';
		}
		else $crCopy="";
		
		if($value['custTypeId']==2 && !empty($value['idPassportNo']))
		{
			if(empty($value['passportCopy']))
				$passportCopy=$noImage;
			else 
				$passportCopy='<a href="'.$value['passportCopy'].'" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>';
		}
		else $passportCopy="";
		///if(!empty($value['endDateAgency']))
		//{
			if(empty($value['agencyCopy']))
				$agencyCopy=$noImage;
			else 
				$agencyCopy='<a href="'.$value['agencyCopy'].'" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>';
		///}
		////else $agencyCopy="";
		
		
		if(!empty($value['crNoIdPassportNo']))
		{
			if(empty($value['crPassportFile']))
				$crPassportFile=$noImage;
			else 
				$crPassportFile='<a href="'.$value['crPassportFile'].'" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>';
		}
		else $crPassportFile="";
	?>
	<tr>
		<td> <?php echo $serial; ?> </td>
		
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['customerId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['customerId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td><?php echo $value['typeName_'.$language]; ?></td>
		<td><?php echo $value['customerName_'.$language]; ?></td>
		
		<td><?php if(!empty($value['crNoIdPassportNo'])) echo $value['crNoIdPassportNo']; ?></td>
		<td><?php echo $crPassportFile; ?></td>
		
		<!--<td><?php if(!empty($value['crNo'])) echo $value['crNo']; ?></td>
		<td><?php echo $crCopy; ?></td>
		<td><?php if(!empty($value['idPassportNo'])) echo $value['idPassportNo']; ?></td>
		<td><?php echo $passportCopy?></td> -->
	
		<td><?php echo $value['customerEmail']; ?></td>
		<td><?php echo $value['mobileNo']; ?></td>
		<td><?php echo $value[$countryName]; ?></td>
		<td><?php echo $value['address']; ?></td>
	
		<td><?php echo $value['endDateAgency']; ?></td>
		<td><?php echo $agencyCopy; ?></td>
		<td><?php echo $value['createdDate']; ?></td>
		<td><?php echo $value['createdBy']; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
