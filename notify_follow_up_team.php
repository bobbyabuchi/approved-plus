<?php
require dirname(dirname(dirname(__FILE__))) .'/ezekcuij/ykiv/settings.php';
// $fullname = 'Bobby';
// $g_name = 'Dr. Martha';
if($gender == 'Male'){
  $pronoun = 'him';
}else{
  $pronoun = 'her';
}
$message_body ='
<p>Calvary Greetings Beloved, <br />'.$fullname.', a '.$gender.' from '.$location.' just made a decision for Jesus Christ.</p>
<p>You can contact '.$pronoun.' for follow up on: <b style="color:skyblue; font-size:22px;">'.$phone.'</b></p>
<p><a href="#">Login to Follow up Portal</a>
';
// require '/usr/share/php/libphp-phpmailer/class.phpmailer.php';
// require '/usr/share/php/libphp-phpmailer/class.smtp.php';
require('libphp-phpmailer/class.phpmailer.php');
require('libphp-phpmailer/class.smtp.php');

$mail = new PHPMailer;
//$mail->setFrom('follow_up@saved.com.ng','Follow Up');
$mail->setFrom('info@saved.com.ng','Saved | Follow Up');

// Receivers 
//$mail->addAddress('bobbyabuchi@gmail.com');
$mail->addAddress('markachi@icloud.com');

$mail->Subject = 'Decision Alert';

$mail->isHTML(true); //// isHTML() with true parameter value, for sending HTML formatted email

$mail->Body = $message_body;
$mail->IsSMTP();
$mail->SMTPSecure = 'ssl';
///$mail->Host = 'ssl://smtp.gmail.com';
///$mail->Host = 'smtp.gmail.com';
$mail->Host = 'premium80.web-hosting.com';
$mail->SMTPAuth = true;
$mail->Port = 465;

//Set your existing gmail address as user name
$mail->Username = 'info@saved.com.ng';

//Set the password of your gmail address here
$mail->Password = $email_pw;
// if(!$mail->send()) {
//   #* log a message about email not being sent display on my dashboard
//   echo 'Email is not sent.';
//   echo 'Email error: ' . $mail->ErrorInfo;
// } else {
//   $message_sent = 'Email has been sent.';
// }
if(!$mail->send()) {
  #* log a message about email not being sent display on my dashboard
  $err_message = 'Email error: ' . $mail->ErrorInfo;
  $log_error_message = "INSERT INTO error_messages (title, details) VALUES ('Email failed to send','$err_message')";
        if ($connect_db->query($log_error_message) != TRUE) {
            // code...
            $flash_message = "<b class='text-dark btn btn-danger btn-block'>Error: ".$connect_db->error."</b>";
            //$flash_message .= $connect_db->error; 
        }
  }
?>