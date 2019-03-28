<?php
// interface between testHarness.php and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

// get the calendar weeks for the cottage after the given date
if ($_POST['method']==='cottageWeek_selectAll') {

  if (empty($_POST['cottageNum'])) {
    $_POST['cottageNum'] = 0;
  }

  $returnArray = $dbFuncs->cottageWeek_selectAll($_POST['dateSat'], $_POST['cottageNum']);

  echo json_encode($returnArray);
  exit();
}


// get all the bookings for the cottage after the given date
if ($_POST['method']==='cottageBook_selectAll') {
  
  if (empty($_POST['cottageNum'])) {
    $_POST['cottageNum'] = 0;
  }
  
  $returnArray = $dbFuncs->cottageBook_selectAll($_POST['dateSat'], $_POST['cottageNum']);

  echo json_encode($returnArray);
  exit();
}



?>