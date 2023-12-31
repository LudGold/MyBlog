<?php

namespace App\Model\Entity;

class User
{
    private ?int $id = null;
    private ?string $lastname = null;
    private ?string $firstname = null;
    private ?string $mail = null;
    private ?string $password = null;
    private $role = [];
    private $creationDate;
    private ?string $registrationToken = null; 
    private ?string $resetToken = null;
    private ?bool $isConfirmed;

    const ROLES = [
        1 => 'member',
        2 => 'admin',
    ];

    public function __construct(array $datas = [])
    {
        $this->creationDate = new \DateTime();
        $this->isConfirmed = false;
        $this->registrationToken = bin2hex(random_bytes(32));
        $this->role = self::ROLES[1];
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
        return array_unique([$this->role, self::ROLES[1]]);
    }
    public function setRoles($role): void
    {
        $this->role = json_decode($role);
    }
    public function setRegistrationToken(?string $registrationToken): void
    {
        $this->registrationToken = $registrationToken;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    public function getRegistrationToken(): ?string
    {
        return $this->registrationToken;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }
    public function getFullName(): ?string
{
    return $this->firstname . ' ' . $this->lastname;
}
}
