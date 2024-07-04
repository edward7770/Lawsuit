<?php 
	$superAdminId="1";
	$servername = "localhost";
	$username = "beinlawy_admin";
	$password = "Oman@2024!";
	$dbname = "beinlawy_ahmedalmufarji";
		
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

		$qry=" call sp_generateNotification();"; 
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
	
	
?>