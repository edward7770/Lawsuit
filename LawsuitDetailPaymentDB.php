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
/*
	if(isset($_POST['action']) && ($_POST['action']=='add' || $_POST['action']=='update'))
	{
		$where="";
		if($_POST['action']=='update')
			$where=" AND lsStateId<>:lsStateId ";
			
		$qry="SELECT lsStateId FROM tbl_lawsuit_states WHERE isActive=1 $where AND (lsStateName_ar=:lsStateName_ar OR lsStateName_en=:lsStateName_en)";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsStateName_en",$_POST['nameEn'],PDO::PARAM_STR);
		$stmt->bindParam(":lsStateName_ar",$_POST['nameAr'],PDO::PARAM_STR);
		if($_POST['action']=='update')
			$stmt->bindParam(":lsStateId",$_POST['id'],PDO::PARAM_STR);
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
			exit(get_lang_msg('record_already_exists'));
	}
	*/
if (isset($_POST['action']) && ($_POST['action'] == 'add' || $_POST['action'] == 'edit')) {
	$lsPaymentId = 0;
	if ($_POST['action'] == 'edit')
		$lsPaymentId = $_POST['id'];
	$dbo->beginTransaction();
	$qry = "CALL sp_get_LSPaymentDetailsNew(:lsMasterId,:lsPaymentId)";
	$stmt = $dbo->prepare($qry);
	$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_INT);
	$stmt->bindParam(":lsPaymentId", $lsPaymentId, PDO::PARAM_INT);
	if ($stmt->execute()) {
		$resultPayment = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$errorInfo = $stmt->errorInfo();
		exit($json = $errorInfo[2]);
	}
	$totalPaidAmount = 0;
	if ($resultPayment) {
		$totalPaidAmount = $resultPayment[0]['paidAmount'] + ((int)$_POST['amount']);
	}
	if ($totalPaidAmount > $resultPayment[0]['totalContractAmount']) {
		echo get_lang_msg('checkPaymentContractMsg');
		echo "101";
		exit();
	}
}


if (isset($_POST['action']) && $_POST['action'] == 'add') {
	$qry = "INSERT INTO tbl_lawsuit_payment(lsStageId,lsMasterId,lsDetailsId,paymentDate,paymentMode,amount,invoiceNumber,remarks,isActive,createdBy) 
					VALUES(:lsStageId,:lsMasterId,:lsDetailsId,:paymentDate,:paymentMode,:amount,:invoiceNumber,:remarks,1,:createdBy)";


	$stmt = $dbo->prepare($qry);


	$stmt->bindParam(":lsStageId", $_POST['lsStage'], PDO::PARAM_INT);
	$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_INT);
	$stmt->bindParam(":lsDetailsId", $_POST['lsDId'], PDO::PARAM_INT);
	$stmt->bindParam(":paymentDate", $_POST['date'], PDO::PARAM_STR);
	$stmt->bindParam(":paymentMode", $_POST['mode'], PDO::PARAM_STR);
	$stmt->bindParam(":amount", $_POST['amount'], PDO::PARAM_STR);
	$stmt->bindParam(":invoiceNumber", $_POST['invoiceNumber'], PDO::PARAM_STR);
	$stmt->bindParam(":remarks", $_POST['remarks'], PDO::PARAM_STR);
	$stmt->bindParam(":createdBy", $_SESSION['username'], PDO::PARAM_STR);
	if ($stmt->execute()) {
		updatePaidStatus($dbo);
		/////echo get_lang_msg('added_successfully');
		/////exit('1');
	} else {
		$dbo->rollBack();
		$errorInfo = $stmt->errorInfo();
		errorMessage($json = $errorInfo[2]);
	}
}

if (isset($_POST['action']) && $_POST['action'] == 'updateSessionInvoice') {
	$qry = "INSERT INTO tbl_lawsuit_invoice(lsMasterId,lsDetailsId,invoiceNumber,invoiceDate) 
		VALUES(:lsMasterId,:lsDetailsId,:invoiceNumber,:invoiceDate)";
	$stmt = $dbo->prepare($qry);
	$stmt->bindParam(":lsDetailsId", $_POST['isDid'], PDO::PARAM_INT);
	$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_INT);
	$stmt->bindParam(":invoiceNumber", $_POST['invoiceNumber'], PDO::PARAM_STR);
	$stmt->bindParam(":invoiceDate", $_POST['invoiceDate'], PDO::PARAM_STR);

	if ($stmt->execute()) {
		$_SESSION['invoice_no'] = $_POST['invoiceNumber'];
		// Optionally return a success response
		echo json_encode(['status' => 'success', 'invoice_no' => $_SESSION['invoice_no']]);
	} else {
		$dbo->rollBack();
		$errorInfo = $stmt->errorInfo();
		errorMessage($json = $errorInfo[2]);
	}
}

if (isset($_POST['action']) && $_POST['action'] == 'getData') {
	/*
		$qry="SELECT lsPaymentId, m.`ls_code`, s.lsStagesName_$language as lsStagesName , d.`lawsuitId`,
			DATE_FORMAT(paymentDate,'%d-%b-%y') paymentDate, paymentMode, amount, remarks,
			(CASE WHEN IFNULL(m.`isPaidAll`,0)=0 THEN 'Current Stage' ELSE 'Full Stages' END) 
			 paymentStatus_en,
			(CASE WHEN IFNULL(m.`isPaidAll`,0)=0 THEN 'مرحله واحده' ELSE 'مدفوع جميع المراحل' END) 
			 paymentStatus_ar 
			FROM tbl_lawsuit_payment l 
			LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=l.`lsMasterId`
			LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=l.`lsStageId`
			LEFT JOIN `tbl_lawsuit_details` d ON d.`lsMasterId`=m.`lsMasterId`
			WHERE l.isActive=1 AND m.`isActive`=1
			AND d.`lsMasterId`=:lsMasterId ";
		*/

	$qry = "SELECT lsPaymentId AS id,l.lsStageId AS stageId,l.`lsDetailsId` AS lsDId, paymentDate AS `date`, 
			paymentMode AS `mode`, amount, invoiceNumber, IFNULL(m.`isPaidAll`,0) AS fullPaid , d.`isPaid` AS paid, remarks ,
			d.`lsDetailsId`
			FROM tbl_lawsuit_payment l 
			LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=l.`lsMasterId`
			LEFT JOIN `tbl_lawsuit_details` d ON d.`lsMasterId`=m.`lsMasterId`
			WHERE l.isActive=1 AND d.`isActive`=1 AND lsPaymentId=:lsPaymentId
			GROUP BY l.`lsPaymentId`";
	$stmt = $dbo->prepare($qry);
	$stmt->bindParam(":lsPaymentId", $_POST['id'], PDO::PARAM_STR);
	if ($stmt->execute()) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		//print_r($result);
		echo json_encode(['status' => true, 'data' => $result], JSON_INVALID_UTF8_SUBSTITUTE);
	} else {
		$errorInfo = $stmt->errorInfo();
		errorMessage($json = $errorInfo[2]);
	}
}


if (isset($_POST['deleteLawsuit'], $_POST['lsMId'])) {
	$qry = "SELECT COUNT(lsPaymentId) AS countPaymentRecords FROM `tbl_lawsuit_payment` p WHERE p.`isActive`=1 AND p.`lsMasterId`=:lsMasterId";

	$stmt = $dbo->prepare($qry);
	$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_INT);
	if ($stmt->execute()) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$errorInfo = $stmt->errorInfo();
		errorMessage($json = $errorInfo[2]);
	}

	if ($result) {
		$countPaymentRecords = $result[0]['countPaymentRecords'];
		if ($countPaymentRecords == 0) {

			$qry = "UPDATE `tbl_lawsuit_details` d SET d.`isActive`=-1, d.`modifiedBy`=:modifiedBy, d.`modifiedDate`=NOW() WHERE d.`lsMasterId`=:lsMasterId;
                            UPDATE `tbl_lawsuit_master` m SET m.`isActive`=-1, m.`modifiedBy`=:modifiedBy, m.`modifiedDate`=NOW() WHERE m.`lsMasterId`=:lsMasterId;";
			$stmt = $dbo->prepare($qry);
			$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_INT);
			$stmt->bindParam(":modifiedBy", $_SESSION['username'], PDO::PARAM_STR);

			if ($stmt->execute()) {
				echo get_lang_msg('deleted_successfully');
				exit('1');
			} else {

				$errorInfo = $stmt->errorInfo();
				errorMessage($json = $errorInfo[2]);
			}
		} else {

			echo get_lang_msg('deleteMsgOnPaymentMade');
			exit('0');
		}
	} else {
	}
}

if (isset($_POST['action']) && $_POST['action'] == 'getContractData') {
	$qry = "SELECT c.`lsContractId` AS id,`lsStageId`,`amount`, `taxValue`, taxAmount, `totalAmount`, `contractEn`, `contractAr` FROM `tbl_lawsuit_contract` c 
			WHERE c.`isActive`=1 AND c.`lsContractId`=:lsContractId";

	$stmt = $dbo->prepare($qry);
	$stmt->bindParam(":lsContractId", $_POST['id'], PDO::PARAM_INT);
	if ($stmt->execute()) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		//print_r($result);
		echo json_encode(['status' => true, 'data' => $result], JSON_INVALID_UTF8_SUBSTITUTE);
	} else {
		$errorInfo = $stmt->errorInfo();
		errorMessage($json = $errorInfo[2]);
	}
}

if (isset($_POST['action']) && $_POST['action'] == 'edit') {
	$qry = "UPDATE tbl_lawsuit_payment SET lsDetailsId=:lsDetailsId,lsStageId=:lsStageId,paymentDate=:paymentDate, paymentMode=:paymentMode, amount=:amount, invoiceNumber=:invoiceNumber, remarks=:remarks, modifiedDate=NOW(), modifiedBy=:modifiedBy
		WHERE lsPaymentId=:lsPaymentId";
	$stmt = $dbo->prepare($qry);
	$stmt->bindParam(":lsDetailsId", $_POST['lsDId'], PDO::PARAM_INT);
	$stmt->bindParam(":lsStageId", $_POST['lsStage'], PDO::PARAM_INT);
	$stmt->bindParam(":paymentDate", $_POST['date'], PDO::PARAM_STR);
	$stmt->bindParam(":paymentMode", $_POST['mode'], PDO::PARAM_STR);
	$stmt->bindParam(":amount", $_POST['amount'], PDO::PARAM_STR);
	$stmt->bindParam(":invoiceNumber", $_POST['invoiceNumber'], PDO::PARAM_STR);
	$stmt->bindParam(":remarks", $_POST['remarks'], PDO::PARAM_STR);
	$stmt->bindParam(":modifiedBy", $_SESSION['username'], PDO::PARAM_STR);
	$stmt->bindParam(":lsPaymentId", $_POST['id'], PDO::PARAM_INT);
	if ($stmt->execute()) {
		updatePaidStatus($dbo);
	} else {
		$dbo->rollBack();
		$errorInfo = $stmt->errorInfo();
		errorMessage($json = $errorInfo[2]);
	}
}

if (isset($_POST['action']) && $_POST['action'] == 'del') {
	////lsMId
	if ($_POST['del'] == 'contract')
		$qry = "UPDATE tbl_lawsuit_contract SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE lsContractId=:id";
	else if ($_POST['del'] == 'payment')
		$qry = "UPDATE tbl_lawsuit_payment SET isActive=-1, modifiedBy=:modifiedBy, modifiedDate=NOW() WHERE lsPaymentId=:id";

	$stmt = $dbo->prepare($qry);
	$stmt->bindParam(":modifiedBy", $_SESSION['username'], PDO::PARAM_STR);
	$stmt->bindParam(":id", $_POST['id'], PDO::PARAM_INT);
	if ($stmt->execute()) {
		if ($_POST['del'] == 'contract') {
			echo get_lang_msg('deleted_successfully');
			exit('1');
		} else if ($_POST['del'] == 'payment') {
			$qry = "SELECT p.* FROM tbl_lawsuit_payment p
				LEFT JOIN tbl_lawsuit_stages s ON s.lsStagesId=p.lsStageId
				WHERE p.isActive=1 AND p.lsMasterId=:lsMasterId AND s.isFullStage=1";
			$stmt = $dbo->prepare($qry);
			$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_INT);
			if ($stmt->execute()) {
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$errorInfo = $stmt->errorInfo();
				exit($json = $errorInfo[2]);
			}
			if ($result) {
				echo get_lang_msg('deleted_successfully');
				exit('1');
			} else {

				$qry = "UPDATE `tbl_lawsuit_master` m SET m.`isPaidAll`=NULL WHERE m.`lsMasterId`=:lsMasterId;";
				$stmt = $dbo->prepare($qry);
				$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_INT);
				if ($stmt->execute()) {
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				} else {
					$errorInfo = $stmt->errorInfo();
					exit($json = $errorInfo[2]);
				}

				$qry = "SELECT d.lsDetailsId FROM tbl_lawsuit_details d
						WHERE d.lsMasterId=:lsMasterId AND d.isActive=1
						ORDER BY d.lsDetailsId DESC LIMIT 0,1";
				$stmt = $dbo->prepare($qry);
				$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_INT);
				if ($stmt->execute()) {
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				} else {
					$errorInfo = $stmt->errorInfo();
					exit($json = $errorInfo[2]);
				}
				if ($result) {
					$lsDetailsId = $result[0]['lsDetailsId'];
					$qry = "SELECT p.`lsPaymentId` FROM tbl_lawsuit_payment p WHERE p.lsDetailsId=:lsDetailsId AND p.isActive=1";
					$stmt = $dbo->prepare($qry);
					$stmt->bindParam(":lsDetailsId", $lsDetailsId, PDO::PARAM_INT);
					if ($stmt->execute()) {
						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					} else {
						$errorInfo = $stmt->errorInfo();
						exit($json = $errorInfo[2]);
					}
					if ($result) {
						echo get_lang_msg('deleted_successfully');
					} else {
						$qry = "UPDATE tbl_lawsuit_details d SET d.isPaid=null, modifiedDate=now(), modifiedBy=:modifiedBy WHERE d.lsDetailsId=:lsDetailsId";
						$stmt = $dbo->prepare($qry);
						$stmt->bindParam(":lsDetailsId", $lsDetailsId, PDO::PARAM_INT);
						$stmt->bindParam(":modifiedBy", $_SESSION['username'], PDO::PARAM_STR);
						if ($stmt->execute()) {
							echo get_lang_msg('deleted_successfully');
							exit('1');
						} else {
							$errorInfo = $stmt->errorInfo();
							exit($json = $errorInfo[2]);
						}
					}
				} else {
					echo get_lang_msg('deleted_successfully');
					exit('1');
				}
			}
		}
	} else {
		$errorInfo = $stmt->errorInfo();
		errorMessage($json = $errorInfo[2]);
	}
}
/*
	if(isset($_POST['customerData']))
	{
		$postData = json_decode($_POST['customerData'], true); // assuming you are posting JSON data
		////print_r($postData);
	}
	*/

if (isset($_POST['ContractData'])) {
	$postData = json_decode($_POST['ContractData'], true); // assuming you are posting JSON data
	if (isset($_FILES['contractFile'])) {
		$file_path = 'uploadFiles/';
		include_once('customerDBFiles.php');
		$filePath = $file_path . "contractFile/";
		check_file("contractFile");
		$fileName = set_file_name("contractFile", "contractFile");
		upload_file('contractFile', $filePath, $fileName);
		$contractFilePath = $filePath . $fileName;
		$column = "contractFilePath=:contractFilePath,";
	} else {
		$contractFilePath = "";
		$column = "";
	}

	$qryInsert = "INSERT INTO tbl_lawsuit_contract
            (
             `lsMasterId`,
             `lsStageId`,
             `amount`,
             `taxValue`,
             `taxAmount`,
             `totalAmount`,
             `contractEn`,
             `contractAr`,
             `contractFilePath`,
             `isActive`,
             `createdBy`,
             `createdDate`
			)
		values (:lsMasterId,
        :lsStageId,
        :amount,
        :taxValue,
        :taxValueAmount,
        :totalAmount,
        :contractEn,
        :contractAr,
        :contractFilePath,
        1,
        :createdBy,
        now()
        )";


	$qryUpdate = "update tbl_lawsuit_contract set
             lsStageId=:lsStageId,
             amount=:amount,
             taxValue=:taxValue,
             taxAmount=:taxValueAmount,
             totalAmount=:totalAmount,
             contractEn=:contractEn,
             contractAr=:contractAr,
             $column
             modifiedDate=now(),modifiedBy=:createdBy where lsContractId=:lsContractId";
	if ($postData['action'] == 'add')
		$qry = $qryInsert;
	else $qry = $qryUpdate;
	$stmt = $dbo->prepare($qry);
	if ($postData['action'] == 'add')
		$stmt->bindParam(":lsMasterId", $postData['lsMId'], PDO::PARAM_INT);
	$stmt->bindParam(":lsStageId", $postData['stage'], PDO::PARAM_INT);
	$stmt->bindParam(":amount", $postData['amountContract'], PDO::PARAM_STR);
	$stmt->bindParam(":taxValue", $postData['taxValue'], PDO::PARAM_STR);
	$stmt->bindParam(":taxValueAmount", $postData['taxValueAmount'], PDO::PARAM_STR);
	$stmt->bindParam(":totalAmount", $postData['totContAmount'], PDO::PARAM_STR);
	$stmt->bindParam(":contractEn", $postData['termEn'], PDO::PARAM_STR);
	$stmt->bindParam(":contractAr", $postData['termAr'], PDO::PARAM_STR);
	if (isset($_FILES['contractFile']))
		$stmt->bindParam(":contractFilePath", $contractFilePath, PDO::PARAM_STR);
	else {
		if ($postData['action'] == 'add')
			$stmt->bindParam(":contractFilePath", $contractFilePath, PDO::PARAM_NULL);
	}
	$stmt->bindParam(":createdBy", $_SESSION['username'], PDO::PARAM_STR);
	if ($postData['action'] == 'edit')
		$stmt->bindParam(":lsContractId", $postData['id'], PDO::PARAM_INT);
	if ($stmt->execute()) {
		if ($postData['action'] == 'add')
			echo get_lang_msg('added_successfully');
		else
			echo get_lang_msg('modified_successfully');
		exit('1');
	} else {
		$errorInfo = $stmt->errorInfo();
		errorMessage($json = $errorInfo[2]);
	}
}

/*
	function updatePaidStatus($dbo)
	{
		$qry="SELECT m.isPaidAll FROM tbl_lawsuit_master m WHERE m.isActive=1  AND m.isPaidAll=1 AND m.lsMasterId=:lsMasterId";
		$stmt=$dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_STR);
		if($stmt->execute())
		{
			$resultisPaidAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
		
		if(isset($_POST['paidStatusStage']) && $_POST['paidStatusStage']=='paidStatus' && $_POST['paidStatus']==1)
		{
			$paidStatus=1;
			$tbl_lawsuit_details=0;
			$qry="UPDATE tbl_lawsuit_details set isPaid=:isPaid,isPaidBy=:modifiedBy, isPaidDateTime=NOW() WHERE isActive=1 AND lsMasterId=:lsMasterId AND lsDetailsId=:lsDetailsId";
			$stmt=$dbo->prepare($qry);
			$stmt->bindParam(":isPaid",$paidStatus,PDO::PARAM_INT);
			$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
			$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_INT);
			$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
			if($stmt->execute())
			{
				$tbl_lawsuit_details=1;
				$paidStatus=0;
			}
			else 
			{
				$dbo->rollBack();
				$errorInfo = $stmt->errorInfo();
				errorMessage($json =$errorInfo[2]);
			}
			$tbl_lawsuit_master=0;
			if($resultisPaidAll && $paidStatus==0)
			{
				$qry="UPDATE tbl_lawsuit_master set isPaidAll=:isPaid,isPaidByAll=:modifiedBy, isPaidAllDateTime=NOW() WHERE isActive=1 AND lsMasterId=:lsMasterId";
				$stmt=$dbo->prepare($qry);
				$stmt->bindParam(":isPaid",$paidStatus,PDO::PARAM_INT);
				$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_STR);
				$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
				if($stmt->execute())
				{
					$tbl_lawsuit_master=1;
				}
				else 
				{
					$dbo->rollBack();
					$errorInfo = $stmt->errorInfo();
					errorMessage($json =$errorInfo[2]);
				}
			}
			else $tbl_lawsuit_master=1;
				
			if($tbl_lawsuit_details && $tbl_lawsuit_master)
			{
				$dbo->commit();
				echo get_lang_msg('modified_successfully');
				exit('1');
			}
			else 
			{
				echo get_lang_msg('errorMessage');
			}
		}
		if(isset($_POST['paidStatusStage']) && $_POST['paidStatusStage']=='paidStatusAll' && $_POST['paidStatus']==2)
		{
			$paidStatus=1;
			$tbl_lawsuit_details=0;
			$tbl_lawsuit_master=0;
			$qry="UPDATE tbl_lawsuit_master set isPaidAll=:isPaid,isPaidByAll=:modifiedBy, isPaidAllDateTime=NOW() WHERE isActive=1 AND lsMasterId=:lsMasterId";
			$stmt=$dbo->prepare($qry);
			$stmt->bindParam(":isPaid",$paidStatus,PDO::PARAM_INT);
			$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_STR);
			$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
			if($stmt->execute())
			{
				$tbl_lawsuit_master=1;
				///echo get_lang_msg('modified_successfully');
				////exit('1');
			}
			else 
			{
				$dbo->rollBack();
				$errorInfo = $stmt->errorInfo();
				errorMessage($json =$errorInfo[2]);
			}
			if(!$resultisPaidAll)
			{
				$qry="UPDATE tbl_lawsuit_details set isPaid=:isPaid,isPaidBy=:modifiedBy, isPaidDateTime=NOW() WHERE isActive=1 AND lsMasterId=:lsMasterId";
				$stmt=$dbo->prepare($qry);
				$stmt->bindParam(":isPaid",$paidStatus,PDO::PARAM_INT);
				/////$stmt->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_STR);
				$stmt->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_STR);
				$stmt->bindParam(":modifiedBy",$_SESSION['username'],PDO::PARAM_STR);
				if($stmt->execute())
				{
					///echo get_lang_msg('modified_successfully');
					/////exit('1');
					$tbl_lawsuit_details=1;
				}
				else 
				{
					$dbo->rollBack();
					$errorInfo = $stmt->errorInfo();
					errorMessage($json =$errorInfo[2]);
				}
			}
			else $tbl_lawsuit_details=1;
			
			if($tbl_lawsuit_master && $tbl_lawsuit_details)
			{
				$dbo->commit();
				echo get_lang_msg('modified_successfully');
				exit('1');
			}
			else 
			{
				$dbo->rollBack();
				echo get_lang_msg('errorMessage');
			}
		}
	}
	*/
if (isset($_POST['isFullStage'], $_POST['stageId'])) {
	$qry = "SELECT IFNULL(s.`isFullStage`,0) isFullStage FROM `tbl_lawsuit_stages` s WHERE s.`lsStagesId`=:lsStagesId";
	$stmt = $dbo->prepare($qry);
	$stmt->bindParam(":lsStagesId", $_POST['stageId'], PDO::PARAM_INT);
	if ($stmt->execute()) {
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($result) echo $result[0]['isFullStage'];
		else echo "-1";
	} else {
		$errorInfo = $stmt->errorInfo();
		exit($json = $errorInfo[2]);
	}
}

function updatePaidStatus($dbo)
{
	$tbl_lawsuit_master = 0;
	$tbl_lawsuit_details = 0;

	if ($_POST['lsDId'] == "-1") {
		$qry = "UPDATE tbl_lawsuit_master set isPaidAll=1,isPaidByAll=:modifiedBy, isPaidAllDateTime=NOW() WHERE isActive=1 AND lsMasterId=:lsMasterId";
		$stmt = $dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy", $_SESSION['username'], PDO::PARAM_STR);
		if ($stmt->execute()) {
			$tbl_lawsuit_master = 1;
		} else {
			$dbo->rollBack();
			$errorInfo = $stmt->errorInfo();
			errorMessage($json = $errorInfo[2]);
		}

		$qry = "UPDATE tbl_lawsuit_details set isPaid=1,isPaidBy=:modifiedBy, isPaidDateTime=NOW() WHERE isActive=1 AND lsMasterId=:lsMasterId";
		$stmt = $dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy", $_SESSION['username'], PDO::PARAM_STR);
		if ($stmt->execute()) {
			$tbl_lawsuit_details = 1;
		} else {
			$dbo->rollBack();
			$errorInfo = $stmt->errorInfo();
			errorMessage($json = $errorInfo[2]);
		}
	} else {
		$qry = "UPDATE tbl_lawsuit_master set isPaidAll=null,isPaidByAll=:modifiedBy, isPaidAllDateTime=NOW() WHERE isActive=1 AND lsMasterId=:lsMasterId";
		$stmt = $dbo->prepare($qry);
		$stmt->bindParam(":lsMasterId", $_POST['lsMId'], PDO::PARAM_STR);
		$stmt->bindParam(":modifiedBy", $_SESSION['username'], PDO::PARAM_STR);
		if ($stmt->execute()) {
			$tbl_lawsuit_master = 1;
		} else {
			$dbo->rollBack();
			$errorInfo = $stmt->errorInfo();
			errorMessage($json = $errorInfo[2]);
		}


		$tbl_lawsuit_master = 1;

		$qry = "UPDATE tbl_lawsuit_details set isPaid=1,isPaidBy=:modifiedBy, isPaidDateTime=NOW() WHERE isActive=1 AND lsDetailsId=:lsDetailsId";
		$stmt = $dbo->prepare($qry);
		$stmt->bindParam(":lsDetailsId", $_POST['lsDId'], PDO::PARAM_INT);
		$stmt->bindParam(":modifiedBy", $_SESSION['username'], PDO::PARAM_STR);
		if ($stmt->execute()) {
			$tbl_lawsuit_details = 1;
		} else {
			$dbo->rollBack();
			$errorInfo = $stmt->errorInfo();
			errorMessage($json = $errorInfo[2]);
		}
	}

	if ($tbl_lawsuit_details && $tbl_lawsuit_master) {
		$dbo->commit();
		echo get_lang_msg('modified_successfully');
		exit('1');
	} else {
		$dbo->rollBack();
		echo get_lang_msg('errorMessage');
	}
}
