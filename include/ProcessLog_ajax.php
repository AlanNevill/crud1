<?php
// interface between ProcessLog_maint.php and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

$input = filter_input_array(INPUT_POST);

// select all the ProcessLog rows from the table for a given MessType
if ($_POST['method']==='ProcessLog_selectAll') {
  echo json_encode($dbFuncs->ProcessLog_selectAll($input['MessType']));
  exit();
}


// select all the ProcessLog rows from the table for a given MessType
if ($_POST['method']==='ProcessLog_select') {

  $messType = '';
  
  switch ($_POST['messType']) {
    case '2':
      $messType = "and MessType='E'";
      break;
    case '3':
      $messType = "and MessType='W'";
      break;
    case '4':
      $messType = "and MessType='I'";
      break;
    case '5':
      $messType = "and MessType in ('E','W')";
      break;
  }

  $alarmRaised = '';

  switch ($_POST['alarmRaised']) {
    case '2':
      $alarmRaised = "and alarmRaised='Y'";
      break;
    case '3':
      $alarmRaised = "and alarmRaised='N'";
      break;
  }

  $sql = "select * from ProcessLog where 1=1 {$messType} {$alarmRaised} order by IdNum desc";
  echo json_encode($dbFuncs->ProcessLog_select($sql));
  exit();
}


// update a ProcessLog row
if ($input['method'] === 'ProcessLog_upd') {
  echo json_encode($dbFuncs->ProcessLog_upd($input['IdNum'], $input['UserId'], $input['Application'], $input['Remarks'], $input['AlarmRaised']));
  exit();
}


// delete a given ProcessLog row
if ($_POST['method']==='ProcessLog_delete') {
  echo json_encode($dbFuncs->ProcessLog_delete($input['IdNum']));
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

    # get the host name to prefix the CSV filename
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