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
$language = $_SESSION['lang'];

$pageName = "Income";
$qry = "SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName)";
$stmt = $dbo->prepare($qry);
$stmt->bindParam(":pageName", $pageName, PDO::PARAM_STR);
if ($stmt->execute()) {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
} else {
    $errorInfo = $stmt->errorInfo();
    exit($json = $errorInfo[2]);
}

function set_value($val)
{
    foreach ($GLOBALS['result'] as $value) {
        if (trim($value['phrase']) == trim($val)) {
            return $value['VALUE'];
            break;
        }
    }
}

$min = 0;
$max = 500000;
$randomNumber = rand($min, $max);
$invoiceNumber = str_pad($randomNumber, 6, "0", STR_PAD_LEFT);
$receiptDate = date('n/j/Y');

$totalAmount = 0;
$paidAmount = 0;

$serial = 1;

if (isset($_POST['incomeId'])) {
    $qry_income="SELECT incomeId,incomeTypeId,i.lsMasterId,m.`ls_code`,l.empName_$language AS receivedBy,description,amount,taxValue,taxAmount,totalIncomeAmount,incomeDate,invoiceNumber
	FROM tbl_income i
	LEFT JOIN `tbl_employees` l ON l.`empId`=i.`incomeReceivedBy`
	LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=i.`lsMasterId`
	WHERE i.`isActive`=1 AND incomeId=:incomeId";
    $stmt_income = $dbo->prepare($qry_income);
    $stmt_income->bindParam(":incomeId", $_POST['incomeId'], PDO::PARAM_INT);
    if ($stmt_income->execute()) {
        $result_income = $stmt_income->fetchAll(PDO::FETCH_ASSOC);
        $stmt_income->closeCursor();
    } else {
        $errorInfo = $stmt_income->errorInfo();
        exit($json = $errorInfo[2]);
    }
}
?>

<body>
    <div class="main-wrapper" <?php if ($language === 'ar') echo 'style="direction: rtl"';
                                else echo 'style="direction: ltr"';  ?>>
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
                                <?php echo set_value('income_invoice'); ?>
                            </div>
                            <div class="inv-details">
                                <div class="inv-date">
                                    <?php echo set_value('date'); ?>: <span><?php echo $receiptDate ?></span>
                                </div>
                                <div class="inv-date">
                                    <?php echo set_value('invoice_number'); ?>: <span><?php echo $result_income[0]['invoiceNumber']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-table mt-3 mb-5">
                        <!-- <p class="mb-1"><?php echo set_value('paymentDetails'); ?></p> -->
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="table_width_1">#</th>
                                        <th><?php echo set_value('incomeType'); ?></th>
                                        <th><?php echo set_value('lsMasterCode'); ?></th>
                                        <th><?php echo set_value('description'); ?></th>
                                        <th><?php echo set_value('amount'); ?></th>
                                        <th><?php echo set_value('taxValueAmount'); ?></th>
                                        <th><?php echo set_value('amountWithTax'); ?></th>
                                        <th><?php echo set_value('incomeDate'); ?></th>
                                        <th><?php echo set_value('receivedBy'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($result_income as $value) {
                                        $paidAmount += $value['amount'];
                                    ?>
                                        <tr>
                                            <td> <?php echo $serial; ?> </td>
                                            <td><?php if ($value['incomeTypeId'] == 1) echo 'Lawsuit';
                                                else echo 'General'; ?></td>
                                            <td><?php echo $value['ls_code']; ?></td>
                                            <td><?php echo $value['description']; ?></td>
                                            <td><?php echo setAmountDecimal($value['amount']); ?></td>
                                            <td><?php echo setAmountDecimal($value['taxAmount']); ?></td>
                                            <td><?php echo setAmountDecimal($value['totalIncomeAmount']); ?></td>
                                            <td><?php echo $value['incomeDate']; ?></td>
                                            <td><?php echo $value['receivedBy']; ?></td>
                                        </tr>
                                    <?php
                                        $serial++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
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
    function lang(lang) {
        var condition = navigator.onLine ? "online" : "offline";
        if (condition == 'offline') {
            showMessage('No Internet / Network Connection, please reconnect and try again');
            return;
        }
        var getLan = ($('#lang').val()).trim();
        if (getLan == lang) {
            return false;
        }

        $.ajax({
            type: "POST",
            url: "config/config.php",
            data: {
                lang: lang
            },

            beforeSend: function() {
                $("#ajax_loader").show();
                $('#login').prop("disabled", true);
            },
            success: function(data) {
                ////var datta=data.replace(/\D/g, "");   //Return only numbers from string
                location.reload();
            },
            error: function(jqXHR, exception) {
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
            complete: function(jqXHR, exception) {
                $("#ajax_loader").hide();
                $('#login').prop("disabled", false);
            }
        });
    }
</script>
<script src="js_custom/LawsuitDetailPaymentInvoice.js"></script>