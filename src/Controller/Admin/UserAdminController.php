<?php

namespace App\Controller\Admin;

use App\Model\Repository\ArticleRepository;
use App\Model\Repository\CommentRepository;
use Core\component\AbstractController;
use App\Model\Repository\UserRepository;
use App\Service\UserService;


class UserAdminController extends AbstractController
{
    public function adminDashboard()
    {
        if ($this->isAdmin()) {
            // Récupérez les informations des utilisateurs depuis la base de données
            $userRepository = new UserRepository();
            $users = $userRepository->getAllUsers();
            $commentRepository = new CommentRepository();
            $comments = $commentRepository->getPendingComments();
            $articleRepository = new ArticleRepository();
            $articles = $articleRepository->getAllArticles();

            return $this->render('admin/adminDashboard.html.twig', [
                'users' => $users,
                'allComments' => $comments,
                'articles' => $articles,
            ]);

            return $this->redirect('/not-authorised');
        }
    }

    public function updateUserRole()
    {
        $this->isAdmin();

        if ($this->isSubmitted("submit") && $this->isValided($_POST)) {

            $userId = $_POST['user_id'];
            $newRole = $_POST['role'];
            if (is_string($newRole)) {
                $newRole = [$newRole];
            }
            $userRepository = new UserRepository();
            $userService = new UserService($userRepository);
            if ($userService->updateUserRole($userId, $newRole)) {
                $this->addFlash('success', 'Le rôle de l\'utilisateur a été mis à jour avec succès.');
            } else {
                $this->addFlash('error', 'Impossible de mettre à jour le rôle de l\'utilisateur.');
            }
            return $this->redirect('/admin/user/addRole');
        }
        $userRepository = new UserRepository();
        $users = $userRepository->getAllUsers();

        return $this->render('admin/user/addRole.html.twig', ['users' => $users]);
    }
}
