$(document).ready(function () {
  getCurrency();
  getData();
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
  var myTable = $("#example").DataTable();
  var rows = myTable.rows().remove().draw();
  $.ajax({
    type: "POST",
    url: "PaymentData.php",
    data: { getData: "1" },
    success: function (data) {
      if (!$.trim(data) == "") {
        data = data.replace(/^\s*|\s*$/g, "");
        data = data.replace(/\\r\\n/gm, "");
        var expr = "</tr>\\s*<tr";
        var regEx = new RegExp(expr, "gm");
        var newRows = data.replace(regEx, "</tr><tr");
        $("#example").DataTable().rows.add($(newRows)).draw();
      }
      getPayment();
    },
    error: function (jqXHR, exception) {
      errorShow(jqXHR, exception);
    },
  });
}

function viewLSDetails(lsMId, lsDId) {
  var form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "LawsuitDetail.php");

  // Generate a unique name for the window
  var windowName = "formresult_" + lsMId;
  // setting form target to a window named 'formresult'
  form.setAttribute("target", windowName);

  var hiddenField = document.createElement("input");
  hiddenField.setAttribute("name", "lsMId");
  hiddenField.setAttribute("value", lsMId);
  hiddenField.setAttribute("hidden", "hidden");
  form.appendChild(hiddenField);
  document.body.appendChild(form);

  var hiddenField = document.createElement("input");
  hiddenField.setAttribute("name", "lsDId");
  hiddenField.setAttribute("value", lsDId);
  hiddenField.setAttribute("hidden", "hidden");
  form.appendChild(hiddenField);
  document.body.appendChild(form);

  // creating the 'formresult' window with custom features prior to submitting the form
  //window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
  window.open("test.html", windowName);

  form.submit();
}
function viewLSDetailsPayment(lsMId, lsDId) {
  // var form = document.createElement("form");
  // form.setAttribute("method", "post");
  // form.setAttribute("action", "LawsuitDetailPayment.php");

  // // Generate a unique name for the window
  // var windowName = "formresult_" + lsMId;
  // // setting form target to a window named 'formresult'
  // form.setAttribute("target", windowName);

  // var hiddenField = document.createElement("input");
  // hiddenField.setAttribute("name", "lsMId");
  // hiddenField.setAttribute("value", lsMId);
  // hiddenField.setAttribute("hidden", "hidden");
  // form.appendChild(hiddenField);
  // document.body.appendChild(form);

  // var hiddenField = document.createElement("input");
  // hiddenField.setAttribute("name", "lsDId");
  // hiddenField.setAttribute("value", lsDId);
  // hiddenField.setAttribute("hidden", "hidden");
  // form.appendChild(hiddenField);
  // document.body.appendChild(form);

  // // creating the 'formresult' window with custom features prior to submitting the form
  // //window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
  // window.open('test.html', windowName);

  var form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "LawsuitDetailPayment.php");

  var hiddenField1 = document.createElement("input");
  hiddenField1.setAttribute("name", "lsMId");
  hiddenField1.setAttribute("value", lsMId);
  hiddenField1.setAttribute("type", "hidden");
  form.appendChild(hiddenField1);

  var hiddenField2 = document.createElement("input");
  hiddenField2.setAttribute("name", "lsDId");
  hiddenField2.setAttribute("value", lsDId);
  hiddenField2.setAttribute("type", "hidden");
  form.appendChild(hiddenField2);

  // Append the form to the document body
  document.body.appendChild(form);

  // Submit the form
  form.submit();

  // form.submit();
}

function newStage(lsMId, lsCode) {
  var form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "LawsuitAdd.php");

  // Generate a unique name for the window
  var windowName = "formresult_" + lsMId;
  // setting form target to a window named 'formresult'
  form.setAttribute("target", windowName);

  var hiddenField = document.createElement("input");
  hiddenField.setAttribute("name", "lsMId");
  hiddenField.setAttribute("value", lsMId);
  hiddenField.setAttribute("hidden", "hidden");
  form.appendChild(hiddenField);
  document.body.appendChild(form);

  var hiddenField = document.createElement("input");
  hiddenField.setAttribute("name", "lsCode");
  hiddenField.setAttribute("value", lsCode);
  hiddenField.setAttribute("hidden", "hidden");
  form.appendChild(hiddenField);
  document.body.appendChild(form);

  // creating the 'formresult' window with custom features prior to submitting the form
  //window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
  window.open("test.html", windowName);

  form.submit();
}

function viewDetails(mId) {
  var myTable = $("#LawsuitMasterDetailModalData").DataTable();
  var rows = myTable.rows().remove().draw();
  $.ajax({
    type: "POST",
    url: "LawsuitPaymentMasterModalData.php",
    data: { mId: mId },

    success: function (data) {
      console.log(data);
      ////return;
      if (!$.trim(data) == "") {
        data = data.replace(/^\s*|\s*$/g, "");
        data = data.replace(/\\r\\n/gm, "");
        var expr = "</tr>\\s*<tr";
        var regEx = new RegExp(expr, "gm");
        var newRows = data.replace(regEx, "</tr><tr");
        $("#LawsuitMasterDetailModalData")
          .DataTable()
          .rows.add($(newRows))
          .draw();
      }
      ////$('#add_customer').modal('toggle');
      $("#LawsuitMasterDetailModal").modal("toggle");
    },
    error: function (jqXHR, exception) {
      errorShow(jqXHR, exception);
    },
  });
}

function viewLSEdit(lsMId, lsDId) {
  var form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "LawsuitEdit.php");

  // Generate a unique name for the window
  var windowName = "formresultLS" + lsDId;
  // setting form target to a window named 'formresult'
  form.setAttribute("target", windowName);

  var hiddenField = document.createElement("input");
  hiddenField.setAttribute("name", "lsMId");
  hiddenField.setAttribute("value", lsMId);
  hiddenField.setAttribute("hidden", "hidden");
  form.appendChild(hiddenField);
  document.body.appendChild(form);

  var hiddenField = document.createElement("input");
  hiddenField.setAttribute("name", "lsDId");
  hiddenField.setAttribute("value", lsDId);
  hiddenField.setAttribute("hidden", "hidden");
  form.appendChild(hiddenField);
  document.body.appendChild(form);

  // creating the 'formresult' window with custom features prior to submitting the form
  //window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
  window.open("test.html", windowName);

  form.submit();
}
var o = "rtl" === $("html").attr("data-textdirection");
function printContracts(lsDId) {
  $.ajax({
    type: "POST",
    url: "getContractData.php",
    data: { lsDId: lsDId },
    success: function (data) {
      htmlTextArray = [];
      const jsonObject = JSON.parse(data);
      if (jsonObject.status) {
        data_array = jsonObject["data"];
        jQuery.each(data_array, function (index, item) {
          if (item["contractEn"]) htmlTextArray.push(item["contractEn"]);
          if (item["contractAr"]) htmlTextArray.push(item["contractAr"]);
        });
        if (htmlTextArray.length > 0) {
          var fileName = "contract";
          generateHTML_docZip(htmlTextArray[0], htmlTextArray[1], fileName);
        } else {
          toastr.error("Empty Contract", "", {
            closeButton: !0,
            tapToDismiss: !1,
            rtl: o,
          });
        }
      }
    },
    error: function (jqXHR, exception) {
      errorShow(jqXHR, exception);
    },
  });
}

function viewLSDetailsExpense(lsDId) {
  var form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "LawsuitDetailExpense.php");

  // Generate a unique name for the window
  var windowName = "formresult_" + lsDId;
  // setting form target to a window named 'formresult'
  form.setAttribute("target", windowName);

  var hiddenField = document.createElement("input");
  hiddenField.setAttribute("name", "lsDId");
  hiddenField.setAttribute("value", lsDId);
  hiddenField.setAttribute("hidden", "hidden");
  form.appendChild(hiddenField);
  document.body.appendChild(form);

  // creating the 'formresult' window with custom features prior to submitting the form
  //window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
  window.open("test.html", windowName);

  form.submit();
}

function getPayment() {
  $.ajax({
    type: "POST",
    url: "PaymentData.php",
    data: { getPayment: 1 },
    success: function (data) {
      const jsonObject = JSON.parse(data);
      if (jsonObject.status) {
        // data_array = jsonObject['data'];
        // console.log(data_array);
        // jQuery.each(data_array, function() {
        // $('#totalAmount').html(parseFloat(this.totalCasesAmount).toFixed(3)+" "+currency);
        // $('#paidAmount').html(parseFloat(this.totalPayment).toFixed(3)+" "+currency);
        // $('#dueAmount').html(parseFloat(this.outstandingAmount).toFixed(3)+" "+currency);
        // ///$('#totalAmountMonthly').html(parseFloat(this.monthlyCasesAmount).toFixed(3)+" "+currency);
        // ///$('#totalAmountToday').html(parseFloat(this.todayCasesAmount).toFixed(3)+" "+currency);
        // ///$('#dueAmountMonthly').html(parseFloat(this.monthlyPayment).toFixed(3)+" "+currency);
        // ///$('#dueAmountToday').html(parseFloat(this.dailyPayment).toFixed(3)+" "+currency);
        // });
        $("#totalAmount").html(
          jsonObject.totalCasesAmount.toFixed(3) + " " + currency
        );
        $("#paidAmount").html(
          jsonObject.totalPayment.toFixed(3) + " " + currency
        );
        $("#dueAmount").html(
          jsonObject.outstandingAmount.toFixed(3) + " " + currency
        );
      } else {
        $("#totalIncome").html("0");
        $("#monthlyIncome").html("0");
        $("#todayIncome").html("0");
      }
    },
    error: function (jqXHR, exception) {
      errorShow(jqXHR, exception);
    },
  });
}

function errorShow(jqXHR, exception) {
  if (jqXHR.status === 0) {
    showMessage("Not connect.\n Verify Network");
  } else if (jqXHR.status == 404) {
    showMessage("Requested page not found. [404]");
  } else if (jqXHR.status == 500) {
    showMessage("Internal Server Error [500]");
  } else if (exception === "parsererror") {
    showMessage("Requested JSON parse failed.");
  } else if (exception === "timeout") {
    showMessage("Time out error.");
  } else if (exception === "abort") {
    showMessage("Ajax request aborted");
  }
}
