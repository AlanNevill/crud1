// bookingMaint.js
    const version = "v0.52";

    var objDateSat, cottageNum, trIdNum, rentalVal;

    var booCottageNum = false;
    var booDateSat = false;

    var cottageWeek;

$(document).ready(function () {
   
    // hide the booking insert form until a satDate & cottageNum have been input
    $('.insertForm').hide();


    // autoNumeric for rental field in New Booking Form
    // rentalVal = new AutoNumeric('#Rental > input', AutoNumeric.getPredefinedOptions().British);
    rentalVal = new AutoNumeric('#Rental', AutoNumeric.getPredefinedOptions().numericPos);
    rentalVal.options.maximumValue('9999.99');
    rentalVal.options.currencySymbol('£');
    rentalVal.options.digitGroupSeparator(',');


    // set up functions which add and remove class 'loading' when ajax starts or stops. See crud1.css
    $body = $("body");

    $(document).on({
        ajaxStart: function() { $body.addClass("loading");},
        ajaxStop: function() { $body.removeClass("loading");}    
    });

    
    // show current version on title double click
    $("#title").on("dblclick click", function () {
        $(this).next().html(version).toggle();
    });


    // update the number of remaining chars in the Notes field of the new booking form
    $('textarea').keyup(function(){
        if (this.value.length > $(this).attr('maxlength')) {
            return false;
        }
        $(this).next().html("Remaining characters : " + ($(this).attr('maxlength') - this.value.length));
    });


    // get the selected dateSat when selection changes 
    $('#wcDateSat').change(function () {
        if ($('#wcDateSat').val()=="-1") {
                $("#output1").html('Selected date is not valid');
                objDateSat = null;
                booDateSat = false;
                showFrmNewBooking();
        }
        else {
            // a valid date has been selected
            objDateSat = moment($('#wcDateSat').val(), 'YYYY-MM-DD');
            booDateSat = true;

            // put the date into the frmNewBooking in case the user does an insert
            $("#frmNewBooking input[name=dateSat]").val(moment(objDateSat,'YYYY-MM-DD').format('YYYY-MM-DD'));

            //  set up the firstNight & lastNight date selection drop down options
            $('firstNight').empty();
            $('lastNight').empty();

            // debugger; 
        
            for (let i = 0; i < 7; i++) {
                document.frmNewBooking.firstNight.options[i]=new Option(moment(objDateSat,'YYYY-MM-DD').add(i, 'days').format('ddd DD MMM'), moment(objDateSat,'YYYY-MM-DD').add(i, 'days').format('YYYY-MM-DD'), false, false);
                document.frmNewBooking.lastNight.options[i]=new Option(moment(objDateSat,'YYYY-MM-DD').add(i, 'days').format('ddd DD MMM'), moment(objDateSat,'YYYY-MM-DD').add(i, 'days').format('YYYY-MM-DD'), false, false);
            }

            // set defaults: lastNight to next Friday, 7 nights,
            $('#lastNight option:eq(6)').prop('selected', true); 
            $("#frmNewBooking #nights").text("7 nights");

            showFrmNewBooking();
        }
    });


    // get the selected cottageNum when selection changes 
    $('#cottageNum').change(function () {
        // debugger; 
        cottageNum = $('#cottageNum').val();
   
        if (cottageNum == "-1") {
            $("#output1").html('Invalid cottage number: ' + cottageNum) ;
            booCottageNum = false;
            showFrmNewBooking();
        }
        else {
            // put the cottageNum into the frmNewBooking in case the user does an insert
            $("#frmNewBooking input[name=cottageNum]").val(cottageNum);

            // show the new CottageWeek_data section
            $('.CottageWeek_data').show();

            booCottageNum = true;
            showFrmNewBooking();
        }
    });


    function showFrmNewBooking() {

        // clear any new booking messages when dateSat or cottageNum change
        $('#newBookingInfo').empty();
        $('#output1').empty();

        if (booDateSat && booCottageNum) {

            // refresh the existing bookings list for this date and cottage
            $("#submitButton").click();

            // show the new booking form but with add button disabled
            $('.insertForm').show();

        }
        else {
            $('.insertForm').hide();
        }
    }


    // called by function showFrmNewBooking()
    // debug: user clicks submit button on get form
    $("#submitButton").click(function(e) {

        e.preventDefault();

        // date or cottage number has changed so empty the bookings table and clear any output1 messages
        $('#tbodyBookings').empty();
        $("#output1").empty();


        // if dateSat and cottage number are both true get 
        // 1. CottageWeek data to display
        // 2. any existing bookings from CootageBook table
        if (booDateSat && booCottageNum) {

            // 1. get the CottageWeek row
            var dateSat = moment(objDateSat).format('YYYY-MM-DD');
            $.post(
                "include/bookingMaint2.php",
                {'method':'cottageWeek_get','dateSat': dateSat, 'cottageNum' : cottageNum }
            )
            .done( function(data) {
                // put the CottageWeek data into the section CottageWeek_data
                cottageWeek = JSON.parse(data);
                if (cottageWeek.success == true) {
                    $('#DayRent').html('Rent / day £' + cottageWeek.cottageWeekRow[0].RentDay);
                    $('#WeekRent').html('Rent / week £' + cottageWeek.cottageWeekRow[0].RentWeek);
                    $('#bShortBreaksAllowed').html('Short breaks allowed this week: ' + (cottageWeek.cottageWeekRow[0].bShortBreaksAllowed == true));

                    // put the weekly rent into the new booking form
                    $('#frmNewBooking #Rental').val(cottageWeek.cottageWeekRow[0].RentWeek);
                    rentalVal.set(cottageWeek.cottageWeekRow[0].RentWeek);
                }
                else {$("#output1").empty().append(cottageWeekRow[0]);}
            });


            // 2. get any existing bookings from CottageBook
            $.post(
                    "include/bookingMaint2.php",
                    $("#formGet").serialize()
                )
                .done( function(data) {
// debugger;
                    if (data.indexOf('No bookings yet') !== -1) {
                        $("#output1").append(data);
                    } 
                    else {
                        // populate the existing bookings table
                        $('#tbodyBookings').append(data);
                    }
                
                })
                .always( function(data) {

                    // if trIdNum points to a newly inserted booking row then set its background color
                    $(trIdNum).css('background-color', '#a8cb17');
                    tridNum = null;

                })
                .fail((error) => $("#output1").empty().append(error.statusText));
        }
        else { // don't POST as one or both of the dateSat and cottageNum have not been selected
            $('#output1').empty().append("<h3>Please select both a date and cottage number</h3>");
        }
    });


    // firstNight or lastNight selection changes
    $('#firstNight, #lastNight').on('change', function() {

        // clear any messages and disable the submit button
        $('#output1').empty();
        $('#submitNewBooking').prop('disabled', true);

        // sanity check proposed booking dates in UI & update the number of nights field on the form
        if ($('#lastNight').val() < $('#firstNight').val()) {
            $('#output1').append("<h5>The last night cannot be before the arrival date</h5>");
            return;
        }

        // if the date check succeed then enable the new booking button
        $('#submitNewBooking').prop('disabled', false);
// debugger;

        // Update number of nights field in the form
        let a = moment($('#lastNight').val());
        let b = moment($('#firstNight').val());
        let c = a.diff(b, 'days') + 1;   
        $("#frmNewBooking #nights").text(c + ( c > 1 ? " nights" : " night" ));

        // update the rental field
        if (c == 7 ) {
            // $('#frmNewBooking #Rental').val(cottageWeek.cottageWeekRow[0].RentWeek);
            rentalVal.set(cottageWeek.cottageWeekRow[0].RentWeek);
        }
        else {
            // $('#frmNewBooking #Rental').val(cottageWeek.cottageWeekRow[0].RentDay * c);
            rentalVal.set(cottageWeek.cottageWeekRow[0].RentDay * c);
        }
    });
    

    // user clicks submit button on frmNewBooking form
    // TODO Move checking and insert into PHP as one post with validation on Rental field max and min values
    $("#submitNewBooking").click(function(e) {

        e.preventDefault();

        // disable the submit button
        $('#submitNewBooking').attr("disabled", true);

        $('#outpu1').empty();

        // check for date clashes with existing bookings by setting method=check
        $("#frmNewBooking input[name=method]").val('insert');

        // unformat the rental field before posting to DB
        $('#Rental').val(rentalVal.getNumericString());

        // ajax post to method=insert
        $.post(
                "include/bookingMaint2.php",
                $("#frmNewBooking").serialize()
            )
            .done( function(data) { // check for date clashes with existing bookings
// debugger;
                const returnArray = JSON.parse(data);

                // process any errors in method=insert
                if (!returnArray.success) {
                    switch (returnArray.messSeverity) {
                        // 2 types of warning
                        case 'W':
                            if (returnArray.clashCount > 0) {
                                $('#output1').empty().append('<h5>The proposed booking clashes with ' + returnArray.clashCount + ' existing booking' + (returnArray.clashCount > 1 ? 's' : '') + '</h5>');
                            }
                            // must be a warning about the rental value greater then 9,999.99
                            else{
                                $('#output1').empty().append('<h5>The rental is greater the £9,999.99</h5>');

                            }
                            break;
                        // any error
                        case 'E':
                            // TODO insert line breaks
                            $('#output1').empty().append('<h5>Error has occurred - contact support' + returnArray.errorMess);
                        break;
                    } // end of switch
                }      
                // method=insert returned success, so process the new booking
                else {
// debugger;
                    // set trIdNum to the IdNum of the newly inserted row so that when the table is refreshed the row can be highlighted
                    trIdNum = '#'+returnArray.insertedIdNum;

                    // reinstate the Rental field formatting by triggering the focus event
                    rentalVal.set( $('#Rental').val() );

                    // No errors found so row was inserted in db table therefore add clear any messages
                    $("#output1").empty();

                    // requery the database in case someone else has removed or inserted and get the rows in the correct date order
                    // TODO temp disable
                    // $('#submitButton').click();


                    // Remove class=highlight from any previously inserted row in the table
                    $('#tbodyBookings > tr').removeClass('highlight');

                    // new booking row mustache template
                    const bookingTemplate  = "" +
                            "<tr class='highlight' firstNight='{{ArriveDate}}'>" +
                            "<td>{{FirstNight}}</td>" +
                            "<td>{{LastNight}}</td>" +
                            "<td>{{numNights}}</td>" +
                            "<td>{{Rental}}</td>" +
                            "<td>{{BookingRef}}</td>" +
                            "<td>{{Notes}}</td>" +
                            "<td><button class='remove' IdNum={{IdNum}}></button></td></tr>";

                    // get the new booking form data into an array
                    let frmNewBookingSer = $("#frmNewBooking").serializeArray();
            
                    // associative array for use with mustache tamplate
                    let row  = {
                                ArriveDate: moment(frmNewBookingSer[3].value).format('YYYY-MM-DD'),
                                FirstNight: moment(frmNewBookingSer[3].value).format('ddd DD MMM'),
                                LastNight:  moment(frmNewBookingSer[4].value).format('ddd DD MMM'),
                                numNights:  function () {
                                                let a = moment(frmNewBookingSer[4].value);
                                                let b = moment(frmNewBookingSer[3].value);
                                                let c = a.diff(b, 'days') + 1;
                                                return  c; },
                                Rental:     rentalVal.getFormatted(),
                                BookingRef: returnArray.bookingRef,
                                Notes:      frmNewBookingSer[6].value,
                                IdNum:      returnArray.insertedIdNum
                                };

                    // render the new booking row using the mustache template
                    var newBookingRow = Mustache.render(bookingTemplate,row);

                    // add the row to the existing bookings table rows
                    $(newBookingRow).appendTo($('#tblBookings'));

                    // sort the bookings by the tr attribute firstNight so that the inserted row is in the correct order
                    let tbody = $('#tbodyBookings');

                    let rows = $('#tbodyBookings').find('tr').get();
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

                    // TODO clear down the new booking form
                    // showFrmNewBooking();

                    // display message for 4 seconds
                    $("#newBookingInfo").empty().append('Booking made').fadeOut(4000);
                }
            })
            // always re-enable the submit new booking button
            .always(() => $('#submitNewBooking').removeAttr("disabled"))
    
            .fail((error) => $("#newBookingInfo").empty().append(error.statusText))
            ; // end of $.post method=insert

    });   // end of #submitNewBooking.click


    // Remove a booking by defining a delegate on the table body which fires on click of any remove button
    $('#tbodyBookings').delegate('.remove', 'click', function () {

        // disable any remaining row remove buttons
        $('btn .remove').attr("disabled", true);

        // find the table row of the deleted booking and remove with fade out
        let removeTR = $(this).closest('tr');

        // post the delete to the PHP URL with the IdNum extracted from the tr
        $.post( "include/bookingMaint2.php", 
                {'method':'delete','IdNum': $(this).attr('IdNum')}
        )
        .done(function (response) {

            // don't refresh the bookings from the DB so just remove the row from the table
            if (response.indexOf('error') === -1) {
                    removeTR.fadeOut(500, function() {
                        $(this).remove(); 
                });  
            }

            // remove any previous clash message
            $("#output1").empty();

            // re-enable the row remove button(s)
            $('btn .remove').removeAttr("disabled");

            $("#newBookingInfo").empty().append(response).fadeOut(5000);
            
        }); // end of $.post
                                
    }); // end of remove button click event

}); // end of (document).ready


// TODO provide lookup function by booking ref
// generate a Char(4) booking reference
function bookingRef() {
    return Math.floor((1 + Math.random()) * 0x10000)
        .toString(16)
        .substring(1)
        .toUpperCase();
}

// prevent the enter key submitting the new booking form
$('#frmNewBooking').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) { 
        e.preventDefault();
        return false;
    }
});



