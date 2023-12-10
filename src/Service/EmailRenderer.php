<?php
namespace App\Service;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;



class EmailRenderer
{
    private $twig;
    

    public function __construct()
    {
        $this->twig = new Environment(new FilesystemLoader('../template/security'));
    }
    

    public function renderConfirmationEmail($registrationToken)
    {
        // Charge le template Twig
        $template = $this->twig->load('mailConfirmation.html.twig');

        // Variables à utiliser dans le template
        $variables = ['confirmation_link' => $this->generateConfirmationLink($registrationToken)];

        // Rend le template avec les variables
        return $template->render($variables);
    }
  public function renderResetPasswordEmail($resetToken)
    {
        $template = $this->twig->load('resetPassword.html.twig');
        $variables = ['reset_link' => $this->generateResetPasswordLink($resetToken)];
        return $template->render($variables);
    }
    private function generateConfirmationLink($registrationToken)
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/confirmation/" . $registrationToken;

      return $url;
    
        
    }
    private function generateResetPasswordLink($resetToken)
    {
        // Générer le lien de réinitialisation de mot de passe
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/resetPassword/" . $resetToken;

     
        return $url;
    }
}