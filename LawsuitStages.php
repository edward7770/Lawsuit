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
	////echo set_value("add_new_customer");
	
?>

<style>
	
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('stage'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" onclick="add()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('create_stage'); ?></a>
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
										<th>#</th>
										<th><?php echo set_value('action'); ?></th>
										<th><?php echo set_value('name_ar'); ?></th>
										<th><?php echo set_value('name_en'); ?></th>
										<th><?php echo set_value('created_at'); ?></th>
										<th><?php echo set_value('user'); ?></th>
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

<!-- Add Asset customer-->
<div class="toggle-sidebar">
	<div class="sidebar-layout-filter">
		<div class="sidebar-header">
			<h5>Filter</h5>
			<a href="#" class="sidebar-closes"><i class="fa-regular fa-circle-xmark"></i></a>
		</div>
		<div class="sidebar-body">						
			<form action="#" autocomplete="off">
				<!-- Customer -->
				<div class="accordion" id="accordionMain1">
					<div class="card-header-new" id="headingOne">
						<h6 class="filter-title">
							<a href="javascript:void(0);" class="w-100" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								Customer	
								<span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
							</a> 
						</h6>
					</div>
				</div>
				<!-- /Customer -->
				
				<!-- Select Date -->
				<div class="accordion" id="accordionMain2">
					<div class="card-header-new" id="headingTwo">
						<h6 class="filter-title">
							<a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
								Select Date	
								<span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
							</a> 
						</h6>
					</div>
					
					<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"  data-bs-parent="#accordionExample2">
						<div class="card-body-chat">
							<div class="form-group">
								<label class="form-control-label">From</label>
								<div class="cal-icon">
									<input type="email" class="form-control datetimepicker" placeholder="DD-MM-YYYY">
								</div>
							</div>
							<div class="form-group">
								<label class="form-control-label">To</label>
								<div class="cal-icon">
									<input type="email" class="form-control datetimepicker" placeholder="DD-MM-YYYY">
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Select Date -->
				
				<button type="submit" class="d-inline-flex align-items-center justify-content-center btn w-100 btn-primary">
					<span><img src="assets/img/icons/chart.svg" class="me-2" alt="Generate report"></span>Generate report
				</button>
			</form>
			
		</div>
	</div>
</div>	
<!--/Add Asset -->

<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('delete_stage'); ?></h3>
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

<?php include_once('MessageModalShow.php'); ?>


</div>
<!-- /Main Wrapper -->

<!-- sample modal content -->
<div class="modal fade" id="add_customer_type" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form id='customer_type' action='javascript:addCustomerType();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("create_stage"); ?></h4>
				
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
				<input type='hidden' id='id' />
			</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->



<?php include_once('footer.php'); ?>
<script>
$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
	})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});
	
	
function addCustomerType()
{
	var nameEn=$('#nameEn').val();
	var nameAr=$('#nameAr').val();
	var id=$('#id').val();
	if(nameEn=='' && nameAr=='')
	{
		showMessage('Invalid Input');
		return;
	}
	if(id>0)
		var action="update";
	else 
		var action="add";
	$.ajax({
		type:"POST",
		url: "LawsuitStagesDB.php",
		data: { action:action,nameAr:nameAr,nameEn:nameEn,id:id },
		success: function (data) {
			////console.log(data);
			$("#customer_type")[0].reset();
			$('#add_customer_type').modal('toggle');
			showMessage(data);
			getData();
		},
		error: function (jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert("Not connect.\n Verify Network");
			} else if (jqXHR.status == 404) {
				alert("Requested page not found. [404]");
			} else if (jqXHR.status == 500) {
				alert("Internal Server Error [500]");
			} else if (exception === 'parsererror') {
				alert("Requested JSON parse failed.");
			} else if (exception === 'timeout') {
				alert("Time out error.");
			} else if (exception === 'abort') {
				alert("Ajax request aborted");
			}
		}
	}); 
}

$( document ).ready(function() {
    getData();
});

function getData()
{
	var myTable = $('#setData').DataTable();
	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "LawsuitStagesData.php",
		success: function (data) {
			if (!$.trim(data) == '') {
				data = data.replace(/^\s*|\s*$/g, '');
				data = data.replace(/\\r\\n/gm, '');
				var expr = "</tr>\\s*<tr";
				var regEx = new RegExp(expr, "gm");
				var newRows = data.replace(regEx, "</tr><tr");
				$("#setData").DataTable().rows.add($(newRows)).draw();
			}
		}
	});
}
function add()
{
	$("#customer_type")[0].reset();
	$('#id').val("0");
	$('#add_customer_type').modal('toggle');
}

function edit(id)
{
	$.ajax({
		type:"POST",
		url: "LawsuitStagesDB.php",
		data: {
				action:'getData',id:id
		},
		success: function (data) {
			////console.log(data);
			/////alert(JSON.stringify(data));
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#id').val(this.id);
					$('#nameAr').val(this.lsStagesName_ar);
					$('#nameEn').val(this.lsStagesName_en);
					
				});
				$('#add_customer_type').modal('toggle');
			}
			else 
			{
				////alert();
			}
		}
	});
}
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
		url: "LawsuitStagesDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			/////$('#delete_modal').modal('toggle');
			$('#delete_modal').modal().hide();
			showMessage(data);
			getData();
		}
	});
}

</script>