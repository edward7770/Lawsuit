
function showLawsuit() {
	$("#divLawsuit").show();
	$("#divCustomer").hide();
	$("#divEmployee").hide();
	$("#divSession").hide();
	$("#divLSTask").hide();
	$("#divDocument").hide();
	$("#divConsultation").hide();
	$("#divGenTask").hide();
};

function showCustomer() {
	$("#divLawsuit").hide();
	$("#divCustomer").show();
	$("#divEmployee").hide();
	$("#divSession").hide();
	$("#divLSTask").hide();
	$("#divDocument").hide();
	$("#divConsultation").hide();
	$("#divGenTask").hide();
};
function showEmployee() {
	$("#divLawsuit").hide();
	$("#divCustomer").hide();
	$("#divEmployee").show();
	$("#divSession").hide();
	$("#divSession").hide();
	$("#divLSTask").hide();
	$("#divDocument").hide();
	$("#divConsultation").hide();
	$("#divGenTask").hide();
};

function showSession() {
	$("#divLawsuit").hide();
	$("#divCustomer").hide();
	$("#divEmployee").hide();
	$("#divSession").show();
	$("#divLSTask").hide();
	$("#divDocument").hide();
	$("#divConsultation").hide();
	$("#divGenTask").hide();
};

function showLSTask() {
	$("#divLawsuit").hide();
	$("#divCustomer").hide();
	$("#divEmployee").hide();
	$("#divSession").hide();
	$("#divLSTask").show();
	$("#divDocument").hide();
	$("#divConsultation").hide();
	$("#divGenTask").hide();
};

function showDocument() {
	$("#divLawsuit").hide();
	$("#divCustomer").hide();
	$("#divEmployee").hide();
	$("#divSession").hide();
	$("#divLSTask").hide();
	$("#divDocument").show();
	$("#divConsultation").hide();
	$("#divGenTask").hide();
};

function showConsultation() {
	$("#divLawsuit").hide();
	$("#divCustomer").hide();
	$("#divEmployee").hide();
	$("#divSession").hide();
	$("#divLSTask").hide();
	$("#divDocument").hide();
	$("#divConsultation").show();
	$("#divGenTask").hide();
};

function showGenTask() {
	$("#divLawsuit").hide();
	$("#divCustomer").hide();
	$("#divEmployee").hide();
	$("#divSession").hide();
	$("#divLSTask").hide();
	$("#divDocument").hide();
	$("#divConsultation").hide();
	$("#divGenTask").show();
};

var lsCount=$('#lsCount').val();
$('#lawsuit').html('('+lsCount+')');
var lsCount=$('#lsCountCust').val();
$('#customer').html('('+lsCount+')');
var lsCount=$('#lsCountEmp').val();
$('#employee').html('('+lsCount+')');

var lsCount=$('#lsCountSession').val();
$('#session').html('('+lsCount+')');

var lsCount=$('#lsCountLSTask').val();
$('#LSTask').html('('+lsCount+')');

var lsCount=$('#lsCountDoc').val();
$('#docFile').html('('+lsCount+')');

var lsCount=$('#lsCountCons').val();
$('#constant').html('('+lsCount+')');

var lsCount=$('#lsCountGenTask').val();
$('#task').html('('+lsCount+')');

/*
	<input type='hidden' id="searchId" value="<?php if(isset($_POST['id'])) echo $_POST['id']; else "0"; ?>" />
	
	var searchId=0;
	searchId=$('#searchId').val();
	data: {searchId:searchId},
	$where="";
	if(isset($_POST['searchId']) && !empty($_POST['searchId']))
	{
	$where=" AND c.`customerId`=:id";
	}
	
	if(!empty($where))
	$stmt->bindParam(":id",$_POST['searchId'],PDO::PARAM_INT);
	
*/
