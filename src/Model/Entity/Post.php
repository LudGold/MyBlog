<?php
namespace App\Model\Entity;

class Post {
    private ?int $id = null;
    private ?string $title = null;
    private \DateTime $date;
    private \DateTime $updateDate;
    private ?string $content = null;
    private ?string $chapo = null;
    private ?int $userId = null;

    // Constructeur de la classe
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    // Méthodes à mettre en place (get, set etc..) de connexion
    public function getId(): ?int {
        return $this->id;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    } 
    
    public function setTitle(string $title): void
    {
        $this->title = $title; 
    }

    public function setUpdateDate(\DateTime $updateDate): void
    {
        $this->updateDate = $updateDate; 
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setChapo(string $chapo): void
    {
        $this->chapo = $chapo;
    }

    public function getChapo(): ?string
    {
        return $this->chapo;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
