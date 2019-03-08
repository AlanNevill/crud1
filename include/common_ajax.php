<?php // common_ajax.php

$input = filter_input_array(INPUT_POST);

// create a row in the DeviceId table if the deviceId does not already exist
if ($_POST['method']==='DeviceId_insert') {

  include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

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

  include('dbFuncs.php');     // open db connection and instantiate dbFuncs class

  $dbFuncs->ProcessLog_insert($_POST['MessType'], 
                             $_SERVER['REQUEST_URI'],
                             $_POST['Routine'],
                             $dbFuncs->deviceId,
                             $_POST['ErrorMess'],
                             $_POST['Remarks']
                          );

  exit();
}


// send a MGF enquiry response email
if ($input['method']==='EnquiryResponseEmail') {

  require_once '../vendor/autoload.php';
  include('reCaptcha.php');
  include('emailConfig.php');

  $returnArray = array('success'        => true,
                      'allValid'        => true,
                      'first_nameValid' => true,
                      'last_nameValid'  => true,
                      'email_toValid'   => true,
                      'enquiryValid'    => true,
                      'message'         => null
                    );

  // function to format validation errors and return to user
  function died($returnArray, $error) {
    // error message to user
    $mess  = "We are very sorry, but there were error(s) found with the form you submitted. ";
    $mess .= "These errors appear below.<br /><ul>" . $error . "</ul>";
    $mess .= "Please go back and fix these errors.<br />";

    // return the return array to javascript caller
    $returnArray['success'] = false;
    $returnArray['message'] = $mess;
    echo json_encode($returnArray);
    exit();
  }

  // function to clean up user input fields
  function clean_string($string) {
    $bad = array("content-type","bcc:","to:","cc:","href");
    return str_replace($bad, "", strip_tags($string));
  }

  // validate the required fields
  $error_message = "";
  if (empty($input['first_name'])) {
    $error_message .= "<li>First name is blank.</li>"; 
    $returnArray['first_nameValid'] = false;
  }
  if (empty($input['last_name']))  {
    $error_message .= "<li>Last name is blank.</li>"; 
    $returnArray['last_nameValid'] = false;
  }
  if (empty($input['email_to']))   {
    $error_message .= "<li>Email address is blank.</li>";
    $returnArray['email_toValid'] = false;
   }
  if (empty($input['enquiry']))    {
    $error_message .= "<li>Enquiry is blank.</li>"; 
    $returnArray['enquiryValid'] = false;
  }

  if (empty($input['captcha']))    {
    $error_message .= "<li>Please check the 'I'm not a robot' checkbox.</li>"; 
  }

  if(strlen($error_message) > 0) {
    $returnArray['allValid'] = false;
    died($returnArray, $error_message);
  }


  // clean up the user supplied fields
  $first_name     = clean_string($input['first_name']); // required
  $last_name      = clean_string($input['last_name']);  // required
  $email_to       = clean_string($input['email_to']);   // required
  $telephone      = (empty($input['telephone'])) ? "" : clean_string($input['telephone']);  // not required
  $enquiry        = clean_string($input['enquiry']);    // required

  $string_exp = "/^[A-Za-z .'-]+$/";
  if(!preg_match($string_exp, $first_name)) {
    $error_message .= '<li>First name does not appear to be valid.</li>';
    $returnArray['first_nameValid'] = false;
  }

  if(!preg_match($string_exp, $last_name)) {
    $error_message .= '<li>Last name does not appear to be valid.</li>';
    $returnArray['last_nameValid'] = false;
  }

  $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
  if(!preg_match($email_exp, $email_to)) {
    $error_message .= '<li>Email address does not appear to be valid.</li>';
    $returnArray['email_toValid'] = false;
  }

  // verfiy the recaptcha
  $verifySite = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$input['captcha']}");

  $captchaVerify = json_decode($verifySite);
  if ($captchaVerify->success == false) {
    $error_message .= "Please verify that you are not a robot."; 
  }

  // return error message if any errors found
  if(strlen($error_message) > 0) {
    $returnArray['allValid'] = false;
    died($returnArray, $error_message);
  }

  // no errors so format and send the message
  $email_subject = "Confirmation of your enquiry submitted to Meadow Green Farm";

  $email_message = "Dear {$first_name} {$last_name},\n\nThankyou for your enquiry. We will respond within 24 hours.\n\n";

  // $email_message .= "First name: "  . $first_name . "\n";
  // $email_message .= "Last name: "   . $last_name  . "\n";
  $email_message .= "Your email: {$email_to}\t\tYour contact no.: {$telephone}\n\n";
  // $email_message .= "Telephone: "   . $telephone  . "\n\n";
  $email_message .= $enquiry . "\n\nKind regards,\n\nMeadow Green Farm";

  // Create the Transport
  $transport = (new Swift_SmtpTransport('mail.meadowgreenfarm.co.uk', 465, 'ssl'))
    ->setUsername($emailConfig['enquiry']['setUsername'])
    ->setPassword($emailConfig['enquiry']['setPassword'])
  ;

  // Create the Mailer using the created Transport
  $mailer = new Swift_Mailer($transport);

  // Create a message
  $message = (new Swift_Message($email_subject))
    ->setFrom($emailConfig['enquiry']['From'])
    ->setReplyTo($emailConfig['enquiry']['ReplyTo'])
    ->setTo($email_to)
    ->setCc($emailConfig['enquiry']['Cc'])
    ->setBcc($emailConfig['enquiry']['Bcc'])
    ->setBody($email_message)
    ;

  // ini_set('max_execution_time', 60);

  // Send the message
  $result = $mailer->send($message);
  if ($result) {
    $returnArray['message'] = "# messages sent: " . $result;
  }
  else {
    $returnArray['success'] = false;
    $returnArray['message'] = "ERROR - Number of messages sent: " . $result;
  }

  // return the return array to javascript caller
  echo json_encode($returnArray);
  exit();
}
  

?>