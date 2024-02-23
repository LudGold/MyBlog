<?php

namespace App\Controller;

use Core\component\AbstractController;

class ErrorController extends AbstractController
{

    public function notFound()
    {
        return $this->render("errors/error404.html.twig");
    }
    public function notAuthorised()
    {
        return $this->render("errors/error403.html.twig");
    }
}
