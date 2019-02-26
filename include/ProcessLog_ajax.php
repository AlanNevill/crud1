<?php
// interface between ProcessLog_maint.php and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

$input = filter_input_array(INPUT_POST);

// select all the ProcessLog rows from the table
if ($_POST['method']==='ProcessLog_selectAll') {
  echo json_encode($dbFuncs->ProcessLog_selectAll($input['MessType']));
  exit();
}


// update AlarmRaised for given ProcessLog row PK
if ($input['method'] === 'ProcessLog_updDeviceDesc') {
  echo json_encode($dbFuncs->ProcessLog_updAlarmRaised($input['PK_ProcessLog'], $input['AlarmRaised']));
  exit();
}


// delete a given ProcessLog row
if ($_POST['method']==='ProcessLog_delete') {
  echo json_encode($dbFuncs->ProcessLog_delete($input['PK_ProcessLog']));
  exit();
}


?>