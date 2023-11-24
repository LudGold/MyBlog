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

    private function generateConfirmationLink($registrationToken)
    {
        return '/confirmation/' .$registrationToken;
    }
}