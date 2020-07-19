<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />

  <title>MGF booking</title>
  <?php include '../include/MGF_header.html'; ?>        <!-- favicon + bootstrap & fork awesome style sheets -->

  <link rel="stylesheet" href="../css/bookingView.css" />  <!-- Page style sheet -->
  
  <?php 
    // include ('../include/MGF_header.html'); # favicon.ico + bootstrap.css & fork awesome.css style sheets
    include ('../include/dbFuncs.php');     # open db connection and instantiate dbFuncs class
  ?>  

</head>

<body onload="showCottageBook()">

<main class="container-fluid">

    <!-- debug info div for validation and error messages -->
    <section id="info" class="text-white bg-success d-none">INFO - 
      <?php
        echo $dbFuncs->getHostAndDb();
      ?>
    </section>

  <h4 id="title">MGF Booking Calendar</h4><span></span>

  <!-- form to allow user to select the wc Sat date and the cottage number -->
  <form  id="formGet" class="d-inline">
    <div class="form-row">
      <div class="form-group col-6">
        <label for="cottageNum">Cottage</label>
        <select id="cottageNum" name="cottageNum" class="custom-select" style="width:150px">
          <option value="0" selected disabled hidden>Select cottage</option>
          <option value="1">Cornflower</option>
          <option value="2">Cowslip</option>
          <option value="3">Meadowsweet</option>
        </select>
      </div>
      <div class="form-group col-3">
        <label for="key">Key:</label>
        <div id="key" class="confirmed">Confirmed</div>
        <div class="provisional">Provisional</div>
      </div>
    </div>
  </form>

  <p class="d-sm-none"><small>Scroll right to view details</small></p>

  <!-- calendar for the cottage number -->
  <div class="table-responsive">
    <table id="tblBookings" class="table table-bordered w-auto">
      <caption class="d-sm-none"><small>Scroll right to view details</small></caption>
      <thead class="thead-light">
        <tr>
          <!-- <th style="text-align:center">Saturday</th> -->
          <th style="color:blue">Sat</th>
          <th style="color:blue">Sun</th>
          <th style="color:blue">Mon</th>
          <th style="color:blue">Tue</th>
          <th style="color:blue">Wed</th>
          <th style="color:blue">Thu</th>
          <th style="color:blue">Fri</th>
          <th data-toggle='tooltip' data-placement='auto' title='View details of the weeks bookings'>View</th>
          <th style="text-align:center" data-toggle='tooltip' data-placement='auto' title='Are Short Breaks allowed?'>short bks</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
  
  <!-- error & warning messages -->
  <div id="output1" class="text-white bg-dark"></div> 

  <!-- div for in progress gif during ajax calls -->
  <div class="ajaxLoading"></div>

</main>

  <!-- include the bootstrap, jquery and date libraries -->
  <?php include '../include/MGF_libs.html'; ?>

  <!-- clientJS class used to get fingerprint & userAgentString -->
  <script src="../js/client.min.js"></script>

  <!-- my classes -->
  <script src="../js/classes.js"></script>

  <!-- date formatting -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.min.js"></script>

  <!-- page javascript -->
  <script src="../js/bookingView.js"></script>

  <script type="application/javascript">
      // save today's date minus 7 days (so that the current week is always shown) into the global const momDateSat
      var momDateSat = new Date('<?php echo date('Y-m-d', strtotime('-7 days')) ?>');
  </script>
</body>
</html>
