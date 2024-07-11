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
	<!-- <style media="print">
        @media print {
            .reference_no_record {
                width: 50px !important;
            }
            
            td, th {
                width: 100px;
            }
        }
    </style> -->
</head>
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

    $qry_lawsuitData="CALL sp_getLawsuitDetails_Summary('".$language."',".$_POST['type'].",".$_POST['state'].",".$_POST['stage'].") ";
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

    $min = 0;
	$max = 500000;
	$randomNumber = rand($min, $max);
	$reportedNumber = str_pad($randomNumber, 6, "0", STR_PAD_LEFT);
    $reportedDate = date('n/j/Y');
    $serial=1;
    $serial_session=1;
	$lsDetailsId=0;

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
								<?php echo set_value('lawsuit_report'); ?>
							</div>
			               	<div class="inv-details">
			               		<div class="inv-date">
								   <?php echo set_value('date'); ?>: <span><?php echo($reportedDate) ?></span>
								</div>
								<div class="inv-date">
									<?php echo set_value('report_number'); ?>: <span><?php echo($reportedNumber) ?></span>
								</div>
			               	</div>	
		                </div>					    
				    </div>
				    <!-- <div class="invoice-address">
						<h6 class="company-name mt-3"><?php echo set_value('company_name'); ?></h6>
				    	<div class="company-details d-flex flex-wrap mt-4">
							<div class="gst-details col-6 d-flex">
				    			<b><?php echo set_value('lawsuits_Type'); ?></b>: <span>&nbsp;<?php if($_POST['type'] == -1) echo "-"; else echo $_POST['type']; ?></span>
				    		</div>
				    		<div class="gst-details col-6 d-flex">
								<b><?php echo set_value('state'); ?></b>: <span>&nbsp;<?php if($_POST['state'] == -1) echo "-"; else echo $_POST['state']; ?></span>
				    		</div>
							<div class="gst-details col-6 d-flex">
								<b><?php echo set_value('stage'); ?></b>: <span>&nbsp;<?php if($_POST['stage'] == -1) echo "-"; else echo $_POST['stage']; ?></span>
				    		</div>
				    		<div class="gst-details col-6 d-flex">
								<b><?php echo set_value('reportType'); ?></b>: <span>&nbsp;<?php if($_POST['reportType'] == -1) echo "-"; else echo $_POST['reportType']; ?></span>
				    		</div>
				    	</div>				    	
				    </div> -->
				    <div class="invoice-table mt-3 mb-5">
						<p class="mb-1"><?php echo set_value('paymentDetails'); ?></p>
				    	<div class="table-responsive">
			                <table>
								<thead>
									<tr>
										<th class="table_width_1">#</th>
										<th ><?php echo set_value('lsMasterCode'); ?></th>
										<!-- <th class="reference_no_record"><?php echo set_value('referenceNo'); ?></th> -->
										<th><?php echo set_value('lawsuitId'); ?></th>
										<th class="reference_no_record"><?php echo set_value('customer'); ?></th>
										<th><?php echo set_value('opponent'); ?></th>												   
										<th><?php echo set_value('state'); ?></th>
										<th><?php echo set_value('stage'); ?></th>
										<th><?php echo set_value('lawsuitDate'); ?></th>
									</tr>
								</thead>
			                  <tbody>
									<?php 
										foreach($result_lawsuitData as $i=> $value)
                                        {
                                            if($lsDetailsId!=$value['lsDetailsId'])
                                            {
                                                    $lsDetailsId=$value['lsDetailsId'];
                                                    $rowspan=0;
                                                    if($_POST['reportType']=="detailed" && $value['lsDetailsId_Session']>0)
                                                    {
                                                        $rowspan=getCountSessions($lsDetailsId);
                                                    }
                                                
                                                ?>
                                                <tr>
                                                    <td> <?php echo $serial; ?> </td>
                                                    <td><?php echo $value['ls_code']; ?></td>
                                                    <!-- <td class="reference_no_record"> <?php echo $value['empName_'.$language]; ?> </td> -->
                                                    <td> <?php echo $value['lawsuitId']; ?> </td>
                                                    <td class="reference_no_record"> <?php echo $value['customerName']; ?> </td>
                                                    <td> <?php echo $value['oppoName']; ?> </td>
                                                    <!-- <td> <?php echo $value['oppoName']; ?> </td> -->
                                                    <td> <?php echo $value['lsStateName_'.$language]; ?> </td>
                                                    <td> <?php echo $value['lsStagesName_'.$language]; ?> </td>
                                                    <td> <?php
                                                        if(!empty($value['lsDate']))
                                                        {
                                                            $displayDate="displayDate_$language";
                                                            echo  $displayDate( $value['lsDate']);
                                                        }
                                                    ?>
                                                    </td>
                                                </tr>    
                                                <?php 
                                                ///break;
                                                $serial++;
                                            }
                                        }
									?>
			                  </tbody>
			                </table>			               
			            </div>
				    </div>
                    <?php 
                        if($_POST['reportType']=="detailed"	) {
                    ?>
                        <div class="invoice-table mt-3 mb-5">
						<p class="mb-1"><?php echo set_value('contractDetails'); ?></p>
				    	<div class="table-responsive">
			                <table>
								<thead>
									<tr>
										<th class="table_width_1">#</th>
                                        <th><?php echo set_value('lsMasterCode'); ?></th>
                                        <th> <?php echo set_value('sessions'); ?></th>
                                        <th> <?php echo set_value('dateSession'); ?></th>
                                        <th> <?php echo set_value('timeSession'); ?></th>
									</tr>
								</thead>
                                <tbody>
									<?php 
										foreach($result_lawsuitData as $i=> $value)
                                        {
                                            if($lsDetailsId!=$value['lsDetailsId'])
                                            {
                                                    $lsDetailsId=$value['lsDetailsId'];
                                                    $rowspan=0;
                                                    if($_POST['reportType']=="detailed" && $value['lsDetailsId_Session']>0)
                                                    {
                                                        $rowspan=getCountSessions($lsDetailsId);
                                                    }
                                                
                                                ?>
                                                    <?php 
                                                        if($rowspan>0)
                                                        {
                                                        ?>

                                                            <?php 
                                                                foreach($result_lawsuitData as $i=> $innerValue)
                                                                {
                                                                    if($lsDetailsId==$innerValue['lsDetailsId_Session'])
                                                                    { ?>
                                                                        <tr>
                                                                            <td> <?php echo $serial_session; ?> </td>
                                                                            <td> <?php echo $innerValue['ls_code']; ?> </td>
                                                                            <td> <?php echo $innerValue['sessionName']; ?> </td>
                                                                            <td> <?php echo $innerValue['sessionDate']; ?></td>
                                                                            <td> <?php echo $innerValue['sessionTime']; ?></td>
                                                                        </tr>
                                                                    <?php 
                                                                    $serial_session++;
                                                                    }
                                                                }
                                                        ?>
                                                        <?php 
                                                        } ?>
                                                        
                                                <?php 
                                                ///break;
                                            }
                                        }
									?>
			                  </tbody>
			                </table>			               
			            </div>
				    </div>
                    <?php
                        }
                    ?>
                    
				</div>												
		    </div>
			<input type="hidden" value=<?php echo $language; ?> id="lang" />
			<input type='hidden' id="lsDId" value="<?php echo $_POST['lsDId']; ?>" >
			<input type='hidden' id="lsMId" value="<?php echo $_POST['lsMId']; ?>" >
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