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

    public function renderConfirmationEmail(string $registrationToken)
    {
        $template = $this->twig->load('security/mailConfirmation.html.twig');
        $variables = ['confirmation_link' => $this->generateConfirmationLink($registrationToken)];
        return $template->render($variables);
    }
    private function generateConfirmationLink(string $registrationToken)
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/confirmation/" . $registrationToken;

        return $url;
    }
    public function renderResetPasswordEmail(string $resetToken)
    {
        $template = $this->twig->load('security/resetPassword.html.twig');
        $variables = ['reset_link' => $this->generateResetLink($resetToken)];
        return $template->render($variables);
    }

    private function generateResetLink(string $resetToken)
    {
        // lien de réinitialisation de mot de passe
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/resetPassword/" . $resetToken;

        return $url;
    }

    public function renderCommentNotificationEmail()
    {

        $template = $this->twig->load('admin/comment/mailCommentNotification.html.twig');
        $variables = [
            'contactLink' => 'mailto:ludivinezarkos@gmail.com',
        ];

        return $template->render($variables);
    }
    public function renderContactEmail(string $name, string $email, string $message)
    {
        $htmlContent = "Vous avez reçu un nouveau message de contact de la part de M $name ($email): <br><br> $message";

        return $htmlContent;
    }
}
