<?php
	if(!isset($_POST['id']) || empty($_POST['id']))
	exit('<script>window.location.replace("consultation.php")</script>');
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	////$pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
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
	if(isset($_POST['id']) && !empty($_POST['id']))
	{
		$qrySide="SELECT consId, cus.customerName_$language as customerName, e.empName_$language as empName ,title,contractDate,amount, tax, totalAmount, notes_$language as notes FROM tbl_consultations c 
		LEFT JOIN tbl_customers cus ON cus.customerId=c.customerId
		LEFT JOIN tbl_employees e ON e.empId=c.lawyerId
		WHERE c.isActive=1 and c.consId=:consId";
		$stmt=$dbo->prepare($qrySide);
		$stmt->bindParam(":consId",$_POST['id'],PDO::PARAM_INT);
		if($stmt->execute())
		{
			$resultConsult = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($resultConsult as $row )
			{
				$contractDate=$row['contractDate'];
				$customerName=$row['customerName'];
				$empName=$row['empName'];
				$title=$row['title'];
			}
		}
		else 
		{
			$errorInfo = $stmt->errorInfo();
			exit($json =$errorInfo[2]);
		}
	}
	
?>

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">
		
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header ">
				<h5> <?php echo set_value('addNewConsultationPayment'); ?></h5>
				
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<ul class="list-unstyled mb-0">
							<li class="py-0">
								<h6><?php echo set_value('ContractDate'); ?></h6>
							</li>
							<li style="color:red">
								<?php echo $contractDate; ?>
							</li>
							<li class="pt-2 pb-0">
								<h6> <?php echo set_value('lawsuitLawyer'); ?> </h6>
							</li>
							<li>
							<?php echo $customerName; ?> </h6>
						</li>
						<li class="pt-2 pb-0">
							<h6> <?php echo set_value('customer'); ?> </h6>
						</li>
						<li>
						<?php echo $empName; ?> </h6>
					</li>
				</ul>
			</div>
		</div>
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
								<div class="dash-title"><?php echo set_value('totalAmount'); ?></div>
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
								<div class="dash-title"><?php echo set_value('paidAmount'); ?></div>
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
								<div class="dash-title"><?php echo set_value('dueAmount'); ?></div>
								<div class="dash-counts">
									<p id="dueAmount"></p>
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
								<a class="btn btn-primary" id='addButton' ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i> <?php echo set_value("addNewPayment"); ?></a>
							</li>
							<li>
								<a class="btn btn-success" id='UpdatePayment' ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('editContractAmount'); ?></a>
							</li>
							
							<li>
								<a class="btn">
									<div class="status-toggle">
										<?php echo set_value('paidStatus'); ?>
										<input id="paidStatus" class="check" type="checkbox">
										<label for="paidStatus" class="checktoggle checkbox-bg">checkbox</label>
									</div>
								</a>
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
										<th><?php echo set_value('paymentDate'); ?></th>
										<th><?php echo set_value('paymentMode'); ?></th>												   
										<th><?php echo set_value('paidAmount'); ?></th>
										<th><?php echo set_value('remarks'); ?></th>
										
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
						<input type='hidden' id="consultId" value="<?php echo $_POST['id']; ?>" >
						<!--<input type='hidden' id="lsMId" value="<?php //////echo $_POST['lsMId']; ?>" > -->
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
<div class="modal fade" id="LawsuitPaymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='form' action='javascript:add();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value('createPayment'); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="date" class="form-label"><?php echo set_value('paymentDate'); ?><span class="text-danger"> * </span></label>
									<input type="date" class="form-control form-control-sm" id="date" placeholder="dd/mm/yyyy" required onkeydown="return false;">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="mode" class="form-label"><?php echo set_value('paymentMode'); ?> <span class="text-danger"> * </span></label>
									<input type="text" class="form-control form-control-sm" id="mode" placeholder="<?php echo set_value('paymentMode'); ?>" required>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="amount" class="form-label"><?php echo set_value('paymentAmount'); ?> <span class="text-danger"> * </span></label>
									<input type="number" class="form-control form-control-sm" id="amount" placeholder="<?php echo set_value('paymentAmount'); ?>" required>
								</div>
							</div>
						</div>
						
						<div class="col-md-12">
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
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('addPayment'); ?>" />
					<input type='hidden' value='0' id='id'>
				</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="LawsuitAmountModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id='form' action='javascript:updateContract();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value('editContractAmount'); ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="amountContract" class="form-label"><?php echo set_value('paymentAmount'); ?> <span class="text-danger"> * </span></label>
									<input type="number" class="form-control form-control-sm" id="amountContract" placeholder="<?php echo set_value('paymentAmount'); ?>" required>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="taxValue" class="form-label"><?php echo set_value("taxValue"); ?>%</label>
									<input type="number" class="form-control form-control-sm" id="taxValue" value="5">
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-4">
								<div class="form-group">
									<label for="contractAmountIncludingTax" class="form-label"><?php echo set_value("contractAmountIncludingTax"); ?>%</label>
									<input type="text" class="form-control form-control-sm" id="contractAmountIncludingTax" value="<?php if(isset($resultLawsuit)) echo $resultLawsuit[0]['totalContractAmount']; ?>"  step="0.01" value="0.00" disabled>
								</div>
							</div>
						</div>
						
					</div>
					
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value('close'); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('update'); ?>" />
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
					<h3><?php echo set_value('deleteConsultatonPayment'); ?></h3>
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
			url: "consultationPaymentData.php",
			data:{ id:$('#consultId').val() },
			success: function (data) {
				console.log(data);
				if (!$.trim(data) == '') {
					data = data.replace(/^\s*|\s*$/g, '');
					data = data.replace(/\\r\\n/gm, '');
					var expr = "</tr>\\s*<tr";
					var regEx = new RegExp(expr, "gm");
					var newRows = data.replace(regEx, "</tr><tr");
					$("#setData").DataTable().rows.add($(newRows)).draw();
					getPaymentData();
				}
				else 
				{
					$('#dueAmount').html('0');
					$('#totalAmount').html('0');
					$('#paidAmount').html('0');
				}
					
			}
		});
	}
	
	$("body").on("click","#addButton",function(){
		$("#form")[0].reset();
		$('#id').val("0");
		$('#LawsuitPaymentModal').modal('toggle');
	});
	
	function add()
	{
		var date=$('#date').val();
		var mode=$('#mode').val();
		var amount=$('#amount').val();
		var remarks=$('#remarks').val();
		var consultId=$('#consultId').val();
		var id=$('#id').val();
		if(!date || !mode || !amount || !remarks || !consultId)
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
			url: "consultationPaymentDB.php",
			data: {
				action:action,id:id,date:date,mode:mode,amount:amount,remarks:remarks,consultId:consultId
			},
			success: function (data) {
				console.log(data);
				$('#LawsuitPaymentModal').modal('toggle');
				showMessage(data);
				getData();
				getPaymentData();
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
			url: "consultationPaymentDB.php",
			data: {
				action:'getData',id:id
			},
			success: function (data) {
				/////console.log(data);
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
					});
					$('#LawsuitPaymentModal').modal('toggle');
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
			url: "consultationPaymentDB.php",
			data: {action:'del',id:id},
			success: function (data) {
				getData();
				getPaymentData();
				showMessage(data);
				$('#msg_modal').modal('toggle');
			}
		});
	}
	
	function customer_type_modal()
	{
		$("#customer_type")[0].reset();
		//$('#id').val("0");
		$('#add_customer_type').modal('toggle');
	}
	function getPaymentData()
	{
		var id=$('#consultId').val();
		$.ajax({
			type:"POST",
			url: "consultationPaymentData.php",
			data: {
				getPaymentData:'1',id:id
			},
			success: function (data) {
				///console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						if(this.totalContractAmount)
							$('#totalAmount').html(this.totalContractAmount);
						else 
							$('#totalAmount').html('0');
						$('#paidAmount').html(this.paidAmount);
						if(this.totalDues)
							$('#dueAmount').html(this.totalDues);
						else 
							$('#dueAmount').html('0');
						if(this.isPaid)
						$('#paidStatus').attr("checked", true);
						else 
						$('#paidStatus').attr("checked", false);
					});
				}
			}
		});
	}
	$("body").on("click","#UpdatePayment",function(){
		$("#form")[0].reset();
		/////$('#id').val("0");
		$('#LawsuitAmountModal').modal('toggle');
		getContractData();
	});
	$('#amountContract').on('input', function() {
		CalculateTax();
	});
	$('#taxValue').change(function() {
		CalculateTax();
	});

	function CalculateTax()
	{
		var amountContract=parseFloat($('#amountContract').val());
		if(!amountContract)
			return $('#contractAmountIncludingTax').val(0);
		var taxValue=parseFloat($('#taxValue').val());
		 taxValue=parseFloat(taxValue/100);
		 taxValue=(taxValue*amountContract);  //toFixed(2)
		 $('#contractAmountIncludingTax').val(taxValue+amountContract);
	}
	function getContractData()
	{
		var consultId=$('#consultId').val();
		$.ajax({
			type:"POST",
			url: "consultationPaymentData.php",
			data: {
				getContractData:'1',consultId:consultId
			},
			success: function (data) {
				console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#amountContract').val(this.amount);
						if(this.tax!="")
						$('#taxValue').val(this.tax);
						$('#contractAmountIncludingTax').val(this.totalAmount);
					});
				}
			}
		});
	}
	
	function updateContract()
	{
		var amountContract=$('#amountContract').val();
		var taxValue=$('#taxValue').val();
		var totContAmount=$('#contractAmountIncludingTax').val();
		var consultId=$('#consultId').val();
		if(!amountContract || !taxValue || !totContAmount || !consultId)
		{
			showMessage('Invalid Input');
			return;
		}
		$.ajax({
			type:"POST",
			url: "consultationPaymentDB.php",
			data: {
				action:'updateContract',consultId:consultId,amountContract:amountContract,taxValue:taxValue,totContAmount:totContAmount
			},
			success: function (data) {
				console.log(data);
				showMessage(data);
				$('#LawsuitAmountModal').modal('toggle');
				getPaymentData();
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
	
	$('#paidStatus').change(function() {
		var paidStatus;
		if ($(this).is(':checked')) {
			paidStatus=1;
		}
		else paidStatus=0;
		var consultId=$('#consultId').val();
		$.ajax({
			type:"POST",
			url: "consultationPaymentDB.php",
			data: {
				paidStatus:paidStatus,consultId:consultId
			},
			success: function (data) {
				///console.log(data);
				showMessage(data);
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
	});
	
	
	
</script>
