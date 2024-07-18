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

$serial_payment=1;
$serial_expense=1;
$serial_income=1;
$lsDetailsId=0;

// Excel file name for download 
$fileName = "Accounting_Report_" . date('Y-m-d') . ".xls"; 
 
// Column names 
$fields_payment = array('#', set_value('payment_no_vat'), set_value('expense_no_vat'), set_value('income_no_vat')); 
$displayDate="displayDate_$language";

$qry_payment="SELECT c.`lsContractId`, c.`lsMasterId`,m.`ls_code`,`lsStageId`, s.lsStagesName_$language as lsStagesName,`amount`, `taxValue`, `taxAmount`, c.`createdDate`, `totalAmount`, c.`isActive` FROM `tbl_lawsuit_contract` c 
LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=c.`lsMasterId`
LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=c.`lsStageId`
WHERE c.`isActive`=1";
$stmt_payment=$dbo->prepare($qry_payment);
if($stmt_payment->execute())
{
    $result_payment = $stmt_payment->fetchAll(PDO::FETCH_ASSOC);
}
else 
{
    $errorInfo = $stmt_payment->errorInfo();
    exit($json =$errorInfo[2]);
}

//get expense data
$qry_expense="SELECT expenseId,e.`expCatId` AS catId,m.`ls_code`,expenseDate,supplier, pm.name_$language AS expenseMode,amount,taxValue, taxAmount,totalExpAmount,remarks
FROM tbl_expense e
LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=e.`lsMasterId`
LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=e.`expenseMode` AND pm.`isActive`=1
WHERE e.`isActive`=1";
$stmt_expense=$dbo->prepare($qry_expense);
/////$stmt_expense->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
if($stmt_expense->execute())
{
	$result_expense = $stmt_expense->fetchAll(PDO::FETCH_ASSOC);
}
else 
{
	$errorInfo = $stmt_expense->errorInfo();
	exit($json =$errorInfo[2]);
}

//get income data
$qry_income="SELECT incomeId,incomeTypeId,i.lsMasterId,m.`ls_code`,l.empName_$language AS receivedBy,description,amount,taxValue,taxAmount,totalIncomeAmount,incomeDate
FROM tbl_income i
LEFT JOIN `tbl_employees` l ON l.`empId`=i.`incomeReceivedBy`
LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=i.`lsMasterId`
WHERE i.`isActive`=1";
$stmt_income=$dbo->prepare($qry_income);
/////$stmt_income->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
if($stmt_income->execute())
{
	$result_income = $stmt_income->fetchAll(PDO::FETCH_ASSOC);
}
else 
{
	$errorInfo = $stmt_income->errorInfo();
	exit($json =$errorInfo[2]);
}

$total_payment = 0;
$total_income = 0;
$total_expense = 0;
$date = date('n/j/Y');

$excelData = set_value('profit_loss_report')."\n";
$excelData .= set_value('date').":".$date."\n";
$excelData .= "\n";
$excelData .= "\n";
$excelData .= implode("\t", array_values($fields_payment)) . "\n";
if(count($result_payment) > 0){ 
    foreach($result_payment as $i=> $value)
    {
		$total_payment += $value['amount'];
    }
}

if(count($result_income) > 0){ 
    foreach($result_income as $i=> $value)
    {
		$total_income +=$value['amount'];
    }
}

if(count($result_expense) > 0){ 
    foreach($result_expense as $i=> $value)
    {
		$total_expense +=$value['amount'];
    }
}


for ($i=0; $i < count($result_payment); $i++) { 
    $expense = '';
    $income = '';
    if($i < count($result_expense)) {
        $expense = $result_expense[$i]['amount'];
    }

    if($i < count($result_income)) {
        $income = $result_income[$i]['amount'];
    }
    $excelData .= ($i+1)."\t".$result_payment[$i]['amount']."\t".$expense."\t".$income."\n";
}

$excelData .= "\n";
$excelData .= "\n";
$excelData .= "\n";
$excelData .= "\n";
$excelData .= "\n";
$excelData .= set_value('total')."\t".$total_payment."\t".$total_expense."\t".$total_income."\n";
$excelData .= "\n";
$total = $total_payment + $total_income - $total_expense;
$excelData .= "\t\t\t\t".set_value('profit_loss').$total;
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 
 
exit;