<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />

  <title>MGF booking</title>
  <!-- Favicon -->
  
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

  <link rel="stylesheet" href="../css/MGF.css" />

</head>

<body>

  <?php

    include ('../include/dbFuncs.php');     // open db connection and instantiate dbFuncs class
   //include 'include/MGF_menu.html';
    // require_once 'vendor/autoload.php';

    // // Create the Transport
    // $transport = (new Swift_SmtpTransport('mail.meadowgreenfarm.co.uk', 465, 'ssl'))
    //   ->setUsername('alan@meadowgreenfarm.co.uk')
    //   ->setPassword('uY^4#bWX')
    // ;

    // // Create the Mailer using your created Transport
    // $mailer = new Swift_Mailer($transport);

    // // Create a message
    // $message = (new Swift_Message('Test Subject 03 with ssl'))
    //   ->setFrom(['alan@meadowgreenfarm.co.uk' => 'Alan@MGF'])
    //   ->setTo(['alannevill@gmail.com', 'a1@lansdowne-place.myzen.co.uk' => 'a1 Zen'])
    //   ->setBody('Here is the message itself 03 - with ssl port 465')
    //   ;

    // ini_set('max_execution_time', 60);

    // // Send the message
    // $result = $mailer->send($message);

  ?>

  <main role="main" class="container">

    <!-- debug info div for validation and error messages -->
    <section id="info" class="text-white bg-success mb-4">info - 
      <?php
        echo $dbFuncs->getHostAndDb();
      ?>
    </section>


    <div class="d-flex justify-content-between align-items-center mt-3">

      <div id="yearBtns" class="btn-group btn-group-toggle" data-toggle="buttons" role="group" aria-label="Select the year buttons">
        <label for="yearBtns" class="h3 mt-3 mr-3">Select year:</label>

        <label class="btn btn-outline-success btn-lg active mr-3" style="border-radius: 20%;font-weight:bold; font-size: 1.6rem;">
          <input type="radio" name="years" id="btnthisYear" autocomplete="off" checked 
            value="<?php echo date('Y', strtotime('-7 days')) ?>">
            <?php echo date('Y', strtotime('-7 days')) ?>
        </label>
        <label class="btn btn-outline-success btn-lg" style="border-radius: 20%;font-weight:bold; font-size: 1.6rem;">
          <input type="radio" name="years" id="btnnextYear" autocomplete="off" 
            value="<?php echo date('Y', strtotime('358 days')) ?>" >
            <?php echo date('Y', strtotime('358 days')) ?>
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
      <!-- <div  class="card mt-5 mb-1 ml-3 mr-3 text-center d-print-none">
        <div class="card-text h5">Key</div>
        <div class="card-text booked px-3">Fully booked</div> 
        <div class="card-text shortbreaks px-3">Short breaks</div >
      </div>
      <div> -->
      <a href="/include/Booking conditions 2015-05-05.pdf"><small class="d-print-none font-weight-bold">Click to view booking conditions</small></a>
      <!-- </div> -->
    </div>


    <section>
      
      <!-- weekly calendar for the cottages; wc date in rows and cottages in columns -->
      <table id="tblBookings" class="table table-fixed table-responsive-sm vertical-align w-auto">
          <!-- <caption class="d-sm-none"><small>Scroll right to view details</small></caption> -->
          <thead class="table-success">
            <tr class="mb-5 mb-md-0">
              <th style="width:10%" >wc Sat</th>
              <!-- <th style="width:10%" data-toggle='tooltip' data-placement='auto' title='Are Short Breaks allowed in specific weeks?'>SB</!--> 
              <th style="width:22%" data-toggle='tooltip' data-placement='auto' title='Links to view short break availability for selected week'>View short breaks</th>
              <th class="d-none d-sm-block" style="width:22%">Cornflower</th>
              <th class="d-sm-none" style="width:22%">Corn flower</th>

              <th style="width:22%">Cowslip</th>
              
              <th class="d-none d-sm-block" style="width:22%">Meadowsweet</th>
              <th class="d-sm-none" style="width:22%">Meadow sweet</th>
            </tr>
          </thead>
          <tbody>
            <tr class="table-info">
              <th scope="row">1</th>
              <td>Kate</td>
              <td>Moss</td>
              <td>USA</td>
              <td>New York City</td>
              <td>23</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Anna</td>
              <td>Wintour</td>
              <td>United Kingdom</td>
              <td>London</td>
              <td>36</td>
            </tr>
            <tr class="table-info">
              <th scope="row">3</th>
              <td>Tom</td>
              <td>Bond</td>
              <td>Spain</td>
              <td>Madrid</td>
              <td>25</td>
            </tr>
            <tr>
              <th scope="row">4</th>
              <td>Jerry</td>
              <td>Horwitz</td>
              <td>Italy</td>
              <td>Bari</td>
              <td>41</td>
            </tr>
            <tr class="table-info">
              <th scope="row">5</th>
              <td>Janis</td>
              <td>Joplin</td>
              <td>Poland</td>
              <td>Warsaw</td>
              <td>39</td>
            </tr>
            <tr>
              <th scope="row">6</th>
              <td>Gary</td>
              <td>Winogrand</td>
              <td>Germany</td>
              <td>Berlin</td>
              <td>37</td>
            </tr>
            <tr class="table-info">
              <th scope="row">7</th>
              <td>Angie</td>
              <td>Smith</td>
              <td>USA</td>
              <td>San Francisco</td>
              <td>52</td>
            </tr>
            <tr>
              <th scope="row">8</th>
              <td>John</td>
              <td>Mattis</td>
              <td>France</td>
              <td>Paris</td>
              <td>28</td>
            </tr>
            <tr class="table-info">
              <th scope="row">9</th>
              <td>Otto</td>
              <td>Morris</td>
              <td>Germany</td>
              <td>Munich</td>
              <td>35</td>
            </tr>
          </tbody>
        </table>
    </section>


    <!-- div for in progress gif during ajax calls -->
    <div class="ajaxLoading"></div>

  </main><!-- /.container -->

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/oj.mustache/0.7.2/oj.mustache.js" ></script>

  <!-- moment date formatting library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

  <!-- currency formatting -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.1.0/autoNumeric.min.js"></script>

  <script src="../js/client.min.js"></script>

  <!-- class functions -->
  <script src="../js/classes.js"></script>

  <!-- <script type="module" src="/js/commonFuncs.js"></script> -->

  <!-- this page javascript -->
  <!-- <script src="../js/testHarness2.js"></script> -->

  <script>
  </script>

</body>
</html>
