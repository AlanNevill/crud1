// ProcessLog_maint.js
"use strict";

var ProcessLogRows, ProcessLogRow;

function showProcessLog() {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../include/ProcessLog_ajax.php", true);
  xhr.responseType = "arraybuffer";
  xhr.onload = function() {
    if (this.status === 200) {
      var filename = "";
      var disposition = xhr.getResponseHeader("Content-Disposition");
      if (disposition && disposition.indexOf("attachment") !== -1) {
        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
        var matches = filenameRegex.exec(disposition);
        if (matches != null && matches[1])
          filename = matches[1].replace(/['"]/g, "");
      }
      var type = xhr.getResponseHeader("Content-Type");

      var blob =
        typeof File === "function"
          ? new File([this.response], filename, { type: type })
          : new Blob([this.response], { type: type });
      if (typeof window.navigator.msSaveBlob !== "undefined") {
        // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
        window.navigator.msSaveBlob(blob, filename);
      } else {
        var URL = window.URL || window.webkitURL;
        var downloadUrl = URL.createObjectURL(blob);

        if (filename) {
          // use HTML5 a[download] attribute to specify filename
          var a = document.createElement("a");
          // safari doesn't support this yet
          if (typeof a.download === "undefined") {
            window.location = downloadUrl;
          } else {
            a.href = downloadUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
          }
        } else {
          window.location = downloadUrl;
        }

        setTimeout(function() {
          URL.revokeObjectURL(downloadUrl);
        }, 100); // cleanup
      }
    } else {
      // header status not 200 OK instead set to 204
      $("#output1")
        .empty()
        .append(
          "No E or W rows to report - check ProcessLog for more information"
        );
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("method=ProcessLog_reportErrors");
}

// TODO - Function to get all the ProcessLog rows. Display in table tblProcessLog
function showProcessLog2() {
  // clear any previous ProcessLog rows
  $("#tbodyProcessLog").empty();

  $.post("../include/ProcessLog_ajax.php", {
    method: "ProcessLog_selectAll",
    MessType: "W"
  }).done(function(data) {
    // update the ProcessLog rows into table tblProcessLog

    let funcReturn = JSON.parse(data);

    if (funcReturn.success === true) {
      ProcessLogRows = funcReturn.ProcessLogRows;

      // iterate over the rows updating the table
      ProcessLogRows.forEach(ProcessLogRow => {
        let newRow = `
          <tr ProcessLog='${ProcessLogRow.IdNum}'> 
            <td>${ProcessLogRow.IdNum}</td> 
            <td>${ProcessLogRow.MessDateTime}</td>
            <td>${ProcessLogRow.MessType}</td>
            <td>${ProcessLogRow.Application}</td> 
            <td>${ProcessLogRow.Routine}</td>
            <td>${ProcessLogRow.UserId}</td>
            <td>${ProcessLogRow.ErrorMess}</td> 
            <td>${ProcessLogRow.Remarks}</td>
            <td>${ProcessLogRow.AlarmRaised}</td>
            <td>
              <div class="btn-group pull right">
                <button id="bEdit" type="button" class="btn btn-sm btn-success" onclick="rowEdit(this);">
                  <i class="fa fa-pencil-square"></i>
                </button>
                <button id="bElim" type="button" class="btn btn-sm btn-danger" onclick="rowElim(this);">
                  <i class="fa fa-trash"></i>
                </button>
                <button id="bAcep" type="button" class="btn btn-sm btn-info" style="display:none;" onclick="rowAcep(this);">
                  <i class="fa fa-check-square"></i>
                </button>
                <button id="bCanc" type="button" class="btn btn-small btn-warning" style="display:none;" onclick="rowCancel(this);">
                  <i class="fa fa-undo"></i>
                </button>
              </div>
            </td>
          </tr>
        `;

        // add the row to the table body
        $(newRow).appendTo($("#tbodyProcessLog"));
      }); // end of foreach ProcessLogRows

      $("#output1")
        .empty()
        .append(
          `Select success: ${funcReturn.success} message: ${funcReturn.message}`
        );
    } else {
      // an error was returned
      $("#output1")
        .empty()
        .append(funcReturn.message);
      alert("An error has occured\n\n" + funcReturn.message);
    }
  });
} // end of showProcessLog function

// update the new DeviceDesc into the DB
function deviceDesc_upd($row) {
  const cols = $row.find("td"); // create array of columns in the row
  const ProcessLog = cols[0].innerHTML; // get the PK, ProcessLog from column 0
  const newDeviceDesc = cols[1].innerHTML; // get the new device desc from column 1

  $.post("ProcessLog_ajax.php", {
    method: "ProcessLog_updDeviceDesc",
    PK_ProcessLog: ProcessLog,
    newDesc: newDeviceDesc
  }).done(function(response) {
    let returnArray = JSON.parse(response);
    $("#output1")
      .empty()
      .append("Update success: " + returnArray.success);
  });
} // end of function deviceDesc_upd

// delete the ProcessLog row
function ProcessLog_del($row) {
  const cols = $row.find("td"); // create array of columns in the row
  const ProcessLog = cols[0].innerHTML; // get the PK, ProcessLog from column 0

  $.post("ProcessLog_ajax.php", {
    method: "ProcessLog_delete",
    PK_ProcessLog: ProcessLog
  }).done(function(response) {
    let returnArray = JSON.parse(response);
    $("#output1")
      .empty()
      .append("Delete success: " + returnArray.success);
  });
} // end of function ProcessLog_del

// document ready
$(document).ready(function() {
  // set up functions which add and remove class 'loading' when ajax starts or stops. See crud1.css
  let body = $("body");
  $(document).on({
    ajaxStart: function() {
      body.addClass("loading");
    },
    ajaxStop: function() {
      body.removeClass("loading");
    }
  });

  // setup the table as editable with options
  $("#tblProcessLog").SetEditable({
    columnsEd: "1", // Ex.: "1,2,3,4,5"
    onEdit: function($row) {
      deviceDesc_upd($row);
    }, // end of onEdit function
    onDelete: function($row) {
      ProcessLog_del($row);
    }
  });

  // go to bookingMaint.php to view or make a booking for the selected cottageNum and DateSat of the row clicked
  $("#tbodyProcessLog").delegate(".ProcessLog_upd", "click", function() {
    // get the DateSat from the nearest TR and update into bookingMaint session storage
    let rowTR = $(this).closest("tr");
    bookingMaint.dateSat = rowTR.attr("dateSat");

    // call bookingMaint.php to view or book for the selected week and cottageNum
    window.location = "bookingMaint.php";
  }); // end of ProcessLog_upd button click event

  $('[data-toggle="tooltip"]').tooltip();
}); // end of document ready
