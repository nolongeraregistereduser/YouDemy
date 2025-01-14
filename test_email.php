<?php
require 'vendor/autoload.php';
require 'config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

try {
    $mail = new PHPMailer(true);

    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'zouhairihlima@gmail.com';                     //SMTP username
    $mail->Password   = 'yydk njrk zrzl vzow';                    // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        // Enable TLS encryption
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('zouhairihlima@gmail.com', 'Youdemy');
    $mail->addAddress('itsmezouhairi@gmail.com');                 // Add a recipient

    //Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'Test Email from Youdemy';
    $mail->Body    = 'This is a test email from Youdemy. If you receive this, the email system is working!';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
} 