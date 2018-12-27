<?php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

// mustache templating in PHP
// require 'C:/Users/User/Downloads/Mustache/mustache.php-2.12.0/src/Mustache/autoloader.php';
// Mustache_Autoloader::register();
// $m = new Mustache_Engine;
// echo $m->render('Hello, {{planet}}!', array('planet' => 'World')); // "Hello, World!"

// get the bookings for the wc date and cottage num selected
if($_POST['method']==='CottageBook_select'){
  $errors = array(); // validation errors

  if($_POST['wcDateSat']==-1) { $errors[] = "Please select a wc Saturday date/n"; }
  
  if($_POST['cottageNum']==-1) { $errors[] = "Please select a cottage"; }
  
  if(!empty($errors)){
      $errHtml="<p><strong>";
      foreach ($errors as $msg) { $errHtml.= $msg; }
      $errHtml.="</strong></p>";
      die(nl2br($errHtml));
  }
  
  // rows from CottageBook for the given DateSat and CottageNum
  $returnArray = $dbFuncs->CottageBook_select($_POST['wcDateSat'], $_POST['cottageNum']);

  echo json_encode($returnArray);
  exit;
}   // end of method=CottageBook_select    


if($_POST['method']==='CottageBook_delete')
{
    // call the delete function in dbFunc
    $returnArray = $dbFuncs->CottageBook_delete($_POST['IdNum']);

    // check for error in the sql delete
    if ($returnArray['success']) {
        $returnArray['errorMess'] = 'Delete successful';
        $dbFuncs->ProcessLog_insert2('I', 'bookingMaint_ajax.php', 'method=CottageBook_delete', '', 'Deleted, IdNum: '. $_POST['IdNum']);
    } else {
        $returnArray['errorMess'] = 'Delete failed - see ErrorLog';
        $dbFuncs->ProcessLog_insert2('E', 'bookingMaint_ajax.php', 'method=CottageBook_delete', $returnArray['errorMess'], 'Deleted failed, IdNum: '. $_POST['IdNum']);
    }
  echo JSON_encode($returnArray);
  exit;
} // end of method=delete


// check for clashes 
// if($_POST['method']==='check')
// {
//     $DateSat = $_POST['dateSat'];
//     $CottageNum = $_POST['cottageNum']; 
//     $FirstNight = $_POST['firstNight']; 
//     $LastNight = $_POST['lastNight']; 

//     // check proposed dates for a new booking against existing bookings in the database and return count
//     $returnArray = $dbFuncs->CottageBook_check($DateSat, $CottageNum, $FirstNight, $LastNight);

//     echo JSON_encode($returnArray);
//     exit;
// } // end of method=check


// insert a new booking in CottageBook table if no clashes with existing bookings
if($_POST['method']==='insert')
{
    $DateSat        = $_POST['dateSat'];
    $CottageNum     = $_POST['cottageNum']; 
    $FirstNight     = $_POST['firstNight']; 
    $LastNight      = $_POST['lastNight']; 
    $Rental         = $_POST['Rental'];
    $Notes          = $_POST['notes'];
    $BookingStatus  = $_POST['BookingStatus'];

    // initialize the returnArray
    $returnArray = array('success'      =>true,
                        'clashCount'    =>0,
                        'bookingRef'    =>null,
                        'insertedIdNum' =>0,
                        'messSeverity'  =>'I',
                        'errorMess'     =>null
                    );

    // check $Rental is not greater than 9,999.99
    if ($Rental > 9999.99 ) {
        $returnArray['success']         = false;
        $returnArray['messSeverity']    = 'W';
        $returnArray['errorMess']       = 'Rental value greater than limit £9,999.99';
        echo json_encode($returnArray);
        exit;    
    }

    // check for clashes with existing bookings by calling function CottageBook_check
    $CottageBook_check = $dbFuncs->CottageBook_check($DateSat, $CottageNum, $FirstNight, $LastNight);

    // if error in check function copy into returnArray and return
    if (!$CottageBook_check['success']) {
        $returnArray['success'] = $CottageBook_check['success'];
        $returnArray['messSeverity'] = 'E';
        $returnArray['errorMess'] = $CottageBook_check['errorMess'];
        echo json_encode($returnArray);
        exit;    
    }

    // $CottageBook_check['success'] is true, no error in check function so check if the clashCount > 0
    if ($CottageBook_check['clashCount'] > 0) {
        $returnArray['success']         = false;
        $returnArray['clashCount']      = $CottageBook_check['clashCount'];
        $returnArray['messSeverity']    = 'W';
        $returnArray['errorMess']       = 'clashCount > 0';
        echo json_encode($returnArray);
        exit;    
    }

    // generate the bookingRef and put into returnArray to pass back to bookingMaint.js
    $bookingRef = $dbFuncs->generateUnique(4,'U');
    $returnArray['bookingRef'] = $bookingRef;

    // call the insert function; database errors are dealt with in the function
    $insertReturnArray = $dbFuncs->CottageBook_insert($DateSat, $CottageNum, $FirstNight, $LastNight, $Rental, $Notes, $bookingRef, $BookingStatus);

    if ($insertReturnArray['success']) {

        // copy insertedIdNum into returnArray to pass back to bookingMaint.js
        $returnArray['insertedIdNum'] = $insertReturnArray['insertedIdNum'];

        $dbFuncs->ProcessLog_insert2('I','bookingMaint_ajax.php','method=insert', null,'Inserted: IdNum: '            . $returnArray['insertedIdNum']
         . ' DateSat: '         . $DateSat
         . ' Cottage number: '  . $CottageNum
         . ' firstNight: '      . $FirstNight
         . ' lastNight: '       . $LastNight 
         . ' BookingRef: '      . $returnArray['bookingRef'] 
         . ' BookingStatus: '   . $BookingStatus
         . ' Rental: '          . $Rental 
         . ' Notes: '           . $Notes);
    }
    else {
        // something went wrong during insert so copy error message into returnArray
        $returnArray['success']         = false;
        $returnArray['messSeverity']    = 'E';
        $returnArray['errorMess']       = $insertReturnArray['errorMess'];
    }

    // always return the return array for the javascript to interpret
    echo json_encode($returnArray);

    exit;
} // end of method=insert


// get a single CottageWeek row from the database
if ($_POST['method']==='cottageWeek_get') {

    $return = array('success'          =>true,
                   'cottageWeekRow'    =>null
                    );

    $returnArray = $dbFuncs->cottageWeek_get($_POST['dateSat'], $_POST['cottageNum']);
    $return['success']          = $returnArray['success'];
    $return['cottageWeekRow']   = $returnArray['data'][0];
    echo json_encode($return);

    exit;
} // end of method='cottageWeek_get'


// update the BookingStatus of a CottageBook row
if ($_POST['method']==='cottageBook_updStatus') {

    $return = array('success'   =>true,
                    'errorMess' =>null
                    );

    $returnArray = $dbFuncs->cottageBook_updStatus($_POST['IdNum'], $_POST['BookingStatus']);

    $return['success']      = $returnArray['success'];
    $return['errorMess']    = $returnArray['errorMess'];
    echo json_encode($return);

    exit;
} // end of method===cottageBook_updStatus


?>