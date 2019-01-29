<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

    <title>Booking</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <!-- Place your stylesheet here-->
    <link rel="stylesheet" href="css/crud1.css" />
    <link rel="stylesheet" href="css/newBooking.css" />

</head>

<body>
  <!-- load the MGF menu -->
  <?php include 'include/MGF_menu.html';  ?>

  
  <div class="d-flex justify-content-between align-items-center header-area">
      <div>Select year
        <select id="theYear" name="theYear" class="custom-select"></select>
      </div>
      <div class="card">
        <div class="card-text booked align-middle"><strong>&nbsp&nbspFully booked&nbsp&nbsp</strong></div> 
        <div class="card-text shortbreaks"><strong>&nbsp&nbspShort breaks&nbsp&nbsp</strong></div >
        </div>
      <div>
        <a href="/include/Booking conditions 2015-05-05.pdf"><small class="text-muted">Click to view booking conditions</small>
        </a>
    </div>
  </div>


  <main role="main" class="container">

    <section class="content-area">
      <div class="table-area">

        <!-- weekly calendar for the cottages; wc date in rows and cottages in columns -->
        <table id="tblBookings" class="table table-fixed table-light table-bordered">
            <!-- <caption class="d-sm-none"><small>Scroll right to view details</small></caption> -->
            <thead class="thead-light">
                <tr>
                    <th style="width:10%">wc.</th>
                    <th style="width:10%" data-toggle='tooltip' data-placement='auto' title='Are Short Breaks allowed?'>SB</th>
                    <th style="width:20%">Cornflower</th>
                    <th style="width:20%">Cowslip</th>
                    <th style="width:20%">Meadowsweet</th>
                    <th style="width:20%" data-toggle='tooltip' data-placement='auto' title='View details of short break availablilty'>View short breaks</th>
                </tr>
            </thead>
            <tbody id="tbodyBookings">
            </tbody>
        </table>

      </div>  <!-- end of class="table-area" -->
    </section>  <!-- end of class="content-area" -->

    <?php 
      include  'include/MGF_footer.html';
    ?>
    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main><!-- /.container -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button id="modalCloseX" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
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
            <p><small>Minimum 2 nights</small></p>
            <button id="modalCloseButton" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
          </div>
        </div>
      </div>
    </div>


  <!-- inlcude the bootstrap, jquery and date libraries  -->
  <?php include  'include/MGF_libs.html'; ?>
 
  <!-- custom javascript for this page -->
  <script type="application/javascript">
      // save todays date into global const TODAY
      const TODAY = new Date('<?php echo date("Y-m-d") ?>');
  </script>

  <!-- page javascipt -->
  <script src="js/newBooking.js"></script>

</body>
</html>