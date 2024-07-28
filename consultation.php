<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	///$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
	$pageName = 'consultationAdd';
	
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
	////echo set_value("add_new_customer");
	
	
?>

<style>
	.green-color {
	color:green;
    }
    .red-color {
	color:red;
    }
	

	
	
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('consultationList'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<!--<li>
							<a class="btn btn-primary" onclick="add();" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php ////echo set_value('addNewConsultation'); ?></a>
						</li> -->
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
										<th><?php echo set_value('customer'); ?></th>
										<th><?php echo set_value('lawsuitLawyer'); ?></th>
										<th><?php echo set_value('titleConsultation'); ?></th>
										<th><?php echo set_value('ContractDate'); ?></th>
										<!-- <th><?php echo set_value('amountContract'); ?></th>
										<th><?php echo set_value('taxValueAmount'); ?></th>
										<th><?php echo set_value('ContAmountinclTax'); ?></th>
										<th><?php echo set_value('paidStatus'); ?></th> -->
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
<input type='hidden' id="searchId" value="<?php if(isset($_POST['id'])) echo $_POST['id']; else "0"; ?>" />	
<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('deleteConsultation'); ?></h3>
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

<!-- Message Items Modal -->
<div class="modal custom-modal fade" id="msg_detailModal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="standard-modalLabel"><?php echo set_value("contractDetails_$language"); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="modelBody">
				
			</div>
		</div>
	</div>
</div>
<!-- /Message Items Modal -->

<?php include_once('footer.php'); ?>
<script src="js_custom/consultation.js"> </script>

<script>
/*	
$('.datatable tbody').on('click', 'tr', function () {
	///table.$('tr.active').removeClass('active');
	$('.datatable tr').removeAttr('style');
	$(this).css('backgroundColor', '#F3F3F9');
			
});
*/
</script>