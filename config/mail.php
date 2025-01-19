<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail {
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        //Server settings
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com'; // Modifier selon votre serveur SMTP
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'zouhairihlima@gmail.com'; // Votre email
        $this->mail->Password   = 'kewd mymh axzx afdu'; // Votre mot de passe d'application
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587;
        $this->mail->CharSet    = 'UTF-8';
    }
    
    public function send($to, $subject, $message) {
        try {
            //Recipients
            $this->mail->setFrom('votre_email@gmail.com', 'Youdemy');
            $this->mail->addAddress($to);

            //Content
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $message;

            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
} 