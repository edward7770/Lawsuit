var paidStatusPayment = 0;
$(document).ready(function () {
  getCurrency();
  getData();
  getContractData();
  getPaymentData();
  $("#lsStage").select2({
    dropdownParent: $("#LawsuitPaymentModal"),
  });
  $("#mode").select2({
    dropdownParent: $("#LawsuitPaymentModal"),
  });

  $("#stage").select2({
    dropdownParent: $("#LawsuitAmountModal"),
  });
  $("#stageEdit").select2({
    dropdownParent: $("#LawsuitAmountModalEdit"),
  });
});
$(document)
  .ajaxStart(function () {
    $("#ajax_loader").show();
    $("#submit").prop("disabled", true);
  })

  .ajaxStop(function () {
    $("#ajax_loader").hide();
    $("#submit").prop("disabled", false);
  });
var currency = "";
function getCurrency() {
  $.ajax({
    type: "POST",
    url: "get4setCurrency.php",
    data: { getCurrency: 1 },
    async: false,
    success: function (data) {
      if (data == "0") showMessage(data);
      else currency = data;
    },
    error: function (jqXHR, exception) {
      errorShow(jqXHR, exception);
    },
  });
}

function getData() {
  var myTable = $("#setData").DataTable();
  var rows = myTable.rows().remove().draw();
  $(".table-btn-action-icon").hide();
  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentData.php",
    data: { lsMId: $("#lsMId").val() },
    success: function (data) {
      if (!$.trim(data) == "") {
        data = data.replace(/^\s*|\s*$/g, "");
        data = data.replace(/\\r\\n/gm, "");
        var expr = "</tr>\\s*<tr";
        var regEx = new RegExp(expr, "gm");
        var newRows = data.replace(regEx, "</tr><tr");
        $("#setData").DataTable().rows.add($(newRows)).draw();
        // $(copyButton).insertAfter(".dataTables_filter")
      }
      // $("div.dataTables_filter").css("float", "right");

      // var csvButton =
      //   '<a href="LawsuitPaymentExcelPrint.php?lsMId=' +
      //   $("#lsMId").val() +
      //   "&lsDId=" +
      //   $("#lsDId").val() +
      //   '" class="table-btn-action-icon" onclick="printExcelPaymentReport();"><span><i class="fa fa-file-csv"></i></span></a>';
      // var printButton =
      //   '<a href="#" class="table-btn-action-icon" onclick="printInvoiceModal(' +
      //   $("#lsMId").val() +
      //   "," +
      //   $("#lsDId").val() +
      //   ');"><span><i class="fa fa-print"></i></span></a>';
      // $(printButton).insertAfter(".dataTables_filter");
      // $(csvButton).insertAfter(".dataTables_filter");
    },
  });
}

function getContractData() {
  var myTable = $("#setDataContract").DataTable();
  // $("#setDataContract").hide();
  var rows = myTable.rows().remove().draw();
  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentData.php",
    data: { lsMId: $("#lsMId").val(), contractData: 1 },
    success: function (data) {
      if (!$.trim(data) == "") {
        data = data.replace(/^\s*|\s*$/g, "");
        data = data.replace(/\\r\\n/gm, "");
        var expr = "</tr>\\s*<tr";
        var regEx = new RegExp(expr, "gm");
        var newRows = data.replace(regEx, "</tr><tr");
        $("#setDataContract").DataTable().rows.add($(newRows)).draw();
      }
    },
  });
}

$("body").on("click", "#addButton", function () {
  var invoiceNumber =  $("#invoiceNumber").val();
  // const min = 100;
  // const max = 999;
  // const randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;
  let randomNumber = '1';

  $("#form")[0].reset();
  $("#id").val("0");
  $("#lsStage").val("").change();
  $("#mode").val("").change();
  if(invoiceNumber === '') {
    $("#invoiceNumber").val(randomNumber.toString().padStart(3, '0'));
  } else {
    currentNumber = parseInt(invoiceNumber) + 1;
    $("#invoiceNumber").val(currentNumber.toString().padStart(3, '0'));
  }
  $("#LawsuitPaymentModal").modal("toggle");
});

function add() {
  var date = $("#date").val();
  var mode = $("#mode").val();
  var amount = $("#amount").val();
  var invoiceNumber = $("#invoiceNumber").val();
  var remarks = $("#remarks").val();
  var lsStageArray = $("#lsStage").val().split(",");
  var lsStage = lsStageArray[0];
  var lsDId = lsStageArray[1];
  var id = $("#id").val();
  var paymentContractId = $("#paymentContractId").val();
  var paidStatus = $('input[name="paidStatus"]:checked').val();
  if (paidStatus == 1) var paidStatusStage = "paidStatus";
  else if (paidStatus == 2) var paidStatusStage = "paidStatusAll";
  var lsMId = $("#lsMId").val();

  ////////alert("date="+date+"mode="+mode+"amount="+amount+"remarks="remarks+"id="+id+"lsStage="+lsStage+"paidStatus="+paidStatus+"paidStatusStage="+paidStatusStage+"lsMId="+lsMId);

  if (
    !date ||
    !mode ||
    !amount ||
    !id ||
    !lsStage ||
    !paidStatus ||
    !paidStatusStage ||
    !paymentContractId ||
    !lsMId ||
    !lsDId
  ) {
    showMessage("Invalid Input");
    return;
  }
  if (id > 0) var action = "edit";
  else var action = "add";

  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentDB.php",
    data: {
      action: action,
      id: id,
      date: date,
      mode: mode,
      amount: amount,
      invoiceNumber: invoiceNumber,
      contractId: paymentContractId,
      remarks: remarks,
      lsStage: lsStage,
      paidStatusStage: paidStatusStage,
      paidStatus: paidStatus,
      lsMId: lsMId,
      lsDId: lsDId,
    },
    success: function (data) {
      console.log(data);
      let response = JSON.parse(data);
      showMessage(response.message);
      var data_validate = response.message.replace(/[^\d.]/g, "");
      if (data_validate == "101") {
        return;
      } else if (data_validate == "1") {
        $("#LawsuitPaymentModal").modal("toggle");
        // $("#invoiceNumber").val(response.receiptNo);
        getData();
        getPaymentData();
      }
    },
    error: function (jqXHR, exception) {
      if (jqXHR.status === 0) {
        alert("Not connect.\n Verify Network");
      } else if (jqXHR.status == 404) {
        alert("Requested page not found. [404]");
      } else if (jqXHR.status == 500) {
        alert("Internal Server Error [500]");
      } else if (exception === "parsererror") {
        alert("Requested JSON parse failed.");
      } else if (exception === "timeout") {
        alert("Time out error.");
      } else if (exception === "abort") {
        alert("Ajax request aborted");
      }
    },
  });
}

function edit(id) {
  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentDB.php",
    data: {
      action: "getData",
      id: id,
    },
    success: function (data) {
      ////console.log(data);
      const jsonObject = JSON.parse(data);
      if (jsonObject.status) {
        data_array = jsonObject["data"];
        jQuery.each(data_array, function () {
          $("#id").val(this.id);
          $("#lsStage")
            .val(this.stageId + "," + this.lsDId)
            .change();
          $("#date").val(this.date);
          $("#mode").val(this.mode).change();
          $("#amount").val(this.amount);
          $("#invoiceNumber").val(this.invoiceNumber);
          $("#remarks").val(this.remarks);
          if (this.fullPaid == 1) $("#ispaidStatusAll").prop("checked", true);
          else if (this.paid == 1) $("#paidStatus").prop("checked", true);
        });
        $("#LawsuitPaymentModal").modal("toggle");
      }
    },
  });
}
/*
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
			url: "LawsuitDetailPaymentDB.php",
			data: {action:'del',id:id},
			success: function (data) {
				getData();
				getPaymentData();
				showMessage(data);
				$('#msg_modal').modal('toggle');
			}
		});
	}
	*/
function customer_type_modal() {
  ////alert(paidStatusPayment);
  $("#customer_type")[0].reset();
  //$('#id').val("0");
  $("#add_customer_type").modal("toggle");
}

$("body").on("click", "#UpdatePayment", function () {
  $("#idContract").val("0");
  $("#stage").val("").change();
  ///$("#ContractTermsEn").summernote('code','');
  ////$("#ContractTermsAr").summernote('code','');
  var lawsuit_code =  $("#invoice_number_list").html();
  var invoiceNumber =  $("#contractInvoiceNumber").val();
  $("#formContract")[0].reset();
  let randomNumber = '1';

  if(invoiceNumber === '') {
    $("#contractInvoiceNumber").val(lawsuit_code.trim() + '-' + randomNumber.toString().padStart(3, '0'));
  } else {
    currentNumber = parseInt(invoiceNumber.split(lawsuit_code.trim() + '-')[1]) + 1;
    $("#contractInvoiceNumber").val(lawsuit_code.trim() + '-' + currentNumber.toString().padStart(3, '0'));
  }

  $("#LawsuitAmountModal").modal("toggle");
  ////getContractData();
  getContractContent();
});

$("body").on("click", "#DeleteLawsuit", function () {
  deleteLawsuit();
});

$("#amountContract").on("input", function () {
  CalculateTax();
});
$("#taxValue").change(function () {
  CalculateTax();
});

function deleteLawsuit() {
  var lsMId = $("#lsMId").val();
  var lsDId = $("#lsDId").val();
  var countPaymentRecords = 0;
  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentDB.php",
    data: {
      deleteLawsuit: "1",
      lsMId: lsMId,
      lsDId: lsDId
    },
    success: function (data) {
      console.log(data);
      showMessage(data);
      var result = data.replace(/\D/g, "");
      if (result == 1) {
        setTimeout(function () {
          window.location.replace("Lawsuit.php");
        }, 1200);
      }
    },
  });
}

function CalculateTax() {
  var amountContract = parseFloat($("#amountContract").val());
  if (!amountContract) return $("#contractAmountIncludingTax").val(0);
  var taxValue = parseFloat($("#taxValue").val());
  taxValue = parseFloat(taxValue / 100);
  taxValue = taxValue * amountContract; //toFixed(2)
  //$('#contractAmountIncludingTax').val(taxValue+amountContract);
  $("#contractAmountIncludingTax").val(
    parseFloat(taxValue + amountContract).toFixed(3)
  );
  $("#taxValueAmount").val(parseFloat(taxValue).toFixed(3));
}

// function getContractData()
// {
// 	var lsDId=$('#lsDId').val();
// 	$.ajax({
// 		type:"POST",
// 		url: "LawsuitDetailPaymentData.php",
// 		data: {
// 			getContractData:'1',lsDId:lsDId
// 		},
// 		success: function (data) {
// 			///console.log(data);
// 			const jsonObject = JSON.parse(data);
// 			if(jsonObject.status)
// 			{
// 				data_array = jsonObject['data'];
// 				jQuery.each(data_array, function() {
// 					$('#amountContract').val(this.amountContract);
// 					if(this.taxValue!="")
// 					$('#taxValue').val(this.taxValue);
// 					$('#contractAmountIncludingTax').val(this.totalContractAmount);
// 				});
// 			}
// 		}
// 	});
// }

function updateContract() {
  ////var apendId="";
  var id = $("#idContract").val();
  if (id > 0) {
    var action = "edit";
    ////apendId="Edit";
  } else var action = "add";

  var amountContract = $("#amountContract").val();
  var taxValue = $("#taxValue").val();
  var taxValueAmount = $("#taxValueAmount").val();
  var totContAmount = $("#contractAmountIncludingTax").val();
  var contractDate = $("#contractDate").val();
  var contractInvoiceNumber = $("#contractInvoiceNumber").val();
  var lsDId = $("#lsDId").val();
  var lsMId = $("#lsMId").val();
  var termEn = $("#ContractTermsEn").summernote("code");
  var termAr = $("#ContractTermsAr").summernote("code");
  var stage = $("#contract_stage").val();

  if (
    !amountContract ||
    !taxValue ||
    !taxValueAmount ||
    !totContAmount ||
    !lsDId ||
    !lsMId ||
    !contractDate ||
    !stage
  ) {
    showMessage("Invalid Input");
    return;
  }

  var ContractData = {
    action: action,
    id: id,
    stage: stage,
    amountContract: $("#amountContract").val(),
    taxValue: $("#taxValue").val(),
    taxValueAmount: $("#taxValueAmount").val(),
    totContAmount: $("#contractAmountIncludingTax").val(),
    contractDate: contractDate,
    contractInvoiceNumber: contractInvoiceNumber,
    //////lsDId:$('#lsDId').val(),
    lsMId: $("#lsMId").val(),
    termEn: $("#ContractTermsEn").summernote("code"),
    termAr: $("#ContractTermsAr").summernote("code"),
  };
  var formData = new FormData();
  formData.append("ContractData", JSON.stringify(ContractData));
  var contractFile = $("#contractFile")[0].files[0];
  if (contractFile) {
    formData.append("contractFile", $("#contractFile")[0].files[0]);
  }

  ///console.log(formData);
  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentDB.php",
    processData: false,
    contentType: false,
    data: formData,
    success: function (data) {
      /////console.log(data);
      showMessage(data);
      $("#LawsuitAmountModal").modal("toggle");
      getPaymentData();
      getContractData();
    },
    error: function (jqXHR, exception) {
      if (jqXHR.status === 0) {
        alert("Not connect.\n Verify Network");
      } else if (jqXHR.status == 404) {
        alert("Requested page not found. [404]");
      } else if (jqXHR.status == 500) {
        alert("Internal Server Error [500]");
      } else if (exception === "parsererror") {
        alert("Requested JSON parse failed.");
      } else if (exception === "timeout") {
        alert("Time out error.");
      } else if (exception === "abort") {
        alert("Ajax request aborted");
      }
    },
  });
}

function getPaymentData() {
  var lsMId = $("#lsMId").val();
  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentData.php",
    data: {
      getPaymentData: "1",
      lsMId: lsMId,
    },
    success: function (data) {
      ///console.log(data);

      const jsonObject = JSON.parse(data);
      if (jsonObject.status) {
        data_array = jsonObject["data"];
        jQuery.each(data_array, function () {
          if (this.totalContractAmount)
            $("#totalAmount").html(
              parseFloat(this.totalContractAmount).toFixed(3) + " " + currency
            );
          else
            $("#totalAmount").html(parseFloat("0").toFixed(3) + " " + currency);
          $("#paidAmount").html(
            parseFloat(this.paidAmount).toFixed(3) + " " + currency
          );
          if (this.totalDues)
            $("#dueAmount").html(
              parseFloat(this.totalDues).toFixed(3) + " " + currency
            );
          else
            $("#dueAmount").html(parseFloat("0").toFixed(3) + " " + currency);
        });
      }
    },
  });
}

$("#lsStage").change(function () {
  var isFullStage = "";
  if (this.value != "") {
    $.ajax({
      type: "POST",
      url: "LawsuitDetailPaymentDB.php",
      ////async: false,
      data: {
        isFullStage: 1,
        stageId: this.value,
      },
      success: function (data) {
        console.log(data);
        ////isFullStage=data;
        if (data < 0) {
          showMessage("Full Stage is not defined");
          return;
        }
        if (data == "1") {
          $("#ispaidStatusAll").prop("checked", true);
          $("input[name=paidStatus]").prop("disabled", true);
        } else {
          $("#paidStatus").prop("checked", true);
          $("input[name=paidStatus]").prop("disabled", true);
        }
        if (data == "") {
          $("input[name=paidStatus]").prop("disabled", false);
          $("input[name=paidStatus]").prop("checked", false);
        }
      },
      error: function (jqXHR, exception) {
        if (jqXHR.status === 0) {
          alert("Not connect.\n Verify Network");
        } else if (jqXHR.status == 404) {
          alert("Requested page not found. [404]");
        } else if (jqXHR.status == 500) {
          alert("Internal Server Error [500]");
        } else if (exception === "parsererror") {
          alert("Requested JSON parse failed.");
        } else if (exception === "timeout") {
          alert("Time out error.");
        } else if (exception === "abort") {
          alert("Ajax request aborted");
        }
      },
    });
  }
});

function editContract(id) {
  ///$("#ContractTermsEn").summernote('code','');
  ////$("#ContractTermsAr").summernote('code','');
  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentDB.php",
    data: {
      action: "getContractData",
      id: id,
    },
    success: function (data) {
      ///console.log(data);
      const jsonObject = JSON.parse(data);
      if (jsonObject.status) {
        data_array = jsonObject["data"];
        jQuery.each(data_array, function () {
          $("#idContract").val(this.id);
          $("#stage").val(this.lsStageId).change();
          $("#amountContract").val(this.amount);
          $("#taxValue").val(this.taxValue);
          $("#taxValueAmount").val(this.taxValueAmount);
          $("#contractAmountIncludingTax").val(this.totalAmount);
          $("#contractDate").val(this.contractDate);
          $("#contractInvoiceNumber").val(this.contractInvoiceNumber);
          $("#ContractTermsEn").summernote("code", this.contractEn);
          $("#ContractTermsAr").summernote("code", this.contractAr);
          /*
						if(this.contractEn=="")
							$("#ContractTermsEn").summernote('code',$('#tors_en').val());
						else 
							$("#ContractTermsEn").summernote('code',this.contractEn);
						if(this.contractAr=="")
							$("#ContractTermsAr").summernote('code',$('#tors_ar').val());
						else 
							$("#ContractTermsAr").summernote('code',this.contractAr);
						*/
        });
        $("#LawsuitAmountModal").modal("toggle");
      }
    },
  });
}
var delAction = "";
function delModal(id, del) {
  $("#delete_modal").modal("toggle");
  $("#del_button").val(id, del);
  delAction = del;
}

function del() {
  if (delAction == "contract") action = "contract";
  else action = "payment";
  var id = $("#del_button").val();
  var lsMId = $("#lsMId").val();
  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentDB.php",
    data: { action: "del", del: action, id: id, lsMId: lsMId },
    success: function (data) {
      getContractData();
      getData();
      getPaymentData();
      showMessage(data);
      $("#msg_modal").modal("toggle");
    },
  });
}
function printContracts(contractId, lawsuitcode) {
  $.ajax({
    type: "POST",
    url: "getContractData.php",
    ////data: { lsDId:lsDId },
    data: { contractId: contractId },
    success: function (data) {
      console.log(data);
      htmlTextArray = [];
      const jsonObject = JSON.parse(data);
      if (jsonObject.status) {
        data_array = jsonObject["data"];
        jQuery.each(data_array, function (index, item) {
          ////if(item['contractEn'])
          htmlTextArray.push(item["contractEn"]);
          ////if(item['contractAr'])
          htmlTextArray.push(item["contractAr"]);
        });
        if (htmlTextArray.length > 0) {
          var fileName = "Contracts " + lawsuitcode;
          generateHTML_docZip(htmlTextArray[0], htmlTextArray[1], fileName);
        } else {
          showMessage("Empty Contract");
        }
      }
    },
  });
}

function getContractContent() {
  $.ajax({
    type: "POST",
    url: "getContractTerm.php",
    data: {
      getContractTerm: "1",
    },
    success: function (data) {
      /////console.log(data);
      const jsonObject = JSON.parse(data);
      if (jsonObject.status) {
        data_array = jsonObject["data"];
        jQuery.each(data_array, function () {
          $("#ContractTermsEn").summernote("code", this.tors_en);
          $("#ContractTermsAr").summernote("code", this.tors_ar);
        });
      }
    },
  });
}

$(document).on("click", "#print", function () {
  ///var activeTabContent = $('.tab-pane.active.show .summernote').code();
  var activeTabContent = $(".tab-pane.active.show .summernote").summernote(
    "code"
  );
  printContract(activeTabContent);
});

function printContract(activeTabContent) {
  var printWindow = window.open("", "_blank");
  printWindow.document.write("<html><head><title>&nbsp;</title>");
  printWindow.document.write(
    "<style>@media print { .current-time, .page, .about-blank { display: none; } }</style>"
  );
  printWindow.document.write("</head><body>");

  // Modify activeTabContent to remove unwanted content
  // For example, you can remove the current time, page numbers, and about:blank text
  var modifiedContent = activeTabContent.replace(
    /<span class="current-time">.*<\/span>/g,
    ""
  ); // Remove elements with class="current-time"
  modifiedContent = modifiedContent.replace(
    /<span class="page">.*<\/span>/g,
    ""
  ); // Remove elements with class="page"
  modifiedContent = modifiedContent.replace(
    /<span class="about-blank">.*<\/span>/g,
    ""
  ); // Remove elements with class="about-blank"

  printWindow.document.write(modifiedContent);
  printWindow.document.write("</body></html>");

  // Close printWindow when user cancels the print dialog
  printWindow.onafterprint = function () {
    printWindow.close();
  };

  printWindow.document.close();
  printWindow.print();
  checkPrintDialog();

  // Remove about:blank text after the print dialog is opened
  setTimeout(function () {
    var aboutBlankElements =
      printWindow.document.querySelectorAll(".about-blank");
    aboutBlankElements.forEach(function (element) {
      element.parentNode.removeChild(element);
    });
  }, 1000); // Adjust the timeout as needed
}

function printInvoiceModal() {
  // $.ajax({
  //   type: "POST",
  //   url: "LawsuitDetailPayment-invoice.php",
  //   data: { lsMId: isMid, lsDid: isDid },
  //   success: function (data) {
  //     // Open a new window for printing
  //     var printWindow = window.open("", "_blank");
  //     printWindow.document.write(
  //       "<html><head><title>&nbsp;</title></head><body>"
  //     );

  //     // Write the received content to the print window
  //     printWindow.document.write(data);

  //     printWindow.document.write("</body></html>");
  //     printWindow.document.close();

  //     // Print the content after a short delay
  //     setTimeout(function () {
  //       printWindow.print();
  //       printWindow.close();
  //     }, 1000); // Adjust the delay as needed
  //   },
  // });
  var invoiceNumber = $.trim($("#invoice_number_list").html());
  $("#form_invoice_number").val(invoiceNumber);
  $("#LawsuitPrintModal").modal("toggle");
}

// function printLawsuitInvoice(lsMId, lsDId) {
//   $.ajax({
//     type: "POST",
//     url: "LawsuitDetailPayment-invoice.php",
//     data: {
//       lsMId: lsMId,
//       lsDid: lsDId,
//       paymentId: paymentId
//     },
//     success: function (data) {
//       // Open a new window for printing
//       var printWindow = window.open("", "_blank");
//       printWindow.document.write(
//         "<html><head><title>&nbsp;</title></head><body>"
//       );

//       // Write the received content to the print window
//       printWindow.document.write(data);

//       printWindow.document.write("</body></html>");
//       printWindow.document.close();

//       // Print the content after a short delay
//       setTimeout(function () {
//         printWindow.print();
//         printWindow.close();
//       }, 1000);
//     },
//   });
// }

function printInvoice() {
  var isMid = $("#lsMId").val();
  var isDid = $("#lsDId").val();
  var invoiceNumber = $("#form_invoice_number").val();
  var invoiceDate = $("#form_invoice_date").val();

  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentDB.php", // URL of the session update script
    data: {
      action: "updateSessionInvoice",
      lsMId: isMid,
      isDid: isDid,
      invoiceNumber: invoiceNumber,
      invoiceDate: invoiceDate,
    },
    success: function (response) {
      var result = JSON.parse(response);
      if (result.status == "success") {
        $("#invoice_number_list").html(invoiceNumber);
        $("#invoice_number").html(invoiceNumber);

        $.ajax({
          type: "POST",
          url: "LawsuitPaymentReceiptPrint.php",
          data: {
            lsMId: isMid,
            lsDid: isDid,
            invoiceNumber: invoiceNumber,
            invoiceDate: invoiceDate,
          },
          success: function (data) {
            $("#formLawsuitPrint")[0].reset();
            $("#LawsuitPrintModal").modal("toggle");
            // Open a new window for printing
            var printWindow = window.open("", "_blank");
            printWindow.document.write(
              "<html><head><title>&nbsp;</title></head><body>"
            );

            // Write the received content to the print window
            printWindow.document.write(data);

            printWindow.document.write("</body></html>");
            printWindow.document.close();

            // Print the content after a short delay
            setTimeout(function () {
              printWindow.print();
              printWindow.close();
            }, 1000); // Adjust the delay as needed

            getData();
          },
        });
      } else {
        // Handle the error response
        alert("Error updating session: " + result.message);
      }
    },
    error: function () {
      alert("Error communicating with the server.");
    },
  });
}

function printPaymentReceipt(paymentId) {
  var lsMId = $("#lsMId").val();
  var lsDId = $("#lsDId").val();

  $.ajax({
    type: "POST",
    url: "PaymentReceiptPrint.php",
    data: {
      lsMId: lsMId,
      lsDid: lsDId,
      paymentId: paymentId,
    },
    success: function (data) {
      $("#formLawsuitPrint")[0].reset();
      // Open a new window for printing
      var printWindow = window.open("", "_blank");
      printWindow.document.write(
        "<html><head><title>&nbsp;</title></head><body>"
      );

      // Write the received content to the print window
      printWindow.document.write(data);

      printWindow.document.write("</body></html>");
      printWindow.document.close();

      // Print the content after a short delay
      setTimeout(function () {
        printWindow.print();
        printWindow.close();
      }, 1000);
    },
  });
}

function printContractReceipt(contractId) {
  var lsMId = $("#lsMId").val();
  var lsDId = $("#lsDId").val();

  $.ajax({
    type: "POST",
    url: "LawsuitContractReceiptPrint.php",
    data: {
      lsMId: lsMId,
      lsDid: lsDId,
      contractId: contractId,
    },
    success: function (data) {
      $("#formLawsuitPrint")[0].reset();
      // Open a new window for printing
      var printWindow = window.open("", "_blank");
      printWindow.document.write(
        "<html><head><title>&nbsp;</title></head><body>"
      );

      // Write the received content to the print window
      printWindow.document.write(data);

      printWindow.document.write("</body></html>");
      printWindow.document.close();

      // Print the content after a short delay
      setTimeout(function () {
        printWindow.print();
        printWindow.close();
      }, 1000);
    },
  });
}

function printExcelPaymentReport() {
  var lsMId = $("#lsMId").val();
  var lsDId = $("#lsDId").val();

  $.ajax({
    type: "POST",
    url: "LawsuitPaymentExcelPrint.php",
    data: { lsMId: lsMId, lsDId: lsDId },
    success: function (data) {
      // console.log('123123123');
    },
  });
}

function saveInvoice() {
  var isMid = $("#lsMId").val();
  var isDid = $("#lsDId").val();
  var invoiceNumber = $("#form_invoice_number").val();
  var invoiceDate = $("#form_invoice_date").val();

  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentDB.php",
    data: {
      action: "updateSessionInvoice",
      lsMId: isMid,
      isDid: isDid,
      invoiceNumber: invoiceNumber,
      invoiceDate: invoiceDate,
    },
    success: function (response) {
      var result = JSON.parse(response);
      if (result.status == "success") {
        $("#invoice_number_list").html(invoiceNumber);
        $("#invoice_number").html(invoiceNumber);
        $("#LawsuitPrintModal").modal("toggle");
        getData();
      } else {
        // Handle the error response
        alert("Error updating session: " + result.message);
      }
    },
    error: function () {
      alert("Error communicating with the server.");
    },
  });
}
