<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('config/conn.php');
	$where="";
	if(isset($_POST['searchId']) && !empty($_POST['searchId']))
	{
		$where=" AND docsId=:id";
	}
	$qry="SELECT docsId,docName_en,docName_ar,docDescription, docFilePath,docFileName 
	FROM tbl_docs WHERE isActive=1 $where";
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
	$noImage='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	foreach($result as $index=> $value)
	{ 
	if(empty($value['docFilePath']) || empty($value['docFileName']))
		$fileImage=$noImage;
	else 
		$fileImage='<a href="'.$value['docFilePath'].$value['docFileName'].'" target="_blank" class="btn-action-icon"><span><i class="fa fa-file fa-2x"></i></span></a>';	
		
	?>
	<tr>
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['docsId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['docsId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a> &nbsp;&nbsp;
		</td>
		<td> <?php echo $serial; ?> </td>
		<td><?php echo $value['docName_ar']; ?></td>
		<td><?php echo $value['docName_en']; ?></td>
		<td><?php echo $value['docDescription']; ?></td>
		<td> <?php echo $fileImage; ?> </td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
