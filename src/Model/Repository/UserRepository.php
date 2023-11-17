<?php

namespace App\Model\Repository;

use App\Model\Entity\User;
use Core\Database\Database;
use PDOException;


class UserRepository
{
    private const DATE_FORMAT = "Y-m-d H:i:s";
    private $db;
    //méthode dans laquelle tu mets la connexion et le fichier qui appelle la bdd, 
    //et enregistrer 
    // Connexion à la base de données

    public function __construct()
    {
        $this->db = Database::connect();

    }

    public function saveUser(User $user)
    {
        //debug par ici
        try {
            $role =  json_encode($user->getRoles());
            // Préparer et exécuter la requête SQL
            $sql = "INSERT INTO user (lastname ,firstname ,mail ,password ,role ,creationDate ,isConfirmed ,registrationToken) VALUES (:lastname, :firstname,:mail,:password ,:role ,:creationDate,:isConfirmed, :registrationToken)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':lastname' => $user->getLastname(),
                ':firstname' => $user->getFirstname(),
                ':mail' => $user->getMail(),
                ':password' => password_hash($user->getPassword(), PASSWORD_BCRYPT),
                ':role' => $role,
                ':creationDate' => $user->getCreationDate()->format(self::DATE_FORMAT),
                ':isConfirmed' => $user->getIsConfirmed(),
                ':registrationToken' => $user->getRegistrationToken()

            ]);
           
        } catch (PDOException $e) {

            echo "Erreur lors de l'enregistrement de l'utilisateur : " . $e->getMessage();
        }
    }

    public function getUser($mail)
    {
        try {
            $sql = "SELECT * FROM user WHERE mail = :mail";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':mail' => $mail]);
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "App\Model\Entity\User");
            $userData = $stmt->fetch(); // Utilisation de fetch pour récupérer un seul utilisateur

        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
        }

        return $userData;
    }
    //function getUser, allUser, deleteUser, editUserRole
    public function getAllUsers()
    {
        $users = []; // Initialisation du tableau vide pour stocker tous les utilisateurs

        try {
            $sql = "SELECT id, lastname, firstname, mail, password, role FROM user";
            $stmt = $this->db->query($sql);
            $users = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Récupére tous les utilisateurs sous forme d'un tableau associatif
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
        }

        return $users;
    }
    // public function deleteUser($id)
    // {
    //     try {
    //         $sql = "DELETE FROM user WHERE id = :id";
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute([':id' => $id]);
    //         echo "L'utilisateur avec l'ID $id a été supprimé avec succès de la base de données.";
    //     } catch (PDOException $e) {
    //         echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
    //     }
}
    // public function editRoleUser($role)
    // {
        
    // }
//function findBy et CustomUserMessageAuthenticationException