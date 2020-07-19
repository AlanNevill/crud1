// CottageWeek_maint.js
"use strict";

var cottageNum, cottageWeekRows, cottageWeekRow;


// get the selected cottageNum on selection change event
$('#cottageNum').on("change", function() {

  if ($(this).val() === '-1') {
    $('#output1').html('Selected cottage number is not valid');
  } 
  else {
    // a valid cottage number has been selected so save in global variable
    cottageNum = $(this).val();
    showCottageWeek();
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
// Function to get all the CottageWeek rows for the selected cottage. Display in table tblCottageWeek. Called on selection change
function showCottageWeek() {
  // clear any previous CottageWeek rows
  $("#tbody").empty();

  $.post("../include/CottageWeek_ajax.php", {
    method: "CottageWeek_select",
    CottageNum: cottageNum
  }).done(function(data) {
    // update the CottageWeek rows into table tblCottageWeek

    let funcReturn = JSON.parse(data);

    if (funcReturn.success === true) {
      cottageWeekRows = funcReturn.cottageWeekRows;

      // iterate over the rows updating the table
      cottageWeekRows.forEach(cottageWeekRow => {
        let newRow = `
          <tr> 
            <td>${cottageWeekRow.DateSat}</td> 
            <td>${cottageWeekRow.bShortBreaksAllowed}</td>
            <td>${cottageWeekRow.RentDay}</td>
            <td>${cottageWeekRow.RentWeek}</td> 
            <td>
              <div class="btn-group pull right">
                <button id="bEdit" type="button" class="btn btn-sm btn-success" onclick="rowEdit(this);">
                  <i class="fa fa-pencil-square"></i>
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
        $(newRow).appendTo($("#tbody"));
      }); // end of foreach CottageWeekRows

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
  }).fail(error =>
    $('#output1')
      .empty()
      .append('Error - ' + error.statusText)
  )

  ; // end of $.post
} // end of showProcessLog function


//////////////////////////
// update a CottageWeek row
function CottageWeek_upd($row) {
  let cols = $row.find("td"); // create array of columns in the row
  let dateSat     = cols[0].innerHTML; 
  let shortBreaks = cols[1].innerHTML;
  let rentDay     = cols[2].innerHTML;
  let rentWeek    = cols[3].innerHTML;

  $.post("../include/CottageWeek_ajax.php", {
    method:       "CottageWeek_upd",
    DateSat:      dateSat,
    CottageNum:   cottageNum,
    ShortBreaks:  shortBreaks,
    RentDay:      rentDay,
    RentWeek:     rentWeek 
  }).done(function(response) {

    // check for valid JSON response
    if (!isJSON(response)) {
      $('#output1')
      .empty()
      .html(response);

      return;
    }

    let returnArray = JSON.parse(response);
    $("#output1")
      .empty()
      .append("Update - success: " + returnArray.success + ", message: " + returnArray.message);
  }).always(function() {
    
    // todo - reload CottageWeek table

  }).fail(error =>
    $('#output1')
      .empty()
      .append('Error - ' + error.statusText)
  ) 

  ; // end of $.post

} // end of function CottageWeek_upd




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
  $("#tblCottageWeek").SetEditable({
    columnsEd: "1,2,3", // Ex.: "1,2,3,4,5"

    onEdit: function($row) {
      CottageWeek_upd($row);
    } // end of onEdit function

  });

  $('[data-toggle="tooltip"]').tooltip();
}); // end of document ready
