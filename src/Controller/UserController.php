<?php

namespace App\Controller;
use Core\component\AbstractController;
use PDOException;
use PDO;
use App\Model\Entity\User;



class UserController extends AbstractController
{
    public function registerUser()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupérer les données du formulaire
            $userDatas = [
                'lastname' => $_POST["lastname"],
                'firstname' => $_POST["firstname"],
                'mail' => $_POST["mail"],
                'password' => $_POST["password"]
            ];

            // Créer un objet User avec les données du formulaire
            $user = new User();
            $user->setLastname($userDatas['lastname']);
            $user->setFirstname($userDatas['firstname']);
            $user->setMail($userDatas['mail']);
            $user->setPassword($userDatas['password']);

            // Connexion à la base de données
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=my_blog", "pseudo", "password");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Préparer et exécuter la requête SQL
                $sql = "INSERT INTO users (lastname, firstname, mail, password) VALUES (:lastname, :firstname, :mail, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':lastname' => $user->getLastname(),
                    ':firstname' => $user->getFirstname(),
                    ':mail' => $user->getMail(),
                    ':password' => $user->getPassword()
                ]);

                echo "Utilisateur enregistré avec succès dans la base de données.";
            } catch (PDOException $e) {
                echo "Erreur lors de l'enregistrement de l'utilisateur : " . $e->getMessage();
            }
        }
    }
}

// Utilisation du contrôleur pour enregistrer un utilisateur
$userController = new UserController();
$userController->registerUser();

