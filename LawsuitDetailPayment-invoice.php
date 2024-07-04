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
	include_once('header.php'); 
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
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	////print_r($result);
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
	if(isset($_POST['id']))
		$lsPaymentId=$_POST['id'];
	else 
		$lsPaymentId=12;


		echo $lsPaymentId.'HELLO 123';

		$query = 	$con->query("SELECT lp.*, lm.*, c.*, ld.lsSubject, ld.lawsuitId FROM tbl_lawsuit_payment lp, tbl_lawsuit_master lm, tbl_lawsuit_details ld, tbl_lawsuit_customers lc, tbl_customers C WHERE lp.lsPaymentId = $lsPaymentId AND lp.lsMasterId = lm.lsMasterId AND lc.lsDetailsId = lm.lsDetailId AND lc.customerId = c.customerId AND ld.lsDetailsId = lm.lsDetailId AND lp.isActive='1'");
	$result = $query->fetch_assoc();
?>

<body>
	<div class="main-wrapper">
		<div class="container">
			<div class="invoice-wrapper download_section">
				<div class="inv-content">
					<div class="invoice-header">
		                <div class="inv-header-left">
		                	 <a href="#">
		                		<img src="assets/img/logo2.png" alt="Logo">
		                	</a> 
		                </div>
		                <div class="inv-header-right">
		                	<div class="invoice-title">
								TAX INVOICE
							</div>
			               	<div class="inv-details">
			               		<div class="inv-date">
									Date: <span>5/12/2022</span>
								</div>
								<div class="inv-date">
									Invoice No: <span>00001</span>
								</div>
			               	</div>	
		                </div>					    
				    </div>
				    <div class="invoice-address">
				    	<div class="invoice-to">
				    		<span>Invoice To:</span>
				    		<div class="inv-to-address">
				    			Walter Roberson<br>
				    			299 Star Trek Drive, Panama City, <br>
				    			Florida, 32405, USA.<br>
				    			walter@example.com <br>
				    			+45 5421 4523
				    		</div>
				    	</div>
				    	<div class="invoice-to">
				    		<span>Pay To:</span>
				    		<div class="inv-to-address">
				    			Lowell H. Dominguez<br> 84 Spilman Street, London<br>
				    			United King<br>
				    			domlowell@example.com<br>
				    			+45 5421 2154
				    		</div>
				    	</div>
				    	<div class="company-details">
				    		<span class="company-name">Dreamguys</span>
				    		<div class="gst-details">
				    			GST IN: <span>22AABCU9603R1ZX</span>
				    		</div>
				    		<div class="gst-details">
				    			Address: <span>5 Hodges Mews, High Wycombe HP12 3JL, United Kingdom</span>
				    		</div>
				    		<div class="gst-details mb-0">
				    			Mobile: <span>+ 91 98765 43210</span>
				    		</div>
				    	</div>				    	
				    </div>
				    <div class="invoice-table">
				    	<div class="table-responsive">
			                <table>
								<thead>
										<tr>
											<th class="table_width_1">#</th>
											<th class="table_width_2">Item</th>
											<th class="table_width_3">Tax Value</th>
											<th class="table_width_1 text-center">Qty</th>
											<th class="table_width_4 text-end">Price</th>
											<th class="table_width_4 text-end">Total</th>
										</tr>
								</thead>
			                  <tbody>
									<tr>
										<td>1</td>	
										<td class=""> Website Design</td>
										<td class="table-description">Four plus web pages design and two rivision</td>
										<td class="text-center">1</td>
										<td class="text-end">$350</td>
										<td class="text-end">$350</td>
									</tr>
									<tr>
										<td>2</td>	
										<td class="table-description">Web Development</td>
										<td class="">Dynamic frontend design</td>
										<td class="text-center">1</td>
										<td class="text-end">$600</td>			                      
										<td class="text-end">$600</td>
									</tr>
									<tr>
										<td>3</td>	
										<td class="">App Development</td>
										<td class="table-description">Android and Ios App design</td>
										<td class="text-center">2</td>
										<td class="text-end">$200</td>			                      
										<td class="text-end">$400</td>
									</tr>
									<tr>
										<td>4</td>	
										<td class="">Digital Marketing</td>
										<td class="table-description">Facebook and instagram marketing</td>
										<td class="text-center">3</td>
										<td class="text-end">$100</td>			                      
										<td class="text-end">$300</td>
									</tr>
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
				                      <td>Taxable Amount</td>
				                      <td>$1650</td>
				                    </tr>
				                    <tr>
				                      <td>GST 18.0% </td>
				                      <td>$165</td>
				                    </tr>				                    
				                </tbody>
				            </table>
			            </div> 
				    </div>
				    <div class="invoice-table-footer">
			            <div class="table-footer-left">       
                            <p class="total-info">Total Items / Qty : 4 / 4.00</p>
			            </div>
			            <div class="table-footer-right">       
                            <table class="totalamt-table">
				                <tbody>
				                   <tr>
				                      <td>Total</td>
				                      <td>$1650</td>
				                    </tr>				                    				                    
				                </tbody>
				            </table>
			            </div>			                           	
			        </div>
			        <div class="total-amountdetails">
			        	<p>Total amount ( in words): <span>$  One Thousand Six Hundred Fifteen  Only.</span></p>
			        </div>
			        <div class="bank-details">
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
					</div>
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