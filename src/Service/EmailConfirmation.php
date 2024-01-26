<?php

namespace App\Service;

use Core\Config\ConfigMail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class EmailConfirmation extends ConfigMail
{


    public function __construct(EmailRenderer $emailRenderer)
    {
        $this->mailer = new PHPMailer(true);
        $this->emailRenderer = $emailRenderer;
    }

    public function sendEmail($user, $subject, $registrationToken)
    {
        try {

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
