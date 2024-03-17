<?php

namespace App\Controller\Admin;

use Core\component\AbstractController;

use App\Model\Repository\ArticleRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\CommentRepository;
use App\Model\Entity\Article;

class ArticleAdminController extends AbstractController
{
    public function index(): void
    {
        $this->isAdmin();

        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getAllArticles();

        $this->render("admin/article/index.html.twig", ['articles' => $articles]);
    }

    public function newArticle()
    {
        $this->isAdmin();
        // Récupérer l'ID de l'utilisateur depuis la session
        $userId = $this->getSessionInfos("userId");
        $userRepository = new UserRepository();
        $user = $userRepository->getUserBy('id', $userId);
    
        // Récupérer le nom complet de l'utilisateur
        $userName = $user->getFullName();
        // Vérifier si le formulaire a été soumis
        if ($this->isSubmitted('submit') && $this->isValided($_POST)) {

            // Créer un tableau de données pour le nouvel article
            $articleData = [
                'title' => $_POST['title'],
                'chapo' => $_POST['chapo'],
                'content' => $_POST['content'],
                'userId' => $userId,
            ];
            // Créer une instance de la classe Article avec les données
            $newArticle = new Article($articleData);
            // Enregistrer l'article dans la base de données
            $articleRepository = new ArticleRepository();
            $articleRepository->saveArticle($newArticle);
        
            return $this->redirect("/admin/newArticle");
        }
        return $this->render("admin/article/newArticle.html.twig", [
            'userName' => $userName,
        ]);
    }

    public function changeArticle(int $articleId): void
    {
        $this->isAdmin();
        $userId = $this->getSessionInfos("userId");
        $userRepository = new UserRepository();

        // Récupérer l'utilisateur par son ID
        $user = $userRepository->getUserBy('id', $userId);

        $articleRepository = new ArticleRepository();
        $article = $articleRepository->getArticleById($articleId);

        // Vérifier si l'article existe
        if (!$article) {
            $this->redirect("/admin/articles");
        }
        // Vérifier si le formulaire a été soumis
        if ($this->isSubmitted('submit') && $this->isValided($_POST)) {
            // Mettre à jour les champs de l'article avec les nouvelles données
            $article->setTitle($_POST['title']);
            $article->setChapo($_POST['chapo']);
            $article->setContent($_POST['content']);
            $article->setUserId($userId);
            // Mettre à jour la date de modification
            $article->setUpdateDate(new \DateTime());
            // Enregistrer les modifications dans la base de données
            $articleRepository->changeArticle($article);
            $this->addFlash('success', 'cet article a été modifié avec succès');
            $this->redirect("/admin/articles");
        }
        $this->render("admin/article/changeArticle.html.twig", [
            'article' => $article,
        ]);
    }

    public function deleteArticle(int $articleId): void
    {
        $this->isAdmin();
        $commentRepository = new CommentRepository();

        // Récupère tous les commentaires liés à l'article
        $comments = $commentRepository::getCommentsByArticleId($articleId);

        // Supprime tous les commentaires associés à l'article
        foreach ($comments as $comment) {
            $commentRepository->deleteCommentById($comment->getId());
        }
        $articleRepository = new ArticleRepository();
        $articleRepository->deleteArticle($articleId);

        $this->addFlash('success', 'Article supprimé avec succès');
        $this->redirect("/admin/articles");
    }
}
