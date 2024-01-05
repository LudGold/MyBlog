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
            $sql = "INSERT INTO article (title, chapo, date, content, user_id) 
                    VALUES (:title, :chapo, :date, :content, :user_id )";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':title' => $article->getTitle(),
                ':chapo' => $article->getchapo(),
                ':date' => $article->getDate()->format(self::DATE_FORMAT),
                ':content' => $article->getContent(),
                //pour le moment pas besoin puisque creation article
                // ':updateDate' => $article->getUpdateDate()->format(self::DATE_FORMAT),
                ':user_id' => $article->getUserId(),
            ]);
            $this->addFlash('success', "Votre article a bien été publié");
            
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
