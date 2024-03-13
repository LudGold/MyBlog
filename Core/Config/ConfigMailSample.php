<?php

namespace Core\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Service\EmailRenderer;

class ConfigMailSample
{
    protected $mailer;
    protected $emailRenderer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->emailRenderer =  new EmailRenderer();

        // Paramètres du serveur pour envoi de mails

        $this->mailer->isSMTP();
        $this->mailer->Host = '';  // Spécifiez le serveur SMTP
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = ''; // SMTP username
        $this->mailer->Password = '';           // SMTP password
        $this->mailer->SMTPSecure = 'ssl';            // Enable TLS encryption, `ssl` also accepted
        $this->mailer->Port = 465;                    // Port TCP
        $this->mailer->CharSet = 'UTF-8';
    }
    protected function sendGenericEmail($to, $subject, $htmlContent)
    {
        try {
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlContent;
            $this->mailer->AltBody = strip_tags($htmlContent);

            $this->mailer->setFrom('myBlog@myBlog.fr');
            $this->mailer->addAddress($to);

            $this->mailer->send();
            // Retourne true en cas de succès
            return true;
        } catch (Exception $e) {
            // Log l'erreur ou la gère selon les besoins de l'application
            return false;
        }
    }
}
