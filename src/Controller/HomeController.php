<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Repository\ArticleRepository;


class HomeController extends AbstractController
{

    public function home()
    {

        $articleController = new ArticleController();
        // On récupère les derniers articles
        $articles = $articleController->displayLatestArticles();
        
        // Rendre le template home avec les articles
        return $this->render("home/home.html.twig", [
            'articles' => $articles,
        ]);
    }
    public function param(int $id)
    {
        return $this->render("home/param.html.twig", ['id' => $id]);
    }
}
