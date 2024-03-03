<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\Comment;
use App\Model\Repository\ArticleRepository;
use App\Service\ArticleService;
use App\Model\Repository\CommentRepository;


class ArticleController extends AbstractController
{
    private $articleService;

    public function show(int $articleId)
    {
        $articleRepository = new ArticleRepository();
        $article = $articleRepository->getArticleById($articleId);
        // Récupérer les commentaires associés à l'article depuis le CommentRepository
        $commentRepository = new CommentRepository();
        $comments = $commentRepository->getCommentsByArticleId($articleId);
        if (!$article) {
            return $this->redirect("/articles");
        }
        return $this->render("article/show.html.twig", [
            'article' => $article,
            'comments' => $comments,
        ]);
    }

    public function submitComment(int $articleId)
    {
        if ($this->isSubmitted('submit') && $this->isValided($_POST)) {

            // Récupérer les données du formulaire
            $pseudo = $_POST['pseudo'];
            $mail = $_POST['mail'];
            $content = $_POST['content'];


            // Créer une instance de la classe Comment avec les données
            $commentData = [
                'pseudo' => $pseudo,
                'mail' => $mail,
                'content' => $content,
                'articleId' => $articleId,
            ];

            $commentRepository = new CommentRepository();
            $newComment = new Comment($commentData);

            // Enregistrer le commentaire dans la base de données
            $commentRepository->saveComment($newComment);

            // message de succès
            $this->addFlash('success', 'Commentaire ajouté avec succès. En attente d\'approbation.');
        }
        $this->redirect("/article/{$articleId}");
    }
    public function setArticleService(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function displayLatestArticles()
    {
        // Instanciation de ArticleRepository
        $articleRepository = new ArticleRepository();

        // Instanciation de ArticleService en passant ArticleRepository comme argument
        $articleService = new ArticleService($articleRepository);

        // Injection de ArticleService dans ArticleController
        $this->setArticleService($articleService);

        // Appel de la méthode findLatestArticles() du service ArticleService
        $articles = $this->articleService->getLatestArticles(3); // Limite de 4 articles

        return $this->render("home/home.html.twig", [
            'articles' => $articles,
        ]);
    }
    public function displayAllArticles()
    {
        // Exemple d'utilisation pour afficher tous les articles
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getAllArticles();
        return $this->render("article/index.html.twig", [
            'articles' => $articles,
        ]);
    }
}
