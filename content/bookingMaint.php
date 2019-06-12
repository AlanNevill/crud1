<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />

  <title>MGF booking</title>

  <?php include '../include/MGF_header.html'; ?>        <!-- favicon + bootstrap & fork awesome style sheets -->

  <link rel="stylesheet" href="../css/bookingMaint.css" />

</head>

<body>

  <?php
    // get the future Saturday dates from CottageWeek to populate the dropdown select w/c Sat. date

    include('../include/dbFuncs.php');     // open db connection and instantiate dbFuncs class

    $options = '';                
    $dateSat = date("Y-m-d", strtotime('-7 days')); // default today's date - 7 days

    // select distinct DateSat rows from CottageWeek table
    $data = $dbFuncs->dateSat_select($dateSat);

    // warning if no rows returned
    if(count($data)==0) {
      $dbFuncs->ProcessLog_insert2('E', 'bookingMaint.php', 'dbFuncs.dateSat_select', 'No weeks found: ', $dateSat);
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
        <select id="wcDateSat" name="wcDateSat" class="custom-select">
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

      <!-- <input id="submitButton" type="hidden" value="Get bookings"> -->
    </div>
  </form>

  
  <!-- cottageWeek row data -->
  <section class="CottageWeek_data text-white bg-secondary">
    <div class="row mt-3 mb-3">
      <div id="Rent" class="col-6">&nbsp</div>
      <div id="bShortBreaksAllowed" class="col-6 text-left"></div>
      <!-- <div id="DayRent" class="col-3 text-right"></div> -->
    </div>
  </section>


  <!-- existingBookings holds the list of existing bookings for the wc Sat date and the cottage number -->
  <h5 class="mt-3">Existing bookings</h5>
  <div class="d-sm-none d-print-none">Scroll right to edit or delete</div>

  <table id="tblBookings" class="table table-bordered table-responsive table-fit">
    <caption class="d-sm-none d-print-none">Scroll right to edit or delete</caption>
    <thead class="thead-light">
      <tr>
        <th>Name</th>
        <th>Arrive date</th>
        <th>Last night</th>
        <th>Nights</th>
        <th>Rent</th>
        <th class="d-none d-md-table-cell">Our ref</th> <!-- hide booking ref on xs and sm screens -->
        <!-- <th>Notes</th> -->
        <th data-toggle='tooltip' data-placement='auto' title='A booking is (C)onfirmed or (P)rovisional'>Status</th>
        <th class="d-print-none" data-toggle='tooltip' data-placement='auto' title='Edit or delete the booking'>Edit Del.</th>
        <th class="d-none d-print-table-cell">Notes</th>

      </tr>
    </thead>
    <tbody id="tbodyBookings">
    </tbody>
  </table>

  <!-- error & warning messages -->
  <div id="output1" class="text-white bg-danger text-center"></div> 

  <!-- button to reveal / collapse the new booking form -->
  <button class="btn btn-success d-print-none" type="button" data-toggle="collapse" data-target="#collapseForm">
    <i class="fa fa-chevron-down" aria-hidden="true"></i>
    Toggle new booking form
    <i class="fa fa-chevron-up" aria-hidden="true"></i>
  </button>

  <div class="collapse" id="collapseForm">
    <!-- new booking form -->
    <form id="frmNewBooking" role="form" name="frmNewBooking" class="form insertForm form-horizontal p-2">

      <h5>New booking form</h5>
      <!-- FIXME: remove  3 hidden form fields -->
      <input type="hidden" id="method"  name="method" value="insert">
          <!-- method=insert for bookingMaint2 function insert -->
      <input type="hidden" id="dateSat" name="DateSat">         <!-- DateSat    for bookingMaint2 functions check & insert -->
      <input type="hidden" id="cottageNum" name="CottageNum">   <!-- CottageNum for bookingMaint2 functions check & insert -->
      <div class="form-row">
          <div class="form-group m-2">
            <label for="BookingName" class="form-control-label mb-0">Booking name</label>
            <input id="BookingName" name="BookingName" class="form-control" type="text" required>
          </div>
          <div class="form-group m-2">
            <label for="ContactEmail" class="form-control-label mb-0">Contact info</label>
            <input id="ContactEmail" name="ContactEmail" class="form-control" type="text" required placeholder="e.g. email and/or mobile">
          </div>
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

        <div class="form-group m-2" style="width: 80px">
            <label for="Rental" class="form-control-label mb-0">Rent</label>
            <input id="Rental" name="Rental" type="text" class="form-control" value="0" required>
        </div>

      </div>
      <div class="form-row">

        <div class="form-group m-2" style="width: 150px">
            <label for="BookingStatus" class="form-control-label mb-0">Booking status</label>
            <select id="BookingStatus" name="BookingStatus" class="custom-select">
                <!-- TODO load status options from DB table StatusCodes where category='bookingStatus' -->
                <option value="P" selected>Provisional</option>
                <option value="C">Confirmed</option>
            </select>
        </div>

        <div class="form-group m-2" style="width: 150px">
            <label for="BookingSource" class="form-control-label mb-0">Booking source</label>
            <select id="BookingSource" name="BookingSource" class="custom-select">
                <!-- TODO load bookingSource options from DB table StatusCodes where category='bookingSource' -->
                <option value='W'>Website</option>
                <option value='H'>HomeAway</option>
                <option value='T'>Telephone</option>
                <option value='E'>Email</option>
                <option value='O'>Other</option>
                <option value='Q'>Enquiry</option>
            </select>
        </div>

        <div class="form-group m-2">
            <label for="ExternalReference" class="form-control-label mb-0">External booking reference</label>
            <input id="ExternalReference" name="ExternalReference" class="form-control" type="text" placeholder="e.g. HomeAway ref.">
        </div>

        <div class="form-group m-2" style="width: 80px">
            <label for="NumAdults" class="form-control-label mb-0"># adults</label>
            <input id="NumAdults" name="NumAdults" class="form-control" type="number" value="1" min="1" max="6">
        </div>

        <div class="form-group m-2" style="width: 80px">
            <label for="NumChildren" class="form-control-label mb-0"># children</label>
            <input id="NumChildren" name="NumChildren" class="form-control" type="number" value="0" min="0" max="6">
        </div>

        <div class="form-group m-2" style="width: 80px">
            <label for="NumDogs" class="form-control-label mb-0"># dogs</label>
            <input id="NumDogs" name="NumDogs" class="form-control" type="number" value="0" min="0" max="4">
        </div>

        <div class="form-group m-2">
            <label for="Children" class="form-control-label mb-0">Children info</label>
            <input id="Children" name="Children" class="form-control" type="text" placeholder="e.g. names & ages">
        </div>

      </div>

      <label for="notes" id="notesLabel" class="form-control-label mb-0">Notes</label>
      <textarea id="notes" name="notes" class="form-control rounded-0" type="textarea" rows="1" maxlength="100" placeholder="e.g. special requirements" ></textarea><span class="ml-1 mb-3"></span>

      <div class="form-row">
        <div class="col-12 text-center mb-3">
            <input id="submitNewBooking" class="btn btn-lg btn-success pl-4 pr-4" type="button" value="Submit new booking">
        </div>
      </div>

    </form>

  </div>

  <!-- div for booking messages -->
  <div id="newBookingInfo" class="bg-success text-center m-4">&nbsp</div>

  <!-- div for in progress gif during ajax calls -->
  <div class="ajaxLoading"></div>

  <!-- Modal form for updating a booking -->
  <div class="modal fade" id="mybookingUpdForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updDialogTitle"></h5>
          <button id="modalCloseX" type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close fa-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <div id="innerbookingUpdFrm" class="md-form" >
            <div class="form-row">
              <input id="IdNum" type="hidden">
              <input id="BookingRef" type="hidden">

              <div>
                <label for="BookingName" class="form-control-label mb-0">Name</label>
                <input id="BookingName"  class="form-control" type="text">
              </div>

              <div class="form-group col-3 m-2">
                <label for="firstNightUpd" class="form-control-label mb-0">Arrival date</label>
                <input id="firstNightUpd"  class="form-control" readonly>
              </div>

              <div class="form-group col-3 m-2">
                  <label for="lastNightUpd" class="form-control-label mb-0">Last night</label>
                  <input id="lastNightUpd"  class="form-control" readonly>
              </div> 

              <div class="form-group col-1 m-2" >
                  <label for="NumNights" class="form-control-label mb-0">Nights</label>
                  <input id="NumNights"  type="text" class="form-control-plaintext text-center" readonly>
              </div>
            
              <div class="form-group col-3 m-2" style="width: 100px">
                  <label for="Rental" class="form-control-label mb-0 mr-3 float-right">Rent</label>
                  <input id="Rental"  type="text" class="form-control text-right" value="0" readonly>
              </div>

              <div class="form-group m-2" style="width: 150px">
                <label for="BookingStatus" class="form-control-label mb-0">Booking status</label>
                <select id="BookingStatus" class="custom-select">
                    <!-- TODO load status options from DB table StatusCodes where category='booking' -->
                    <option value="P">Provisional</option>
                    <option value="C">Confirmed</option>
                </select>
              </div>

              <div class="form-group m-2" style="width: 100px">
                <label for="BookingSource" class="form-control-label mb-0">Source</label>
                <select id="BookingSource" class="custom-select">
                    <!-- TODO load status options from DB table StatusCodes where category='bookingSource' -->
                    <option value='W'>Website</option>
                    <option value='H'>HomeAway</option>
                    <option value='T'>Telephone</option>
                    <option value='E'>Email</option>
                    <option value='O'>Other</option>
                    <option value='Q'>Enquiry</option>
                </select>
              </div>

              <div class="form-group m-2" style="width: 150px">
                  <label for="ExternalReference" class="form-control-label mb-0 mr-3">Ext. reference</label>
                  <input id="ExternalReference" type="text" class="form-control">
              </div>

              <div class="form-group m-2" style="width: 250px">
                  <label for="ContactEmail" class="form-control-label mb-0 mr-3">Contact email</label>
                  <input id="ContactEmail" type="text" class="form-control">
              </div>

              <div class="form-group m-2" style="width: 55px">
                <label for="NumAdults" class="form-control-label mb-0 mr-3">Adults</label>
                <input id="NumAdults" type="number" class="form-control">
              </div>

              <div class="form-group m-2" style="width: 55px">
                <label for="NumChildren" class="form-control-label mb-0 mr-3">Children</label>
                <input id="NumChildren" type="number" class="form-control">
              </div>

              <div class="form-group m-2" style="width: 55px">
                <label for="NumDogs" class="form-control-label mb-0 mr-3">Dogs</label>
                <input id="NumDogs" type="number" class="form-control">
              </div>
              
              <div class="form-group m-2">
                <label for="Children" class="form-control-label mb-0 mr-3">Children details</label>
                <input id="Children" type="text" class="form-control">
              </div>

              <div class="form-group col-12 m-2">
                <label for="Notes"   class="form-control-label mb-0">Notes</label>
                <textarea id="Notes" class="form-control rounded-0" type="textarea" rows="2" maxlength="100" placeholder="Name of guest,  contact no., email etc." ></textarea><span class="ml-1 mb-3"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="bookingUpdSave"   type="button" class="btn btn-primary">Save changes</button>
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
