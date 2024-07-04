<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	$qry="SELECT w.webpageId,w.webpageDisplayname_en,w.webpageDisplayname_ar,i.iconHtml,w.url,w.parentWebpageId,w.menuOrderby,w.isActive,w.isParent,
	w.isShownOnMenu, wj.`webpageDisplayname_en` AS parentName 
	FROM tbl_webpages w 
	LEFT JOIN tbl_webpages wj ON wj.webpageId =w.parentWebpageId AND wj.isParent>0
	LEFT JOIN tbl_icons i ON i.iconId=w.icon
	WHERE w.isActive<>-1 GROUP BY w.webpageId
	ORDER BY w.menuOrderby, w.webpageDisplayname_en";
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
	$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	foreach($result as $value)
	{ ?>
	<tr>
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['webpageId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['webpageId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value['webpageDisplayname_ar']; ?></td>
		<td><?php echo $value['webpageDisplayname_en']; ?></td>
		<td><i class="<?php echo $value['iconHtml']; ?>"></i></td>
		
		<td><?php echo $value['menuOrderby']; ?></td>
		<td><?php if($value['isActive']>0) echo $checkButton; else echo $crossButton; ?></td>
		<td><?php if($value['isShownOnMenu']>0) echo $checkButton; else echo $crossButton; ?></td>
		<td><?php if($value['parentWebpageId']>0) echo $value['parentName']; ?></td>
		<td><?php echo $value['url']; ?></td>
	</tr>
	<?php 
		$serial++;
	}
?>
