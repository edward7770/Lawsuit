<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	
	$qry="SELECT assetId,c.`assetCatName_en` AS assetCatName,s.`subAssetCatName_en` AS subAssetCatName,assetDate,supplier,a.depreciationRate,amount,taxValue,taxAmount,totalAssetAmount,quantity,location,remarks FROM
		`tbl_asset` a 
		LEFT JOIN `tbl_asset_category` c ON c.`assetCatId`=a.`assetCatId`
		LEFT JOIN `tbl_asset_subcategory` s ON s.`subAssetCatId`=a.`subassetCatId`
		WHERE a.`isActive`=1";
	$stmt=$dbo->prepare($qry);
	/////$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
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
	
	foreach($result as $value)
	{ ?>
	<tr>
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['assetId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['assetId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value['assetCatName']; ?></td>
		<td><?php echo $value['subAssetCatName']; ?></td>
		<td><?php echo $value['supplier']; ?></td>
		<td><?php echo setAmountDecimal($value['amount']); ?></td>
		<td><?php echo setAmountDecimal($value['taxAmount']); ?></td>
		<td><?php echo setAmountDecimal($value['totalAssetAmount']); ?></td>
		<td><?php echo $value['assetDate']; ?></td>
		<td><?php echo setAmountDecimal($value['depreciationRate']); ?></td>
		<td><?php echo $value['quantity']; ?></td>
		<td><?php echo $value['location']; ?></td>
		<td><?php echo $value['remarks']; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
