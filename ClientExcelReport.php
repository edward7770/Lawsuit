<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// include_once('header.php'); 
include_once ('config/conn.php');
$language = $_SESSION['lang'];

$pageName = "Lawsuit";
$pageName2 = "LawsuitDetail";
$qry = "SELECT l.`phrase`, $language AS VALUE FROM `language` l
LEFT JOIN languagepageref r ON r.languageid=l.`id`
INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
WHERE m.`pageName` IN(:pageName,:pageName2)";
$stmt = $dbo->prepare($qry);
$stmt->bindParam(":pageName", $pageName, PDO::PARAM_STR);
$stmt->bindParam(":pageName2", $pageName2, PDO::PARAM_STR);
if ($stmt->execute()) {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
} else {
    $errorInfo = $stmt->errorInfo();
    exit($json = $errorInfo[2]);
}

function set_value($val)
{
    foreach ($GLOBALS['result'] as $value) {
        if (trim($value['phrase']) == trim($val)) {
            return $value['VALUE'];
            break;
        }
    }

}

function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"'))
        $str = '"' . str_replace('"', '""', $str) . '"';
}

$serial_payment = 1;
$serial_expense = 1;
$serial_income = 1;
$lsDetailsId = 0;

// Excel file name for download 
$fileName = "Client_Report" . date('Y-m-d') . ".xls";

// Column names 
$fields_payment = array('#', set_value('lsMasterCode'), set_value('customer'), set_value('referenceNo'), set_value('lawsuitNumber'), set_value('selectCustomerType'), set_value('lawsuitLawyer'), set_value('paidAmount'), set_value('dueAmount'), set_value('totalAmount'), set_value('paymentStatus'));

//get payment data
$qry_payment = "CALL sp_getLawsuitDetails('" . $language . "'," . $_SESSION['customerId'] . ",-1,-1,-1)";
$stmt_payment = $dbo->prepare($qry_payment);
if ($stmt_payment->execute()) {
    $result_payment = $stmt_payment->fetchAll(PDO::FETCH_ASSOC);
    $stmt_payment->closeCursor();
} else {
    $errorInfo = $stmt_payment->errorInfo();
    exit($json = $errorInfo[2]);
}

$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
LEFT JOIN languagepageref r ON r.languageid=l.`id`
INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
WHERE m.`pageName`=:pageName"; 
$stmt=$dbo->prepare($qry);
$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
if($stmt->execute())
{
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else 
{
    $errorInfo = $stmt->errorInfo();
    exit($json =$errorInfo[2]);
}

$qry_client="SELECT customerId,c.custTypeId,customerName_ar, customerName_en,
ct.typeName_$language
FROM tbl_customers c 
LEFT JOIN tbl_customertypes ct ON ct.`custTypeId`=c.custTypeId
WHERE c.`isActive`=1";
    
$stmt_client=$dbo->prepare($qry_client);
if($stmt_client->execute())
{
    $result_client = $stmt_client->fetchAll(PDO::FETCH_ASSOC);
}
else 
{
    $errorInfo = $stmt->errorInfo();
    exit($json =$errorInfo[2]);
}


$total_payment = 0;
$total_paid = 0;
$total_due = 0;
$date = date('n/j/Y');

$excelData = set_value('client_report') . "\n";
$excelData .= set_value('date') . ":" . $date . "\n";
$excelData .= "\n";
$excelData .= implode("\t", array_values($fields_payment)) . "\n";
if (count($result_payment) > 0) {
    foreach ($result_payment as $i => $value) {
        $clientType = '';
        foreach ($result_client as $i => $client) {
            if($client['customerName_'.$language] === $value['customerName']) {
                $clientType = $client['typeName_'.$language];
            }
        }
        $total_payment += $value['totalAmount'];
        $total_paid += $value['paymentAmount'];
        $total_due += $value['dueAmount'];
        $lineData_payment = array($serial_payment, $value['ls_code'], $value['customerName'], $value['referenceNo'], (string)$value['lawsuitId'], $clientType, $value['empName_'.$language], setAmountDecimal($value['paymentAmount']), setAmountDecimal($value['dueAmount']), setAmountDecimal($value['totalAmount']), $value['paymentStatus']);
        array_walk($lineData_payment, 'filterData');
        $excelData .= implode("\t", array_values($lineData_payment)) . "\n";
        $serial_payment++;
    }
}

$excelData .= "\n";
$excelData .= "\n";
$excelData .= "\t\t\t\t\t\t" . set_value('total') . "\t" . $total_paid . "\t" . $total_due . "\t" . $total_payment . "\n";

// Headers for download 
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Render excel data 
echo $excelData;

exit;