<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

include_once('../config/conn.php');
$qry="SELECT d.`lsDetailsId`, cs.customerName_$language as customerName , cs.`customerEmail`,
e.`empName_en`, e.`email`,
s.`sessionName`, s.`sessionDate`, s.`sessionTime`
 FROM `tbl_lawsuit_details` d
LEFT JOIN `tbl_lawsuit_customers` c ON c.`lsDetailsId`=d.`lsDetailsId`
LEFT JOIN `tbl_customers` cs ON cs.`customerId`=c.`customerId`
LEFT JOIN `tbl_lawsuit_lawyer` l ON l.`lsDetailId`=d.`lsDetailsId`
LEFT JOIN `tbl_employees` e ON e.`empId`=l.`empId`
LEFT JOIN `tbl_lawsuit_sessions` s ON s.`lsDetailsId`=d.`lsDetailsId`
WHERE s.`lsSessionId`=:lsSessionId";

$stmt=$dbo->prepare($qry);
$stmt->bindParam(":lsSessionId",$_POST['id'],PDO::PARAM_STR);
if($stmt->execute())
{
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else 
{
	$errorInfo = $stmt->errorInfo();
	exit($json =$errorInfo[2]);
}
if(!$result) 
{
	exit('no Data to email');
}
foreach($result as $row)
{
	$customerName=$row['customerName'];
	$customerEmail=$row['customerEmail'];
	$empName_en=$row['empName_en'];
	$email=$row['email'];
	$sessionName=$row['sessionName'];
	$sessionDate=$row['sessionDate'];
	$sessionTime=$row['sessionTime'];
}


try {
    //Server settings
    ////$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
	$mail->SetLanguage("en", "PHPMailer/language");
	
    $mail->Host       = 'mail.beinlawyer.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'alert@beinlawyer.com';                     //SMTP username
    $mail->Password   = 'Oman@2024!';                               //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
    //$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('alert@beinlawyer.com', 'No Reply - Bein Lawyer');
   
    $mail->addAddress('msazab8@gmail.com'); // to
   // $mail->addAddress($email,$empName_en); // to
    $mail->addCC($email,$empName_en); // cc ownner

    //$mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('alert@beinlawyer.com', 'No Reply - Lawsuit');
  
    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Session Details';
    $mail->Body    = 'Name:'.$sessionName.'<br/> Date:'.$sessionDate.'<br/> Time:'.$sessionTime;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	if(!$mail->Send())
		//echo "<script>alert('Message could not be sent. Mailer Error:'{$mail->ErrorInfo}</script>";
		echo "Message could not be sent. Mailer Error";
	else 
		echo 'Message has been sent';
}
	catch (Exception $e) {
		///echo "<script>alert('Message could not be sent. Mailer Error:'{$mail->ErrorInfo}</script>";
		echo "'Message could not be sent. Mailer Error:'{$mail->ErrorInfo}";
	}

//echo '<script>window.location.href = "/checkemail"</script>';