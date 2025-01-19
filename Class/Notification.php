<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail.php';

class Notification {
    private $conn;
    private $mailer;
    
    public function __construct($db) {
        $this->conn = $db;
        $this->mailer = new Mail();
    }
    
    public function sendApprovalEmail($teacher) {
        $subject = "Compte enseignant approuvé - Youdemy";
        
        $message = "
        <html>
        <head>
            <title>Compte enseignant approuvé sur Youdemy</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .button { 
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #3498db;
                    color: white !important;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 20px 0;
                }
                .footer { margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Félicitations {$teacher['firstname']} {$teacher['lastname']}!</h2>
                <p>Votre compte enseignant a été approuvé sur Youdemy.</p>
                <p>Vous pouvez maintenant:</p>
                <ul>
                    <li>Créer et gérer vos cours</li>
                    <li>Interagir avec vos étudiants</li>
                    <li>Accéder à votre tableau de bord</li>
                </ul>
                <a href='http://{$_SERVER['HTTP_HOST']}/Youdemy/auth.php' class='button'>
                    Se connecter maintenant
                </a>
                <p>Merci de faire partie de notre communauté Youdemy!</p>
                <div class='footer'>
                    <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                </div>
            </div>
        </body>
        </html>";
        
        try {
            return $this->mailer->send($teacher['email'], $subject, $message);
        } catch (Exception $e) {
            error_log("Error sending approval email: " . $e->getMessage());
            return false;
        }
    }
} 