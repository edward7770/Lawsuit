<?php 
	
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
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
include_once('header.php'); 	
?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link href="assets/plugins/editor/editor.css" type="text/css" rel="stylesheet"/>

<style>
	h6,.textColor {
		color:red
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
			<!-- /Page Header -->
					
					
					<div class="card mb-0">
						<div class="card-body pb-0">
							<div class="invoice-card-title">
								<h6><?php echo set_value('customerData'); ?> :</h6>
							</div>
							<div class="fieldGroup">
								<input type="hidden" id="id" value="0" />
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary addMore"><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value("addMoreCustomer"); ?></button>
								</div>
							</div>
							<br><br>
							<div class="invoice-card-title">
								<h6><?php echo set_value('opponentsData'); ?> </h6>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label for="opponentName" class="form-label"><?php echo set_value("opponentName"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentName" placeholder="<?php echo set_value("opponentName"); ?>">
								</div>
								<div class="col-md-3">
									<label for="opponentPhone" class="form-label"><?php echo set_value("opponentPhone"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentPhone" placeholder="<?php echo set_value("opponentPhone"); ?>">
								</div>
								<div class="col-md-3">
									<label for="opponentNationality" class="form-label"><?php echo set_value("opponentNationality"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentNationality" placeholder="<?php echo set_value("opponentNationality"); ?>">
								</div>
								<div class="col-md-3">
									<label for="opponentAddress" class="form-label"><?php echo set_value("opponentAddress"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentAddress" placeholder="<?php echo set_value("opponentAddress"); ?>">
								</div>
							
							</div>
							<br/>
							<div class="row">
								<div class="col-md-3">
									<label for="opponentLawyer" class="form-label"><?php echo set_value("opponentLawyer"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentLawyer" placeholder="<?php echo set_value("opponentLawyer"); ?>">
								</div>
								<div class="col-md-3">
									<label for="opponentLawyerPhone" class="form-label"><?php echo set_value("opponentLawyerPhone"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentLawyerPhone" placeholder="<?php echo set_value("opponentLawyerPhone"); ?>">
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
									<label for="lawsuitsType" class="form-label"><?php echo set_value("lawsuits_Type"); ?></label>
									<select class="form-control select" multiple="multiple" id='lawsuitsType'>
										<?php echo include_once('dropdown_lawsuitType.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="opponentLawyer" class="form-label"><?php echo set_value("opponentLawyer"); ?></label>
									<input type="text" class="form-control form-control-sm" id="opponentLawyer" placeholder="<?php echo set_value("opponentLawyer"); ?>">
								</div>
								<div class="col-md-6">
									<label for="subjectLawsuit" class="form-label"><?php echo set_value("subjectLawsuit"); ?></label>
									<input type="text" class="form-control form-control-sm" id="subjectLawsuit" placeholder="<?php echo set_value("subjectLawsuit"); ?>">
								</div>
							</div>
							<br/>
							<div class="row">
								<div class="col-md-3">
									<label for="stage" class="form-label"><?php echo set_value("stage"); ?>*</label>
									<select class="form-control select" multiple="multiple" id='stage'>
										<?php echo include_once('dropdown_stage.php'); ?>
									</select>
								</div>
								<div class="col-md-3">
									<label for="state" class="form-label"><?php echo set_value("state"); ?>*</label>
									<select class="form-control select" multiple="multiple" id='state'>
										<?php echo include_once('dropdown_state.php'); ?>
									</select>
								</div>
								<br/>
								<div class="col-md-3">
									<label for="lawsuitLocation" class="form-label"><?php echo set_value("lawsuitLocation"); ?>*</label>
									<input type="text" class="form-control form-control-sm" id="lawsuitLocation" placeholder="<?php echo set_value("lawsuitLocation"); ?>">
								</div>
								<br/>
								<div class="col-md-3">
									<label for="createdAt" class="form-label"><?php echo set_value("created_at"); ?>*</label>
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
							
						
						</div>
					</div>
				</div>
					
				</div>
			</div>
		</div>
		
		
	</div>
</div>
<!-- /Page Wrapper -->

<!-- /Customer Modal -->

<?php include_once('CustomerModal.php'); ?>

<?php include_once('MessageModalShow.php'); ?>
</div>
<!-- /Main Wrapper -->
	
<?php include_once('footer.php'); ?>

		<script src="assets/plugins/editor/editor.js"></script>
 
		<script>
			$(document).ready(function() {
				$("#txtEditor").Editor();
				$("#txtEditor2").Editor();
			});
		</script>



<script>
var id_array=[0];	
var row_id=1;
$("body").on("click",".addMore",function(e)
{
	/*
	if (e.which) {
        // Actually clicked
		elementClicked=true;
		
    } else {
        // Triggered by code
		elementClicked=false;
    }
	var row=this.value;
	addMoreid=this.value;
	//alert(this.value);
	//id++;
	var qual=$("#qual"+row).val();
	var qual_sp=$("#qual_sp"+row).val();
	//var degree_sp=$("#degree_sp"+row).val();
	var job_cat=$("#job_cat"+row).val();
	var tot_exp=$("#tot_exp"+row).val();
	var postgraduate_exp=$("#postgraduate_exp"+row).val();
	

	var id=this.id;
	*/
	$("#ajax_loader").show();
		setTimeout(function() { 
			addCustomer();
		}, 50);
	row_id++;
});

function addCustomer() {
	//alert(join_type+" row " +row);
	$.ajax({
		type: "POST",
		url: "AddCustomerRow.php",
		async: false,
		data:{rowId:row_id},
		success: function(data){
			if(typeof data != "undefined"){
				$("#ajax_loader").hide();
			}
			var fieldHTML = '<div class="form-group fieldGroup">'+data+'</div>';
			$('body').find('.fieldGroup:last').after(fieldHTML);			
		},
		complete: function(){
			$('#customer'+row_id).select2({
			});
			$('#customerType'+row_id).select2({
			});
			$('#customerAjective'+row_id).select2({
			});
			id_array.push(row_id);
			//$("#join_type"+row_id).val(join_type);
			row_id++;
		}
	});
}
//remove fields group
$("body").on("click",".remove",function(){
	var idu=this.id;
	idu=idu.replace(/\D+/g, '');   //Removing everything except numbers in a string
	$(this).parents(".fieldGroup").remove();
	//to remove specific value from array using jQuery
	id_array = jQuery.grep(id_array, function(value) {
		return value !=idu ;
	});
});

$( document ).ready(function() {
	addCustomer();
});

</script>