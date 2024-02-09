<?php
namespace App\Service;

use App\Model\Repository\CommentRepository;
use App\Service\EmailCommentNotification;

class CommentHandler
{
    private $commentRepository;
    private $emailRenderer; 

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getPendingComments()
    {
        return $this->commentRepository->getPendingComments();
    }

    public function getApprovedComments()
    {
        return $this->commentRepository->getApprovedComments();
    }

    public function getRejectedComments()
    {
        return $this->commentRepository->getRejectedComments();
    }

    public function getAllCommentsWithStatus()
    {
        $allComments = $this->commentRepository->getAllComments();
        $commentsWithStatus = [];

        foreach ($allComments as $comment) {
            $commentData = [
                'date' => $comment->getDate(),
                'content' => $comment->getContent(),
                'pseudo' => $comment->getPseudo(),
                'mail' => $comment->getMail(),
                'status' => $comment->getStatus(),
            ];
            $commentsWithStatus[] = $commentData;
        }

        return $commentsWithStatus;
    }

    public function handleCommentAction($commentId, $action)
    {
        $comment = $this->commentRepository->getCommentById($commentId);

        if ($action === 'validate') {
            $this->commentRepository->validateComment($commentId);
            return ['success', 'Le commentaire a été validé avec succès.'];
        } elseif ($action === 'reject') {
            $this->commentRepository->rejectedComment($commentId);
            $subject = 'Votre commentaire a été refusé';
            $emailCommentNotification = new EmailCommentNotification($this->emailRenderer);
            $emailCommentNotification->sendEmailComment($comment, $subject);
            return ['success', 'Le commentaire a été rejeté, un e-mail a été envoyé à l\'utilisateur'];
        } else {
            return ['error', 'ID du commentaire ou action non spécifiée.'];
        }
    }
}
