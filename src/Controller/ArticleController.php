<?php

namespace App\Controller;

use Core\component\AbstractController;


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


        // public function param()
        //  {

        // //    $article= new Article($_POST);
        //    return $this->render("article/articles.html.twig");
        // }

    }
}
