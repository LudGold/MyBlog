<?php
namespace App\Model\Entity;

class User {
    private ?int $id = null;
    private ?string $username = null;
    private ?string $firstname = null;
    private ?string $mail = null;
    private ?string $password = null;
    private ?array $role = [];
    private $creationDate;
    
    const ROLES = [
        1 => 'member',
        2 => 'admin'  
    ];

    public function __construct() {
        $this->creationDate = new \DateTime();
    }
    
    // Méthodes à mettre en place (getters, setters, etc.)
    
    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function getFirstname(): ?string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void {
        $this->firstname = $firstname;
    }

    public function getMail(): ?string {
        return $this->mail;
    }

    public function setMail(string $mail): void {
        $this->mail = $mail;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    // Permet d'ajouter un rôle à l'utilisateur
    public function addRole(int $role): void {
        $this->role[] = $role;
    }

    // Renvoie la chaîne de caractères représentant le rôle actuel de l'utilisateur
    public function getRoles(): array {
        $roleStrings = [];
        foreach ($this->role as $role) {
            if (isset(self::ROLES[$role])) {
                $roleStrings[] = self::ROLES[$role];
            }
        }
        return $roleStrings;
    }
}
