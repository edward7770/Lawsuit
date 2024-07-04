<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	INNER JOIN `tbl_pagemenu` m ON m.`pageId`=r.`menuId`
	WHERE m.`pageName`=:pageName"; 
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
	?>

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('assets'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" id='addButton' ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('addNewAssets'); ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
	
		<?php 
			$top=10;
			$left=50;
			include_once('loader.php'); 
			?>
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
										<th><?php echo set_value('assetsCategory'); ?></th>
										<th><?php echo set_value('assetsSubCategory'); ?></th>												   
										<th><?php echo set_value('supplier'); ?></th>												   
										<th><?php echo set_value('assetsAmount'); ?></th>												   
										<th><?php echo set_value('taxValueAmount'); ?></th>												   
										<th><?php echo set_value('amountWithTax'); ?></th>												   
										<th><?php echo set_value('assetsDate'); ?></th>												   
										<th><?php echo set_value('depreciationRate'); ?></th>
										<th><?php echo set_value('quantity'); ?></th>
										<th><?php echo set_value('location'); ?></th>
										<th><?php echo set_value('remarks'); ?></th>
									</tr>
								</thead>
								<tbody > </tbody>
								
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
					<h4 class="modal-title"><?php echo set_value('addNewAssets'); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="catId" class="form-label"><?php echo set_value("assetsCategory"); ?><span class="text-danger"> <span class="text-danger"> * </span> </span></label>
									<select class="form-control js-example-basic-single form-small select" id="catId" required>
										<option value=""> <?php echo set_value('select'); ?></option>
										<?php include('dropdown_assetCategory.php'); ?>
									</select>
									
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="subCatId" class="form-label"><?php echo set_value("assetsSubCategory"); ?> <span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id="subCatId" required>
										<option value=""> <?php echo set_value('select'); ?></option>
									</select>
									<input type='hidden' id='getSelect' value="<?php echo set_value('select'); ?>" >
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="supplier" class="form-label"><?php echo set_value('supplier'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="supplier" placeholder="<?php echo set_value('supplier'); ?>" required>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="amount" class="form-label"><?php echo set_value('assetsAmount'); ?> <span class="text-danger"> * </span></label>
									<input type="number" class="form-control form-control-sm" id="amount" step="0.001" placeholder="<?php echo set_value('assetsAmount'); ?>" required>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="taxValue" class="form-label"><?php echo set_value("taxValue"); ?></label>
									<input type="number" class="form-control form-control-sm" id="taxValue" step="0.001" value="5" required>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="taxValueAmount" class="form-label"><?php echo set_value("taxValueAmount"); ?></label>
									<input type="number" class="form-control form-control-sm" id="taxValueAmount" step="0.001" required>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="mb-4">
								<div class="form-group">
									<label for="amountWithTax" class="form-label"><?php echo set_value("amountWithTax"); ?>%</label>
										<input type="text" class="form-control form-control-sm" id="amountWithTax" step="0.001" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['totalContractAmount']; ?>"  step="0.01" value="0.00" disabled >
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="date" class="form-label"><?php echo set_value('assetsDate'); ?><span class="text-danger"> * </span></label>
									<input type="date" class="form-control form-control-sm" id="date" placeholder="dd/mm/yyyy" required onkeydown="return false;">
								</div>
							</div>
						</div>
					
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="deprRate" class="form-label"><?php echo set_value('depreciationRate'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="deprRate" placeholder="<?php echo set_value('depreciationRate'); ?>" required>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="quantity" class="form-label"><?php echo set_value('quantity'); ?> <span class="text-danger"> * </span></label>
									<input type="number" class="form-control form-control-sm" id="quantity" placeholder="<?php echo set_value('quantity'); ?>" required>
								</div>
							</div>
						</div>
					</div>
					<div class="row">	
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="location" class="form-label"><?php echo set_value('location'); ?></label>
									<input type="text" class="form-control form-control-sm" id="location" placeholder="<?php echo set_value('location'); ?>">
								</div>
							</div>
						</div>
						<div class="col-md-8">
							<div class="mb-4">
								<div class="form-group">
									<label for="remarks" class="form-label"><?php echo set_value('remarks'); ?></label>
									<input type="text" class="form-control form-control-sm" id="remarks" placeholder="<?php echo set_value('remarks'); ?>">
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
					<h3><?php echo set_value('deleteAssets'); ?></h3>
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

<?php ///// include_once('MessageModalShow.php'); ?>
<?php include_once('footer.php'); ?>
<script src='js_custom/asset.js'></script>
