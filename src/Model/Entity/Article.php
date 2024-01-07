<?php

namespace App\Model\Entity;

class Article
{
    private ?int $id = null;
    private ?string $title = null;
    private \DateTime $date;
    private $updateDate = null;
    private ?string $content = null;
    private ?string $chapo = null;
    private ?int $userId = null;
    private ?string $slug = null;
    
    // Constructeur de la classe
    public function __construct(array $datas = [])
    {
        $this->date = new \DateTime();
       
        foreach ($datas as $attr => $value) {
            $method = "set" . ucfirst($attr);
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }
// j'ai mis dans la méthode slug le title et l'id, est ce la bonne pratique? 
    public function generateSlug(): void
    {

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title . '-' . $this->id)));
        $this->slug = $slug;
    }

    // Méthodes à mettre en place (get, set etc..) de connexion
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
        $this->generateSlug();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDate($date): void
{
  
    if (!$this->date) {
        $this->date = $date;
    }
}
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setUpdateDate($updateDate): void
    {
        $this->updateDate = $updateDate;
    }
    public function getUpdateDate(): \DateTime
    {
        return $this->updateDate;
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


    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
