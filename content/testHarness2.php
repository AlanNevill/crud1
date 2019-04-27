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

<div class="container">
<main>
<nav class="navbar navbar-expand-md navbar-light bg-light fixed-top font-weight-bold" style="padding-top: 5px;">

  <button class="navbar-toggler" data-target="#my-nav" data-toggle="collapse">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand myNavBarBrand" href="home.php"></a>

  <div id="my-nav" class="collapse navbar-collapse">
    <ul class="navbar-nav mr-auto">

      <li class="nav-item">
          <a id="Meadowsweet" class="nav-link" href="meadowsweet.php">Meadowsweet Cottage</a>
      </li>

      <li class="nav-item">
          <a id="Cowslip" class="nav-link" href="cowslip.php">Cowslip Cottage</a> 
      </li>

      <li class="nav-item">
          <a id="Cornflower" class="nav-link" href="cornflower.php">Cornflower Cottage</a>
      </li>

      <li class="nav-item dropdown">
          <a id="Facilities" class="nav-link dropdown-toggle" data-toggle="dropdown" id="Preview" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              Facilities
          </a>
          <div class="dropdown-menu" aria-labelledby="Preview">
              <a class="dropdown-item" href="facilitiesSummary.php">Summary</a>
              <div class="dropdown-divider"></div>
              <a id="Beachhut"    class="dropdown-item" href="facilitiesBeachHut.php">The Beach Hut</a>
              <a id="Play_area"   class="dropdown-item" href="facilitiesPlayArea.php">Childrens' play area</a>
              <a id="Babies"      class="dropdown-item" href="facilitiesBabies.php">Babies &amp; Toddlers</a>
              <a id="Our_Animals" class="dropdown-item" href="facilitiesAnimals.php">Our animals</a>
              <a id="Lakes_and_woods" class="dropdown-item" href="facilitiesLakesWoods.php">The lakes and trails</a>
          </div>
      </li>

      <li class="nav-item">
          <a id="Booking" class="nav-link" href="newBooking.php">Booking &amp; prices</a>
      </li>

      <li class="nav-item">
          <a id="Enquiry" class="nav-link" href="enquiry.php">Make Enquiry</a>
      </li>

      <li class="nav-item">
        <a id="Attractions" class="nav-link" href="attractions.php">Things to do</a>
      </li>

      <li class="nav-item">
          <a id="Guest_Book" class="nav-link" href="guestBook.php">Guest book</a>
      </li>

      <li class="nav-item">
        <a id="Contact" class="nav-link" href="contact.php">Find us</a>
      </li>

    </ul>
  </div>
</nav>

  <div class="list-group">
    <a class="list-group-item" href="#"><i class="fa fa-home fa-fw" aria-hidden="true"></i>&nbsp; Home</a>
    <a class="list-group-item" href="#"><i class="fa fa-book fa-fw" aria-hidden="true"></i>&nbsp; Library</a>
    <a class="list-group-item" href="#"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i>&nbsp; Applications</a>
    <a class="list-group-item" href="#"><i class="fa fa-cog fa-fw" aria-hidden="true"></i>&nbsp; Settings</a>
  </div>

  <div>
    <button type="button" class="btn btn-sm"><i class="fa fa-pencil-square-o">edit</i></button>
  </div>

  <!-- debug info div for validation and error messages -->
  <div id="info" class="">info</div>

  <!-- debug info div for validation and error messages -->
  <div id="output1" class="">output1</div>

  <div class="d-flex justify-content-between">
      <div>Year:
        <select id="theYear" name="theYear" class="custom-select"></select>
      </div>
      <div class="booked">fully booked</div> 
      <div class="shortbreaks">some short breaks</div >
      <div>Click to view booking conditions</div>
  </div>

  </main>
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

    <!-- this page javascipt -->
    <!-- <script src="../js/testHarness2.js"></script> -->

    <script>
    </script>

</body>
</html>
