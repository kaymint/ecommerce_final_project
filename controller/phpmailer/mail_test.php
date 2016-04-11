<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->From = "kenneth.mensah62@gmail.com";
$mail->FromName = "Kenneth Mensah";

$mail->addAddress("kenneth.mensah62@gmail.com", "Kenneth Mintah Mensah");

//Provide file path and name of the attachments
//$mail->addAttachment("file.txt", "File.txt");
//$mail->addAttachment("../../customer_view/images/banner.png"); //Filename is optional

$mail->isHTML(true);

$mail->Subject = "Testing";
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send())
{
    echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
    echo "Message has been sent successfully";
}