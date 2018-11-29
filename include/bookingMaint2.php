<?php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

// mustache templating in PHP
// require 'C:/Users/User/Downloads/Mustache/mustache.php-2.12.0/src/Mustache/autoloader.php';
// Mustache_Autoloader::register();
// $m = new Mustache_Engine;
// echo $m->render('Hello, {{planet}}!', array('planet' => 'World')); // "Hello, World!"

$errors = array(); // used 

// echo json_encode($_POST);

// get the bookings for the wc date and cottage num selected
if($_POST['method']==='get'){

    if($_POST['wcDateSat']==-1) {
        $errors[] = "Please select a wc Saturday date/n";
    }
    
    if($_POST['cottageNum']==-1) {
        $errors[] = "Please select a cottage";
    }
    
    if(!empty($errors)){
        $errHtml="<p><strong>";
        foreach ($errors as $msg) {
            $errHtml.= $msg;
        }
        $errHtml.="</strong></p>";
        die(nl2br($errHtml));
    }
    
    // get any CottageBook rows 
    $wcDateSat = $_POST['wcDateSat'];
    $cottageNum = $_POST['cottageNum'];
    $data = array();

    // rows from CottageBook for the given DateSat and CottageNum
    $data = $dbFuncs->CottageBook_select($wcDateSat, $cottageNum);

    // if rows returned
    if($data) {
        $bookingTable = '';
        $bookingRow = '';

        foreach ($data as $row) {
            $bookingRow = "<tr id="     . $row['IdNum']
                        . " firstNight=". $row['FirstNight']
                        . "><td>"       . date("D d M", strtotime($row['FirstNight']))
                        . "</td><td>"   . date("D d M", strtotime($row['LastNight'])) 
                        . "</td><td>"   . $row['numNights'] 
                        . "</td><td>"   . "£" . number_format($row['Rental'] ,2)
                        . "</td><td>"   . $row['BookingRef'] 
                        . "</td><td>"   . $row['Notes'] 
                        . "</td><td><button class='remove' IdNum=" . $row['IdNum'] . "></button></td></tr>";
            $bookingTable .= $bookingRow;
        }
        echo $bookingTable;
    }
    else {
        echo '<strong>No bookings yet</strong>';
        }
   
    exit;
}   // end of method=get    


if($_POST['method']==='delete')
{
    // call the delete function
    $errors = $dbFuncs->CottageBook_delete($_POST['IdNum']);

    // check for error in the sql delete
    if (is_string($errors) && substr($errors, 0, 8 ) == 'ERROR - ') {
        $dbFuncs->ProcessLog_insert2('E', 'bookingMaint2.php', 'method=delete', $errors, 'IdNum: '. $_POST['IdNum']);
        echo '<h2>Delete error - see errorlog</h2>';
    } else {
        $dbFuncs->ProcessLog_insert2('I', 'bookingMaint2.php', 'method=delete', null, 'Deleted, IdNum: '. $_POST['IdNum']);
        echo 'Delete successful';
    }
   exit;
} // end of method=delete


// check for clashes 
if($_POST['method']==='check')
{
    $DateSat = $_POST['dateSat'];
    $CottageNum = $_POST['cottageNum']; 
    $FirstNight = $_POST['firstNight']; 
    $LastNight = $_POST['lastNight']; 

    // check proposed dates for a new booking against existing bookings in the database and return count
    $returnArray = $dbFuncs->CottageBook_check($DateSat, $CottageNum, $FirstNight, $LastNight);

    echo JSON_encode($returnArray);
    exit;
} // end of method=check


// insert a new booking in CottageBook table
if($_POST['method']==='insert')
{
    $DateSat    = $_POST['dateSat'];
    $CottageNum = $_POST['cottageNum']; 
    $FirstNight = $_POST['firstNight']; 
    $LastNight  = $_POST['lastNight']; 
    $Rental     = $_POST['Rental'];
    $Notes      = $_POST['notes'];

    // initialize the returnArray
    $returnArray = array('success'      =>true,
                        'clashCount'    =>0,
                        'bookingRef'    =>null,
                        'insertedIdNum' =>0,
                        'messSeverity'  =>'I',
                        'errorMess'     =>null);

    // check $Rental is not greater than 9,999.99
    if ($Rental > 9999.99 ) {
        $returnArray['success'] = false;
        $returnArray['messSeverity'] = 'W';
        $returnArray['errorMess'] = 'Rental value greater than limit £9,999.99';
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

    // $CottageBook_check['success'] is true, no error in check function so check the clashCount > 0
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
    $insertReturnArray = $dbFuncs->CottageBook_insert($DateSat, $CottageNum, $FirstNight, $LastNight, $Rental, $Notes, $bookingRef);

    if ($insertReturnArray['success']) {

        // copy insertedIdNum into returnArray to pass back to bookingMaint.js
        $returnArray['insertedIdNum']   = $insertReturnArray['insertedIdNum'];

        // TODO If it's worth starting the mustache engine
        // $remarksTemplate =  "Inserted: IdNum: " . $return .
        //                     " dateSat: {{dateSat}} " .
        //                     "cottageNum: {{cottageNum}} " .
        //                     "firstNight: {{firstNight}} " .
        //                     "lastNight: {{lastNight}} " .
        //                     "notes: {{notes}}";
        // $remarks = $m->render($remarksTemplate, $_POST);

        // $dbFuncs->ProcessLog_insert2('I','bookingMaint2.php','method=insert', null, $remarks);

        $dbFuncs->ProcessLog_insert2('I','bookingMaint2.php','method=insert', null,'Inserted: IdNum: '              . $returnArray['insertedIdNum']
         . ' DateSat: '         . $DateSat
         . ' Cottage number: '  . $CottageNum
         . ' firstNight: '      . $FirstNight
         . ' lastNight: '       . $LastNight 
         . ' BookingRef:'       . $returnArray['bookingRef'] 
         . ' Rental:'           . $Rental 
         . ' Notes: '           . $Notes);
    }
    else {
        // something went wrong during insert so copy error message into returnArray
        $returnArray['success'] = false;
        $returnArray['messSeverity'] = 'E';
        $returnArray['errorMess'] = $insertReturnArray['errorMess'];
    }

    // always return the return array for the javascript to interpret
    echo json_encode($returnArray);

    exit;
} // end of method=insert


// get a single CottageWeek row from the database
if ($_POST['method']==='cottageWeek_get') {

    $return = array('success'=>true, 'cottageWeekRow'=>null);
    try {
        $return['cottageWeekRow'] = $dbFuncs->cottageWeek_get($_POST['dateSat'], $_POST['cottageNum']);
    } catch (Exception $e) {
        $dbFuncs->ProcessLog_insert2('E', 'bookingMaint2.php', 'method=cottageWeek_get', $e.getMessage(), 'dateSat: '. $_POST['dateSat']. ' cottageNum: ' . $_POST['cottageNum']);

        $return['success']=false;
        $return['cottageWeekRow'] = $e.getMessage();
    } finally{
        echo json_encode($return);
        }
} // end of method='cottageWeek_get'


?>