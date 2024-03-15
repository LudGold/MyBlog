<?php

namespace App\Model\Repository;

use Core\component\AbstractController;
use App\Model\Entity\Article;
use Core\Database\Database;
use PDOException;

class ArticleRepository extends AbstractController
{
    private const DATE_FORMAT = "Y-m-d H:i:s";
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function saveArticle(Article $article)
    {
        try {
            $sql = "INSERT INTO article (title, chapo, date, content, userId) 
                    VALUES (:title, :chapo, :date, :content, :userId)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':title' => $article->getTitle(),
                ':chapo' => $article->getchapo(),
                ':date' => $article->getDate()->format(self::DATE_FORMAT),
                ':content' => $article->getContent(),
                ':userId' => $article->getUserId(),
            ]);

            $this->addFlash('success', "Votre article a bien été publié");
        } catch (PDOException $e) {
            echo "Erreur lors de l'enregistrement de l'article : " . $e->getMessage();
        }
    }
    public static function getArticleById(int $articleId): ?Article
    {
        try {
            $db = Database::connect();
            $sql = "SELECT article.*, user.lastname, user.firstname FROM article INNER JOIN user ON article.userId = user.id  WHERE article.id = :articleId";
            $stmt = $db->prepare($sql);
            $stmt->execute([':articleId' => $articleId]);

            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "App\Model\Entity\Article");
            $result = $stmt->fetch();
            return $result;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'article : " . $e->getMessage();
            return null;
        }
    }
    public function findLatestArticles($limit = 3)
    {
        $articles = [];
        try {
            $sql = "SELECT * FROM article ORDER BY date DESC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $result) {
            $articles[] = new Article($result);
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des articles : " . $e->getMessage();
        }
        return $articles;
    }


    public function changeArticle(Article $article)
    {
        $updateDate = $article->getUpdateDate();
        try {
            $sql = "UPDATE article 
                SET userId = :userId,
                    title = :title, 
                    chapo = :chapo, 
                    updateDate = :updateDate, 
                    content = :content
                WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':userId' => $article->getUserId(),
                ':title' => $article->getTitle(),
                ':chapo' => $article->getChapo(),
                ':updateDate' => $updateDate->format(self::DATE_FORMAT),
                ':content' => $article->getContent(),
                ':id' => $article->getId(),
            ]);
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de l'article : " . $e->getMessage();
        }
    }

    public function deleteArticle(int $articleId)
    {
        try {
            $this->db->beginTransaction();
            // Suppression des commentaires associés à l'article
            $sql = "DELETE FROM comment WHERE articleId = :articleId";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':articleId' => $articleId]);

            $sql = "DELETE FROM article WHERE id = :articleId";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':articleId' => $articleId]);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Erreur lors de la suppression de l'article et de ses commentaires : " . $e->getMessage();
        }
    }

    public function getAllArticles()
    {
        $articles = [];
        try {
            $sql = "SELECT * FROM article ORDER BY date DESC";
            $stmt = $this->db->query($sql);
            $articles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $articles;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des articles : " . $e->getMessage();
        }
    }
}
