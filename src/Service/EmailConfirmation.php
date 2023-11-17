<?php
namespace App\Service;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class EmailConfirmation

    {
        //ENVOI DU MAIL ET CONFIGURATION 
        //controller : methode confirm USER 


        private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
    }

    public function sendEmail($to, $subject, $body)
    {
        try {
            // Paramètres du serveur
            $this->mailer->isSMTP();
            $this->mailer->Host = 'cantonais.o2switch.net:465';  // Spécifiez le serveur SMTP
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = 'nepasrepondre@vdweb.fr'; // SMTP username
            $this->mailer->Password = 'SzALhHsmlOb5';           // SMTP password
            $this->mailer->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted
            $this->mailer->Port = 587;                    // Port TCP

            //Destinataires
            $this->mailer->setFrom('from@example.com', 'Mailer');
            $this->mailer->addAddress($to);               // Ajoute un destinataire

            // Contenu
            $this->mailer->isHTML(true);                                  // Set email format to HTML
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->AltBody = strip_tags($body);

            $this->mailer->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }
    }
}
 