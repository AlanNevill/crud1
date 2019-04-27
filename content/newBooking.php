<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Booking</title>

    <?php include '../include/MGF_header.html'; ?>          <!-- favicon + bootstrap & fork awesome style sheets -->

    <link rel="stylesheet" href="../css/MGF.css" />       <!-- Site stylesheet -->
    <link rel="stylesheet" href="../css/newBooking.css" />  <!-- Page stylesheet -->

</head>

<body>
  <!-- load the MGF menu -->
  <?php include '../include/MGF_menu.html';  ?>
  
  <main role="main" class="container">
    <div class="d-flex justify-content-between align-items-center header-area">
      <div class="ml-3">
        <label for="theYear" class="mt-3 mb-0">Select year</label>
        <select id="theYear" name="theYear" class="custom-select"></select>
      </div>
      <div class="card mt-5 mb-1 ml-3 mr-3 text-center">
        <div class="card-text booked px-3">Fully booked</div> 
        <div class="card-text shortbreaks px-3">Short breaks</div >
        </div>
      <div>
        <a href="/include/Booking conditions 2015-05-05.pdf"><small class="text-muted mr-3">Click to view booking conditions</small>
        </a>
      </div>
    </div>

    <div class="table-area ">
      <!-- weekly calendar for the cottages; wc date in rows and cottages in columns -->
      <table id="tblBookings" class="table table-striped table-fixed">
          <!-- <caption class="d-sm-none"><small>Scroll right to view details</small></caption> -->
          <thead class="table-success">
            <tr>
              <th style="width:10%" >wc Sat</th>
              <th style="width:10%" data-toggle='tooltip' data-placement='auto' title='Are Short Breaks allowed in specific weeks?'>SB</th>
              <th style="width:20%">Cornflower</th>
              <th style="width:20%">Cowslip</th>
              <th style="width:20%">Meadowsweet</th>
              <th style="width:20%" data-toggle='tooltip' data-placement='auto' title='Links to view short break availablilty for selected week'>View short breaks</th>
            </tr>
          </thead>
          <tbody id="tbodyBookings">
          </tbody>
      </table>

    </div>  <!-- end of class="table-area" -->

    <?php include  '../include/MGF_footer.html';  ?>

    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main><!-- /.container -->

    <!-- Modal form for short break prices & availablity-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button id="modalCloseX" type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close fa-lg"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table id="tblBookings" class="table table-light table-bordered">
                <caption class="d-sm-none"><small>Scroll right to view</small></caption>
                <thead class="thead-light">
                  <tr>
                    <th>Cottage</th>
                    <th>£ per night</th>
                    <th>Sa</th>
                    <th>Su</th>
                    <th>Mo</th>
                    <th>Tu</th>
                    <th>We</th>
                    <th>Th</th>
                    <th>Fr</th>
                  </tr>
                </thead>
                <tbody id="tbodyModal"></tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <div style="width: 300px;">Minimum 2 nights</div>
            <button id="modalCloseButton" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>


  <!-- inlcude the bootstrap, jquery and date libraries -->
  <?php include  '../include/MGF_libs.html'; ?>
 
  <!-- custom javascript for this page -->
  <script type="application/javascript">
      // save todays date minus 7 days (so that the current week is always shown) into the global const TODAY
      const TODAY = new Date('<?php echo date('Y-m-d', strtotime('-7 days')) ?>');
  </script>

  <!-- site javascipt -->
  <script src="../js/crud1.js"></script>

  <!-- page javascipt -->
  <script src="../js/newBooking.js"></script>

</body>
</html>