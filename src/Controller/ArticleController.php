<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\Article;
use App\Model\Repository\ArticleRepository;


class ArticleController extends AbstractController
{

    public function article()
    {

        if ($_POST) {

            return $this->redirect("/articles");
        }
        // require_once TEMPLATE_DIR.'/home/home.html.twig';
        return $this->render("article/index.html.twig");
    }
    public function show()
    {
        if ($_POST) {
            return $this->redirect("/article");
        }
        // require_once TEMPLATE_DIR.'/home/home.html.twig';
        return $this->render("article/show.html.twig");
    }
    public function createArticle()
    {
      
        $articleRepository = new ArticleRepository();
        $newArticle = new Article();
        $articleRepository->saveArticle($newArticle);
    }

    public function displayAllArticles()
    {
        // Exemple d'utilisation pour afficher tous les articles
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getAllArticles();
        return $this->render("admin/article/index.html.twig");
        
    }

   

    };

