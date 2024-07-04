<?php
	session_start();
	include_once('config/conn.php');
	$webpage_ids = rtrim(implode(',', $_POST['selected']), ',');
	$webpage_ids= $conn -> real_escape_string($webpage_ids);
	$List = implode(', ', $_POST['selected']); 
	$str_arr = explode (",", $List);
	$length= $conn -> real_escape_string($_POST['length']);
	$roleId= $conn -> real_escape_string($_POST['role']);
	// Turn autocommit off
	mysqli_autocommit($conn,FALSE);
	foreach($str_arr as $string)
	{
		$sql = "SELECT rw.isActive FROM tbl_role_webpages rw WHERE rw.roleId=$roleId AND rw.webpageId=$string AND rw.isActive=1";
		$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				$query="UPDATE tbl_role_webpages rw SET rw.isActive=0, rw.modifiedDate=NOW(), rw.modifiedBy='".$_SESSION['username']."' WHERE rw.roleId=$roleId and rw.webpageId=$string";
				if ($conn->query($query) === TRUE) {
				mysqli_autocommit($conn,TRUE);
				echo "1";
				}
				else {
				if (!$mysqli_commit($conn))
				echo "0";
				exit;
				}
			}
			else {
			$query="INSERT INTO tbl_role_webpages (roleId,webpageId,isActive, createdDate,createdBy)
				VALUES ($roleId,$string,1,NOW(),'".$_SESSION['username']."' )";
				if ($conn->query($query) === TRUE) {
					echo "1";
					mysqli_autocommit($conn,TRUE);
				}
				else {
				if (!$mysqli_commit($conn)) 
				echo "0";
				exit;
				}
			}
		mysqli_autocommit($conn,FALSE);
	}
	$conn->close(); 
?>
