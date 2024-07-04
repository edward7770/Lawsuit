
<?php 
	
	/////$language=$_SESSION['lang'];
	/////include_once('config/conn.php');
	
	/// Parameters InsertNotifications(name,id,date,expiryDatePassNo) 
	/////InsertNotifications('customer', "1", "2024-01-01","2024-01-01");
		
	function InsertNotifications($nameTable, $id, $expiryDate,$expiryDatePassNo)
	{
		if($nameTable!="customer" && $nameTable!="employee" && $nameTable!="contract")
		{
			return 'invalid Parameter name';
		}
		if(empty($nameTable) || empty($id) || empty($expiryDate))
		{
			if($nameTable=='employee' && empty($expiryDatePassNo))
				return 'invalid Parameters';
			else 
				return 'invalid Parameters';
		}
		
		$url="";
		$description_en="";
		$description_ar="";
		$date=$expiryDate;
		$isSeen="";
		
		if($nameTable=='customer')
		{
			////$tableName='tbl_customers';
			$qry="SELECT c.`endDateAgency` as date FROM tbl_customers c WHERE c.`isActive`=1 AND c.`customerId`=:id";
			$url="Customer.php";
			$description_en="End date Agency updated on ";
			$description_ar="تاريخ الانتهاء تم تحديث الوكالة بتاريخ";
		}
		else if($nameTable=='employee')
		{
			///$tableName='tbl_employees';
			$qry="SELECT e.expiryDate  as date, expiryDatePassNo FROM tbl_employees e WHERE e.`isActive`=1 AND e.`empId`=:id";
			$url="employees.php";
		}
		else if($nameTable=='contract')
		{
			///$tableName='tbl_customers';
			$qry="SELECT e.`contractDateTo` AS date FROM `tbl_emp_contracts` e WHERE e.`isActive`=1 AND e.`empContractId`=:id";
			$url="EmpContract.php";
			$description_en="Contract updated on ";
			$description_ar="تم تحديث تاريخ العقد بتاريخ";
		}
				
		$stmt=$GLOBALS['dbo']->prepare($qry);
		$stmt->bindParam(":id",$id,PDO::PARAM_STR);
		if($stmt->execute())
		{
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
		if($result)
		{
			$dateResult=$result[0]['date'];
			if($nameTable=='employee')
			{
				$expiryDatePassNoResult=$result[0]['expiryDatePassNo'];
				if($expiryDate!=$dateResult)
				{
					$description_en.="Id updated on";
					$description_ar.="تم تحديث الرقم المدني بتاريخ";
					$isInsert=true;
					$insert=insertRecord($id,$url,$description_en,$description_ar,$isSeen,$date);
				}
				if($expiryDatePassNo!=$expiryDatePassNoResult)
				{
					$description_en="Passport updated on";
					$description_ar="تم تحديث جواز السفر بتاريخ";
					$insert=insertRecord($id,$url,$description_en,$description_ar,$isSeen,$expiryDatePassNo);
				}
				if(isset($insert))
				{
					return $insert;
					
				}
				else 
					return 'noNeedToUpdate';
			}
			else 
			{
				if($expiryDate!=$dateResult)
				{
					return insertRecord($id,$url,$description_en,$description_ar,$isSeen,$date);
				}
				else 
				{
					return 'noNeedToUpdate';
				}
			}
		}
		else 
		{
			return 'no record found';
		}
	}
	
	function insertRecord($id,$url,$description_en,$description_ar,$isSeen,$date)
	{
		$insertQuery="
			INSERT INTO tbl_notification
				(
				 id,
				 url,
				 description_en,
				 description_ar,
				 isActive,
				 isSeen,
				 date,
				 generateType,
				 createdDate
				 )
			VALUES(
					:id,
					:url,
					:description_en,
					:description_ar,
					1,
					:isSeen,
					:date,
					2,
					now()
					)";
		$stmt=$GLOBALS['dbo']->prepare($insertQuery);
		$stmt->bindParam(":id",$id,PDO::PARAM_INT);
		$stmt->bindParam(":url",$url,PDO::PARAM_STR);
		$stmt->bindParam(":description_en",$description_en,PDO::PARAM_STR);
		$stmt->bindParam(":description_ar",$description_ar,PDO::PARAM_STR);
		$stmt->bindParam(":isSeen",$isSeen,PDO::PARAM_NULL);
		$stmt->bindParam(":date",$date,PDO::PARAM_STR);
		if($stmt->execute())
		{
			return 'inserted';
			////echo get_lang_msg('deleted_successfully');
			/////exit('1');
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			errorMessage($json =$errorInfo[2]);
		}
	}
	
?>
