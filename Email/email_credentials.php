<?php 	
require 'email_files/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();                                      	// Set mailer to use SMTP
$mail->Host = 'mail.beinlawyer.com';  					// Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               	// Enable SMTP authentication

//$mail->SMTPSecure = 'none';                          	// Enable encryption, 'ssl' also accepted
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
//$mail->Port = 465;
//$mail->Port = 465;
	
//Set the encryption system to use - ssl (deprecated) or tls
//$mail->SMTPSecure = 'ssl';

$mail->From = 'alert@beinlawyer.com';
$mail->Password = 'Oman@2024!';  
$mail->FromName = 'No Reply - BeinLawyer';
//$mail->addAddress('ellen@example.com');               // Name is optional

//////////////// $mail->addReplyTo('aijaz.gul@pphisindh.org', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');
//$url_domain="https://pphisindh.org/careers/";
$url_domain=url();
$mail->WordWrap = 50;                                 	// Set word wrap to 50 characters
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);  
// Set email format to HTML

function url(){
	$server_name = explode("/", $_SERVER['SCRIPT_NAME']);
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['HTTP_HOST'],
	'/' .$server_name[1].'/'
  );
}

?>