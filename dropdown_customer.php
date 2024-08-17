<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$qry="SELECT customerId as id, customerName_$language as val, endDateAgency FROM tbl_customers c WHERE c.isActive=1";
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	$currentDate = new DateTime();
	if($stmt->execute())
	{
		$result_country = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	if(isset($_POST['getSelect'])) echo '<option value="">'.$_POST['getSelect'].'</option>';
	foreach($result_country as $val)
	{
		if(new DateTime($val['endDateAgency']) > $currentDate) {
			echo "<option value='".$val['id']."'>".$val['val']."</option>";
		}
	}
	
?>