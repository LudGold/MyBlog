<?php
namespace App\Model\Repository;

use App\Model\Entity\User;
use Core\Database\Database;
use PDOException;


class UserRepository {
private $db;
//méthode dans laquelle tu mets la connexion et le fichier qui appelle la bdd, 
//et enregistrer 
// Connexion à la base de données

public function __construct(){
    $this->db = Database::connect();
}

public function saveUser(User $user){
//debug par ici
try {
    $role=  json_encode($user->getRoles());
    // Préparer et exécuter la requête SQL
    $sql = "INSERT INTO user (lastname, firstname, mail, password, role) VALUES (:lastname, :firstname, :mail, :password, :role)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':lastname' => $user->getLastname(),
        ':firstname' => $user->getFirstname(),
        ':mail' => $user->getMail(),
        ':password' => $user->getPassword(),
        ':role' => $role,
    ]);
   
    echo "Utilisateur enregistré avec succès dans la base de données.";
} catch (PDOException $e) {
  
    echo "Erreur lors de l'enregistrement de l'utilisateur : " . $e->getMessage();
}
}

//function getUser, allUser, deleteUser, editUserRole

}