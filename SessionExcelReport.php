<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_POST['reportType'] = "detailed";
$_POST['type'] = -1;
$_POST['state'] = -1;
$_POST['stage'] = -1;

// include_once('header.php'); 
include_once('config/conn.php');
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
$fileName = "Session_Report" . date('Y-m-d') . ".xls";

// Column names 
// $fields_payment = array('#', set_value('lsMasterCode'), set_value('referenceNo'), set_value('lawsuitId'), set_value('sessions'), set_value('dateSession'), set_value('timeSession'));
$fields_payment = array('#', set_value('lsMasterCode'), set_value('lawsuitId'),  set_value('lawsuitLocation'),  set_value('customer'),  set_value('opponent'), set_value('sessions'), set_value('dateSession'), set_value('timeSession'));
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

$qry = "SELECT l.`phrase`, $language AS VALUE FROM `language` l
LEFT JOIN languagepageref r ON r.languageid=l.`id`
INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
WHERE m.`pageName`=:pageName";
$stmt = $dbo->prepare($qry);
$stmt->bindParam(":pageName", $pageName, PDO::PARAM_STR);
if ($stmt->execute()) {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $errorInfo = $stmt->errorInfo();
    exit($json = $errorInfo[2]);
}

$qry_client = "SELECT customerId,c.custTypeId,customerName_ar, customerName_en,
ct.typeName_$language
FROM tbl_customers c 
LEFT JOIN tbl_customertypes ct ON ct.`custTypeId`=c.custTypeId
WHERE c.`isActive`=1";

$stmt_client = $dbo->prepare($qry_client);
if ($stmt_client->execute()) {
    $result_client = $stmt_client->fetchAll(PDO::FETCH_ASSOC);
} else {
    $errorInfo = $stmt->errorInfo();
    exit($json = $errorInfo[2]);
}


$total_payment = 0;
$total_paid = 0;
$total_due = 0;
$date = date('n/j/Y');

$excelData = set_value('session_report') . "\n";
$excelData .= set_value('date') . ":" . $date . "\n";
$excelData .= "\n";
$excelData .= implode("\t", array_values($fields_payment)) . "\n";
if (count($result_payment) > 0) {

    $qry_session = "CALL sp_getLawsuitDetails_Summary('" . $language . "'," . $_POST['type'] . "," . $_POST['state'] . "," . $_POST['stage'] . ") ";
    $stmt_session = $dbo->prepare($qry_session);
    if ($stmt_session->execute()) {
        $result = $stmt_session->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errorInfo = $stmt_session->errorInfo();
        exit($json = $errorInfo[2]);
    }

    $serial = 1;
    $lsDetailsId = 0;

    function getCountSessions($lsDetailsId)
    {
        $count = 0;
        foreach ($GLOBALS['result'] as $i => $value) {
            if ($lsDetailsId == $value['lsDetailsId_Session'])
                $count++;
        }
        return $count + 2;
    }

    foreach ($result as $i => $value) {
        if ($lsDetailsId != $value['lsDetailsId']) {
            $lsDetailsId = $value['lsDetailsId'];
            $rowspan = 0;
            if ($_POST['reportType'] == "detailed" && $value['lsDetailsId_Session'] > 0) {
                $rowspan = getCountSessions($lsDetailsId);
            }
            if ($rowspan > 0) {
                foreach ($result as $i => $innerValue) {
                    if ($lsDetailsId == $innerValue['lsDetailsId_Session']) {
                        if (new DateTime($_GET['from']) <= new DateTime($innerValue['sessionDate']) && new DateTime($innerValue['sessionDate']) <= new DateTime($_GET['to'])) {
                            $lineData_payment = array($serial, $value['ls_code'],  $value['lawsuitId'], $value['location'], $value['customerName'], $value['oppoName'], $innerValue['sessionName'], $innerValue['sessionDate'], $innerValue['sessionTime']);
                            array_walk($lineData_payment, 'filterData');
                            $excelData .= implode("\t", array_values($lineData_payment)) . "\n";
                            $serial++;
                        }
                    }
                }
            }
        }
    }
}


// Headers for download 
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Render excel data 
echo $excelData;

exit;
