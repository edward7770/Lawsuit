<?php 
	session_start();
	include_once('config/conn.php');
	////print_r($postData);
	////print_r($_FILES);
	////exit;
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		echo $errorInfo;
		///echo get_lang_msg('errorMessage');
		die;
	}
	$_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);
	
	if(isset($_POST['action']) && $_POST['action']=='del')
	{
		$qry="SELECT p.`lsPaymentId` FROM tbl_lawsuit_payment p
		LEFT JOIN tbl_lawsuit_stages s ON s.lsStagesId=p.lsStageId
		WHERE p.isActive=1 AND p.lsMasterId=:lsMasterId AND s.isFullStage=1";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
		
		if($result)
		{
			echo 'you cannot deleted because all stages payment has been already done.';
			exit('0');
		}
		else {
			
			$qry="SELECT p.* FROM tbl_lawsuit_payment p
			WHERE p.isActive=1 AND p.lsDetailsId=:lsDetailsId";
			$stmt=$dbo->prepare($qry);
			$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
			if($stmt->execute())
			{
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$errorInfo = $stmt->errorInfo();
				exit($json =$errorInfo[2]);
			}
			
			if($result)
			{
				echo 'you cannot deleted because payment has been already done against this stage.';
				exit('0');
			}
			
			else
			{
                $dbo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
				// $qry="UPDATE tbl_lawsuit_details SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE isActive=1 AND lsDetailsId=:lsDetailsId";
                $qry = "DELETE FROM `tbl_lawsuit_details` WHERE `lsMasterId` = :lsMasterId;
                    DELETE FROM `tbl_lawsuit_master` WHERE `lsMasterId` = :lsMasterId;";
				$stmt=$dbo->prepare($qry);
				$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
				// $stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
                $stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_INT);
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
			
		}
		
		
		
	}
?>