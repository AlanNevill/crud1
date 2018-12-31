<?php // common_ajax.php

// interface between js and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

// create a row in the DeviceId table if the deviceId does not already exist
if ($_POST['method']==='DeviceId_insert') {

  $return = $dbFuncs->DeviceId_insert($_POST['deviceId'], 
                                      $_POST['userAgentString']);

  // if the return is not an array then it's an error message
  if ( !is_array($return) ) {
      $return['success'] = false;
  } 
  
  echo json_encode($return);
  exit();
}


// write a row to table ProcessLog
if ($_POST['method']==='ProcessLog_insert') {

 $dbFuncs->ProcessLog_insert($_POST['MessType'], 
                             $_SERVER['REQUEST_URI'],
                             $_POST['Routine'],
                             $dbFuncs->deviceId,
                             $_POST['ErrorMess'],
                             $_POST['Remarks']
                          );

  exit();
}


?>