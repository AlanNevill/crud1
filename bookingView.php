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
    <link rel="stylesheet" href="css/bookingView.css" />

    <style>
        
    </style>
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
    // echo 'You are visitor number ' . $_SESSION['page_count'];

    // setcookie("user_name", "Guru99", time()+ 60,'/'); // expires after 60 seconds
    // print_r($_COOKIE);    //output the contents of the cookie array variable 
    // echo 'the cookie has been set for 60 seconds';

    include('include/dbFuncs.php');     // open db connection and instantiate dbFuncs class

    $data = array();
    // $options = '';                
    $dateSat = date("Y-m-d"); // default today's date

    // select distinct DateSat rows from dateSat table
    // $data = $dbFuncs->dateSat_select($dateSat);

    // warning if no rows returned
    // if(count($data)==0) {
    //     $dbFuncs->ProcessLog_insert('E', 'MGF2','dbFuncs.dateSat_select', 'me', 'No weeks found', $dateSat);
    //     die('<h1>No weeks found</h1>');
    // }

    // // enumerate the rows returned into HTML select options for date Sat select
    // foreach($data as $row)
    // {
    //     $options    .= '<option value=' . $row["DateSat"] 
    //                 . '>'
    //                 . date("d M Y", strtotime($row["DateSat"])) 
    //                 . '</option>';
    // }
?>

<div class="container">

    <!-- debug info div for validation and error messages -->
    <div id="info" class="d-none">info</div>

    <h4 id="title">MGF Booking Calendar</h4><span></span>

    <!-- form to allow user to select the wc Sat date and the cottage number -->
    <form  id="formGet" >
      <!-- <label for="wcDateSat">W/c Sat. date</label>
      <select id="wcDateSat"  name="wcDateSat" class="custom-select" style="width:150px">
          <option value="-1" selected disabled hidden>Select Saturday</option>
          <?php echo $options ?>
      </select> -->
      <!-- <div class="form-row"> -->
      <div>
        <label for="cottageNum">Cottage </label>
        <select id="cottageNum" name="cottageNum" class="custom-select" style="width:150px">
            <option value="-1" selected disabled hidden>Select cottage</option>
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
        <table id="tblBookings" class="table table-light table-bordered">
            <caption class="d-sm-none"><small>Scroll right to view details</small></caption>
            <thead class="thead-light">
                <tr>
                    <th>wc.</th>
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
            <tbody id="tbodyBookings">
            </tbody>
        </table>
    </div>
    
    <!-- error & warning messages -->
    <div id="output1" class="text-white bg-dark"></div> 

    <!-- div for in progress gif during ajax calls -->
    <div class="modal"></div>

</div>


    <!-- <script src="js/jquery.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <!-- jquery.form library NB. not compatible with jquery.min.slim.js-->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script> -->

    <!-- Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"     crossorigin="anonymous">
    </script>

    <!-- Latest compiled and minified Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"     crossorigin="anonymous">
    </script>

    <!-- Latest compiled and minified mustache.js templating JavaScript -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/oj.mustache/0.7.2/oj.mustache.js" ></script> -->

    <!-- moment date formatting library -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script> -->

    <!-- currency formatting
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.1.0/autoNumeric.min.js"></script> -->

    <!-- date formatting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.min.js"></script>

    <!-- my classes -->
    <script src="js/classes.js"></script>

    <!-- page javascipt -->
    <script src="js/bookingView.js"></script>

    <script type="application/javascript">
        // save todays date into global var momDateSat
        var momDateSat = new Date('<?php echo $dateSat ?>');
    </script>
</body>
</html>
