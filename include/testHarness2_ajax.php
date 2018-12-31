<?php
// interface between testHarness2.php and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate the dbFuncs class

// create a row in the DeviceId table if the deviceId does not already exist
if ($_POST['method']==='DeviceId_insert') {

  // $return = array('success'=>true, 'message'=>null);

  $return = $dbFuncs->DeviceId_insert($_POST['deviceId'], 
                                      $_POST['userAgentString']);

  // if the return is not an array then it's an error message
  if ( !is_array($return) ) {
      $return['success'] = false;
  } 
  
  echo json_encode($return);
  exit();
}




?>