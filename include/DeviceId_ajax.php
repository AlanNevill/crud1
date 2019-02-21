<?php
// interface between DeviceId_maint.php and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

$input = filter_input_array(INPUT_POST);

// select all the DeviceId rows from the table
if ($_POST['method']==='DeviceId_selectAll') {
  echo json_encode($dbFuncs->DeviceId_selectAll());
  exit();
}


// update DeviceDesc for given DeviceId row PK
if ($input['method'] === 'DeviceId_updDeviceDesc') {
  echo json_encode($dbFuncs->DeviceId_updDeviceDesc($input['PK_deviceId'], $input['newDesc']));
  exit();
}


// delete a given DeviceId row
if ($_POST['method']==='DeviceId_delete') {
  echo json_encode($dbFuncs->DeviceId_delete($input['PK_deviceId']));
  exit();
}


?>