	var viewtask=1;
	var viewSession=1;
	$(function () {
		////$(".hijri-date-input").hijriDatePicker();
		$('#dateHSession').hijriDatePicker({
			hijri:true,
			format:'D/M/YYYY',
			hijriFormat:'iD/iM/iYYYY'
		});
		collapsedId=$('#collapsedId').val();
		collapsedData=$('#collapsedData').val();
		if(collapsedId,collapsedData)
		{
			if(collapsedData=='task')
			{
				getModalData('LawsuitTaskModalData');
				viewtask++;
			}
			else 
			{
				viewSession++;
				getModalData('LawsuitSessionModalData');
			}
		}
		{
			viewSession++;
			getModalData('LawsuitSessionModalData');
		}
		
	});
	var rowCountCheck=2;
	$('a[href="#tabImages"]').click(function(){
		var rowCount = $("#imageTabel tr").length;
		if(rowCount<=rowCountCheck)
		getModalData('LawsuitImageModalData');
	});
	
	
	$('a[href="#tabSessions"]').click(function(){
		if(viewSession==1)
		{
			getModalData('LawsuitSessionModalData');
			viewSession++;
		}
	});
	
	$('a[href="#tabTask"]').click(function(){
		if(viewtask==1)
		{
			getModalData('LawsuitTaskModalData');
			viewtask++;
		}
	});
	
	$('a[href="#tabPapers"]').click(function(){
		var rowCount = $("#paperTabel tr").length;
		if(rowCount<=rowCountCheck)
		getModalData('LawsuitPaperModalData');
	});
	$('a[href="#tabNumbers"]').click(function(){
		var rowCount = $("#numberTabel tr").length;
		if(rowCount<=rowCountCheck)
		getModalData('LawsuitNumberModalData');
	});
	$('a[href="#tabRuling"]').click(function(){
		var rowCount = $("#rulingTabel tr").length;
		if(rowCount<=rowCountCheck)
		getModalData('LawsuitRulingModalData');
	});
	$('a[href="#tabObjections"]').click(function(){
		var rowCount = $("#objectionTabel tr").length;
		if(rowCount<=rowCountCheck)
		getModalData('LawsuitObjectionModalData');
	});
	$('a[href="#tabVetoList"]').click(function(){
		var rowCount = $("#vetoTabel tr").length;
		if(rowCount<=rowCountCheck)
		getModalData('LawsuitVetoModalData');
	});
	$('a[href="#tabClearance"]').click(function(){
		var rowCount = $("#clearanceTabel tr").length;
		if(rowCount<=rowCountCheck)
		getModalData('LawsuitClearanceModalData');
	});
	
	$("body").on("click","#sessionButton",function(){
		$("#modalFormSession")[0].reset();
		$('#SessionId').val('0');
		$("#sessionDetails").summernote('code', '');
		$('#LawsuitSessionModal').modal('toggle');
	});
	
	$("body").on("click","#createImageButton",function(){
		$("#modalCreateImage")[0].reset();
		$("#taskDescription").summernote('code','');
		$('#taskAssignedTo').val('').change();
		$('#imageId').val('0');
		$('#LawsuitImageModal').modal('toggle');
	});
	
	$("body").on("click","#createTaskButton",function(){
		$("#modalTaskForm")[0].reset();
		$('#taskId').val('0');
		$('#LawsuitTaskModal').modal('toggle');
	});
	
	$("body").on("click","#paperButton",function(){
		$("#modalFormPaper")[0].reset();
		$('#paperId').val('0');
		$("#paperDetails").summernote('code', '');
		$('#LawsuitPaperModal').modal('toggle');
	});
	$("body").on("click","#numberButton",function(){
		$("#modalFormNumber")[0].reset();
		$('#numberId').val('0');
		$('#LawsuitNumberModal').modal('toggle');
	});	
	
	$("body").on("click","#rulingButton",function(){
		$("#modalFormRuling")[0].reset();
		$('#rulingId').val('0');
		$('#fileRuling').val('');
		$("#rulingDetails").summernote('code', '');
		$('#LawsuitRulingModal').modal('toggle');
	});	
	
	$("body").on("click","#objectionButton",function(){
		$("#modalFormObjection")[0].reset();
		$('#objectionId').val('0');
		$("#objectionNotes").summernote('code', '');
		$('#LawsuitObjectionModal').modal('toggle');
	});	
	
	$("body").on("click","#vetoButton",function(){
		$("#modalFormVeto")[0].reset();
		$('#vetoId').val('0');
		$('#fileImageVeto').val('');
		$("#vetoNotes").summernote('code', '');
		$('#LawsuitVetoModal').modal('toggle');
	});	
	
	$("body").on("click","#CFormButton",function(){
		$("#modalFormClearance")[0].reset();
		$('#clearenceId').val('0');
		$('#fileClearance').val('');
		$("#NoteClearance").summernote('code', '');
		$('#LawsuitClearanceModal').modal('toggle');
	});	
	
	
	$(document).ajaxStart(function() {
		$("#ajax_loader").show();
		///$('#submit').prop("disabled", true);
	})

	.ajaxStop(function() {
		$("#ajax_loader").hide();
		////$('#submit').prop("disabled", false);		
	});
	function getModalData(modal)
	{
		var id=$('#dId').val();
		$.ajax({
			type:"POST",
			url: "modals/Data/"+modal+".php",
			data: { id:id },
			success: function (data) {
				////console.log(data);
				$('#'+modal).html(data);
				collapsedId=$('#collapsedId').val();
				collapsedData=$('#collapsedData').val();
				if(collapsedId && collapsedData)
				{
					if(collapsedData=="session")
						$("#headingSession"+collapsedId).collapse("show");
					else if(collapsedData=="task")
					{
						$('a[href="#tabTask"]').tab('show');
						$('#headingTask'+collapsedId).collapse('show');
					}
				}
					 
			}
		});
	}
	function addSession()
	{
		var nameSession		=$('#nameSession').val();
		var dateSession		=$('#dateSession').val();
		/////var dateHSession	=$('#dateHSession').val();
		var timeSession		=$('#timeSession').val();
		/////var placeSession	=$('#placeSession').val(); ,placeSession:placeSession
		var sessionDetails	=$('textarea#sessionDetails').val();
		var dId				=$('#dId').val();
		var id=$('#SessionId').val();
		if(id>0)
		var action="editSession";
		else 
		var action="addSession";
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: 
			{
				action:action,dId:dId,nameSession:nameSession,dateSession:dateSession,
				timeSession:timeSession,sessionDetails:sessionDetails,id:id
			},
			success: function (data) {
				$('#LawsuitSessionModal').modal('toggle');
				showMessage(data);
				getModalData('LawsuitSessionModalData');
			}
		})
	}
	
	function addTask()
	{
		var name=$('#name').val();
		var taskDesc=$("#taskDescription").summernote('code');
		var assigTo=$('#taskAssignedTo').val();
		var startDate=$("#taskstartDate").val();
		var dueDate=$('#taskDueDate').val();
		var dId=$('#dId').val();
		var id=$('#taskId').val();
		if(id>0)
			var action="editTask";
		else 
			var action="addTask";
		
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: { action:action,dId:dId,name:name,taskDesc:taskDesc,assigTo:assigTo,
					startDate:startDate,dueDate:dueDate,id:id, 
				},
			success: function (data) {
				////console.log(data);
				$('#LawsuitTaskModal').modal('toggle');
				showMessage(data);
				getModalData('LawsuitTaskModalData');
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
	
	function editSession(id)
	{
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: { getData:1,sessionData:1, id:id },
			success: function (data) {
				///alert(JSON.stringify(data));
				////console.log(data)
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#SessionId').val(this.id);
						$('#nameSession').val(this.sessionName);
						$('#dateSession').val(this.sessionDate);
						$('#dateHSession').val(this.sessionHijriDate);
						$('#timeSession').val(this.sessionTime);
						////$('#placeSession').val(this.sessionPlace);
						$("#sessionDetails").summernote('code', this.sessionDetails);
					});
					$('#LawsuitSessionModal').modal('toggle');
				}
				else 
					showMessage(jsonObject['data']);
			}
		})
	}
	function editTask(id)
	{
		$.ajax({
		type:"POST",
		url: "LawsuitDetailDB.php",
		data: { getData:1,lsTaskData:1, id:id },
		success: function (data) {
			console.log(data); ///
			/////alert(JSON.stringify(data));
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				jQuery.each(data_array, function() {
					$('#taskId').val(this.id);
					$('#name').val(this.taskName);
					$("#taskDescription").summernote('code', this.taskDescription);
					$('#taskstartDate').val(this.startDate).change();
					$('#taskDueDate').val(this.dueDate).change();
					if(this.assignedToId>0)
						$('#taskAssignedTo').val(this.assignedToId).change();
				});
				$('#LawsuitTaskModal').modal('toggle');
			}
			else 
			{
				showMessage(jsonObject['data']);
			}
		}
	});
}
	function del()
	{
		var values=$('#del_button').val();
		var id = values.split(',');
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: {action:'del',id:id[0],op:id[1]},
		
			success: function (data) {
				if(id[1]=='session')
					modal='LawsuitSessionModalData';
				if(id[1]=='task')
					modal='LawsuitTaskModalData';
				if(id[1]=='image')
				modal='LawsuitImageModalData';
				if(id[1]=='paper')
				modal='LawsuitPaperModalData';
				if(id[1]=='number')
				modal='LawsuitNumberModalData';
				if(id[1]=='ruling')
				modal='LawsuitRulingModalData';
				if(id[1]=='objection')
				modal='LawsuitObjectionModalData';
				if(id[1]=='veto')
				modal='LawsuitVetoModalData';
				if(id[1]=='clearance')
				modal='LawsuitClearanceModalData';
				
				//////$('#delete_modal').modal('toggle');
				$('#delete_modal').modal('hide');
				showMessage(data);
				getModalData(modal);
			}
		});
	}
	
	function delModal(id,action)
	{
		
		$('#delete_modal').modal('toggle');
		$('#del_button').val(id+','+action);
	}
	////////////Image/////
	
	function createImage()
	{
		var nameImage		=$('#nameImage').val();
		var dId				=$('#dId').val();
		var id=$('#imageId').val();
		if(id>0)
		var action="editImage";
		else 
		var action="addImage";
		var formData = new FormData();
		formData.append('action',action);
		formData.append('nameImage',nameImage );
		formData.append('dId',dId );
		formData.append('id',id );
		if ($('#fileImage')[0].files.length > 0)
		formData.append('lsDetailImage',$('#fileImage')[0].files[0]);
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				$('#LawsuitImageModal').modal('toggle');
				showMessage(data);
				getModalData('LawsuitImageModalData');
			}
		})
	}
	function editImage(id)
	{
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: { getData:1,imageData:1, id:id },
		
			success: function (data) {
				///console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#imageId').val(this.id);
						$('#nameImage').val(this.imageName);
					});
					$('#fileImage').val('');
					$('#LawsuitImageModal').modal('toggle');
				}
			}
		})
	}
	
	function createPaper()
	{
		var namePaper		=$('#namePaper').val();
		var dId				=$('#dId').val();
		var paperDetails	=$('#paperDetails').val();
		var id=$('#paperId').val();
		if(id>0)
		var action="editPaper";
		else 
		var action="addPaper";
		var formData = new FormData();
		formData.append('action',action);
		formData.append('namePaper',namePaper );
		formData.append('paperDetails',paperDetails );
		formData.append('dId',dId );
		formData.append('id',id );
		if ($('#filePaper')[0].files.length > 0)
		formData.append('lsDetailPaper',$('#filePaper')[0].files[0]);
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				$('#LawsuitPaperModal').modal('toggle');
				showMessage(data);
				getModalData('LawsuitPaperModalData');
			}
		})
	}
	function editPaper(id)
	{
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: { getData:1,paperData:1, id:id },
			success: function (data) {
				///console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#paperId').val(this.id);
						$('#namePaper').val(this.paperName);
						$("#paperDetails").summernote('code', this.paperDetails);
					});
					$('#filePaper').val('');
					$('#LawsuitPaperModal').modal('toggle');
				}
			}
		})
	}
	function addNumber()
	{
		var nameNumber		=$('#nameNumber').val();
		var nameValue		=$('#nameValue').val();
		var notes			=$('#notes').val();
		var dId				=$('#dId').val();
		var id=$('#numberId').val();
		if(id>0)
		var action="editNumber";
		else 
		var action="addNumber";
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: 
			{
				action:action,dId:dId,nameNumber:nameNumber,nameValue:nameValue,notes:notes,id:id
			},
			success: function (data) {
				$('#LawsuitNumberModal').modal('toggle');
				showMessage(data);
				getModalData('LawsuitNumberModalData');
			}
		})
		
	}
	function editNumber(id)
	{
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: { getData:1,numberData:1, id:id },
			success: function (data) {
				////console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#numberId').val(this.id);
						$('#nameNumber').val(this.numberName);
						$('#nameValue').val(this.numberValue);
						$('textarea#notes').val(this.notes);
					});
					$('#LawsuitNumberModal').modal('toggle');
				}
			}
		})
	}
	
	function createRuling()
	{
		var rulingDate		=$('#rulingDate').val();
		var dId				=$('#dId').val();
		var id=				$('#rulingId').val();
		if(id>0)
		var action="editRuling";
		else 
		var action="addRuling";
		var formData = new FormData();
		formData.append('action',action);
		formData.append('rulingDate',rulingDate);
		formData.append('rulingDetails',$('textarea#rulingDetails').val());
		formData.append('dId',dId );
		formData.append('id',id );
		if ($('#fileRulling')[0].files.length > 0)
		formData.append('lsDetailApeal',$('#fileRulling')[0].files[0]);
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				$('#LawsuitRulingModal').modal('toggle');
				showMessage(data);
				getModalData('LawsuitRulingModalData');
			}
		})
	}
	function editRuling(id)
	{
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: { getData:1,rulingData:1, id:id },
			success: function (data) {
				////console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#rulingId').val(this.id);
						$('#rulingDate').val(this.appealdate);
						$("#rulingDetails").summernote('code', this.appealDetails);
					});
					$('#fileRuling').val('');
					$('#LawsuitRulingModal').modal('toggle');
				}
			}
		})
	}
	
	function addObjection()
	{
		var dId=$('#dId').val();
		var id=$('#objectionId').val();
		if(id>0)
		var action="editObjection";
		else 
		var action="addObjection";
		var formData = new FormData();
		formData.append('action',action);
		formData.append('nameObjection',$('#nameObjection').val());
		formData.append('dateObjection',$('#dateObjection').val());
		formData.append('objectionNotes',$('textarea#objectionNotes').val());
		formData.append('dId',dId );
		formData.append('id',id );
		if ($('#fileObjection')[0].files.length > 0)
		formData.append('lsDetailObjection',$('#fileObjection')[0].files[0]);
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				$('#LawsuitObjectionModal').modal('toggle');
				showMessage(data);
				getModalData('LawsuitObjectionModalData');
			}
		})
	}
	function editObjection(id)
	{
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: { getData:1,objectionData:1, id:id },
		
			success: function (data) {
				////console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#objectionId').val(this.id);
						$('#nameObjection').val(this.objectName);
						$('#dateObjection').val(this.endDate);
						$("#objectionNotes").summernote('code', this.objectDetails);
					});
					$('#fileObjection').val('');
					$('#LawsuitObjectionModal').modal('toggle');
				}
			}
		})
	}
	
	function addVeto()
	{
		var dId=$('#dId').val();
		var id=$('#vetoId').val();
		if(id>0)
		var action="editVeto";
		else 
		var action="addVeto";
		var formData = new FormData();
		formData.append('action',action);
		formData.append('nameVeto',$('#nameVeto').val());
		formData.append('dateVeto',$('#dateVeto').val());
		formData.append('vetoNotes',$('textarea#vetoNotes').val());
		formData.append('dId',dId );
		formData.append('id',id );
		if ($('#fileImageVeto')[0].files.length > 0)
		formData.append('lsDetailVeto',$('#fileImageVeto')[0].files[0]);
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				$('#LawsuitVetoModal').modal('toggle');
				showMessage(data);
				getModalData('LawsuitVetoModalData');
			}
		})
	}
	function editVeto(id)
	{
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: { getData:1,vetoData:1, id:id },
			
			success: function (data) {
				////console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#vetoId').val(this.id);
						$('#nameVeto').val(this.vlName);
						$('#dateVeto').val(this.endDate);
						$("#vetoNotes").summernote('code', this.vlDetails);
					});
					$('#fileImageVeto').val('');
					$('#LawsuitVetoModal').modal('toggle');
				}
			}
		})
	}
	function addClearance()
	{
		var dId=$('#dId').val();
		var id=$('#clearenceId').val();
		if(id>0)
		var action="editClearance";
		else 
		var action="addClearance";
		var formData = new FormData();
		formData.append('action',action);
		formData.append('nameClearance',$('#nameClearance').val());
		formData.append('NoteClearance',$('#NoteClearance').val());
		formData.append('dId',dId);
		formData.append('id',id );
		if ($('#fileClearance')[0].files.length > 0)
		formData.append('lsDetailClearance',$('#fileClearance')[0].files[0]);
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				$('#LawsuitClearanceModal').modal('toggle');
				showMessage(data);
				getModalData('LawsuitClearanceModalData');
			}
		})
	}
	function editClearance(id)
	{
		$.ajax({
			type:"POST",
			url: "LawsuitDetailDB.php",
			data: { getData:1,clearanceData:1, id:id },
			success: function (data) {
				///console.log(data);
				const jsonObject = JSON.parse(data);
				if(jsonObject.status)
				{
					data_array = jsonObject['data'];
					jQuery.each(data_array, function() {
						$('#clearenceId').val(this.id);
						$('#nameClearance').val(this.cfName);
						$("#NoteClearance").summernote('code', this.cfDetails);
					});
					$('#fileClearance').val('');
					$('#LawsuitClearanceModal').modal('toggle');
				}
			}
		})
	}
	function email(id)
	{
		$.ajax({
			type:"POST",
			url: "Email/action_page.php",
			data: { id:id },
			beforeSend: function()
			{
				$('#showHide').hide();
			},
			success: function (data) {
				showMessage(data)
			}
		});
	}
	function showDetailModal(val,row)
	{
		if(val=='paper')
		{
			$('#modalHeading').html($('#set_valuePaperDetails').val());
			$('#modelBody').html($('#appealDetail'+row).val());
		}
		else if(val=='number')
		{
			$('#modalHeading').html($('#notesDetail').val());
			$('#modelBody').html($('#numberDetail'+row).val());
		}
		else if(val=='appeal')
		{
			$('#modalHeading').html($('#set_valueAppealDetails').val());
			$('#modelBody').html($('#appealDetail'+row).val());
		}
		else if(val=='objection')
		{
			$('#modalHeading').html($('#notesDetail').val());
			$('#modelBody').html($('#objectionDetail'+row).val());
		}
		else if(val=='veto')
		{
			$('#modalHeading').html($('#notesDetail').val());
			$('#modelBody').html($('#VetoDetail'+row).val());
		}
		else if(val=='cf')
		{
			$('#modalHeading').html($('#notesDetail').val());
			$('#modelBody').html($('#cfDetail'+row).val());
		}
		else if(val=='task')
		{
			$('#modalHeading').html($('#notesDetail').val());
			$('#modelBody').html($('#taskDetail'+row).val());
		}
		$('#msg_detailModal').modal('toggle');
	}