<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	$webpageDisplayname='webpageDisplayname_'.$language;
	include_once('config/conn.php');
	$qry="SELECT w.webpageId, w.$webpageDisplayname, w.url, rw.isActive	
		FROM tbl_webpages w 
		LEFT OUTER JOIN tbl_role_webpages rw ON w.webpageId = rw.webpageId AND rw.roleId = :roleId AND rw.isActive=1
		WHERE w.isActive =1 ORDER BY w.menuOrderby";
	
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":roleId",$_POST['roleId'],PDO::PARAM_STR);
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
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value[$webpageDisplayname]; ?></td>
		<td><?php echo $value['url']; ?></td>
		<td>
			<label class="custom_check">
				<input type="checkbox" class="gaccess" id="<?php echo $value['webpageId']; ?>" <?php if($value['isActive']==1) echo 'checked'; ?>>
				<span class="checkmark"></span> 
			</label>
		</td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
