<?php 
namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\Article;

class HomeController extends AbstractController{
    
    public function home()  {
        
        if ($_POST) {
            var_dump($_POST); die();
            return $this->redirect("/");
        }        
        // require_once TEMPLATE_DIR.'/home/home.html.twig';
        return $this->render("home/home.html.twig");
    }
    //public function param(int $id) 
    //{
        
    //$article= new Article($_POST);
        //return $this->render("home/param.html.twig",['id'=>$id]);
   // }
}
