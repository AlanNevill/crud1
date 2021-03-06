<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Alan Nevill">

    <title>Booking</title>

    <?php include '../include/MGF_header.html'; ?>          <!-- favicon + bootstrap & fork awesome style sheets -->

    <link rel="stylesheet" href="../css/MGF.css" />         <!-- Site stylesheet -->
    <link rel="stylesheet" href="../css/newBooking.css" />  <!-- Page stylesheet -->

</head>

<body>
  <!-- load the MGF menu -->
  <?php include '../include/MGF_menu.html';  ?>
  
  <main role="main" class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mt-3">

      <div id="yearBtns" class="btn-group btn-group-toggle" data-toggle="buttons" role="group" aria-label="Select the year buttons">
        <label for="yearBtns" class="h3 mt-3 mr-3">Select year:</label>

        <label for="btnthisYear" class="btn btn-outline-success btn-lg active mr-3" style="border-radius: 20%;font-weight:bold; font-size: 1.6rem;">
          <input type="radio" id="btnthisYear" name="years" autocomplete="off" checked>
          <span id="spnthisYear"></span>
        </label>
        <label for="btnnextYear" class="btn btn-outline-success btn-lg" style="border-radius: 20%;font-weight:bold; font-size: 1.6rem;">
          <input type="radio" id="btnnextYear" name="years" autocomplete="off" >
          <span id="spnnextYear"></span>
        </label>
      </div>

      <table class="table-bordered">
        <tr>
          <td rowspan=2 style="vertical-align:middle" class="h4 font-weight-bold">Key&nbsp;</td>
          <td class="booked px-3 font-weight-bold">Fully booked</td>
        </tr>
        <tr>
          <td class="shortbreaks px-3 font-weight-bold">Short breaks</td>
        </tr>
      </table>

      <a href="/include/Booking conditions 2015-05-05.pdf"><small class="d-print-none font-weight-bold">Click to view booking conditions</small></a>
    </div>


    <section>
      <div class="table-responsive">
        
        <!-- weekly calendar for the cottages; wc date in rows and cottages in columns -->
        <table id="tblBookings" class="table vertical-align w-auto">
            <!-- <caption class="d-sm-none"><small>Scroll right to view details</small></caption> -->
            <thead class="table-success">
              <tr class="mb-5 mb-md-0">
                <th>wc Sat</th>
                <th data-toggle='tooltip' data-placement='auto' title='Links to view short break availability for selected week'>View short breaks</th>
                <th class="d-none d-sm-block" >Cornflower</th>
                <th class="d-sm-none" >Corn flower</th>

                <th>Cowslip</th>
                
                <th class="d-none d-sm-block" >Meadowsweet</th>
                <th class="d-sm-none" >Meadow sweet</th>
              </tr>
            </thead>
            <tbody id="tbodyBookings"></tbody>
        </table>
        
      </div>

    </section>


    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main><!-- /.container -->

    <?php include  '../include/MGF_footer.html';  ?>

    <!-- Modal form for short break prices & availability-->
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
                    <!-- <th>£ per night</th> day prices removed 2019-09-29 -->
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
            <div class="mr-auto">Minimum 3 nights - Contact us for prices</div>
            <button id="modalCloseButton" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

  <!-- date formatting -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.min.js"></script>

  <!-- include the bootstrap, jquery and popper libraries -->
  <?php include  '../include/MGF_libs.html'; ?>

  <!-- site javascript -->
  <script src="../js/crud1.js"></script>

  <!-- page javascript -->
  <script src="../js/newBooking.js"></script>

</body>
</html>