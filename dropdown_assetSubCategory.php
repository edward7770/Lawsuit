<?php 
if(isset($_POST['catId']) && !empty($_POST['catId']))
{
	session_start();
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$qry="SELECT subAssetCatId AS id, subAssetCatName_$language as val FROM tbl_asset_subcategory WHERE isActive=1 AND assetCatId=:assetCatId";
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":assetCatId",$_POST['catId'],PDO::PARAM_INT);
	if($stmt->execute())
	{
		$resultSubCat = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	if(isset($_POST['getSelect']))
		echo '<option value="">'.$_POST['getSelect'].'</option>';
		
	
	foreach($resultSubCat as $val)
	{
		echo "<option value='".$val['id']."'>".$val['val']."</option>";
	}
}
?>