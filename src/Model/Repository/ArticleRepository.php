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
    // Dans la classe Article

    public static function getArticleById(int $articleId): ?Article
    {
        // Assurez-vous d'adapter ces lignes en fonction de votre système de stockage de données

        try {
            $db = Database::connect(); // Assurez-vous d'avoir la méthode connect dans votre classe Database

            $sql = "SELECT * FROM article WHERE id = :articleId";
            $stmt = $db->prepare($sql);
            $stmt->execute([':articleId' => $articleId]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result) {
                // Créez une instance d'Article avec les données récupérées
                $article = new Article($result);
                return $article;
            }

            return null; // Retourne null si l'article n'est pas trouvé
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'article : " . $e->getMessage();
            return null;
        }
    }

    public function changeArticle(Article $article)
    {
        try {
            $sql = "UPDATE article 
                SET title = :title, 
                    chapo = :chapo, 
                    content = :content, 
                    user_id = :user_id, 
                    update_date = :update_date 
                WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $article->getId(),
                ':title' => $article->getTitle(),
                ':chapo' => $article->getChapo(),
                ':content' => $article->getContent(),
                ':user_id' => $article->getUserId(),
                ':update_date' => $article->getUpdateDate()->format(self::DATE_FORMAT),
            ]);

            $this->addFlash('success', "Article mis à jour avec succès");
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de l'article : " . $e->getMessage();
        }
    }

    public function deleteArticle(int $articleId)
    {
        try {
            $sql = "DELETE FROM article WHERE id = :articleId";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':articleId' => $articleId]);
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'article : " . $e->getMessage();
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
