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
<style>

.text-wrap{
    white-space:normal;
}
</style>

<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('language'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-sm-12">
				<div class="col-lg-4 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="page" class="form-label"><?php echo set_value("pagesList"); ?><span class="text-danger"> * </span></label>
						<select class="form-control js-example-basic-single form-small select" id='page'>
							<option value=""><?php echo set_value("select"); ?></option>
							<option value="-1"><?php echo set_value("all"); ?></option>
							<?php echo include_once('dropdown_pageMenu.php'); ?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<?php 
			$top=10;
			$left=50;
			include_once('loader.php'); 
		?>
		<div class="row">
				<div class="card-table">
					<div class="card-body">
						<div class="table-responsive" id='showData' style='display:none'>
							<table class="table table-center table-hover datatable" id='setData'>
								<thead class="thead-light">
									<tr>
										<th><?php echo set_value('action'); ?></th>
										<th>#</th>
										<th><?php echo set_value('phrase'); ?></th>
										<th><?php echo set_value('name_ar'); ?></th>
										<th><?php echo set_value('name_en'); ?></th>
										<!-- <th><?php //echo set_value('active'); ?></th> -->
										<th><?php echo set_value('page'); ?></th>
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
					<h3><?php echo set_value('deleteUser'); ?></h3>
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
			<form id='modalForm' action='javascript:addNew();'>
			<div class="modal-header">
				<h4 class="modal-title"><?php echo set_value("addUpdatePhrase"); ?></h4>
				
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-12">
						<div class="mb-4">
							<label for="phrase" class="form-label"><?php echo set_value("phrase"); ?> </label>
							<input type="text" class="form-control form-control-sm" id="phrase" placeholder="<?php echo set_value("phrase"); ?>" disabled>
						</div>
					</div>
					<div class="col-md-12">
						<div class="mb-4">
							<label for="nameAr" class="form-label"><?php echo set_value("name_ar"); ?> <span class="text-danger"> * </span></label>
							<input type="text" class="form-control form-control-sm" id="nameAr" placeholder="<?php echo set_value("name_ar"); ?>" >
						</div>
					</div>
					<div class="col-md-12">
						<div class="mb-4">
							<label for="nameEn" class="form-label"><?php echo set_value("name_en"); ?> <span class="text-danger"> * </span></label>
							<input type="text" class="form-control form-control-sm" id="nameEn" placeholder="<?php echo set_value("name_en"); ?>">
						</div>
					</div>
					<div class="col-md-12">
						<div class="mb-4">
							<label for="pageId" class="form-label"><?php echo set_value("pages"); ?></label>
							<select class="form-control js-example-basic-single form-small select" id="pageId" multiple="multiple" disabled>
								<option value=""> <?php echo set_value("select"); ?></option>
								<?php echo include('dropdown_pageMenu.php'); ?>
							</select>
						</div>
					</div>
							
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('submit'); ?>" />
					<input type='hidden' id='id' value="0" />
					<input type='hidden' id='mId' value="0" />
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
	
	
	
	
function addNew()
{
	var nameAr=$('#nameAr').val();
	var nameEn=$('#nameEn').val();
	var pageId=$('#pageId').val();
	
	if(nameAr=='' && nameEn=='' && pageId=='')
	{
		alert('Invalid Input');
		return;
	}
	var id=$('#id').val();
	if(id>0)
		var action="update";
	else 
		var action="add";
	$.ajax({
		type:"POST",
		url: "languageDB.php",
		data: { action:action,nameAr:nameAr,nameEn:nameEn,pageId:pageId,id:id},
		beforeSend: function()
		{
			$("#ajax_loader").show();
			$('#login').prop("disabled", true);
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
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		},
		complete: function (jqXHR, exception) {
			$("#ajax_loader").hide();
			$('#login').prop("disabled", false);
		}
	}); 
}

$( document ).ready(function() {
    ///getData();
	$('#pageId').select2({
        dropdownParent: $('#addModal')
	});
});
function getData()
{
	var pageId=$('#page').val();
	if(!pageId)
	{
		$('#showData').hide();
		return;
	}
	$('#showData').show();
	var myTable = $('#setData').DataTable();
	var rows = myTable.rows().remove().draw();
	
	$.ajax({
		type:"POST",
		url: "LanguageData.php",
		data: { pageId:pageId },
		success: function (data) {
			if (!$.trim(data) == '') {
                data = data.replace(/^\s*|\s*$/g, '');
                data = data.replace(/\\r\\n/gm, '');
                var expr = "</tr>\\s*<tr";
                var regEx = new RegExp(expr, "gm");
                var newRows = data.replace(regEx, "</tr><tr");
                $("#setData").DataTable().rows.add($(newRows)).draw();
             }
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
			$("#ajax_loader").hide();
		},
		complete: function (jqXHR, exception) {
			$("#ajax_loader").hide();
		}
	}); 
}

function add()
{
	$("#modalForm")[0].reset();
	$('#id').val('0');
	$('#mId').val('0');
	$('#addModal').modal('toggle');
	$('#pageId').val('').change();
}

function edit(id,mId)
{
	$.ajax({
		type:"POST",
		url: "languageDB.php",
		data: {
				action:'getData',id:id, mId:mId
		},
		success: function (data) {
			///alert(JSON.stringify(data));
			console.log(data);
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#id').val(this.id);
					$('#mId').val(this.menuId);
					$('#phrase').val(this.phrase);
					$('#nameAr').val(this.ar);
					$('#nameEn').val(this.en);
					$("#pageId").val((this.pageName).split(",")).change(); 
				});
				$('#addModal').modal('toggle');
			}
			else 
			{
				///alert();
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
		url: "languageDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			showMessage(data);
			getData();
		}
	});
}

$("#page").on('change', function() {
	if(!this.value)
	{
		$('#showData').hide();
		return;
	}
	getData();
});
</script>