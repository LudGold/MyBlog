<?php

namespace App\Controller\Admin;

use Core\component\AbstractController;
use App\Service\CommentHandler;
use App\Model\Repository\CommentRepository;
use App\Service\EmailRenderer; 
use PDOException;



class CommentsAdminController extends AbstractController
{
    private $commentHandler;

    public function __construct()
    {
        parent::__construct();
        // Initialisation 
        $emailRenderer = new EmailRenderer();
        $this->commentHandler = new CommentHandler(new CommentRepository(), $emailRenderer);
    }
    public function showPendingComments()
    {
        $pendingComments = $this->commentHandler->getPendingComments();
        return $this->render("admin/comment/comments_pending.html.twig", [
            'pendingComments' => $pendingComments,
        ]);
    }
    public function showApprovedComments()
    {
        $approvedComments = $this->commentHandler->getApprovedComments();
        return $this->render("admin/comment/comments_approved.html.twig", [
            'approvedComments' => $approvedComments,
        ]);
    }
    public function showRejectedComments()
    {
        $rejectedComments = $this->commentHandler->getRejectedComments();
        return $this->render("admin/comment/comments_rejected.html.twig", [
            'rejectedComments' => $rejectedComments,
        ]);
    }

    public function showAllComments()
    {
        $commentsWithStatus = $this->commentHandler->getAllCommentsWithStatus();
        return $this->render('admin/comment/index.html.twig', [
            'allComments' => $commentsWithStatus,
        ]);
    }

    public function checkedComment()
    {
        $this->isAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            [$type, $message] = $this->commentHandler->handleCommentAction($_POST['commentId'], $_POST['action']);
            $this->addFlash($type, $message);
        } else {
            $this->addFlash('error', 'Requête invalide.');
        }
        return $this->redirect("/admin/comment/index");
    }


}
