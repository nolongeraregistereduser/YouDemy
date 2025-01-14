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
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Félicitations M/Mme {$teacher['firstname']} {$teacher['lastname']}!</h2>
                <p>Votre compte enseignant a été approuvé sur Youdemy.</p>
                <p>Vous pouvez maintenant:</p>
                <ul>
                    <li>Vous connecter à votre compte enseignant</li>
                    <li>Créer vos cours</li>
                    <li>Poster votre contenu</li>
                    <li>Interagir avec vos étudiants :)</li>
                </ul>
                <a href='http://localhost/Youdemy/auth.php' class='button'>

                    Se connecter maintenant
                </a>
                <p>Merci de faire partie de notre communauté Youdemy!!</p>
            </div>
        </body>
        </html>";
        
        return $this->mailer->send($teacher['email'], $subject, $message);
    }
} 