<?php
session_start();
include_once('config/conn.php');

$invoiceNoLength = strlen($_SESSION['invoice_no']);
$numericPart = substr($_SESSION['invoice_no'], -$invoiceNoLength);
$incrementedNumber = str_pad((int)$numericPart + 1, $invoiceNoLength, '0', STR_PAD_LEFT);
$_SESSION['invoice_no'] = substr($_SESSION['invoice_no'], 0, -$invoiceNoLength) . $incrementedNumber;

$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
$qry = "UPDATE tbl_lawsuit_invoice   
    SET invoiceNumber=:invoiceNumber
    WHERE invoiceId = (  
        SELECT max(invoiceId) FROM (SELECT invoiceId FROM tbl_lawsuit_invoice) AS temp_table  
    );";
$stmt = $dbo->prepare($qry);
$stmt->bindParam(":invoiceNumber",$_SESSION['invoice_no'], PDO::PARAM_STR);

if ($stmt->execute()) {
    // Optionally return a success response
    echo json_encode(['status' => 'success', 'invoice_no' => $_SESSION['invoice_no']]);
} else {
    $dbo->rollBack();
    $errorInfo = $stmt->errorInfo();
    errorMessage($json = $errorInfo[2]);
}
