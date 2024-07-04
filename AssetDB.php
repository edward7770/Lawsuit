<?php 
	session_start();
	include_once('config/conn.php');
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	if(isset($_POST['action']) && $_POST['action']=='add' )
		
	{
		$qry="INSERT INTO tbl_asset(assetCatId,subassetCatId,assetDate,supplier,depreciationRate,amount,taxValue,taxAmount,totalAssetAmount,quantity,location,remarks,isActive,createdBy) 
											VALUES(:assetCatId,:subassetCatId,:assetDate,:supplier,:depreciationRate,:amount,:taxValue,:taxValueAmount,:totalAssetAmount,:quantity,:location,:remarks,1,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":assetCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":subassetCatId",$_POST['subCatId'],PDO::PARAM_INT);
		$stmt->bindParam(":assetDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":supplier",$_POST['supl'],PDO::PARAM_STR);
		$stmt->bindParam(":depreciationRate",$_POST['deprRate'],PDO::PARAM_STR);
		$stmt->bindParam(":location",$_POST['location'],PDO::PARAM_STR);
		$stmt->bindParam(":quantity",$_POST['quantity'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValue",$_POST['tax'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValueAmount",$_POST['taxAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":totalAssetAmount",$_POST['totAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":remarks",$_POST['remarks'],PDO::PARAM_STR);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			echo get_lang_msg('added_successfully');
			exit('1');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
	if(isset($_POST['action']) && $_POST['action']=='getData' )
	{
		$qry="SELECT assetId as id,assetCatId AS catId,subassetCatId AS subCatId,assetDate,supplier,depreciationRate AS deprRate,amount,taxValue,taxAmount,totalAssetAmount,quantity,location,remarks FROM
				tbl_asset WHERE isActive=1 AND assetId=:assetId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":assetId",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//print_r($result);
			echo json_encode(['status'=>true, 'data'=>$result],JSON_INVALID_UTF8_SUBSTITUTE);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}

	}
	
	if(isset($_POST['action']) && $_POST['action']=='edit' )
	{
		$qry="UPDATE tbl_asset SET assetCatId=:assetCatId,subassetCatId=:subassetCatId, 
		assetDate=:assetDate, depreciationRate=:depreciationRate, amount=:amount, 
		supplier=:supplier,taxValue=:taxValue, taxAmount=:taxValueAmount,totalAssetAmount=:totalAssetAmount,
		quantity=:quantity,location=:location,remarks=:remarks, modifiedDate=NOW(), 
		modifiedBy=:modifiedBy WHERE assetId=:assetId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":assetCatId",$_POST['catId'],PDO::PARAM_INT);
		$stmt->bindParam(":subassetCatId",$_POST['subCatId'],PDO::PARAM_INT);
		$stmt->bindParam(":assetDate",$_POST['date'],PDO::PARAM_STR);
		$stmt->bindParam(":supplier",$_POST['supl'],PDO::PARAM_STR);
		$stmt->bindParam(":depreciationRate",$_POST['deprRate'],PDO::PARAM_STR);
		$stmt->bindParam(":location",$_POST['location'],PDO::PARAM_STR);
		$stmt->bindParam(":quantity",$_POST['quantity'],PDO::PARAM_STR);
		$stmt->bindParam(":amount",$_POST['amount'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValue",$_POST['tax'],PDO::PARAM_STR);
		$stmt->bindParam(":taxValueAmount",$_POST['taxAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":totalAssetAmount",$_POST['totAmount'],PDO::PARAM_STR);
		$stmt->bindParam(":remarks",$_POST['remarks'],PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":assetId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('modified_successfully');
			exit('1');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$qry="UPDATE tbl_asset SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE assetId=:assetId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":assetId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('deleted_successfully');
			exit('1');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
?>