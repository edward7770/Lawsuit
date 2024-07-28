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
$pageName2 = "Payroll";
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

if (isset($_POST['type']) && $_POST['type'] == "Remaining") {
    $qry_data = "call sp_get_remaining_emp_payroll(:month,:year)";
    $stmt_data = $dbo->prepare($qry_data);
    $stmt_data->bindParam(":month", $_POST['month'], PDO::PARAM_INT);
    $stmt_data->bindParam(":year", $_POST['year'], PDO::PARAM_INT);
    if ($stmt_data->execute()) {
        $result_data = $stmt_data->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errorInfo = $stmt_data->errorInfo();
        exit($json = $errorInfo[2]);
    }
} else if (isset($_POST['type']) && $_POST['type'] == "Generated") {
    $qry_data = "call sp_get_generatedPayroll(:month,:year)";
    $stmt_data = $dbo->prepare($qry_data);
    $stmt_data->bindParam(":month", $_POST['month'], PDO::PARAM_INT);
    $stmt_data->bindParam(":year", $_POST['year'], PDO::PARAM_INT);
    if ($stmt_data->execute()) {
        $result_data = $stmt_data->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errorInfo = $stmt_data->errorInfo();
        exit($json = $errorInfo[2]);
    }
}

$min = 0;
$max = 500000;
$randomNumber = rand($min, $max);
$reportedNumber = str_pad($randomNumber, 6, "0", STR_PAD_LEFT);
$reportedDate = date('n/j/Y');

$serial = 1;
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
                                <?php echo set_value('payroll_report'); ?>
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
                            <h6><?php echo set_value('payment'); ?></h6>
                            <div class="card-body mb-4">
                                <div class="table-responsive" id='setData_payment'>
                                    <div class="table-responsive">
                                        <table class="table table-center table-hover datatable" id="example">
                                            <thead class="thead-light">
                                                <tr>
                                                    <?php
                                                    if (isset($_POST['type']) && $_POST['type'] == "Remaining") {
                                                    ?>
                                                        <th>#</th>
                                                        <th><?php echo set_value('employeeName'); ?></th>
                                                        <th><?php echo set_value('employeecategory'); ?></th>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <th>#</th>
                                                        <th><?php echo set_value('employeeName'); ?></th>
                                                        <th><?php echo set_value('employeecategory'); ?></th>
                                                        <th class="table_header_min_width_90"><?php echo set_value('basicSalary'); ?></th>
                                                        <th class="table_header_min_width_90"><?php echo set_value('allowance'); ?></th>
                                                        <th class="table_header_min_width_90"><?php echo set_value('grossSalary'); ?></th>
                                                        <th class="table_header_min_width_90"><?php echo set_value('deduction'); ?></th>
                                                        <th class="table_header_min_width_90"><?php echo set_value('netPayment'); ?></th>
                                                    <?php
                                                    }
                                                    ?>

                                                </tr>
                                            </thead>
                                            <tbody id='setData'>
                                                <?php

                                                foreach ($result_data as $value) {
                                                    if (isset($_POST['type']) && $_POST['type'] == "Remaining") {
                                                ?>
                                                        <tr>
                                                            <td> <?php echo $serial; ?> </td>
                                                            <td><?php echo $value['empName_' . $language]; ?></td>
                                                            <td><?php echo $value['categoryName']; ?></td>
                                                        </tr>

                                                    <?php
                                                        $serial++;
                                                    } else {
                                                    ?>
                                                        <tr>
                                                            <td> <?php echo $serial; ?> </td>
                                                            <td><?php echo $value['empName_' . $language]; ?></td>
                                                            <td><?php echo $value['categoryName']; ?></td>
                                                            <td><?php echo setAmountDecimal($value['basicSalary']); ?></td>
                                                            <td><?php echo setAmountDecimal($value['allow']); ?></td>
                                                            <td><?php echo setAmountDecimal($value['basicSalary'] + $value['allow']); ?></td>
                                                            <td><?php echo setAmountDecimal($value['deduct']); ?></td>
                                                            <td><?php echo setAmountDecimal(($value['basicSalary'] + $value['allow']) - $value['deduct']); ?></td>
                                                        </tr>
                                                <?php
                                                $serial++;
                                                    }
                                                } ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
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