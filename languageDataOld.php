
<?php 
		include_once('config/conn.php');
		
		$qry="SELECT l.id, l.phrase,l.ar, l.en, GROUP_CONCAT(m.pageName SEPARATOR '<br/>') AS pageName FROM LANGUAGE l
		LEFT JOIN languagepageref r ON r.languageid=l.id
		INNER JOIN tbl_pagemenu m ON m.pageId=r.menuId
		WHERE m.isActive=1 GROUP BY l.id ORDER BY en,ar, pageName";
		$stmt=$dbo->prepare($qry);
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
				<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['id']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			</td>
			<td> <?php echo $serial; ?> </td>
			<td class="text-wrap"><?php echo $value['phrase']; ?></td>
			<td class="text-wrap"><?php echo $value['ar']; ?></td>
			<td class="text-wrap"><?php echo $value['en']; ?></td>
			<td class="text-wrap"><?php echo $value['pageName']; ?></td>
		</tr>
		
		<?php 
			$serial++;
		}
?>
