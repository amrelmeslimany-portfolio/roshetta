<?php
Use PHPMailer\PHPMailer\PHPMailer;
Use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

$mail = new PHPMailer();

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                  
$mail->isSMTP();                           
$mail->Host       = 'smtp.gmail.com';                          
$mail->SMTPAuth   = true;  
$mail->Username   = 'roshettateam@gmail.com';                         
$mail->Password   = 'lprcobbzrycqpzvu';              
$mail->SMTPSecure = 'ssl';           
$mail->Port       = 465;                                     
$mail->isHTML(true);        
$mail->CharSet = "UTF-8"; 
?>