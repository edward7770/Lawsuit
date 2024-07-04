<?php /**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
$myString = $_REQUEST['emails'];


/*$myArray = explode(',', $myString);
print_r($myArray);
echo "<br/>";
echo $myArray;
//exit;
*/
date_default_timezone_set('Etc/UTC');

require '../PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = 'phe.pheservers.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 465;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'ssl';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "abdul.salam@pphisindh.org";

//Password to use for SMTP authentication
$mail->Password = "Pphi_123@";

//Set who the message is to be sent from
$mail->setFrom('abdul.salam@pphisindh.org', 'Abdul Salam');

//Set an alternative reply-to address
$mail->addReplyTo('abdul.salam@pphisindh.org', 'salam');

//Set who the message is to be sent to

//$mail->addAddress($_REQUEST['emails'], 'salam salam');

$addr = explode(',',$myString);

foreach ($addr as $ad) {
    $mail->AddAddress( trim($ad) );       
}

//$mail->AddCC('mukhtiar.lander@pphisindh.org', 'mukhtiar');
//$mail->AddCC('abdul.salam@pphisindh.org', 'salam');

//Set the subject line

$mail->Subject = $_REQUEST['subject'];

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($_REQUEST['message']);
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

//Replace the plain text body with one created manually
$mail->AltBody = $_REQUEST['message'];

//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
 	
	  $server="pphisindh.org";
        $database="pphisind_jobs_new";
     	$user="pphisind_salam";
		$password="Salam_123@";
         mysql_connect($server,$user,$password);
        $conn = mysql_connect($server,$user,$password);
        $connn = mysql_connect($server,$user,$password);
        
		mysql_select_db($database);
		
$sql ="update tbl_schedules set is_email='1' where id = '".$_REQUEST['sch_id']."'" ;	
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not update data: ' . mysql_error());
}

   echo "Message sent!";
	echo "<script> alert('your email has been sent to all'); window.location='../../schedule.php';</script>";

	
	
	//header('Location: ../../email_updated.php'); 
}
?>