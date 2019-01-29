// newBooking.js
'use strict';

const thisYear = dateFns.getYear(new Date(TODAY));
const nextYear = dateFns.getYear(new Date(dateFns.addYears(new Date(TODAY),1)));

var cottageWeekRows, 
    cottageBookAllRows; 

// // filter function to select all the cottageBookAllRows for a given DateSat
// function selectDateSat(DateSat) {
//   return DateSat === cottageBookAllRows.DateSat;
// }


// function called by showBookings()
function setWeekBooked(dateSat, cottageNum, numNights) {

  if (numNights==='0') { 
    return;
  }

  // find the table row with the dateSat attribute
  let tr = $("[datesat=" + dateSat + "]");

  // select the correct column & remove the weekly rent
  let cottage = $(tr).children("[cottageNum='" + cottageNum + "']");
  $(cottage).html("&nbsp"); // needed to use a non breaking space otherwise the background does not fill the cell
  
  // select the background colour key based on the number of nights
  if (numNights==='7') {
    // booked for week
    $(cottage).addClass('booked');
  } else {
    // has short breaks
    $(cottage).addClass('shortbreaks');
  }
}


function showBookings() {
  // clear any messages when Year changes
  $("#output1").empty();

  let selectedYear = $('#theYear option:selected').text();
  let startDate;

  // get the bookings from today to the end of the current year or from 1st Jan next year 'til the end of that year
  if (selectedYear == thisYear) {
    startDate  = dateFns.format(TODAY,'YYYY-MM-DD');  // the current date in the current year
  } else { // 1st January next year
    startDate = dateFns.format(dateFns.startOfYear(new Date(selectedYear,1,1,23,59,0)),'YYYY-MM-DD');
  }

  let yearEnd = dateFns.format(dateFns.endOfYear(new Date(selectedYear,1,1,23,59,0)), 'YYYY-MM-DD');

  $.post(
    "include/newBooking_ajax.php",
    {'method':'newBooking_crosstab','startDate': startDate, 'yearEnd' : yearEnd }
  )
  .done( function(data) {
      // put the CottageWeekRows data into the table
      let funcReturn = JSON.parse(data);

      if (funcReturn.success === true) {

        // store the detailed confirmed bookings in global var for use in the modal popup
        cottageBookAllRows = funcReturn.cottageBookAllRows;
  
        // iterate over the rows adding them to the calendar table
        cottageWeekRows = funcReturn.cottageWeekRows;
        cottageWeekRows.forEach((cottageWeekRow) => {
          // debug console.log(index, cottageWeekRow);

          let fDateSat = dateFns.format(new Date(cottageWeekRow.datesat),'DD MMM');
          let shortBreaks = cottageWeekRow.bShortBreaksAllowed ? 'Y' : 'N';
          let classForShortBreaks = cottageWeekRow.bShortBreaksAllowed ? "<button type='button' class='btn view_details'></button>" : "";

          let newRow = `
            <tr dateSat='${cottageWeekRow.datesat}'> 
            <td style="width:10%">${fDateSat}</td> 
            <td style="width:10%">${shortBreaks}</td>
            <td style="width:20%" cottageNum='1'>${cottageWeekRow.CornflowerW}</td> 
            <td style="width:20%" cottageNum='2'>${cottageWeekRow.CowslipW}</td> 
            <td style="width:20%" cottageNum='3'>${cottageWeekRow.MeadowsweetW}</td> 
            <td style="width:20%">${classForShortBreaks}</td></tr>
          `;

          // add the row to the calendar table
          $(newRow).appendTo($('#tblBookings'));
        }); // end of cottageWeekRows.forEach 

        // now process the bookings array (if any)
        if (funcReturn.cottageBookRows) {
          let cottageBookRows = funcReturn.cottageBookRows; 

          // iterate over the rows updating the calendar table
          cottageBookRows.forEach((cottageBookRow) => {

            setWeekBooked(cottageBookRow.DateSat, 1, cottageBookRow.CornflowerNights);
            setWeekBooked(cottageBookRow.DateSat, 2, cottageBookRow.CowslipNights);
            setWeekBooked(cottageBookRow.DateSat, 3, cottageBookRow.MeadowsweetNights);


          }); // end of foreach cottageBookRows

        }

      }
      else {
        $("#output1").empty().append(funcReturn.cottageWeekRows); 
        alert("An error has occured\n\n" + funcReturn.cottageWeekRows );
      }
  })
  ; // end of $.post

} // end of function showBookings


// document ready
$(document).ready(function() {

  // set up the year selection options
  $('#theYear').append($('<option>', {
    value: thisYear,
    text: thisYear
  }));

  $('#theYear').append($('<option>', {
    value: nextYear,
    text: nextYear
  }));

  // select the current year
  $('#theYear option[value=' + thisYear + ']').attr('selected','selected');


  // set up functions which add and remove class 'loading' when ajax starts or stops. See crud1.css
  let body = $("body");
  $(document).on({
      ajaxStart: function() { body.addClass("loading");},
      ajaxStop: function() { body.removeClass("loading");}    
  });

  // display the weekly bookings
  showBookings();


  // get the selected Year when selection changes
  $("#theYear").change(function() {
    // clear out previouysly displayed bookings
    $('#tbodyBookings').empty();

    showBookings(); // re-populate the bookings for the selected year
  });


  // pop up modal form with the confirmed bookings by day for the selected week
  $('#tbodyBookings').delegate('.view_details', 'click', function () {

    // clear out any previous popups
    $('#tbodyModal').empty();

    // get the DateSat from the nearest TR and update into bookingMaint session storage
    let rowTR = $(this).closest('tr');
    let DateSat = dateFns.format(new Date(rowTR.attr('dateSat')), 'YYYY-MM-DD');
    let modalTitle = dateFns.format(new Date(DateSat), 'w/c dddd D MMMM YYYY');

    $('#exampleModalLongTitle').html(modalTitle);

    // get the per day cost from cottageWeekRows
    let cottageWeekRow = cottageWeekRows.filter(function(row) {
      return row.datesat === DateSat;
    });
    // loop for 3 cottages
    for (let index = 1; index < 4; index++) {

      // set up the cottage name and cost per night for the table row
      let cottageName, perDay;
      let booked = ['','','','','','','']; //

      switch (index) {
        case 1:
          cottageName = "Cornflower";
          perDay = cottageWeekRow[0].CornflowerD;
          break;
        case 2:
          cottageName = "Cowslip";
          perDay = cottageWeekRow[0].CowslipD;
          break;
        case 3:
          cottageName = "Meadowsweet";
          perDay = cottageWeekRow[0].MeadowsweetD;
          break;
      }

      // filter the all bookings array for the selected DateSat and cottageNum (index)
      let cottageBookRows = cottageBookAllRows.filter(function(row) {
        return (row.DateSat === DateSat && row.CottageNum === index);      
      });

      // for each booking for the week and cottage number
      cottageBookRows.forEach((cottageBookRow) => {

        // convert firstNight into day of the week where Sat = 0
        let dowFirstNight = new Date(cottageBookRow.FirstNight).getDay() + 1;
        if (dowFirstNight === 7) {
          dowFirstNight = 0;
        }
        
        // set up which days are booked
        for (let index = dowFirstNight; index < dowFirstNight + cottageBookRow.numNights; index++) {
          booked[index] = ' class="booked"';
        }
      });

      // format the row
      const cottageTemplate = `<TR>
                        <TD>${cottageName}</TD>
                        <TD>${perDay}</TD>
                        <TD ${booked[0]}></TD>
                        <TD ${booked[1]}></TD>
                        <TD ${booked[2]}></TD>
                        <TD ${booked[3]}></TD>
                        <TD ${booked[4]}></TD>
                        <TD ${booked[5]}></TD>
                        <TD ${booked[6]}></TD>
                        </TR>`;

      // add the row to the modal table
      $(cottageTemplate).appendTo($('#tbodyModal'));

    } // end of for each cottage number 1-3

    $('#myModal').modal('show');
                              
  }); // end of view_details button click event

  
  // get the page title and use it to select the menu <a> element with the same id then add the active class
  $("#" + $(this).attr('title')).addClass("active");

  $('[data-toggle="tooltip"]').tooltip();


}); // end of document ready

