<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />

  <title>MGF booking</title>
  <?php include '../include/MGF_header.html'; ?>        <!-- favicon + bootstrap & fork awesome style sheets -->

  <link rel="stylesheet" href="../css/crud1.css" />        <!-- Project style sheet -->
  <link rel="stylesheet" href="../css/bookingView.css" />  <!-- Page style sheet -->
</head>

<body onload="showCottageBook()">

<div class="container">

  <!-- debug info div for validation and error messages -->
  <div id="info" class="d-none">info</div>

  <h4 id="title">MGF Booking Calendar</h4><span></span>

  <!-- form to allow user to select the wc Sat date and the cottage number -->
  <form  id="formGet" >
    <!-- <label for="wcDateSat">W/c Sat. date</label>
    <select id="wcDateSat"  name="wcDateSat" class="custom-select" style="width:150px">
        <option value="-1" selected disabled hidden>Select Saturday</option>
    </select> -->
    <!-- <div class="form-row"> -->
    <div>
      <label for="cottageNum">Cottage</label>
      <select id="cottageNum" name="cottageNum" class="custom-select" style="width:150px">
        <option value="0" selected disabled hidden>Select cottage</option>
        <option value="1">Cornflower</option>
        <option value="2">Cowslip</option>
        <option value="3">Meadowsweet</option>
      </select>
    </div>
    <div class="provisional float-right" 
      style="padding-right: 10px;padding-left: 10px;margin-bottom: 10px;">rovisional</div>
    <div class="confirmed float-right" style="padding-right: 10px;padding-left: 10px;margin-bottom: 10px;">onfirmed</div>
    <!-- </div> -->
  </form>

  <p class="d-sm-none"><small>Scroll right to view details</small></p>

  <!-- calendar for the cottage number -->
  <div class="table-responsive">
    <table id="tblBookings" class="table table-light table-striped">
      <caption class="d-sm-none"><small>Scroll right to view details</small></caption>
      <thead class="thead-light">
        <tr>
          <th>wc Sat</th>
          <th data-toggle='tooltip' data-placement='auto' title='Are Short Breaks allowed?'>SB</th>
          <th>Sa</th>
          <th>Su</th>
          <th>Mo</th>
          <th>Tu</th>
          <th>We</th>
          <th>Th</th>
          <th>Fr</th>
          <th data-toggle='tooltip' data-placement='auto' title='View details of the weeks bookings'>View</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
  
  <!-- error & warning messages -->
  <div id="output1" class="text-white bg-dark"></div> 

  <!-- div for in progress gif during ajax calls -->
  <div class="ajaxLoading"></div>

</div>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include '../include/MGF_libs.html'; ?>

  <!-- clientJS class used to get fingerprint & userAgentString -->
  <script src="../js/client.min.js"></script>

  <!-- my classes -->
  <script src="../js/classes.js"></script>

  <!-- page javascipt -->
  <script src="../js/bookingView.js"></script>

  <script type="application/javascript">
      // save todays date minus 7 days (so that the current week is always shown) into the global const momDateSat
      var momDateSat = new Date('<?php echo date('Y-m-d', strtotime('-7 days')) ?>');
  </script>
</body>
</html>
