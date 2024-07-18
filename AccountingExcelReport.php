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
$fields_payment = array('#', set_value('lsMasterCode'), set_value('customer'), set_value('lawsuitLawyer'), set_value('paidAmount'), set_value('dueAmount'), set_value('totalAmount'), set_value('paymentStatus')); 
$fields_expense = array('#', set_value('expenseCategory'), set_value('lsMasterCode'), set_value('supplier'), set_value('amount'), set_value('taxValueAmount'),  set_value('expenseMode'), set_value('expenseDate')); 
$fields_income = array('#', set_value('incomeType'), set_value('lsMasterCode'), set_value('amount'), set_value('taxValueAmount'), set_value('receivedBy'), set_value('incomeDate')); 

//get payment data
$qry_payment="CALL sp_getLawsuitDetails('".$language."',".$_SESSION['customerId'].",-1,-1,-1)";
$stmt_payment=$dbo->prepare($qry_payment);
if($stmt_payment->execute())
{
	$result_payment = $stmt_payment->fetchAll(PDO::FETCH_ASSOC);
	$stmt_payment->closeCursor();
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

$excelData = "\t\t\t".set_value('accounting_report')."\n";
$excelData .= set_value('date').":".$date."\n";
$excelData .= "\n";
$excelData .= set_value('payment')."\n";
$excelData .= implode("\t", array_values($fields_payment)) . "\n";
if(count($result_payment) > 0){ 
    foreach($result_payment as $i=> $value)
    {
		$total_payment += $value['totalAmount'];
        $lineData_payment = array($serial_payment, $value['ls_code'], $value['customerName'], $value['empName_'.$language], setAmountDecimal($value['paymentAmount']), setAmountDecimal($value['dueAmount']), setAmountDecimal($value['totalAmount']), $value['paymentStatus']); 
        array_walk($lineData_payment, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData_payment)) . "\n"; 
        $serial_payment++;
    }
}
$excelData .= "\t\t\t\t\t\t\t".set_value('total').": ".setAmountDecimal($total_payment)."\n";
$excelData .= "\n";
$excelData .= "\n";

$excelData .= set_value('income')."\n";
$excelData .= implode("\t", array_values($fields_income)) . "\n";
if(count($result_income) > 0){ 
    foreach($result_income as $i=> $value)
    {
		if($value['incomeTypeId']==1) {
			$incomeType='Lawsuit';
		}else {
			$incomeType='General';
		}

		$total_income +=$value['amount'];
        $lineData_income = array($serial_income, $incomeType, $value['ls_code'], setAmountDecimal($value['amount']), setAmountDecimal($value['taxAmount']), $value['receivedBy'], $value['incomeDate']); 
        array_walk($lineData_income, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData_income)) . "\n"; 
        $serial_income++;
    }
}

$excelData .= "\t\t\t\t\t\t\t".set_value('total').": ".setAmountDecimal($total_income)."\n";
$excelData .= "\n";
$excelData .= "\n";

$excelData .= set_value('expense')."\n";
$excelData .= implode("\t", array_values($fields_expense)) . "\n";
if(count($result_expense) > 0){ 
    foreach($result_expense as $i=> $value)
    {
		if($value['catId']==1) {
			$expenseCategory='Lawsuit';
		} else {
			$expenseCategory='General Expense';
		}

		$total_expense +=$value['amount'];
        $lineData_expense = array($serial_expense, $expenseCategory, $value['ls_code'], $value['supplier'], number_format((float)$value['amount'],2), number_format((float)$value['taxAmount'],2), $value['expenseMode'], $value['expenseDate']); 
        array_walk($lineData_expense, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData_expense)) . "\n"; 
        $serial_expense++;
    }
}

$excelData .= "\t\t\t\t\t\t\t".set_value('total').": ".setAmountDecimal($total_expense)."\n";
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 
 
exit;