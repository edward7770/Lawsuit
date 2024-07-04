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
				<h5><?php echo set_value('manageRoleAccess'); ?></h5>
				<div class="list-btn">
					<ul class="filter-list">
						
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
				<div class="col-lg-4 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="role" class="form-label"><?php echo set_value("role"); ?><span class="text-danger"> * </span></label>
						<select class="form-control js-example-basic-single form-small select" id='role'>
							<option value=""><?php echo set_value("select"); ?></option>
							<?php echo include_once('dropdown_role.php'); ?>
						</select>
					</div>
				</div>
			</div>
			<?php /*
			<div class="role-testing d-flex align-items-center justify-content-between showHide">
				<h6></h6>
				<p class="showHide"><label class="custom_check "><input type="checkbox" id="checkAll"><span class="checkmark"></span></label>Allow All Modules</p>
			</div>
			*/
			?>
			
		</div>
		<div class="row showHide">
			<div class="card-table">
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-center table-hover datatable" id='setData'>
							<thead class="thead-light">
								<tr>
									<th>#</th>
									<th><?php echo set_value('webPages'); ?></th>
									<th><?php echo set_value('url'); ?></th>
									<th><?php echo set_value('grantAccess'); ?></th>
								</tr>
							</thead>
							<tbody > </tbody>
							
						</table>
						
						<!-- /Table -->
					</div>
					<div class="text-center showHide" >
						<button type="submit" class="btn btn-primary" onclick="save();"><?php echo set_value('update'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->
<?php include_once('footer.php'); ?>
<script>
	
$(document).ready(function() {
	$('.showHide').hide();
		
});
$(document).ajaxStart(function() {
	$("#ajax_loader").show();
	$('#submit').prop("disabled", true);
	})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#submit').prop("disabled", false);		
});
	
	function getData(roleId)
	{
		$('.showHide').show();
		$('#setData').show();
		var myTable = $('#setData').DataTable();
		var rows = myTable.rows().remove().draw();
		
		$.ajax({
			type:"POST",
			url: "ManageRoleData.php",
			data:{ roleId:roleId },
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
	
	
	$("#role").on('change', function() {
		if(!this.value)
		{
			$('#setData').hide();
			$('.showHide').hide();
			return;
		}
		getData(this.value);
	});
	
	
	var selected = [];
	function save()
	{
		var length=selected.length;
		var roleId=$('#role').val();
		if(length>0 && roleId>0)
		$.ajax({
			type: 'POST',
			url: 'ManageRoleDB.php',
			data: {"role":roleId,"selected":selected,"length":selected.length},
			success: function(data){
				selected = [];
				if(data>0)
				{
					////getData(roleId);
					showMessage("1Access has been granted successfully");
				}
				else
				{
					showMessage("Error: Failed to add/update grant. Please try again");
				}
			}
		});
		else 
		{ 
			showMessage("No Changes are saved b/c you have not change anything");
		}
	}
	
	$("body").on("change",".gaccess",function(){
		if (!selected.includes(this.id))
		selected.push(this.id);
		else 
		{	
			var index = selected.indexOf(this.id);
			if (index > -1)
			selected.splice(index, 1);
		}
	});
	/*
	$("body").on("change","#checkAll",function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
		var checked=0;
		$(".gaccess:checkbox:checked").each(function() {
			checked=1;
			if (!selected.includes(this.id))
				selected.push(this.id);
		});
		if(!checked)
			selected=[];
	});
	*/
	</script>	