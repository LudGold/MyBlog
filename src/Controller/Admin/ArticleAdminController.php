<?php

namespace App\Controller\Admin;

use Core\component\AbstractController;
use App\Model\Repository\ArticleRepository;
use App\Model\Repository\UserRepository;
use App\Model\Entity\Article;

class ArticleAdminController extends AbstractController
{


    // page articles avec les differents infos, icone stylo et icon trash à droite
    public function index()
    {
        $this->isAdmin();

        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->getAllArticles();

        return $this->render("admin/article/index.html.twig", ['articles' => $articles]);
    }

    public function newArticle()
    {
        $this->isAdmin();
        // Récupérer l'ID de l'utilisateur depuis la session
        $userId = $this->getSessionInfos("userId");
        $userRepository = new UserRepository();

        // Récupérer l'utilisateur par son ID
        $user = $userRepository->getUserBy('id', $userId);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            // Gérer le cas où l'utilisateur n'est pas trouvé
            // Peut-être rediriger l'utilisateur ou afficher un message d'erreur
            return $this->redirect("/");
        }

        // Récupérer le nom complet de l'utilisateur
        $userName = $user->getFullName();

        // Vérifier si le formulaire a été soumis
        if ($_POST) {
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

            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirect("/admin/newArticle");
        }

        // Si le formulaire n'a pas été soumis ou s'il y a une erreur, afficher le formulaire
        return $this->render("admin/article/newArticle.html.twig", [
            'userName' => $userName,
        ]);
    }

    //modification article - field title, chapo, auteur et content

    public function changeArticle(int $articleId)
    {
        $this->isAdmin();
        

        $articleRepository = new ArticleRepository();
        $article = $articleRepository->getArticleById($articleId);
        

        // Vérifier si l'article existe
        if (!$article) {
            // Gérer le cas où l'article n'est pas trouvé
            // Peut-être rediriger l'utilisateur ou afficher un message d'erreur
            return $this->redirect("/admin/articles");
        }

        // Vérifier si le formulaire a été soumis
        if ($_POST) {
            // Mettre à jour les champs de l'article avec les nouvelles données
            $article->setTitle($_POST['title']);
            $article->setChapo($_POST['chapo']);
            $article->setContent($_POST['content']);
            
            

            // Vous pouvez également mettre à jour l'auteur si nécessaire
            $article->setUserId($_POST['userId']);

            // Mettre à jour la date de modification
            $article->setUpdateDate(new \DateTime());
         

            // Enregistrer les modifications dans la base de données
            $articleRepository->changeArticle($article);
          

            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirect("/admin/articles");
        }

        // Si le formulaire n'a pas été soumis ou s'il y a une erreur, afficher le formulaire
        return $this->render("admin/article/changeArticle.html.twig", [
            'article' => $article,
        ]);
    }


    public function deleteArticle(int $articleId)
    {
        $articleRepository = new ArticleRepository();
        $articleRepository->deleteArticle($articleId);

        $this->addFlash('success', 'Article supprimé avec succès');
        return $this->redirect("/admin/articles");
    }
}
