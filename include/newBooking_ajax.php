<?php
// interface between newBooking.php and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

// get the calendar for all the cottages for the given year
if ($_POST['method']==='newBooking_crosstab') {

    $return = $dbFuncs->newBooking_crosstab($_POST['startDate'], $_POST['yearEnd']);

    echo json_encode($return);
    exit();
}


// get all the bookings for all the cottages for the given year
if ($_POST['method']==='cottageBook_selectAll') {

    $return = array('success'=>true, 'cottagesBookRows'=>null);

    $return['cottageBookRows'] = $dbFuncs->cottageBook_selectAll($_POST['theYear']);

    // if the return is not an array then it's an error message
    if ( !is_array($return['cottagesBookRows']) ) {
        $return['success'] = false;
    } 

    echo json_encode($return);
    exit();
}



?>