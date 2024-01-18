<?php

namespace App\Controller\Admin;

use Core\component\AbstractController;
use App\Model\Entity\Comment;

use App\Model\Repository\CommentRepository;


class CommentsAdminController extends AbstractController
{

    public function showPendingComments()
    {
        $commentRepository = new CommentRepository();
        $pendingComments = $commentRepository->getPendingComments(); // Assurez-vous d'avoir une méthode dans le repository pour récupérer les commentaires en attente

        return $this->render("admin/comment/comments_pending.html.twig", [
            'pendingComments' => $pendingComments,
        ]);
    }
    public function showApprovedComments()
    {
        $commentRepository = new CommentRepository();
        $approvedComments = $commentRepository->getApprovedComments();

        return $this->render("admin/comment/comments_approved.html.twig", [
            'approvedComments' => $approvedComments,
        ]);
    }
    public function showRejectedComments()
    {
        $commentRepository = new CommentRepository();
        $rejectedComments = $commentRepository->getRejectedComments();

        return $this->render("admin/comment/comments_rejected.html.twig", [
            'rejectedComments' => $rejectedComments,
        ]);
    }
    public function rejectedComments()
    {
        // faire un service d'envoi de mail avec message "le commentaire ne correspond pas aux cgv du site"
    }

    public function showAllComments()
    {
        $commentRepository = new CommentRepository();
        $allComments = $commentRepository->getAllComments();

        return $this->render("admin/comment/index.html.twig", [
            'allComments' => $allComments,
        ]);
    }


    public function validateComment()
    {
        $this->isAdmin();

        // Récupérer la valeur de 'action' depuis $_POST
        $action = isset($_POST['action']) ? $_POST['action'] : null;

        if ($action === 'validate') {
            // Logique pour valider le commentaire
        } elseif ($action === 'reject') {
            // Logique pour rejeter le commentaire
        }
        $this->addFlash('success', 'Le commentaire a été validé avec succès.');
        return $this->redirect("/admin/comment/index");
    }
}
