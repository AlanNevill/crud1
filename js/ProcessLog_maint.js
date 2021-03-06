// ProcessLog_maint.js
"use strict";

var messType = '1', alarmRaised = '1', limitNum, ProcessLogRows, ProcessLogRow;


// get the selected messType on selection change event
$('#messType').on("change", function() {

  if ($(this).val() === '-1') {
    $('#output1').html('Selected messType is not valid');
  } 
  else {
    // a valid messType has been selected so save in global variable
    messType = $(this).val();
    showProcessLog2();
  }
});


// get the selected alarmRaised on selection change event
$('#alarmRaised').on("change", function() {

  if ($(this).val() === '-1') {
    $('#output1').html('Invalid alarmRaised number');
  } 
  else {
    // alarmRaised is a valid selection
    alarmRaised = $(this).val();
    showProcessLog2();
  }
});


// download to CSV file
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

      var blob = typeof File === "function"
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

/////////////////////////////////////////////////////////////////////////////////////////
// Function to get all the ProcessLog rows. Display in table tblProcessLog. Called onload
function showProcessLog2() {
  // clear any previous ProcessLog rows
  $("#tbodyProcessLog").empty();

  $.post("../include/ProcessLog_ajax.php", {
    method: "ProcessLog_select",
    messType: messType,
    alarmRaised: alarmRaised
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
          `ProcessLog_select success: ${funcReturn.success}, message: ${funcReturn.message}`
        );
    } else {
      // an error was returned
      $("#output1")
        .empty()
        .append(funcReturn.message);

      alert("An error has occurred\n\n" + funcReturn.message);
    }
  })
  .fail(error =>
    $('#output1')
      .empty()
      .append('Error - ' + error.statusText)
  )

  ; // end of $.post
} // end of showProcessLog function


//////////////////////////
// update a ProcessLog row
function ProcessLog_upd($row) {
  let cols = $row.find("td"); // create array of columns in the row
  let IdNum       = cols[0].innerHTML; // get the PK, ProcessLog from column 0
  let UserId      = cols[3].innerHTML;
  let Application = cols[5].innerHTML;
  let Remarks     = cols[7].innerHTML;
  let AlarmRaised = cols[8].innerHTML;

  $.post("../include/ProcessLog_ajax.php", {
    method:     "ProcessLog_upd",
    IdNum:       IdNum,
    UserId:      UserId,
    Application: Application,
    Remarks:     Remarks,
    AlarmRaised: AlarmRaised 
  }).done(function(response) {
    let returnArray = JSON.parse(response);
    $("#output1")
      .empty()
      .append("Update - success: " + returnArray.success + ", message: " + returnArray.message);
  });
} // end of function ProcessLog_upd


////////////////////////////
// delete the ProcessLog row
function ProcessLog_del($row) {
  let cols = $row.find("td"); // create array of columns in the row
  let IdNum       = cols[0].innerHTML; // get the PK, ProcessLog from column 0

  $.post("../include/ProcessLog_ajax.php", {
    method:     "ProcessLog_delete",
    IdNum:       IdNum
  }).done(function(response) {
    let returnArray = JSON.parse(response);
    $("#output1")
      .empty()
      .append("Delete - success: " + returnArray.success + ", message: " + returnArray.message);
  });
} // end of function ProcessLog_del


/////////////////
// document ready
$(function() {
  // set up functions which add and remove class 'loading' when ajax starts or stops. See MGF.css
  let body = $('body');
  $(document).on({
    ajaxStart: function() {
      body.addClass('loading');
    },
    ajaxStop: function() {
      body.removeClass('loading');
    }
  });

  // setup the table as editable with options
  $("#tblProcessLog").SetEditable({
    columnsEd: "3,5,7,8", // Ex.: "1,2,3,4,5"

    onEdit: function($row) {
      ProcessLog_upd($row);
    }, // end of onEdit function

    onDelete: function($row) {
      ProcessLog_del($row);
    } // end of onDelete
  });

  $('[data-toggle="tooltip"]').tooltip();
}); // end of document ready
