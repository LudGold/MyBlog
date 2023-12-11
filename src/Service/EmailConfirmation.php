<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class EmailConfirmation
{

    private $mailer;
    private $emailRenderer;
    

    public function __construct(EmailRenderer $emailRenderer)
    {
        $this->mailer = new PHPMailer(true);
        $this->emailRenderer = $emailRenderer;
       
    }

    public function sendEmail($user, $subject, $registrationToken)
    {
        try {
            // Paramètres du serveur
            $this->mailer->isSMTP();
            $this->mailer->Host = 'cantonais.o2switch.net';  // Spécifiez le serveur SMTP
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = 'nepasrepondre@vdweb.fr'; // SMTP username
            $this->mailer->Password = 'SzALhHsmlOb5';           // SMTP password
            $this->mailer->SMTPSecure = 'ssl';            // Enable TLS encryption, `ssl` also accepted
            $this->mailer->Port = 465;                    // Port TCP
    
            // Class emailRenderer utilisée pour obtenir html
            $htmlContent = $this->emailRenderer->renderConfirmationEmail($registrationToken);
             //Contenu
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlContent;
            $this->mailer->AltBody = strip_tags($htmlContent);

            //Destinataires
            $this->mailer->setFrom('nepasrepondre@vdweb.fr', 'myBlog');     
            $this->mailer->addAddress($user->getMail());

            $this->mailer->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }
    }
    public function sendResetEmail($user, $subject, $resetToken)
    {
        try {
            // Paramètres du serveur
            $this->mailer->isSMTP();
            $this->mailer->Host = 'cantonais.o2switch.net';  // Spécifiez le serveur SMTP
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = 'nepasrepondre@vdweb.fr'; // SMTP username
            $this->mailer->Password = 'SzALhHsmlOb5';           // SMTP password
            $this->mailer->SMTPSecure = 'ssl';            // Enable TLS encryption, `ssl` also accepted
            $this->mailer->Port = 465;  

            // Class emailRenderer utilisée pour obtenir html
            $htmlContent = $this->emailRenderer->renderResetPasswordEmail($resetToken);
            
            
            //Contenu
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlContent;
            $this->mailer->AltBody = strip_tags($htmlContent);

            //Destinataires
            $this->mailer->setFrom('nepasrepondre@vdweb.fr', 'myBlog');     
            $this->mailer->addAddress($user->getMail());

            $this->mailer->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }
    }
}
