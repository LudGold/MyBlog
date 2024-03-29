<?php

namespace App\Model\Repository;

use App\Model\Entity\User;
use Core\Database\Database;
use PDOException;

class UserRepository
{
    private const DATE_FORMAT = "Y-m-d H:i:s";
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function saveUser(User $user)
    {
        try {
            $mail = $user->getMail();
            $existingUser = $this->getUserBy('registrationToken', $user->getRegistrationToken());

            if ($existingUser) {
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
                $sql = "INSERT INTO user (lastname ,firstname ,mail ,password ,role ,creationDate ,isConfirmed ,registrationToken) 
                        VALUES (:lastname, :firstname,:mail,:password ,:role ,:creationDate,:isConfirmed, :registrationToken)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':lastname' => $user->getLastname(),
                    ':firstname' => $user->getFirstname(),
                    ':mail' => $user->getMail(),
                    ':password' => password_hash($user->getPassword(), PASSWORD_BCRYPT),
                    ':role' => json_encode('[member]'),
                    ':creationDate' => $user->getCreationDate()->format(self::DATE_FORMAT),
                    ':isConfirmed' => 0,
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

            if ($stmt->rowCount() > 0) {
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                $userData = $stmt->fetch(); // Utilisation de fetch pour récupérer un seul utilisateur

                if ($userData) {
                    $userData['creationDate'] = new \DateTime($userData['creationDate']);
                    $userData['role'] = json_decode($userData['role'], true);

                    return new User($userData);
                }
            } else {

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

            if (!empty($_POST['new_password'])) {

                $passwordSql = "UPDATE user SET password = :password WHERE id = :id";
                $passwordStmt = $this->db->prepare($passwordSql);
                $passwordStmt->execute([
                    ':id' => $user->getId(),
                    ':password' => $user->getPassword(),
                ]);
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du profil : " . $e->getMessage();
        }
    }
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
    public function updateUserRole(int $userId, array $newRole)
    {
        try {
            $sql = "UPDATE user SET role = :role WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':role' => json_encode($newRole),
                ':id' => $userId,
            ]);
            return true;
        } catch (PDOException $e) {
            // Gérer l'exception si nécessaire
            echo "Erreur lors de la mise à jour du rôle de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
}
