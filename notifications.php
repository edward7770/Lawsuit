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
				<h5><?php echo set_value('notifications'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<!--
							<li>
							<a class="btn btn-primary" onclick="add()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("add_new_customer"); ?></a>
							</li>
							<li>
							<a class="btn btn-success" onclick="customer_type_modal()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('add_new customer_type'); ?></a>
							</li> 
						-->
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
							<table class="table table-center table-hover datatable" id="example">
								<thead class="thead-light">
									<tr>
										<th width="10%"><?php echo set_value('action'); ?></th>
										<th width="6%">#</th>
										<th><?php echo set_value('notificationTitle'); ?></th>
										<th><?php echo set_value('notificationDescription'); ?></th>
										<th><?php echo set_value('date'); ?></th>
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
</div>
<!-- /Page Wrapper -->


<!-- /Customer Modal -->

<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('deleteNotifications'); ?></h3>
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

<?php include_once('footer.php'); 
	
?>

<!--<script src="js_custom/Lawsuit.js"> </script> -->
<script>
	$( document ).ready(function() {
		getData();
	});
	
	function delModal(id)
	{
		$('#delete_modal').modal('toggle');
		$('#del_button').val(id);
	}
	function del()
	{
		var id=$('#del_button').val();
		$.ajax({
			type:"POST",
			url: "notificationsDB.php",
			data: {action:'del',id:id},
			success: function (data) {
				////console.log(data);
				getData();
				$('#delete_modal').modal().hide();
				showMessage(data);
			}
		});
	}
	
	function getData()
	{
		var myTable = $('#example').DataTable();
		var rows = myTable.rows().remove().draw();
		$.ajax({
			type:"POST",
			url: "notificationsData.php",
			success: function (data) {
				/////console.log(data);
				////$('#setData').html(data);
				if (!$.trim(data) == '') {
					data = data.replace(/^\s*|\s*$/g, '');
					data = data.replace(/\\r\\n/gm, '');
					var expr = "</tr>\\s*<tr";
					var regEx = new RegExp(expr, "gm");
					var newRows = data.replace(regEx, "</tr><tr");
					$("#example").DataTable().rows.add($(newRows)).draw();
				 }
			}
		});
	}
	
</script>