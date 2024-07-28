<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include_once('header.php'); 
include_once('config/conn.php');
$language = $_SESSION['lang'];
$pageName = "Lawsuit";
$pageName2 = "Payroll";
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

$serial = 1;

// Excel file name for download 
$fileName = "Payroll_Report" . date('Y-m-d') . ".xls";

// Column names 
$fields = array('#', set_value('employeeName'), set_value('employeecategory'));
$fields_gen = array('#', set_value('employeeName'), set_value('employeecategory'), set_value('basicSalary'), set_value('allowance'), set_value('grossSalary'), set_value('deduction'), set_value('netPayment'));

if (isset($_GET['type']) && $_GET['type'] == "Remaining") {
    $qry_data = "call sp_get_remaining_emp_payroll(:month,:year)";
    $stmt_data = $dbo->prepare($qry_data);
    $stmt_data->bindParam(":month", $_GET['month'], PDO::PARAM_INT);
    $stmt_data->bindParam(":year", $_GET['year'], PDO::PARAM_INT);
    if ($stmt_data->execute()) {
        $result_data = $stmt_data->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errorInfo = $stmt_data->errorInfo();
        exit($json = $errorInfo[2]);
    }
} else if (isset($_GET['type']) && $_GET['type'] == "Generated") {
    $qry_data = "call sp_get_generatedPayroll(:month,:year)";
    $stmt_data = $dbo->prepare($qry_data);
    $stmt_data->bindParam(":month", $_GET['month'], PDO::PARAM_INT);
    $stmt_data->bindParam(":year", $_GET['year'], PDO::PARAM_INT);
    if ($stmt_data->execute()) {
        $result_data = $stmt_data->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errorInfo = $stmt_data->errorInfo();
        exit($json = $errorInfo[2]);
    }
}

$date = date('n/j/Y');

$excelData = set_value('payroll_report') . "\n";
$excelData .= set_value('date') . ":" . $date . "\n";
$excelData .= "\n";

if (isset($_GET['type']) && $_GET['type'] == "Remaining") {
    $excelData .= implode("\t", array_values($fields)) . "\n";
} else {
    $excelData .= implode("\t", array_values($fields_gen)) . "\n";
}

if (count($result_data) > 0) {
    if (isset($_GET['type']) && $_GET['type'] == "Remaining") {
        foreach ($result_data as $i => $value) {
            $lineData_payment = array($serial, $value['empName_' . $language], $value['categoryName']);
            array_walk($lineData_payment, 'filterData');
            $excelData .= implode("\t", array_values($lineData_payment)) . "\n";
            $serial++;
        }
    } else {
        foreach ($result_data as $i => $value) {
            $lineData_payment = array($serial, $value['empName_' . $language], $value['categoryName'], setAmountDecimal($value['basicSalary']), setAmountDecimal($value['allow']), setAmountDecimal($value['basicSalary'] + $value['allow']), setAmountDecimal($value['deduct']), setAmountDecimal(($value['basicSalary'] + $value['allow']) - $value['deduct']));
            array_walk($lineData_payment, 'filterData');
            $excelData .= implode("\t", array_values($lineData_payment)) . "\n";
            $serial++;
        }
    }
}


// Headers for download 
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Render excel data 
echo $excelData;

exit;
