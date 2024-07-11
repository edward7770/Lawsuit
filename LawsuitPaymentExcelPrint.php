<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	// include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$pageName = "Lawsuit";
	$pageName2="LawsuitDetail";
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName,:pageName2)"; 
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
	$stmt->bindParam(":pageName2",$pageName2,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}

	function set_value($val)
	{
		foreach($GLOBALS['result'] as $value)
		{
			if(trim($value['phrase'])==trim($val))
			{
				return $value['VALUE'];
				break;
			}
		}
		
	}

function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 

$serial=1;
$serial_contract=1;
$lsDetailsId=0;

$displayDate="displayDate_$language";
// Excel file name for download 
$fileName = "lawsuit_payment_" . date('Y-m-d') . ".xls"; 
 
// Column names 
$fields_payment = array('#', set_value('lsMasterCode'), set_value('stage'), set_value('invoiceNumber'), set_value('paymentDate'), set_value('paymentMode'), set_value('paidAmount'), set_value('remarks'), set_value('paidStatus')); 
$field_contract = array('#', set_value('stage'), set_value('paymentAmount'), set_value('taxValueAmount'), set_value('contractAmountIncludingTax')); 
 
// Display column names as first row 
$excelData = implode("\t", array_values($fields_payment)) . "\n"; 
 
if(isset($_GET['lsMId'])) {
    $qry_getpaymentdata="SELECT lsPaymentId, m.`ls_code`, s.lsStagesName_$language as lsStagesName , d.`lawsuitId`,
        DATE_FORMAT(paymentDate,'%d-%b-%y') paymentDate, pm.name_$language as paymentMode, amount, invoiceNumber, remarks,
        (CASE WHEN IFNULL(m.`isPaidAll`,0)=0 THEN 'Current Stage' ELSE 'Full Stages' END) 
            paymentStatus_en,
        (CASE WHEN IFNULL(m.`isPaidAll`,0)=0 THEN 'مرحله واحده' ELSE 'مدفوع جميع المراحل' END) 
            paymentStatus_ar 
        FROM tbl_lawsuit_payment l 
        LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=l.`lsMasterId`
        LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=l.`lsStageId`
        LEFT JOIN `tbl_lawsuit_details` d ON d.`lsMasterId`=m.`lsMasterId`
        LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=l.`paymentMode`
        WHERE l.isActive=1 AND m.`isActive`=1
        AND d.`lsMasterId`=:lsMasterId GROUP BY l.`lsPaymentId` ";
    $stmt_getpaymentdata=$dbo->prepare($qry_getpaymentdata);
    $stmt_getpaymentdata->bindParam(":lsMasterId",$_GET['lsMId'],PDO::PARAM_INT);
    if($stmt_getpaymentdata->execute())
    {
        $result_paymentdata = $stmt_getpaymentdata->fetchAll(PDO::FETCH_ASSOC);
        $stmt_getpaymentdata->closeCursor();
    }
    else 
    {
        $errorInfo = $stmt_getpaymentdata->errorInfo();
        exit($json =$errorInfo[2]);
    }


    $qry_getcontractdata="SELECT c.`lsContractId`, c.`lsMasterId`,m.`ls_code`,`lsStageId`, s.lsStagesName_$language as lsStagesName,`amount`, `taxValue`, taxAmount, `totalAmount`, `contractEn`, `contractAr`, `contractFilePath`, c.`isActive` FROM `tbl_lawsuit_contract` c 
    LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=c.`lsMasterId`
    LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=c.`lsStageId`
    WHERE c.`isActive`=1 AND c.`lsMasterId`=:lsMasterId";
    $stmt_getcontractdata=$dbo->prepare($qry_getcontractdata);
    $stmt_getcontractdata->bindParam(":lsMasterId",$_GET['lsMId'],PDO::PARAM_INT);
    if($stmt_getcontractdata->execute())
    {
        $result_contactdata = $stmt_getcontractdata->fetchAll(PDO::FETCH_ASSOC);
        $stmt_getcontractdata->closeCursor();
    }
    else 
    {
        $errorInfo = $stmt_getcontractdata->errorInfo();
        exit($json =$errorInfo[2]);
    }
}


if(count($result_paymentdata) > 0){ 
    // Output each row of the data 
    foreach($result_paymentdata as $i=> $value) {
        $lineData = array($serial, $value['ls_code'], $value['lsStagesName'], $value['invoiceNumber'], $value['paymentDate'], $value['paymentMode'], setAmountDecimal($value['amount']), $value['remarks'], $displayDate($value['paymentStatus_'.$language])); 
        array_walk($lineData, 'filterData'); 
        $serial++;
        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
        
    } 
}else{ 
    $excelData .= 'No records found...'. "\n"; 
}

$excelData .= "\n";
$excelData .= implode("\t", array_values($field_contract)) . "\n"; 


if(count($result_contactdata) > 0){ 
    foreach($result_contactdata as $i=> $value)
    {
        $lineData1 = array($serial_contract, $value['lsStagesName'], $value['amount'], $value['taxAmount'], $value['totalAmount']); 
        array_walk($lineData1, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData1)) . "\n"; 
        $serial_contract++;
    }
}

 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 
 
exit;