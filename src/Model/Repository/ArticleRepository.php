<?php


namespace App\Model\Repository;

use App\Model\Entity\Article;
use Core\Database\Database;
use PDOException;

class ArticleRepository
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
            $sql = "INSERT INTO article (title, chapo, date, updateDate, content) 
                    VALUES (:title, :chapo, :date, :updateDate, :content)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':title' => $article->getTitle(),
                ':chapo' => $article->getchapo(),
                ':creationDate' => $article->getDate()->format(self::DATE_FORMAT),
                ':content' => $article->getContent(),
                ':updateDate' => $article->getUpdateDate()->format(self::DATE_FORMAT),
            ]);
        } catch (PDOException $e) {
            echo "Erreur lors de l'enregistrement de l'article : " . $e->getMessage();
        }
    }

    public function getAllArticles()
    {
        $articles = [];

        try {
            $sql = "SELECT * FROM article ORDER BY id DESC";
            $stmt = $this->db->query($sql);
            $articles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            return $articles;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des articles : " . $e->getMessage();
        }
    }
}
