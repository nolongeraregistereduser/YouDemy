<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';  // Use Gmail SMTP
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'zouhairihlima@gmail.com'; 
            $this->mail->Password = 'yydk njrk zrzl vzow';    
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;
            $this->mail->CharSet = 'UTF-8';
            
            // Default sender
            $this->mail->setFrom('your-email@gmail.com', 'Youdemy');
            
        } catch (Exception $e) {
            throw new Exception('Mail configuration error: ' . $e->getMessage());
        }
    }
    
    public function send($to, $subject, $body) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            
            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
} 