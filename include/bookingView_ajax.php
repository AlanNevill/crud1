<?php
// interface between testHarness.php and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

// get the calendar for the cottage after the given date
if ($_POST['method']==='cottageWeek_selectAll') {

    $return = array('success'=>true, 'cottageWeekRows'=>null);

    $return['cottageWeekRows'] = $dbFuncs->cottageWeek_selectAll($_POST['dateSat'], $_POST['cottageNum']);

    // if the return is not an array then it's an error message
    if ( !is_array($return['cottageWeekRows']) ) {
        $return['success'] = false;
    } 

    echo json_encode($return);
    exit();
}


// get all the bookings for the cottage after the given date
if ($_POST['method']==='cottageBook_selectAll') {

    $return = array('success'=>true, 'cottageBookRows'=>null);

    $return['cottageBookRows'] = $dbFuncs->cottageBook_selectAll($_POST['dateSat'], $_POST['cottageNum']);

    // if the return is not an array then it's an error message
    if ( !is_array($return['cottageBookRows']) ) {
        $return['success'] = false;
    } 

    echo json_encode($return);
    exit();
}



?>