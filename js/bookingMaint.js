// bookingMaint.js
'use strict';

var objDateSat, cottageNum, trIdNum;
var rentalVal; // AutoNumeric value for the cottage rental with £ sign and 2 dp bound to form id=Rental
var cottageWeekRow; // row from CottageWeekRow table for the slected cottage and week

var booCottageNum = false; // is the selected cottage number valid
var booDateSat = false; // is the selected date valid

// Create a new clsDeviceIdCookie class which sets up the deviveId cookie
const _clsDeviceIdCookie = new clsDeviceIdCookie();

// load bookingMaint from session storage
const _clsbookingMaint = new clsbookingMaint();

$(document).ready(function() {
  // hide the booking insert form until a dateSat & cottageNum have been input
  $('.insertForm').hide();

  // autoNumeric for rental field in New Booking Form
  // rentalVal = new AutoNumeric('#Rental > input', AutoNumeric.getPredefinedOptions().British);
  rentalVal = new AutoNumeric('#Rental', AutoNumeric.getPredefinedOptions().numericPos);
  rentalVal.options.maximumValue('9999');
  rentalVal.options.currencySymbol('£');
  rentalVal.options.digitGroupSeparator(',');
  rentalVal.options.decimalPlaces('0');

  // set up functions which add and remove class 'loading' when ajax starts or stops. See crud1.css
  let body = $('body');

  $(document).on({
    ajaxStart: function() {
      body.addClass('loading');
    },
    ajaxStop: function() {
      body.removeClass('loading');
    }
  });

  // show current global version and deviceId on title click or double click
  $('#title').on('dblclick click', function() {
    $(this)
      .next()
      .html(`${_VERSION}-${_clsDeviceIdCookie.deviceId}`)
      .toggle();
  });

  // update the number of remaining chars in the Notes field of the new booking form
  $('textarea').keyup(function() {
    if (this.value.length === 0) {
      $(this)
        .next()
        .empty();
      return false;
    }
    if (this.value.length > $(this).attr('maxlength')) {
      return false;
    }
    $(this)
      .next()
      .html('Remaining characters : ' + ($(this).attr('maxlength') - this.value.length));
  });

  // get the selected dateSat when selection changes
  $('#wcDateSat').change(function() {
    if ($('#wcDateSat').val() === '-1') {
      $('#output1').html('Selected date is not valid');
      objDateSat = null;
      booDateSat = false;
      showFrmNewBooking();
    } else {
      // a valid date has been selected so save in global variable
      objDateSat = moment($('#wcDateSat').val(), 'YYYY-MM-DD');
      booDateSat = true;
      showFrmNewBooking();
    }
  });

  // get the selected cottageNum when selection changes
  $('#cottageNum').change(function() {
    // debugger;

    if (cottageNum === '-1') {
      $('#output1').html('Invalid cottage number: ' + cottageNum);
      booCottageNum = false;
      showFrmNewBooking();
    } else {
      // cottageNum is a valid selection
      cottageNum = $('#cottageNum').val();
      booCottageNum = true;
      showFrmNewBooking();
    }
  });

  /*////////////////////////////////////////////////////////////////////////////
  // clear any new booking messages when dateSat or cottageNum change
  // Called by: $("#cottageNum").change, $("#wcDateSat").change, $("#submitNewBooking").click
  ////////////////////////////////////////////////////////////////////////////*/
  function showFrmNewBooking() {
    $('#newBookingInfo').empty();
    $('#output1').empty();

    if (booDateSat && booCottageNum) {
      // put the date into the frmNewBooking in case the user does an insert
      $('#frmNewBooking input[name=dateSat]').val(moment(objDateSat, 'YYYY-MM-DD').format('YYYY-MM-DD'));

      // remove any previous dates then set up the firstNight & lastNight date selection drop down options
      $('#firstNight option').remove();
      $('#lastNight option').remove();

      for (let i = 0; i < 7; i++) {
        $('#firstNight').append(
          $('<option>', {
            text: moment(objDateSat, 'YYYY-MM-DD')
              .add(i, 'days')
              .format('ddd DD MMM'),
            value: moment(objDateSat, 'YYYY-MM-DD')
              .add(i, 'days')
              .format('YYYY-MM-DD')
          })
        );

        $('#lastNight').append(
          $('<option>', {
            text: moment(objDateSat, 'YYYY-MM-DD')
              .add(i, 'days')
              .format('ddd DD MMM'),
            value: moment(objDateSat, 'YYYY-MM-DD')
              .add(i, 'days')
              .format('YYYY-MM-DD')
          })
        );
      }

      // set selected lastNight to next Friday
      $('#lastNight option:eq(6)').prop('selected', true);

      // put the cottageNum into the frmNewBooking in case the user does an insert
      $('#frmNewBooking input[name=cottageNum]').val(cottageNum);

      // show the new CottageWeek_data section
      $('.CottageWeek_data').show();

      // refresh the existing bookings list for this date and cottage
      $('#submitButton').click();

      // show the new booking form but with add button disabled
      $('.insertForm').show();
    } else {
      $('.insertForm').hide();
    }
  }

  /*////////////////////////////////////////////////////////////////////////////
  //
  // called by function showFrmNewBooking()
  ////////////////////////////////////////////////////////////////////////////*/
  $('#submitButton').click(function(e) {
    e.preventDefault();

    // date or cottage number has changed so empty the bookings table and clear any output1 messages
    $('#tbodyBookings').empty();
    $('#output1').empty();

    // if dateSat and cottage number are both true get
    // 1. CottageWeek data to display as a single info line
    // 2. any existing bookings from CottageBook table
    if (booDateSat && booCottageNum) {
      // 1. get the CottageWeek row

      // var dateSat = moment(objDateSat).format('YYYY-MM-DD');
      $.post('../include/bookingMaint_ajax.php', {
        method: 'cottageWeek_get',
        dateSat: objDateSat._i,
        cottageNum: cottageNum
      }).done(function(returnArray) {
        if (isJSON(returnArray)) {
          let returned = JSON.parse(returnArray);
          if (returned.success) {
            // put the CottageWeekRow data into the dom section CottageWeek_data
            cottageWeekRow = returned.data[0];

            $('#DayRent').html('Rent / day £' + cottageWeekRow.RentDay);
            if (!cottageWeekRow.bShortBreaksAllowed) {
              $('#DayRent').css('text-decoration', 'line-through');
            }
            $('#WeekRent').html('Rent / week £' + cottageWeekRow.RentWeek);

            let allowed = cottageWeekRow.bShortBreaksAllowed ? '' : '*not* ';
            let shortBreak = `Short breaks are ${allowed}allowed this week`;
            $('#bShortBreaksAllowed').html(shortBreak);

            // put the weekly rent into the new booking form
            $('#frmNewBooking #Rental').val(cottageWeekRow.RentWeek);
            rentalVal.set(cottageWeekRow.RentWeek);
          } else {
            $('#output1')
              .empty()
              .append(returned.message);
          }
        } else {
          $('#output1').append('Error occurred - See ErrorLog');
        }
      });

      // 2. get any existing bookings from CottageBook
      $.post('../include/bookingMaint_ajax.php', $('#formGet').serialize())
        .done(function(response) {
          if (isJSON(response)) {
            let returnArray = JSON.parse(response);

            if (returnArray.success && returnArray.cottageBookCount > 0) {
              // call formating function then display the table
              let tblBookings = CottageBook2Table(returnArray.cottageBookRows);
              $('#tbodyBookings').append(tblBookings); // populate the existing bookings table

              // now that #tbodyBookings is populated with rows call function to initialise the delete confirmation popups
              setupConfirmations();
            }

            $('#output1').append(returnArray.errorMess); // always display the returned message
          } else {
            $('#output1').append('Error occurred - See ErrorLog');
          }
        })
        .always(function() {
          // if trIdNum points to a newly inserted booking row then set its background color
          $(trIdNum).css('background-color', '#a8cb17');
          let tridNum = null;
        })
        .fail(error =>
          $('#output1')
            .empty()
            .append(error.statusText)
        ); // end of $.post
    } else {
      // don't POST as one or both of the dateSat and cottageNum have not been selected
      $('#output1')
        .empty()
        .append('<h3>Please select both a date and cottage number</h3>');
    }
  }); // end of #submitButton.click function

  // firstNight or lastNight selection changes
  $('#firstNight, #lastNight').on('change', function() {
    // clear any messages and disable the submit button
    $('#output1').empty();
    $('#submitNewBooking').prop('disabled', true);

    // sanity check proposed booking dates in UI & update the number of nights field on the form
    if ($('#lastNight').val() < $('#firstNight').val()) {
      $('#output1').append('<h5>The last night cannot be before the arrival date</h5>');
      return;
    }

    // if the date check succeed then enable the new booking button
    $('#submitNewBooking').prop('disabled', false);

    // Update number of nights field in the new booking form
    let c = dateFns.differenceInDays($('#lastNight').val(), $('#firstNight').val()) + 1;
    $('#frmNewBooking #nights').val(c);

    // update the rental field
    if (c === 7) {
      // $('#frmNewBooking #Rental').val(cottageWeek.cottageWeekRow[0].RentWeek);
      rentalVal.set(cottageWeekRow.RentWeek);
    } else {
      // $('#frmNewBooking #Rental').val(cottageWeek.cottageWeekRow[0].RentDay * c);
      rentalVal.set(cottageWeekRow.RentDay * c);
    }
  }); // end of firstNight or lastNight selection changes

  /*////////////////////////////////////////////////////////////////////////////
   // user clicks submit button on frmNewBooking form
  ////////////////////////////////////////////////////////////////////////////*/
  $('#submitNewBooking').click(function(e) {
    e.preventDefault();

    // disable the submit button
    $('#submitNewBooking').attr('disabled', true);

    $('#outpu1').empty();

    // unformat the rental field before posting to DB
    $('#Rental').val(rentalVal.getNumericString());

    // ajax post to method=insert
    $.post('../include/bookingMaint_ajax.php', $('#frmNewBooking').serialize())
      .done(function(data) {
        // check for date clashes with existing bookings
        // debugger;

        // check for unexpected error returned from PHP
        if (!isJSON(data)) {
          $('#newBookingInfo')
            .empty()
            .append(data);
          return;
        }
        const returnArray = JSON.parse(data);

        // process any errors returned
        if (!returnArray.success) {
          switch (returnArray.messSeverity) {
            // 2 types of warning
            case 'W':
              if (returnArray.clashCount > 0) {
                $('#output1')
                  .empty()
                  .append(
                    '<h5>The proposed booking clashes with ' +
                      returnArray.clashCount +
                      ' existing booking' +
                      (returnArray.clashCount > 1 ? 's' : '') +
                      '</h5>'
                  );
              }
              // must be a warning about the rental value greater then 9,999.99
              else {
                $('#output1')
                  .empty()
                  .append('<h5>The rental is greater the £9,999.99</h5>');
              }
              break;
            // any error
            case 'E':
              $('#output1')
                .empty()
                .append('<h5>Error has occurred - contact support' + returnArray.errorMess);
              break;
          } // end of switch
        }
        // method=insert returned success, so process the new booking
        else {
          // set trIdNum to the IdNum of the newly inserted row so that when the table is refreshed the row can be highlighted
          trIdNum = '#' + returnArray.insertedIdNum;

          // reinstate the Rental field formatting by triggering the focus event
          rentalVal.set($('#Rental').val());

          // Remove class=highlight from any previously inserted row in the table
          $('#tbodyBookings > tr').removeClass('highlight');

          // // define template substitution variables
          // let ArriveDate,
          //   IdNum,
          //   FirstNight,
          //   LastNight,
          //   numNights,
          //   Rental,
          //   BookingRef,
          //   Notes,
          //   Status;

          // ArriveDate = moment($("#firstNight").val()).format("YYYY-MM-DD");
          // FirstNight = moment($("#firstNight").val()).format("ddd DD MMM");
          // LastNight = moment($("#lastNight").val()).format("ddd DD MMM");
          // numNights = $("#nights").val();
          // Rental = rentalVal.getFormatted();
          // BookingRef = returnArray.bookingRef;
          // Notes = $("#notes").val();
          // IdNum = returnArray.insertedIdNum;
          // Status = $("#BookingStatus").val() === "P" ? "Provisional" : "Confirmed";

          let cottageBookRow = {
            ArriveDate: moment($('#firstNight').val()).format('YYYY-MM-DD'),
            IdNum: returnArray.insertedIdNum,
            FirstNight: moment($('#firstNight').val()).format('ddd DD MMM'),
            LastNight: moment($('#lastNight').val()).format('ddd DD MMM'),
            numNights: $('#nights').val(),
            Rental: rentalVal.getFormatted(),
            BookingRef: returnArray.bookingRef,
            Notes: $('#notes').val(),
            BookingStatus: $('#BookingStatus').val() === 'P' ? 'Provisional' : 'Confirmed'
          };

          $('#tbodyBookings').append(formatCottageBookRow(cottageBookRow, true));

          // let newBookingTemplate = `
          //   <tr class='highlight d-flex' firstNight='${ArriveDate}' IdNum=${IdNum}>
          //     <td class="col-sm-2">${FirstNight}</td>
          //     <td class="col-sm-2">${LastNight}</td>
          //     <td class="col-sm-1 text-center">${numNights}</td>
          //     <td class="col-sm-1">${Rental}</td>
          //     <td class="col-sm-1">${BookingRef}</td>
          //     <td class="col-sm-2">${Notes}</td>
          //     <td class="col-sm-2">${Status}</td>
          //     <td>
          //       <div class="btn-group float-right">
          //         <button id="bEdit" type="button" class="btn btn-sm btn-success" onclick="rowEdit(this);">
          //           <i class="fa fa-pencil-square fa-lg"></i>
          //         </button>
          //         <button id="bElim" type="button" data-toggle="confirmation" class="btn btn-sm btn-danger">
          //           <i class="fa fa-trash fa-lg"></i>
          //         </button>
          //       </div>
          //     </td>
          //   </tr>
          // `;

          // // add the new row to the existing bookings table rows
          // $(newBookingTemplate).appendTo($("#tblBookings"));

          // sort the bookings by the tr attribute firstNight so that the new inserted row is in the correct position
          let tbody = $('#tbodyBookings');

          let rows = $('#tbodyBookings tr').get();

          rows.sort(function(a, b) {
            let keyA = $(a).attr('firstNight');
            let keyB = $(b).attr('firstNight');
            if (keyA < keyB) return -1;
            if (keyA > keyB) return 1;
            return 0;
          });

          $.each(rows, function(index, row) {
            tbody.append(row);
          });

          // set up the confirmations popups to include the new row
          setupConfirmations();

          // clear down the new booking form ready for another booking for the same cottage & week
          $('#firstNight :nth-child(1)').prop('selected', true);
          $('#lastNight :nth-child(7)').prop('selected', true);
          $('#frmNewBooking #nights').val('7');
          $('#BookingStatus :nth-child(1)').prop('selected', true);
          rentalVal.set(cottageWeekRow.RentWeek);
          $('#notes').val('');
          $('textarea')
            .next()
            .empty();

          // display success message for 5 seconds
          $('#newBookingInfo')
            .empty()
            .append('Booking made')
            .fadeOut(5000);
        }
      })

      // always re-enable the submit new booking button
      .always(() => $('#submitNewBooking').removeAttr('disabled'))

      .fail(error =>
        $('#newBookingInfo')
          .empty()
          .append(error.statusText)
      ); // end of $.post method=insert
  }); // end of #submitNewBooking.click

  // check for values in session storage; if found then display the DateSat and CottageNum
  if (typeof _clsbookingMaint.cottageNum !== 'undefined' && typeof _clsbookingMaint.dateSat !== 'undefined') {
    cottageNum = _clsbookingMaint.cottageNum;
    booCottageNum = true;
    $('#cottageNum [value=' + cottageNum + ']').attr('selected', 'selected');

    objDateSat = moment(_clsbookingMaint.dateSat, 'YYYY-MM-DD');
    $('#wcDateSat [value=' + _clsbookingMaint.dateSat + ']').attr('selected', 'selected');
    booDateSat = true;

    // get the existing bookings if any and show the new booking form
    showFrmNewBooking();
  }

  // initialise the tooltips
  $('[data-toggle="tooltip"]').tooltip();
}); // end of (document).ready

/*////////////////////////////////////////////////////////////////////////////
// prevent the enter key submitting the new booking form
////////////////////////////////////////////////////////////////////////////*/
$('#frmNewBooking').on('keyup keypress', function(e) {
  const keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});

/*////////////////////////////////////////////////////////////////////////////////////////////////////
// Formats an array of CottageBook rows returned from SQL and formats into existing bookings table rows
// Called by: $("#submitButton").click
////////////////////////////////////////////////////////////////////////////////////////////////////*/
function CottageBook2Table(cottageBookRows) {
  let existingBookingRows = '';

  cottageBookRows.forEach(row => {
    let cottageBookRow = {
      ArriveDate: row.FirstNight,
      IdNum: row.IdNum,
      FirstNight: dateFns.format(new Date(row.FirstNight), 'ddd D MMM'),
      LastNight: dateFns.format(new Date(row.LastNight), 'ddd D MMM'),
      numNights: row.numNights,
      Rental: '£' + parseFloat(row.Rental).toFixed(0),
      BookingRef: row.BookingRef,
      Notes: row.Notes,
      BookingStatus: row.BookingStatus
    };

    existingBookingRows += formatCottageBookRow(cottageBookRow, false);
  }); // end of cottageBookRows.forEach

  return existingBookingRows;
}

/*////////////////////////////////////////////////////////////////////////////
// function to delete the selected existing booking
////////////////////////////////////////////////////////////////////////////*/
function rowDel(delButton) {
  // find the table row of the booking to be deleted
  let removeTR = $(delButton).closest('tr');

  // post the delete to the PHP URL with the IdNum extracted from the tr
  $.post('../include/bookingMaint_ajax.php', {
    method: 'CottageBook_delete',
    IdNum: removeTR.attr('IdNum')
  }).done(
    function(response) {
      // check that a valid JSON response has been received
      if (isJSON(response)) {
        let returnArray = JSON.parse(response);

        // don't reload the bookings from the DB, just remove the row from the existing bookings table
        if (returnArray.success) {
          // remove the row from the existing bookings table with fade out
          removeTR.fadeOut(500, function() {
            $(this).remove();
          });

          // display a confirm message for 5 seconds
          $('#newBookingInfo')
            .empty()
            .append(returnArray.message)
            .fadeOut(5000);
        } else {
          // remove any previous message & display the error message
          $('#output1')
            .empty()
            .append(returnArray.message);
          alert('an error has occured\n\n' + returnArray.message);
        }
      } else {
        // response is not valid JSON so remove any previous message & display the error message
        $('#output1')
          .empty()
          .append('an error has occured');
        alert('an error has occured\n\n' + response);
      }
    } // end of .done
  ); // end of $.post method: "CottageBook_delete"
} // end of function rowDel

/*/////////////////////////////////////////////////////////////////////////////////////////////////
// pop up a modal form to allow updating of the booking status & notes columns for the selected row
// called by: onclick="rowEdit(this);" on the update button
/////////////////////////////////////////////////////////////////////////////////////////////////*/
function rowEdit(editButton) {
  // clear out any previous popups
  $('#bookingUpdForm').empty();

  let row = $(editButton).closest('tr');
  let cols = row.find('td');

  // copy the fields from the table row into the fields in the modal bookingUpdForm
  $('#idnumUpd').val(row[0].attributes['idnum'].value);
  $('#firstNightUpd').val(cols[0].textContent);
  $('#lastNightUpd').val(cols[1].textContent);
  $('#nightsUpd').val(cols[2].textContent);
  $('#RentalUpd').val(cols[3].textContent);
  $('#bookingRefUpd').val(cols[4].textContent);
  $('#notesUpd').val(cols[5].textContent);
  let statusSelected = cols[6].textContent === 'Provisional' ? 'P' : 'C';
  $('#BookingStatusUpd').val(statusSelected); // use P or C from cols[6]

  $('#exampleModalLongTitle').html('Update the status or notes for booking ref. ' + cols[4].textContent);

  $('#mybookingUpdForm').modal('show');
} // end of function rowEdit

/*////////////////////////////////////////////////////////////////////////////
// button click handler for the save changes button in the modal update form
////////////////////////////////////////////////////////////////////////////*/
$('#bookingUpdSave').click(function(e) {
  e.preventDefault();
  // debugger;
  let IdNum = $('#idnumUpd').val();
  let errMess = '';

  // post the status update with the IdNum extracted from the tr
  $.post('../include/bookingMaint_ajax.php', {
    method: 'cottageBook_updStatus',
    IdNum: IdNum,
    BookingStatus: $('#BookingStatusUpd').val()
  }).done(function(response) {
    let returnArray = JSON.parse(response);

    // if success
    if (returnArray.success) {
      // put the new booking status value into the existing bookings table
      let rowTR = $('tr[IdNum="' + IdNum + '"]');
      let cols = rowTR.find('td');
      cols[6].firstChild.textContent = $('#BookingStatusUpd').val() === 'P' ? 'Provisional' : 'Confirmed';
    } else {
      // returnArray.success == false
      // update the error message
      errMess += returnArray.message + '\n';
    }
  }); // end of $.post method: "cottageBook_updStatus"

  // post the Notes column update with the IdNum extracted from the tr
  $.post('../include/bookingMaint_ajax.php', {
    method: 'cottageBook_updNotes',
    IdNum: IdNum,
    Notes: $('#notesUpd').val()
  }).done(function(response) {
    let returnArray = JSON.parse(response);

    // if success
    if (returnArray.success) {
      // put the new booking Notes value into the existing bookings table
      let rowTR = $('tr[IdNum="' + IdNum + '"]');
      let cols = rowTR.find('td');
      cols[5].firstChild.textContent = $('#notesUpd').val();
    } else {
      // returnArray.success == false
      // update the error messages & output the error message
      errMess += returnArray.message + '\n';
    }
  }); // end of $.post method: "cottageBook_updStatus"

  // always close the modal form
  $('#mybookingUpdForm').modal('toggle');

  if (errMess) {
    alert('An error has occured\n\n' + errMess);

    $('#newBookingInfo')
      .empty()
      .append(errMess)
      .fadeOut(10000);
  } else {
    $('#newBookingInfo')
      .empty()
      .append('Booking ref. ${$("#bookingRefUpd").val()} was amended')
      .fadeOut(5000);
  }
}); // end of function $("#bookingUpdSave").click

/*////////////////////////////////////////////////////////////////////////////
// Function to set up the confirmation popup to confirm delete after the existing bookings table is populated
////////////////////////////////////////////////////////////////////////////*/
function setupConfirmations() {
  $('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    onConfirm: function() {
      rowDel(this);
    },
    title: 'Confirm delete?',
    placement: 'left',
    popout: true,
    singleton: true,
    btnOkClass: 'btn btn-sm btn-danger'
  });
}

/*///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Function takes an associative array of a CottageBook row and formats it into an existing booking table row
// If boolean booNewBooking = true then the new row is highlighted
// Called by: CottageBook2Table & $("#submitNewBooking").click
///////////////////////////////////////////////////////////////////////////////////////////////////////////*/
function formatCottageBookRow(cottageBookRow, booNewBooking) {
  let existingBooking = `
    <tr firstNight='${cottageBookRow.ArriveDate}' IdNum=${cottageBookRow.IdNum} 
        class="d-flex ${booNewBooking ? 'highlight' : ''}">
      <td class="col-2">${cottageBookRow.FirstNight}</td>
      <td class="col-2">${cottageBookRow.LastNight}</td>
      <td class="col-1 text-center">${cottageBookRow.numNights}</td>
      <td class="col-1">${cottageBookRow.Rental}</td>
      <td class="col-1">${cottageBookRow.BookingRef}</td>
      <td class="col-2">${cottageBookRow.Notes}</td>
      <td class="col-2">${cottageBookRow.BookingStatus === 'P' ? 'Provisional' : 'Confirmed'}</td>
      <td class="col-1">
        <div class="btn-group float-right">
          <button type="button" class="btn btn-sm btn-success" onclick="rowEdit(this);">
            <i class="fa fa-pencil-square fa-lg"></i>
          </button>
          <button type="button" data-toggle="confirmation" class="btn btn-sm btn-danger">
            <i class="fa fa-trash fa-lg"></i>
          </button>
        </div>
      </td>
    </tr>
  `;

  return existingBooking;
}
