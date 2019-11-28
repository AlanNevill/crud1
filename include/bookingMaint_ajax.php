<?php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class


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
    // call the delete function in dbFuncs
    $returnArray = $dbFuncs->CottageBook_delete($_POST['IdNum']);

    // check for error in the sql delete
    if ($returnArray['success']) {
        $dbFuncs->ProcessLog_insert2('I', 'bookingMaint_ajax.php', 'method=CottageBook_delete', '', 'Deleted, IdNum: '. $_POST['IdNum']);
    } else {
        $dbFuncs->ProcessLog_insert2('E', 'bookingMaint_ajax.php', 'method=CottageBook_delete', $returnArray['message'], 'Deleted failed, IdNum: '. $_POST['IdNum']);
    }
  echo JSON_encode($returnArray);
  exit;
} // end of method=delete


// insert a new booking into CottageBook table if no clashes with existing bookings
if($_POST['method']==='insert')
{
    $DateSat            = $_POST['DateSat'];
    $CottageNum         = $_POST['CottageNum']; 
    $FirstNight         = $_POST['firstNight']; 
    $LastNight          = $_POST['lastNight']; 
    $BookingName        = $_POST['BookingName'];
    $BookingStatus      = $_POST['BookingStatus'];
    $Rental             = $_POST['Rental'];
    $Notes              = $_POST['notes'];
    $BookingSource      = $_POST['BookingSource'];
    $ExternalReference  = $_POST['ExternalReference'];
    $ContactEmail       = $_POST['ContactEmail'];
    $NumAdults          = $_POST['NumAdults'];
    $NumChildren        = $_POST['NumChildren'];
    $Children           = $_POST['Children'];
    $NumDogs            = $_POST['NumDogs'];

    # initialize the returnArray
    $returnArray = array('success'      => true,
                        'clashCount'    => 0,
                        'bookingRef'    => null,
                        'insertedIdNum' => 0,
                        'messSeverity'  => 'I',
                        'errorMess'     => null
                      );

    # check mandatory variables are populated
    if (empty($DateSat))       { $returnArray['errorMess'] .= 'DateSat is empty, '; }
    if (empty($CottageNum))    { $returnArray['errorMess'] .= 'CottageNum is empty, '; }
    if (empty($FirstNight))    { $returnArray['errorMess'] .= 'FirstNight is empty, '; }
    if (empty($LastNight))     { $returnArray['errorMess'] .= 'LastNight is empty, '; }
    if (empty($BookingName))   { $returnArray['errorMess'] .= 'BookingName is empty, '; }
    if (empty($Rental))        { $returnArray['errorMess'] .= 'Rental is empty, '; }
    if (empty($BookingStatus)) { $returnArray['errorMess'] .= 'BookingStatus is empty, '; }
    if (empty($BookingSource)) { $returnArray['errorMess'] .= 'BookingSource is empty, '; }
    if ($Rental > 9999.99)     { $returnArray['errorMess'] .= 'Rental value greater than limit £9,999.99'; }

    # any errors then return
    if (!empty($returnArray['errorMess'])) {
      $returnArray['success'] = false;
      $returnArray['messSeverity'] = 'E';
      echo json_encode($returnArray);
      exit;    
    }

    # check for clashes with existing bookings by calling function CottageBook_check
    $CottageBook_check = $dbFuncs->CottageBook_check($DateSat, $CottageNum, $FirstNight, $LastNight);

    # if error returned from CottageBook_check function, copy into returnArray and return
    if (!$CottageBook_check['success']) {
        $returnArray['success'] = $CottageBook_check['success'];
        $returnArray['messSeverity'] = 'E';
        $returnArray['errorMess'] = $CottageBook_check['errorMess'];
        echo json_encode($returnArray);
        exit;    
    }

    # $CottageBook_check['success'] is true, no error in check function so check if the clashCount > 0
    if ($CottageBook_check['clashCount'] > 0) {
        $returnArray['success']         = false;
        $returnArray['clashCount']      = $CottageBook_check['clashCount'];
        $returnArray['messSeverity']    = 'W';
        $returnArray['errorMess']       = 'clashCount > 0';
        echo json_encode($returnArray);
        exit;    
    }

    ///////////////////////////////////////////////////////////
    // all validation complete; proceed with insert new booking
    ///////////////////////////////////////////////////////////

    # generate the bookingRef and put into returnArray to pass back to bookingMaint.js
    $bookingRef = $dbFuncs->generateUnique(4,'U');
    $returnArray['bookingRef'] = $bookingRef;

    
    # call the insert function; database errors are dealt with in the dbFuncs function
    $insertReturnArray = $dbFuncs->CottageBook_insert($DateSat, 
                                                      $CottageNum, 
                                                      $FirstNight, 
                                                      $LastNight, 
                                                      $BookingName, 
                                                      $BookingStatus,
                                                      $bookingRef,
                                                      $Rental, 
                                                      $Notes, 
                                                      $BookingSource,
                                                      $ExternalReference,
                                                      $ContactEmail,
                                                      $NumAdults,
                                                      $NumChildren,
                                                      $Children,
                                                      $NumDogs
                                                      );

    if ($insertReturnArray['success']) {

      # copy insertedIdNum into returnArray to pass back to bookingMaint.js
      $returnArray['insertedIdNum'] = $insertReturnArray['insertedIdNum'];

      # log the insert into ProcessLog table
      $dbFuncs->ProcessLog_insert2('I','bookingMaint_ajax.php','method=insert', null,
          'Inserted: IdNum: '  . $returnArray['insertedIdNum']
        . ', DateSat: '         . $DateSat
        . ', Cottage number: '  . $CottageNum
        . ', firstNight: '      . $FirstNight
        . ', lastNight: '       . $LastNight 
        . ', BookingName: '     . $BookingName
        . ', BookingRef: '      . $returnArray['bookingRef'] 
        . ', BookingStatus: '   . $BookingStatus
        . ', Rental: '          . $Rental 
        . ', Notes: '           . $Notes);
    }
    else {
      # something went wrong during insert so copy error message into returnArray
      $returnArray['success']         = false;
      $returnArray['messSeverity']    = 'E';
      $returnArray['errorMess']       = $insertReturnArray['errorMess'];
    }

    # always return the return array for the javascript to interpret
    echo json_encode($returnArray);

    exit;
} // end of method=insert


// update a CottageBook row
if ($_POST['method']==='cottageBook_upd') {

  # initialize the returnArray
  $returnArray = array('success'  => true,
                      'errorMess' => null
                      );

  # check mandatory variables are populated
  if (empty($_POST['IdNum']))         { $returnArray['errorMess'] .= 'IdNum is empty, '; }
  if (empty($_POST['BookingName']))   { $returnArray['errorMess'] .= 'Booking Name is empty, '; }
  if (empty($_POST['BookingStatus'])) { $returnArray['errorMess'] .= 'Booking Status is empty, '; }
  if (empty($_POST['BookingSource'])) { $returnArray['errorMess'] .= 'Booking Source is empty, '; }

  if (!empty($returnArray['errorMess'])) {
    $returnArray['success'] = false;
    echo json_encode($returnArray);
    exit;    
  }

  # check BookingStatus is valid
  if (!$dbFuncs->StatusCodes_validate('bookingStatus', $_POST['BookingStatus'])) {
    $returnArray['success'] = false;
    $returnArray['errorMess'] = 'Booking Status: ' . $_POST['BookingStatus'] . ' is not a valid status.';
    echo json_encode($returnArray);
    exit;    
  }
  
  # check BookingSource is valid
  if (!$dbFuncs->StatusCodes_validate('bookingSource', $_POST['BookingSource'])) {
    $returnArray['success'] = false;
    $returnArray['errorMess'] = 'Booking Source: ' . $_POST['BookingSource'] . ' is not a valid source.';
    echo json_encode($returnArray);
    exit;    
  }

  $FuncReturn = $dbFuncs->cottageBook_upd($_POST['IdNum'], 
                                          $_POST['BookingName'],
                                          $_POST['BookingStatus'],
                                          $_POST['Rental'],
                                          $_POST['Notes'],
                                          $_POST['BookingSource'],
                                          $_POST['ExternalReference'],
                                          $_POST['ContactEmail'],
                                          $_POST['NumAdults'],
                                          $_POST['NumChildren'],
                                          $_POST['Children'],
                                          $_POST['NumDogs']
                                          );

  if (!$FuncReturn['success']) {
    $returnArray['success']   = false;
    $returnArray['errorMess'] = $FuncReturn['message'];
  }
  echo json_encode($returnArray);

  exit;
} // end of method===cottageBook_upd



// get a single CottageWeek row from the database
if ($_POST['method']==='cottageWeek_get') {

    $returnArray = $dbFuncs->cottageWeek_get($_POST['dateSat'], $_POST['cottageNum']);
    
    echo json_encode($returnArray);

    exit;
} // end of method='cottageWeek_get'


// update the BookingStatus of a CottageBook row
if ($_POST['method']==='cottageBook_updStatus') {

    $returnArray = $dbFuncs->cottageBook_updStatus($_POST['IdNum'], $_POST['BookingStatus']);

    echo json_encode($returnArray);

    exit;
} // end of method===cottageBook_updStatus


// update the BookingNotes of a CottageBook row
if ($_POST['method']==='cottageBook_updNotes') {

  if (empty($_POST['IdNum'])) {

    $errMess = 'IdNum is empty.';
    $returnArray = array('success'    => false,
                          'message'   => $errMess);

    $dbFuncs->ProcessLog_insert2('E', 'MGF2', 'dbFuncs.spCottageBook_updNotes', $errMess, null);
    echo json_encode($returnArray);
    exit;
  }

  if(empty($_POST['Notes'])) {
    $_POST['Notes'] = "";
  }
  $returnArray = $dbFuncs->cottageBook_updNotes($_POST['IdNum'], $_POST['Notes']);

  echo json_encode($returnArray);

  exit;
} // end of method===cottageBook_updNotes

?>
