<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />

  <title>MGF booking</title>

  <?php include '../include/MGF_header.html'; ?>        <!-- favicon + bootstrap & fork awesome style sheets -->

  <link rel="stylesheet" href="../css/crud1.css" />
  <link rel="stylesheet" href="../css/bookingMaint.css" />

</head>

<body>

  <?php
    // get the future date Saturday dates from CottageWeek to populate the dropdown select w/c Sat. date

    include('../include/dbFuncs.php');     // open db connection and instantiate dbFuncs class

    $options = '';                
    $dateSat = date("Y-m-d", strtotime('-7 days')); // default today's date - 7 days

    // select distinct DateSat rows from CottageWeek table
    $data = $dbFuncs->dateSat_select($dateSat);

    // warning if no rows returned
    if(count($data)==0) {
      $dbFuncs->ProcessLog_insert2('E', 'bookingMaint.php', 'dbFuncs.dateSat_select', 'No weeks found', $dateSat);
      die('<h1>No weeks found</h1>');
    }

    // enumerate the rows returned into HTML select options for date Sat select
    foreach($data as $row)
    {
      $options  .= '<option value=' . $row["DateSat"] 
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

    <div class="form-row">
      <div class="form-group col-6">
        <label for="wcDateSat" class="mb-0">W/c Sat. date</label>
        <select id="wcDateSat"  name="wcDateSat" class="custom-select" >
            <option value="-1" selected disabled hidden>Select Saturday</option>
            <?php echo $options ?>
        </select>
      </div>
      <div class="form-group col-6">
        <label for="cottageNum" class="mb-0">Select a cottage</label>
        <select id="cottageNum" name="cottageNum" class="custom-select">
            <option value="-1" selected disabled hidden>Select cottage</option>
            <option value="1">Cornflower</option>
            <option value="2">Cowslip</option>
            <option value="3">Meadowsweet</option>
        </select>
      </div>

      <input id="submitButton" type="hidden" value="Get bookings">
    </div>
  </form>

  <!-- existingBookings holds the list of existing bookings for the wc Sat date and the cottage number -->
  <h5 class="mt-3">Existing bookings</h5>

  <table id="tblBookings" class="table table-bordered table-responsive-md">
    <caption class="d-sm-none">Scroll right to edit or delete</caption>
    <thead class="thead-light">
      <tr class="d-flex">
        <th class="col-2">Arrive date</th>
        <th class="col-2">Last night</th>
        <th class="col-1">Nights</th>
        <th class="col-1">Rent</th>
        <th class="col-1">Ref.</th>
        <th class="col-2">Notes</th>
        <th class="col-2" data-toggle='tooltip' data-placement='auto' title='A booking is (C)onfirmed or (P)rovisional'>Status</th>
        <th class="col-1" data-toggle='tooltip' data-placement='auto' title='Edit or delete the booking'>Edit Del.</th>
      </tr>
    </thead>
    <tbody id="tbodyBookings">
    </tbody>
  </table>

  <!-- error & warning messages -->
  <div id="output1" class="text-white bg-dark"></div> 

  <!-- cottageWeek row data -->
  <section class="CottageWeek_data text-white bg-secondary ">
    <div class="row mt-3 mb-3">
      <div id="WeekRent" class="col-4"></div>
      <div id="bShortBreaksAllowed" class="col-5 text-align-center"></div>
      <div id="DayRent" class="col-3"></div>
    </div>
  </section>


  <!-- new booking form -->
  <form id="frmNewBooking" role="form" name="frmNewBooking" class="form insertForm form-horizontal p-2 " style="display:none;">

    <h5>New booking form</h5>

    <input type="hidden" id="method"  name="method" value="insert">
        <!-- method=insert for bookingMaint2 function insert -->
    <input type="hidden" id="dateSat" name="dateSat">         <!-- dateSat    for bookingMaint2 functions check & insert -->
    <input type="hidden" id="cottageNum" name="cottageNum">   <!-- cottageNum for bookingMaint2 functions check & insert -->
    <div class="form-row">

      <div class="form-group m-2">
          <label for="firstNight" class="form-control-label mb-0">Arrival date</label>
          <select id="firstNight" name="firstNight" class="form-select form-control"></select>
      </div>

      <div class="form-group m-2">
          <label for="lastNight" class="form-control-label mb-0">Last night</label>
          <select id="lastNight" name="lastNight" class="form-select form-control"></select>
      </div> 

      <div class="form-group m-2" style="width: 70px">
          <label for="nights" class="form-control-label mb-0">Nights</label>
          <input id="nights" name="nights" type="text" class="form-control-plaintext text-center" value="7" readonly>
      </div>

      <div class="form-group m-2" style="width: 100px">
          <label for="Rental" class="form-control-label mb-0">Rent</label>
          <input id="Rental" name="Rental" type="text" class="form-control" value="0" required>
      </div>
      <div class="form-group m-2" style="width: 150px">
          <label for="BookingStatus" class="form-control-label mb-0">Status</label>
          <select id="BookingStatus" name="BookingStatus" class="custom-select">
              <!-- TODO load status options from DB table StatusCodes where category='booking' -->
              <option value="P" selected>Provisional</option>
              <option value="C">Confirmed</option>
          </select>
      </div>

    </div>

    <label for="notes" id="notesLabel" class="form-control-label mb-0">Notes</label>
    <textarea id="notes" name="notes" class="form-control rounded-0" type="textarea" rows="1" maxlength="100" placeholder="Name of guest,  contact no., email etc." ></textarea><span class="ml-1 mb-3"></span>

    <div class="form-row">
      <div class="col-12 text-center mb-3">
          <input id="submitNewBooking" class="btn btn-lg btn-success" type="button" value=" Make new booking ">
      </div>
    </div>

  </form>

  <!-- div for new booking form messages -->
  <div id="newBookingInfo"></div>

  <!-- div for in progress gif during ajax calls -->
  <div class="ajaxLoading"></div>

  <!-- Modal form for updating a booking's Notes & Booking status columns -->
  <div class="modal fade" id="mybookingUpdForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Update booking</h5>
          <button id="modalCloseX" type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close fa-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <div id="innerbookingUpdFrm" class="md-form" >
            <div class="form-row">
              <input id="idnumUpd" type="hidden">
              <input id="bookingRefUpd" type="hidden">

              <div class="form-group col-3 m-2">
                <label for="firstNightUpd" class="form-control-label mb-0">Arrival date</label>
                <input id="firstNightUpd" name="firstNight" class="form-control" readonly>
              </div>

              <div class="form-group col-3 m-2">
                  <label for="lastNightUpd" class="form-control-label mb-0">Last night</label>
                  <input id="lastNightUpd" name="lastNight" class="form-control" readonly>
              </div> 

              <div class="form-group col-1 m-2" >
                  <label for="nightsUpd" class="form-control-label mb-0">Nights</label>
                  <input id="nightsUpd" name="nights" type="text" class="form-control-plaintext text-center" readonly>
              </div>
            
              <div class="form-group col-3 m-2" style="width: 100px">
                  <label for="RentalUpd" class="form-control-label mb-0">Rent</label>
                  <input id="RentalUpd" name="Rental" type="text" class="form-control" value="0" readonly>
              </div>

              <div class="form-group m-2" style="width: 150px">
                <label for="BookingStatusUpd" class="form-control-label mb-0">Status</label>
                <select id="BookingStatusUpd" name="BookingStatusUpd" class="custom-select">
                    <!-- TODO load status options from DB table StatusCodes where category='booking' -->
                    <option value="P">Provisional</option>
                    <option value="C">Confirmed</option>
                </select>
              </div>

              <div class="form-group  col-12 m-2">
                <label for="notesUpd" id="notesUpdLabel" class="form-control-label mb-0">Notes</label>
                <textarea id="notesUpd" name="notesUpd" class="form-control rounded-0" type="textarea" rows="2" maxlength="100" placeholder="Name of guest,  contact no., email etc." ></textarea><span class="ml-1 mb-3"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="bookingUpdSave" type="button" class="btn btn-primary">Save changes</button>
          <button id="modalCloseButton" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>


</div> <!-- end of div class="container" -->


<!-- include the bootstrap, jquery and date libraries -->
<?php include '../include/MGF_libs.html'; ?>

<!-- moment date formatting library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

<!-- currency formatting -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.1.0/autoNumeric.min.js"></script>

<!-- confirmation popup for delete button library -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-confirmation2@4.0.4/dist/bootstrap-confirmation.min.js" integrity="sha256-HLaBCKTIBg6tnkp3ORya7b3Ttkf7/TXAuL/BdzahrO0=" crossorigin="anonymous"></script>

<!-- clientJS class used to get fingerprint & userAgentString -->
<script src="../js/client.min.js"></script>

<!-- my classes -->
<script src="../js/classes.js"></script>

<!-- this page javascipt -->
<script src="../js/bookingMaint.js"></script>

</body>
</html>
