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
				<h5><?php echo set_value('assetSubCategoryList'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" onclick="add()" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('addAssetSubCat'); ?></a>
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
										<th><?php echo set_value('subCategoryName_ar'); ?></th>
										<th><?php echo set_value('subCategoryName_en'); ?></th>
										<th><?php echo set_value('category'); ?></th>
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

<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('deleteAssetSubCat'); ?></h3>
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

</div>
<!-- /Main Wrapper -->

<!-- sample modal content -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form id='expense_category' action='javascript:addExpenseCategory();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("addAssetSubCat"); ?></h4>
				
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-12">
						<div class="mb-4">
							<div class="form-group">
								<label for="catId" class="form-label"><?php echo set_value("category"); ?><span class="text-danger"> <span class="text-danger"> * </span> </span></label>
								<select class="form-control js-example-basic-single form-small select" id="catId" required>
								</select>
							</div>
								<input type='hidden' id='getSelect' value="<?php echo set_value('select'); ?>" />
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="mb-4">
							<div class="form-group">
								<label for="nameAr" class="form-label"><?php echo set_value("subCategoryName_ar"); ?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="nameAr" placeholder="<?php echo set_value("subCategoryName_ar"); ?>" required>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="mb-4">
							<div class="form-group">
								<label for="nameEn" class="form-label"><?php echo set_value('subCategoryName_en'); ?> <span class="text-danger"> * </span></label>
								<input type="text" class="form-control form-control-sm" id="nameEn" placeholder="<?php echo set_value("subCategoryName_en"); ?>" required>
							</div>
						</div>
					</div>
					
				</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
				<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('submit'); ?>" />
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
$( document ).ready(function() {
    getData();
	$('#catId').select2({
        dropdownParent: $('#addModal')
	});
});
	
	
function addExpenseCategory()
{
	var nameEn=$('#nameEn').val();
	var nameAr=$('#nameAr').val();
	var catId=$('#catId').val();
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
		url: "AssetSubCategoryDB.php",
		data: { action:action,nameAr:nameAr,nameEn:nameEn,id:id,catId:catId },
		success: function (data) {
			console.log(data);
			$("#expense_category")[0].reset();
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

function getData()
{
	var myTable = $('#setData').DataTable();
	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "AssetSubCategoryData.php",
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
	$("#expense_category")[0].reset();
	$('#id').val("0");
	$('#addModal').modal('toggle');
	$('#catId').val('').change();
	getCategory();
}

function edit(id)
{
	$.ajax({
		type:"POST",
		url: "AssetSubCategoryDB.php",
		data: {
				action:'getData',id:id
		},
		success: function (data) {
			console.log(data);
			$('#id').val(id);
			/////alert(JSON.stringify(data));
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#id').val(this.id);
					$('#nameAr').val(this.subAssetCatName_ar);
					$('#nameEn').val(this.subAssetCatName_en);
					getCategory(this.catId);
				});
				$('#addModal').modal('toggle');
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
		url: "AssetSubCategoryDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			$('#delete_modal').modal().hide();
			showMessage(data);
			getData();
		}
	});
}
function getCategory(catId)
{
	var getSelect=$('#getSelect').val();
	$.ajax({
		type:"POST",
		url: "dropdown_assetCategory.php",
		data: { getSelect:getSelect },
		success: function (data) {
			/////console.log(data);
			$('#catId').html(data);
		},
		complete: function (data) {
			$('#catId').val(catId).change();
		}
	});
}
/*
$("body").on("change","#catId",function(){
	var getSelect=$('#getSelect').val();
});
*/
</script>