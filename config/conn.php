<?php 
	$superAdminId="1";
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "lv7_lawsuit";
		
	try {
		$dbo = new PDO('mysql:host='.$servername.';dbname='.$dbname, $username, $password);
		$dbo -> exec("set names utf8");
		} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	$conn->set_charset("UTF8");
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	//$dbname = "pphisind_jobs_new";
	

	
	// Create connection
	$con = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($con->connect_error) {
		die("Connection failed: " . $con->connect_error);
	} 
	if(isset($_SESSION['lang']))
	{
		$language=$_SESSION['lang'];
		$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
		LEFT JOIN languagepageref r ON r.languageid=l.`id`
		WHERE r.menuid=7"; 
		$stmt=$GLOBALS['dbo']->prepare($qry);
		//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result_conn = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	
	function get_lang_msg($val)
	{
		foreach($GLOBALS['result_conn'] as $value)
		{
			if(trim($value['phrase'])==trim($val))
			{
				return $value['VALUE'];
				break;
			}
		}
	}
	$decimalplace=3;
	function setAmountDecimal($amount) 
	{
		return number_format((float)$amount,$GLOBALS['decimalplace']);
	}
	/////echo displayDate('2023-10-13');
	function displayDate($originalDate)
	{
		// Original date in 'YYYY-MM-DD' format
		/////$originalDate = '2023-10-13';
		// Convert the original date to a Unix timestamp
		$timestamp = strtotime($originalDate);
		// Format the timestamp to the desired 'DD-MM-YYYY' format
		///$newDate = date('d-m-Y', $timestamp);
		$newDate = date('d-M-y', $timestamp);
		return $newDate;  // Output: 13-10-2023
	}
	function displayDate_ar($originalDate)
	{
		// Original date in 'YYYY-MM-DD' format
		/////$originalDate = '2023-10-13';
		// Convert the original date to a Unix timestamp
		$timestamp = strtotime($originalDate);
		// Format the timestamp to the desired 'DD-MM-YYYY' format
		///$newDate = date('d-m-Y', $timestamp);
		$newDate = date('y-M-d', $timestamp);
		return $newDate;  // Output: 13-10-2023
	}
	function displayDate_en($originalDate)
	{
		return displayDate($originalDate);
	}
	/////$lsMasterCode="LS-000";
	
	
	
?>