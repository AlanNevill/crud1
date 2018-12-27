<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>MGF booking</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="css/crud1.css" />
    <link rel="stylesheet" href="css/bookingMaint.css" />


</head>

<body>

<?php

    include('include/dbFuncs.php');     // open db connection and instantiate dbFuncs class

    $data = array();
    $options = '';                
    $dateSat = date("Y-m-d"); // default today's date

    // select distinct DateSat rows from CottageWeek table
    $data = $dbFuncs->dateSat_select($dateSat);

    // warning if no rows returned
    if(count($data)==0) {
        $dbFuncs->ProcessLog_insert('E', 'MGF2','dbFuncs.dateSat_select', 'me', 'No weeks found', $dateSat);
        die('<h1>No weeks found</h1>');
    }

    // enumerate the rows returned into HTML select options for date Sat select
    foreach($data as $row)
    {
        $options    .= '<option value=' . $row["DateSat"] 
                    . ' bShortBreaksAllowed=' . $row["bShortBreaksAllowed"] . '>'
                    . date("d M Y", strtotime($row["DateSat"])) 
                    . '</option>';
    }

?>

<div class="container">

    <!-- debug info div for validation and error messages -->
    <div id="info" class="d-none">info</div>

    <h4 id="title">MGF Booking Details</h4><span></span>

    <!-- form to allow user to select the wc Sat date and the cottage number -->
    <form  id="formGet" >
        <input type="hidden" name="method" value="CottageBook_select"> <!-- method=CottageBook_select for bookingMaint1 function selection -->
        <label for="wcDateSat">W/c Sat. date</label>
        <select id="wcDateSat"  name="wcDateSat" class="custom-select" style="width:150px">
            <option value="-1" selected disabled hidden>Select Saturday</option>
            <?php echo $options ?>
        </select>

        <label for="cottageNum">Select a cottage</label>
        <select id="cottageNum" name="cottageNum" class="custom-select" style="width:150px">
            <option value="-1" selected disabled hidden>Select cottage</option>
            <option value="1">Cornflower</option>
            <option value="2">Cowslip</option>
            <option value="3">Meadowsweet</option>
        </select>

        <input id="submitButton" type="hidden" value="Get bookings">

    </form>

    <!-- existingBookings holds the list of existing bookings for the wc Sat date and the cottage number -->
    <h5>Existing bookings</h5>
    <div class="table-responsive">
        <table id="tblBookings" class="table table-light">
            <caption class="d-sm-none">Scroll right to change status & delete</caption>
            <thead class="thead-light">
                <tr>
                    <th>Arrive date</th>
                    <th>Last night</th>
                    <th>Nights</th>
                    <th>Rent</th>
                    <th>Ref.</th>
                    <th>Notes</th>
                    <th data-toggle='tooltip' data-placement='auto' title='A booking is (C)onfirmed or (P)rovisional'>Amend status</th>
                    <th data-toggle='tooltip' data-placement='auto' title='Delete the booking'>Del.</th>
                </tr>
            </thead>
            <tbody id="tbodyBookings">
            </tbody>
        </table>
    </div>
    
    <!-- error & warning messages -->
    <div id="output1" class="text-white bg-dark"></div> 

    <!-- cottageWeek row data -->
    <section class="CottageWeek_data text-white bg-secondary">
        <div class="row">
            <div id="WeekRent" class="col-4"></div>
            <div id="bShortBreaksAllowed" class="col-5 text-align-center"></div>
            <div id="DayRent" class="col-3"></div>
        </div>
    </section>

    <!-- new booking form -->
    <form id="frmNewBooking" role="form" name="frmNewBooking" class="form insertForm form-horizontal" action="" oninput="" style="display:none;">
        <h5>New booking</h5>
        <input type="hidden" id="method"  name="method" value="insert">
           <!-- method=insert for bookingMaint2 function insert -->
        <input type="hidden" id="dateSat" name="dateSat">           <!-- dateSat    for bookingMaint2 functions check & insert -->
        <input type="hidden" id="cottageNum" name="cottageNum">        <!-- cottageNum for bookingMaint2 functions check & insert -->
        <div class="form-row">
            <div class="col-xs-6">
                <label for="firstNight" class="form-control-label">Arrival date</label>
            </div>
            <div class="col-xs-6">
                <select id="firstNight" name="firstNight" class="form-select form-control"></select>
            </div>

            <div class="col-xs-4">
                <label for="lastNight" class="form-control-label">Last night</label>
            </div>
            <div class="col-xs-6">
                <select id="lastNight" name="lastNight" class="form-select form-control"></select>
            </div>
            <div class="col-xs-4">
                <span id="nights" name="nights">1 night</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-xs-2">
                <label for="Rental" class="form-control-label">Rent</label>
            </div>
            <div class="col-xs-4">
                <input id="Rental" name="Rental" type="text" class="form-control" value="0" style="width:120px">
            </div>
            <div class="col-xs-6">
                <label for="BookingStatus">Status</label>
                <select id="BookingStatus" name="BookingStatus" class="custom-select" style="width:120px">
                    <!-- TODO load status options from DB table StatusCodes where category='booking' -->
                    <option value="P" selected>Provisional</option>
                    <option value="C">Confirmed</option>
                </select>
            </div>
        </div>
        <label for="notes" id="notesLabel" class="form-control-label">Notes</label>
        <textarea id="notes" name="notes" class="form-control rounded-0 boxsizingBorder" type="textarea" rows="1" maxlength="100" placeholder="Name of guest,  contact no., email etc." ></textarea><span></span>
        <div class="form-group">
            <div class="row">
                <div class="col-12 text-center">
                    <input id="submitNewBooking" class="btn btn-default" type="button" value="Make new booking">
                </div>
            </div>
        </div>
    </form>

    <!-- div for new booking form messages -->
    <div id="newBookingInfo"></div>

    <!-- div for in progress gif during ajax calls -->
    <div class="modal"></div>
</div>


    <!-- <script src="js/jquery.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <!-- jquery from library NB. not compatible with jquery.min.slim.js-->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script> -->

    <!-- Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"     crossorigin="anonymous">
    </script>

    <!-- Latest compiled and minified Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"     crossorigin="anonymous">
    </script>

    <!-- Latest compiled and minified mustache.js templating JavaScript -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/oj.mustache/0.7.2/oj.mustache.js" ></script> -->

    <!-- moment date formatting library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

    <!-- currency formatting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.1.0/autoNumeric.min.js"></script>

    <!-- date formatting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.min.js"></script>

    <!-- my classes -->
    <script src="js/classes.js"></script>

    <!-- this page javascipt -->
    <script src="js/bookingMaint.js"></script>

</body>
</html>
