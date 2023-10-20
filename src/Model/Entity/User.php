<?php

namespace App\Model\Entity;



class User
{
    private ?int $id = null;
    private ?string $lastname = null;
    private ?string $firstname = null;
    private ?string $mail = null;
    private ?string $password = null;
    private ?array $role = [];
    private $creationDate;

    const ROLES = [
        1 => 'member',
        2 => 'admin'
    ];

    public function __construct(array $datas = [])
    {
        $this->creationDate = new \DateTime();

        foreach ($datas as $attr => $value) {
            $method = "set" . ucfirst($attr);
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }
    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }


    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    // Permet d'ajouter un rôle à l'utilisateur
    public function addRole(int $role): void
    {
        $this->role[] = $role;
        $this->role = array_filter($this->role);
    }

    // Renvoie la chaîne de caractères représentant le rôle actuel de l'utilisateur
    public function getRoles(): array
    {
        $roleStrings = [];
        foreach ($this->role as $role) {
            if (isset(self::ROLES[$role])) {
                $roleStrings[] = self::ROLES[$role];
            }
        }
        return $roleStrings;
    }
    public function setRoles(array $role): void
    {
        $this->role = $role;
    }
}
