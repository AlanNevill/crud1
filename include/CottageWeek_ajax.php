<?php
// interface between CottageWeek_maint.php and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class


// select all the CottageWeek rows from the table for a given Cottage Number
if ($_POST['method']==='CottageWeek_select') {

  $todayMinus7 = date('Y-m-d', strtotime('-7 days'));

  $filters = array (
    "CottageNum"   => array ( "filter"=>FILTER_VALIDATE_INT,
                              "options"=>array("min_range"=>1,"max_range"=>3)
                            )
    );

  $input = filter_input_array(INPUT_POST, $filters);

  $sql = "select * from CottageWeek where DateSat >= '{$todayMinus7}' and CottageNum = {$input['CottageNum']} order by 1 asc";
  echo json_encode($dbFuncs->CottageWeek_select($sql));
  exit();
}


// update a CottageWeek row
if ($_POST['method'] === 'CottageWeek_upd') {

  $filters = array (
    "CottageNum"  => array ( "filter"=>FILTER_VALIDATE_INT,
                              "options"=>array("min_range"=>1,"max_range"=>3)
                            ),
    "ShortBreaks" => array ( "filter"=>FILTER_VALIDATE_BOOLEAN),
    "RentDay"     => array ( "filter"=>FILTER_VALIDATE_FLOAT,
                              "options"=>array("min_range"=>0,"max_range"=>999)
                            ),
    "RentWeek"     => array ( "filter"=>FILTER_VALIDATE_FLOAT,
                              "options"=>array("min_range"=>0,"max_range"=>9999)
                            )
  );

  $input = filter_input_array(INPUT_POST, $filters);

  echo json_encode($dbFuncs->CottageWeek_upd($_POST['DateSat'], $input['CottageNum'], $input['ShortBreaks'], $input['RentDay'], $input['RentWeek']));
  exit();
}


// report all non reported errors or warnings in the CottageWeek table into a CSV file
// TODO - not implemented
if ($_POST['method']==='CottageWeek_reportErrors') {
  $maxIdNum = $dbFuncs->CottageWeek_selectMaxIdNum();

  # check for no CottageWeek E or W rows to report
  if ($maxIdNum==0) {
    header('HTTP/1.1 204');
    header('Content-Type: text/csv; charset=UTF-8');

    echo json_encode(array( 'success' => true,
                            'message' => 'No E or W CottageWeek rows to report')
    );
    exit;
  }


  $returnArray = $dbFuncs->CottageWeek_selectErrorsNotReported($maxIdNum);
  if ($returnArray['success'] == true) {

    # Informational message to CottageWeek in case need to revert the AlarmRaised update to 'Y'
    $dbFuncs->CottageWeek_insert2( 'I',
                                  'CottageWeek_ajax - method: CottageWeek_reportErrors',
                                  'dbFuncs->CottageWeek_selectErrorsNotReported',
                                  null,
                                  $returnArray['message']
    );

    # Update the AlarmRaised column to 'Y'
    $returnArray2 = $dbFuncs->CottageWeek_updateErrorsNotReported($maxIdNum);
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
    header("Content-Disposition: attachment; filename=\"{$hostname}_CottageWeek-Errors.csv\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    # output the CottageWeek rows as CSV file
    $output = fopen('php://output', 'w');
    fputcsv($output, $colHeaders);

    foreach ($returnArray['CottageWeekRows'] as $row) {
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
} // end of CottageWeek_reportErrors


?>