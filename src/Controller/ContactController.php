<?php

namespace App\Controller;

use Core\component\AbstractController;

class ContactController extends AbstractController{

    public function contact()
    {
        if ($_POST){

        }
        return $this->render("contact/reachMe.html.twig");
    }


}