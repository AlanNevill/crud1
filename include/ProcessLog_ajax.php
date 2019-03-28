<?php
// interface between ProcessLog_maint.php and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

$input = filter_input_array(INPUT_POST);

// select all the ProcessLog rows from the table for a given MessType
if ($_POST['method']==='ProcessLog_selectAll') {
  echo json_encode($dbFuncs->ProcessLog_selectAll($input['MessType']));
  exit();
}

// TODO : Not yet implemented in dbFuncs
// update AlarmRaised for given ProcessLog row PK
if ($input['method'] === 'ProcessLog_updAlarmRaised') {
  echo json_encode($dbFuncs->ProcessLog_updAlarmRaised($input['PK_ProcessLog'], $input['AlarmRaised']));
  exit();
}


// delete a given ProcessLog row
if ($_POST['method']==='ProcessLog_delete') {
  echo json_encode($dbFuncs->ProcessLog_delete($input['PK_ProcessLog']));
  exit();
}


// report all non reported errors or warnings in the ProcessLog table
if ($_POST['method']==='ProcessLog_reportErrors') {
  $maxIdNum = $dbFuncs->ProcessLog_selectMaxIdNum();

  # check for no ProcessLog E or W rows to report
  if ($maxIdNum==0) {
    header('HTTP/1.1 204');
    header('Content-Type: text/csv; charset=UTF-8');

    echo json_encode(array( 'success' => true,
                            'message' => 'No E or W processLog rows to report')
    );
    exit;
  }

  $returnArray = $dbFuncs->ProcessLog_selectErrorsNotReported($maxIdNum);
  if ($returnArray['success'] == true) {

    # Informational message to ProcessLog in case need to revert the AlarmRaised update to 'Y'
    $dbFuncs->ProcessLog_insert2( 'I',
                                  'ProcessLog_ajax - method: ProcessLog_reportErrors',
                                  'dbFuncs->ProcessLog_selectErrorsNotReported',
                                  null,
                                  $returnArray['message']
    );

    # Update the AlarmRaised column to 'Y'
    $returnArray2 = $dbFuncs->ProcessLog_updateErrorsNotReported($maxIdNum);
    if ($returnArray2['success'] == false) {
      header('HTTP/1.1 204');
      header('Content-Type: text/csv; charset=UTF-8');

      echo json_encode($returnArray2);
      exit;
    }

    # write a CSV file
    $colHeaders = array(  'IdNum',
                          'MessDateTime',
                          'MessType',
                          'Application',
                          'Routine',
                          'UserId',
                          'ErrorMess',
                          'Remarks',
                          'AlarmRaised'
    );

    $hostname = gethostname();

    # setup the return headers
    header('HTTP/1.1 200 OK');
    header('Content-Description: File Transfer');
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: text/csv; charset=UTF-8');
    header("Content-Disposition: attachment; filename=\"{$hostname}_ProcessLog-Errors.csv\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    # output the ProcessLog rows as CSV file
    $output = fopen('php://output', 'w');
    fputcsv($output, $colHeaders);
    foreach ($returnArray['ProcessLogRows'] as $row) {
      fputcsv($output, $row);
    }
    fclose($output);

  }
  else { # some error
    header('HTTP/1.1 204');
    header('Content-Type: text/csv; charset=UTF-8');

    echo json_encode($returnArray);
  }

  exit();
}



?>