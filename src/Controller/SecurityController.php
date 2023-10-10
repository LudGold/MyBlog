<?php

namespace App\Controller;

use Core\component\AbstractController;


class SecurityController extends AbstractController
{

    public function register()
    {

        if ($_POST) {
            var_dump($_POST);
            die();
            return $this->redirect("/register");
        }

        return $this->render("security/register.html.twig");
    }
}



