<?php

namespace App\Controller;

use Core\component\AbstractController;


class HomeController extends AbstractController
{

    public function home()
    {

        if ($_POST) {

            return $this->redirect("/");
        }
        // require_once TEMPLATE_DIR.'/home/home.html.twig';
        return $this->render("home/home.html.twig");
    }
    public function param(int $id)
    {

        return $this->render("home/param.html.twig", ['id' => $id]);
    }
}
