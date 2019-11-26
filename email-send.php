<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once ('include/PHPMailer/src/Exception.php');
require_once ('include/PHPMailer/src/PHPMailer.php');
require('../../../wp-load.php');

if ($_POST){


$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$organization = $_POST['organization'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$bodytext = ""; 

if ($first_name) {
  $bodytext .= "<p>First Name - ".$first_name."</p>";
}

if ($last_name) {
  $bodytext .= "<p>Last Name - ".$last_name."</p>";
}

if ($email) {
  $bodytext .= "<p>Email - ".$email."</p>";
}

if ($phone) {
  $bodytext .= "<p>Phone - ".$phone."</p>";
}

if ($message) {
  $bodytext .= "<p>Message - ".$message."</p>";
}

$admin_email = get_option('admin_email');

$email = new PHPMailer(true);
try {
  
  $email->ClearAttachments(); 
  $email->ClearCustomHeaders(); 
  $email->ClearReplyTos(); 
  
  $email->SingleTo = true;
  $email->ContentType = 'text/html'; 
  $email->IsHTML( true );
  $email->CharSet = 'utf-8';
  $email->ClearAllRecipients();
  $email->From      = $admin_email;
  $email->FromName  = 'rumla';
  $email->Subject   = 'Заявка с сайта';
  $email->Body      = $bodytext;
  $email->addAddress($admin_email);  

 
  
  if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $email->AddAttachment($_FILES['file']['tmp_name'], $_FILES['file']['name']);
  }
  if (!$email->send()) { 
    $result = array('status'=>"error", 'message'=>"Mailer Error: ".$email->ErrorInfo);//
    echo json_encode($result);
  } else {
      $result = array('status'=>"success", 'message'=>"Message sent.");
      echo json_encode($result);
  }
}
catch (Exception $e) {
  $result = array('status'=>"error", 'message'=>'Message could not be sent. Mailer Error: '.$email->ErrorInfo);
  echo json_encode($result);
}
}
?> 