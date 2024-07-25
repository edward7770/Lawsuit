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
    <style>
        @media print {
            .table_header_min_width_180 {
                padding: 8px 0px !important;
                min-width: 180px !important;
                text-overflow: ellipsis !important;
                white-space: pre-wrap !important;
            }

            .table_header_min_width_120 {
                padding: 8px 0px !important;
                min-width: 120px !important;
                text-overflow: ellipsis !important;
                white-space: pre-wrap !important;
            }

            .table_header_min_width_90 {
                padding: 8px 0px !important;
                min-width: 80px !important;
                text-overflow: ellipsis !important;
                white-space: pre-wrap !important;
            }

            .table_header_min_width_70 {
                padding: 8px 0px !important;
                min-width: 70px !important;
                text-overflow: ellipsis !important;
                white-space: pre-wrap !important;
            }
        }
    </style>
    <style>
        .table-responsive .dropdown,
        .table-responsive .btn-group,
        .table-responsive .btn-group-vertical {
            position: static;
        }
    </style>
    <style>
        .green-color {
            color: green;
        }

        .red-color {
            color: red;
        }
    </style>
</head>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// include_once('header.php'); 
include_once('config/conn.php');
$language = $_SESSION['lang'];

$pageName = "Lawsuit";
$pageName2 = "LawsuitDetail";
$qry = "SELECT l.`phrase`, $language AS VALUE FROM `language` l
LEFT JOIN languagepageref r ON r.languageid=l.`id`
INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
WHERE m.`pageName` IN(:pageName,:pageName2)";
$stmt = $dbo->prepare($qry);
$stmt->bindParam(":pageName", $pageName, PDO::PARAM_STR);
$stmt->bindParam(":pageName2", $pageName2, PDO::PARAM_STR);
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

//get payment data
$qry_payment = "CALL sp_getLawsuitDetails('" . $language . "'," . $_SESSION['customerId'] . ",-1,-1,-1)";
$stmt_payment = $dbo->prepare($qry_payment);
if ($stmt_payment->execute()) {
    $result_payment = $stmt_payment->fetchAll(PDO::FETCH_ASSOC);
    $stmt_payment->closeCursor();
} else {
    $errorInfo = $stmt_payment->errorInfo();
    exit($json = $errorInfo[2]);
}

//get expense data
$qry_expense = "SELECT expenseId,e.`expCatId` AS catId,m.`ls_code`,expenseDate,supplier, pm.name_$language AS expenseMode,amount,taxValue, taxAmount,totalExpAmount,remarks
    FROM tbl_expense e
    LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=e.`lsMasterId`
    LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=e.`expenseMode` AND pm.`isActive`=1
    WHERE e.`isActive`=1";
$stmt_expense = $dbo->prepare($qry_expense);
/////$stmt_expense->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
if ($stmt_expense->execute()) {
    $result_expense = $stmt_expense->fetchAll(PDO::FETCH_ASSOC);
} else {
    $errorInfo = $stmt_expense->errorInfo();
    exit($json = $errorInfo[2]);
}

//get income data
$qry_income = "SELECT incomeId,incomeTypeId,i.lsMasterId,m.`ls_code`,l.empName_$language AS receivedBy,description,amount,taxValue,taxAmount,totalIncomeAmount,incomeDate
    FROM tbl_income i
    LEFT JOIN `tbl_employees` l ON l.`empId`=i.`incomeReceivedBy`
    LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=i.`lsMasterId`
    WHERE i.`isActive`=1";
$stmt_income = $dbo->prepare($qry_income);
/////$stmt_income->bindParam(":lsDetailsId",$_POST['lsDId'],PDO::PARAM_INT);
if ($stmt_income->execute()) {
    $result_income = $stmt_income->fetchAll(PDO::FETCH_ASSOC);
} else {
    $errorInfo = $stmt_income->errorInfo();
    exit($json = $errorInfo[2]);
}

$total_payment = 0;
$total_income = 0;
$total_expense = 0;

$min = 0;
$max = 500000;
$randomNumber = rand($min, $max);
$reportedNumber = str_pad($randomNumber, 6, "0", STR_PAD_LEFT);
$reportedDate = date('n/j/Y');

$serial_payment = 1;
$serial_expense = 1;
$serial_income = 1;
?>

<body <?php if ($language === 'ar') echo 'style="direction: rtl"';
        else echo 'style="direction: ltr"';  ?>>
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
                                <?php echo set_value('accounting_report'); ?>
                            </div>
                            <div class="inv-details">
                                <div class="inv-date">
                                    <?php echo set_value('date'); ?>: <span><?php echo ($reportedDate) ?></span>
                                </div>
                                <div class="inv-date">
                                    <?php echo set_value('report_number'); ?>: <span><?php echo ($reportedNumber) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 mb-5">
                        <div class="card-table" style="padding-left: 15px; padding-right: 15px;">
                            <?php
                            if ($_POST['payment_option'] === '' || $_POST['payment_option'] === 'payment') {
                            ?>
                                <h6><?php echo set_value('payment'); ?></h6>
                                <div class="card-body mb-4">
                                    <div class="table-responsive" id='setData_payment'>
                                        <div class="table-responsive">
                                            <table class="table table-center table-hover datatable" id="example">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="table_header_min_width_90"><?php echo set_value('lsMasterCode'); ?></th>
                                                        <th class="table_header_min_width_180"><?php echo set_value('customer'); ?></th>
                                                        <!-- <th class="table_header_min_width_180"><?php echo set_value('lawsuitLawyer'); ?></th>
                                                        <th><?php echo set_value('lawsuits_Type'); ?></th>
                                                        <th><?php echo set_value('paidStatus'); ?></th> -->
                                                        <th class="table_header_min_width_90"><?php echo set_value('totalAmount'); ?></th>
                                                        <th class="table_header_min_width_90"><?php echo set_value('paidAmount'); ?></th>
                                                        <!-- <th class="table_header_min_width_90"><?php echo set_value('dueAmount'); ?></th> -->
                                                        <th class="table_header_min_width_90"><?php echo set_value('paymentDate'); ?></th>
                                                        <th><?php echo set_value('paymentStatus'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody id='setData'>
                                                    <?php
                                                    $typeName = "lsTypeName_" . $language;
                                                    $stateName = "lsStateName_" . $language;
                                                    $stagesName = "lsStagesName_" . $language;
                                                    $payment = set_value('payment');
                                                    $expense = set_value('expense');
                                                    $history = set_value('history');
                                                    $checkButton = '<a href="#" class="btn-action-icon"><span><i class="fa fa-check fa-2x green-color"></i></span></a>';
                                                    $crossButton = '<a href="#" class="btn-action-icon"><span><i class="fa fa-times fa-2x red-color"></i></span></a>';
                                                    ////<td class="d-flex align-items-center">
                                                    ////print_r($result);

                                                    $NotPaid = set_value('NotPaid');
                                                    $FullPaid = set_value('FullPaid');
                                                    $OutStanding = set_value('OutStanding');
                                                    $paymentStatusVal = "";

                                                    include('get4setCurrency.php');
                                                    // print_r($result_payment);
                                                    foreach ($result_payment as $i => $value) {
                                                        if ($value['paymentStatus'] == 'FullPaid') {
                                                            $paymentStatus = "bg-success-light";
                                                            $paymentStatusVal = $FullPaid;
                                                        } else if ($value['paymentStatus'] == 'NotPaid') {
                                                            $paymentStatus = "bg-danger-light";
                                                            $paymentStatusVal = $NotPaid;
                                                        } else if ($value['paymentStatus'] == 'OutStanding') {
                                                            $paymentStatus = "bg-warning-light";
                                                            $paymentStatusVal = $OutStanding;
                                                        } else
                                                            $paymentStatus = "";

                                                        $qry_payment_details = "SELECT lsPaymentId, m.`ls_code`, s.lsStagesName_$language as lsStagesName , d.`lawsuitId`,
                                                        paymentDate, pm.name_$language as paymentMode, amount, invoiceNumber, remarks
                                                        FROM tbl_lawsuit_payment l 
                                                        LEFT JOIN `tbl_lawsuit_master` m ON m.`lsMasterId`=l.`lsMasterId`
                                                        LEFT JOIN `tbl_lawsuit_stages` s ON s.`lsStagesId`=l.`lsStageId`
                                                        LEFT JOIN `tbl_lawsuit_details` d ON d.`lsMasterId`=m.`lsMasterId`
                                                        LEFT JOIN `tbl_payment_mode` pm ON pm.`paymentModeId`=l.`paymentMode`
                                                        WHERE l.`isActive`=1 AND m.`isActive`=1
                                                        AND d.`lsMasterId`=:lsMasterId 
                                                        ORDER BY l.`paymentDate` DESC
                                                        LIMIT 1";

                                                        $stmt_payment_details = $dbo->prepare($qry_payment_details);
                                                        $stmt_payment_details->bindParam(":lsMasterId", $value['lsMasterId'], PDO::PARAM_INT);
                                                        if ($stmt_payment_details->execute()) {
                                                            $result_payment_details = $stmt_payment_details->fetchAll(PDO::FETCH_ASSOC);
                                                            if (count($result_payment_details) > 0) {
                                                                if (new DateTime($_POST['from']) <= new DateTime($result_payment_details[0]['paymentDate']) && new DateTime($result_payment_details[0]['paymentDate']) <= new DateTime($_POST['to'])) {
                                                                    $total_payment += $value['totalAmount'];
                                                    ?>
                                                                    <tr>
                                                                        <td> <?php echo $serial_payment; ?> </td>
                                                                        <td> <?php /* <a href="javascript:viewLSDetails(<?php echo $value['lsMasterId'].",".$value['lsDetailsId']; ?>);" ><?php echo $value['ls_code']; ?>  </a> */ echo $value['ls_code']; ?> </td>
                                                                        <td class="table_header_min_width_180"> <?php echo $value['customerName']; ?> </td>
                                                                        <!-- <td class="table_header_min_width_180"> <?php echo $value['empName_' . $language]; ?> </td>
                                                                        <td><?php echo $value[$typeName]; ?></td> -->
                                                                        <!--<td style="background-color:<?php ///echo $value['lsColor']; 
                                                                                                        ?>"><?php ///echo $value[$stateName]; 
                                                                                                            ?></td> -->
                                                                        <!-- <td style="color:<?php echo $value['lsColor']; ?>"><?php echo $value[$stateName]; ?></td>
                                                                        <td><?php echo $value[$stagesName]; ?></td>
                                                                        <td><?php echo $value['noofStages']; ?></td> -->
                                                                        <!-- <td><?php if ($value['isPaid']) echo $checkButton;
                                                                                    else echo $crossButton; ?></td> -->
                                                                        <td class="table_header_min_width_90"><?php echo setAmountDecimal($value['totalAmount']); ?></td>
                                                                        <td class="table_header_min_width_90"><?php echo setAmountDecimal($value['paymentAmount']); ?></td>
                                                                        <!-- <td><?php echo setAmountDecimal($value['dueAmount']); ?></td> -->
                                                                        <td class="table_header_min_width_90"><?php echo $result_payment_details[0]['paymentDate']; ?></td>
                                                                        <td> <span class="badge badge-pill <?php echo $paymentStatus; ?>"><?php echo $paymentStatusVal; ?></span> </td>

                                                                    </tr>
                                                                <?php
                                                                    $serial_payment++;
                                                                }
                                                                ?>
                                                    <?php
                                                            }
                                                            $stmt_payment_details->closeCursor();
                                                        } else {
                                                            $errorInfo = $stmt_payment_details->errorInfo();
                                                            exit($json = $errorInfo[2]);
                                                        }
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
                                                        <td style="'color: black !important; font-size: 16px;"><b><?php echo set_value('total'); ?></b>:</td>
                                                        <td style="'color: black;"><?php echo $total_payment; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if ($_POST['payment_option'] === '' || $_POST['payment_option'] === 'expense') {
                            ?>
                                <h6><?php echo set_value('expense'); ?></h6>
                                <div class="card-body mb-4">
                                    <div class="table-responsive" id='setData_expense'>
                                        <table class="table table-center table-hover datatable" id='setExpenseData'>
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th class="table_header_min_width_90"><?php echo set_value('expenseCategory'); ?></th>
                                                    <th class="table_header_min_width_90"><?php echo set_value('lsMasterCode'); ?></th>
                                                    <th class="table_header_min_width_90"><?php echo set_value('supplier'); ?></th>
                                                    <th class="table_header_min_width_90"><?php echo set_value('expenseAmount'); ?></th>
                                                    <th class="table_header_min_width_90"><?php echo set_value('taxValueAmount'); ?></th>
                                                    <!-- <th class="table_header_min_width_90"><?php echo set_value('amountWithTax'); ?></th> -->
                                                    <th class="table_header_min_width_90"><?php echo set_value('expenseDate'); ?></th>
                                                    <!-- <th class="table_header_min_width_50"><?php echo set_value('expenseMode'); ?></th> -->
                                                    <!-- <th><?php echo set_value('remarks'); ?></th> -->
                                                </tr>
                                            </thead>
                                            <tbody id='setData'>
                                                <?php

                                                foreach ($result_expense as $value) {
                                                    if (new DateTime($_POST['from']) <= new DateTime($value['expenseDate']) && new DateTime($value['expenseDate']) <= new DateTime($_POST['to'])) {
                                                        $total_expense += $value['amount'];
                                                ?>
                                                        <tr>
                                                            <td> <?php echo $serial_expense; ?> </td>
                                                            <td><?php if ($value['catId'] == 1) echo 'Lawsuit';
                                                                else echo 'General Expense'; ?></td>
                                                            <td><?php echo $value['ls_code']; ?></td>
                                                            <td><?php echo $value['supplier']; ?></td>
                                                            <td><?php echo number_format((float)$value['amount'], $decimalplace); ?></td>
                                                            <td><?php echo number_format((float)$value['taxAmount'], $decimalplace); ?></td>
                                                            <!-- <td><?php echo number_format((float)$value['totalExpAmount'], $decimalplace); ?></td> -->
                                                            <td><?php echo $value['expenseDate']; ?></td>
                                                            <!-- <td><?php echo $value['expenseMode']; ?></td> -->
                                                            <!-- <td><?php echo $value['remarks']; ?></td> -->
                                                        </tr>

                                                <?php
                                                        $serial_expense++;
                                                    }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="invoice-table-footer">
                                        <div class="table-footer-left"></div>
                                        <div class="text-end table-footer-right">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td style="'color: black !important; font-size: 16px;"><b><?php echo set_value('total'); ?></b>:</td>
                                                        <td style="'color: black;"><?php echo $total_expense; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if ($_POST['payment_option'] === '' || $_POST['payment_option'] === 'income') {
                            ?>
                                <h6><?php echo set_value('income'); ?></h6>
                                <div class="card-body mb-5">
                                    <div class="table-responsive" id='setData_income'>
                                        <div class="table-responsive">
                                            <table class="table table-center table-hover datatable" id='setIncomeData'>
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <!-- <th class="table_header_min_width_70"><?php echo set_value('incomeType'); ?></th> -->
                                                        <th class="table_header_min_width_120"><?php echo set_value('lsMasterCode'); ?></th>
                                                        <th class="table_header_min_width_70"><?php echo set_value('description'); ?></th>
                                                        <th class="table_header_min_width_70"><?php echo set_value('amount'); ?></th>
                                                        <th class="table_header_min_width_70"><?php echo set_value('taxValueAmount'); ?></th>
                                                        <!-- <th><?php echo set_value('amountWithTax'); ?></th> -->
                                                        <th class="table_header_min_width_70"><?php echo set_value('incomeDate'); ?></th>
                                                        <th class="table_header_min_width_70"><?php echo set_value('receivedBy'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody id='setData'>
                                                    <?php
                                                    foreach ($result_income as $value) {
                                                        if (new DateTime($_POST['from']) <= new DateTime($value['incomeDate']) && new DateTime($value['incomeDate']) <= new DateTime($_POST['to'])) {
                                                            $total_income += $value['amount'];
                                                    ?>
                                                            <tr>
                                                                <td> <?php echo $serial_income; ?> </td>
                                                                <!-- <td><?php if ($value['incomeTypeId'] == 1) echo 'Lawsuit';
                                                                            else echo 'General'; ?></td> -->
                                                                <td><?php echo $value['ls_code']; ?></td>
                                                                <td><?php echo $value['description']; ?></td>
                                                                <td><?php echo setAmountDecimal($value['amount']); ?></td>
                                                                <td><?php echo setAmountDecimal($value['taxAmount']); ?></td>
                                                                <!-- <td><?php echo setAmountDecimal($value['totalIncomeAmount']); ?></td> -->
                                                                <td><?php echo $value['incomeDate']; ?></td>
                                                                <td class="table_header_min_width_70"><?php echo $value['receivedBy']; ?></td>
                                                            </tr>
                                                    <?php
                                                            $serial_income++;
                                                        }
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
                                                        <td style="'color: black !important; font-size: 16px;"><b><?php echo set_value('total'); ?></b>:</td>
                                                        <td style="'color: black;"><?php echo $total_income; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
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