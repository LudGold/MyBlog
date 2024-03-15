<?php

namespace App\Model\Repository;

use Core\component\AbstractController;
use App\Model\Entity\Comment;
use Core\Database\Database;
use PDOException;

class CommentRepository extends AbstractController
{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }
    public function saveComment(Comment $comment)
    {
        try {
            $sql = "INSERT INTO comment (content, pseudo, articleId, userId, mail, isPending, isApproved, isRejected) 
                    VALUES (:content, :pseudo, :articleId, :userId, :mail, :isPending, :isApproved, :isRejected)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':content' => $comment->getContent(),
                ':pseudo' => $comment->getPseudo(),
                ':articleId' => $comment->getArticleId(),
                ':userId' => $comment->getUserId(),
                ':mail' => $comment->getMail(),
                ':isPending' => true,
                ':isApproved' => false,
                ':isRejected' => false,
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur lors de l'enregistrement du commentaire : " . $e->getMessage();
            return null;
        }
    }
    public static function getCommentsByArticleId(int $articleId): array
    {
        try {
            $db = Database::connect();
            $sql = "SELECT * FROM comment WHERE articleId = :articleId";
            $stmt = $db->prepare($sql);
            $stmt->execute([':articleId' => $articleId]);
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Comment::class);
            $results = $stmt->fetchAll();

            return $results;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des commentaires : " . $e->getMessage();
            return [];
        }
    }
    public function deleteCommentById(int $commentId)
    {
        try {
            $sql = "DELETE FROM comment WHERE id = :commentId";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':commentId' => $commentId]);
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du commentaire : " . $e->getMessage();
        }
    }

    public function getCommentById(int $commentId): ?Comment
    {
        try {
            $db = Database::connect();
            $sql = "SELECT * FROM comment WHERE id = :commentId";
            $stmt = $db->prepare($sql);
            $stmt->execute([':commentId' => $commentId]);
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Comment::class);
            $comment = $stmt->fetch();
            return $comment;
        } catch (PDOException $e) {

            return null;
        }
    }

    public function getPendingComments()
    {
        try {
            $db = Database::connect();
            $sql = "SELECT * FROM comment WHERE isPending = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Comment::class);
            $pendingComments = $stmt->fetchAll();

            return $pendingComments;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des commentaires en attente : " . $e->getMessage();
            return [];
        }
    }

    public function getApprovedComments()
    {

        try {
            $db = Database::connect();
            $sql = "SELECT * FROM comment WHERE isApproved = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Comment::class);
            $approvedComments = $stmt->fetchAll();

            return $approvedComments;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des commentaires validés : " . $e->getMessage();
            return [];
        }
    }
    public function getRejectedComments()
    {
        try {
            $db = Database::connect();
            $sql = "SELECT * FROM comment WHERE isRejected = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Comment::class);
            $rejectedComments = $stmt->fetchAll();

            return $rejectedComments;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des commentaires rejetés : " . $e->getMessage();
            return [];
        }
    }

    public function validateComment($commentId)
    {
        try {
            $stmt = $this->db->prepare("UPDATE comment SET isPending = 0, isApproved = 1, isRejected = 0 WHERE id = :commentId");
            $stmt->bindParam(':commentId', $commentId, \PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
    
            return  'Erreur lors de la mise à jour de la base de données';
        }
    }
    public function rejectedComment($commentId)
    {
        try {
            $stmt = $this->db->prepare("UPDATE comment SET isPending = 0, isApproved = 0, isRejected = 1 WHERE id = :commentId");
            $stmt->bindParam(':commentId', $commentId, \PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {

            return  'Erreur lors de la mise à jour de la base de données';
        }
    }

    public function getAllComments()
    {
        try {
            $db = Database::connect();
            $sql = "SELECT * FROM comment";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Comment::class);
            $allComments = $stmt->fetchAll();

            return $allComments;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de tous les commentaires : " . $e->getMessage();
            return [];
        }
    }
}
