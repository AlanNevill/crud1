// DeviceId_maint.js
"use strict";

var DeviceIdRows, DeviceIdRow;

// Function to get all the DeviceId rows. Display in table tblDeviceId
function showDeviceId() {
  // clear any previous DeviceId rows
  $("#tbodyDeviceId").empty();

  $.post("../include/DeviceId_ajax.php", {
    method: "DeviceId_selectAll"
    }).done(function(data) {
      // load the DeviceId rows into the HTML table tblDeviceId

      let funcReturn = JSON.parse(data);

      if (funcReturn.success === true) {
        DeviceIdRows = funcReturn.DeviceIdRows;

        // iterate over the rows adding them to the table
        DeviceIdRows.forEach(DeviceIdRow => {
          let newRow = `
            <tr DeviceId='${DeviceIdRow.DeviceId}'> 
              <td>${DeviceIdRow.DeviceId}</td> 
              <td>${DeviceIdRow.DeviceDesc}</td>
              <td>${DeviceIdRow.UserAgentString}</td>
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
          $(newRow).appendTo($("#tbodyDeviceId"));
        }); // end of foreach DeviceIdRows

        $("#output1").append("Select - Success: " + funcReturn.success + ", Message: " + funcReturn.message);

      } else {
        $("#output1")
          .empty()
          .append(funcReturn.message);
        alert("An error has occurred\n\n" + funcReturn.message);
      }
    }) // end of .done
    .fail(error =>
      $('#output1')
        .empty()
        .append('Error - ' + error.statusText)
    ) // end of .fail  
  ; // end of $.post
} // end of showDeviceId function


// update the new DeviceDesc into the DB table
function deviceDesc_upd($row) {
  const cols = $row.find("td"); // create array of columns in the row
  const deviceId = cols[0].innerHTML; // get the PK, DeviceId from column 0
  const newDeviceDesc = cols[1].innerHTML; // get the new device desc from column 1

  $.post("../include/DeviceId_ajax.php", {
    method: "DeviceId_updDeviceDesc",
    PK_deviceId: deviceId,
    newDesc: newDeviceDesc
  }).done(function(response) {
    let returnArray = JSON.parse(response);
    $("#output1")
      .empty()
      .append("Update - success: " + returnArray.success);
  })  // end of .done
  .fail(error =>
    $('#output1')
      .empty()
      .append('Update error - ' + error.statusText)
  )   // end of .fail
  ; // end of $.post
} // end of function deviceDesc_upd


// delete the DeviceId row
function deviceId_del($row) {
  const cols = $row.find("td"); // create array of columns in the row
  const deviceId = cols[0].innerHTML; // get the PK, DeviceId from column 0

  $.post("../include/DeviceId_ajax.php", {
    method: "DeviceId_delete",
    PK_deviceId: deviceId
  }).done(function(response) {
    let returnArray = JSON.parse(response);
    $("#output1")
      .empty()
      .append("Delete - success: " + returnArray.success);
  })   // end of .done
  .fail(error =>
    $('#output1')
      .empty()
      .append('Delete error - ' + error.statusText)
  )   // end of .fail
  ; // end of $.post
} // end of function deviceId_del


// document ready
$(function() {
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
  $("#tblDeviceId").SetEditable({
    columnsEd: "1", // Ex.: "1,2,3,4,5"
    onEdit: function($row) {
      deviceDesc_upd($row);
    }, // end of onEdit function
    onDelete: function($row) {
      deviceId_del($row);
    }
  });

  $('[data-toggle="tooltip"]').tooltip();
}); // end of document ready
