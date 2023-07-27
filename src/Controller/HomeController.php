<?php 
namespace App\Controller;

use Core\component\AbstractController;


class HomeController extends AbstractController{
    
    public function home()  {
        // require_once TEMPLATE_DIR.'/home/home.html.twig';
        return $this->render("home/home.html.twig");
    }
}
