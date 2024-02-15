<?php

namespace App\Model\Repository;

use App\Model\Entity\User;
use Core\Database\Database;
use PDOException;


class UserRepository
{
    private const DATE_FORMAT = "Y-m-d H:i:s";
    private $db;
    //méthode dans laquelle tu mets la connexion et le fichier qui appelle la bdd 
    // Connexion à la base de données

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function saveUser(User $user)
    {
        try {
            $role =  json_encode($user->getRoles());
            $mail = $user->getMail();

            // Vérifier si l'utilisateur existe déjà dans la base de données
            $existingUser = $this->getUserBy('registrationToken', $user->getRegistrationToken());

            if ($existingUser) {
                // L'utilisateur existe déjà, mettre à jour le statut isConfirmed
                $sql = "UPDATE user 
                    SET isConfirmed = :isConfirmed, registrationToken= :registrationToken 
                    WHERE mail = :mail";

                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':isConfirmed' => $user->getIsConfirmed(),
                    ':mail' => $mail,
                    ':registrationToken' => null,

                ]);
            } else {

                // Préparer et exécuter la requête SQL
                $sql = "INSERT INTO user (lastname ,firstname ,mail ,password ,role ,creationDate ,isConfirmed ,registrationToken) 
                        VALUES (:lastname, :firstname,:mail,:password ,:role ,:creationDate,:isConfirmed, :registrationToken)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':lastname' => $user->getLastname(),
                    ':firstname' => $user->getFirstname(),
                    ':mail' => $user->getMail(),
                    ':password' => password_hash($user->getPassword(), PASSWORD_BCRYPT),
                    ':role' => $role,
                    ':creationDate' => $user->getCreationDate()->format(self::DATE_FORMAT),
                    ':isConfirmed' => $user->getIsConfirmed(),
                    ':registrationToken' => $user->getRegistrationToken(),

                ]);
            }
        } catch (PDOException $e) {

            echo "Erreur lors de l'enregistrement de l'utilisateur : " . $e->getMessage();
        }
    }

    public function updateResetToken(User $user)
    {
        try {
            $sql = "UPDATE user 
                SET resetToken = :resetToken
                WHERE mail = :mail";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':resetToken' => $user->getResetToken(),
                ':mail' => $user->getMail(),
            ]);
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du Token : " . $e->getMessage();
        }
    }
    public function updatePassword($mail, $hashedPassword)
    {
        try {
            $sql = "UPDATE user 
                SET password = :password
                WHERE mail = :mail";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':password' => $hashedPassword,
                ':mail' => $mail,
            ]);
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du mot de passe : " . $e->getMessage();
        }
    }
    public function getUserBy($propertyName, $propertyValue)
    {
        try {
            $sql = "SELECT * FROM user WHERE $propertyName = :propertyValue";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':propertyValue' => $propertyValue]);

            // Vérifier si la requête a réussi
            if ($stmt->rowCount() > 0) {
                $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "App\Model\Entity\User");
                $userData = $stmt->fetch(); // Utilisation de fetch pour récupérer un seul utilisateur
                return $userData;
            } else {
                // Aucun utilisateur trouvé avec la propriété spécifiée
                return null;
            }
        } catch (PDOException $e) {

            throw new \PDOException("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }

    public function editBddProfil(User $user)
    {
        try {
            $sql = "UPDATE user 
                SET lastname = :lastname, firstname = :firstname, mail = :mail
                WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $user->getId(),
                ':lastname' => $user->getLastname(),
                ':firstname' => $user->getFirstname(),
                ':mail' => $user->getMail(),
            ]);
        
            // Si un nouveau mot de passe est fourni
            if (!empty($_POST['new_password'])) {

                $passwordSql = "UPDATE user SET password = :password WHERE id = :id";
                $passwordStmt = $this->db->prepare($passwordSql);
                $passwordStmt->execute([
                    ':id' => $user->getId(),
                    ':password' => $user->getPassword(), // Assurez-vous que ceci est déjà haché
                ]);
            }
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur

            echo "Erreur lors de la mise à jour du profil : " . $e->getMessage();
        }
    }


    //function getUser, allUser, deleteUser, editUserRole
    public function getAllUsers()
    {
        $users = []; // Initialisation du tableau vide pour stocker tous les utilisateurs

        try {
            $sql = "SELECT id, lastname, firstname, mail, password, role FROM user WHERE isConfirmed = 1";
            $stmt = $this->db->query($sql);
            $users = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Récupére tous les utilisateurs sous forme d'un tableau associatif
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
        }

        return $users;
    }
}



    // public function editRoleUser($role)
    // {
