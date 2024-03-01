<?php

namespace App\Controller;

use Core\component\AbstractController;
use App\Model\Entity\Comment;
use App\Model\Repository\ArticleRepository;
use App\Model\Repository\CommentRepository;


class ArticleController extends AbstractController
{
    public function displayAllArticles()
    {
        // Exemple d'utilisation pour afficher tous les articles
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getAllArticles();
        return $this->render("article/index.html.twig", [
            'articles' => $articles,
        ]);
    }
    public function displayLatestArticles()
    {
      
        $articleRepository = new ArticleRepository();
        $limit = 4;
        $articles = $articleRepository->findLatestArticles($limit);
        return $articles;
        
    }

    public function show(int $articleId)
    {
        $articleRepository = new ArticleRepository();
        $article = $articleRepository->getArticleById($articleId);
        // Récupérer les commentaires associés à l'article depuis le CommentRepository
        $commentRepository = new CommentRepository();
        $comments = $commentRepository->getCommentsByArticleId($articleId);
        if (!$article) {
            // Gérer le cas où l'article n'est pas trouvé
            // Peut-être rediriger l'utilisateur ou afficher un message d'erreur
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

}
