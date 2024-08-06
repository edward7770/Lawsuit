<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once('header.php');
include_once('config/conn.php');
$language = $_SESSION['lang'];
$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

$qry = "SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName`=:pageName";
$stmt = $dbo->prepare($qry);
$stmt->bindParam(":pageName", $pageName, PDO::PARAM_STR);
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
?>

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('incomeList'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" id='addButton'><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('addNewIncome'); ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->

		<?php
		$top = 10;
		$left = 50;
		include_once('loader.php');
		?>
		<div class="row">

			<div class="col-lg-4 col-sm-6 col-12">
				<div class="bg-info-light">
					<div class="card-body">

						<div class="dash-widget-header">
							<span class="inovices-widget-icon ">
								<img src="assets/img/icons/receipt-item.svg" alt="">
							</span>
							<div class="dash-count">
								<div class="dash-title"><?php echo set_value('totalIncome'); ?></div>
								<div class="dash-counts">
									<p id="totalIncome"></p>
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
								<div class="dash-title"><?php echo set_value('monthlyIncome'); ?></div>
								<div class="dash-counts">
									<p id="monthlyIncome"></p>
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
								<div class="dash-title"><?php echo set_value('todayIncome'); ?></div>
								<div class="dash-counts">
									<p id="todayIncome"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="card-table">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-center table-hover datatable" id='setData'>
								<thead class="thead-light">
									<tr>
										<th><?php echo set_value('action'); ?></th>
										<th>#</th>
										<th><?php echo set_value('incomeType'); ?></th>
										<th><?php echo set_value('lsMasterCode'); ?></th>
										<?php /*
										<th><?php echo set_value('customer'); ?></th>												   
										<th><?php echo set_value('lawsuitLawyer'); ?></th>	
										*/ ?>
										<th><?php echo set_value('description'); ?></th>
										<th><?php echo set_value('amount'); ?></th>
										<th><?php echo set_value('taxValueAmount'); ?></th>
										<th><?php echo set_value('amountWithTax'); ?></th>
										<th><?php echo set_value('incomeDate'); ?></th>
										<th><?php echo set_value('invoiceNumber'); ?></th>
										<th><?php echo set_value('receivedBy'); ?></th>
									</tr>
								</thead>
								<tbody> </tbody>

							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Page Wrapper -->

<!-- Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='form' action='javascript:add();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value('addUpdateNewIncome'); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="catId" class="form-label"><?php echo set_value("incomeType"); ?><span class="text-danger"> <span class="text-danger"> * </span> </span></label>
									<select class="form-control js-example-basic-single form-small select" id="catId" required>
										<option value=""> <?php echo set_value('select'); ?></option>
										<option value=1><?php echo set_value('lsMasterCode'); ?> </option>
										<?php /*<option value=1><?php echo set_value('consultations'); ?>  </option> */ ?>
										<option value=2><?php echo set_value('generalExpense'); ?> </option>
									</select>

								</div>
							</div>


						</div>
						<div class="col-md-4" style="display: none" id="divSubCatId">
							<div class="mb-4">
								<div class="form-group">
									<label for="subCatId" class="form-label"><?php echo set_value("lsMasterCode"); ?> <span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id="subCatId" required>
										<option value=""> <?php echo set_value('select'); ?></option>
									</select>
									<input type='hidden' id='getSelect' value="<?php echo set_value('select'); ?>">
								</div>
							</div>
						</div>
						<?php /* 
						<div class="col-md-4" id="div_customer">
							<div class="mb-4">
								<div class="form-group">
									<label for="subCatId" class="form-label"><?php echo set_value("customer"); ?> <span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id="subCatId" required>
										<option value=""> <?php echo set_value('select'); ?></option>
										<?php include('dropdown_customer.php'); ?>
									</select>
									<!--<input type='hidden' id='getSelect' value="<?php ////echo set_value('select'); ?>" > --->
								</div>
							</div>
						</div>
						
						<div class="col-md-4" id="div_lawsuitLawyer">
							<div class="mb-4">
								<div class="form-group">
									<label for="lawyerId" class="form-label"><?php echo set_value('lawsuitLawyer'); ?> <span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id="lawyerId" required>
										<option value=""> <?php echo set_value('select'); ?></option>
										<?php include('dropdown_employee.php'); ?>
									</select>
								</div>
							</div>
						</div>
						*/ ?>
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="date" class="form-label"><?php echo set_value('incomeDate'); ?><span class="text-danger"> * </span></label>
									<input type="date" class="form-control form-control-sm" id="date" placeholder="dd/mm/yyyy" required onkeydown="return false;">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="amount" class="form-label"><?php echo set_value('amount'); ?> <span class="text-danger"> * </span></label>
									<input type="number" class="form-control form-control-sm" id="amount" placeholder="<?php echo set_value('amount'); ?>" step="0.001" required>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="taxValue" class="form-label"><?php echo set_value("taxValue"); ?></label>
									<input type="number" class="form-control form-control-sm" id="taxValue" value="5" step="0.001">
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="taxValueAmount" class="form-label"><?php echo set_value("taxValueAmount"); ?></label>
									<input type="number" class="form-control form-control-sm" id="taxValueAmount" step="0.001" disabled>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="amountWithTax" class="form-label"><?php echo set_value("amountWithTax"); ?></label>
									<input type="text" class="form-control form-control-sm" id="amountWithTax" value="<?php if (isset($resultLawsuit)) echo $resultLawsuit[0]['totalContractAmount']; ?>" step="0.001" value="0.00" disabled>
								</div>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="invoiceNumber" class="form-label"><?php echo set_value('invoiceNumber'); ?></label>
									<input type="text" class="form-control form-control-sm" id="invoiceNumber" placeholder="<?php echo set_value('invoiceNumber'); ?>" required>
								</div>
							</div>
						</div>

						<div class="col-md-8">
							<div class="mb-4">
								<div class="form-group">
									<label for="receivedBy" class="form-label"><?php echo set_value('receivedBy'); ?> <span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id="receivedBy" required>
										<option value=""> <?php echo set_value('select'); ?></option>
										<?php include('dropdown_employee.php'); ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="mb-4">
								<div class="form-group">
									<label for="description" class="form-label"><?php echo set_value('description'); ?></label>
									<input type="text" class="form-control form-control-sm" id="description" placeholder="<?php echo set_value('description'); ?>">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value('close'); ?></button>&nbsp;
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('submit'); ?>" />
					<input type='hidden' value='0' id='id'>
				</div>
			</form>

		</div>
	</div>
</div><!-- /.modal -->
<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('deleteIncome'); ?></h3>
					<p><?php echo set_value('areYouSureWantTodelete?'); ?></p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary paid-continue-btn" id="del_button" onclick="del();"><?php echo set_value('delete'); ?></button>
						</div>
						<div class="col-6">
							<button type="submit" data-bs-dismiss="modal" class="w-100 btn btn-primary paid-cancel-btn"><?php echo set_value("close"); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Delete Items Modal -->

<?php ///// include_once('MessageModalShow.php'); 
?>
<?php include_once('footer.php'); ?>
<script src='js_custom/income.js'></script>