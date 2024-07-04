<?php 
	session_start();
	include_once('config/conn.php');
	////print_r($_POST);
	///exit;
	if(isset($_POST['active']))
	{
		if($_POST['active']=='true')
			$_POST['active']=1;
		else 
			$_POST['active']=0;
	}
	
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		if($_POST['action']=='getData')
		{
			echo json_encode(['status'=>false,'data'=>get_lang_msg('errorMessage')]);
		}
		else
		{
			///echo $errorInfo;
			echo get_lang_msg('errorMessage');
			die;
		}
	}
	
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND l.id<>:id ";
			
		$qry="SELECT l.id FROM language l
		LEFT JOIN languagepageref r ON r.languageid=l.id
		INNER JOIN tbl_pagemenu m ON m.pageId=r.menuId
		WHERE m.isActive<>-1 AND l.phrase=:phrase $where LIMIT 1";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":phrase",$_POST['phrase'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($errorInfo[2]);
		}
		if($result)
			exit(get_lang_msg('record_already_exists'));
	}
	
	if(isset($_POST['action']) && $_POST['action']=='add')
	{
		$dbo->beginTransaction();
		$qry="INSERT INTO `language` (phrase,en,ar,isActive,createdBy)
				VALUES(:phrase,:en,:ar,:isActive,:createdBy)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":phrase",$_POST['phrase'],PDO::PARAM_STR);
		$stmt->bindParam(":en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":isActive",$_POST['active'],PDO::PARAM_INT);
		$stmt->bindParam(":createdBy",$_SESSION['username'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$languageId = $dbo->lastInsertId();
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($errorInfo[2]);
		}
		$languagepageref=0;
		$qry="INSERT INTO languagepageref (languageId,menuId) VALUES (:languageId,:menuId); ";
		foreach($_POST['pageId'] as $pageId)
		{
			$stmt=$dbo->prepare($qry);
			$stmt->bindParam(":languageId",$languageId,PDO::PARAM_INT);
			$stmt->bindParam(":menuId",$pageId,PDO::PARAM_INT);
			if($stmt->execute())
			{
				$languagepageref=1;
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				errorMessage($errorInfo[2]);
			}
		}
		if($languagepageref)
		{
			$dbo->commit();
			echo get_lang_msg('added_successfully');
			exit('1');
		}
		else 
		{
			$dbo->rollBack();
		}
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='getData' )
	{
		$qry="SELECT l.id,r.PageRefId as rId ,l.phrase,l.ar, l.en, m.pageId, l.isActive FROM language l
			LEFT JOIN languagepageref r ON r.languageid=l.id
			INNER JOIN tbl_pagemenu m ON m.pageId=r.menuId
			WHERE m.`isActive`<>-1 AND l.id=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//print_r($result);
			echo json_encode(['status'=>true, 'data'=>$result],JSON_INVALID_UTF8_SUBSTITUTE);

		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($errorInfo[2]);
		}

	}
	
	if(isset($_POST['action']) && $_POST['action']=='update' )
	{
		$dbo->beginTransaction();
		$qry="UPDATE language SET phrase=:phrase,en=:en,ar=:ar,isActive=:isActive,modifiedDate=now(),modifiedBy=:modifiedBy 
		WHERE id=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":phrase",$_POST['phrase'],PDO::PARAM_STR);
		$stmt->bindParam(":en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":ar",$_POST['nameAr'],PDO::PARAM_STR);
		$stmt->bindParam(":isActive",$_POST['active'],PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			/////echo get_lang_msg('modified_successfully');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($errorInfo[2]);
		}
		$languagepageref=0;
		$qry="";
		$qryUpdate="update languagepageref set languageId=:languageId,menuId=:menuId where PageRefId=:PageRefId";
		$qryInsert="INSERT INTO `languagepageref`(languageId,menuId) VALUES (:languageId,:menuId)";
		foreach($_POST['pageId'] as $pageId)
		{
			$query="SELECT l.PageRefId FROM languagepageref l WHERE l.languageId=:languageId AND l.menuId=:menuId";
			$stmt=$dbo->prepare($query);
			$stmt->bindParam(":languageId",$_POST['id'],PDO::PARAM_STR);
			$stmt->bindParam(":menuId",$pageId,PDO::PARAM_STR);
			if($stmt->execute())
			{
				$resultLRef = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				errorMessage($errorInfo[2]);
			}
			
			if($resultLRef) $qry=$qryUpdate;
			else $qry=$qryInsert;
				
				$stmt=$dbo->prepare($qry);
				$stmt->bindParam(":languageId",$_POST['id'],PDO::PARAM_INT);
				$stmt->bindParam(":menuId",$pageId,PDO::PARAM_INT);
				if($resultLRef)
					$stmt->bindParam(":PageRefId",$_POST['rId'],PDO::PARAM_INT);
				if($stmt->execute())
				{
					$languagepageref=1;
				}
				else 
				{
					$dbo->rollBack();
					$errorInfo = $stmt->errorInfo();
					errorMessage($errorInfo[2]);
				}
		}
		
		if($languagepageref)
		{
			$dbo->commit();
			echo get_lang_msg('modified_successfully');
			exit('1');
		}
		else 
		{
			$dbo->rollBack();
		}
		die();
	}
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$qry="SELECT PageRefId from languagepageref where languageId=:languageId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":languageId",$_POST['id'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($errorInfo[2]);
		}
		if($result)
			exit(get_lang_msg('checkRefereceBeforeDeleteLangugage'));

		$qry="UPDATE language SET isActive=-1,modifiedDate=NOW(),modifiedBy=:modifiedBy WHERE id=:id";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
		$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			echo get_lang_msg('deleted_successfully');
			exit('1');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($errorInfo[2]);
		}
	}
	
?>