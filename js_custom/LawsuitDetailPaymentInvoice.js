var paidStatusPayment = 0;
$(document).ready(function () {
  getCurrency();
//   getData();
//   getContractData();
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
        $("div.dataTables_filter").css("float", "right");
        $("div.dataTables_filter").css("margin-bottom", "3px");
        var copyButton =
          '<a href="#" class="table-btn-action-icon" onclick="printInvoice();"><span><i class="fa fa-copy"></i></span></a>';
        var csvButton =
          '<a href="#" class="table-btn-action-icon" onclick="printInvoice();"><span><i class="fa fa-file-csv"></i></span></a>';
        var printButton =
          '<a href="#" class="table-btn-action-icon" onclick="printInvoice();"><span><i class="fa fa-print"></i></span></a>';
        $(printButton).insertAfter(".dataTables_filter");
        $(csvButton).insertAfter(".dataTables_filter");
        $(copyButton).insertAfter(".dataTables_filter");
      }
    },
  });
}

$("body").on("click", "#addButton", function () {
  $("#form")[0].reset();
  $("#id").val("0");
  $("#lsStage").val("").change();
  $("#mode").val("").change();
  $("#LawsuitPaymentModal").modal("toggle");
});

function customer_type_modal() {
  ////alert(paidStatusPayment);
  $("#customer_type")[0].reset();
  //$('#id').val("0");
  $("#add_customer_type").modal("toggle");
}

$("body").on("click", "#UpdatePayment", function () {
  $("#formContract")[0].reset();
  $("#idContract").val("0");
  $("#stage").val("").change();
  ///$("#ContractTermsEn").summernote('code','');
  ////$("#ContractTermsAr").summernote('code','');
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

function getContractData() {
  var lsDId = $("#lsDId").val();
  $.ajax({
    type: "POST",
    url: "LawsuitDetailPaymentData.php",
    data: {
      getContractData: "1",
      lsDId: lsDId,
    },
    success: function (data) {
      ///console.log(data);
      const jsonObject = JSON.parse(data);
      if (jsonObject.status) {
        data_array = jsonObject["data"];
        jQuery.each(data_array, function () {
          $("#amountContract").val(this.amountContract);
          if (this.taxValue != "") $("#taxValue").val(this.taxValue);
          $("#contractAmountIncludingTax").val(this.totalContractAmount);
        });
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

function getContractData() {
  var myTable = $("#setData").DataTable();

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
