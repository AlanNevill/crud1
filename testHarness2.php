<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>MGF booking</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/Sheep-icon.jpg">

    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="css/crud1.css" />

</head>

<body>

<?php
    // session_start(); //start the PHP_session function

    // if(isset($_SESSION['page_count']))
    // {
    //     $_SESSION['page_count'] += 1;
    // }
    // else
    // {
    //     $_SESSION['page_count'] = 1;
    // }

    // $messages = 'You are visitor number ' . $_SESSION['page_count'] . '\n';

    // setcookie("user_name", "Guru99", time()+ 60,'/'); // expires after 60 seconds
    // print_r($_COOKIE);    //output the contents of the cookie array variable 

    // $messages .= 'the cookie has been set for 60 seconds\n';
    // echo nl2br($messages);

?>

<div class="container">

    <h4 id="title">testHarness2.php</h4><span></span>

    <!-- debug info div for validation and error messages -->
    <div id="info" class="">info</div>

    <!-- debug info div for validation and error messages -->
    <div id="output1" class="">output1</div>


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

    <!-- <script src="js/autoNumeric.js"></script> -->

    <!-- class functions -->
    <script src="js/classes.js"></script>

    <!-- this page javascipt -->
    <script src="js/testHarness2.js"></script>

    <script>
    </script>

</body>
</html>
