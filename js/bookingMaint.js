// bookingMaint.js
'use strict';

var objDateSat, cottageNum, trIdNum;
var rentalVal; // AutoNumeric value for the cottage rental with £ sign and 2 dp bound to form id=Rental
var cottageWeekRow; // row from CottageWeekRow table for the selected cottage and week
var cottageBookRows; // array of existing CottageBookRows

var booCottageNum = false; // is the selected cottage number valid
var booDateSat = false; // is the selected date valid

// Create a new clsDeviceIdCookie class which sets up the deviveId cookie
const _clsDeviceIdCookie = new clsDeviceIdCookie();

// load bookingMaint from session storage
const _clsbookingMaint = new clsbookingMaint();

// document ready
$(document).ready(function() {
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

  // show current global version held in commonClasses and deviceId on title click
  $('#title').on('click', function() {
    $(this)
      .next()
      .html(`${_VERSION}-${_clsDeviceIdCookie.deviceId}`)
      .toggle();
  });

  // get the selected dateSat when selection changes
  $('#wcDateSat').change(function() {
    if ($('#wcDateSat').val() === '-1') {
      $('#output1').html('Selected date is not valid');
      objDateSat = null;
      booDateSat = false;
      // showFrmNewBooking();
    } else {
      // a valid date has been selected so save in global variable
      objDateSat = moment($('#wcDateSat').val(), 'YYYY-MM-DD');
      booDateSat = true;
      showFrmNewBooking();
    }
  });

  // get the selected cottageNum when selection changes
  $('#cottageNum').change(function() {
    if (cottageNum === '-1') {
      $('#output1').html('Invalid cottage number');
      booCottageNum = false;
      // showFrmNewBooking();
    } else {
      // cottageNum is a valid selection
      cottageNum = $('#cottageNum').val();
      booCottageNum = true;
      showFrmNewBooking();
    }
  });

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
/// prevent the enter key submitting the new booking form
////////////////////////////////////////////////////////////////////////////*/
$('#frmNewBooking').on('keyup keypress', function(e) {
  const keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});

/*////////////////////////////////////////////////////////////////////////////////////////////////////
/// Formats an array of CottageBook rows returned from SQL and formats into existing bookings table rows
/// Called by: $("#submitButton").click
////////////////////////////////////////////////////////////////////////////////////////////////////*/
function CottageBook2Table(cottageBookRows) {
  let existingBookingRows = '';

  cottageBookRows.forEach(row => {
    let cottageBookRow = {
      BookingName: row.BookingName,
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
  }); /// end of cottageBookRows.forEach

  return existingBookingRows;
}

/*////////////////////////////////////////////////////////////////////////////
/// function to delete the selected existing booking
////////////////////////////////////////////////////////////////////////////*/
function rowDel(delButton) {
  $('.collapse').collapse('hide');

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
} /// end of function rowDel

/*/////////////////////////////////////////////////////////////////////////////////////
/// pop up a modal dialog to allow updating of the booking columns for the selected row
/// called by: onclick="rowEdit(this);" on the update button
/////////////////////////////////////////////////////////////////////////////////////*/
function rowEdit(editButton) {
  $('.collapse').collapse('hide');

  // clear out any previous modal dialog popups
  $('#bookingUpdForm').empty();

  // get the table row that was clicked
  const row = $(editButton).closest('tr');

  // find the row selected for edit in the existing CottageBookRows array
  // eslint-disable-next-line eqeqeq
  const cottageBookRow = cottageBookRows.find(booking => booking.IdNum == row[0].attributes['idnum'].value);

  // pass the cottageBookRow columns into the modal update form
  $('#innerbookingUpdFrm #IdNum').val(cottageBookRow['IdNum']);
  $('#innerbookingUpdFrm #firstNightUpd').val(dateFns.format(new Date(cottageBookRow['FirstNight']), 'ddd D MMM'));
  $('#innerbookingUpdFrm #lastNightUpd').val(dateFns.format(new Date(cottageBookRow['LastNight']), 'ddd D MMM'));
  $('#innerbookingUpdFrm #NumNights').val(cottageBookRow['NumNights']);
  $('#innerbookingUpdFrm #BookingName').val(cottageBookRow['BookingName']);
  $('#innerbookingUpdFrm #BookingRef').val(cottageBookRow['BookingRef']);
  $('#innerbookingUpdFrm #Rental').val('£' + parseInt(cottageBookRow['Rental']));
  $('#innerbookingUpdFrm #Notes').val(cottageBookRow['Notes']);
  $('#innerbookingUpdFrm #BookingStatus').val(cottageBookRow['BookingStatus']);
  $('#innerbookingUpdFrm #BookingSource').val(cottageBookRow['BookingSource']);
  $('#innerbookingUpdFrm #ExternalReference').val(cottageBookRow['ExternalReference']);
  $('#innerbookingUpdFrm #ContactEmail').val(cottageBookRow['ContactEmail']);
  $('#innerbookingUpdFrm #NumAdults').val(cottageBookRow['NumAdults']);
  $('#innerbookingUpdFrm #NumChildren').val(cottageBookRow['NumChildren']);
  $('#innerbookingUpdFrm #NumDogs').val(cottageBookRow['NumDogs']);
  $('#innerbookingUpdFrm #Children').val(cottageBookRow['Children']);

  $('#updDialogTitle').html('Update booking ref. ' + cottageBookRow['BookingRef']);

  $('#mybookingUpdForm').modal('show');
} /// end of function rowEdit

/*////////////////////////////////////////////////////////////////////////////
/// button click handler for the save changes button in the modal update dialog
/////////////////////////////////////////////////////////////////////////////*/
$('#bookingUpdSave').click(function(e) {
  e.preventDefault();

  // put the form fields into array
  const spCottageBook_upd = {
    method: 'cottageBook_upd',
    IdNum: $('#innerbookingUpdFrm #IdNum').val(),
    BookingName: $('#innerbookingUpdFrm #BookingName').val(),
    BookingStatus: $('#innerbookingUpdFrm #BookingStatus').val(),
    Rental: $('#innerbookingUpdFrm #Rental').val(),
    Notes: $('#innerbookingUpdFrm #Notes').val(),
    BookingSource: $('#innerbookingUpdFrm #BookingSource').val(),
    ExternalReference: $('#innerbookingUpdFrm #ExternalReference').val(),
    ContactEmail: $('#innerbookingUpdFrm #ContactEmail').val(),
    NumAdults: $('#innerbookingUpdFrm #NumAdults').val(),
    NumChildren: $('#innerbookingUpdFrm #NumChildren').val(),
    NumDogs: $('#innerbookingUpdFrm #NumDogs').val(),
    Children: $('#innerbookingUpdFrm #Children').val()
  };

  // call bookingMaint_ajax.php with method 'cottageBook_upd'
  $.post('../include/bookingMaint_ajax.php', spCottageBook_upd)
    .done(function(response) {
      if (isJSON(response)) {
        const returned = JSON.parse(response);

        if (returned.success) {
          // refresh the existing bookings list for this date and cottage
          getCottageWeekandCottageBook();

          // show a confirmation message
          // FIXME - BookgRef is undefined
          $('#newBookingInfo')
            .empty()
            .append(`Booking ref. ${$('#exampleModalLongTitle').html()} was amended`)
            .fadeOut(5000);
        } else {
          // output an error message
          $('#output1')
            .empty()
            .append('Error - ' + returned.errorMess)
            .fadeOut(10000);
        }
      } else {
        // not json returned error
        $('#output1').append('Error occurred - See ErrorLog');
      }
    })
    .always(function() {
      // always close the modal dialog
      $('#mybookingUpdForm').modal('toggle');
    });
}); /// end of function $("#bookingUpdSave").click

/*///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Function to set up the confirmation popup to confirm delete after the existing bookings table is populated
///////////////////////////////////////////////////////////////////////////////////////////////////////////*/
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
/// Function takes an associative array of a CottageBook row and formats it into an existing booking table row
/// If boolean booNewBooking = true then the new row is highlighted by adding the class 'highlight'
/// Called by: CottageBook2Table & $("#submitNewBooking").click
///////////////////////////////////////////////////////////////////////////////////////////////////////////*/
function formatCottageBookRow(cottageBookRow, booNewBooking) {
  let existingBooking = `
    <tr firstNight='${cottageBookRow.ArriveDate}' IdNum=${cottageBookRow.IdNum} 
        class="${booNewBooking ? 'highlight' : ''}">
      <td>${cottageBookRow.BookingName}</td>
      <td>${cottageBookRow.FirstNight}</td>
      <td>${cottageBookRow.LastNight}</td>
      <td class="text-center">${cottageBookRow.numNights}</td>
      <td>${cottageBookRow.Rental}</td>
      <td class="d-none d-md-table-cell">${cottageBookRow.BookingRef}</td>
      <td>${cottageBookRow.BookingStatus === 'P' ? 'Provisional' : 'Confirmed'}</td>
      <td class="d-print-none">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success" onclick="rowEdit(this);">
            <i class="fa fa-pencil fa-lg"></i>
          </button>
          <button type="button" data-toggle="confirmation" class="btn btn-sm btn-danger">
            <i class="fa fa-trash fa-lg"></i>
          </button>
        </div>
      </td>
      <td class="d-none d-print-table-cell">${cottageBookRow.Notes}</td>
    </tr>
  `;

  return existingBooking;
}

/*////////////////////////////////////////////////////////////////////////////
/// clear any new booking messages when dateSat or cottageNum change
/// then get CottageWeek and CottageBook rows
/// Called by: $("#cottageNum").change, $("#wcDateSat").change, $("#submitNewBooking").click
/////////////////////////////////////////////////////////////////////////////*/
function showFrmNewBooking() {
  $('#newBookingInfo').empty();
  $('#output1').empty();

  if (booDateSat && booCottageNum) {
    // refresh the existing bookings list for this date and cottage
    getCottageWeekandCottageBook();

    // $('#submitButton').click();

    // put the date into the frmNewBooking in case the user does an insert
    $('#frmNewBooking #dateSat').val(moment(objDateSat, 'YYYY-MM-DD').format('YYYY-MM-DD'));

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
    $('#frmNewBooking #cottageNum').val(cottageNum);

    // show the new CottageWeek_data section
    $('.CottageWeek_data').show();
  }
}

/*////////////////////////////////////////////////////////////////////////////
/// Function to get the CottageWeek row and any CottageBook rows
/// called by function showFrmNewBooking()
/////////////////////////////////////////////////////////////////////////////*/
function getCottageWeekandCottageBook() {
  // date or cottage number has changed so empty the bookings table and clear any messages
  $('#tbodyBookings').empty();
  $('#output1').empty();
  $('#newBookingInfo').empty();

  // 1. CottageWeek data to display as a single info line
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

        $('#Rent').html(
          `Rent: week £${parseInt(cottageWeekRow.RentWeek).toLocaleString()}, day&nbsp£${parseInt(
            cottageWeekRow.RentDay
          )}`
        );
        // if (!cottageWeekRow.bShortBreaksAllowed) {
        //   $('#DayRent').css('text-decoration', 'line-through');
        // }
        // $('#WeekRent').html('Rent / week £' +);

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
      // not json returned
      $('#output1')
        .empty()
        .append('1 Error occurred - See ErrorLog');
    }
  });

  // 2. any existing bookings from CottageBook table
  $.post('../include/bookingMaint_ajax.php', $('#formGet').serialize())
    .done(function(response) {
      if (isJSON(response)) {
        let returnArray = JSON.parse(response);

        if (returnArray.success && returnArray.cottageBookCount > 0) {
          cottageBookRows = returnArray.cottageBookRows;
          // call formating function then display the table
          let tblBookings = CottageBook2Table(cottageBookRows);
          $('#tbodyBookings').append(tblBookings); // populate the existing bookings table

          // now that #tbodyBookings is populated with rows call function to initialise the delete confirmation popups
          setupConfirmations();
        }

        $('#newBookingInfo').append(returnArray.errorMess);
        // always display the returned message
      } else {
        // not json returned
        $('#output1')
          .empty()
          .append('2 Error occurred - See ErrorLog');
      }
    })
    .always(function() {
      // if trIdNum points to a newly inserted booking row then set its background color
      $(trIdNum).css('background-color', '#a8cb17');
    })
    .fail(error =>
      $('#output1')
        .empty()
        .append(error.statusText)
    ); // end of $.post
}

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

/*////////////////////////////////////////////////////////////////////////////
/// new booking form fields firstNight or lastNight selection changes
/////////////////////////////////////////////////////////////////////////////*/
$('#firstNight, #lastNight').on('change', function() {
  // clear any messages and disable the submit button
  $('#output1').empty();
  $('newBookingInfo').empty();
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
}); /// end of firstNight or lastNight selection changes

/*////////////////////////////////////////////////////////////////////////////
/// user clicks submit button on frmNewBooking form
/////////////////////////////////////////////////////////////////////////////*/
$('#submitNewBooking').click(function(e) {
  e.preventDefault();

  // disable the submit button
  $('#submitNewBooking').attr('disabled', true);

  $('#outpu1').empty();
  $('#newBookingInfo').empty();

  // unformat the rental field before posting to DB
  $('#Rental').val(rentalVal.getNumericString());

  // ajax post to method=insert
  $.post('../include/bookingMaint_ajax.php', $('#frmNewBooking').serialize())
    .done(function(data) {
      // check for date clashes with existing bookings
      // debugger;

      // check for unexpected error returned from PHP
      if (!isJSON(data)) {
        $('#output1')
          .empty()
          .append('Error - ' + data)
          .fadeOut(10000);
        return;
      }

      const returnArray = JSON.parse(data);

      // process any errors returned
      if (!returnArray.success) {
        switch (returnArray.messSeverity) {
          // 2 types of (W)arning
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
              .append('<h5 class="p-3">Error has occurred - ' + returnArray.errorMess);
            break;
        } // end of switch
      }
      // method=insert returned success, so process the new booking
      else {
        // refresh the existing bookings list for this date and cottage
        getCottageWeekandCottageBook();

        // set trIdNum to the IdNum of the newly inserted row so that the row can be highlighted
        // FIXME - highlight doesn't work
        trIdNum = `#${returnArray.insertedIdNum}`;
        $(trIdNum).css('background-color', '#a8cb17');

        // reinstate the Rental field formatting
        // rentalVal.set($('#Rental').val());

        // // Remove class=highlight from any previously inserted row in the table
        // $('#tbodyBookings > tr').removeClass('highlight');

        // let cottageBookRow = {
        //   BookingName: $('.insertForm #BookingName').val(),
        //   ArriveDate: moment($('.insertForm #firstNight').val()).format('YYYY-MM-DD'),
        //   IdNum: returnArray.insertedIdNum,
        //   FirstNight: moment($('.insertForm #firstNight').val()).format('ddd D MMM'),
        //   LastNight: moment($('.insertForm #lastNight').val()).format('ddd D MMM'),
        //   numNights: $('.insertForm #nights').val(),
        //   Rental: rentalVal.getFormatted(),
        //   BookingRef: returnArray.bookingRef,
        //   BookingStatus: $('.insertForm #BookingStatus').val() === 'P' ? 'Provisional' : 'Confirmed'
        // };

        // $('#tbodyBookings').append(formatCottageBookRow(cottageBookRow, true));

        // sort the bookings by the tr attribute firstNight so that the new inserted row is in the correct position
        // let tbody = $('#tbodyBookings');

        // let rows = $('#tbodyBookings tr').get();

        // rows.sort(function(a, b) {
        //   let keyA = $(a).attr('firstNight');
        //   let keyB = $(b).attr('firstNight');
        //   if (keyA < keyB) return -1;
        //   if (keyA > keyB) return 1;
        //   return 0;
        // });

        // $.each(rows, function(index, row) {
        //   tbody.append(row);
        // });

        // set up the confirmations popups to include the new row in the existing bookings table
        // setupConfirmations();

        // clear down the new booking form ready for another booking for the same cottage & week
        $('.insertForm #BookingName').val('');
        $('.insertForm #firstNight :nth-child(1)').prop('selected', true);
        $('.insertForm #lastNight :nth-child(7)').prop('selected', true);
        $('.insertForm #frmNewBooking #nights').val('7');
        $('.insertForm #BookingStatus :nth-child(1)').prop('selected', true);
        rentalVal.set(cottageWeekRow.RentWeek);
        $('.insertForm #notes').val('');
        $('textarea')
          .next()
          .empty();

        // display success message for 10 seconds
        $('#newBookingInfo')
          .empty()
          .append('Booking made')
          .fadeOut(10000);
      }
    })

    // always hide the new booking form
    .always(() => $('.collapse').collapse('hide'))

    .fail(error =>
      $('#output1')
        .empty()
        .append(error.statusText)
    ); // end of $.post method=insert
}); /// end of #submitNewBooking.click in new booking form
