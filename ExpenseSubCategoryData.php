
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	
	$qry="SELECT s.`subExpCatId`, s.`subExpCatName_ar`, s.`subExpCatName_en`, c.`expCatName_$language` as expCatName,s.createdDate,s.createdBy FROM `tbl_expense_subcategory` s 
		LEFT JOIN `tbl_expense_category` c ON c.`expCatId`=s.`expCatId`
		WHERE s.`isActive`=1";
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
	$countryName="countryName_".$language;
	////print_r($result);
	foreach($result as $value)
	{ ?>
	<tr>
		<td> <?php echo $serial; ?> </td>
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['subExpCatId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['subExpCatId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td><?php echo $value['subExpCatName_ar']; ?></td>
		<td><?php echo $value['subExpCatName_en']; ?></td>
		<td><?php echo $value['expCatName']; ?></td>
		<td><?php echo $value['createdDate']; ?></td>
		<td><?php echo $value['createdBy']; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
