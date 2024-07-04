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
<style>
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
				<h5>Customers</h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" onclick="add()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("add_new_customer"); ?></a>
						</li>
						<?php /*
						<li>
							<a class="btn btn-success" onclick="customer_type_modal()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('add_new customer_type'); ?></a>
						</li>  
						 */ ?>
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
										<th>#</th>
										<th><?php echo set_value('action'); ?></th>
										<th><?php echo set_value('customers_types'); ?></th>
										<th><?php echo set_value('customer'); ?></th>
										
										<th><?php echo set_value('crNoIdPassportNo'); ?></th>
										<th><?php echo set_value('crPassportFile'); ?></th>

										<!--<th><?php echo set_value('cr_no'); ?></th>
										<th><?php echo set_value('crCopy'); ?></th>
										<th><?php echo set_value('passport_no'); ?></th>
										<th><?php echo set_value('idCopy'); ?></th> -->
										
										<th><?php echo set_value('email'); ?></th>
										<th><?php echo set_value('mobile_no'); ?></th>
										<th><?php echo set_value('nationality'); ?></th>
										<th><?php echo set_value('address'); ?></th>
										<?php if($language=='ar') $br=25; else $br=9; ?>
										<th><?php echo wordwrap(set_value('end_date_agency'), $br, "<br>", true); ?> </th>
										<th><?php echo set_value('agencyCopy'); ?></th>
										<th><?php echo set_value('created_at'); ?></th>
										<th><?php echo set_value('user'); ?></th>
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
<input type='hidden' id="searchId" value="<?php if(isset($_POST['id'])) echo $_POST['id']; else "0"; ?>" />
<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('delete_Customer'); ?></h3>
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

<!-- /Customer Modal -->

<?php include_once('CustomerModal.php'); ?>

<!-- /Delete Items Modal -->

<?php //// include_once('MessageModalShow.php'); ?>

</div>
<!-- /Main Wrapper -->

<!-- sample modal content -->


<!--<div id="add_customer" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" style="display: none;"> -->
<div class="modal fade" id="add_customer_type" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form id='customer_type' action='javascript:addCustomerType();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("add_new_customer_type"); ?></h4>
				
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-12">
						<div class="mb-4">
							<label for="nameAr" class="form-label"><?php echo set_value("the_name_is_arabic"); ?> *</label>
							<input type="text" class="form-control form-control-sm" id="nameAr" placeholder="<?php echo set_value("the_name_is_arabic"); ?>" required>
						</div>
					</div>
					<div class="col-md-12">
						<div class="mb-4">
							<label for="nameEn" class="form-label"><?php echo set_value('the_name_is_english'); ?> *</label>
							<input type="text" class="form-control form-control-sm" id="nameEn" placeholder="<?php echo set_value("the_name_is_english"); ?>" required>
						</div>
					</div>
				</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('addition'); ?>" />
				</div> 
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->
<?php include_once('footer.php'); ?>
<script src="js_custom/Customer.js"> </script>
<script src="js_custom/imageuploadDoc.js"> </script>
