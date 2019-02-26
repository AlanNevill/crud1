<?php // common_ajax.php

// interface between js and dbFuncs.php

include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

// create a row in the DeviceId table if the deviceId does not already exist
if ($_POST['method']==='DeviceId_insert') {

  $return = $dbFuncs->DeviceId_insert($_POST['deviceId'], 
                                      $_POST['userAgentString']);

  // if the return is not an array then it's an error message
  if ( is_array($return) ) {
    if ($return['rowInserted']==1) {

      $dbFuncs->ProcessLog_insert('W', 
                                  $_SERVER['REQUEST_URI'],
                                  'common_ajax>method===DeviceId_insert',
                                  $_POST['deviceId'],
                                  null,
                                  'New device with userAgentString: ' . $_POST['userAgentString']
      );
    }
    else {
      $dbFuncs->ProcessLog_insert('I', 
                                  $_SERVER['REQUEST_URI'],
                                  'common_ajax>method===DeviceId_insert',
                                  $_POST['deviceId'],
                                  null,
                                  'Existing device with userAgentString: ' . $_POST['userAgentString']
      );
    }
  } else {
    $return['success'] = false;
  }
  
  echo json_encode($return);
  exit();
}


// write a row to the ProcessLog table
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


// send an email test
if ($_POST['method']==='sendEmail') {

  $returnArray = array('success'  =>true,
                      'message'   =>null);

  $to      = 'alannevill@gmail.com, alannevill@outlook.com';
  $subject = 'Subject 04';
  $message = 'Hello 04';
  $headers = 'From:alan@meadowgreenfarm.co.uk' . '\r\n'
              . 'Reply-To:alan@meadowgreenfarm.co.uk' . '\r\n'
              . 'X-Mailer:PHP/' . phpversion();
  // $headers = array(
  //     'From'      => 'alan@meadowgreenfarm.co.uk',
  //     'Reply-To'  => 'alan@meadowgreenfarm.co.uk',
  //     'X-Mailer'  => 'PHP/' . phpversion()
  // );
  
  try {
    $boSend = mail($to, $subject, $message, $message, '-falan@meadowgreenfarm.co.uk');
    $returnArray['message'] = '$boSend: [' . $boSend . ']. $headers: ' . $headers;

  } catch (\Throwable $error) {
    $returnArray['success'] = false;
    $returnArray['message'] = $error->getMessage();

    //throw $error;
  }

  // return the return array to javascript caller
  echo json_encode($returnArray);
  exit();
 }

?>