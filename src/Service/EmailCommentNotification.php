<?php

namespace App\Service;

use Core\Config\ConfigMail;
use PHPMailer\PHPMailer\Exception;




class EmailCommentNotification extends ConfigMail
{

    public function __construct()
    {
        parent::__construct();
    }

    public function sendEmailComment($comment, $subject)
    {
        try {
    
            $htmlContent = $this->emailRenderer->renderCommentNotificationEmail();
            //Contenu
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlContent;
            $this->mailer->AltBody = strip_tags($htmlContent);

            //Destinataires
            $this->mailer->setFrom('nepasrepondre@vdweb.fr', 'myBlog');
            $this->mailer->addAddress($comment->getMail());

            $this->mailer->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }
    }
}
