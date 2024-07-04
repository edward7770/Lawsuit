
<?php 
	if(isset($_POST['pageId']))
	{
		include_once('config/conn.php');
		$where="";
		if($_POST['pageId']>0)
			$where=" AND m.pageId=:pageId";
		
		$qry="SELECT l.id, l.phrase,l.ar, l.en, m.pageName, m.pageId,l.isActive FROM language l
		LEFT JOIN languagepageref r ON r.languageid=l.id
		INNER JOIN tbl_pagemenu m ON m.pageId=r.menuId
		WHERE m.isActive=1 $where ORDER BY en,ar, pageName";
		$stmt=$dbo->prepare($qry);
		if($_POST['pageId']>0)
			$stmt->bindParam(":pageId",$_POST['pageId'],PDO::PARAM_INT);
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
				<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['id']; ?>);"><span><i class="fe fe-edit"></i></span></a>
				<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['id']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
			</td>
			<td> <?php echo $serial; ?> </td>
			<td class="text-wrap"><?php echo $value['phrase']; ?></td>
			<td class="text-wrap"><?php echo $value['ar']; ?></td>
			<td class="text-wrap"><?php echo $value['en']; ?></td>
			<td><?php if($value['isActive']) echo $checkButton; else echo $crossButton; ?></td>
			<td><?php echo $value['pageName']; ?></td>
		</tr>
		
		<?php 
			$serial++;
		}
	}
?>
