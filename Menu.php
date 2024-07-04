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
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">				
		<!-- Page Header -->
		<div class="page-header">
			<div class="content-page-header">
				<h5><?php echo set_value('MenuList'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						<li>
							<a class="btn btn-primary" onclick="add();" ><i class="fa fa-plus-circle me-2" aria-hidden="true"></i><?php echo set_value('addNewMenu'); ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<div id="filter_inputs" class="card filter-card">
			<div class="card-body pb-0">
				<div class="row">
					<div class="col-sm-6 col-md-3">
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="form-group">
							<label>Email</label>
							<input type="text" class="form-control">
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="form-group">
							<label>Phone</label>
							<input type="text" class="form-control">
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Search Filter -->
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
										<th><?php echo set_value('pageNameAr'); ?></th>
										<th><?php echo set_value('pageNameEn'); ?></th>
										<th><?php echo set_value('icon'); ?></th>
										<th><?php echo set_value('orderBy'); ?></th>
										<th><?php echo set_value('active'); ?></th>
										<th><?php echo set_value('showAsMenuItem'); ?></th>
										<th><?php echo set_value('parentName'); ?></th>
										<th><?php echo set_value('url'); ?></th>
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

<!-- Delete Items Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo set_value('deleteWebPageMenu'); ?></h3>
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
				<h4 class="modal-title"><?php echo set_value("addNewMenu"); ?></h4>
				
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="col-md-6">
						<div class="mb-6">
							<label for="pageNameAr" class="form-label"><?php echo set_value("pageNameAr"); ?> <span class="text-danger"> * </span></label>
							<input type="text" class="form-control form-control-sm" id="pageNameAr" placeholder="<?php echo set_value("pageNameAr"); ?>">
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="mb-6">
							<label for="pageNameEn" class="form-label"><?php echo set_value("pageNameEn"); ?> <span class="text-danger"> * </span></label>
							<input type="text" class="form-control form-control-sm" id="pageNameEn" placeholder="<?php echo set_value("pageNameEn"); ?>" required>
						</div>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-md-6">
						<div class="mb-4">
							<label for="icon" class="form-label"><?php echo set_value("icon"); ?></label>
							<select class="form-control js-example-basic-single form-small select" id="icon">
								<option value=""> <?php echo set_value('select'); ?></option>
								<?php echo include_once('dropdown_icons.php'); ?>
							</select>
						</div>
					</div>
				
					<div class="col-md-6">
						<div class="mb-4">
							<label for="orderBy" class="form-label"><?php $orderBy=set_value("orderBy"); echo $orderBy= str_replace("<br/>"," ",$orderBy); set_value("orderBy"); ?> <span class="text-danger"> * </span></label>
							<input type="number" class="form-control form-control-sm" id="orderBy" placeholder="<?php echo $orderBy; ?>" required>
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-md-12">
						<div class="mb-4">
							<label for="url" class="form-label"><?php echo set_value("url"); ?></label>
							<input type="text" class="form-control form-control-sm" id="url" placeholder="<?php echo set_value("url"); ?>">
						</div>
					</div>
				</div>
					
				<div class="row">	
					<div class="col-md-12">
						<div class="mb-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="active" checked>
								<label class="form-check-label" for="active"><span class="text-danger"> * </span>
									<?php echo set_value("active"); ?>
								</label>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="mb-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="showAsMenuItem" checked>
								<label class="form-check-label" for="showAsMenuItem"><span class="text-danger"> * </span>
									<?php echo set_value("showAsMenuItem"); ?>
								</label>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="mb-4">
							<label for="parentName" class="form-label"><?php echo set_value("parentName"); ?></label>
							<select class="form-control js-example-basic-single form-small select" id="parentName">
							</select>
							<input type="hidden" id="setSelect" value="<?php echo set_value("select"); ?>" >
						</div>
					</div>
					
					
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><?php echo set_value("close"); ?></button>&nbsp; 
					<input class="btn btn-primary" type="submit" id='submit' value="<?php echo set_value('add'); ?>" />
					<input type='hidden' id='id' value="0" />
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
	var pageNameAr=$('#pageNameAr').val();
	var pageNameEn=$('#pageNameEn').val();
	var icon=$('#icon').val();
	var orderBy=$('#orderBy').val();
	var showAsMenuItem=$('#showAsMenuItem').is(":checked");
	var parentId=$('#parentName').val();
	var url=$('#url').val();
	var active=$("#active").is(":checked");
	
	if(pageNameEn=='' || orderBy=='')
	{
		showMessage('Invalid Input');
		return;
	}
	var id=$('#id').val();
	if(id>0)
		var action="update";
	else 
		var action="add";
	$.ajax({
		type:"POST",
		url: "MenuDB.php",
		data: { action:action,pageNameAr:pageNameAr,pageNameEn:pageNameEn,icon:icon,orderBy:orderBy,
				showAsMenuItem:showAsMenuItem,url:url,parentId:parentId,active:active,id:id 
		},
		success: function (data) {
			$("#modalForm")[0].reset();
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

$( document ).ready(function() {
    getData();
	$('#parentName').select2({
        dropdownParent: $('#addModal')
	});
	$('#icon').select2({
        dropdownParent: $('#addModal')
	});
});

function getData()
{
	var myTable = $('#setData').DataTable();
 	var rows = myTable.rows().remove().draw();
	$.ajax({
		type:"POST",
		url: "MenuData.php",
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
	$("#modalForm")[0].reset();
	$('#id').val('0');
	$('#addModal').modal('toggle');
	$('#parentName').val('').change();
	getDataWebPageParient();
}

function edit(id)
{
	getDataWebPageParient();
	$.ajax({
		type:"POST",
		url: "MenuDB.php",
		data: {
				action:'getData',id:id
		},
		success: function (data) {
			console.log(data);
			/////alert(JSON.stringify(data));
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#id').val(this.userId);
					$('#pageNameAr').val(this.webpageDisplayname_ar);
					$('#pageNameEn').val(this.webpageDisplayname_en);
					$('#orderBy').val(this.menuOrderby);
					$('#url').val(this.url);
						$('#icon').val(this.icon).change();
					$('#id').val(this.webpageId);
					if(this.isActive==1)
						$('#active').prop('checked', true);
					else 
						$('#active').prop('checked', false);
					
					if(this.isShownOnMenu==1)
						$('#showAsMenuItem').prop('checked', true);
					else 
						$('#showAsMenuItem').prop('checked', false);
					if((this.parentWebpageId)>0)
						$('#parentName').val([this.parentWebpageId]).change();
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
		url: "MenuDB.php",
		data: {action:'del',id:id},
		success: function (data) {
			/////$('#delete_modal').modal('toggle');
			$('#delete_modal').modal().hide();
			showMessage(data);
			getData();
		}
	});
}
function getDataWebPageParient()
{
	$.ajax({
		type:"POST",
		url: "dropdown_webpageParent.php",
		data:{ setSelect: $('#setSelect').val() },
		success: function (data) {
			$('#parentName').html(data);
		}
	});
}

</script>