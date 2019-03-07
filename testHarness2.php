<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0, shrink-to-fit=no" />

  <title>MGF booking</title>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

  <!-- Latest compiled and minified Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fork-awesome@1.1.5/css/fork-awesome.min.css" integrity="sha256-P64qV9gULPHiZTdrS1nM59toStkgjM0dsf5mK/UwBV4=" crossorigin="anonymous">

  <link rel="stylesheet" href="css/crud1.css" />

</head>

<body>

<?php
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

    <script src="js/client.min.js"></script>

    <!-- class functions -->
    <script src="js/classes.js"></script>

    <!-- <script type="module" src="/js/commonFuncs.js"></script> -->

    <!-- this page javascipt -->
    <script src="/js/testHarness2.js"></script>

    <script>
    </script>

</body>
</html>
