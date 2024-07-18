<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once ('header.php');
include_once ('config/conn.php');
$language = $_SESSION['lang'];

$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
$pageName2 = "Lawsuit";
$qry = "SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName` IN(:pageName,:pageName2)";
$stmt = $dbo->prepare($qry);
$stmt->bindParam(":pageName", $pageName, PDO::PARAM_STR);
$stmt->bindParam(":pageName2", $pageName2, PDO::PARAM_STR);
if ($stmt->execute()) {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $errorInfo = $stmt->errorInfo();
    exit($json = $errorInfo[2]);
}
////print_r($result);
function set_value($val)
{
    foreach ($GLOBALS['result'] as $value) {
        if (trim($value['phrase']) == trim($val)) {
            return $value['VALUE'];
            break;
        }
    }

}

/////include('get4setCurrency.php');
?>
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
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="content-page-header">
                <h5><?php echo set_value('tax_report'); ?></h5>
            </div>
        </div>
        <!-- /Page Header -->

        <?php
        $top = 10;
        $left = 50;
        include_once ('loader.php');
        ?>

        <!-- <div class="row">

            <div class="col-lg-4 col-sm-6 col-12">
                <div class="bg-info-light">
                    <div class="card-body">

                        <div class="dash-widget-header">
                            <span class="inovices-widget-icon ">
                                <img src="assets/img/icons/receipt-item.svg" alt="">
                            </span>
                            <div class="dash-count">
                                <div class="dash-title"><?php echo set_value('received_tax'); ?></div>
                                <div class="dash-counts">
                                    <p id="totalAmount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-12">

                <div class="bg-green-light">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="inovices-widget-icon ">
                                <img src="assets/img/icons/message-edit.svg" alt="">
                            </span>
                            <div class="dash-count">
                                <div class="dash-title"><?php echo set_value('paid_tax'); ?></div>
                                <div class="dash-counts">
                                    <p id="paidAmount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4 col-sm-6 col-12">
                <div class="bg-warning-light">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="inovices-widget-icon ">
                                <img src="assets/img/icons/archive-book.svg" alt="">
                            </span>
                            <div class="dash-count">
                                <div class="dash-title"><?php echo set_value('payable_tax'); ?></div>
                                <div class="dash-counts">
                                    <p id="dueAmount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="card-table mt-3">
            <div class="card-body">
                <div class="table-responsive mb-5">
                    <table class="table table-center table-hover datatable" id="example">
                        <thead class="thead-light">
                            <tr>
                                <th><?php echo set_value('action'); ?></th>
                                <th>#</th>
                                <th><?php echo set_value('lsMasterCode'); ?></th>
                                <th><?php echo set_value('customer'); ?></th>
                                <th><?php echo set_value('lawsuitLawyer'); ?></th>
                                <th><?php echo set_value('lawsuits_Type'); ?></th>
                                <th><?php echo set_value('state'); ?></th>
                                <th><?php echo set_value('stage'); ?></th>
                                <th><?php echo set_value('noOfStages'); ?></th>
                                <th><?php echo set_value('paidStatus'); ?></th>
                                <th><?php echo set_value('totalAmount'); ?></th>
                                <th><?php echo set_value('paidAmount'); ?></th>
                                <th><?php echo set_value('dueAmount'); ?></th>
                                <th><?php echo set_value('paymentStatus'); ?></th>
                            </tr>
                        </thead>
                        <tbody id='setData'> </tbody>

                    </table>
                </div>
            </div>
            <h6><?php echo set_value('expense'); ?></h6>
            <div class="table-responsive mb-5">
                <table class="table table-center table-hover datatable" id='setExpenseData'>
                    <thead class="thead-light">
                        <tr>
                            <th><?php echo set_value('action'); ?></th>
                            <th>#</th>
                            <th><?php echo set_value('expenseCategory'); ?></th>
                            <th><?php echo set_value('lsMasterCode'); ?></th>
                            <th><?php echo set_value('supplier'); ?></th>
                            <th><?php echo set_value('expenseAmount'); ?></th>
                            <th><?php echo set_value('taxValueAmount'); ?></th>
                            <th><?php echo set_value('amountWithTax'); ?></th>
                            <th><?php echo set_value('expenseDate'); ?></th>
                            <th><?php echo set_value('expenseMode'); ?></th>
                            <th><?php echo set_value('remarks'); ?></th>
                        </tr>
                    </thead>
                    <tbody id='setData'> </tbody>

                </table>
            </div>
            <h6><?php echo set_value('income'); ?></h6>
            <div class="table-responsive">
                <table class="table table-center table-hover datatable" id='setIncomeData'>
                    <thead class="thead-light">
                        <tr>
                            <th><?php echo set_value('action'); ?></th>
                            <th>#</th>
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
                    <tbody id='setData'> </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- /Page Wrapper -->

<!-- New Stage Items Modal -->
<div class="modal custom-modal fade" id="newStage_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3><?php echo set_value('newStage'); ?></h3>
                    <p><?php echo set_value('areYouSureWantToCreate'); ?>?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="submit" data-bs-dismiss="modal"
                                class="w-100 btn btn-primary paid-continue-btn" id="yesButton"
                                onclick="newStage()"><?php echo set_value('yes'); ?></button>
                        </div>
                        <div class="col-6">
                            <button type="submit" data-bs-dismiss="modal"
                                class="w-100 btn btn-primary paid-cancel-btn"><?php echo set_value("no"); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Items Modal -->

<?php include_once ('modals/LawsuitPaymentMasterDetailModal.php'); ?>


<?php //// include_once('MessageModalShow.php'); ?>

<!-- /Main Wrapper -->

<!-- sample modal content -->


<?php include_once ('footer.php');
?>

<script src="js_custom/TaxReport.js"> </script>
<script>
    $(document).ready(function () {
        ////$('#newStage_modal').modal('toggle');
        //// $('#LawsuitMasterDetailModal').modal('toggle');
    });
</script>