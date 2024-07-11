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

function getCountSessions($lsDetailsId)
{
    $count=0;
    foreach($GLOBALS['result_lawsuitData'] as $i=> $value)
    {
        if($lsDetailsId==$value['lsDetailsId_Session'])
            $count++;
    }
    return $count+2;
}

function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 

$serial=1;
$serial_session=1;
$lsDetailsId=0;

$displayDate="displayDate_$language";
// Excel file name for download 
$fileName = "lawsuit_" . date('Y-m-d') . ".xls"; 
 
// Column names 
$fields = array('#', set_value('lsMasterCode'), set_value('referenceNo'), set_value('lawsuitId'), set_value('customer'), set_value('opponent'), set_value('state'), set_value('stage'), set_value('lawsuitDate')); 
$field_sessions = array('#', set_value('lsMasterCode'), set_value('sessions'), set_value('dateSession'), set_value('timeSession')); 
 
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 
// Fetch records from database 
$qry_lawsuitData="CALL sp_getLawsuitDetails_Summary('".$language."',".$_GET['type'].",".$_GET['state'].",".$_GET['stage'].") ";
$stmt_lawsuidData=$dbo->prepare($qry_lawsuitData);
//$stmt_lawsuidData->bindParam(":to_date",$to_date,PDO::PARAM_STR);
if($stmt_lawsuidData->execute())
{
    $result_lawsuitData = $stmt_lawsuidData->fetchAll(PDO::FETCH_ASSOC);
    $stmt_lawsuidData->closeCursor();
}
else 
{
    $errorInfo = $stmt_lawsuidData->errorInfo();
    exit($json =$errorInfo[2]);
}

$temp_lawsuit_code = '';
if(count($result_lawsuitData) > 0){ 
    // Output each row of the data 
    foreach($result_lawsuitData as $i=> $value) {
        if($temp_lawsuit_code != $value['ls_code']) {
            $lineData = array($serial, $value['ls_code'], $value['empName_'.$language], $value['lawsuitId'], $value['customerName'], $value['oppoName'], $value['lsStateName_'.$language], $value['lsStagesName_'.$language], $displayDate($value['lsDate'])); 
            array_walk($lineData, 'filterData'); 
            $serial++;
            $temp_lawsuit_code = $value['ls_code'];
            $excelData .= implode("\t", array_values($lineData)) . "\n"; 
        }
    } 
}else{ 
    $excelData .= 'No records found...'. "\n"; 
}

$excelData .= "\n";
if($_GET['reportType']=="detailed") {
    $excelData .= implode("\t", array_values($field_sessions)) . "\n"; 
}

if(count($result_lawsuitData) > 0){ 
    foreach($result_lawsuitData as $i=> $value)
    {
        if($lsDetailsId!=$value['lsDetailsId'])
        {
            $lsDetailsId=$value['lsDetailsId'];
            $rowspan=0;
            if($_GET['reportType']=="detailed" && $value['lsDetailsId_Session']>0)
            {
                $rowspan=getCountSessions($lsDetailsId);
            }
        
            if($rowspan>0)
            {
                foreach($result_lawsuitData as $i=> $innerValue)
                {
                    if($lsDetailsId==$innerValue['lsDetailsId_Session'])
                    { 
                        $lineData1 = array($serial_session, $innerValue['ls_code'], $innerValue['sessionName'], $innerValue['sessionDate'], $innerValue['sessionTime']); 
                        array_walk($lineData1, 'filterData'); 
                        $excelData .= implode("\t", array_values($lineData1)) . "\n"; 
                        $serial_session++;
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