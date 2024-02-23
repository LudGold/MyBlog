<?php

namespace App\Service;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class EmailRenderer
{
    private $twig;


    public function __construct()
    {
        $this->twig = new Environment(new FilesystemLoader('../template'));
    }

    public function renderConfirmationEmail($registrationToken)
    {
        // Charge le template Twig
        $template = $this->twig->load('security/mailConfirmation.html.twig');

        // Variables à utiliser dans le template
        $variables = ['confirmation_link' => $this->generateConfirmationLink($registrationToken)];

        // Rend le template avec les variables
        return $template->render($variables);
    }
    private function generateConfirmationLink($registrationToken)
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/confirmation/" . $registrationToken;

        return $url;
    }
    public function renderResetPasswordEmail($resetToken)
    {
        $template = $this->twig->load('security/resetPassword.html.twig');
        $variables = ['reset_link' => $this->generateResetLink($resetToken)];
        return $template->render($variables);
    }

    private function generateResetLink($resetToken)
    {
        // Générer le lien de réinitialisation de mot de passe
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/resetPassword/" . $resetToken;


        return $url;
    }
    // Dans EmailRenderer
    public function renderCommentNotificationEmail()
    {
        // Charge le template Twig
        $template = $this->twig->load('admin/comment/mailCommentNotification.html.twig');

        $variables = [
            'contactLink' => 'mailto:ludivinezarkos@gmail.com',  // Remplacez l'adresse e-mail statique par celle de votre template Twig
        ];
        // Rend le template avec les variables
        return $template->render($variables);
    }
    public function renderContactEmail($name, $email, $message)
    {
        $htmlContent = "Vous avez reçu un nouveau message de contact de la part de $name ($email): <br><br> $message";
        $htmlContent .= "$message<br>";
        // Retournez directement le contenu personnalisé
        return $htmlContent;
    }
}
