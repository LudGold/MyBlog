<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\Article;
use App\Model\Repository\ArticleRepository;


class ArticleController extends AbstractController
{

    public function articles()
    {
        if ($_POST) {
            return $this->redirect("/articles");
        }
        // require_once TEMPLATE_DIR.'/home/home.html.twig';
        return $this->render("article/index.html.twig");
    }
    
    public function show(int $articleId)
{
    $articleRepository = new ArticleRepository();
    $article = $articleRepository->getArticleById($articleId);

    if (!$article) {
        // Gérer le cas où l'article n'est pas trouvé
        // Peut-être rediriger l'utilisateur ou afficher un message d'erreur
        return $this->redirect("/articles");
    }

    return $this->render("article/show.html.twig", [
        'article' => $article,
    ]);
}

    public function displayAllArticles()
    {
        // Exemple d'utilisation pour afficher tous les articles
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getAllArticles();
        return $this->render("article/index.html.twig");
    }
};
