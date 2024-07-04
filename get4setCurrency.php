<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
if(!isset($dbo))
	include_once('config/conn.php');
$language=$_SESSION['lang'];
$myquery="SELECT name_$language currencyText FROM `tbl_currency` WHERE isActive=1";
$stmt=$dbo->prepare($myquery);
if($stmt->execute())
{
	$resultCurrency = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else 
{
	$errorInfo = $stmt->errorInfo();
	exit($json =$errorInfo[2]);
}
if($resultCurrency)
{
	$currencyText=$resultCurrency[0]['currencyText'];
	if(isset($_POST['getCurrency'])) echo $currencyText;
}
else 
	echo '0';

?>