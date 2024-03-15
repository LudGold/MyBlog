<?php

namespace App\Controller;

use Core\component\AbstractController;
use  App\Controller\ArticleController;

class HomeController extends AbstractController
{
    public function home()
    {
        $articleController = new ArticleController();
        $articles = $articleController->displayLatestArticles();
        
        return $this->render("home/home.html.twig", [
            'articles' => $articles,
        ]);
    
}
}