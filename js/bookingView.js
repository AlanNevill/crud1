// bookingView.js

// load bookingMaint from session storage
var bookingMaint = new clsbookingMaint();

var cottageNum = -1, cottageWeekRows, booCalendarDisplayed=0;

// clear the cottage selection in case user presses the back button to get here
$('#cottageNum').val("-1");


/**
 * Function to get all the CottageBook rows for the selected cottage number
 * Display in a table
 * @returns {boolean} true if successful
 */
function showCottageBook() {
  // clear any new booking messages when dateSat or cottageNum change
  $("#newBookingInfo").empty();
  $("#output1").empty();

  // if the calendar has already been retrieved then just get the bookings
  if (booCalendarDisplayed) {
    getCottageBook();
  }
  else
  {
    // get the calendar for the cottage from the selected date
    $.post(
      "include/bookingView_ajax.php",
      {'method':'cottageWeek_selectAll','dateSat': dateFns.format(momDateSat), 'cottageNum' : cottageNum, }
    )
    .done( function(data) {
        // put the CottageWeek data into the table

        let funcReturn = JSON.parse(data);

        if (funcReturn.success === true) {
          cottageWeekRows = funcReturn.cottageWeekRows;
    
          // iterate over the rows adding them to the calendar table
          cottageWeekRows.forEach((cottageWeekRow) => {
            // debug console.log(index, cottageWeekRow);

            let DateSat = cottageWeekRow.DateSat;
            let fDateSat = dateFns.format(new Date(cottageWeekRow.DateSat),'DD MMM');
            let shortBreaks = cottageWeekRow.bShortBreaksAllowed ? 'Y' : 'N';

            let newRow = `
              <tr dateSat='${DateSat}'> 
              <td>${fDateSat}</td> 
              <td>${shortBreaks}</td>
              <td dayofWeek='1'></td> 
              <td dayofWeek='2'></td> 
              <td dayofWeek='3'></td> 
              <td dayofWeek='4'></td> 
              <td dayofWeek='5'></td> 
              <td dayofWeek='6'></td> 
              <td dayofWeek='7'></td> 
              <td><button type='button' class='btn view_details'></button></td></tr>
            `;

            // add the row to the calendar table
            $(newRow).appendTo($('#tblBookings'));
          });

        }
        else {
          $("#output1").empty().append(funcReturn.cottageWeekRows); 
          alert("An error has occured\n\n" + funcReturn.cottageWeekRows );
        }
    })
    .always(() => getCottageBook() // update the existing bookings into the table
    ); // end of $.post

  } // end of else
} // end of function showCottageBook


// Function to get the bookings for the cottage number after the given date
function getCottageBook() {

    // remove all the booking highlight classes
    $("td").removeClass('provisional');
    $("td").removeClass('confirmed');
    // $("td").css("border","");
  
    $.post(
    "include/bookingView_ajax.php",
    {'method':'cottageBook_selectAll','dateSat': dateFns.format(momDateSat), 'cottageNum' : cottageNum, }
  )
  .done( function(data) {
      // update the CottageBook rows into the calendar table

      let funcReturn = JSON.parse(data);

      if (funcReturn.success === true) {
        cottageBookRows = funcReturn.cottageBookRows;
  
        // iterate over the rows updating the calendar table
        cottageBookRows.forEach((cottageBookRow) => {

          // convert firstNight into day of the week where Sat = 1
          let dowFirstNight = new Date(cottageBookRow.FirstNight).getDay() + 2;
          if (dowFirstNight === 8) {
            dowFirstNight = 1;
          }
          
          // find the table row with the dateSat attribute
          let tr = $("[datesat=" + cottageBookRow.DateSat + "]");

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

      }
      else {
        $("#output1").empty().append(funcReturn.cottageBookRows);
        alert("An error has occured\n\n" + funcReturn.cottageBookRows);
      }
  });
}


// document ready
$(document).ready(function() {

  // set up functions which add and remove class 'loading' when ajax starts or stops. See crud1.css
  let body = $("body");
  $(document).on({
      ajaxStart: function() { body.addClass("loading");},
      ajaxStop: function() { body.removeClass("loading");}    
  });

  // show current global version on title double click
  $("#title").on("dblclick click", function () {
    $(this).next().html(_VERSION).toggle();
  });


  // get the selected cottageNum when selection changes
  $("#cottageNum").change(function() {
    // debugger;
    cottageNum = $("#cottageNum").val();

    if (cottageNum === "-1") {
      $("#output1").html("Invalid cottage number: " + cottageNum);
    } else {

      // update and save the cottage number in session storage
      bookingMaint.cottageNum = cottageNum;

      showCottageBook();
      // set boolean so that no need to re load the calendar from the DB to save 0.5 seconds
      booCalendarDisplayed = true;
    }
  });


  // go to bookingMaint.php to view or make a booking for the selected cottageNum and DateSat of the row clicked
  $('#tbodyBookings').delegate('.view_details', 'click', function () {

    // get the DateSat from the nearest TR and update into bookingMaint session storage
    let rowTR = $(this).closest('tr');
    bookingMaint.dateSat = rowTR.attr('dateSat');

    // call bookingMaint.php to view or book for the selected week and cottageNum
    window.location = 'bookingMaint.php';
                              
  }); // end of view_details button click event


  $('[data-toggle="tooltip"]').tooltip();


  // get the selected dateSat when selection changes
  $("#wcDateSat").change(function() {
      // debugger;
    // if ($("#wcDateSat").val() === "-1") {
    //   $("#output1").html("Selected date is not valid");
    // } else {
    //   // a valid date has been selected
    //   momDateSat = moment($("#wcDateSat").val(), "YYYY-MM-DD");

    //   bookingMaint.dateSat = momDateSat._i;

    //   booCalendarDisplayed = false;
    //   showCottageBook();
    //   booCalendarDisplayed = true;

    // }
  });

}); // end of document ready
