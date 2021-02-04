/* eslint-disable eqeqeq */
// newBooking.js
'use strict';
p
var TODAY = new Date(Date.now());

// prevent going back 7 days from finding a date in the previous year. So check for January and day =< 7
if (TODAY.getMonth() === 0 && dateFns.getDate(TODAY) <=7) 
{
  // leave the date as is
} else // Move the date back 7 days so that the previous Saturday is displayed
{
  TODAY.setDate(TODAY.getDate() - 7);
}

const thisYear = dateFns.getYear(new Date(TODAY));
const nextYear = dateFns.getYear(new Date(dateFns.addYears(new Date(TODAY), 1)));

const body = $('body'); // used in document.on AjaxStart & AjaxStop

var cottageWeekRows, cottageBookAllRows;

// initialize selectedYear & displayYear to the current year
var selectedYear = thisYear;
var displayYear = selectedYear;

/*////////////////////////////////////////////////////////////////////////////
// document ready
////////////////////////////////////////////////////////////////////////////*/
$(function() {
    // label the select year buttons
    //$("#btnthisYear").text(thisYear);
    $("#btnthisYear").val(thisYear);

    //$("#btnnextYear").text(nextYear);
    $("#btnnextYear").val(nextYear);

    // label the select year labels
    $("#spnthisYear").text(thisYear);
    $("#spnnextYear").text(nextYear);

    // display the weekly bookings for the selected year
    showBookings();
}); // end of document ready

/*////////////////////////////////////////////////////////////////////////////
// setWeekBooked - function called by showBookings()
////////////////////////////////////////////////////////////////////////////*/
function setWeekBooked(DateSat, cottageNum, numNights) {
    if (numNights === '0') {
        return;
    }

    // find the table row with the DateSat attribute
    let tr = $('[DateSat=' + DateSat + ']');

    // select the correct column in the row
    let cottage = $(tr).children("[cottageNum='" + cottageNum + "']");

    // remove the price for booked weeks
    $(cottage).html('&nbsp'); // needed to use a non breaking space otherwise the background colour does not fill the cell

    // select the background colour key based on the number of nights
    if (numNights === '7') {
        // booked for week
        $(cottage).addClass('booked');
    } else {
        // has short breaks
        $(cottage).addClass('shortbreaks');
    }
}

/*////////////////////////////////////////////////////////////////////////////
// showBookings - get newBooking_crosstab and format the table
////////////////////////////////////////////////////////////////////////////*/
function showBookings() {
    // clear table and any messages when Year changes
    $('#output').empty();

    // get the bookings from today to the end of the current year OR from 1st Jan next year 'til the end of that year
    let startDate =
        selectedYear == thisYear
            ? dateFns.format(TODAY, 'YYYY-MM-DD') // the current date in the current year
            : dateFns.format(dateFns.startOfYear(new Date(selectedYear, 1, 1, 23, 59, 0)), 'YYYY-MM-DD'); // 1st January next year

    // set the year end date based on the selected year
    let yearEnd = dateFns.format(dateFns.endOfYear(new Date(selectedYear, 1, 1, 23, 59, 0)), 'YYYY-MM-DD');

    // Ajax call to return 3 data arrays
    let jqxhr = $.post('../include/newBooking_ajax.php', {
        method: 'newBooking_crosstab',
        startDate: startDate,
        yearEnd: yearEnd
    });

    jqxhr.done(function(data) {
        // put the CottageWeekRows data into the table
        let funcReturn = JSON.parse(data);

        if (funcReturn.success === true) {

            // store the detailed confirmed bookings in global var for use in the modal popup
            cottageBookAllRows = funcReturn.cottageBookAllRows;

            // iterate over the rows adding them to the calendar table
            cottageWeekRows = funcReturn.cottageWeekRows;
            cottageWeekRows.forEach(cottageWeekRow => {
                // debug console.log(index, cottageWeekRow);

                let fDateSat = dateFns.format(new Date(cottageWeekRow.DateSat), 'DD MMM');

                // let shortBreaks = cottageWeekRow.bShortBreaksAllowed
                //   ? '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'
                //   : '<i class="fa fa-thumbs-down" aria-hidden="true"></i>';

                let classForShortBreaks = cottageWeekRow.bShortBreaksAllowed
                    ? `<button type="button" class="btn btn-xs btn-success" data-toggle='tooltip' data-placement='auto' title='Click to view details of short break availability for this week'><i class="fa fa-book fa-lg"></i></button>`
                    : `<button type="button" class="btn btn-xs btn-success invisible" data-toggle='tooltip' data-placement='auto' title='Click to view details of short break availability for this week'><i class="fa fa-book fa-lg"></i></button>`;

                // format the text weekly rental prices for each cottage
                let CornflowerW =   cottageWeekRow.CornflowerW  === '0.00' ? '-POA' : parseInt(cottageWeekRow.CornflowerW).toLocaleString();
                let CowslipW =      cottageWeekRow.CowslipW     === '0.00' ? '-POA' : parseInt(cottageWeekRow.CowslipW).toLocaleString();
                let MeadowsweetW =  cottageWeekRow.MeadowsweetW === '0.00' ? '-POA' : parseInt(cottageWeekRow.MeadowsweetW).toLocaleString();

                let newRow = `
                    <tr DateSat='${cottageWeekRow.DateSat}' class='mb-3 mb-md-0'> 
                      <td style="width:10%;" class="align-middle">${fDateSat}</td> 
                      <td style="width:22%">${classForShortBreaks}</td>
                      <td style="width:22%" cottageNum='1'>£${CornflowerW}
                        <sub class="d-none d-print-inline"> £${parseInt(cottageWeekRow.CornflowerD).toLocaleString()}</sub>
                      </td> 
                      <td style="width:22%" cottageNum='2'>£${CowslipW}
                        <sub class="d-none d-print-inline"> £${parseInt(cottageWeekRow.CowslipD).toLocaleString()}</sub>
                      </td> 
                      <td style="width:22%" cottageNum='3'>£${MeadowsweetW}
                        <sub class="d-none d-print-inline"> £${parseInt(cottageWeekRow.MeadowsweetD).toLocaleString()}</sub>
                      </td> 
                    </tr>
                  `;

                // add the row to the calendar table
                $(newRow).appendTo($('#tblBookings'));
            }); // end of cottageWeekRows.forEach

            // now process the bookings array (if any)
            if (funcReturn.cottageBookRows) {
                let cottageBookRows = funcReturn.cottageBookRows;

                // iterate over the rows updating the calendar table by calling function setWeekBooked()
                cottageBookRows.forEach(cottageBookRow => {
                    setWeekBooked(cottageBookRow.DateSat, 1, cottageBookRow.CornflowerNights);
                    setWeekBooked(cottageBookRow.DateSat, 2, cottageBookRow.CowslipNights);
                    setWeekBooked(cottageBookRow.DateSat, 3, cottageBookRow.MeadowsweetNights);
                }); // end of foreach cottageBookRows
            }
        } else {
            alert('Prices are not yet available for ' + nextYear);
            // re-display the current year by clicking the btnthisYear
            $('#btnthisYear').trigger('click');
        }
    }); // end of $.post
} // end of function showBookings

// Select year buttons click event
$(':radio').on('click', function(event) {
    event.preventDefault();

    // get the selected year from the button value
    selectedYear = $(this).val();

    $('#output').html(selectedYear);

    // ignore if user selects the year that is currently displayed
    if (displayYear != selectedYear) {
        displayYear = selectedYear;
        $('#tbodyBookings').empty();

        showBookings(); // re-populate the bookings for the newly selected year
    }
});

// pop up modal form with the confirmed bookings by day for the selected week
$('#tbodyBookings').delegate('.btn-success', 'click', function() {
    // clear out any previous popups
    $('#tbodyModal').empty();

    // get the DateSat from the nearest TR and update into bookingMaint session storage
    let rowTR = $(this).closest('tr');
    let DateSat = dateFns.format(new Date(rowTR.attr('DateSat')), 'YYYY-MM-DD');
    let modalTitle = dateFns.format(new Date(DateSat), 'w/c dddd D MMMM YYYY');

    $('#exampleModalLongTitle').html(modalTitle);

    // get the per day cost from cottageWeekRows by filtering the array on row.DateSat = DateSat
    let cottageWeekRow = cottageWeekRows.filter(function(row) {
        return row.DateSat === DateSat;
    });

    // loop for 2 cottages (Cowslip 2 and Meadowsweet 3)
    for (let index = 2; index < 4; index++) {
        // set up the cottage name and cost per night for the table row
        let cottageName, perDay;
        let booked = ['', '', '', '', '', '', ''];

        switch (index) {
            case 1:
                cottageName = 'Cornflower';
                perDay = cottageWeekRow[0].CornflowerD;
                break;
            case 2:
                cottageName = 'Cowslip';
                perDay = cottageWeekRow[0].CowslipD;
                break;
            case 3:
                cottageName = 'Meadowsweet';
                perDay = cottageWeekRow[0].MeadowsweetD;
                break;
        }

        // filter the all bookings array for the selected DateSat and cottageNum (index)
        let cottageBookRows = cottageBookAllRows.filter(function(row) {
            return row.DateSat === DateSat && row.CottageNum === index;
        });

        // for each booking for the week and cottage number
        cottageBookRows.forEach(cottageBookRow => {
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
        // <TD>${perDay}</TD> day prices removed 2019-09-29
        const cottageTemplate = `<TR>
                      <TD>${cottageName}</TD>
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
}); // end of short break view_details button click event

// set up functions which add and remove class 'loading' when ajax starts or stops. See crud1.css
$(document).on({
    ajaxStart: function() {
        body.addClass('loading');
    },
    ajaxStop: function() {
        body.removeClass('loading');
    }
});
