<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<!--<title>Kanakku - Bootstrap Admin HTML Template</title>  -->

	<!-- Favicon -->
	<link rel="shortcut icon" href="assets/img/favicon.png">
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">

	 <!-- Fearther CSS -->
	<link rel="stylesheet" href="assets/css/feather.css">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
	
	<!-- Main CSS -->
	<link rel="stylesheet" href="assets/css/style.css">
	
</head>
<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	// include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$pageName = "LawsuitDetailPayment";
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName)"; 
	$stmt=$dbo->prepare($qry);
	$stmt->bindParam(":pageName",$pageName,PDO::PARAM_STR);
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
	// if(isset($_POST['id']))
	// 	$lsPaymentId=$_POST['id'];
	// else 
	// 	$lsPaymentId=12;

	// $query = $con->query("SELECT lp.*, lm.*, c.*, ld.lsSubject, ld.lawsuitId FROM tbl_lawsuit_payment lp, tbl_lawsuit_master lm, tbl_lawsuit_details ld, tbl_lawsuit_customers lc, tbl_customers C WHERE lp.lsPaymentId = $lsPaymentId AND lp.lsMasterId = lm.lsMasterId AND lc.lsDetailsId = lm.lsDetailId AND lc.customerId = c.customerId AND ld.lsDetailsId = lm.lsDetailId AND lp.isActive='1'");
	// $result = $query->fetch_assoc();
	// print_r($result);

	if(isset($_POST['lsDid']))
	{
		$query="CALL sp_getLawsuitDetails('" . $language . "'," . $_SESSION['customerId'] . ",-1,-1,-1)";
		$stmt_lawsuitdetails=$dbo->prepare($query);
		// $stmt_lawsuitdetails->bindParam(":lsDetailId",$_POST['lsDid'],PDO::PARAM_INT);
		if($stmt_lawsuitdetails->execute())
		{
			$resultLawsuitDetails = $stmt_lawsuitdetails->fetchAll(PDO::FETCH_ASSOC);
			// $customerArray=explode (",", $resultLawsuitDetails[0]['custName']);
			// $opponentArray=explode (",", $resultLawsuitDetails[0]['OpponentsName']);
			$stmt_lawsuitdetails->closeCursor();
		}
		else 
		{
			$errorInfo = $stmt_lawsuitdetails->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	// print_r($resultLawsuitDetails);
	$min = 0;
	$max = 500000;
	$randomNumber = rand($min, $max);
	$invoiceNumber = str_pad($randomNumber, 6, "0", STR_PAD_LEFT);
	$invoiceDate = date('n/j/Y');

	$totalAmount = 0;
	$paidAmount = 0;

	$serial=1;
	$serial_contract=1;
	if(isset($_POST['lsMId'])) {
		$qry_getpaymentdata="SELECT lsPaymentId, m.`ls_code`, s.lsStagesName_$language as lsStagesName , d.`lawsuitId`, e.empName_$language as empName_$language,
			DATE_FORMAT(paymentDate,'%d-%b-%y') paymentDate, pm.name_$language as paymentMode, amount, remarks,
			(CASE WHEN IFNULL(m.`isPaidAll`,0)=0 THEN 'Current Stage' ELSE 'Full Stages' END) 
				paymentStatus_en,
			(CASE WHEN IFNULL(m.`isPaidAll`,0)=0 THEN 'مرحله واحده' ELSE 'مدفوع جميع المراحل' END) 
				paymentStatus_ar 
			FROM tbl_lawsuit_payment l 
			LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=l.`lsMasterId`
			LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=l.`lsStageId`
			LEFT JOIN `tbl_lawsuit_details` d ON d.`lsMasterId`=m.`lsMasterId`
			LEFT JOIN `tbl_lawsuit_lawyer` ll ON ll.`lsDetailId`=d.`lsDetailsId`
			LEFT JOIN `tbl_employees` e ON e.`empId`=ll.`empId`
			LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=l.`paymentMode`
			WHERE l.isActive=1 AND m.`isActive`=1
			AND d.`lsMasterId`=:lsMasterId GROUP BY l.`lsPaymentId` ";
		$stmt_getpaymentdata=$dbo->prepare($qry_getpaymentdata);
		$stmt_getpaymentdata->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_INT);
		if($stmt_getpaymentdata->execute())
		{
			$result_paymentdata = $stmt_getpaymentdata->fetchAll(PDO::FETCH_ASSOC);
			$stmt_getpaymentdata->closeCursor();
		}
		else 
		{
			$errorInfo = $stmt_getpaymentdata->errorInfo();
			exit($json =$errorInfo[2]);
		}


		$qry_getcontractdata="SELECT c.`lsContractId`, c.`lsMasterId`,m.`ls_code`,`lsStageId`, s.lsStagesName_$language as lsStagesName,`amount`, `taxValue`, taxAmount, `totalAmount`, `contractEn`, `contractAr`, `contractFilePath`, c.`isActive`, c.`remarks` FROM `tbl_lawsuit_contract` c 
		LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=c.`lsMasterId`
		LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=c.`lsStageId`
		WHERE c.`isActive`=1 AND c.`lsMasterId`=:lsMasterId";
		$stmt_getcontractdata=$dbo->prepare($qry_getcontractdata);
		$stmt_getcontractdata->bindParam(":lsMasterId",$_POST['lsMId'],PDO::PARAM_INT);
		if($stmt_getcontractdata->execute())
		{
			$result_contactdata = $stmt_getcontractdata->fetchAll(PDO::FETCH_ASSOC);
			$stmt_getcontractdata->closeCursor();
		}
		else 
		{
			$errorInfo = $stmt_getcontractdata->errorInfo();
			exit($json =$errorInfo[2]);
		}

		$qry_getlawyerdata ="SELECT m.`phoneNo`, m.`mobileNo`,m.`email`, m.empName_$language as empName FROM `tbl_lawsuit_lawyer` c 
		LEFT JOIN `tbl_employees` m ON m.`empId`=c.`empId`
		WHERE m.`isActive`=1 AND c.`lsDetailId`=:lsDetailId";
		$stmt_getlawyerdata=$dbo->prepare($qry_getlawyerdata);
		$stmt_getlawyerdata->bindParam(":lsDetailId",$_POST['lsDid'],PDO::PARAM_INT);
		if($stmt_getlawyerdata->execute())
		{
			$result_lawyerdata = $stmt_getlawyerdata->fetchAll(PDO::FETCH_ASSOC);
			$stmt_getlawyerdata->closeCursor();
		}
		else 
		{
			$errorInfo = $stmt_getlawyerdata->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
?>

<body>
	<div class="main-wrapper" <?php if ($language === 'ar') echo 'style="direction: rtl"'; else echo 'style="direction: ltr"';  ?>>
		<div class="container">
			<div class="invoice-wrapper download_section">
				<div class="inv-content">
					<div class="invoice-header" style="padding: 20px;">
						<div class="invoice-title" style="color: #2c3038; font-size: 24px; font-weight: 700;"><?php echo set_value('tax_invoice'); ?></div>
					</div>
					<div class="invoice-header">
		                <div class="inv-header-left">
		                	 <a href="#">
		                		<img src="assets/img/logo2.png" alt="Logo">
		                	</a> 
		                </div>
		                <div class="inv-header-right">
		                	<!-- <div class="invoice-title">
								<?php echo set_value('tax_invoice'); ?>
							</div> -->
			               	<div class="inv-details" style="display: block;">
			               		<div class="inv-date" style="margin-right: 0px;">
								   <?php echo set_value('date'); ?>: <span><?php echo $_POST['invoiceDate'] ?></span>
								</div>
								<div class="inv-date">
									<?php echo set_value('invoice_number'); ?>: <span><?php echo $_POST['invoiceNumber']; ?></span>
								</div>
			               	</div>	
		                </div>					    
				    </div>
					<div class="mt-2" <?php if ($language === 'ar') echo 'style="margin-right: 30px;"'; else echo 'style="margin-left: 30px;"';  ?>>
						<h6 class="company-name mt-3"><?php echo set_value('company_name'); ?></h6>
					</div>
				    <div class="invoice-address">
						<!-- <h6 class="company-name mt-3"><?php echo($result_lawyerdata[0]['empName']) ?></h6> -->
						<!-- <div class="company-details d-flex flex-wrap mt-2">
							<div class="gst-details col-4 d-flex">
								<b>Lawyer Email</b>: <span>&nbsp;<?php echo $result_lawyerdata[0]['email']; ?></span>
				    		</div>
							<div class="gst-details col-4 d-flex">
								<b>Phone No</b>: <span>&nbsp;<?php echo $result_lawyerdata[0]['phoneNo']; ?></span>
				    		</div>
							<div class="gst-details col-4 d-flex">
								<b>Mobile No</b>: <span>&nbsp;<?php echo $result_lawyerdata[0]['mobileNo']; ?></span>
				    		</div>
						</div> -->
						<!-- <hr/> -->
				    	<div class="company-details d-flex flex-wrap">
							<div class="gst-details col-6 d-flex">
				    			<b><?php echo set_value('lsMasterCode'); ?></b>: <span style="color: red;">&nbsp;<?php echo $resultLawsuitDetails[0]['ls_code']; ?></span>
				    		</div>
				    		<div class="gst-details col-6 d-flex">
								<b><?php echo set_value('employeeName'); ?></b>: <span>&nbsp;<?php echo($resultLawsuitDetails[0]['empName_' . $language]); ?></span>
				    		</div>
							<div class="gst-details col-6 d-flex">
								<b><?php echo set_value('lawsuitId'); ?></b>: <span>&nbsp;<?php if(empty($resultLawsuitDetails[0]['lawsuitId'])) echo "-"; else echo $resultLawsuitDetails[0]['lawsuitId']; ?></span>
				    		</div>
				    		<div class="gst-details col-6 d-flex">
								<b><?php echo set_value('type'); ?></b>: <span>&nbsp;<?php echo $resultLawsuitDetails[0]['lsTypeName_'.$language]; ?></span>
				    		</div>
							<!-- <div class="gst-details col-3 d-flex">
								<b>State</b>: <span style="color:<?php echo $resultLawsuitDetails[0]['lsColor']; ?>">&nbsp;<?php echo $resultLawsuitDetails[0]['lsStateName_'.$language]; ?></span>
				    		</div>
							<div class="gst-details col-3 d-flex">
								<b>Stage</b>: <span>&nbsp;<?php echo $resultLawsuitDetails[0]['lsStagesName_'.$language]; ?></span>
				    		</div>
							<div class="gst-details col-3 d-flex">
								<b>Subject</b>: <span>&nbsp;<?php if(!empty($resultLawsuitDetails[0]['lsSubject'])) echo $resultLawsuitDetails[0]['lsSubject']; else echo '-'; ?></span>
				    		</div> -->
							<div class="gst-details col-6 mb-0 d-flex">
								<b><?php echo set_value('customer'); ?></b>:<span>&nbsp;<?php echo $resultLawsuitDetails[0]['customerName']; ?></span>
				    		</div>
							<div class="gst-details col-6 mb-0 d-flex">
								<b><?php echo set_value('opponent'); ?></b>:<span>&nbsp;<?php echo $resultLawsuitDetails[0]['oppoName']; ?></span>
				    		</div>
							<div class="gst-details col-6 mb-0 d-flex">
								<b><?php echo set_value('vat_number'); ?></b>:<span>&nbsp;<?php if(!empty($resultLawsuitDetails[0]['vatNumber'])) echo $resultLawsuitDetails[0]['vatNumber']; else echo '-'; ?></span>
				    		</div>
				    	</div>
				    </div>
				    
					<div class="invoice-table mt-3">
						<p class="mb-1"><?php echo set_value('contractDetails'); ?></p>
				    	<div class="table-responsive">
			                <table>
								<thead>
									<tr>
										<th class="table_width_1">#</th>
										<th><?php echo set_value('stage'); ?></th>
										<th><?php echo set_value('paymentAmount'); ?></th>
										<th><?php echo set_value('taxValueAmount'); ?></th>
										<th><?php echo set_value('contractAmountIncludingTax'); ?></th>
										<th><?php echo set_value('remarks'); ?></th>
									</tr>
								</thead>
			                  <tbody>
									<?php 
										foreach ($result_contactdata as $value) {
											$totalAmount += $value['totalAmount'];
											?>
												<tr>
													<td> <?php echo $serial_contract; ?> </td>
													<td><?php echo $value['lsStagesName']; ?></td>
													<td><?php echo setAmountDecimal($value['amount']); ?></td>
													<td><?php echo setAmountDecimal($value['taxAmount']); ?></td>
													<td><?php echo setAmountDecimal($value['totalAmount']); ?></td>
													<td><?php echo $value['remarks']; ?></td>
												</tr>
											<?php
											$serial_contract++;
										}
									?>
			                  </tbody>
			                </table>			               
			            </div>
				    </div>
				    <div class="invoice-table-footer">
				    	<div class="table-footer-left"></div>
				    	<div class="text-end table-footer-right">
			                <table>
								<tbody>
									<tr>
									  <td style="'color: black;"><b style="font-size: 18px;"><?php echo set_value('totalAmount'); ?></b>:</td>
									  <td style="'color: black;"><span style="font-size: 18px;"><?php echo $totalAmount; ?></span></td>
									</tr>				                    
				                   <!-- <tr>
				                      <td style="'color: black !important;"><b style="font-size: 18px;"><?php echo set_value('paidAmount'); ?></b>:</td>
				                      <td style="'color: black;"><span style="font-size: 18px;"><?php echo $paidAmount; ?></span></td>
				                    </tr>
				                    <tr>
				                      <td style="'color: black;"><b style="font-size: 18px;"><?php echo set_value('dueAmount'); ?></b>:</td>
				                      <td style="'color: black;"><span style="font-size: 18px;"><?php echo $totalAmount - $paidAmount; ?></span></td>
				                    </tr>		 -->
				                </tbody>
				            </table>
			            </div> 
				    </div>
					<div class="invoice-address" style="margin-top: 150px; display:block;">
						<p style="font-size: 24px;"><b><?php echo set_value('officeName'); ?></b></p>
						<p class="mt-3" style="font-size: 18px;"><?php echo set_value('signature'); ?></p>
						<p style="font-size: 18px; text-align:right;"><?php echo set_value('receivedBy'); ?></p>
					</div>
				    <!-- <div class="invoice-table-footer mb-5">
			            <div class="table-footer-left" style="opacity: 0">       
                            <p class="total-info">Total Items / Qty : 4 / 4.00</p>
			            </div>
			            <div class="table-footer-right">       
                            <table class="totalamt-table">
				                <tbody>
				                   <tr>
				                      <td><?php echo set_value('totalAmount'); ?></td>
				                      <td><?php echo $totalAmount; ?></td>
				                    </tr>				                    				                    
				                </tbody>
				            </table>
			            </div>			                           	
			        </div> -->
			        <!-- <div class="total-amountdetails">
			        	<p>Total amount ( in words): <span>$  One Thousand Six Hundred Fifteen  Only.</span></p>
			        </div> -->
			        <!-- <div class="bank-details">
			        	<div class="account-info">
			        		<span class="bank-title">Bank Details</span>
			        		<div class="account-details">
			        			Bank : <span>YES Bank</span>
			        		</div>
			        		<div class="account-details">
			        			Account # :<span> 6677889944551 </span>
			        		</div>
			        		<div class="account-details">
			        			IFSC :  <span>YESBBIN4567</span>
			        		</div>
			        		<div class="account-details">
			        			BRANCH : <span>Newyork</span>
			        		</div>
			        	</div>
			        	<div class="company-sign">
			        		<span>For Dreamguys</span>
			        		<img src="assets/img/signature.png" alt="">
			        	</div>
			        </div>
			        <div class="terms-condition">
						<span>Terms and Conditions:</span>
						<ol>
							<li> Goods Once sold cannot be taken back or exchanged</li>
	                        <li> We are not the manufactures, company will stand for warrenty as per their terms and conditions.</li>
						</ol>
					</div>	
					<div class="thanks-msg text-center">
						Thanks for your Business
					</div> -->
				</div>												
		    </div>
			<input type="hidden" value=<?php echo $language; ?> id="lang" />
			<?php /*
			<div class="file-link">
				<button class="download_btn download-link">         
					<i class="feather-download-cloud me-1"></i> <span>Download</span>
				</button>
				<a href="javascript:window.print()" class="print-link">         
					<i class="feather-printer"></i>  <span class="">Print</span>
				</a>
			</div> 
			*/
			?>
		</div>
	</div>    
	<!-- jQuery -->
	<script src="assets/js/jquery-3.6.3.min.js"></script>

	<!-- Bootstrap Core JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>

	<!-- Fearther JS -->
	<script src="assets/js/feather.min.js"></script>

	<script src="assets/js/jspdf.min.js"></script>
   
    <!-- Canvas JS -->
	<script src="assets/js/html2canvas.min.js"></script>
	
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<!-- Custom JS -->
	<script src="assets/js/script.js"></script>
	
</body>
</html>

<script>
function lang(lang)
{
	var condition = navigator.onLine ? "online" : "offline";
	if( condition == 'offline' ){
		showMessage('No Internet / Network Connection, please reconnect and try again');
		return;
	}
	var getLan=($('#lang').val()).trim();
	if(getLan==lang)
	{
		return false;
	}

	$.ajax({
		type:"POST",
		url: "config/config.php",
		data: {lang:lang},
		
		beforeSend: function()
		{
			$("#ajax_loader").show();
			$('#login').prop("disabled", true);
		},
		success: function (data) {
			////var datta=data.replace(/\D/g, "");   //Return only numbers from string
			location.reload();
		},
		error: function (jqXHR, exception) {
			if (jqXHR.status === 0) {
				showMessage("Not connect.\n Verify Network");
				} else if (jqXHR.status == 404) {
				showMessage("Requested page not found. [404]");
				} else if (jqXHR.status == 500) {
				showMessage("Internal Server Error [500]");
				} else if (exception === 'parsererror') {
				showMessage("Requested JSON parse failed.");
				} else if (exception === 'timeout') {
				showMessage("Time out error.");
				} else if (exception === 'abort') {
				showMessage("Ajax request aborted");
			}
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		},
		complete: function (jqXHR, exception) {
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		}
	}); 
}

</script>
<script src="js_custom/LawsuitDetailPaymentInvoice.js"></script>