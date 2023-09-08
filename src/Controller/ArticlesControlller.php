<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\Article;

class ArticlesController extends AbstractController
{

    public function articles()
    {

        if ($_POST) {
            var_dump($_POST);
            die();
            return $this->redirect("/articles");
        }
        // require_once TEMPLATE_DIR.'/home/home.html.twig';
        return $this->render("home/articles.html.twig");
    }
    public function param(int $id)
    {

        $article= new Article($_POST);
        return $this->render("home/articles.html.twig", ['id' => $id]);
    }
}
