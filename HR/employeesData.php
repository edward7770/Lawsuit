
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	include_once('../config/conn.php');
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	function errorMessage($errorInfo)
	{
		///echo $errorInfo;
		echo get_lang_msg('errorMessage');
		die;
	}
	
	$qry="SELECT empId, empName_en,empName_ar,c.categoryName,empNo,joinDate,phoneNo,mobileNo,dob,cn.countryName_$language as countryName,religion,gender,
		idNo,expiryDate,issueDate,passportNo,issueDatePassNo,expiryDatePassNo,email,e.isActive ,e.createdDate, e.createdBy
		FROM tbl_employees e
		LEFT JOIN tbl_emp_category c ON c.empCatid=e.empCatId
		LEFT JOIN `tbl_country` cn ON cn.`countryId`=e.`nationalityId`
		WHERE e.isActive<>-1";
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		errorMessage($json =$errorInfo[2]);
	}
	$serial=1;
	$countryName="countryName_".$language;
	////print_r($result);
	$checkButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
	$crossButton='<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
	
	foreach($result as $value)
	{ ?>
	<tr>
		<td> <?php echo $serial; ?> </td>
		<td class="d-flex align-items-center">
			<a href="#" class="btn-action-icon me-2" onclick="edit(<?php echo $value['empId']; ?>);"><span><i class="fe fe-edit"></i></span></a>
			<a href="#" class="btn-action-icon" onclick="delModal(<?php echo $value['empId']; ?>);"><span><i class="fe fe-trash-2"></i></span></a>
		</td>
		<td><?php echo $value['empName_ar']; ?></td>
		<td><?php echo $value['empName_en']; ?></td>
		<td><?php echo $value['categoryName']; ?></td>
		
		<td><?php echo $value['empNo']; ?></td>
		<td><?php echo $value['joinDate']; ?></td>
		<td><?php echo $value['phoneNo']; ?></td>
		<td><?php echo $value['mobileNo']; ?></td>
		<td><?php echo $value['dob']; ?></td>
		<td><?php echo $value['gender']; ?></td>
		<td><?php echo $value['countryName']; ?></td>
		<td><?php echo $value['religion']; ?></td>
		<td><?php echo $value['idNo']; ?></td>
		<td><?php echo $value['issueDate']; ?></td>
		<td><?php echo $value['expiryDate']; ?></td>
		<td><?php echo $value['passportNo']; ?></td>
		<td><?php echo $value['expiryDatePassNo']; ?></td>
		<td><?php echo $value['expiryDatePassNo']; ?></td>
		
		<td><?php echo $value['email']; ?></td>
		<td><?php if($value['isActive']) echo $checkButton; else echo $crossButton; ?></td>
		<td><?php echo $value['createdDate']; ?></td>
		<td><?php echo $value['createdBy']; ?></td>
	</tr>
	
	<?php 
		$serial++;
	}
?>
