<?php
namespace App\Controller;

use Core\component\AbstractController;

class LegalController extends AbstractController
{
    public function mentionsLegales()
    {
        // Afficher la vue avec les mentions légales
        return  $this->render("security/mentionsLegales.html.twig");
    }
}

