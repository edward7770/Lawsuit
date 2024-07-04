<?php 
	
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	include_once('header.php'); 
	include_once('config/conn.php');
	$language=$_SESSION['lang'];
	
	$qry="SELECT l.`phrase`, $language AS VALUE FROM `language` l
	LEFT JOIN languagepageref r ON r.languageid=l.`id`
	WHERE r.menuid in(8)"; 
	$stmt=$dbo->prepare($qry);
	//$stmt->bindParam(":to_date",$to_date,PDO::PARAM_STR);
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
<!--
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link href="assets/plugins/editor/editor.css" type="text/css" rel="stylesheet"/>
-->
<style>
	h6,.textColor {
	color:red
	}
</style>

<style>
	/*
    .blue-color {
        color:blue;
    }
    .teal-color {
        color:teal;
    }
     
    .yellow-color {
    color:yellow;
    }
    */
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
		<div class="row">
			<div class="col-sm-12">
				
				<!-- /Page Header -->	
				<div class="page-header">
					<div class="content-page-header">
						<h5><?php echo set_value('addLawsuit'); ?></h5>
						<!--
							<div class="list-btn">
							<ul class="filter-list">
							<li>
							<a class="btn btn-filters w-auto popup-toggle"><span class="me-2"><i class="fe fe-filter"></i></span>Filter </a>
							</li>
							
							
							<li>
							<div class="dropdown dropdown-action">
							<a href="#" class="btn-filters" data-bs-toggle="dropdown" aria-expanded="false"><span><i class="fe fe-download"></i></span></a>
							<div class="dropdown-menu dropdown-menu-end">
							<ul class="d-block">
							<li>
							<a class="d-flex align-items-center download-item" href="javascript:void(0);" download><i class="far fa-file-pdf me-2"></i>PDF</a>
							</li>
							<li>
							<a class="d-flex align-items-center download-item" href="javascript:void(0);" download><i class="far fa-file-text me-2"></i>CVS</a>
							</li>
							</ul>
							</div>
							</div>														
							</li>
							
							<li>
							<a class="btn btn-primary" onclick="add()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("add_new_customer"); ?></a>
							</li>
							
							</ul>
							</div>
						-->
					</div>
				</div>
				<div id='ajax_loader' style="position: fixed; left: 50%; top: 12%; display:none;">
					<button class="btn btn-primary" type="button" disabled>
						<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
						Loading...
					</button>  
				</div> 
				<!-- /Page Header -->
				<!--
				<form action="javascript:lawsuitAdd();"> -->
				<div class="card mb-0">
					<div class="card-body pb-0">
						<div class="invoice-card-title">
							<h6><?php echo set_value('customerData'); ?> :</h6>
						</div>
						<form action="javascript:addMoreCustomer();">
							<div class="row">
								<div class="col-md-3">
									<label for="customer class="form-label"><?php echo set_value("selectCustomer"); ?><span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id='customer'  >
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_customer.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="customerType" class="form-label"><?php echo set_value("selectCustomerType"); ?><span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id='customerType' >
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_customerType.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="customerAjective" class="form-label"><?php echo set_value("selectCustomerAdjective"); ?><span class="text-danger"> * </span></label>
									<select class="form-control js-example-basic-single form-small select" id='customerAjective' >
										<option value=""><?php echo set_value("select"); ?></option>
										<?php echo include_once('dropdown_customerAdjectives.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="idCustomer" class="form-label"><?php echo set_value("idCustomer"); ?></label>
									<fieldset id="idCustomerFieldset">
										<input type="file" class="form-control form-control-sm image_check" id="idCustomer">
									</fieldset>
									<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
								</div>
							</div>	
							<div class="row">
								<div class="col-md-3">
									<label for="nationalAddress" class="form-label"><?php echo set_value("nationalAddressPlaintiff"); ?></label>
									<fieldset id="nationalAddressFieldset">
										<input type="file" class="form-control form-control-sm image_check" id="nationalAddress">
									</fieldset>
									<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
								</div>
								
								<div class="col-md-3">
									<label for="idDefendant" class="form-label"><?php echo set_value("idDefendant"); ?></label>
									<fieldset id="idDefendantFieldset">
										<input type="file" class="form-control form-control-sm image_check" id="idDefendant">
									</fieldset>
									
									<span><?php echo set_value("uploadMaximumLimit"); ?> 5MB </span>
								</div>
								
							</div> 
							
							<br>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary" type='submit'><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("addMoreCustomer"); ?></button>
								</div>
							</div>
						</form>
						
						<div class="row">
							<div class="col-sm-12">
								<div class="card-table">
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-center table-hover datatable" id="customerTable" cellspacing="0">
												<thead class="thead-light">
													<tr>
														<th>#</th>
														<th><?php echo set_value('customer'); ?></th>
														<th><?php echo set_value('customers_types'); ?></th>
														<th><?php echo set_value('CustomerAdjectives'); ?></th>
														<th><?php echo set_value('idCustomer'); ?></th>
														<!--<th style="diplay:none;"></th> -->
														<th><?php echo set_value('nationalAddressPlaintiff'); ?></th>
														<!--<th style="diplay:none;"></th> -->
														<th><?php echo set_value('idDefendant'); ?></th>
														<!--<th style="diplay:none;"></th> -->
														<th><?php echo set_value('action'); ?></th>
													</tr>
												</thead>
												<tbody id='setData'> </tbody>
												
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br><br>
						<div class="invoice-card-title">
							<h6><?php echo set_value('opponentsData'); ?> </h6>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="opponent class="form-label"><?php echo set_value("selectOpponent"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" multiple="multiple" id='opponent'>
											
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label"><?php echo set_value("addMoreOponent"); ?></label><br/>
										<button type="button" class="btn btn-primary mt-1" data-bs-toggle="modal" data-bs-target="#opponentModal"><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("addMoreOponent"); ?></button>
									</div>	
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="lawyer class="form-label"><?php echo set_value("opponentLawyer"); ?><span class="text-danger"> * </span></label>
										<select class="form-control js-example-basic-single form-small select" multiple="multiple" id='lawyer'>
											
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-label"><?php echo set_value("addMoreLayer"); ?></label><br/>
										<button type="button" class="btn btn-primary mt-1" data-bs-toggle="modal" data-bs-target="#layerModal"><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("addMoreLayer"); ?></button>
									</div>	
								</div>
							</div>
							
						</div>
						
						<br/>
						<div class="row">
							<div class="col-md-10 textColor" >
								<?php echo set_value("infoModifiedLawsuit"); ?>
							</div>
						</div>
						
						<br/>
						<div class="invoice-card-title">
							<h6><?php echo set_value('lawsuitContractData'); ?> </h6>
						</div>
						<div class="row">
							<div class="col-md-3">
								<label for="lawsuitsType" class="form-label"><?php echo set_value("lawsuits_Type"); ?><span class="text-danger"> * </span></label>
								<select class="form-control select" id='lawsuitsType'>
									<option value=""><?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_lawsuitType.php'); ?>
								</select>
							</div>
							<div class="col-md-6">
								<label for="subjectLawsuit" class="form-label"><?php echo set_value("subjectLawsuit"); ?></label>
								<input type="text" class="form-control form-control-sm" id="subjectLawsuit" placeholder="<?php echo set_value("subjectLawsuit"); ?>">
							</div>
						</div>
						<br/>
						<div class="row">
							<div class="col-md-3">
								<label for="stage" class="form-label"><?php echo set_value("stage"); ?><span class="text-danger"> * </span></label>
								<select class="form-control select" id='stage'>
									<option value=""><?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_stage.php'); ?>
								</select>
							</div>
							<div class="col-md-3">
								<label for="state" class="form-label"><?php echo set_value("state"); ?><span class="text-danger"> * </span></label>
								<select class="form-control select" id='state'>
									<option value=""><?php echo set_value("select"); ?></option>
									<?php echo include_once('dropdown_state.php'); ?>
								</select>
							</div>
							<br/>
							<div class="col-md-3">
								<label for="lawsuitLocation" class="form-label"><?php echo set_value("lawsuitLocation"); ?><span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="lawsuitLocation" placeholder="<?php echo set_value("lawsuitLocation"); ?>">
							</div>
							<br/>
							<div class="col-md-3">
								<label for="createdAt" class="form-label"><?php echo set_value("created_at"); ?><span class="text-danger"> * </span></label>
								<input type="date" class="form-control form-control-sm" id="createdAt" placeholder="<?php echo set_value("created_at"); ?>">
							</div>
							
						</div>
						<br/><br/>
						<div class="row">
							<div class="col-md-3">
								<label for="amountContract" class="form-label"><?php echo set_value("amountContract"); ?></label>
								<input type="text" class="form-control form-control-sm" id="amountContract" placeholder="<?php echo set_value("amountContract"); ?>">
							</div>
							<div class="col-md-3">
								<label for="contractAmountIncludingTax" class="form-label"><?php echo set_value("contractAmountIncludingTax"); ?>%</label>
								<input type="text" class="form-control form-control-sm" id="contractAmountIncludingTax" placeholder="<?php echo set_value("contractAmountIncludingTax"); ?>">
							</div>
							<div class="col-md-3">
								<label for="taxValue" class="form-label"><?php echo set_value("taxValue"); ?>%</label>
								<input type="text" class="form-control form-control-sm" id="taxValue" placeholder="<?php echo set_value("taxValue"); ?>">
							</div>
							<div class="col-md-3">
								<label for="percent" class="form-label"><?php echo set_value("percent"); ?>%</label>
								<input type="text" class="form-control form-control-sm" id="percent" placeholder="<?php echo set_value("percent"); ?>">
							</div>
						</div>
						<br/><br/><br/>
						
						<div class="invoice-card-title">
							<h6><?php echo set_value('contractTerms'); ?> :</h6>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-12 nopadding">
						<textarea id="txtEditor"></textarea> 
					</div>
				</div>
				
				
				<div class="row">
					<div class="col-lg-12 nopadding">
						<textarea id="txtEditor2"></textarea> 
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="note" class="form-label"><?php echo set_value("notes"); ?></label>
							<textarea class="form-control" id="note" rows="3"></textarea>
						</div>
					</div>
				</div>
				
				
				<div class="add-customer-btns text-end ">
					<a href="#" type="reset" class="btn customer-btn-cancel">Cancel</a>
					<a href="#" type="submit" id='add' class="btn customer-btn-save"><?php echo set_value('add'); ?></a>
				</div>
				<!--</form> -->
				
				<!--<fieldset id="CustomerUploadedFiles" style='display:none'> -->
				<fieldset id="CustomerUploadedFiles" style='display:none'>
					<input type="file" id="idCustomerImage1">
					<input type="file" id="nationalAddressImage1">
					<input type="file" id="idDefendantImage1">
				</fieldset>
				
			</div>
		</div>
	</div>
	
</div>
</div>
</div>


</div>
</div>
<!-- /Page Wrapper -->
<!--
	<div class="row">
	
	</div>
	
--->

<!-- /Customer Modal -->

<!-- sample modal content -->
<div class="modal fade" id="opponentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form id='opponentForm' action='javascript:addOpponent();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value("createNewOponent"); ?></h4>
					
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentName" class="form-label"><?php echo set_value("opponentName"); ?><span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="opponentName" placeholder="<?php echo set_value("opponentName"); ?>" required>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentPhone" class="form-label"><?php echo set_value("opponentPhone"); ?><span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="opponentPhone" placeholder="<?php echo set_value("opponentPhone"); ?>" required>
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentNationality" class="form-label"><?php echo set_value("opponentNationality"); ?></label>
								<input type="text" class="form-control form-control-sm" id="opponentNationality" placeholder="<?php echo set_value("opponentNationality"); ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentAddress" class="form-label"><?php echo set_value("opponentAddress"); ?></label>
								<input type="text" class="form-control form-control-sm" id="opponentAddress" placeholder="<?php echo set_value("opponentAddress"); ?>">
							</div>
						</div>
					</div>	
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
					<input type='hidden' id='id' />
				</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->

<!-- sample modal content -->
<div class="modal fade" id="layerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form id='opponentForm' action='javascript:addLayer();'>
				<div class="modal-header">
					<h4 class="modal-title"><?php echo set_value("createNewOponent"); ?></h4>
					
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentLawyer" class="form-label"><?php echo set_value("opponentLawyer"); ?></label>
								<input type="text" class="form-control form-control-sm" id="opponentLawyer" placeholder="<?php echo set_value("opponentLawyer"); ?>" required>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="opponentLawyerPhone" class="form-label"><?php echo set_value("opponentLawyerPhone"); ?></label>
								<input type="text" class="form-control form-control-sm" id="opponentLawyerPhone" placeholder="<?php echo set_value("opponentLawyerPhone"); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
					<input type='hidden' id='id' />
				</div>
			</form>
			
		</div>
	</div>
</div><!-- /.modal -->


<?php /////include_once('CustomerModal.php'); ?>

<?php include_once('MessageModalShow.php'); ?>
</div>
<!-- /Main Wrapper -->

<?php include_once('footer.php'); ?>
<script src="assets/js/custom/LawsuitAdd.js"> </script>
<script src="assets/js/custom/imageupload.js"> </script>
<script src="assets/plugins/editor/editor.js"></script>

<script>
	$(document).ready(function() {
		getDropDown('dropdown_Opponent','opponent');
		getDropDown('dropdown_Lawyer','lawyer');
		///$("#txtEditor").Editor();
		///$("#txtEditor2").Editor();
		//$('#opponentModal').modal('toggle');
		
	});
</script>

<script>
	
	$("body").on("click","#add",function(){
		
		var rowCount = $("#customerTable tr").length;
		if(rowCount<=1)
		{
			message.innerText = "Please add Customer Data";
			$('#msg_modal').modal('toggle');
			return;
		} 
		
		var opponent=$('#opponent').val();
		if(opponent=="")
		{
			message.innerText = "Please Select Opponent";
			$('#msg_modal').modal('toggle');
			return;
		}
		var opponentLawyer=$('#lawyer').val();
		if(opponentLawyer=="")
		{
			message.innerText = "Please Select Lawyer";
			$('#msg_modal').modal('toggle');
			return;
		}
		var lawsuitsType=$('#lawsuitsType').val();
		if(lawsuitsType=="")
		{
			message.innerText = "Please Select lawsuits Type";
			$('#msg_modal').modal('toggle');
			return;
		}
		var stage=$('#stage').val();
		if(stage=="")
		{
			message.innerText = "Please Select Stage";
			$('#msg_modal').modal('toggle');
			return;
		}
		var state=$('#state').val();
		if(state=="")
		{
			message.innerText = "Please Select state";
			$('#msg_modal').modal('toggle');
			return;
		}
		var state=$('#state').val();
		if(state=="")
		{
			message.innerText = "Please Select state";
			$('#msg_modal').modal('toggle');
			return;
		}
		
		var lawsuitLocation=$('#lawsuitLocation').val();
		if(lawsuitLocation=="")
		{
			message.innerText = "Please Select lawsuitLocation";
			$('#msg_modal').modal('toggle');
			return;
		}
		
		var lawSuitData = {
			"customerDetails":[],
			"action":"add",
			"opponentIds":opponent.toString(),
			"opponentLawyerIds":opponentLawyer.toString(),
			"lawsuitTypeId":lawsuitsType.toString(),
			"lawsuitSubject":$('#subjectLawsuit').val(),
			"stageId":stage.toString(),
			"stateId":state.toString(),
			"lawsuitLoc":$('#lawsuitLocation').val(),
			"createdAt":$('#createdAt').val(),
			"amount":$('#amountContract').val(),
			"tax":$('#contractAmountIncludingTax').val(),
			"taxVal":$('#taxValue').val(),
			"percent":$('#percent').val(),
			"termAr":$('#txtEditor').val(),
			"termEn":$('#txtEditor2').val(),
			"note":$('#note').val()	
		};
		
		var formData = new FormData();
		
		$('#customerTable tr').each(function (index, tr) {
			//get td of each row and insert it into cols array
			if(index>0)
			{
				var cId; var typeId; var adjecId; var idCustomerImage; var nationalAddressImage; var idDefendantImage;
				$(this).find('td').each(function (colIndex, c) {
					if(colIndex==1) cId=c.id;
					if(colIndex==2) typeId=c.id;
					if(colIndex==3) adjecId=c.id;
					if(colIndex==4 && c.textContent!="-") { idCustomerImage="idCustomer"+c.id; formData.append('idCustomer'+c.id, $('#idCustomerImage'+c.id)[0].files[0]);  } 
					if(colIndex==5 && c.textContent!="-") { nationalAddressImage='nationalAddress'+c.id;  formData.append('nationalAddress'+c.id, $('#nationalAddressImage'+c.id)[0].files[0]); }
					if(colIndex==6 && c.textContent!="-") { idDefendantImage='idDefendant'+c.id; formData.append('idDefendant'+c.id, $('#idDefendantImage'+c.id)[0].files[0]);}
				});
				
				var pushList_CustomerData = {
					"cId": cId, "typeId": typeId, "adjecId": adjecId, "idCustomerImage": idCustomerImage , "nationalAddressImage":nationalAddressImage,"idDefendantImage":idDefendantImage
				};
				lawSuitData.customerDetails.push(pushList_CustomerData);
				var pushList_CustomerData = {
					"cId": "", "typeId": "", "adjectives": "", "idCustomerImage":"", "nationalAddressImage":"","idDefendantImage":""
				};
			}
		});
		////alert(JSON.stringify(lawSuitData));
		formData.append('customerDetails', JSON.stringify(lawSuitData) );
		$.ajax({
			url: 'LawsuitAddDB.php',
			type: 'POST',
			////data: {data:JSON.stringify(formData)},
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function()
			{
				$("#ajax_loader").show();
				$('#add').prop("disabled", true);
			},
			success: function(response) {
				var result = response.replace(/\D/g, '');	
				var data=response.replace(/[0-9]/g, '');
				if(result==1)
				{
					$("#customerTable").find("tr:gt(0)").remove();
					resetForm();
					message.innerText = data;
					$('#msg_modal').modal('toggle');
				}
				else {
					message.innerText =response;
					$('#msg_modal').modal('toggle');
				}
				// Handle success response
				console.log(response);
				$("#ajax_loader").hide();
				$('#add').prop("disabled", false);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Handle error response
				console.log(errorThrown);
			}
		});
		
	});
	
	function resetForm()
	{
		$("#opponent").val("");
		$('#opponent').select2({ });
		$("#lawyer").val("");
		$('#lawyer').select2({ });
		$("#lawsuitsType").val("");
		$('#lawsuitsType').select2({ });
		$("#state").val("");
		$('#sate').select2({ });
		$("#stage").val("");
		$('#stage').select2({ });
		
		$('#subjectLawsuit').val('');
		$('#lawsuitLocation').val('');
		$('#createdAt').val('');
		$('#amountContract').val('');
		$('#contractAmountIncludingTax').val('');
		$('#taxValue').val('');
		$('#percent').val('');
		$('#txtEditor').val('');
		$('#txtEditor2').val('');
		$('#note').val('');	
	}
	
	</script>		