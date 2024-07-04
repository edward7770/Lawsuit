
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	
	
	$qry="CALL `sp_getNotifications`('en',1)";
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		///$errorInfo = $stmt->errorInfo();
		///exit($json =$errorInfo[2]);
		exit(get_lang_msg('errorMessage'));
	}

	$serial=1;
	foreach($result as $value)
	{ 
		if($value['url']=='LawsuitDetail.php')
			$href="showLSSearch('".$value['lsMasterId']."','".$value['lsDetailId']."','".$value['id']."','".$value['collapsedText']."')";
		else 
			$href="showSearch('".$value['id']."','".$value['url']."')";
		$date=$value['date'];
		?>
	<tr>
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="<?php echo $href; ?>"><span><i class="fe fe-eye"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['notificationId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value['title_'.$language]; ?></td>
		<td style="color:<?php if($value['generateType']==1) echo '#ff0000'; else echo '#24c529'; ?> "><?php echo $value['description_'.$language]; ?></td>
		<td><?php if($language=='en') echo displayDate_en($date); else echo displayDate_ar($date); ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
