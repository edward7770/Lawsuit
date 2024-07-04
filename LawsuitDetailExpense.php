<?php
	if(!isset($_POST['lsDId']) || empty($_POST['lsDId']))
	exit('<script>window.location.replace("Payment.php")</script>');
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
	print_r($_POST);
	
?>

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">
		
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header ">
				<h5> <?php echo set_value('LSExpenseList'); ?></h5>
				
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			
			<div class="col-md-3">
				<?php include_once('getLawsuitDetailsData.php'); ?>
			</div>
			
			<div class="col-md-9">
				
				<div class="row">
					<div class="col-lg-4 col-sm-6 col-12">
						<div class="bg-info-light">
							<div class="card-body">
								
								<div class="dash-widget-header">
									<span class="inovices-widget-icon ">
										<img src="assets/img/icons/receipt-item.svg" alt="">
									</span>
									<div class="dash-count">
										<div class="dash-title"><?php echo set_value('totalExpense'); ?></div>
										<div class="dash-counts">
											<p id="totalAmount"></p>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<?php 
						$top=10;
						$left=50;
						include_once('loader.php'); 
					?>
					<div class="page-header">
						<div class="content-page-header">
							
							<div class="list-btn">
								<ul class="filter-list">
									
									<li>
										<a class="btn btn-primary" id='addButton' ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i> <?php echo set_value("addNewExpense"); ?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12">
						<div class="card-table"> 
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-stripped table-hover datatable" id='setData'>
										<thead class="thead-light">
											<tr>
												<!-- class="text-end"-->
												<th><?php echo set_value('action'); ?></th>
												<th>#</th>
												<th><?php echo set_value('expenseCategory'); ?></th>
												<th><?php echo set_value('expenseSubCategory'); ?></th>
												<th><?php echo set_value('expenseDate'); ?></th>
												<th><?php echo set_value('expenseMode'); ?></th>
												<th><?php echo set_value('expenseAmount'); ?></th>												   
												<th><?php echo set_value('remarks'); ?></th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>
								<input type='hidden' id="lsDId" value="<?php echo $_POST['lsDId']; ?>" >
							</div>
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
					<h4 class="modal-title"><?php echo set_value('addNewExpense'); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="catId" class="form-label"><?php echo set_value("expenseCategory"); ?><span class="text-danger"> <span class="text-danger"> * </span> </span></label>
									<select class="form-control js-example-basic-single form-small select" id="catId" required>
										<option value=""> <?php echo set_value('select'); ?></option>
										<?php include('dropdown_expenseCategory.php'); ?>
									</select>
									
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="subCatId" class="form-label"><?php echo set_value("expenseSubCategory"); ?> <span class="text-danger"> * </span></label>
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
									<label for="date" class="form-label"><?php echo set_value('expenseDate'); ?><span class="text-danger"> * </span></label>
									<input type="date" class="form-control form-control-sm" id="date" placeholder="dd/mm/yyyy" required onkeydown="return false;">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="mode" class="form-label"><?php echo set_value('expenseMode'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="mode" placeholder="<?php echo set_value('paymentMode'); ?>" required>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="amount" class="form-label"><?php echo set_value('expenseAmount'); ?> <span class="text-danger"> * </span></label>
									<input type="number" class="form-control form-control-sm" id="amount" placeholder="<?php echo set_value('paymentAmount'); ?>" required>
								</div>
							</div>
						</div>
						</div>
					<div class="row">	
						<div class="col-md-8">
							<div class="mb-4">
								<div class="form-group">
									<label for="remarks" class="form-label"><?php echo set_value('remarks'); ?><span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="remarks" placeholder="<?php echo set_value('remarks'); ?>" required>
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
					<h3><?php echo set_value('deleteExpense'); ?></h3>
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
<script>
	$( document ).ready(function() {
		getData();
		$('#catId').select2({
        dropdownParent: $('#addModal')
		});
		$('#subCatId').select2({
			dropdownParent: $('#addModal')
		});
		
	});	
	$(document).ajaxStart(function() {
		$("#ajax_loader").show();
		$('#submit').prop("disabled", true);
	})
	
	.ajaxStop(function() {
		$("#ajax_loader").hide();
		$('#submit').prop("disabled", false);		
	});
	
	function getData()
	{
		var myTable = $('#setData').DataTable();
		var rows = myTable.rows().remove().draw();
		$.ajax({
			type:"POST",
			url: "LawsuitDetailExpenseData.php",
			data:{ lsDId:$('#lsDId').val() },
			success: function (data) {
				///console.log(data);
				if (!$.trim(data) == '') {
					data = data.replace(/^\s*|\s*$/g, '');
					data = data.replace(/\\r\\n/gm, '');
					var expr = "</tr>\\s*<tr";
					var regEx = new RegExp(expr, "gm");
					var newRows = data.replace(regEx, "</tr><tr");
					$("#setData").DataTable().rows.add($(newRows)).draw();
					getExpenseData();
				}
				else $('#totalAmount').val('0');
			}
		});
	}
	
	$("body").on("click","#addButton",function(){
		$("#form")[0].reset();
		$('#id').val("0");
		$('#addModal').modal('toggle');
		getCategory('0');
		$('#catId').val('').change();
		$('#subCatId').val('').change();
	});
	
	function add()
	{
		var catId=$('#catId').val();
		var subCatId=$('#subCatId').val();
		var date=$('#date').val();
		var mode=$('#mode').val();
		var amount=$('#amount').val();
		var remarks=$('#remarks').val();
		var lsDId=$('#lsDId').val();
		var id=$('#id').val();
		if(!catId || !subCatId || !date || !mode || !amount || !remarks || !id || !lsDId)
		{
			showMessage('Invalid Input');
			return;
		}
		if(id>0)
		var action="edit";
		else 
		var action="add";
		
		$.ajax({
			type:"POST",
			url: "LawsuitDetailExpenseDB.php",
			data: {
				action:action,id:id,catId:catId,subCatId:subCatId,date:date,
				mode:mode,amount:amount,remarks:remarks,lsDId:lsDId
			},
			success: function (data) {
				$('#addModal').modal('toggle');
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
	
	function edit(id)
	{
		$.ajax({
			type:"POST",
			url: "LawsuitDetailExpenseDB.php",
			data: {
				action:'getData',id:id
			},
			success: function (data) {
				console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#id').val(this.id);
						$('#date').val(this.date);
						$('#mode').val(this.mode);
						$('#amount').val(this.amount);
						$('#remarks').val(this.remarks);
						$('#catId').val(this.catId).change();
						getSubCategory(this.subCatId)
					});
					$('#addModal').modal('toggle');
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
			url: "LawsuitDetailExpenseDB.php",
			data: {action:'del',id:id},
			success: function (data) {
				getData();
				showMessage(data);
				$('#msg_modal').modal('toggle');
			}
		});
	}
	function getExpenseData()
	{
		var lsDId=$('#lsDId').val();
		$.ajax({
			type:"POST",
			url: "LawsuitDetailExpenseData.php",
			data: {
				getExpenseData:'1',lsDId:lsDId
			},
			success: function (data) {
				console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#totalAmount').html(this.totalExpense);
					});
				}
			}
		});
	}
	

function getCategory(catId)
{
	var getSelect=$('#getSelect').val();
	$.ajax({
		type:"POST",
		url: "dropdown_expenseCategory.php",
		data: { getSelect:getSelect },
		success: function (data) {
			////console.log(data);
			$('#catId').html(data);
		},
		complete: function (data) {
			if(catId>0)
			$('#catId').val(catId).change();
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

/////$("#catId").change(function(event) {console.log(event,event.target,event.currentTarget,event.srcElement)

$(document).on('change', '#catId', function (e) {
	getSubCategory('0');
});
function getSubCategory(setSubCatId)
{
	var getSelect=$('#getSelect').val();
	var catId=$('#catId').val();
	$.ajax({
		type:"POST",
		url: "dropdown_expenseSubCategory.php",
		data: { catId:catId,getSelect:getSelect },
		success: function (data) {
			console.log(data);
			$('#subCatId').html(data);
		},
		complete: function (data) {
			if(setSubCatId>0)
			$('#subCatId').val(setSubCatId).change();
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
	
</script>
