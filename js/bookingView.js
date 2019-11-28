// bookingView.js
'use strict';

// load bookingMaint from session storage if it exists
var bookingMaint = new clsbookingMaint();

var cottageNum = 0,
  cottageWeekRows,
  // FIXME: remove booCalendarDisplayed
  booCalendarDisplayed = 0;

// Create a new clsDeviceIdCookie class which set up the deviceId cookie
const _clsDeviceIdCookie = new clsDeviceIdCookie();

/*////////////////////////////////////////////////////////////////////////////
// Function to get all the CottageBook rows for the selected cottage number
////////////////////////////////////////////////////////////////////////////*/
function showCottageBook() {
  // clear any new booking messages when dateSat or cottageNum change
  $('#newBookingInfo').empty();
  $('#output1').empty();

  // if the session storage variable 'bookingMaint' has already been set then re-use the cottageNum
  if (bookingMaint.bookingMaintExists) {
    cottageNum = bookingMaint.cottageNum;
    $('#cottageNum').val(cottageNum);
  } else {
    return;
  }

  // if the calendar has already been retrieved then just get the bookings
  if (booCalendarDisplayed) {
    getCottageBook();
  } else {
    // get the calendar for the cottage from the selected date
    $.post('../include/bookingView_ajax.php', {
      method: 'cottageWeek_selectAll',
      dateSat: dateFns.format(momDateSat),
      cottageNum: cottageNum
    })
      .done(function(data) {
        // check for error in PHP
        if (!isJSON(data)) {
          alert('An error has occured\n\n' + data);
          return;
        }

        // put the CottageWeek data into the table
        let funcReturn = JSON.parse(data);

        if (funcReturn.success) {
          cottageWeekRows = funcReturn.cottageWeekRows;

          // iterate over the rows adding them to the calendar table
          cottageWeekRows.forEach(cottageWeekRow => {
            // debug console.log(index, cottageWeekRow);

            let dayNum = [];
            for (let index = 0; index < 7; index++) {
              let theDay = dateFns.format(dateFns.addDays(new Date(cottageWeekRow.DateSat), index), 'D');
              let monthNum = dateFns.format(dateFns.addDays(new Date(cottageWeekRow.DateSat), index), 'MM');
              dayNum[index] = `
                <strong 
                ${monthNum % 2 ? 'style="color:blue"' : 'style="color:brown"'}>
                ${theDay}
                </strong>
              `;

              // if day is Saturday or the 1st of the month then add month and make date bold
              if (theDay === '1' || dateFns.isSaturday(dateFns.addDays(new Date(cottageWeekRow.DateSat), index))) {
                dayNum[index] = `
                  <strong 
                  ${monthNum % 2 ? 'style="color:blue"' : 'style="color:brown"'}>
                  ${dateFns.format(dateFns.addDays(new Date(cottageWeekRow.DateSat), index), 'D')}
                  ${dateFns.format(dateFns.addDays(new Date(cottageWeekRow.DateSat), index), ' MMM')}
                  </strong>
                `;
              }
            }

            let newRow = `
              <tr dateSat='${cottageWeekRow.DateSat}'> 
                <td dayofWeek='1'><sup>${dayNum[0]}</sup></td> 
                <td dayofWeek='2'><sup>${dayNum[1]}</sup></td> 
                <td dayofWeek='3'><sup>${dayNum[2]}</sup></td> 
                <td dayofWeek='4'><sup>${dayNum[3]}</sup></td> 
                <td dayofWeek='5'><sup>${dayNum[4]}</sup></td> 
                <td dayofWeek='6'><sup>${dayNum[5]}</sup></td> 
                <td dayofWeek='7'><sup>${dayNum[6]}</sup></td> 
                <td style="text-align:center">${
                  cottageWeekRow.bShortBreaksAllowed
                    ? '<i class="fa fa-thumbs-o-up fa-lg" aria-hidden="true"></i>'
                    : '<i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i>'
                }</td>
                <td><button type='button' class='btn btn-sm btn-success'><i class="fa fa-book fa-lg" aria-hidden="true"></i></button></td>
              </tr>
            `;

            // add the row to the calendar table
            $(newRow).appendTo($('tbody'));
          });
        } else {
          $('#output1')
            .empty()
            .append(funcReturn.message);
          alert('An error has occured\n\n' + funcReturn.message);
        }
      })
      .always(
        () => getCottageBook() // update the existing bookings into the table
      ); // end of $.post
  } // end of else
} // end of function showCottageBook

/*////////////////////////////////////////////////////////////////////////////
// Function to get the bookings for the cottage number after the given date
////////////////////////////////////////////////////////////////////////////*/
function getCottageBook() {
  // remove all the booking highlight classes
  $('td').removeClass('provisional');
  $('td').removeClass('confirmed');
  // $("td").css("border","");

  $.post('../include/bookingView_ajax.php', {
    method: 'cottageBook_selectAll',
    dateSat: dateFns.format(momDateSat),
    cottageNum: cottageNum
  }).done(function(data) {
    // update the CottageBook rows into the calendar table

    if (isJSON(data)) {
      let funcReturn = JSON.parse(data);

      if (funcReturn.success === true) {
        let cottageBookRows = funcReturn.cottageBookRows;

        // iterate over the rows updating the calendar table
        cottageBookRows.forEach(cottageBookRow => {
          // convert firstNight into day of the week where Sat = 1
          let dowFirstNight = new Date(cottageBookRow.FirstNight).getDay() + 2;
          if (dowFirstNight === 8) {
            dowFirstNight = 1;
          }

          // find the table row with the dateSat attribute
          let tr = $('[datesat=' + cottageBookRow.DateSat + ']');

          // for day = firstnight to (firstnight + numNights)
          for (let index = dowFirstNight; index < dowFirstNight + cottageBookRow.numNights; index++) {
            let dDay = $(tr).children('[dayofweek=' + index + ']');
            // $(dDay).css("border","1px solid black"); // add a black border to all bookings

            // add class 'provisional' P or 'confirmed' C
            if (cottageBookRow.BookingStatus === 'P') {
              $(dDay).addClass('provisional');
            } else {
              $(dDay).addClass('confirmed');
            }
          }
        }); // end of foreach cottageBookRows
      } else {
        $('#output1')
          .empty()
          .append(funcReturn.message);
        alert('An error has occured\n\n' + funcReturn.message);
      }
    } else {
      alert('An error has occured\n\n' + data);
    }
  });
} // end of function getCottageBook

/*////////////////////////////////////////////////////////////////////////////
// document ready
////////////////////////////////////////////////////////////////////////////*/
$(document).ready(function() {
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

  /*////////////////////////////////////////////////////////////////////////////////////
  // listener to show current global version and deviceId on title click or double click
  ////////////////////////////////////////////////////////////////////////////////////*/
  $('#title').on('click', function() {
    $(this)
      .next()
      .html(`${_VERSION}-${_clsDeviceIdCookie.deviceId}`)
      .toggle();
  });

  /*////////////////////////////////////////////////////////////////////////////
  // get the selected cottageNum when selection changes
  ////////////////////////////////////////////////////////////////////////////*/
  $('#cottageNum').change(function() {
    // debugger;
    cottageNum = $('#cottageNum').val();

    if (cottageNum === '-1') {
      $('#output1').html('Invalid cottage number: ' + cottageNum);
    } else {
      // update and save the cottage number into session storage key bookingMaint
      bookingMaint.cottageNum = cottageNum;

      // clear the table
      $('tbody').empty();
      booCalendarDisplayed = false;

      // reload the table for the newly selected cottage
      showCottageBook();
      // set boolean so that no need to re load the calendar from the DB to save 0.5 seconds
      booCalendarDisplayed = true;
    }
  });

  // FIXME - change delegate for "on".(click) per Notes note
  // go to bookingMaint.php to view or makephp composer.phar install a booking for the selected cottageNum and DateSat of the row clicked
  // $("tbody").delegate(".fa-book", "click", function() {

  //   // $(this).confirmation("show");

  //   // get the DateSat from the nearest TR and update into bookingMaint session storage
  //   let rowTR = $(this).closest("tr");
  //   bookingMaint.dateSat = rowTR.attr("dateSat");

  //   // call bookingMaint.php to view or book for the selected week and cottageNum
  //   window.location = "bookingMaint.php";
  // }); // end of view_details button click event

  /*////////////////////////////////////////////////////////////////////////////
  // event listener for clicks on any buttons with class "fa-book" within "tbody".
  // replaces the jquery delegate method which is deprecated.
  // updates the DateSat into session storage and then calls bookingMaint.php.
  ////////////////////////////////////////////////////////////////////////////*/
  $('tbody').on('click', '.fa-book', function() {
    // get the DateSat from the nearest TR and update into bookingMaint session storage
    let rowTR = $(this).closest('tr');
    bookingMaint.dateSat = rowTR.attr('dateSat');

    // call bookingMaint.php to view or book for the selected week and cottageNum already in session storage
    window.location = 'bookingMaint.php';
  });

  // initiatise the tooltips
  $('[data-toggle="tooltip"]').tooltip();
}); // end of document ready
